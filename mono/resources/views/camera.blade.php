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
                    <i class='bx bxs-dashboard icon'></i>
                    <span class="text nav-text">Dashboard</span>
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
                                    <h6>Satu Jari</h6>
                                    <small>Control Lampu Rumah</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h6>Dua Jari</h6>
                                    <small>Control Lampu Taman Satu</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h6>Tiga Jari</h6>
                                    <small>Control Lampu Taman Dua</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h6>Empat Jari</h6>
                                    <small>Menyalakan Semua Lampu</small>
                                </div>
                            </div>
                            <div class="gesture-item">
                                <div>
                                    <h6>Lima Jari</h6>
                                    <small>Mematikan Semua Lampu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>
    {{-- LED Section --}}
    {{-- main content --}}
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>

    {{-- Link js --}}
    <script src="assets/js/script.js"></script>
</body>

</html>
