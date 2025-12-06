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
            padding: 20px 20px 140px;
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
            padding: 0 20px 100px;
            margin-top: -100px;
            position: relative;
            z-index: 2;
        }

        .profile-card {
            background: white;
            border-radius: 24px;
            padding: 0;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .profile-header {
            text-align: center;
            padding: 30px 20px 25px;
            position: relative;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 24px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            overflow: hidden;
            box-shadow: 0 12px 32px rgba(102, 126, 234, 0.3);
            border: 5px solid white;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .profile-code {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.active {
            background: var(--success-color);
            color: white;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
        }

        .status-badge.inactive {
            background: #6b7280;
            color: white;
        }

        .tarif-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
        }

        .tarif-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .tarif-amount {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .info-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            margin-bottom: 16px;
        }

        .info-card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: -0.3px;
        }

        .info-card-title ion-icon {
            font-size: 22px;
            color: var(--primary-color);
        }

        .info-item {
            background: var(--bg-light);
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 10px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
        }

        .info-value {
            font-size: 15px;
            color: var(--text-dark);
            font-weight: 600;
            line-height: 1.4;
        }

        .info-value.link {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value ion-icon {
            font-size: 20px;
        }

        .keahlian-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 10px 18px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        }

        .timestamp-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            line-height: 1.8;
        }

        .timestamp-card strong {
            color: var(--text-dark);
            font-weight: 600;
        }
    </style>

    <!-- HEADER SECTION -->
    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('tukang.karyawan.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h1>Detail Tukang</h1>
            <div class="subtitle">Informasi lengkap tukang</div>
        </div>
    </div>

    <!-- CONTENT SECTION -->
    <div id="content-section">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-photo">
                    @if($tukang->foto)
                        <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" alt="{{ $tukang->nama_tukang }}">
                    @else
                        <ion-icon name="person-outline"></ion-icon>
                    @endif
                </div>
                <div class="profile-name">{{ $tukang->nama_tukang }}</div>
                <div class="profile-code">{{ $tukang->kode_tukang }}</div>
                <span class="status-badge {{ $tukang->status == 'aktif' ? 'active' : 'inactive' }}">
                    {{ $tukang->status == 'aktif' ? 'Aktif' : 'Non Aktif' }}
                </span>
            </div>

            @if($tukang->tarif_harian)
                <div class="tarif-section">
                    <div class="tarif-label">Tarif Harian</div>
                    <div class="tarif-amount">Rp {{ number_format($tukang->tarif_harian, 0, ',', '.') }}</div>
                </div>
            @endif
        </div>

        <!-- Informasi Pribadi -->
        <div class="info-card">
            <div class="info-card-title">
                <ion-icon name="person-outline"></ion-icon>
                Informasi Pribadi
            </div>

            <div class="info-item">
                <div class="info-label">NIK</div>
                <div class="info-value">{{ $tukang->nik ?? '-' }}</div>
            </div>

            @if($tukang->no_hp)
                <div class="info-item">
                    <div class="info-label">No Handphone</div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tukang->no_hp) }}" 
                       target="_blank" class="info-value link">
                        <ion-icon name="logo-whatsapp"></ion-icon>
                        {{ $tukang->no_hp }}
                    </a>
                </div>
            @endif

            @if($tukang->email)
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <a href="mailto:{{ $tukang->email }}" class="info-value link">
                        <ion-icon name="mail-outline"></ion-icon>
                        {{ $tukang->email }}
                    </a>
                </div>
            @endif

            @if($tukang->alamat)
                <div class="info-item">
                    <div class="info-label">Alamat Lengkap</div>
                    <div class="info-value">{{ $tukang->alamat }}</div>
                </div>
            @endif
        </div>

        <!-- Informasi Keahlian -->
        @if($tukang->keahlian)
            <div class="info-card">
                <div class="info-card-title">
                    <ion-icon name="construct-outline"></ion-icon>
                    Keahlian
                </div>
                <span class="keahlian-badge">{{ $tukang->keahlian }}</span>
            </div>
        @endif

        <!-- Keterangan -->
        @if($tukang->keterangan)
            <div class="info-card">
                <div class="info-card-title">
                    <ion-icon name="document-text-outline"></ion-icon>
                    Keterangan
                </div>
                <div class="info-item">
                    <div class="info-value" style="font-weight: 500; line-height: 1.6;">{{ $tukang->keterangan }}</div>
                </div>
            </div>
        @endif

        <!-- Timestamp -->
        <div class="timestamp-card">
            <div><strong>Dibuat:</strong> {{ $tukang->created_at->format('d F Y, H:i') }} WIB</div>
            <div><strong>Terakhir Update:</strong> {{ $tukang->updated_at->format('d F Y, H:i') }} WIB</div>
        </div>
    </div>
@endsection
