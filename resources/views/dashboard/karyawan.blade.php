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
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 60vh;
            background-image: url('{{ asset('assets/login/images/background.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.2;
            z-index: -2;
            pointer-events: none;
            -webkit-mask-image: linear-gradient(to bottom, 
                rgba(0,0,0,1) 0%, 
                rgba(0,0,0,0.9) 50%, 
                rgba(0,0,0,0.5) 70%,
                rgba(0,0,0,0.2) 85%,
                rgba(0,0,0,0) 100%);
            mask-image: linear-gradient(to bottom, 
                rgba(0,0,0,1) 0%, 
                rgba(0,0,0,0.9) 50%, 
                rgba(0,0,0,0.5) 70%,
                rgba(0,0,0,0.2) 85%,
                rgba(0,0,0,0) 100%);
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: linear-gradient(to bottom, 
                transparent 0%,
                transparent 40%,
                var(--bg-primary) 100%);
            z-index: -1;
            pointer-events: none;
        }

        /* Header Section - Neumorphism */
        #header-section {
            background: transparent;
            padding: 20px;
            position: relative;
        }

        #section-logout {
            position: absolute;
            right: 20px;
            top: 20px;
            z-index: 999;
        }

        #section-theme {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 999;
        }

        .logout-btn {
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
        }

        .logout-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        #section-user {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-direction: row-reverse;
            position: relative;
        }

        #user-info {
            line-height: 1.4;
        }

        #user-info h3 {
            color: var(--text-primary);
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }

        #user-info span {
            color: var(--text-secondary);
            font-size: 0.85rem;
            display: block;
        }

        /* Avatar Neumorphism */
        #user-info + a > div {
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light) !important;
        }

        /* Jam Section - Text Only with Gradient Border */
        #section-jam {
            margin-top: 20px;
            padding: 20px;
            text-align: center;
        }

        .jam-wrapper {
            display: inline-block;
            position: relative;
            padding: 3px;
            background: linear-gradient(45deg, 
                #00D25B, #0090E7, #FFB800, #e74c3c, 
                #00D25B, #0090E7, #FFB800, #e74c3c);
            background-size: 400% 400%;
            animation: gradientRotate 3s linear infinite;
            border-radius: 20px;
        }

        .jam-content {
            background: var(--bg-primary);
            padding: 15px 30px;
            border-radius: 18px;
        }

        #jam {
            font-family: 'Arial Black', 'Arial Bold', sans-serif;
            font-weight: 900;
            font-size: 3.5rem;
            line-height: 1;
            margin: 0;
            letter-spacing: 4px;
            color: var(--text-primary);
            text-shadow: 
                -2px -2px 2px rgba(255, 255, 255, 0.6),
                2px 2px 4px rgba(0, 0, 0, 0.2),
                3px 3px 6px rgba(0, 0, 0, 0.15),
                4px 4px 8px rgba(0, 0, 0, 0.1);
        }

        #section-jam .date-text {
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            opacity: 0.85;
            text-shadow: 
                -1px -1px 0px rgba(255, 255, 255, 0.5),
                1px 1px 2px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
            display: block;
        }

        /* Responsive untuk jam */
        @media (max-width: 576px) {
            #jam {
                font-size: 2.8rem;
                letter-spacing: 3px;
            }
            
            .jam-content {
                padding: 12px 25px;
            }
            
            #section-jam .date-text {
                font-size: 0.8rem;
            }
        }

        /* Presensi Card - Neumorphism */
        #section-presensi {
            margin-top: 20px;
        }

        .presensi-wrapper {
            position: relative;
            border-radius: 20px;
            padding: 3px;
            background: linear-gradient(45deg, 
                #00D25B, #0090E7, #FFB800, #e74c3c, 
                #00D25B, #0090E7, #FFB800, #e74c3c);
            background-size: 400% 400%;
            animation: gradientRotate 3s linear infinite;
        }

        @keyframes gradientRotatePresensi {
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

        #section-presensi .card {
            background: var(--bg-primary);
            border: none;
            border-radius: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            padding: 15px;
            margin: 0;
        }

        #presensi-today {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        #presensi-data {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 2;
        }

        /* Animated Worker Illustration */
        .worker-animation {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            z-index: 1;
        }

        .worker-icon {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            text-shadow: 
                2px 2px 4px var(--shadow-dark),
                -2px -2px 4px var(--shadow-light),
                0 0 10px rgba(0, 144, 231, 0.3);
            white-space: nowrap;
            letter-spacing: 0.5px;
            z-index: 2;
        }

        @keyframes walkToWork {
            0% {
                left: 0;
                opacity: 0.3;
            }
            50% {
                opacity: 1;
            }
            100% {
                left: 100%;
                opacity: 0.3;
            }
        }

        .worker-path {
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, 
                rgba(0, 210, 91, 0.2) 0%, 
                rgba(0, 144, 231, 0.2) 25%,
                rgba(255, 184, 0, 0.2) 50%,
                rgba(231, 76, 60, 0.2) 75%,
                rgba(0, 210, 91, 0.2) 100%);
            background-size: 200% 100%;
            animation: pathFlow 3s linear infinite;
        }

        @keyframes pathFlow {
            0% {
                background-position: 0% 0%;
            }
            100% {
                background-position: 200% 0%;
            }
        }

        #presensi-icon {
            font-size: 30px;
            color: var(--icon-color);
        }

        #presensi-icon img {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        #presensi-detail h4 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
            font-size: 0.95rem;
        }

        #presensi-detail .presensi-text {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .outer {
            display: none;
        }

        /* App Section - Neumorphism Cards */
        #app-section {
            padding: 20px;
        }

        #app-section .card {
            background: var(--bg-primary);
            border: none;
            border-radius: 20px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }

        #app-section .card:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        #app-section .card-body {
            padding: 12px 8px !important;
            line-height: 0.9rem;
        }

        #app-section .card img {
            width: 45px;
            margin-bottom: 6px;
        }

        #app-section .card span {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        #app-section .badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
            border: none;
            box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 10px;
        }

        /* Remove default link styles */
        a {
            text-decoration: none;
        }

        /* Histori Section - Neumorphism */
        #histori-section {
            padding: 0 20px 100px;
        }

        #histori-section .nav-tabs {
            border: none;
            background: var(--bg-primary);
            padding: 8px;
            border-radius: 16px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            gap: 8px;
            display: flex;
        }

        #histori-section .nav-tabs .nav-item {
            flex: 1;
        }

        #histori-section .nav-tabs .nav-link {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 12px 8px;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-align: center;
        }

        #histori-section .nav-tabs .nav-link.active {
            background: var(--bg-primary);
            color: var(--text-primary);
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        /* Histori Card - Neumorphism */
        .historicard {
            background: var(--bg-primary);
            border: none;
            border-radius: 18px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            margin-bottom: 18px;
            transition: all 0.3s ease;
        }

        .historicard:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .historibordergreen {
            position: relative;
        }

        .historibordergreen::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 4px;
            background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
            border-radius: 0 4px 4px 0;
        }

        .historiborderred::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 4px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border-radius: 0 4px 4px 0;
        }

        .historicontent {
            padding: 18px 20px;
        }

        .historidetail1 {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 12px;
        }

        .iconpresence {
            color: var(--icon-color);
            flex-shrink: 0;
        }

        .datepresence h4 {
            font-size: 0.95rem;
            color: var(--text-primary);
            font-weight: 600;
            margin: 0 0 6px 0;
        }

        .timepresence {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .historidetail2 {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
        }

        .historidetail2 h4 {
            font-size: 0.85rem;
            color: var(--text-primary);
            font-weight: 600;
            margin: 0 0 4px 0;
        }

        /* KPI Crew Card - Neumorphism */
        .kpi-card {
            background: var(--bg-primary);
            border: none;
            border-radius: 18px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            margin-bottom: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 4px;
            border-radius: 0 4px 4px 0;
        }

        .kpi-card.rank-1::before {
            background: linear-gradient(135deg, #FFB800 0%, #FFA000 100%);
        }

        .kpi-card.rank-2::before {
            background: linear-gradient(135deg, #0090E7 0%, #0080D0 100%);
        }

        .kpi-card.rank-3::before {
            background: linear-gradient(135deg, #00D25B 0%, #00B84A 100%);
        }

        .kpi-card.rank-other::before {
            background: linear-gradient(135deg, #6C757D 0%, #5A6268 100%);
        }

        .kpi-badge {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
            flex-shrink: 0;
        }

        .kpi-badge.gold {
            background: linear-gradient(135deg, #FFB800 0%, #FFA000 100%);
            color: #fff;
        }

        .kpi-badge.silver {
            background: linear-gradient(135deg, #0090E7 0%, #0080D0 100%);
            color: #fff;
        }

        .kpi-badge.bronze {
            background: linear-gradient(135deg, #00D25B 0%, #00B84A 100%);
            color: #fff;
        }

        .kpi-badge.default {
            background: var(--bg-primary);
            color: var(--text-secondary);
            font-weight: bold;
            font-size: 18px;
        }

        .kpi-point {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--text-primary);
        }

        .kpi-point.top-rank {
            color: #00D25B;
        }

        /* Alert Info Neumorphism */
        .alert-neumorphic {
            background: var(--bg-primary);
            border: none;
            border-radius: 16px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            padding: 15px;
            color: var(--text-primary);
        }

        .alert-neumorphic i {
            color: #0090E7;
        }

        /* 3D Text Style - Banking/Emboss Style */
        .text-3d {
            font-family: 'Arial', 'Helvetica Neue', sans-serif;
            font-weight: 900;
            font-size: 1.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
            padding: 20px 0;
            text-align: center;
            color: #e8e8e8;
            text-shadow: 
                -1px -1px 0px rgba(255, 255, 255, 0.8),
                1px 1px 0px rgba(0, 0, 0, 0.15),
                2px 2px 1px rgba(0, 0, 0, 0.1),
                3px 3px 2px rgba(0, 0, 0, 0.08),
                4px 4px 3px rgba(0, 0, 0, 0.06);
            position: relative;
            line-height: 1.2;
        }

        /* Menu Card - Modern Neumorphism Style with Animated Border */
        .menu-card {
            min-height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 10px !important;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .menu-card-wrapper {
            position: relative;
            border-radius: 22px;
            padding: 0;
        }

        .menu-card-wrapper::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(45deg, 
                #00D25B, #0090E7, #FFB800, #e74c3c, 
                #00D25B, #0090E7, #FFB800, #e74c3c);
            background-size: 400% 400%;
            border-radius: 22px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .menu-card-wrapper:hover::before {
            opacity: 1;
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

        .menu-card .card {
            background: var(--bg-primary);
            border: none;
            border-radius: 22px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .menu-card .card:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            transform: scale(0.98);
        }

        /* Icon Container with Embossed Effect */
        .menu-icon-container {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            background: var(--bg-primary);
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .menu-icon {
            font-size: 28px;
            color: #8B95A5;
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .card:active .menu-icon {
            transform: scale(0.95);
            opacity: 1;
        }

        /* Menu Text - Soft Embossed */
        .menu-3d-text {
            font-family: 'Arial', 'Helvetica Neue', sans-serif;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            color: var(--text-primary);
            opacity: 0.8;
            text-shadow: 
                -1px -1px 1px rgba(255, 255, 255, 0.5),
                1px 1px 1px rgba(0, 0, 0, 0.1);
            margin: 0;
            padding: 0;
            line-height: 1.2;
            text-align: center;
        }

        /* Stats Info Card */
        .stats-card {
            background: var(--bg-primary);
            border: none;
            border-radius: 18px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            padding: 15px 20px;
            margin-bottom: 15px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .stat-item {
            text-align: center;
            position: relative;
        }

        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: -7.5px;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 30px;
            background: var(--border-color);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            display: block;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            text-shadow: 
                -1px -1px 0px rgba(255, 255, 255, 0.5),
                1px 1px 1px rgba(0, 0, 0, 0.15);
        }

        .stat-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 12px;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15),
                       -2px -2px 4px rgba(255, 255, 255, 0.3);
        }
    </style>
    <div id="header-section">
        <div id="section-user">
            <div id="section-theme">
                <a href="#" class="logout-btn" id="theme-toggle">
                    <ion-icon name="sunny-outline" id="theme-icon"></ion-icon>
                </a>
            </div>
            <div id="section-logout">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn" style="border: none; cursor: pointer;">
                        <ion-icon name="exit-outline"></ion-icon>
                    </button>
                </form>
            </div>
            <div id="user-info">

            </div>
            <a href="{{ route('profile.index') }}">
                @if (!empty($karyawan->foto))
                    @if (Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                        <div
                            style="width: 80px; height: 80px; background-image: url({{ getfotoKaryawan($karyawan->foto) }}); background-size: cover; background-position: center; border-radius: 50%;">


                        </div>
                    @else
                        <div class="avatar avatar-xs me-2">
                            <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
                        </div>
                    @endif
                @else
                    <div class="avatar avatar-xs me-2">
                        <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
                    </div>
                @endif
            </a>
        </div>
        <div id="section-jam">
            <div class="jam-wrapper">
                <div class="jam-content">
                    <h2 id="jam"></h2>
                </div>
            </div>
            <span class="date-text">Hari ini : {{ getNamaHari(date('D')) }}, {{ DateToIndo(date('Y-m-d')) }}</span>
        </div>
        <div id="section-presensi">
            <div class="presensi-wrapper">
                <div class="card">
                    <div class="card-body" id="presensi-today">
                        <!-- Worker Animation -->
                        <div class="worker-animation">
                            <div class="worker-icon">{{ $karyawan->nama_karyawan }} - {{ $karyawan->nama_jabatan }}</div>
                        </div>
                        
                        <div id="presensi-data">
                            <div id="presensi-icon">
                                @php
                                    $jam_in = $presensi && $presensi->jam_in != null ? $presensi->jam_in : null;
                                @endphp
                                @if ($presensi && $presensi->foto_in != null)
                                    @php
                                        $path = Storage::url('uploads/absensi/' . $presensi->foto_in . '?v=' . time());
                                    @endphp
                                    <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div id="presensi-detail">
                                <h4>Jam Masuk</h4>
                                <span class="presensi-text">
                                    @if ($jam_in != null)
                                        {{ date('H:i', strtotime($jam_in)) }}
                                    @else
                                        <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                    @endif
                                </span>
                            </div>

                        </div>
                        <div class="outer">
                            <div class="inner"></div>
                        </div>
                        <div id="presensi-data">
                            <div id="presensi-icon">
                                @php
                                    $jam_out = $presensi && $presensi->jam_out != null ? $presensi->jam_out : null;
                                @endphp
                                @if ($presensi && $presensi->foto_out != null)
                                    @php
                                        $path = Storage::url('uploads/absensi/' . $presensi->foto_out . '?v=' . time());
                                    @endphp
                                    <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div id="presensi-detail">
                                <h4>Jam Pulang</h4>
                                <span class="presensi-text">
                                    @if ($jam_out != null)
                                        {{ date('H:i', strtotime($jam_out)) }}
                                    @else
                                        <i class="ti ti-hourglass-low text-warning"></i> Belum Absen
                                    @endif
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="app-section">
        <!-- Stats Info Card -->
        <div class="stats-card">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Hadir</span>
                    <div class="stat-badge" style="background: linear-gradient(135deg, #00D25B 0%, #00B84A 100%);">
                        {{ $rekappresensi ? $rekappresensi->hadir : 0 }}
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Sakit</span>
                    <div class="stat-badge" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                        {{ $rekappresensi ? $rekappresensi->sakit : 0 }}
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Izin</span>
                    <div class="stat-badge" style="background: linear-gradient(135deg, #FFB800 0%, #FFA000 100%);">
                        {{ $rekappresensi ? $rekappresensi->izin : 0 }}
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Cuti</span>
                    <div class="stat-badge" style="background: linear-gradient(135deg, #0090E7 0%, #0080D0 100%);">
                        {{ $rekappresensi ? $rekappresensi->cuti : 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-3">
                <a href="{{ route('karyawan.idcard', Crypt::encrypt($karyawan->nik)) }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-id-badge menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">ID Card</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('presensiistirahat.create') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-cup menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Istirahat</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('lembur.index') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-clock-hour-9 menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Lembur</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-3">
                <a href="{{ route('slipgaji.index') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-wallet menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Slip Gaji</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row mt-2">
            @can('aktivitaskaryawan.index')
                <div class="col-3">
                    <a href="{{ route('aktivitaskaryawan.index') }}">
                        <div class="menu-card-wrapper">
                            <div class="card">
                                <div class="card-body menu-card">
                                    <div class="menu-icon-container">
                                        <i class="ti ti-checklist menu-icon"></i>
                                    </div>
                                    <div class="menu-3d-text">Aktivitas</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
            @can('kunjungan.index')
                <div class="col-3">
                    <a href="{{ route('kunjungan.index') }}">
                        <div class="menu-card-wrapper">
                            <div class="card">
                                <div class="card-body menu-card">
                                    <div class="menu-icon-container">
                                        <i class="ti ti-map-pin menu-icon"></i>
                                    </div>
                                    <div class="menu-3d-text">Kunjungan</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
            <div class="col-3">
                <a href="{{ route('fasilitas.dashboard.karyawan') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-building-community menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Fasilitas</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('saungsantri.dashboard.karyawan') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-book-2 menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Saung<br>Santri</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('manajemen-yayasan.karyawan.dashboard') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-building-mosque menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Yayasan</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('tukang.karyawan.index') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-tools menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Tukang</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('perawatan.karyawan.index') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-settings menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Perawatan</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('laporan-keuangan-karyawan.index') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-report-money menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Laporan</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('temuan.karyawan.list') }}">
                    <div class="menu-card-wrapper">
                        <div class="card">
                            <div class="card-body menu-card">
                                <div class="menu-icon-container">
                                    <i class="ti ti-alert-circle menu-icon"></i>
                                </div>
                                <div class="menu-3d-text">Temuan</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div id="histori-section">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#historipresensi" role="tab">
                        30 Hari terakhir
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kpicrew" role="tab">
                        KPI Crew <i class="ti ti-chart-line"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="historipresensi" role="tabpanel">
                <div class="row mb-1">
                    <div class="col">
                        {{-- {{ $d->jam_out != null ? 'historibordergreen' : 'historiborderred' }} --}}
                        @foreach ($datapresensi as $d)
                            @if ($d->status == 'h')
                                @php
                                    $jam_in = date('Y-m-d H:i', strtotime($d->jam_in));
                                    $jam_masuk = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_masuk));
                                @endphp
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="finger-print-outline" style="font-size: 48px"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <span class="timepresence">
                                                    @if ($d->jam_in != null)
                                                        {{ date('H:i', strtotime($d->jam_in)) }}
                                                    @else
                                                        <span class="text-danger">
                                                            <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                        </span>
                                                    @endif
                                                    -
                                                    @if ($d->jam_out != null)
                                                        {{ date('H:i', strtotime($d->jam_out)) }}
                                                    @else
                                                        <span class="text-danger">
                                                            <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                        </span>
                                                    @endif
                                                </span>

                                                @if ($d->istirahat_in != null)
                                                    <br>
                                                    <span class="timepresence text-info">
                                                        {{ date('H:i', strtotime($d->istirahat_in)) }} -
                                                        @if ($d->istirahat_out != null)
                                                            {{ date('H:i', strtotime($d->istirahat_out)) }}
                                                        @else
                                                            <ion-icon name="hourglass-outline"></ion-icon>
                                                        @endif
                                                    </span>
                                                @endif
                                                <br>
                                                @if ($d->jam_in != null)
                                                    @php
                                                        $terlambat = hitungjamterlambat(
                                                            date('H:i', strtotime($jam_in)),
                                                            date('H:i', strtotime($jam_masuk)),
                                                        );

                                                    @endphp
                                                    {!! $terlambat['show'] !!}
                                                @endif


                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($d->status == 'i')
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <h4 class="timepresence">
                                                    Izin Absen
                                                </h4>
                                                <span>{{ $d->keterangan_izin }}</span>
                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($d->status == 'i')
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <h4 class="timepresence">
                                                    Izin Cuti
                                                </h4>
                                                <span>{{ $d->keterangan_cuti }}</span>
                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($d->status == 's')
                                <div class="card historicard historibordergreen mb-1">
                                    <div class="historicontent">
                                        <div class="historidetail1">
                                            <div class="iconpresence">
                                                <ion-icon name="bag-add-outline" style="font-size: 48px; color: #d4095a"></ion-icon>
                                            </div>
                                            <div class="datepresence">
                                                <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                                <h4 class="timepresence">
                                                    Izin Sakit
                                                </h4>
                                                <span>{{ $d->keterangan_sakit }}</span>
                                            </div>
                                        </div>
                                        <div class="historidetail2">
                                            <h4>{{ $d->nama_jam_kerja }}</h4>
                                            <span class="timepresence">
                                                {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                {{ date('H:i', strtotime($d->jam_pulang)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="kpicrew" role="tabpanel">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="alert-neumorphic">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-info-circle me-2" style="font-size: 24px;"></i>
                                <div>
                                    <strong>KPI Crew</strong> - Peringkat kinerja karyawan bulan ini
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(isset($kpiData) && $kpiData->count() > 0)
                    @foreach ($kpiData as $index => $kpi)
                        @if($kpi->karyawan)
                            <div class="kpi-card rank-{{ $kpi->ranking <= 3 ? $kpi->ranking : 'other' }}">
                                <div style="padding: 15px 20px;">
                                    <div class="d-flex align-items-center">
                                        <!-- Ranking Badge -->
                                        <div class="me-3">
                                            @if($kpi->ranking == 1)
                                                <div class="kpi-badge gold">
                                                    <i class="ti ti-trophy"></i>
                                                </div>
                                            @elseif($kpi->ranking == 2)
                                                <div class="kpi-badge silver">
                                                    <i class="ti ti-medal"></i>
                                                </div>
                                            @elseif($kpi->ranking == 3)
                                                <div class="kpi-badge bronze">
                                                    <i class="ti ti-award"></i>
                                                </div>
                                            @else
                                                <div class="kpi-badge default">
                                                    #{{ $kpi->ranking }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Karyawan Info -->
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: var(--text-primary);">
                                                {{ $kpi->karyawan->nama_karyawan }}
                                            </h6>
                                        </div>
                                        
                                        <!-- Total Point -->
                                        <div class="text-end ms-2">
                                            <div class="kpi-point {{ $kpi->ranking <= 3 ? 'top-rank' : '' }}">
                                                {{ number_format($kpi->total_point, 0) }}
                                            </div>
                                            <small style="font-size: 0.7rem; color: var(--text-secondary);">Point</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="historicard" style="border: none;">
                        <div style="padding: 30px 20px; text-align: center;">
                            <i class="ti ti-mood-sad" style="font-size: 48px; color: var(--text-secondary); opacity: 0.5;"></i>
                            <p class="mt-2 mb-0" style="color: var(--text-secondary);">Belum ada data KPI untuk bulan ini</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }

        // Theme Toggle
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
    </script>
@endpush

<!-- Include Informasi Banner Component -->
@include('components.informasi-banner')

