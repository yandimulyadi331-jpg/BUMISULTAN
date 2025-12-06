<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in & Sign up Form</title>

    <!-- PWA Meta Tags -->
    <meta name="application-name" content="BUMI SULTAN APP">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="BUMI SULTAN">
    <meta name="description" content="Aplikasi Presensi GPS untuk Karyawan">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#696cff">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="/assets/img/icons/pwa/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/assets/img/icons/pwa/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/assets/img/icons/pwa/icon-512x512.png">

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <link rel="stylesheet" href="{{ asset('assets/login/css/style.css') }}" />
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            animation: slideIn 0.5s ease-out;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="login-container">
            <form id="formAuthentication" class="login-box" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="login-content">
                    <h1 class="login-title">Login</h1>
                    <p class="login-subtitle">Welcome back please login to your account</p>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="text" minlength="4" class="input-field @error('id_user') is-invalid @enderror" 
                                name="id_user" value="{{ old('id_user') }}" placeholder="User Name" required />
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                        </div>

                        <div class="input-wrapper">
                            <input type="password" minlength="4" name="password" 
                                class="input-field @error('password') is-invalid @enderror" placeholder="Password" required />
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9V7z"></path>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="remember-wrap">
                        <label class="remember-label">
                            <input type="checkbox" id="remember" name="remember">
                            <span class="checkmark"></span>
                            <span class="remember-text">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="login-btn">Login</button>

                    <p class="signup-text">
                        Don't have an account? <a href="{{ route('signup') }}" class="signup-link">Signup</a>
                    </p>

                    <p class="creator-text">Created by <span class="creator-name">manajemenbumisultan@2025</span></p>
                </div>
            </form>
        </div>
    </main>

    <!-- Javascript file -->
    <script src="{{ asset('assets/login/script/app.js') }}"></script>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>

    <!-- PWA Install Prompt - Only on Login Page -->
    @include('components.pwa-install-prompt')
</body>

</html>
