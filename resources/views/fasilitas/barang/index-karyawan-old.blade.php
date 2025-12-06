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
            font-size: 0.85rem;
        }

        #ruangan-info {
            background: #ffffff;
            margin: -15px 20px 15px 20px;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .info-ruangan-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .info-ruangan-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
        }

        .info-ruangan-value {
            color: #333;
            font-size: 0.85rem;
            text-align: right;
        }

        #content-section {
            padding: 0 20px 80px 20px;
        }

        .barang-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .barang-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .barang-header {
            background: linear-gradient(135deg, #2d6a5a 0%, #4a8a76 100%);
            padding: 12px 15px;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .barang-foto {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: #f5f5f5;
        }

        .barang-body {
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

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
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

        .barang-kategori {
            font-size: 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 12px;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('ruangan.karyawan', Crypt::encrypt($gedung->id)) }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Daftar Barang</h3>
            <p>{{ $ruangan->nama_ruangan }}</p>
        </div>
    </div>

    <div id="ruangan-info">
        <div class="info-ruangan-row">
            <span class="info-ruangan-label">
                <ion-icon name="business-outline"></ion-icon> Gedung
            </span>
            <span class="info-ruangan-value">{{ $gedung->nama_gedung }}</span>
        </div>
        <div class="info-ruangan-row">
            <span class="info-ruangan-label">
                <ion-icon name="door-open-outline"></ion-icon> Ruangan
            </span>
            <span class="info-ruangan-value">{{ $ruangan->kode_ruangan }}</span>
        </div>
        <div class="info-ruangan-row">
            <span class="info-ruangan-label">
                <ion-icon name="layers-outline"></ion-icon> Lantai
            </span>
            <span class="info-ruangan-value">Lantai {{ $ruangan->lantai ?? '-' }}</span>
        </div>
    </div>

    <div id="content-section">
        @if ($barang->count() > 0)
            @foreach ($barang as $d)
                <div class="barang-card">
                    @if($d->foto)
                        <img src="{{ asset('storage/barang/' . $d->foto) }}" alt="{{ $d->nama_barang }}" class="barang-foto">
                    @else
                        <div class="barang-foto d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);">
                            <ion-icon name="cube-outline" style="font-size: 60px; color: #999;"></ion-icon>
                        </div>
                    @endif
                    
                    <div class="barang-header">
                        <div>
                            <h6 style="margin: 0; font-weight: bold; font-size: 0.95rem;">{{ textUpperCase($d->nama_barang) }}</h6>
                            <small style="opacity: 0.9;">{{ $d->kode_barang }}</small>
                        </div>
                        @if($d->kategori)
                            <span class="barang-kategori">{{ $d->kategori }}</span>
                        @endif
                    </div>

                    <div class="barang-body">
                        @if($d->merk)
                            <div class="info-row">
                                <span class="info-label">
                                    <ion-icon name="pricetag-outline"></ion-icon> Merk
                                </span>
                                <span class="info-value">{{ $d->merk }}</span>
                            </div>
                        @endif

                        <div class="info-row">
                            <span class="info-label">
                                <ion-icon name="calculator-outline"></ion-icon> Jumlah
                            </span>
                            <span class="info-value">
                                <span class="badge-custom badge-info">{{ $d->jumlah }} {{ $d->satuan }}</span>
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <ion-icon name="checkmark-circle-outline"></ion-icon> Kondisi
                            </span>
                            <span class="info-value">
                                @if($d->kondisi == 'Baik')
                                    <span class="badge-custom badge-success">{{ $d->kondisi }}</span>
                                @elseif($d->kondisi == 'Rusak Ringan')
                                    <span class="badge-custom badge-warning">{{ $d->kondisi }}</span>
                                @else
                                    <span class="badge-custom badge-danger">{{ $d->kondisi }}</span>
                                @endif
                            </span>
                        </div>

                        @if($d->harga_perolehan)
                            <div class="info-row">
                                <span class="info-label">
                                    <ion-icon name="cash-outline"></ion-icon> Harga Perolehan
                                </span>
                                <span class="info-value">
                                    Rp {{ number_format($d->harga_perolehan, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        @if($d->tanggal_perolehan)
                            <div class="info-row">
                                <span class="info-label">
                                    <ion-icon name="calendar-outline"></ion-icon> Tanggal Perolehan
                                </span>
                                <span class="info-value">
                                    {{ date('d/m/Y', strtotime($d->tanggal_perolehan)) }}
                                </span>
                            </div>
                        @endif

                        @if($d->spesifikasi)
                            <div class="info-row">
                                <span class="info-label">
                                    <ion-icon name="list-outline"></ion-icon> Spesifikasi
                                </span>
                                <span class="info-value" style="max-width: 60%;">
                                    {{ $d->spesifikasi }}
                                </span>
                            </div>
                        @endif

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
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 20px;">
                {{ $barang->links() }}
            </div>
        @else
            <div class="empty-state">
                <ion-icon name="cube-outline"></ion-icon>
                <h5>Tidak Ada Data Barang</h5>
                <p>Belum ada barang yang terdaftar di ruangan ini</p>
            </div>
        @endif
    </div>
@endsection
