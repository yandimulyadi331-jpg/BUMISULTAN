@extends('layouts.mobile.app')
@section('content')
    <style>
        /* FORCE SCROLL */
        * {
            -webkit-overflow-scrolling: touch !important;
        }

        html, body {
            overflow: visible !important;
            overflow-y: scroll !important;
            overflow-x: hidden !important;
            height: auto !important;
            max-height: none !important;
            min-height: 100vh !important;
            position: relative !important;
        }

        #appCapsule {
            overflow: visible !important;
            height: auto !important;
            min-height: calc(100vh + 200px) !important;
            padding-bottom: 150px !important;
        }

        :root {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --text-primary: #1a202c;
            --text-secondary: #718096;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --text-primary: #f7fafc;
            --text-secondary: #cbd5e0;
            --border-color: #4a5568;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--bg-secondary);
        }

        #content-section {
            margin-top: 56px;
            padding: 10px 16px 150px 16px;
            position: relative;
            z-index: 1;
            background: var(--bg-primary);
            min-height: calc(100vh - 56px);
            transition: background 0.3s ease;
        }

        /* Form Card - Modern Style */
        .form-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .feedback-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 14px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
            margin-bottom: 16px;
        }

        .feedback-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .feedback-input::placeholder {
            color: var(--text-secondary);
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-group label {
            font-size: 14px;
            color: var(--text-primary);
            cursor: pointer;
            margin: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            color: white;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Theme Toggle */
        .theme-toggle-btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .theme-toggle-btn ion-icon {
            font-size: 22px;
            color: var(--text-primary);
        }

        .theme-toggle-btn:active {
            transform: scale(0.95);
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Setting User</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <!-- Theme Toggle Button -->
        <button class="theme-toggle-btn" onclick="toggleDarkMode()">
            <ion-icon name="moon-outline" class="theme-icon"></ion-icon>
        </button>

        <div class="form-card">
            <div class="row">
                <div class="col pl-3 pr-3">
                    <form action="{{ route('users.updatepassword', Crypt::encrypt($user->id)) }}" method="POST" id="formIzin" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <input type="password" class="feedback-input passwordbaru" name="passwordbaru" placeholder="Password Baru" id="passwordbaru" />
                        <input type="password" class="feedback-input konfirmasipassword" name="konfirmasipassword" placeholder="Konfirmasi Password"
                            id="konfirmasipassword" />
                        <div class="form-group">
                            <input type="checkbox" id="show-password" onclick="tooglePassword()" />
                            <label for="show-password">Tampilkan Password</label>
                        </div>
                        <button class="btn btn-primary w-100" id="btnSimpan">
                            <ion-icon name="save-outline"></ion-icon>
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        // Dark Mode Toggle
        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update icon
            const icon = document.querySelector('.theme-icon');
            if (icon) {
                icon.name = newTheme === 'dark' ? 'sunny-outline' : 'moon-outline';
            }
        }

        // Load theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            const icon = document.querySelector('.theme-icon');
            if (icon) {
                icon.name = savedTheme === 'dark' ? 'sunny-outline' : 'moon-outline';
            }

            // Force enable scrolling
            document.documentElement.style.overflowY = 'scroll';
            document.body.style.overflowY = 'scroll';
            document.body.style.height = 'auto';
            document.body.style.maxHeight = 'none';
            
            const appCapsule = document.getElementById('appCapsule');
            if (appCapsule) {
                appCapsule.style.overflow = 'visible';
                appCapsule.style.height = 'auto';
                appCapsule.style.maxHeight = 'none';
            }
        });

        function tooglePassword() {
            var x = document.getElementById("passwordbaru");
            var y = document.getElementById("konfirmasipassword");
            if (x.type === "password") {
                x.type = "text";
                y.type = "text";
            } else {
                x.type = "password";
                y.type = "password";
            }
        }
    </script>
@endpush
