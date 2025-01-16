<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="images/CVHS.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(to right, #dce35b, #45b649);
            font-family: 'Arial', sans-serif;
            color: #fff;
        }

        .h-custom {
            height: 100%;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            color: white;
            padding: 40px 30px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 10px;
            padding: 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(255, 183, 77, 0.8);
            border-color: #ffb74d;
        }

        .btn-primary {
            background: linear-gradient(to right, #36d1dc, #5b86e5);
            border: none;
            border-radius: 20px;
            padding: 12px 25px;
            font-size: 18px;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #5b86e5, #36d1dc);
        }

        h3 {
            font-size: 32px;
            font-weight: bold;
        }

        label {
            font-size: 18px;
        }

        .btn-link {
            color: #ffffff;
        }

        .btn-link:hover {
            color: #ffb74d;
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
    </style>
</head>

<body>
    <section class="vh-100">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card">

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <h3 class="text-center mb-4">Login</h3>

                            <!-- Email input -->
                            <div class="form-outline mb-3">
                                <label class="form-label" for="form3Example3">Email Address</label>
                                <input type="email" id="form3Example3" name="email" class="form-control form-control-lg"
                                    placeholder="Enter your email" required>
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-3">
                                <label class="form-label" for="form3Example4">Password</label>
                                <input type="password" id="form3Example4" name="password" class="form-control form-control-lg"
                                    placeholder="Enter your password" required>
                            </div>

                            <!-- Login button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                            </div>

                            <div class="text-center mt-3">
                                <p class="small">Don't have an account? <a href="/register" class="btn-link">Register</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
