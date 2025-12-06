@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            /* Minimal Elegant Colors */
            --bg-body-light: #ecf0f3;
            --bg-primary-light: #ecf0f3;
            --shadow-dark-light: #d1d9e6;
            --shadow-light-light: #ffffff;
            --text-primary-light: #2c3e50;
            --text-secondary-light: #6c7a89;
            --border-light: rgba(0, 0, 0, 0.05);

            --bg-body-dark: #1a202c;
            --bg-primary-dark: #2d3748;
            --shadow-dark-dark: #141923;
            --shadow-light-dark: #3a4555;
            --text-primary-dark: #f7fafc;
            --text-secondary-dark: #a0aec0;
            --border-dark: rgba(255, 255, 255, 0.08);

            /* Accent Colors */
            --gradient-1: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            --gradient-2: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        /* Apply light mode by default */
        body {
            --bg-body: var(--bg-body-light);
            --bg-primary: var(--bg-primary-light);
            --shadow-dark: var(--shadow-dark-light);
            --shadow-light: var(--shadow-light-light);
            --text-primary: var(--text-primary-light);
            --text-secondary: var(--text-secondary-light);
            --border-color: var(--border-light);
        }

        /* Dark mode */
        body.dark-mode {
            --bg-body: var(--bg-body-dark);
            --bg-primary: var(--bg-primary-dark);
            --shadow-dark: var(--shadow-dark-dark);
            --shadow-light: var(--shadow-light-dark);
            --text-primary: var(--text-primary-dark);
            --text-secondary: var(--text-secondary-dark);
            --border-color: var(--border-dark);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-body);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ========== HEADER ========== */
        #header-section {
            background: var(--bg-primary);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .back-btn {
            background: var(--bg-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .back-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .back-btn ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        .header-title {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header-title h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .header-title p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin-top: 5px;
            letter-spacing: 0.3px;
        }

        /* ========== CONTENT ========== */
        #content-section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        /* ========== SECTION TITLE ========== */
        .section-title {
            color: var(--text-primary);
            font-weight: 800;
            margin: 0 0 20px 0;
            padding-left: 15px;
            border-left: 4px solid var(--text-primary);
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ========== BUTTON INPUT ABSENSI ========== */
        .btn-input-absensi {
            background: var(--bg-primary);
            padding: 18px 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-input-absensi:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        .btn-input-absensi span {
            color: var(--text-primary);
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ========== FILTER CARD ========== */
        .filter-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .form-label {
            color: var(--text-secondary);
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-select {
            background: var(--bg-primary);
            border: none;
            padding: 12px 15px;
            border-radius: 12px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            color: var(--text-primary);
            font-weight: 700;
            font-size: 0.85rem;
        }

        .form-select:focus {
            outline: none;
        }

        .btn-primary {
            background: var(--bg-primary);
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            color: var(--text-primary);
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-primary:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        /* ========== ABSENSI CARD ========== */
        .absensi-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 0;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .absensi-header {
            background: var(--gradient-1);
            color: white;
            padding: 18px 20px;
            border-radius: 0;
        }

        .absensi-header-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .absensi-header-subtitle {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .absensi-body {
            padding: 20px;
        }

        .santri-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .santri-item:last-child {
            border-bottom: none;
        }

        .santri-info {
            flex: 1;
        }

        .santri-name {
            font-weight: 800;
            color: var(--text-primary);
            font-size: 0.95rem;
            margin-bottom: 3px;
        }

        .santri-keterangan {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .badge-custom {
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .badge-hadir {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .badge-ijin {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .badge-sakit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .badge-khidmat {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .badge-absen {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        /* ========== NO DATA ========== */
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .no-data i {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 15px;
        }

        .no-data strong {
            color: var(--text-primary);
            font-size: 1.1rem;
            display: block;
            margin-bottom: 8px;
        }

        .no-data small {
            color: var(--text-secondary);
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('jadwal-santri.karyawan.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Detail Jadwal</h3>
                <p>{{ $jadwalSantri->nama_jadwal }}</p>
            </div>
        </div>
    </div>

    <div id="content-section">
        {{-- Section Title --}}
        <div class="section-title">
            <i class="ti ti-checkbox"></i> Riwayat Absensi
        </div>

        {{-- Tombol Input Absensi --}}
        <a href="{{ route('absensi-santri.karyawan.create', $jadwalSantri->id) }}" class="btn-input-absensi">
            <span>Input Absensi</span>
        </a>

        {{-- Filter --}}
        <div class="filter-card">
            <form action="{{ route('jadwal-santri.karyawan.show', $jadwalSantri->id) }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-5">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="ti ti-filter"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Daftar Absensi --}}
        @if($absensiPerTanggal->count() > 0)
            @foreach($absensiPerTanggal as $tanggal => $absensiList)
            <div class="absensi-card">
                <div class="absensi-header">
                    <div class="absensi-header-title">
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="absensi-header-subtitle">
                        {{ $absensiList->count() }} santri tercatat
                    </div>
                </div>

                <div class="absensi-body">
                    @foreach($absensiList as $absensi)
                    <div class="santri-item">
                        <div class="santri-info">
                            <div class="santri-name">{{ $absensi->santri->nama_lengkap ?? 'Santri Tidak Ditemukan' }}</div>
                            @if($absensi->keterangan)
                                <div class="santri-keterangan">{{ $absensi->keterangan }}</div>
                            @endif
                        </div>
                        <span class="badge-custom badge-{{ $absensi->status_kehadiran }}">
                            {{ ucfirst($absensi->status_kehadiran) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
            <div class="no-data">
                <i class="ti ti-calendar-off"></i>
                <p><strong>Belum ada data absensi</strong></p>
                <small>Belum ada absensi untuk periode {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</small>
            </div>
        @endif

        <div style="height: 80px;"></div>
    </div>
@endsection
