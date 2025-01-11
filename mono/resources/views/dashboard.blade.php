<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="{{ url('/images/CVHS.png') }}" />
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Animasi Scroll */
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        .bg-cover {
            background-size: cover;
            background-position: center;
        }

        .card {
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .bi-lightbulb {
            transition: color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
        }

        /* Warna Dashboard */
        .bg-primary {
            background-color: #4A90E2 !important;
        }

        footer {
            background-color: #E94E77 !important;
        }

        /* Warna Ikon Lampu */
        .lampu-indikator {
            color: #E94E77;
        }

        .lampu-rumah {
            color: #F5A623;
        }

        .lampu-taman {
            color: #7ED321;
        }

        .navbar-brand img {
            width: 40px;
            margin-right: 10px;
        }

        /* Animasi Smooth Fade-In */
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease-in forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-light text-dark">
    <!-- Navbar -->

    <body class="index-page">
        <header id="header" class="header d-flex align-items-center fixed-top">
            <div
                class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
                <a href="/" class="logo d-flex align-items-center">
                    <h1 class="sitename">Computer Vision for Hearing Solutions</h1>
                </a>
                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li><a href="/" >Home</a></li>
                        <li><a href="/dashboard" class="active">Dashboard</a></li>
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>
            </div>
        </header>

        <!-- Section dengan background -->
        <section class="position-relative vh-100 d-flex align-items-center justify-content-center bg-cover fade-in"
            style="background-image: url('/images/CVHS-bg.png');">
            <!-- Overlay untuk menggelapkan gambar -->
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>

            <!-- Konten/logo di tengah -->
            <div class="text-center position-relative text-white">
                <h1 class="display-4 fw-bold">Smart Home</h1>
                <p class="lead">Computer Vision for Hearing Solutions</p>
                <a href="#features" class="btn btn-warning mt-3">Explore Features</a>
            </div>
        </section>

        <!-- Section 2: Cards -->
        <div id="features" class="container my-5 fade-in">
            <div class="row g-4">
                <!-- Lampu Indikator -->
                <div class="col-sm-6 col-lg-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-lightbulb fs-1 lampu-indikator"></i>
                            </div>
                            <h5 class="card-title">Lampu Indikator</h5>
                        </div>
                    </div>
                </div>

                <!-- Lampu Rumah -->
                <div class="col-sm-6 col-lg-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-lightbulb fs-1 lampu-rumah"></i>
                            </div>
                            <h5 class="card-title">Lampu Rumah</h5>
                        </div>
                    </div>
                </div>

                <!-- Lampu Taman -->
                <div class="col-sm-6 col-lg-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-lightbulb fs-1 lampu-taman"></i>
                            </div>
                            <h5 class="card-title">Lampu Taman</h5>
                        </div>
                    </div>
                </div>

                <!-- User -->
                <div class="col-sm-6">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-person-circle text-info fs-1"></i>
                            </div>
                            <h5 class="card-title">User</h5>
                        </div>
                    </div>
                </div>

                <!-- Open Camera -->
                <div class="col-sm-6">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-camera text-info fs-1"></i>
                            </div>
                            <h5 class="card-title">Open Camera</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-white text-center py-3 fade-in">
            <p class="mb-0">© 2025 All Rights Reserved. Powered by CVHS.</p>
        </footer>

        <!-- Script -->
        <script>
            // Fungsi untuk menyalakan/mematikan lampu
            function toggleLampu(event) {
                const lampu = event.target;
                const color = lampu.style.color;

                // Mengubah warna ikon lampu
                lampu.style.color = color === 'gray' ? lampu.dataset.color : 'gray';
            }

            // Menambahkan event listener ke setiap elemen dengan class 'bi-lightbulb'
            document.querySelectorAll('.bi-lightbulb').forEach((lampu) => {
                lampu.addEventListener('click', toggleLampu);
            });
        </script>
    </body>

</html>
