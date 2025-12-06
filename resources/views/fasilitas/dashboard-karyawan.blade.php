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
            overflow-x: hidden;
        }

        /* Header Section */
        #header-section {
            background: var(--bg-primary);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo-wrapper {
            display: flex;
            flex-direction: column;
        }

        .logo-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .logo-subtitle {
            font-size: 0.75rem;
            color: var(--text-primary);
            font-weight: 500;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .back-button {
            background: var(--bg-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .back-button ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        /* Content Section */
        #content-section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .menu-card {
            background: var(--bg-primary);
            border-radius: 25px;
            padding: 30px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .menu-card:hover {
            transform: translateY(-3px);
        }

        .menu-card:active {
            transform: translateY(1px);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .menu-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon ion-icon {
            font-size: 50px;
            color: var(--icon-color);
        }

        .menu-icon img {
            width: 50px;
            height: 50px;
            filter: invert(29%) sepia(16%) saturate(1142%) hue-rotate(139deg) brightness(95%) contrast(89%);
        }

        .menu-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .menu-subtitle {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin: 0 0 12px 0;
        }

        .badge-view-only {
            background: linear-gradient(135deg, var(--badge-orange) 0%, #f8c471 100%);
            color: white;
            font-size: 0.7rem;
            padding: 6px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
        }

        .badge-can-input {
            background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
            color: white;
            font-size: 0.7rem;
            padding: 6px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }

        .menu-card.disabled .badge-view-only {
            background: linear-gradient(135deg, #95a5a6 0%, #bdc3c7 100%);
        }

        /* Responsive */
        @media (max-width: 576px) {
            .menu-grid {
                gap: 15px;
            }

            .menu-card {
                padding: 25px 15px;
            }

            .menu-icon {
                width: 50px;
                height: 50px;
            }

            .menu-icon ion-icon {
                font-size: 40px;
            }

            .menu-title {
                font-size: 0.85rem;
            }
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Fasilitas & Asset</span>
            <span class="logo-subtitle">BUMI SULTAN</span>
        </div>
        <a href="{{ route('dashboard.index') }}" class="back-button">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <!-- Content -->
    <div id="content-section">
        <!-- Menu Grid -->
        <div class="menu-grid">
            {{-- Manajemen Gedung --}}
            <a href="{{ route('gedung.karyawan') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="business-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Manajemen<br>Gedung</h6>
                <small class="menu-subtitle">Gedung, Ruangan & Barang</small>
                <div class="badge-can-input">Bisa Input</div>
            </a>

            {{-- Manajemen Kendaraan --}}
            <a href="{{ route('kendaraan.karyawan.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="car-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Manajemen<br>Kendaraan</h6>
                <small class="menu-subtitle">Keluar/Masuk & Peminjaman</small>
                <div class="badge-can-input">Bisa Input</div>
            </a>

            {{-- Manajemen Dokumen --}}
            <a href="{{ route('dokumen.karyawan.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Manajemen<br>Dokumen</h6>
                <small class="menu-subtitle">Dokumen & Arsip</small>
                <div class="badge-view-only">Lihat Saja</div>
            </a>

            {{-- Manajemen Pengunjung --}}
            <a href="{{ route('pengunjung.karyawan.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Manajemen<br>Pengunjung</h6>
                <small class="menu-subtitle">Check-in & Check-out</small>
                <div class="badge-can-input">Bisa Input</div>
            </a>

            {{-- Manajemen Administrasi --}}
            <a href="{{ route('administrasi.karyawan.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="folder-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Manajemen<br>Administrasi</h6>
                <small class="menu-subtitle">Dokumen & Surat</small>
                <div class="badge-view-only">Lihat Saja</div>
            </a>

            {{-- Inventaris dan Peralatan --}}
            <a href="{{ route('inventaris-peralatan.karyawan.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="construct-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Inventaris &<br>Peralatan</h6>
                <small class="menu-subtitle">Inventaris & Peralatan BS</small>
                <div class="badge-view-only">Lihat Saja</div>
            </a>
        </div>

        <div style="height: 80px;"></div>
    </div>
@endsection
