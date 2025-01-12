const videoElement = document.getElementById('webcam');
const canvasElement = document.getElementById('canvas');
const canvasCtx = canvasElement.getContext('2d');
const fingerCountElement = document.getElementById('fingerCount');
const lights = [
    document.getElementById('light1'),
    document.getElementById('light2'),
    document.getElementById('light3')
];

function onResults(results) {
    canvasElement.width = videoElement.videoWidth;
    canvasElement.height = videoElement.videoHeight;

    canvasCtx.save();
    canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
    canvasCtx.drawImage(results.image, 0, 0, canvasElement.width, canvasElement.height);

    if (results.multiHandLandmarks) {
        for (const landmarks of results.multiHandLandmarks) {
            drawConnectors(canvasCtx, landmarks, HAND_CONNECTIONS, {
                color: '#00FF00',
                lineWidth: 5
            });
            drawLandmarks(canvasCtx, landmarks, {
                color: '#FF0000',
                lineWidth: 2
            });

            const fingers = countFingers(landmarks);
            fingerCountElement.textContent = fingers;
            updateLights(fingers);
        }
    }
    canvasCtx.restore();
}

function countFingers(landmarks) {
    let fingers = 0;
    
    // Thumb
    if (landmarks[4].x < landmarks[3].x) fingers++;
    
    // Other fingers
    const fingerTips = [8, 12, 16, 20];
    const fingerMids = [6, 10, 14, 18];
    
    fingerTips.forEach((tip, index) => {
        if (landmarks[tip].y < landmarks[fingerMids[index]].y) fingers++;
    });

    return fingers;
}

function updateLights(fingerCount) {
    lights.forEach((light, index) => {
        if (fingerCount > index) {
            light.classList.add('active');
            light.querySelector('.status').textContent = 'ON';
        } else {
            light.classList.remove('active');
            light.querySelector('.status').textContent = 'OFF';
        }
    });
}

// Initialize MediaPipe Hands
const hands = new Hands({
    locateFile: (file) => {
        return `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`;
    }
});

hands.setOptions({
    maxNumHands: 1,
    modelComplexity: 1,
    minDetectionConfidence: 0.5,
    minTrackingConfidence: 0.5
});

hands.onResults(onResults);

// Start camera on page load
async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        videoElement.srcObject = stream;
        
        const camera = new Camera(videoElement, {
            onFrame: async () => {
                await hands.send({image: videoElement});
            },
            width: 1280,
            height: 720
        });
        camera.start();
    } catch (err) {
        console.error("Error accessing webcam:", err);
    }
}

window.addEventListener('load', startCamera);