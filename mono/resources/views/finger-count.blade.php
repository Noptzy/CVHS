<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finger Count</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/handpose"></script>
    <style>
        video,
        canvas {
            width: 100%;
            max-width: 480px;
        }
    </style>
</head>

<body>
    <h1>Finger Count with TensorFlow.js</h1>
    <video id="video" autoplay></video>
    <canvas id="output"></canvas>
    <p id="status">Detecting...</p>
    <script>
        const video = document.getElementById("video");
        const canvas = document.getElementById("output");
        const ctx = canvas.getContext("2d");
        const status = document.getElementById("status");

        async function setupCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                console.error("MediaDevices API not available in this browser.");
                throw new Error('Browser does not support getUserMedia');
            }

            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                });
                video.srcObject = stream;
                return new Promise((resolve) => {
                    video.onloadeddata = () => {
                        resolve(video);
                    };
                });
            } catch (error) {
                console.error("Error accessing camera:", error);
                throw new Error('Failed to access camera. Check browser permissions.');
            }
        }


        async function main() {
            await setupCamera();
            const model = await handpose.load();
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            async function detect() {
                const predictions = await model.estimateHands(video);
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (predictions.length > 0) {
                    const landmarks = predictions[0].landmarks;

                    // Draw detected landmarks
                    for (let i = 0; i < landmarks.length; i++) {
                        const [x, y, z] = landmarks[i];
                        ctx.beginPath();
                        ctx.arc(x, y, 5, 0, 2 * Math.PI);
                        ctx.fillStyle = "red";
                        ctx.fill();
                    }

                    const fingerCount = getFingerStates(landmarks).reduce((sum, val) => sum + val, 0);
                    status.innerText = `Detected Fingers: ${fingerCount}`;
                }
                requestAnimationFrame(detect);
            }

            function getFingerStates(landmarks) {
                const fingerTips = [8, 12, 16, 20];
                const base = [6, 10, 14, 18];
                return fingerTips.map((tip, index) =>
                    landmarks[tip][1] < landmarks[base[index]][1] ? 1 : 0
                );
            }

            detect();
        }
        main();
    </script>
</body>

</html>
