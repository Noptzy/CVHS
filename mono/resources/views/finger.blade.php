<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hand Landmark Detection</title>
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: roboto;
            margin: 2em;
            color: #3d3d3d;
            --mdc-theme-primary: #007f8b;
            --mdc-theme-on-primary: #f1f3f4;
        }

        h1 {
            color: #007f8b;
        }

        h2 {
            clear: both;
        }

        video {
            clear: both;
            display: block;
            transform: rotateY(180deg);
        }

        section {
            opacity: 1;
            transition: opacity 500ms ease-in-out;
        }

        .videoView,
        .detectOnClick {
            position: relative;
            float: left;
            width: 48%;
            margin: 2% 1%;
            cursor: pointer;
        }

        .canvas {
            z-index: 1;
            position: absolute;
            pointer-events: none;
        }

        .output_canvas {
            transform: rotateY(180deg);
        }

        .detectOnClick {
            z-index: 0;
        }

        .detectOnClick img {
            width: 100%;
        }

        .mdc-button--raised {
            background-color: #007f8b;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Hand Landmark Detection using MediaPipe</h1>

    <section id="demos" class="invisible">
        <h2>Demo: Detecting Images</h2>
        <p><b>Click on an image below</b> to see the key landmarks of the hands.</p>
        
        <div class="detectOnClick">
            <img src="https://assets.codepen.io/9177687/hand-ge4ca13f5d_1920.jpg" width="100%" crossorigin="anonymous" title="Click to get detection!" />
        </div>
        <div class="detectOnClick">
            <img src="https://assets.codepen.io/9177687/couple-gb7cb5db4c_1920.jpg" width="100%" crossorigin="anonymous" title="Click to get detection!" />
        </div>

        <h2>Demo: Webcam continuous hands landmarks detection</h2>
        <p>Hold your hand in front of your webcam to get real-time hand landmarker detection.</br>Click <b>enable webcam</b> below and grant access to the webcam if prompted.</p>

        <div id="liveView" class="videoView">
            <button id="webcamButton" class="mdc-button mdc-button--raised">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label">ENABLE WEBCAM</span>
            </button>
            <div style="position: relative;">
                <video id="webcam" style="position: absolute;" autoplay playsinline></video>
                <canvas class="output_canvas" id="output_canvas" style="position: absolute; left: 0px; top: 0px;"></canvas>
            </div>
        </div>
    </section>

    <script type="module">
        import { HandLandmarker, FilesetResolver } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";

        const demosSection = document.getElementById("demos");

        let handLandmarker = undefined;
        let runningMode = "IMAGE";
        let webcamRunning = false;

        const createHandLandmarker = async () => {
            const vision = await FilesetResolver.forVisionTasks("https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm");
            handLandmarker = await HandLandmarker.createFromOptions(vision, {
                baseOptions: {
                    modelAssetPath: "https://storage.googleapis.com/mediapipe-models/hand_landmarker/hand_landmarker/float16/1/hand_landmarker.task",
                    delegate: "GPU"
                },
                runningMode: runningMode,
                numHands: 2
            });
            demosSection.classList.remove("invisible");
        };
        createHandLandmarker();

        const imageContainers = document.getElementsByClassName("detectOnClick");
        for (let i = 0; i < imageContainers.length; i++) {
            imageContainers[i].children[0].addEventListener("click", handleClick);
        }

        async function handleClick(event) {
            if (!handLandmarker) {
                console.log("Wait for handLandmarker to load before clicking!");
                return;
            }

            if (runningMode === "VIDEO") {
                runningMode = "IMAGE";
                await handLandmarker.setOptions({ runningMode: "IMAGE" });
            }

            const allCanvas = event.target.parentNode.getElementsByClassName("canvas");
            for (let i = allCanvas.length - 1; i >= 0; i--) {
                const n = allCanvas[i];
                n.parentNode.removeChild(n);
            }

            const handLandmarkerResult = handLandmarker.detect(event.target);
            console.log(handLandmarkerResult.handednesses[0][0]);
            const canvas = document.createElement("canvas");
            canvas.setAttribute("class", "canvas");
            canvas.setAttribute("width", event.target.naturalWidth + "px");
            canvas.setAttribute("height", event.target.naturalHeight + "px");
            canvas.style.left = "0px";
            canvas.style.top = "0px";
            canvas.style.width = event.target.width + "px";
            canvas.style.height = event.target.height + "px";

            event.target.parentNode.appendChild(canvas);
            const cxt = canvas.getContext("2d");
            for (const landmarks of handLandmarkerResult.landmarks) {
                drawConnectors(cxt, landmarks, HAND_CONNECTIONS, {
                    color: "#00FF00",
                    lineWidth: 5
                });
                drawLandmarks(cxt, landmarks, {
                    color: "#FF0000",
                    lineWidth: 1
                });
            }
        }

        const video = document.getElementById("webcam");
        const canvasElement = document.getElementById("output_canvas");
        const canvasCtx = canvasElement.getContext("2d");

        const hasGetUserMedia = () => !!navigator.mediaDevices?.getUserMedia;

        if (hasGetUserMedia()) {
            const enableWebcamButton = document.getElementById("webcamButton");
            enableWebcamButton.addEventListener("click", enableCam);
        } else {
            console.warn("getUserMedia() is not supported by your browser");
        }

        function enableCam(event) {
            if (!handLandmarker) {
                console.log("Wait! HandLandmarker not loaded yet.");
                return;
            }

            if (webcamRunning === true) {
                webcamRunning = false;
                event.target.innerText = "ENABLE WEBCAM";
            } else {
                webcamRunning = true;
                event.target.innerText = "DISABLE WEBCAM";
            }

            const constraints = { video: true };
            navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
                video.srcObject = stream;
                video.addEventListener("loadeddata", predictWebcam);
            });
        }

        let lastVideoTime = -1;

        async function predictWebcam() {
            canvasElement.style.width = video.videoWidth + "px";
            canvasElement.style.height = video.videoHeight + "px";
            canvasElement.width = video.videoWidth;
            canvasElement.height = video.videoHeight;

            if (runningMode === "IMAGE") {
                runningMode = "VIDEO";
                await handLandmarker.setOptions({ runningMode: "VIDEO" });
            }

            const startTimeMs = performance.now();
            if (lastVideoTime !== video.currentTime) {
                lastVideoTime = video.currentTime;
                const results = handLandmarker.detectForVideo(video, startTimeMs);

                canvasCtx.save();
                canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);

                if (results.landmarks) {
                    for (const landmarks of results.landmarks) {
                        drawConnectors(canvasCtx, landmarks, HAND_CONNECTIONS, {
                            color: "#00FF00",
                            lineWidth: 5
                        });
                        drawLandmarks(canvasCtx, landmarks, {
                            color: "#FF0000",
                            lineWidth: 2
                        });
                    }
                }

                canvasCtx.restore();
            }

            if (webcamRunning === true) {
                window.requestAnimationFrame(predictWebcam);
            }
        }
    </script>
</body>
</html>
