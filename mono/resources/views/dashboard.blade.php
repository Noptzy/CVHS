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
            <section class="led-section">
                <h3>Manual Control</h3>
                <ul class="led-list">
                    <li class="led">
                        <img src="images/led-off.png" alt="Lampu Rumah" class="led-image" id="rumahLedImage" />
                        <div class="control-led">
                            <span class="text">Lampu Rumah</span>
                            <div class="led-btn-container">
                                <button class="led-submit" id="ledRumah" onclick="toggleLed('rumah')">ON</button>
                            </div>
                        </div>
                    </li>
                    <li class="led">
                        <img src="images/led-off.png" alt="Lampu Taman Satu" class="led-image" id="tamanSatuLedImage" />
                        <div class="control-led">
                            <span class="text">Lampu Taman Satu</span>
                            <div class="led-btn-container">
                                <button class="led-submit" id="ledTamanSatu"
                                    onclick="toggleLed('tamanSatu')">ON</button>
                            </div>
                        </div>
                    </li>
                    <li class="led">
                        <img src="images/led-off.png" alt="Lampu Taman Dua" class="led-image" id="tamanDuaLedImage" />
                        <div class="control-led">
                            <span class="text">Lampu Taman Dua</span>
                            <div class="led-btn-container">
                                <button class="led-submit" id="ledTamanDua" onclick="toggleLed('tamanDua')">ON</button>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>
            <!-- Distance Section -->
            <section class="distance-section">
                <h2>Ultrasonic Sensor</h2>
                <div>
                    <p class="distance-value" id="distanceValue">-- cm</p>
                    <p class="distance-label">Distance</p>
                </div>
            </section>
            <!-- Distance Section -->
        </main>
    </section>
    {{-- LED Section --}}
    {{-- main content --}}

    <script src="assets/js/script.js"></script>
</body>

</html>
