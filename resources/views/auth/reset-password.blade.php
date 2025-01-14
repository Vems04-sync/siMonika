<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SiMonika</title>
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

        .reset-password-container {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .reset-password-header {
            background: #3498db;
            padding: 30px;
            text-align: center;
            color: white;
        }

        .reset-password-body {
            padding: 30px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating .form-control {
            border-radius: 10px;
        }

        .btn-reset {
            background: #3498db;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            width: 100%;
            margin-top: 10px;
        }

        .btn-reset:hover {
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
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-header">
            <h4 class="mb-0">Reset Password</h4>
            <small>Masukkan password baru Anda</small>
        </div>

        <div class="reset-password-body">
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

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="name@example.com" value="{{ old('email') }}" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password" 
                           name="password" placeholder="Password Baru" required>
                    <label for="password">Password Baru</label>
                    <i class="bi bi-eye-slash password-toggle" id="passwordToggle" 
                       onclick="togglePassword('password', 'passwordToggle')"></i>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password_confirmation" 
                           name="password_confirmation" placeholder="Konfirmasi Password" required>
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <i class="bi bi-eye-slash password-toggle" id="confirmToggle" 
                       onclick="togglePassword('password_confirmation', 'confirmToggle')"></i>
                </div>

                <button type="submit" class="btn btn-reset btn-primary">
                    <i class="bi bi-key me-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            
            if (input.type === 'password') {
                input.type = 'text';
                toggle.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = 'password';
                toggle.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>
</body>
</html> 