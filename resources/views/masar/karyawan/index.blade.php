@extends('layouts.mobile.app')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #e8f0f2;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Header Section */
        #header-section {
            background: #e8f0f2;
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
            color: #2F5D62;
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .logo-subtitle {
            font-size: 0.75rem;
            color: #2F5D62;
            font-weight: 500;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .back-button {
            background: #e8f0f2;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 8px 8px 16px #c5d3d5,
                       -8px -8px 16px #ffffff;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:active {
            box-shadow: inset 4px 4px 8px #c5d3d5,
                       inset -4px -4px 8px #ffffff;
        }

        .back-button ion-icon {
            font-size: 24px;
            color: #2F5D62;
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
            background: #e8f0f2;
            border-radius: 25px;
            padding: 30px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 8px 8px 16px #c5d3d5,
                       -8px -8px 16px #ffffff;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .menu-card:hover {
            transform: translateY(-3px);
        }

        .menu-card:active {
            transform: translateY(1px);
            box-shadow: inset 4px 4px 8px #c5d3d5,
                       inset -4px -4px 8px #ffffff;
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
            color: #2F5D62;
        }

        .menu-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2F5D62;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .menu-subtitle {
            font-size: 0.75rem;
            color: #5a7c7f;
            margin: 0 0 10px 0;
        }

        .badge-view-only {
            background: linear-gradient(135deg, #f39c12 0%, #f8c471 100%);
            color: white;
            font-size: 0.7rem;
            padding: 4px 12px;
            border-radius: 12px;
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
        }

        .badge-can-input {
            background: linear-gradient(135deg, #27ae60 0%, #52c77a 100%);
            color: white;
            font-size: 0.7rem;
            padding: 4px 12px;
            border-radius: 12px;
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
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
            <span class="logo-title">MASAR</span>
            <span class="logo-subtitle">SAUNG AR-ROHMAH</span>
        </div>
        <a href="{{ route('manajemen-yayasan.karyawan.dashboard') }}" class="back-button">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <!-- Content -->
    <div id="content-section">
        <!-- Menu Grid -->
        <div class="menu-grid">
            <!-- Data Jamaah -->
            <a href="{{ route('masar.karyawan.jamaah.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Data<br>Jamaah</h6>
                <small class="menu-subtitle">Lihat Data Jamaah</small>
                <div class="badge-view-only">Lihat Saja</div>
            </a>

            <!-- Distribusi Hadiah -->
            <a href="{{ route('masar.karyawan.distribusi.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="share-social-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Distribusi<br>Hadiah</h6>
                <small class="menu-subtitle">Lihat Distribusi</small>
                <div class="badge-view-only">Lihat Saja</div>
            </a>

            <!-- Laporan -->
            <a href="{{ route('masar.karyawan.laporan.index') }}" class="menu-card">
                <div class="menu-icon">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <h6 class="menu-title">Laporan</h6>
                <small class="menu-subtitle">Lihat Laporan</small>
                <div class="badge-can-input">Bisa Lihat</div>
            </a>
        </div>
    </div>
@endsection
