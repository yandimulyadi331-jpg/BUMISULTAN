@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
            --color-nav-hover: #3ab58c;
        }

        #header-section {
            height: auto;
            padding: 20px;
            position: relative;
            background: linear-gradient(135deg, #32745e 0%, #58907D 100%);
            border-radius: 0 0 30px 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            color: #ffffff;
            font-size: 30px;
            text-decoration: none;
        }

        .back-btn:hover {
            color: #dff9fb;
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            margin-top: 10px;
        }

        #header-title h3 {
            font-weight: bold;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #header-title p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        #gedung-info {
            background: #ffffff;
            margin: -15px 20px 15px 20px;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .info-gedung-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .info-gedung-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
        }

        .info-gedung-value {
            color: #333;
            font-size: 0.85rem;
            text-align: right;
        }

        #content-section {
            padding: 0 20px 80px 20px;
        }

        .ruangan-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .ruangan-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .ruangan-header {
            background: linear-gradient(135deg, #2d6a5a 0%, #4a8a76 100%);
            padding: 15px;
            color: #ffffff;
        }

        .ruangan-foto {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: #f5f5f5;
        }

        .ruangan-body {
            padding: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
        }

        .info-value {
            color: #333;
            font-size: 0.85rem;
            text-align: right;
        }

        .badge-custom {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .btn-view-detail {
            width: 100%;
            background: var(--bg-indicator);
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-view-detail:hover {
            background: var(--color-nav-hover);
            transform: scale(1.02);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state ion-icon {
            font-size: 80px;
            margin-bottom: 15px;
            opacity: 0.3;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('gedung.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Daftar Ruangan</h3>
            <p>{{ $gedung->nama_gedung }}</p>
        </div>
    </div>

    <div id="gedung-info">
        <div class="info-gedung-row">
            <span class="info-gedung-label">
                <ion-icon name="business-outline"></ion-icon> Kode Gedung
            </span>
            <span class="info-gedung-value">{{ $gedung->kode_gedung }}</span>
        </div>
        <div class="info-gedung-row">
            <span class="info-gedung-label">
                <ion-icon name="location-outline"></ion-icon> Alamat
            </span>
            <span class="info-gedung-value" style="max-width: 60%;">{{ $gedung->alamat ?? '-' }}</span>
        </div>
        <div class="info-gedung-row">
            <span class="info-gedung-label">
                <ion-icon name="layers-outline"></ion-icon> Jumlah Lantai
            </span>
            <span class="info-gedung-value">{{ $gedung->jumlah_lantai }} Lantai</span>
        </div>
    </div>

    <div id="content-section">
        @if ($ruangan->count() > 0)
            @foreach ($ruangan as $d)
                <div class="ruangan-card">
                    @if($d->foto)
                        <img src="{{ asset('storage/ruangan/' . $d->foto) }}" alt="{{ $d->nama_ruangan }}" class="ruangan-foto">
                    @else
                        <div class="ruangan-foto d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);">
                            <ion-icon name="door-open-outline" style="font-size: 60px; color: #999;"></ion-icon>
                        </div>
                    @endif
                    
                    <div class="ruangan-header">
                        <h5 style="margin: 0; font-weight: bold;">{{ textUpperCase($d->nama_ruangan) }}</h5>
                        <small style="opacity: 0.9;">{{ $d->kode_ruangan }}</small>
                    </div>

                    <div class="ruangan-body">
                        <div class="info-row">
                            <span class="info-label">
                                <ion-icon name="layers-outline"></ion-icon> Lantai
                            </span>
                            <span class="info-value">
                                <span class="badge-custom badge-info">Lantai {{ $d->lantai ?? '-' }}</span>
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <ion-icon name="expand-outline"></ion-icon> Luas Ruangan
                            </span>
                            <span class="info-value">
                                {{ $d->luas ? number_format($d->luas, 2) . ' m²' : '-' }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <ion-icon name="people-outline"></ion-icon> Kapasitas
                            </span>
                            <span class="info-value">
                                {{ $d->kapasitas ?? '-' }} Orang
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <ion-icon name="cube-outline"></ion-icon> Total Barang
                            </span>
                            <span class="info-value">
                                <span class="badge-custom badge-success">{{ $d->total_barang }} Barang</span>
                            </span>
                        </div>

                        @if($d->keterangan)
                            <div class="info-row">
                                <span class="info-label">
                                    <ion-icon name="information-circle-outline"></ion-icon> Keterangan
                                </span>
                                <span class="info-value" style="max-width: 60%;">
                                    {{ $d->keterangan }}
                                </span>
                            </div>
                        @endif

                        <a href="{{ route('barang.karyawan', [
                            'gedung_id' => Crypt::encrypt($gedung->id),
                            'ruangan_id' => Crypt::encrypt($d->id)
                        ]) }}" class="btn-view-detail">
                            <ion-icon name="cube-outline"></ion-icon> Lihat Daftar Barang
                        </a>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 20px;">
                {{ $ruangan->links() }}
            </div>
        @else
            <div class="empty-state">
                <ion-icon name="door-open-outline"></ion-icon>
                <h5>Tidak Ada Data Ruangan</h5>
                <p>Belum ada ruangan yang terdaftar di gedung ini</p>
            </div>
        @endif
    </div>
@endsection
