@extends('layouts.mobile.app')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-primary);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .idcard-wrapper {
            position: relative;
            width: 400px;
            margin: 100px auto 40px auto;
            padding: 3px;
            border-radius: 30px;
            background: linear-gradient(45deg, 
                #00D25B, #0090E7, #FFB800, #e74c3c, 
                #00D25B, #0090E7, #FFB800, #e74c3c);
            background-size: 400% 400%;
            animation: gradientRotate 3s linear infinite;
        }

        @keyframes gradientRotate {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .idcard-container {
            width: 100%;
            min-height: 520px;
            background: var(--bg-primary);
            border-radius: 28px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            overflow: hidden;
            font-family: 'Segoe UI', Arial, sans-serif;
            position: relative;
            border: none;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            transition: all 0.3s ease;
        }

        .idcard-header-modern {
            background: linear-gradient(120deg, #32745e 80%, #58907D 100%);
            height: 120px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 36px;
            box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                       inset -2px -2px 4px rgba(255, 255, 255, 0.1);
        }

        .profile-pic-modern {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--bg-primary);
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
            position: absolute;
            top: 80px;
            left: 36px;
            background: var(--bg-primary);
            transition: all 0.3s ease;
        }

        .company-logo-modern {
            position: absolute;
            top: 24px;
            right: 32px;
            width: 60px;
            opacity: 0.95;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
        }

        .idcard-body-modern {
            padding: 110px 28px 18px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .idcard-name-modern {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 1px;
            margin-bottom: 2px;
            text-shadow: 
                -1px -1px 1px rgba(255, 255, 255, 0.3),
                1px 1px 1px rgba(0, 0, 0, 0.1);
        }

        .idcard-position-modern {
            font-size: 1.08rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .idcard-position-modern_jabatan {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 5px;
        }

        .idcard-info-modern {
            display: flex;
            align-items: center;
            font-size: 1.01rem;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .idcard-info-modern i {
            font-size: 1.13rem;
            color: var(--icon-color);
            margin-right: 10px;
            width: 22px;
            text-align: center;
        }

        .barcode-modern {
            margin: 32px 0 16px 0;
            text-align: center;
            background: var(--bg-primary);
            padding: 15px;
            border-radius: 12px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .barcode-modern img {
            height: 54px;
        }

        .idcard-footer-modern {
            text-align: center;
            font-size: 1.01rem;
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
            text-shadow: 
                -1px -1px 0px rgba(255, 255, 255, 0.3),
                1px 1px 1px rgba(0, 0, 0, 0.1);
        }

        .company-name-modern {
            position: absolute;
            left: 36px;
            top: 30px;
            color: #fff;
            font-size: 1.08rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            max-width: 60%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Header Section - Neumorphism */
        #header-section {
            background: transparent;
            padding: 20px;
            position: relative;
        }

        #section-theme {
            position: absolute;
            left: 20px;
            top: 20px;
            z-index: 999;
        }

        .theme-btn {
            background: var(--bg-primary);
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            color: var(--icon-color);
            font-size: 24px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .theme-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .download-btn-wrapper {
            text-align: center;
            margin: 24px 0 0 0;
            padding-bottom: 100px;
            z-index: 2;
            position: relative;
        }

        .download-btn {
            background: var(--bg-primary);
            color: var(--text-primary);
            border: none;
            border-radius: 16px;
            padding: 15px 30px;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .download-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        .download-btn i {
            color: var(--icon-color);
        }

        /* Back Button Neumorphism */
        .appHeader {
            background: linear-gradient(135deg, #32745e 0%, #58907D 50%, #2F5D62 100%) !important;
            box-shadow: 0 4px 15px rgba(50, 116, 94, 0.3);
            border: none !important;
            padding: 15px 20px !important;
        }

        .headerButton {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15),
                       inset 0 1px 2px rgba(255, 255, 255, 0.3);
            color: #ffffff;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .headerButton:active {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.2);
            transform: scale(0.95);
        }

        .headerButton ion-icon {
            color: #ffffff;
            font-size: 24px;
        }

        .pageTitle {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        @media (max-width: 400px) {
            .idcard-wrapper {
                width: 90%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <div id="header-section">
        <div id="section-theme">
            <a href="#" class="theme-btn" id="theme-toggle">
                <ion-icon name="sunny-outline" id="theme-icon"></ion-icon>
            </a>
        </div>
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">ID Card</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="idcard-wrapper">
            <div class="idcard-container" id="idcard-area">
            <div class="idcard-header-modern">
                @if ($generalsetting->logo && Storage::exists('public/logo/' . $generalsetting->logo))
                    <img src="{{ asset('storage/logo/' . $generalsetting->logo) }}" alt="Logo Perusahaan" class="company-logo-modern" alt="Company Logo">
                @else
                    <img src="https://placehold.co/100x100?text=Logo" class="company-logo-modern" alt="Company Logo">
                @endif
                <div class="company-name-modern">
                    {{ $generalsetting->nama_perusahaan ?? 'Nama Perusahaan' }}
                </div>
            </div>

            @if (!empty($karyawan->foto))
                @if (Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                    <img src="{{ getfotoKaryawan($karyawan->foto) }}" class="profile-pic-modern" alt="Profile Picture">
                @else
                    <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" class="profile-pic-modern" alt="Profile Picture">
                @endif
            @else
                <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" class="profile-pic-modern" alt="Profile Picture">
            @endif

            <div class="idcard-body-modern">
                <div class="idcard-name-modern">{{ textUpperCase($karyawan->nama_karyawan) }}</div>
                <div class="idcard-position-modern">{{ $karyawan->nama_dept }}</div>

                <div class="idcard-info-modern"><i class="fa-solid fa-id-badge"></i> ID: {{ $karyawan->nik }}</div>
                <div class="idcard-info-modern"><i class="fa-solid fa-calendar-plus"></i> Join Date:
                    {{ date('d-m-Y', strtotime($karyawan->tanggal_masuk)) }}</div>
                <div class="idcard-info-modern"><i class="fa-solid fa-phone"></i> {{ $karyawan->no_hp }}</div>
                <div class="idcard-info-modern"><i class="fa-solid fa-user"></i> {{ $karyawan->nama_jabatan }}</div>
                <div class="barcode-modern">
                    {!! DNS1D::getBarcodeHTML($karyawan->nik, 'C128', 2, 54) !!}
                </div>
            </div>
            <div class="idcard-footer-modern">
                {{ $generalsetting->nama_perusahaan }}
            </div>
            </div>
        </div>
        <div class="download-btn-wrapper">
            <button id="download-idcard" class="download-btn">
                <i class="fa-solid fa-download"></i> Download JPG
            </button>
        </div>
    </div>
    <script>
        // Theme Toggle - Same as dashboard
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const root = document.documentElement;

        // Initialize theme - default to light if not set
        let currentTheme = localStorage.getItem('theme');
        if (!currentTheme) {
            currentTheme = 'light';
            localStorage.setItem('theme', 'light');
        }

        // Apply saved theme on page load
        function applyTheme(theme) {
            if (theme === 'dark') {
                root.style.setProperty('--bg-primary', '#2c3e50');
                root.style.setProperty('--shadow-dark', 'rgba(0, 0, 0, 0.3)');
                root.style.setProperty('--shadow-light', 'rgba(255, 255, 255, 0.05)');
                root.style.setProperty('--text-primary', '#ecf0f1');
                root.style.setProperty('--text-secondary', '#bdc3c7');
                root.style.setProperty('--icon-color', '#3498db');
                root.style.setProperty('--border-color', '#34495e');
                themeIcon.setAttribute('name', 'moon-outline');
                document.body.classList.add('dark-mode');
            } else {
                root.style.setProperty('--bg-primary', '#e8eef3');
                root.style.setProperty('--shadow-dark', 'rgba(94, 104, 121, 0.3)');
                root.style.setProperty('--shadow-light', 'rgba(255, 255, 255, 0.9)');
                root.style.setProperty('--text-primary', '#2c3e50');
                root.style.setProperty('--text-secondary', '#7f8c8d');
                root.style.setProperty('--icon-color', '#3498db');
                root.style.setProperty('--border-color', '#bdc3c7');
                themeIcon.setAttribute('name', 'sunny-outline');
                document.body.classList.remove('dark-mode');
            }
        }

        // Apply theme on load
        applyTheme(currentTheme);

        // Toggle theme on click
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            currentTheme = localStorage.getItem('theme');
            
            if (currentTheme === 'light') {
                localStorage.setItem('theme', 'dark');
                applyTheme('dark');
            } else {
                localStorage.setItem('theme', 'light');
                applyTheme('light');
            }
        });

        // Download ID Card functionality
        // Ketika dokumen selesai dimuat, jalankan fungsi ini
        document.addEventListener('DOMContentLoaded', function() {
            // Temukan tombol dengan id 'download-idcard'
            var btn = document.getElementById('download-idcard');
            // Jika tombol ditemukan, tambahkan event listener untuk klik
            if (btn) {
                btn.addEventListener('click', function() {
                    // Temukan area dengan id 'idcard-area'
                    var area = document.getElementById('idcard-area');
                    // Jika area tidak ditemukan, tampilkan pesan error
                    if (!area) {
                        alert('ID Card tidak ditemukan!');
                        return;
                    }
                    // Jika html2canvas tidak terdefinisi, tampilkan pesan error
                    if (typeof html2canvas === 'undefined') {
                        alert('Gagal memuat html2canvas. Pastikan koneksi internet Anda stabil.');
                        return;
                    }
                    // Gunakan html2canvas untuk mengubah area menjadi canvas
                    html2canvas(area, {
                        backgroundColor: null, // Tidak mengubah warna latar belakang
                        scale: 2 // Meningkatkan skala gambar untuk kualitas lebih baik
                    }).then(function(canvas) {
                        // Buat elemen 'a' untuk download gambar
                        var link = document.createElement('a');
                        // Tentukan nama file yang akan diunduh
                        link.download = 'idcard-{{ $karyawan->nik }}.jpg';
                        // Tentukan URL gambar yang akan diunduh
                        link.href = canvas.toDataURL('image/jpeg', 0.95); // Kualitas gambar 95%
                        // Klik elemen 'a' untuk memulai unduhan
                        link.click();
                    }).catch(function(e) {
                        // Jika terjadi error saat membuat gambar, tampilkan pesan error
                        alert('Gagal membuat gambar: ' + e);
                    });
                });
            }
        });
    </script>
@endsection
