<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Camera Control - CVHS</title>
    
    <!-- Include existing CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
    
    <!-- MediaPipe Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands@0.4.1646424915/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>

    <style>
        .camera-page {
            padding-top: 80px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .camera-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        #webcam, #canvas {
            width: 100%;
            border-radius: 10px;
        }

        #canvas {
            position: absolute;
            left: 0;
            top: 20px;
        }

        .controls-panel {
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        .light-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .light-card {
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .light-card.active {
            background: #4CAF50;
            color: white;
        }

        .led-image {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            padding: 10px 20px;
            border-radius: 50px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #333;
        }

        .camera-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #666;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <a href="/dashboard" class="back-button">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>

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
                
                <div class="light-grid">
                    <div class="light-card" id="light1">
                        <img id="dapurLedImage" src="images/led-off.png" class="led-image" alt="LED Dapur">
                        <h5 class="mt-2">Lampu Dapur</h5>
                        <p class="mb-0">Status: <span class="status">OFF</span></p>
                        <button id="ledDapur" onclick="toggleDapurLed()" class="btn btn-sm btn-primary mt-2">TURN ON</button>
                    </div>
                    <div class="light-card" id="light2">
                        <img id="tamuLedImage" src="images/led-off.png" class="led-image" alt="LED Tamu">
                        <h5 class="mt-2">Lampu Tamu</h5>
                        <p class="mb-0">Status: <span class="status">OFF</span></p>
                        <button id="ledTamu" onclick="toggleTamuLed()" class="btn btn-sm btn-primary mt-2">TURN ON</button>
                    </div>
                    <div class="light-card" id="light3">
                        <img id="makanLedImage" src="images/led-off.png" class="led-image" alt="LED Makan">
                        <h5 class="mt-2">Lampu Makan</h5>
                        <p class="mb-0">Status: <span class="status">OFF</span></p>
                        <button id="ledMakan" onclick="toggleMakanLed()" class="btn btn-sm btn-primary mt-2">TURN ON</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/js/camera-control.js"></script>

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
</body>
</html>