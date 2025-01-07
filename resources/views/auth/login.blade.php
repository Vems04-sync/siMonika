<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiMonika</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            background: #3498db;
            padding: 30px;
            text-align: center;
            color: white;
        }

        .login-header .logo {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .login-body {
            padding: 30px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating .form-control {
            border-radius: 10px;
            height: calc(3rem + 2px);
            padding: 1rem 0.75rem;
        }

        .form-floating label {
            padding: 1rem 0.75rem;
        }

        .btn-login {
            background: #3498db;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            width: 100%;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #2980b9;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #6c757d;
            text-decoration: none;
        }

        .forgot-password a:hover {
            color: #3498db;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="bi bi-display"></i> 
            </div>
            <h4 class="mb-0">SiMonika</h4>
            <small>Sistem Monitoring Aplikasi</small>
        </div>

        <div class="login-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <i class="bi bi-eye-slash password-toggle" id="passwordToggle" onclick="togglePassword()"></i>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn btn-login btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>
            <div class="forgot-password">
                <a href="#" onclick="forgotPassword(event)">Lupa password?</a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>
</body>
</html>
