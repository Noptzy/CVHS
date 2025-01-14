<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

</head>

<body>
    <!-- Header -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="/" class="logo d-flex align-items-center">
                <h1 class="sitename">Computer Vision for Hearing Solutions</h1>
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/dashboard" class="active">Dashboard</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="camera-page">
        <div class="container">
            <div class="camera-container">
                <div class="camera-loading" id="loadingIndicator">
                    <div class="spinner-border loading-spinner" role="status"></div>
                    <p>Starting camera...</p>
                </div>
                <video id="webcam" autoplay playsinline style="display: none;"></video>
                <canvas id="canvas"></canvas>
            </div>

            <div class="controls-panel">
                <h3 class="text-center mb-4">Gesture Control Panel</h3>
                <div class="finger-count text-center mb-4">
                    Detected Fingers: <span id="fingerCount">0</span>
                </div>

                <div class="row g-4">
                    <!-- Light Cards -->
                    <div class="col-md-4">
                        <div class="light-card text-center p-3 border rounded">
                            <img id="dapurLedImage" src="images/led-off.png" class="led-image mb-2" alt="LED Dapur">
                            <h5>Lampu Dapur</h5>
                            <p>Status: <span class="status">OFF</span></p>
                            <button id="ledDapur" onclick="toggleDapurLed()" class="btn btn-sm btn-primary">TURN ON</button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="light-card text-center p-3 border rounded">
                            <img id="tamuLedImage" src="images/led-off.png" class="led-image mb-2" alt="LED Tamu">
                            <h5>Lampu Tamu</h5>
                            <p>Status: <span class="status">OFF</span></p>
                            <button id="ledTamu" onclick="toggleTamuLed()" class="btn btn-sm btn-primary">TURN ON</button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="light-card text-center p-3 border rounded">
                            <img id="makanLedImage" src="images/led-off.png" class="led-image mb-2" alt="LED Makan">
                            <h5>Lampu Makan</h5>
                            <p>Status: <span class="status">OFF</span></p>
                            <button id="ledMakan" onclick="toggleMakanLed()" class="btn btn-sm btn-primary">TURN ON</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer id="footer" class="footer dark-background">
        <div class="container footer-top py-4">
            <div class="row">
                <div class="col-lg-4">
                    <a href="/" class="logo d-flex align-items-center">
                        <span class="sitename">CVHS</span>
                    </a>
                </div>
                <div class="col-lg-8 text-center text-lg-end">
                    <p>&copy; 2025 CVHS. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const videoElement = document.getElementById('webcam');
        const loadingIndicator = document.getElementById('loadingIndicator');

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                videoElement.srcObject = stream;
                videoElement.style.display = 'block';
                loadingIndicator.style.display = 'none';

                videoElement.onloadedmetadata = () => {
                    const camera = new Camera(videoElement, {
                        onFrame: async () => {
                            await hands.send({image: videoElement});
                        },
                        width: 1280,
                        height: 720
                    });
                    camera.start();
                };
            } catch (err) {
                console.error("Error accessing webcam:", err);
                loadingIndicator.innerHTML = `<p class="text-danger">Error accessing camera: ${err.message}</p>`;
            }
        }

        startCamera();
    });

    const endpoint = "http://192.168.100.32";

    function updateLights(fingerCount) {
        if (fingerCount >= 1) toggleDapurLed();
        if (fingerCount >= 2) toggleTamuLed();
        if (fingerCount >= 3) toggleMakanLed();
    }

    function checkAndUpdateLedStatus() {
        getDapurLed();
        getTamuLed();
        getMakanLed();
    }

    function getDapurLed() {
        fetch(`${endpoint}/dapur`, { method: "GET" })
            .then(response => response.text())
            .then(result => {
                updateLightUI('dapur', result);
            })
            .catch(error => console.error("Error:", error));
    }

    function getTamuLed() {
        fetch(`${endpoint}/tamu`, { method: "GET" })
            .then(response => response.text())
            .then(result => {
                updateLightUI('tamu', result);
            })
            .catch(error => console.error("Error:", error));
    }

    function getMakanLed() {
        fetch(`${endpoint}/makan`, { method: "GET" })
            .then(response => response.text())
            .then(result => {
                updateLightUI('makan', result);
            })
            .catch(error => console.error("Error:", error));
    }

    function updateLightUI(type, status) {
        const button = document.getElementById(`led${type.charAt(0).toUpperCase() + type.slice(1)}`);
        const image = document.getElementById(`${type}LedImage`);
        const card = button.closest('.light-card');
        
        if (status === "ON") {
            button.innerHTML = "TURN OFF";
            image.src = "images/led-on.png";
            card.classList.add('active');
            card.querySelector('.status').textContent = 'ON';
        } else {
            button.innerHTML = "TURN ON";
            image.src = "images/led-off.png";
            card.classList.remove('active');
            card.querySelector('.status').textContent = 'OFF';
        }
    }

    function toggleDapurLed() {
        fetch(`${endpoint}/dapur`, { method: "POST" })
            .then(() => checkAndUpdateLedStatus())
            .catch(error => console.error("Error:", error));
    }

    function toggleTamuLed() {
        fetch(`${endpoint}/tamu`, { method: "POST" })
            .then(() => checkAndUpdateLedStatus())
            .catch(error => console.error("Error:", error));
    }

    function toggleMakanLed() {
        fetch(`${endpoint}/makan`, { method: "POST" })
            .then(() => checkAndUpdateLedStatus())
            .catch(error => console.error("Error:", error));
    }

    // Initialize status checking
    document.addEventListener('DOMContentLoaded', () => {
        checkAndUpdateLedStatus();
        setInterval(checkAndUpdateLedStatus, 5000); // Check every 5 seconds
    });
</script>

</html>
