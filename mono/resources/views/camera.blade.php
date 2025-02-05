<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    {{-- CSS --}}
    <link rel="stylesheet" href="assets/css/style.css">

    {{-- Boxicons CSS --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class="sidebar close">
        <header>
            <div class="logo-text">
                <span class="image">
                    <img src="images/CVHS.png" alt="logo">
                </span>

                <div class="header header-text">
                    <span class="app-name text">CVHS</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <li class="dashboard">
                    <h3 class="text nav-text">Dashboard</h3>
                </li>
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="/dashboard">
                            <i class='bx bxs-book icon'></i>
                            <span class="text nav-text">Manual</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/camera">
                            <i class='bx bxs-camera icon'></i>
                            <span class="text nav-text">Camera</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- dark mode --}}
            {{-- <div class="button-content">
                <li class="mode">
                    <div class="moon-sun">
                        <i class='bx bxs-moon icon moon'></i>
                        <i class='bx bxs-sun icon sun'></i>
                    </div>
                    <span class="mode-text tex">Dark Mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
        </div> --}}
    </section>
    <nav>
        <div class="profile-section">
            <span class="profile-name">{{ Auth::user()->name }}</span>
            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Profile" class="profile-img"
                onclick="toggleDropdown()">
            <div class="dropdown-menu" id="profileDropdown">
                <a href="/profile/edit"><i class="fas fa-user-edit"></i> Edit Profile</a>
                <a href="/logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    {{-- main content --}}
    <section class="main-content">
        <main>
            <div class="container py-4">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="camera-container text-center">
                            <div id="notification"></div>
                            <h3 class="mb-4">Hand Gesture Control</h3>
                            <!-- Video akan ada di bawah Canvas, sehingga canvas akan menggambar di atasnya -->
                            <div class="video-container">
                                <video id="video" autoplay playsinline></video>
                                <canvas id="canvas" width="640" height="480"></canvas>
                            </div>
                            <div class="status-card mt-3">
                                <i class='bx bxs-hand'></i>
                                <span id="status">Status: Waiting for gesture...</span>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="gesture-guide">
                            <h4 class="mb-3">Gesture Guide</h4>
                            <div class="gesture-item">
                                <div>
                                    <h5>Satu Jari</h5>
                                    <small>Control Lampu Rumah</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h5>Dua Jari</h5>
                                    <small>Control Lampu Taman Satu</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h5>Tiga Jari</h5>
                                    <small>Control Lampu Taman Dua</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h5>Empat Jari</h5>
                                    <small>Menyalakan Semua Lampu</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h5>Lima Jari</h5>
                                    <small>Mematikan Semua Lampu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>

    <script>
        // toggle sidebar
        const body = document.querySelector("body"),
            sidebar = body.querySelector(".sidebar"),
            toggle = body.querySelector(".toggle");

        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        //menu active
        document.addEventListener("DOMContentLoaded", function() {
            var currentUrl = window.location.pathname; // Ambil URL path saat ini
            var menuLinks = document.querySelectorAll(".menu-links .nav-link a");

            menuLinks.forEach(function(link) {
                if (link.getAttribute("href") === currentUrl) {
                    link.classList.add("active"); // Tambahkan class active
                }
            });
        });

        //   toggle dropdown profile
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }
        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches(".profile-img")) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains("show")) {
                        openDropdown.classList.remove("show");
                    }
                }
            }
        };

        const videoElement = document.getElementById('video');
        const canvasElement = document.getElementById('canvas');
        const canvasCtx = canvasElement.getContext('2d');
        const statusElement = document.getElementById('status');
        const notificationElement = document.getElementById('notification');
        const apiEndpoint = "http://192.168.130.80";

        const fingerStates = {
            "01000": "/rumah",
            "01100": "/tamanSatu",
            "01110": "/tamanDua",
            "01111": "/on_all",
            "11111": "/off_all",
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
            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);

            canvasCtx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);

            if (results.multiHandLandmarks) {
                for (let i = 0; i < results.multiHandLandmarks.length; i++) {
                    const landmarks = results.multiHandLandmarks[i];
                    const handedness = results.multiHandedness[i].label;

                    drawConnectors(
                        canvasCtx,
                        landmarks,
                        HAND_CONNECTIONS, {
                            color: handedness === 'Left' ? '#FF0000' : '#00FF00'
                        }
                    );

                    drawLandmarks(
                        canvasCtx,
                        landmarks, {
                            color: handedness === 'Left' ? '#FF0000' : '#00FF00',
                            fillColor: 'white'
                        }
                    );
                    const gesture = getFingerState(landmarks);

                    if (gesture in fingerStates) {
                        debounceGesture(fingerStates[gesture]);
                        statusElement.textContent =
                            `Status: ${gesture === "11111" ? "Mematikan Semua Lampu" : gesture === "01111" ? "Menyalakan Semua Lampu" : fingerStates[gesture]}`;
                    } else {
                        statusElement.textContent = "Status: tidak ada jari yang terdeteksi, mohon tunggu...";
                    }
                }
            }
        });

        function getFingerState(landmarks) {
            const ujungJari = [8, 12, 16, 20];
            const bases = [6, 10, 14, 18];
            let state = [];

            const thumbTip = landmarks[4];
            const thumbBase = landmarks[3];
            state.push(thumbTip.x < thumbBase.x ? 1 : 0);

            for (let i = 0; i < ujungJari.length; i++) {
                state.push(landmarks[ujungJari[i]].y < landmarks[bases[i]].y ? 1 : 0);
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
                const response = await fetch(url, {
                    method: "POST"
                });

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
                await hands.send({
                    image: videoElement
                });
            },
            width: 640,
            height: 480,
        });
        camera.start();
    </script>

</body>

</html>
