<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hand Gesture Detection with LED Mapping</title>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }

        .container {
            position: relative;
            margin: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        canvas {
            position: absolute;
            border-radius: 10px;
        }

        video {
            transform: scaleX(-1);
            border-radius: 10px;
            max-width: 100%;
        }

        #status {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: var(--light);
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .back-btn {
            text-decoration: none;
            color: var(--light);
            background-color: var(--accent);
            padding: 12px 24px;
            border-radius: 25px;
            margin-bottom: 20px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .back-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <a href="/dashboard" class="back-btn">Dashboard</a>
    <div id="status">Status: Waiting...</div>
    <div class="container">
        <video id="video" autoplay playsinline></video>
        <canvas id="output"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>

    <script>
        const videoElement = document.getElementById('video');
        const canvasElement = document.getElementById('output');
        const canvasCtx = canvasElement.getContext('2d');
        const statusElement = document.getElementById('status');

        const apiEndpoint = "http://127.0.0.1:8000/api/leds"; // Replace with your Laravel API URL

        // Define finger state mappings
        const fingerStates = {
            "01000": "red",
            "01100": "yellow",
            "01110": "green",
            "01111": "all",
            "11111": "off"
        };

        const hands = new Hands({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
        });

        hands.setOptions({
            maxNumHands: 1,
            modelComplexity: 1,
            minDetectionConfidence: 0.5,
            minTrackingConfidence: 0.5
        });

        hands.onResults((results) => {
            canvasElement.width = videoElement.videoWidth;
            canvasElement.height = videoElement.videoHeight;

            canvasCtx.save();
            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
            canvasCtx.drawImage(results.image, 0, 0, canvasElement.width, canvasElement.height);

            if (results.multiHandLandmarks) {
                for (const landmarks of results.multiHandLandmarks) {
                    drawConnectors(canvasCtx, landmarks, Hands.HAND_CONNECTIONS, { color: '#00FF00', lineWidth: 4 });
                    drawLandmarks(canvasCtx, landmarks, { color: '#FF0000', lineWidth: 2 });

                    const fingerState = getFingerState(landmarks);
                    const mappedState = fingerStates[fingerState];
                    
                    if (mappedState) {
                        postState(mappedState);
                        statusElement.textContent = `Status: ${mappedState}`;
                    } else {
                        statusElement.textContent = "Status: No valid gesture detected";
                    }
                }
            }
            canvasCtx.restore();
        });

        function getFingerState(landmarks) {
            const tips = [8, 12, 16, 20]; 
            const bases = [6, 10, 14, 18];
            const state = [];

            const thumbTip = landmarks[4];
            const thumbBase = landmarks[2];
            state.push(thumbTip.x < landmarks[3].x && thumbTip.y < thumbBase.y ? 1 : 0);

            // Other fingers
            for (let i = 0; i < tips.length; i++) {
                const tip = landmarks[tips[i]];
                const base = landmarks[bases[i]];
                state.push(tip.y < base.y ? 1 : 0);
            }

            return state.join("");
        }

        async function postState(state) {
            try {
                const response = await fetch(apiEndpoint, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ state })
                });

                if (response.ok) {
                    console.log(`Sent state: ${state}`);
                } else {
                    console.error(`Failed to send state: ${response.status}`);
                }
            } catch (error) {
                console.error(`Error posting state: ${error}`);
            }
        }

        const camera = new Camera(videoElement, {
            onFrame: async () => {
                await hands.send({ image: videoElement });
            },
            width: 1280,
            height: 720
        });

        camera.start();
    </script>
</body>
</html>
