<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - E-Arsip</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: backgroundMove 20s linear infinite;
            opacity: 0.3;
        }

        @keyframes backgroundMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50%, 50%); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1100px;
            margin: 20px;
        }

        .login-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        /* Left Side - Branding */
        .login-branding {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -150px;
            right: -150px;
        }

        .login-branding::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
        }

        .brand-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
            position: relative;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .brand-features {
            list-style: none;
            text-align: left;
            margin-top: 30px;
            position: relative;
            z-index: 1;
        }

        .brand-features li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .brand-features li i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        /* Right Side - Login Form */
        .login-form-section {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 0.9rem;
        }

        .form-control {
            height: 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 20px 12px 50px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 42px;
            color: #999;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: #667eea;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 42px;
            cursor: pointer;
            color: #999;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
            color: #666;
        }

        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login span {
            position: relative;
            z-index: 1;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .alert-danger {
            background-color: #fee;
            color: #c33;
        }

        .alert-success {
            background-color: #efe;
            color: #3c3;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .login-branding {
                display: none;
            }

            .login-form-section {
                padding: 40px 30px;
            }
        }

        @media (max-width: 576px) {
            .login-container {
                margin: 10px;
            }

            .login-form-section {
                padding: 30px 20px;
            }

            .form-header h2 {
                font-size: 1.5rem;
            }

            .brand-title {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .btn-login.loading {
            pointer-events: none;
        }

        .btn-login.loading span {
            opacity: 0;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-wrapper">
            <!-- Left Side - Branding -->
            <div class="login-branding">
                <i class="bi bi-folder2-open brand-icon"></i>
                <h1 class="brand-title">E-Arsip</h1>
                <p class="brand-subtitle">Sistem Manajemen Arsip Elektronik</p>
                <ul class="brand-features">
                    <li><i class="bi bi-check-circle-fill"></i> Manajemen Surat Masuk & Keluar</li>
                    <li><i class="bi bi-check-circle-fill"></i> Sistem Disposisi Digital</li>
                    <li><i class="bi bi-check-circle-fill"></i> Arsip Dokumen Terstruktur</li>
                    <li><i class="bi bi-check-circle-fill"></i> Laporan & Statistik Real-time</li>
                    <li><i class="bi bi-check-circle-fill"></i> Keamanan Berbasis Role</li>
                </ul>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-form-section">
                <div class="form-header">
                    <h2>Selamat Datang</h2>
                    <p>Silakan login untuk mengakses sistem</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> 
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Masukkan email Anda">
                        <i class="bi bi-envelope input-icon"></i>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            required 
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda">
                        <i class="bi bi-lock input-icon"></i>
                        <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="remember_me" 
                                name="remember">
                            <label class="form-check-label" for="remember_me">
                                Ingat Saya
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-login">
                        <span><i class="bi bi-box-arrow-in-right"></i> Masuk</span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="bi bi-shield-check"></i> 
                        Sistem ini menggunakan enkripsi untuk keamanan data Anda
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Form Loading State
        const loginForm = document.getElementById('loginForm');
        const submitBtn = loginForm.querySelector('.btn-login');

        loginForm.addEventListener('submit', function() {
            submitBtn.classList.add('loading');
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
