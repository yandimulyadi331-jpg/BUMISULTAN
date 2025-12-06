@extends('layouts.mobile.app')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Dark Mode Variables */
        :root {
            --bg-primary: #e8eef3;
            --bg-secondary: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --shadow-light: rgba(255, 255, 255, 0.9);
            --shadow-dark: rgba(94, 104, 121, 0.3);
            --border-color: #bdc3c7;
            --icon-color: #3498db;
        }

        body.dark-mode {
            --bg-primary: #2c3e50;
            --bg-secondary: #34495e;
            --text-primary: #ecf0f1;
            --text-secondary: #bdc3c7;
            --shadow-light: rgba(255, 255, 255, 0.05);
            --shadow-dark: rgba(0, 0, 0, 0.3);
            --border-color: #34495e;
            --icon-color: #3498db;
        }

        body {
            background: var(--bg-primary);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            transition: background 0.3s ease;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

        .logo-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .theme-toggle {
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
        }

        .theme-toggle:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .theme-toggle ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        /* Hide global theme toggle from layout */
        body > #theme-toggle {
            display: none !important;
        }

        /* Content Section */
        #content-section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        /* Filter Card with Animated Rainbow Border */
        .filter-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            position: relative;
            overflow: visible;
        }

        .filter-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #ff0000, #ff7f00, #ffff00, #00ff00,
                #0000ff, #4b0082, #9400d3, #ff0000
            );
            background-size: 400% 400%;
            border-radius: 22px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .filter-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 20px;
            z-index: -1;
        }

        @keyframes gradientMove {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .filter-input {
            background: var(--bg-primary);
            border: none;
            border-radius: 12px;
            padding: 14px 18px;
            width: 100%;
            margin-bottom: 12px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
        }

        .filter-input::placeholder {
            color: var(--text-secondary);
        }

        .btn-search {
            background: linear-gradient(145deg, #2196F3, #1976D2);
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-search:active {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2),
                       inset -4px -4px 8px rgba(255, 255, 255, 0.1);
            transform: scale(0.98);
        }

        /* Slip Card with Animated Rainbow Border */
        .slip-card {
            background: var(--bg-primary);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: visible;
        }

        .slip-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #00ff00, #00ff7f, #00ffff, #00bfff,
                #007fff, #003fff, #007fff, #00bfff,
                #00ffff, #00ff7f, #00ff00
            );
            background-size: 400% 400%;
            border-radius: 20px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .slip-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 18px;
            z-index: -1;
        }

        .slip-card:active {
            transform: scale(0.98);
        }

        .slip-card .badge {
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .slip-card .actions {
            margin-top: 12px;
            display: flex;
            gap: 8px;
        }

        .btn-outline-primary {
            background: var(--bg-primary);
            color: #2196F3;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 0.9rem;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .btn-outline-primary:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.95);
        }

        .btn-outline-danger {
            background: var(--bg-primary);
            color: #e74c3c;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 0.9rem;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .btn-outline-danger:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.95);
        }

        .btn-success {
            background: linear-gradient(145deg, #27ae60, #229954);
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-success:active {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2),
                       inset -4px -4px 8px rgba(255, 255, 255, 0.1);
            transform: scale(0.98);
        }

        .alert-warning {
            background: var(--bg-primary);
            border: none;
            border-radius: 16px;
            color: var(--text-primary);
            padding: 16px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            text-align: center;
        }

        .input-group {
            background: #fff;
            border-radius: 10px;
            box-shadow: var(--md-shadow);
            margin-bottom: 18px;
            padding: 10px 8px;
            display: flex;
            gap: 8px;
        }

        .input-group select,
        .input-group button {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            font-size: 1em;
            padding: 7px 12px;
        }

        .input-group button {
            background: var(--md-primary);
            color: #fff;
            border: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .input-group button:active {
            background: var(--md-primary-dark);
        }

        /* Typography */
        .slip-card strong {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.05em;
        }

        .slip-card .label {
            color: var(--text-secondary);
            font-size: 0.98em;
        }

        /* Ripple effect - removed as not needed */
    </style>

    <div id="header-section">
        <a href="{{ route('dashboard.index') }}" class="back-button">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="logo-wrapper">
            <div class="logo-title">Slip Gaji</div>
        </div>
        <button id="theme-toggle-slipgaji" class="theme-toggle">
            <ion-icon name="sunny-outline"></ion-icon>
        </button>
    </div>

    <div id="content-section">
        <div class="filter-card">
            <form action="{{ route('slipgaji.index') }}" method="GET">
                <select name="tahun" id="tahun" class="filter-input" required>
                    <option value="" disabled {{ !request('tahun') ? 'selected' : '' }}>Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option {{ request('tahun', date('Y')) == $t ? 'selected' : '' }} value="{{ $t }}">
                            {{ $t }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn-search">
                    <ion-icon name="search-outline"></ion-icon> Cari
                </button>
            </form>
        </div>
        </div>

        @can('slipgaji.create')
            <a href="#" class="btn-success" id="btnCreate" style="margin-bottom: 20px;">
                <ion-icon name="add-circle-outline"></ion-icon> Buat Slip Gaji
            </a>
        @endcan

        @if (count($slipgaji))
            @foreach ($slipgaji as $d)
                <a href="/laporan/cetakslipgaji?bulan={{ $d->bulan }}&tahun={{ $d->tahun }}&periode_laporan=1">
                    <div class="slip-card">
                        <div class="d-flex align-items-start" style="gap:14px;">
                            <div
                                style="flex-shrink:0;display:flex;align-items:center;justify-content:center;width:56px;height:56px;background:#27ae60;border-radius:14px;z-index:1;">
                                <ion-icon name="document-text-outline" style="font-size:2.5em;color:#fff;"></ion-icon>
                            </div>
                            <div style="flex:1;z-index:1;">
                                <div class="mb-2">
                                    <div style="font-weight:700;color:var(--text-primary);font-size:1.13em;">Slip Gaji Bulan
                                        {{ getNamabulan($d->bulan) }} Tahun {{ $d->tahun }}</div>
                                    <div><span class="label" style="font-weight:500;">Periode:</span>
                                        <span style="color:#27ae60;font-weight:600;">

                                            @php
                                                $periode_laporan_dari = $general_setting->periode_laporan_dari;
                                                $periode_laporan_sampai = $general_setting->periode_laporan_sampai;
                                                $periode_laporan_lintas_bulan =
                                                    $general_setting->periode_laporan_next_bulan;

                                                if ($periode_laporan_lintas_bulan == 1) {
                                                    if ($d->bulan == 1) {
                                                        $bulan = 12;
                                                        $tahun = $d->tahun - 1;
                                                    } else {
                                                        $bulan = $d->bulan - 1;
                                                        $tahun = $d->tahun;
                                                    }
                                                } else {
                                                    $bulan = $d->bulan;
                                                    $tahun = $d->tahun;
                                                }

                                                // Menambahkan nol di depan bulan jika bulan kurang dari 10

                                                $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                                                $bulan_next = str_pad($d->bulan, 2, '0', STR_PAD_LEFT);
                                                $periode_dari = $tahun . '-' . $bulan . '-' . $periode_laporan_dari;
                                                $periode_sampai =
                                                    $tahun . '-' . $bulan_next . '-' . $periode_laporan_sampai;

                                            @endphp
                                            {{ DateToIndo($periode_dari) }}
                                            - {{ DateToIndo($periode_sampai) }}</span>
                                    </div>

                                </div>
                                <div class="actions d-flex">
                                    @can('slipgaji.edit')
                                        <a href="#" class="btn btn-outline-primary btnEdit me-2"
                                            kode_slip_gaji="{{ Crypt::encrypt($d->kode_slip_gaji) }}">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                    @endcan
                                    @can('slipgaji.delete')
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('slipgaji.delete', Crypt::encrypt($d->kode_slip_gaji)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger delete-confirm"><i
                                                    class="ti ti-trash"></i> Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <div class="alert alert-warning">Tidak ada data slip gaji.</div>
        @endif
    </div>

    <!-- App Bottom Menu - Neumorphism Style -->
    <style>
        .appBottomMenu {
            background: var(--bg-primary);
            border-top: none;
            box-shadow: 0 -8px 20px var(--shadow-dark);
            padding: 8px 16px 12px;
            border-radius: 0;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .appBottomMenu .item {
            transition: all 0.3s ease;
            color: var(--text-secondary);
            position: relative;
            padding: 4px 8px;
            flex: 1;
            text-decoration: none;
        }

        .appBottomMenu .item .col {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-height: 60px;
        }

        .appBottomMenu .item ion-icon {
            font-size: 26px;
            transition: all 0.3s ease;
        }

        .appBottomMenu .item strong {
            font-size: 10px;
            font-weight: 600;
            margin-top: 2px;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }

        /* Active State with Neumorphism Background */
        .appBottomMenu .item.active .col {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 8px 12px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .appBottomMenu .item.active {
            color: var(--text-primary);
        }

        .appBottomMenu .item.active ion-icon {
            color: var(--text-primary);
            transform: scale(1.1);
        }

        .appBottomMenu .item.active strong {
            color: var(--text-primary);
            font-weight: 700;
        }

        /* Center Button - Elevated Neumorphism */
        .appBottomMenu .action-button {
            background: linear-gradient(145deg, var(--gradient-start), var(--gradient-end));
            box-shadow: 10px 10px 20px var(--shadow-dark),
                       -10px -10px 20px var(--shadow-light);
            width: 64px;
            height: 64px;
            border-radius: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: -20px;
        }

        .appBottomMenu .action-button:active {
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
            transform: translateY(2px);
        }

        .appBottomMenu .action-button ion-icon {
            color: var(--icon-color);
            font-size: 32px;
        }

        /* Hover effect for non-active items */
        .appBottomMenu .item:not(.active):hover ion-icon {
            color: var(--text-primary);
            transform: scale(1.05);
        }

        /* Gradient variables for button */
        :root {
            --gradient-start: #4CAF50;
            --gradient-end: #45a049;
        }

        body.dark-mode {
            --gradient-start: #2d5a2f;
            --gradient-end: #245024;
        }
    </style>

    <div class="appBottomMenu">
        <a href="/dashboard" class="item">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="{{ route('presensi.histori') }}" class="item">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated" aria-label="document text outline"></ion-icon>
                <strong>Histori</strong>
            </div>
        </a>

        <a href="/presensi/create" class="item">
            <div class="col">
                <div class="action-button large">
                    <ion-icon name="finger-print-outline"></ion-icon>
                </div>
            </div>
        </a>
        <a href="{{ route('pengajuanizin.index') }}" class="item">
            <div class="col">
                <ion-icon name="calendar-outline"></ion-icon>
                <strong>Pengajuan Izin</strong>
            </div>
        </a>
        <a href="{{ route('users.editpassword', Crypt::encrypt(Auth::user()->id)) }}" class="item">
            <div class="col">
                <ion-icon name="settings-outline"></ion-icon>
                <strong>Setting</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->
@endsection

@push('myscript')
    <script>
        // Dark Mode Toggle for Slip Gaji
        const themeToggleBtn = document.getElementById('theme-toggle-slipgaji');
        const themeIcon = themeToggleBtn.querySelector('ion-icon');
        
        // Get current theme from localStorage
        let currentTheme = localStorage.getItem('theme') || 'light';
        
        // Apply theme on page load
        function applyTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                themeIcon.setAttribute('name', 'moon-outline');
            } else {
                document.body.classList.remove('dark-mode');
                themeIcon.setAttribute('name', 'sunny-outline');
            }
        }
        
        // Apply saved theme
        applyTheme(currentTheme);
        
        // Toggle theme on click
        themeToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            currentTheme = localStorage.getItem('theme') || 'light';
            
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
