<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/template/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/template/css/styleform.css') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>
        /* Dark Mode Variables */
        :root {
            --bg-primary: #e8f0f2;
            --bg-secondary: #ffffff;
            --text-primary: #2F5D62;
            --text-secondary: #5a7c7f;
            --shadow-light: #ffffff;
            --shadow-dark: #c5d3d5;
            --border-color: #c5d3d5;
            --icon-color: #2F5D62;
            --badge-green: #27ae60;
            --badge-orange: #f39c12;
        }

        body.dark-mode {
            --bg-primary: #1a1d23;
            --bg-secondary: #252932;
            --text-primary: #e8eaed;
            --text-secondary: #9ca3af;
            --shadow-light: #2d3139;
            --shadow-dark: #0d0e11;
            --border-color: #3a3f4b;
            --icon-color: #64b5f6;
            --badge-green: #4caf50;
            --badge-orange: #ff9800;
        }

        body {
            background: var(--bg-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Theme Toggle Button */
        #theme-toggle {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 9999;
            background: var(--bg-primary);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        #theme-toggle:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        #theme-toggle ion-icon {
            font-size: 24px;
            color: var(--icon-color);
            transition: all 0.3s ease;
        }

        /* Dark mode icon animation */
        body.dark-mode #theme-toggle ion-icon[name="sunny-outline"] {
            display: none;
        }

        body:not(.dark-mode) #theme-toggle ion-icon[name="moon-outline"] {
            display: none;
        }

        .historicontent {
            display: flex;
            justify-content: space-between;
            padding: 20px
        }

        .historibordergreen {
            border: 1px solid #32745e;
        }

        .historiborderred {
            border: 1px solid rgb(171, 18, 18);
        }

        .historidetail1 {
            display: flex;
        }

        .historidetail2 h4 {
            margin-bottom: 0;
        }



        .datepresence {
            margin-left: 10px;
        }

        .datepresence h4 {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 0;
        }

        .timepresence {
            font-size: 14px;
        }
    </style>
    {{-- <style>
        .selectmaterialize,
        textarea {
            display: block;
            background-color: transparent !important;
            border: 0px !important;
            border-bottom: 1px solid #9e9e9e !important;
            border-radius: 0 !important;
            outline: none !important;
            height: 3rem !important;
            width: 100% !important;
            font-size: 16px !important;
            margin: 0 0 8px 0 !important;
            padding: 0 !important;
            color: #495057;

        }

        textarea {
            height: 80px !important;
            color: #495057 !important;
            padding: 20px !important;
        }
    </style> --}}
</head>

<body>

    <!-- Theme Toggle Button -->
    <button id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle Dark Mode">
        <ion-icon name="sunny-outline"></ion-icon>
        <ion-icon name="moon-outline"></ion-icon>
    </button>

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    @yield('header')

    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
    </div>
    <!-- * App Capsule -->


    @include('layouts.mobile.bottomNav')


    @include('layouts.mobile.script')

    <!-- Dark Mode Script -->
    <script>
        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }

        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            
            // Save theme preference
            const theme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            
            // Add bounce animation
            const toggleBtn = document.getElementById('theme-toggle');
            toggleBtn.style.transform = 'scale(0.9)';
            setTimeout(() => {
                toggleBtn.style.transform = 'scale(1)';
            }, 150);
        }
    </script>

    @yield('scripts')
    @stack('scripts')


</body>

</html>
