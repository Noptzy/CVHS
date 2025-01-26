<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="images/CVHS.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Login - CVHS</title>
    <style>
        body {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .card {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border-radius: 25px;
            border: 1px solid rgba(209, 213, 219, 0.3);
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px) rotateX(5deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 15px 45px 15px 20px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #4158d0;
            box-shadow: 0 0 0 0.25rem rgba(65, 88, 208, 0.25);
            transform: translateX(5px);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4158d0;
            z-index: 4;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4158d0, #c850c0);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.2);
            transform: rotate(45deg);
            transition: all 0.5s;
        }

        .btn-primary:hover::after {
            left: 100%;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s;
            color: white;
        }

        .google { background: #DB4437; }
        .facebook { background: #4267B2; }
        .twitter { background: #1DA1F2; }

        .social-btn:hover {
            transform: scale(1.1);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ccc;
        }

        .divider span {
            padding: 0 10px;
            color: #777;
            font-size: 0.9rem;
        }

    </style>
</head>
<body>
    <div class="container vh-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card p-4 floating">
                    <div class="text-center mb-4">
                        <img src="images/CVHS.png" alt="Logo" class="mb-3" style="width: 80px;">
                        <h3 class="text-dark">Welcome Back!</h3>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" 
                                       placeholder="Email Address" required>
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Password" required type="button" onclick="togglePasswordVisibility()">
                                <span class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="loginBtn">
                            <span>Login</span>
                        </button>
                        <p class="text-center mt-3">
                            Don't have an account? 
                            <a href="/register" class="text-primary fw-bold">Register</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            btn.disabled = true;
        });

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const icon = event.currentTarget.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>