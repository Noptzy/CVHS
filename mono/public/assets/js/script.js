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
                    ledButton.innerHTML = "OFF";
                    ledImage.src = "images/led-on.png";
                } else {
                    ledButton.innerHTML = "ON";
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
