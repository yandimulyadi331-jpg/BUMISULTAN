@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --card-bg: #ffffff;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --bg-light: #f9fafb;
        }

        body {
            background: var(--bg-light);
            min-height: 100vh;
        }

        #header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 20px 20px 80px;
            position: relative;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.2);
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            color: #ffffff;
            font-size: 28px;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            padding: 0 50px;
        }

        #header-title h1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        #header-title .subtitle {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 5px;
        }

        #content-section {
            padding: 20px 15px 100px;
            margin-top: -50px;
            position: relative;
            z-index: 2;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 16px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card.success::before {
            background: linear-gradient(90deg, var(--success-color), #34d399);
        }

        .stat-card .number {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .stat-card .label {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .tukang-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .tukang-card {
            background: white;
            border-radius: 28px;
            padding: 0;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            color: inherit;
            overflow: hidden;
            position: relative;
            cursor: default;
        }

        .tukang-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.12);
        }

        .tukang-card-header {
            height: 140px;
            background: linear-gradient(135deg, #a8c0ff 0%, #c0d8ff 50%, #e0eaff 100%);
            position: relative;
            overflow: hidden;
        }

        .tukang-card-header::before {
            content: '';
            position: absolute;
            top: 20px;
            right: 20px;
            width: 80px;
            height: 50px;
            background: rgba(255,255,255,0.7);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            filter: blur(20px);
        }

        .tukang-card-header::after {
            content: '';
            position: absolute;
            bottom: 30px;
            left: -10px;
            width: 100px;
            height: 60px;
            background: rgba(255,255,255,0.6);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            filter: blur(25px);
        }

        .tukang-card-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tukang-avatar-container {
            text-align: center;
            margin-top: -45px;
            position: relative;
            z-index: 2;
            padding: 0 20px;
        }

        .tukang-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            overflow: hidden;
            border: 5px solid white;
            position: relative;
        }

        .tukang-avatar::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tukang-card:hover .tukang-avatar::after {
            opacity: 1;
        }

        .tukang-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tukang-avatar ion-icon {
            font-size: 42px;
            color: var(--text-muted);
        }

        .tukang-card-body {
            padding: 15px 20px 20px;
            text-align: center;
        }

        .tukang-badge-top {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 3;
        }

        .badge-status {
            background: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .badge-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .badge-status.active {
            color: var(--success-color);
        }

        .badge-status.active::before {
            background: var(--success-color);
        }

        .badge-status.inactive {
            color: #6b7280;
        }

        .badge-status.inactive::before {
            background: #6b7280;
        }

        .tukang-name {
            font-size: 17px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .tukang-description {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 16px;
            line-height: 1.5;
            min-height: 32px;
        }

        .info-detail {
            background: #f8f9fb;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            text-align: left;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-row ion-icon {
            font-size: 16px;
            color: var(--primary-color);
            margin-top: 1px;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 9px;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 11px;
            color: var(--text-dark);
            font-weight: 600;
            word-break: break-word;
        }

        .info-value a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .tukang-stats {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 16px;
            padding: 16px 12px;
            margin-bottom: 12px;
            color: white;
        }

        .tarif-label {
            font-size: 10px;
            opacity: 0.9;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .tarif-amount {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .tukang-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .action-btn {
            flex: 1;
            padding: 10px;
            border-radius: 12px;
            background: #f8f9fb;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: var(--text-dark);
            font-size: 11px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-btn ion-icon {
            font-size: 18px;
        }

        .action-btn.whatsapp:hover {
            background: #25D366;
            color: white;
        }

        .action-btn.email:hover {
            background: var(--primary-color);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .empty-state ion-icon {
            font-size: 80px;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .pagination-wrapper {
            margin-top: 20px;
        }
    </style>

    <!-- HEADER SECTION -->
    <div id="header-section">
        <div id="section-back">
            <a href="/dashboard" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h1>Data Tukang</h1>
            <div class="subtitle">Lihat informasi tukang</div>
        </div>
    </div>

    <!-- CONTENT SECTION -->
    <div id="content-section">
        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="number">{{ $tukangs->where('status', 'aktif')->count() }}</div>
                <div class="label">Tukang Aktif</div>
            </div>
            <div class="stat-card success">
                <div class="number">{{ $tukangs->total() }}</div>
                <div class="label">Total Tukang</div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="section-title">
            Daftar Tukang
        </div>

        <!-- Tukang List -->
        <div class="tukang-list">
            @forelse ($tukangs as $tukang)
                <div class="tukang-card">
                    <div class="tukang-card-header">
                        @if($tukang->foto)
                            <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" alt="{{ $tukang->nama_tukang }}">
                        @endif
                    </div>
                    
                    <div class="tukang-badge-top">
                        <span class="badge-status {{ $tukang->status == 'aktif' ? 'active' : 'inactive' }}">
                            {{ $tukang->status == 'aktif' ? 'Aktif' : 'Non Aktif' }}
                        </span>
                    </div>

                    <div class="tukang-avatar-container">
                        <div class="tukang-avatar">
                            @if($tukang->foto)
                                <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" alt="{{ $tukang->nama_tukang }}">
                            @else
                                <ion-icon name="person-outline"></ion-icon>
                            @endif
                        </div>
                    </div>

                    <div class="tukang-card-body">
                        <div class="tukang-name">{{ $tukang->nama_tukang }}</div>
                        <div class="tukang-description">
                            Kode: {{ $tukang->kode_tukang }}
                            @if($tukang->keahlian)
                                • {{ $tukang->keahlian }}
                            @endif
                        </div>
                        
                        @if($tukang->tarif_harian)
                            <div class="tukang-stats">
                                <div class="tarif-label">Tarif Harian</div>
                                <div class="tarif-amount">Rp {{ number_format($tukang->tarif_harian, 0, ',', '.') }}</div>
                            </div>
                        @endif

                        <div class="info-detail">
                            @if($tukang->nik)
                                <div class="info-row">
                                    <ion-icon name="card-outline"></ion-icon>
                                    <div class="info-content">
                                        <div class="info-label">NIK</div>
                                        <div class="info-value">{{ $tukang->nik }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($tukang->no_hp)
                                <div class="info-row">
                                    <ion-icon name="call-outline"></ion-icon>
                                    <div class="info-content">
                                        <div class="info-label">No Handphone</div>
                                        <div class="info-value">{{ $tukang->no_hp }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($tukang->email)
                                <div class="info-row">
                                    <ion-icon name="mail-outline"></ion-icon>
                                    <div class="info-content">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">{{ $tukang->email }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($tukang->alamat)
                                <div class="info-row">
                                    <ion-icon name="location-outline"></ion-icon>
                                    <div class="info-content">
                                        <div class="info-label">Alamat</div>
                                        <div class="info-value">{{ $tukang->alamat }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($tukang->keterangan)
                                <div class="info-row">
                                    <ion-icon name="information-circle-outline"></ion-icon>
                                    <div class="info-content">
                                        <div class="info-label">Keterangan</div>
                                        <div class="info-value">{{ $tukang->keterangan }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="tukang-actions">
                            @if($tukang->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tukang->no_hp) }}" 
                                   target="_blank" class="action-btn whatsapp">
                                    <ion-icon name="logo-whatsapp"></ion-icon>
                                    <span>WA</span>
                                </a>
                            @endif
                            @if($tukang->email)
                                <a href="mailto:{{ $tukang->email }}" class="action-btn email">
                                    <ion-icon name="mail-outline"></ion-icon>
                                    <span>Email</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <ion-icon name="construct-outline"></ion-icon>
                    <h3>Tidak Ada Data</h3>
                    <p>Belum ada data tukang yang tersedia</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tukangs->hasPages())
            <div class="pagination-wrapper">
                {{ $tukangs->links() }}
            </div>
        @endif
    </div>
@endsection
