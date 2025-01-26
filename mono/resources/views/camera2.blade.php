<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camera Control - CVHS</title>
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

        .camera-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
        }

        #video {
            transform: scaleX(-1);
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            width: 100%;
            max-width: 640px;
        }

        .status-card {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        #notification {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            display: none;
            z-index: 1000;
        }

        .gesture-guide {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .gesture-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .gesture-icon {
            margin-right: 15px;
            font-size: 24px;
            color: var(--primary-color);
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
            <a href="/led"><i class="fas fa-lightbulb"></i>Manual</a>
            <a href="/camera"><i class="fas fa-camera"></i>Camera</a>
        </div>
    </div>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="camera-container text-center">
                    <h3 class="mb-4">Hand Gesture Control</h3>
                    <video id="video" autoplay playsinline></video>
                    <div class="status-card mt-3">
                        <i class="fas fa-hand-paper me-2"></i>
                        <span id="status">Status: Waiting for gesture...</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="gesture-guide">
                    <h4 class="mb-3">Gesture Guide</h4>
                    <div class="gesture-item">
                        <i class="fas fa-hand-point-up gesture-icon"></i>
                        <div>
                            <h6>One Finger</h6>
                            <small>Controls Dapur LED</small>
                        </div>
                    </div>
                    <div class="gesture-item">
                        <i class="fas fa-peace gesture-icon"></i>
                        <div>
                            <h6>Two Fingers</h6>
                            <small>Controls Tamu LED</small>
                        </div>
                    </div>
                    <div class="gesture-item">
                        <i class="fas fa-hand-paper gesture-icon"></i>
                        <div>
                            <h6>Three Fingers</h6>
                            <small>Controls Makan LED</small>
                        </div>
                    </div>
                    <div class="gesture-item">
                        <i class="fas fa-hand-rock gesture-icon"></i>
                        <div>
                            <h6>Fist</h6>
                            <small>Turn Off All LEDs</small>
                        </div>
                    </div>
                    <div class="gesture-item">
                        <i class="fas fa-hand-spock gesture-icon"></i>
                        <div>
                            <h6>All Fingers</h6>
                            <small>Turn On All LEDs</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="notification"></div>


    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>

    <script>
        const videoElement = document.getElementById('video');
        const statusElement = document.getElementById('status');
        const notificationElement = document.getElementById('notification');
        const apiEndpoint = "http://192.168.100.32"; // Ganti dengan IP NodeMCU

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
