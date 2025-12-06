@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            /* Neomorphic Colors */
            --bg-body-light: #e0e5ec;
            --bg-primary-light: #e0e5ec;
            --shadow-dark-light: #a3b1c6;
            --shadow-light-light: #ffffff;
            --text-primary-light: #2d3748;
            --text-secondary-light: #718096;
            --border-light: rgba(0, 0, 0, 0.08);

            --bg-body-dark: #1a202c;
            --bg-primary-dark: #2d3748;
            --shadow-dark-dark: #171923;
            --shadow-light-dark: #3f4c63;
            --text-primary-dark: #f7fafc;
            --text-secondary-dark: #a0aec0;
            --border-dark: rgba(255, 255, 255, 0.08);

            /* Variant Colors */
            --gradient-4: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
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
            padding: 25px 20px;
            position: relative;
            background: var(--bg-primary);
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            margin-bottom: 20px;
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--bg-primary);
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-size: 24px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-btn:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        #header-title {
            text-align: center;
            color: var(--text-primary);
            margin-top: 10px;
        }

        #header-title h3 {
            font-weight: 700;
            margin: 0;
            font-size: 1.6rem;
        }

        #header-title p {
            margin: 5px 0 0 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* ========== CONTENT ========== */
        #content-section {
            padding: 0 20px 100px 20px;
        }

        /* ========== RIWAYAT CARD ========== */
        .riwayat-card {
            background: var(--bg-primary);
            padding: 20px;
            border-radius: 25px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .riwayat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .riwayat-date {
            font-size: 0.85rem;
            font-weight: 700;
            color: #f97316;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .riwayat-code {
            font-size: 0.75rem;
            font-weight: 900;
            color: var(--text-secondary);
            background: var(--bg-primary);
            padding: 6px 12px;
            border-radius: 12px;
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        .transfer-flow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .location-box {
            flex: 1;
            background: var(--bg-primary);
            padding: 15px;
            border-radius: 15px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .location-title {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .location-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.4;
        }

        .arrow-icon {
            font-size: 1.5rem;
            color: #f97316;
        }

        .riwayat-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 4px;
        }

        .badge-qty {
            background: transparent;
            color: #f97316;
            border: 2px solid #f97316;
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: 900;
            font-size: 0.85rem;
            display: inline-block;
        }

        .keterangan-section {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
        }

        .keterangan-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .keterangan-text {
            font-size: 0.85rem;
            color: var(--text-primary);
            line-height: 1.5;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-secondary);
        }

        .empty-state ion-icon {
            font-size: 120px;
            margin-bottom: 25px;
            opacity: 0.25;
        }

        .empty-state h4 {
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        .empty-state p {
            opacity: 0.7;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('gedung.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Riwayat Transfer</h3>
            <p>{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</p>
        </div>
    </div>

    <div id="content-section">
        @if($riwayat->count() > 0)
            @foreach ($riwayat as $d)
                <div class="riwayat-card">
                    <div class="riwayat-header">
                        <div class="riwayat-date">
                            <ion-icon name="calendar"></ion-icon>
                            {{ $d->tanggal_transfer->format('d M Y') }}
                        </div>
                        <div class="riwayat-code">#{{ $d->kode_transfer }}</div>
                    </div>

                    <div class="transfer-flow">
                        <div class="location-box">
                            <div class="location-title">
                                <ion-icon name="exit-outline"></ion-icon> Dari
                            </div>
                            <div class="location-name">
                                {{ $d->ruanganAsal->gedung->nama_gedung }}<br>
                                {{ $d->ruanganAsal->nama_ruangan }}
                            </div>
                        </div>

                        <div class="arrow-icon">
                            <ion-icon name="arrow-forward"></ion-icon>
                        </div>

                        <div class="location-box">
                            <div class="location-title">
                                <ion-icon name="enter-outline"></ion-icon> Ke
                            </div>
                            <div class="location-name">
                                {{ $d->ruanganTujuan->gedung->nama_gedung }}<br>
                                {{ $d->ruanganTujuan->nama_ruangan }}
                            </div>
                        </div>
                    </div>

                    <div class="riwayat-info">
                        <div class="info-item">
                            <div class="info-label">Jumlah</div>
                            <div class="info-value">
                                <span class="badge-qty">{{ $d->jumlah_transfer }} item</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Petugas</div>
                            <div class="info-value">{{ $d->petugas ?? '-' }}</div>
                        </div>
                    </div>

                    @if($d->keterangan)
                        <div class="keterangan-section">
                            <div class="keterangan-label">
                                <ion-icon name="document-text-outline"></ion-icon> Keterangan
                            </div>
                            <div class="keterangan-text">
                                {{ $d->keterangan }}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Pagination --}}
            @if($riwayat->hasPages())
                <div style="padding: 20px 0;">
                    {{ $riwayat->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <ion-icon name="time-outline"></ion-icon>
                <h4>Belum Ada Riwayat</h4>
                <p>Belum ada riwayat transfer untuk barang ini</p>
            </div>
        @endif
    </div>
@endsection
