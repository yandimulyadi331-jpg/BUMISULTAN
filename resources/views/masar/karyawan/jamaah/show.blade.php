@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --primary-color: #2F5D62;
            --bg-main: #e8f0f2;
            --shadow-light: #ffffff;
            --shadow-dark: #c5d3d5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-main);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Section */
        #header-section {
            background: var(--bg-main);
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
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .logo-subtitle {
            font-size: 0.7rem;
            color: var(--primary-color);
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .back-btn {
            background: var(--bg-main);
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

        .back-btn:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .back-btn ion-icon {
            font-size: 24px;
            color: var(--primary-color);
        }

        #content-section {
            padding: 20px;
        }

        .profile-card {
            background: var(--bg-main);
            border-radius: 25px;
            padding: 30px 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            margin-bottom: 20px;
        }

        .profile-header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .profile-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--bg-main);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 50px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 8px 0;
        }

        .profile-number {
            color: #5a7c7f;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 12px;
        }

        .info-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid rgba(47, 93, 98, 0.1);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            flex: 0 0 120px;
            font-weight: 600;
            color: #5a7c7f;
            font-size: 0.85rem;
        }

        .info-value {
            flex: 1;
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .badge {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #27ae60 0%, #52c77a 100%);
            color: white;
            box-shadow: 0 3px 8px rgba(39, 174, 96, 0.3);
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #ec7063 100%);
            color: white;
            box-shadow: 0 3px 8px rgba(231, 76, 60, 0.3);
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #3498db 0%, #5dade2 100%);
            color: white;
            box-shadow: 0 3px 8px rgba(52, 152, 219, 0.3);
        }

        .badge.bg-secondary {
            background: var(--bg-main);
            color: var(--primary-color);
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        .stats-card {
            background: var(--bg-main);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
        }

        .stats-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .stat-item {
            text-align: center;
            background: var(--bg-main);
            padding: 20px 15px;
            border-radius: 15px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #5a7c7f;
            font-weight: 500;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 30px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .list-card {
            background: var(--bg-main);
            border-radius: 15px;
            padding: 18px;
            margin-bottom: 12px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .list-card-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .list-card-title ion-icon {
            font-size: 20px;
        }

        .list-card-detail {
            font-size: 0.85rem;
            color: #5a7c7f;
            line-height: 1.8;
        }

        .list-card-detail div {
            margin-bottom: 4px;
        }

        .list-card-detail strong {
            color: var(--primary-color);
            font-weight: 600;
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Detail Jamaah</span>
            <span class="logo-subtitle">MASAR</span>
        </div>
        <a href="{{ route('masar.karyawan.jamaah.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <div id="content-section">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    @if($jamaah->foto)
                        <img src="{{ asset('storage/' . $jamaah->foto) }}" alt="{{ $jamaah->nama_jamaah }}">
                    @else
                        <ion-icon name="person-outline"></ion-icon>
                    @endif
                </div>
                <h5 class="profile-name">{{ $jamaah->nama_jamaah }}</h5>
                <p class="profile-number">{{ $jamaah->nomor_jamaah }}</p>
                @if($jamaah->status_aktif == 'aktif')
                    <span class="badge bg-success">
                        <ion-icon name="checkmark-circle-outline"></ion-icon> Aktif
                    </span>
                @else
                    <span class="badge bg-danger">
                        <ion-icon name="close-circle-outline"></ion-icon> Non Aktif
                    </span>
                @endif
            </div>

            <div class="info-item">
                <div class="info-label">NIK</div>
                <div class="info-value">{{ $jamaah->nik ?: '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">No. Telepon</div>
                <div class="info-value">{{ $jamaah->no_telepon ?: '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Alamat</div>
                <div class="info-value">{{ $jamaah->alamat ?: '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tahun Masuk</div>
                <div class="info-value">{{ $jamaah->tahun_masuk }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status Umroh</div>
                <div class="info-value">
                    @if($jamaah->status_umroh)
                        <span class="badge bg-info">
                            <ion-icon name="airplane-outline"></ion-icon> Sudah Umroh
                        </span>
                    @else
                        <span class="badge bg-secondary">Belum Umroh</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="stats-card">
            <div class="stats-header">
                <ion-icon name="stats-chart-outline"></ion-icon>
                Statistik Kehadiran
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ $jamaah->jumlah_kehadiran }}</div>
                    <div class="stat-label">Total Hadir</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="font-size: 1.2rem;">
                        @if($jamaah->kehadiran->count() > 0)
                            {{ $jamaah->kehadiran->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="stat-label">Terakhir Hadir</div>
                </div>
            </div>
        </div>

        <!-- Riwayat Hadiah -->
        @if($jamaah->distribusiHadiah->count() > 0)
            <div class="section-title">
                Riwayat Hadiah Diterima
            </div>
            @foreach($jamaah->distribusiHadiah as $distribusi)
                <div class="list-card">
                    <div class="list-card-title">
                        <ion-icon name="gift-outline"></ion-icon>
                        {{ $distribusi->hadiah->nama_hadiah ?? '-' }}
                    </div>
                    <div class="list-card-detail">
                        <div><strong>Tanggal:</strong> {{ date('d/m/Y', strtotime($distribusi->tanggal_distribusi)) }}</div>
                        <div><strong>Jumlah:</strong> {{ $distribusi->jumlah }}</div>
                        @if($distribusi->ukuran)
                            <div><strong>Ukuran:</strong> {{ $distribusi->ukuran }}</div>
                        @endif
                        @if($distribusi->keterangan)
                            <div><strong>Keterangan:</strong> {{ $distribusi->keterangan }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <div style="height: 80px;"></div>
    </div>
@endsection
            color: #333;
            margin-bottom: 8px;
        }

        .list-card-detail {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.6;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('masar.karyawan.jamaah.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Detail Lengkap</h3>
            <p>MASAR</p>
        </div>
    </div>

    <div id="content-section">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%); margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px;">
                    <ion-icon name="person-outline"></ion-icon>
                </div>
                <h5 class="profile-name">{{ $jamaah->nama_jamaah }}</h5>
                <p class="profile-number">{{ $jamaah->nomor_jamaah }}</p>
                @if($jamaah->status_aktif == 'aktif')
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Non Aktif</span>
                @endif
            </div>

            <div class="info-item">
                <div class="info-label">NIK</div>
                <div class="info-value">{{ $jamaah->nik ?: '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Alamat</div>
                <div class="info-value">{{ $jamaah->alamat ?: '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">No. Telepon</div>
                <div class="info-value">{{ $jamaah->no_telepon ?: '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tahun Masuk</div>
                <div class="info-value">{{ $jamaah->tahun_masuk }}</div>
            </div>
            <div class="info-item" style="border-bottom: none;">
                <div class="info-label">Status Umroh</div>
                <div class="info-value">
                    @if($jamaah->status_umroh)
                        <span class="badge bg-info">
                            <ion-icon name="airplane-outline"></ion-icon> Sudah Umroh
                        </span>
                    @else
                        <span class="badge bg-secondary">Belum Umroh</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="stats-card">
            <h6 style="margin: 0 0 5px 0; font-weight: bold;">
                <ion-icon name="stats-chart-outline"></ion-icon>
                Statistik Kehadiran
            </h6>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ $jamaah->jumlah_kehadiran }}</div>
                    <div class="stat-label">Total Hadir</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        @if($jamaah->kehadiran->count() > 0)
                            {{ $jamaah->kehadiran->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="stat-label">Terakhir Hadir</div>
                </div>
            </div>
        </div>

        <!-- Riwayat Hadiah -->
        @if($jamaah->distribusiHadiah->count() > 0)
            <h6 class="section-title">Riwayat Hadiah Diterima</h6>
            @foreach($jamaah->distribusiHadiah as $distribusi)
                <div class="list-card">
                    <div class="list-card-title">
                        <ion-icon name="gift-outline"></ion-icon>
                        {{ $distribusi->hadiah->nama_hadiah ?? '-' }}
                    </div>
                    <div class="list-card-detail">
                        <div><strong>Tanggal:</strong> {{ date('d/m/Y', strtotime($distribusi->tanggal_distribusi)) }}</div>
                        <div><strong>Jumlah:</strong> {{ $distribusi->jumlah }}</div>
                        @if($distribusi->ukuran)
                            <div><strong>Ukuran:</strong> {{ $distribusi->ukuran }}</div>
                        @endif
                        @if($distribusi->keterangan)
                            <div><strong>Keterangan:</strong> {{ $distribusi->keterangan }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <div style="height: 80px;"></div>
    </div>
@endsection

