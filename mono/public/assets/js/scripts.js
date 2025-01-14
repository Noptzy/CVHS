//start
document.addEventListener('DOMContentLoaded', function () {
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
            await hands.send({ image: videoElement });
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


// Scroll-to-Top Functionality
const scrollToTopButton = document.querySelector(".scroll-top");

scrollToTopButton.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});
