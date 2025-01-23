<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hand Gesture Controlled LED</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #282c34;
            color: white;
        }
        #video {
            transform: scaleX(-1);
            border: 2px solid #61dafb;
            border-radius: 10px;
        }
        #status {
            margin-top: 20px;
            font-size: 20px;
        }
        #notification {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: #61dafb;
            color: black;
            border-radius: 5px;
            font-size: 16px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Hand Gesture Controlled LED</h1>
    <video id="video" autoplay playsinline></video>
    <div id="status">Status: Waiting for gesture...</div>
    <div id="notification"></div>

    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>

    <script>
        const videoElement = document.getElementById('video');
        const statusElement = document.getElementById('status');
        const notificationElement = document.getElementById('notification');
        const apiEndpoint = "http://192.168.47.80"; // Ganti dengan IP NodeMCU

        const fingerStates = {
            "01000": "/dapur",  
            "01100": "/tamu",   
            "01110": "/makan",  
            "11111": "/off_all", 
            "01111": "/on_all",  
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
            if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                const landmarks = results.multiHandLandmarks[0];
                const gesture = getFingerState(landmarks);

                if (gesture in fingerStates) {
                    debounceGesture(fingerStates[gesture]);
                    statusElement.textContent = `Status: ${gesture === "11111" ? "Turning OFF all lights" : gesture === "01111" ? "Turning ON all lights" : fingerStates[gesture]}`;
                } else {
                    statusElement.textContent = "Status: No valid gesture detected";
                }
            }
        });

        function getFingerState(landmarks) {
            const tips = [8, 12, 16, 20];
            const bases = [6, 10, 14, 18];
            const state = [];

            const thumbTip = landmarks[4];
            const thumbBase = landmarks[3];
            state.push(thumbTip.x < thumbBase.x ? 1 : 0);

            for (let i = 0; i < tips.length; i++) {
                state.push(landmarks[tips[i]].y < landmarks[bases[i]].y ? 1 : 0);
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
                const response = await fetch(url, { method: "POST" });

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
                await hands.send({ image: videoElement });
            },
            width: 640,
            height: 480,
        });

        camera.start();
    </script>
</body>
</html>
