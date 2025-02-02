// toggle sidebar
const body = document.querySelector("body"),
      sidebar = body.querySelector(".sidebar"),
      toggle = body.querySelector(".toggle");

      toggle.addEventListener("click", () => {
        sidebar.classList.toggle("close");
      });


    //   toggle dropdown profile
    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }
      // Close dropdown when clicking outside
      window.onclick = function(event) {
        if (!event.target.matches('.profile-img')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    // toggle led dan ultrasonic
    const endpoint = "http://192.168.100.32"; 

        // Fetch dengan retry logic
        async function fetchWithRetry(url, options = {}, retries = 3) {
            for (let attempt = 0; attempt < retries; attempt++) {
                try {
                    const response = await fetch(url, options);
                    if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
                    return await response.text();
                } catch (error) {
                    console.error(`Attempt ${attempt + 1} failed:`, error);
                    if (attempt === retries - 1) throw error;
                }
            }
        }

        // Update status LED
        async function updateLedStatus(room) {
            try {
                const result = await fetchWithRetry(`${endpoint}/${room}`);
                const ledButton = document.getElementById(`led${capitalize(room)}`);
                const ledImage = document.getElementById(`${room}LedImage`);
                if (result === "ON") {
                    ledButton.innerHTML = "TURN OFF";
                    ledImage.src = "images/led-on.png";
                } else {
                    ledButton.innerHTML = "TURN ON";
                    ledImage.src = "images/led-off.png";
                }
            } catch (error) {
                console.error(`Error updating ${room} LED:`, error);
            }
        }

        // Toggle LED
        async function toggleLed(room) {
            try {
                await fetchWithRetry(`${endpoint}/${room}`, {
                    method: "POST"
                });
                updateLedStatus(room);
            } catch (error) {
                console.error(`Error toggling ${room} LED:`, error);
            }
        }

        // Update jarak
        async function updateDistance() {
            try {
                const distance = await fetchWithRetry(`${endpoint}/distance`);
                document.getElementById('distanceValue').textContent = `${distance} cm`;
            } catch (error) {
                console.error("Error fetching distance:", error);
                document.getElementById('distanceValue').textContent = "-- cm";
            }
        }

        // Utility: Capitalize first letter
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Inisialisasi
        function init() {
            // Nama endpoint sesuai backend
            ['rumah', 'tamanSatu', 'tamanDua'].forEach(updateLedStatus);
            setInterval(updateDistance, 1000); // Update jarak setiap 1 detik
        }

        init();


// toggle kamera
        const videoElement = document.getElementById('video');
        const canvasElement = document.getElementById('canvas');
        const canvasCtx = canvasElement.getContext('2d');
        const statusElement = document.getElementById('status');
        const notificationElement = document.getElementById('notification');
        const apiEndpoint = "http://192.168.100.32";

        const fingerStates = {
            "01000": "/rumah",
            "01100": "/tamanSatu",
            "01110": "/tamanDua",
            "01111": "/on_all",
            "11111": "/off_all",
        };

        const hands = new Hands({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
        });

        hands.setOptions({
            maxNumHands: 2,
            modelComplexity: 1,
            minDetectionConfidence: 0.5,
            minTrackingConfidence: 0.5,
        });

        hands.onResults((results) => {
            // Clear canvas
            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);

            // Draw video frame
            canvasCtx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);

            // Process each detected hand
            if (results.multiHandLandmarks) {
                for (let i = 0; i < results.multiHandLandmarks.length; i++) {
                    const landmarks = results.multiHandLandmarks[i];
                    const handedness = results.multiHandedness[i].label;

                    // Draw landmarks and connections
                    drawConnectors(
                        canvasCtx,
                        landmarks,
                        HAND_CONNECTIONS, {
                            color: handedness === 'Left' ? '#FF0000' : '#00FF00'
                        }
                    );

                    drawLandmarks(
                        canvasCtx,
                        landmarks, {
                            color: handedness === 'Left' ? '#FF0000' : '#00FF00',
                            fillColor: 'white'
                        }
                    );

                    // Gesture recognition
                    const gesture = getFingerState(landmarks);

                    if (gesture in fingerStates) {
                        debounceGesture(fingerStates[gesture]);
                        statusElement.textContent =
                            `Status: ${gesture === "11111" ? "Mematikan Semua Lampu" : gesture === "01111" ? "Menyalakan Semua Lampu" : fingerStates[gesture]}`;
                    } else {
                        statusElement.textContent = "Status: No valid gesture detected";
                    }
                }
            }
        });

        function getFingerState(landmarks) {
            const ujungJari = [8, 12, 16, 20];
            const bases = [6, 10, 14, 18];
            const state = [];

            const thumbTip = landmarks[4];
            const thumbBase = landmarks[3];
            state.push(thumbTip.x < thumbBase.x ? 1 : 0);

            for (let i = 0; i < ujungJari.length; i++) {
                state.push(landmarks[ujungJari[i]].y < landmarks[bases[i]].y ? 1 : 0);
            }

            return state.join("");
        }

        let debounceTimeout;

        function debounceGesture(route, delay = 500) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => sendGestureCommand(route), delay);
        }

        async function sendGestureCommand(route) {
            try {
                const url = `${apiEndpoint}${route}`;
                console.log(`Sending command to: ${url}`);
                const response = await fetch(url, {
                    method: "POST"
                });

                if (response.ok) {
                    const message = await response.text();
                    showNotification(`Success: ${message}`);
                } else {
                    showNotification(`Failed: ${response.statusText}`, true);
                }
            } catch (error) {
                showNotification(`Error: ${error.message}`, true);
            }
        }

        function showNotification(message, isError = false) {
            notificationElement.textContent = message;
            notificationElement.style.backgroundColor = isError ? "#ff4c4c" : "#61dafb";
            notificationElement.style.display = "block";

            setTimeout(() => {
                notificationElement.style.display = "none";
            }, 3000);
        }

        const camera = new Camera(videoElement, {
            onFrame: async () => {
                await hands.send({
                    image: videoElement
                });
            },
            width: 640,
            height: 480,
        });
        camera.start();
