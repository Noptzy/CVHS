<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .bg-cover {
            background-size: cover;
            background-position: center;
        }

        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        .bi-lightbulb {
            transition: color 0.3s ease, transform 0.3s ease;
        }
    </style>
</head>

<body class="bg-light text-dark">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="images/CVHS.png" alt="Logo" class="rounded-circle" style="width: 40px;">
                Dashboard
            </a>
        </div>
    </nav>

    <!-- Section dengan background -->
    <section class="position-relative vh-100 d-flex align-items-center justify-content-center bg-cover"
        style="background-image: url('/images/CVHS-bg.png');">
        <!-- Overlay untuk menggelapkan gambar -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>

        <!-- Konten/logo di tengah -->
        <div class="text-center position-relative text-white">
            <h1 class="display-4 fw-bold">Smart Home</h1>
            <p class="lead">Computer Vision for Hearing Solutions</p>
        </div>
    </section>

    <!-- Section 2: Cards -->
    <div class="container my-5">
        <div class="row g-4">
            <!-- Lampu Indikator -->
            <div class="col-sm-6 col-lg-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-lightbulb fs-1 lampu-indikator" data-color="red"></i>
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
                            <i class="bi bi-lightbulb fs-1 lampu-rumah" data-color="blue"></i>
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
                            <i class="bi bi-lightbulb fs-1 lampu-taman" data-color="yellow"></i>
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
    <footer class="bg-primary text-white text-center py-3">
        <p class="mb-0">© 2025 All Rights Reserved.</p>
    </footer>

    <!-- Script -->
    <script>
        // Fungsi untuk menyalakan/mematikan lampu
        function toggleLampu(event) {
            const lampu = event.target; // Elemen lampu yang diklik
            const color = lampu.dataset.color; // Warna dari atribut data-color
            
            // Mengubah warna ikon lampu
            if (lampu.style.color === color) {
                lampu.style.color = ""; // Mematikan lampu
            } else {
                lampu.style.color = color; // Menyalakan lampu
            }
        }

        // Menambahkan event listener ke setiap elemen dengan class 'bi-lightbulb'
        document.querySelectorAll('.bi-lightbulb').forEach((lampu) => {
            lampu.addEventListener('click', toggleLampu);
        });
    </script>
</body>

</html>
