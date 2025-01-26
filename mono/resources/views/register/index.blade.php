<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="images/CVHS.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Register - CVHS</title>
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

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: calc(2.25rem + 2px);
            margin: 0;
            opacity: 0;
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }
    </style>
</head>
<body>
    <div class="container vh-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card p-4 floating">
                    <div class="text-center mb-4">
                        <img src="images/CVHS.png" alt="Logo" class="mb-3" style="width: 80px;">
                        <h3 class="text-dark">Create Account</h3>
                    </div>

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" 
                                       placeholder="Full Name" required>
                                <span class="input-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

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
                                       placeholder="Password" required>
                                <span class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="file" name="foto" class="form-control" required>
                                <span class="input-icon">
                                    <i class="fas fa-image"></i>
                                </span>
                            </div>
                            @error('foto')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="registerBtn">
                            Register
                        </button>

                        <p class="text-center mt-4">
                            Already have an account? 
                            <a href="/login" class="text-primary fw-bold">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('registerBtn');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Account...';
            btn.disabled = true;
        });
    </script>
</body>
</html>