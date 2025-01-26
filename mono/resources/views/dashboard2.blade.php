<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CVHS Dashboard</title>
    <link href="images/CVHS.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --background-color: #f0f4f8;
            --sidebar-color: #2c3e50;
            --hover-color: #34495e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            margin-left: 260px;
            transition: margin-left 0.3s ease-in-out;
        }

        body.sidebar-collapsed {
            margin-left: 80px;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            background: var(--sidebar-color);
            color: white;
            transition: width 0.3s ease-in-out;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            background: rgba(0,0,0,0.1);
            position: relative;
        }

        .sidebar-header img {
            width: 40px;
            margin-right: 15px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .sidebar-header:hover img {
            transform: rotate(360deg);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.3rem;
            white-space: nowrap;
            overflow: hidden;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-header h3 {
            opacity: 0;
            width: 0;
        }

        .sidebar-menu {
            padding-top: 20px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-menu a i {
            margin-right: 15px;
            font-size: 1.2rem;
            min-width: 25px;
            text-align: center;
        }

        .sidebar-menu a span {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-menu a span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-menu a:hover {
            background-color: var(--hover-color);
        }

        .sidebar-menu a::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: var(--primary-color);
            transition: height 0.3s ease;
        }

        .sidebar-menu a:hover::after {
            height: 100%;
        }

        .toggle-btn {
            position: absolute;
            right: -40px;
            top: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .toggle-btn:hover {
            background: var(--secondary-color);
        }

        .top-bar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .profile-section {
            position: relative;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid var(--primary-color);
            transition: transform 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.1);
        }

        .profile-name {
            font-weight: 600;
            color: var(--sidebar-color);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            border-radius: 8px;
            display: none;
            min-width: 200px;
            overflow: hidden;
            animation: dropdownSlideIn 0.3s ease;
        }

        @keyframes dropdownSlideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sidebar-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-menu a i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .dropdown-menu a:hover {
            background: var(--background-color);
            color: var(--primary-color);
        }

        .logout-link {
            color: #e74c3c !important;
        }

        .logout-link:hover {
            background-color: #f8d7da !important;
        }

        @media (max-width: 768px) {
            body {
                margin-left: 80px;
            }
            .sidebar {
                width: 80px;
            }
            .sidebar-header h3 {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="images/CVHS.png" alt="CVHS Logo">
            <h3>CVHS Dashboard</h3>
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            <a href="/camera">
                <i class="fas fa-camera"></i>
                <span>Camera Control</span>
            </a>
            <a href="/led">
                <i class="fas fa-cogs"></i>
                <span>Manual Control</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div class="profile-section">
                <span class="profile-name">{{ Auth::user()->name }}</span>
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Profile" class="profile-img" onclick="toggleDropdown()">
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="/profile/edit"><i class="fas fa-user-edit"></i> Edit Profile</a>
                    <a href="/logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function toggleDropdown() {
            document.getElementById('profileDropdown').classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.profile-img')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>