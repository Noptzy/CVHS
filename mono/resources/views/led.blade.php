<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT LED Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 font-sans text-gray-800">
    <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Navbar -->
        <section class="navbar bg-gray-800 text-white p-4 text-center rounded-t-lg">
            <h1 class="text-2xl font-bold tracking-wider">IoT LED Control</h1>
        </section>

        <!-- LEDs Section -->
        <section class="leds grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 my-8">
            <!-- LED 1 -->
            <div class="led bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <img src="images/led-off.png" alt="" class="led-image mx-auto w-24 h-24" id="rumahLedImage" />
                <p class="led-location-text mt-4 text-center font-semibold text-gray-600">
                    Lampu Rumah
                </p>
                <div class="flex justify-center mt-4">
                    <button
                        class="led-submit bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition"
                        id="ledRumah" onclick="toggleLed('rumah')">
                        TURN ON
                    </button>
                </div>
            </div>

            <!-- LED 2 -->
            <div class="led bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <img src="images/led-off.png" alt="" class="led-image mx-auto w-24 h-24"
                    id="tamanSatuLedImage" />
                <p class="led-location-text mt-4 text-center font-semibold text-gray-600">
                    Lampu Taman Satu
                </p>
                <div class="flex justify-center mt-4">
                    <button
                        class="led-submit bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition"
                        id="ledTamanSatu" onclick="toggleLed('tamanSatu')">
                        TURN ON
                    </button>
                </div>
            </div>

            <!-- LED 3 -->
            <div class="led bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
                <img src="images/led-off.png" alt="" class="led-image mx-auto w-24 h-24"
                    id="tamanDuaLedImage" />
                <p class="led-location-text mt-4 text-center font-semibold text-gray-600">
                    Lampu Taman Dua
                </p>
                <div class="flex justify-center mt-4">
                    <button
                        class="led-submit bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition"
                        id="ledTamanDua" onclick="toggleLed('tamanDua')">
                        TURN ON
                    </button>
                </div>
            </div>
        </section>

        <!-- Distance Section -->
        <section class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-center">Ultrasonic Sensor</h2>
            <div class="text-center">
                <p class="text-4xl font-bold text-indigo-600" id="distanceValue">-- cm</p>
                <p class="text-gray-600 mt-2">Distance</p>
            </div>
        </section>
    </div>

    <script>
        const endpoint = "http://192.168.100.32"; // Ganti dengan IP ESP8266 Anda

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
    </script>
</body>

</html>
 