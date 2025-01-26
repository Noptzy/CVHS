<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LED Control - CVHS</title>
    <link href="images/CVHS.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --background-color: #f0f4f8;
            --sidebar-color: #2c3e50;
            --hover-color: #34495e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            margin-left: 260px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            background: var(--sidebar-color);
            color: white;
            transition: width 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            background: rgba(0,0,0,0.1);
        }

        .sidebar-header img {
            width: 40px;
            margin-right: 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-menu a:hover {
            background: var(--hover-color);
        }

        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .led-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .led-card:hover {
            transform: translateY(-5px);
        }

        .led-button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .led-button:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }

        .distance-card {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="images/CVHS.png" alt="CVHS Logo">
            <h4>CVHS Dashboard</h4>
        </div>
        <div class="sidebar-menu">
            <a href="/dashboard"><i class="fas fa-home"></i>Dashboard</a>
            <a href="/led"><i class="fas fa-lightbulb"></i>LED Control</a>
            <a href="/camera"><i class="fas fa-camera"></i>Camera</a>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-4">
            <!-- LED Cards -->
            <div class="col-md-4">
                <div class="led-card p-4 text-center">
                    <img src="images/led-off.png" alt="" class="w-50 mb-3" id="dapurLedImage">
                    <h5>Lampu Ruang Dapur</h5>
                    <button class="led-button mt-3" id="ledDapur" onclick="toggleLed('dapur')">
                        TURN ON
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="led-card p-4 text-center">
                    <img src="images/led-off.png" alt="" class="w-50 mb-3" id="tamuLedImage">
                    <h5>Lampu Ruang Tamu</h5>
                    <button class="led-button mt-3" id="ledTamu" onclick="toggleLed('tamu')">
                        TURN ON
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="led-card p-4 text-center">
                    <img src="images/led-off.png" alt="" class="w-50 mb-3" id="makanLedImage">
                    <h5>Lampu Ruang Makan</h5>
                    <button class="led-button mt-3" id="ledMakan" onclick="toggleLed('makan')">
                        TURN ON
                    </button>
                </div>
            </div>

            <!-- Distance Card -->
            <div class="col-12">
                <div class="distance-card p-4 rounded-lg text-center">
                    <h4 class="mb-3">Ultrasonic Sensor</h4>
                    <h2 id="distanceValue">-- cm</h2>
                </div>
            </div>
        </div>
    </div>

    <script>
        const endpoint = "http://192.168.100.32";

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

        async function toggleLed(room) {
            const button = document.getElementById(`led${capitalize(room)}`);
            button.classList.add('loading');
            button.disabled = true;

            try {
                await fetchWithRetry(`${endpoint}/${room}`, { method: "POST" });
                await updateLedStatus(room);
            } catch (error) {
                console.error(`Error toggling ${room} LED:`, error);
            } finally {
                button.classList.remove('loading');
                button.disabled = false;
            }
        }

        async function updateDistance() {
            try {
                const distance = await fetchWithRetry(`${endpoint}/distance`);
                document.getElementById('distanceValue').textContent = `${distance} cm`;
            } catch (error) {
                console.error("Error fetching distance:", error);
                document.getElementById('distanceValue').textContent = "Error";
            }
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Initial setup
        function init() {
            ['dapur', 'tamu', 'makan'].forEach(updateLedStatus);
            setInterval(updateDistance, 1000);
        }

        init();
    </script>
</body>
</html>