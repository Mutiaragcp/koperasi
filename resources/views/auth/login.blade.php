<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Masuk - SIKOPSIM</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #2563eb;
            --bs-primary-dark: #1d4ed8;
            --bs-primary-light: #eff6ff;
            --bs-surface: #ffffff;
            --bs-background: #f8fafc;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bs-background);
            color: #1e293b;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Abstract Background Elements */
        .bg-shape {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            border-radius: 50%;
            opacity: 0.5;
            animation: float 10s infinite ease-in-out alternate;
        }

        .bg-shape-1 {
            width: 500px;
            height: 500px;
            background: rgba(37, 99, 235, 0.3);
            top: -100px;
            left: -100px;
        }

        .bg-shape-2 {
            width: 400px;
            height: 400px;
            background: rgba(14, 165, 233, 0.2);
            bottom: -50px;
            right: -50px;
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -50px) scale(1.1); }
        }

        /* Card Container */
        .login-container {
            width: 100%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* Branding Side */
        .login-branding {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #0ea5e9 100%);
            padding: 3rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.83-54.627 54.627-.83-.83L54.627 0zM29.627 0l.83.83-29.627 29.627-.83-.83L29.627 0zM59.627 30l.83.83-29.627 29.627-.83-.83L59.627 30z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Form Side */
        .login-form-area {
            padding: 3rem;
            flex: 1;
            background: var(--bs-surface);
        }

        /* Inputs */
        .form-floating-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control-custom {
            width: 100%;
            background-color: #F8FAFC;
            border: 2px solid transparent;
            padding: 1.25rem 1rem 0.75rem 1rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            color: #0F172A;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control-custom:focus {
            background-color: #ffffff;
            border-color: var(--bs-primary);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .form-floating-custom label {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #64748B;
            font-size: 1rem;
            font-weight: 500;
            pointer-events: none;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus ~ label,
        .form-control-custom:not(:placeholder-shown) ~ label {
            top: 0.8rem;
            font-size: 0.75rem;
            color: var(--bs-primary);
            font-weight: 600;
        }

        /* Button */
        .btn-login {
            background: var(--bs-primary);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .btn-login:hover {
            background: var(--bs-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
        }

        .btn-login:hover::after {
            width: 300px;
            height: 300px;
        }

        /* Checkbox */
        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            border: 2px solid #CBD5E1;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .glass-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            body {
                background: linear-gradient(135deg, var(--bs-primary) 0%, #0ea5e9 100%);
            }
            .bg-shape {
                display: none; /* Sembunyikan shape di mobile agar lebih clean */
            }
            .login-branding {
                display: none !important;
            }
            .login-container {
                max-width: 450px;
                border: none;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
                border-radius: 24px;
            }
            .login-form-area {
                padding: 3rem 2.5rem;
                border-radius: 24px;
            }
        }
        @media (max-width: 575.98px) {
            body {
                padding: 1rem;
            }
            .login-container {
                border-radius: 20px;
            }
            .login-form-area {
                padding: 2.5rem 1.5rem;
                border-radius: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>

    <div class="login-container">
        
        <!-- Left Side: Branding -->
        <div class="col-lg-5 d-none d-lg-flex login-branding shadow-inner">
            <div style="z-index: 2;">

                
                <h1 class="display-5 fw-bold mb-4" style="line-height: 1.1;">
                    Kelola <br>Koperasi<br>Lebih Mudah.
                </h1>
                <p class="fs-6 opacity-75 mb-0" style="line-height: 1.6;">
                    Pantau simpanan, kelola pinjaman, dan bagikan SHU secara transparan dalam satu platform yang terintegrasi.
                </p>
            </div>

            <div style="z-index: 2;" class="d-flex align-items-center gap-3">

                <div>
                    <h5 class="mb-0 fw-bold">SIKOPSIM</h5>
                    <small class="opacity-75">Sistem Informasi Koperasi</small>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="login-form-area col-12 col-lg-7 d-flex flex-column justify-content-center">
            
            <div class="text-center text-lg-start mb-5">
                <!-- Mobile Branding -->
                <div class="d-lg-none text-center mb-4">
                    <h4 class="fw-bold text-primary mb-1" style="letter-spacing: 1px;">SIKOPSIM</h4>
                    <p class="small text-muted mb-0">Sistem Informasi Koperasi</p>
                </div>

                <h2 class="fw-bold mb-2 text-dark" style="letter-spacing: -0.5px;">Selamat Datang! 👋</h2>
                <p class="text-secondary">Silakan masuk menggunakan kredensial Anda.</p>
            </div>

            @if ($errors->any() || session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 d-flex align-items-center gap-3 fw-medium mb-4 shadow-sm" style="animation: slideDown 0.3s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                </svg>
                <div>
                    {{ session('error') ?? $errors->first() }}
                </div>
            </div>
            @endif

            <form action="/login" method="POST">
                @csrf 
                
                <div class="form-floating-custom">
                    <input type="email" class="form-control-custom" name="email" id="email" value="{{ old('email') }}" placeholder=" " required autofocus>
                    <label for="email">Alamat Email</label>
                </div>

                <div class="form-floating-custom mb-3">
                    <input type="password" class="form-control-custom" name="password" id="password" placeholder=" " required>
                    <label for="password">Kata Sandi</label>
                    <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y text-muted px-3 shadow-none" onclick="togglePassword()">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                        </svg>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check d-flex align-items-center gap-2 m-0">
                        <input type="checkbox" name="remember" class="form-check-input mt-0 shadow-none" id="remember">
                        <label class="form-check-label text-secondary fw-medium pt-1" for="remember" style="cursor: pointer;">Ingat Saya</label>
                    </div>
                    <a href="#" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.95rem;">Lupa sandi?</a>
                </div>

                <button type="submit" class="btn-login">
                    Masuk 
                </button>

            </form>

            <div class="text-center mt-5">
                <span class="text-secondary fw-medium" style="font-size: 0.9rem;">
                    Sistem Internal Koperasi &copy; 2026
                </span>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            var pwd = document.getElementById('password');
            if(pwd.type === 'password') {
                pwd.type = 'text';
            } else {
                pwd.type = 'password';
            }
        }
    </script>
</body>
</html>