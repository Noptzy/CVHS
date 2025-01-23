<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #495057;
        }
        
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto User" class="rounded-circle profile-img">
            <h4 class="mt-3">{{ Auth::user()->name }}</h4>
        </div>
        <a href="/led">Kendali Manual</a>
        <a href="/camera">Kendali Camera</a>
        <a href="{{ route('profile.edit') }}">Edit Profile</a>
        <a href="/logout" class="text-danger">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <header class="bg-primary text-white text-center py-4 mb-4">
            <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
        </header>
        <img src="{{ asset('images/done.gif') }}" alt="CVHS" class="center">
        <p class="text-center">Were Here For You</p>
        {{-- <div class="text-center mb-4">
            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto User" class="rounded-circle profile-img">
        </div> --}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>