<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard - CVHS</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.9.6/lottie.min.js"></script>


    <style>
        .icon-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            background: #ffffff;
        }
    
        .icon-box .icon i {
            transition: transform 0.3s ease, color 0.3s ease;
        }
    
        .icon-box:hover .icon i {
            transform: scale(1.2);
            color: #ff5722; /* Warna berubah saat hover */
        }
    
        .icon-box h4 {
            margin-top: 10px;
        }
    </style>
</head>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
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

    <main class="main">
        <!-- Dashboard Hero Section -->
        <section id="hero" class="hero section dark-background">
            <img src="assets/img/dashboard-bg.jpg" alt="" class="hero-bg">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Welcome to Your Dashboard</h1>
                        <p class="lead">Monitor and control your smart home devices seamlessly.</p>
                        <a href="#features" class="btn-get-started">Explore Features</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="features section">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 gy-4 icon-boxes">
                    <!-- Lampu Indikator -->
                    <div class="col" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon-box text-center">
                            <i id="light-on-off" style="width: 200px; height: 200px; margin: auto;"></i>
                            <h3>Lampu Indikator</h3>
                            <p>Monitor and control the status of indicator lights in real time.</p>
                        </div>
                    </div> <!-- End Icon Box -->
        
                    <!-- Lampu Rumah -->
                    <div class="col" data-aos="fade-up" data-aos-delay="300">
                        <div class="icon-box text-center">
                            <i class="bi bi-lamp-fill"></i>
                            <h3>Lampu Rumah</h3>
                            <p>Control all home lights easily using gestures or mobile devices.</p>
                        </div>
                    </div> <!-- End Icon Box -->
        
                    <!-- Lampu Taman -->
                    <div class="col" data-aos="fade-up" data-aos-delay="400">
                        <div class="icon-box text-center">
                            <i class="bi bi-lightbulb-fill"></i>
                            <h3>Lampu Taman</h3>
                            <p>Adjust the garden lights for better ambiance at any time.</p>
                        </div>
                    </div> <!-- End Icon Box -->
                </div>
            </div>
        </section>
        
        
    </main>

    <footer id="footer" class="footer dark-background">
        <div class="container footer-top">
            <div class="row">
                <div class="col-lg-4">
                    <a href="/" class="logo d-flex align-items-center">
                        <span class="sitename">CVHS</span>
                    </a>
                </div>
                <div class="col-lg-8 text-center text-lg-end">
                    <p>© 2025 CVHS. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        // Light On and Off Animation
  lottie.loadAnimation({
    container: document.getElementById('light-on-off'), // Element ID
    renderer: 'svg', // Renderer type
    loop: true, // Should the animation loop
    autoplay: true, // Should the animation play automatically
    path: 'URL_OR_PATH_TO_JSON/light-on-off.json' // Replace with JSON URL or file path
  });
    </script>
</body>

</html>
