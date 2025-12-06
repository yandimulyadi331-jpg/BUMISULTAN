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

        body {
            background: linear-gradient(180deg, #e3f4f9 0%, #f8fbfc 100%);
        }

        #header-section {
            height: auto;
            padding: 20px;
            position: relative;
            background: linear-gradient(135deg, #1e5245 0%, #2d6a5a 100%);
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
            font-size: 0.8rem;
        }

        #ruangan-info {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            margin: -15px 20px 10px 20px;
            padding: 12px 15px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-ruangan-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 0.8rem;
        }

        .info-ruangan-label {
            font-weight: 600;
            color: #666;
        }

        .info-ruangan-value {
            color: #333;
            text-align: right;
        }

        #content-section {
            padding: 10px 0 100px 0;
            overflow-x: hidden;
        }

        .section-subtitle {
            padding: 0 20px 10px 20px;
            color: #666;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .barang-scroll-container {
            display: flex;
            overflow-x: auto;
            padding: 0 15px 20px 15px;
            gap: 18px;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .barang-scroll-container::-webkit-scrollbar {
            display: none;
        }

        .pricing-card {
            min-width: 260px;
            max-width: 260px;
            height: 460px;
            border-radius: 25px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            scroll-snap-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .pricing-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .card-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0.3;
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(30, 82, 69, 0.95) 0%, 
                rgba(45, 106, 90, 0.9) 100%);
        }

        .pricing-card.variant-1 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(168, 85, 247, 0.95) 0%, 
                rgba(192, 132, 252, 0.9) 100%);
        }

        .pricing-card.variant-2 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(59, 130, 246, 0.95) 0%, 
                rgba(96, 165, 250, 0.9) 100%);
        }

        .pricing-card.variant-3 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(16, 185, 129, 0.95) 0%, 
                rgba(52, 211, 153, 0.9) 100%);
        }

        .pricing-card.variant-4 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(249, 115, 22, 0.95) 0%, 
                rgba(251, 146, 60, 0.9) 100%);
        }

        .card-content {
            position: relative;
            z-index: 2;
            padding: 22px 18px;
            height: 100%;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .card-badge {
            text-align: center;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .card-title {
            text-align: center;
            font-size: 1.15rem;
            font-weight: 800;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            line-height: 1.2;
        }

        .card-subtitle {
            text-align: center;
            font-size: 0.7rem;
            opacity: 0.85;
            margin-bottom: 18px;
        }

        .card-stats {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 5px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 11px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            font-weight: 600;
        }

        .stat-value {
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-icon {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
        }

        .kondisi-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.25);
        }

        .kondisi-baik { background: rgba(74, 222, 128, 0.3); }
        .kondisi-rusak-ringan { background: rgba(251, 191, 36, 0.3); }
        .kondisi-rusak-berat { background: rgba(248, 113, 113, 0.3); }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state ion-icon {
            font-size: 100px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .scroll-indicator {
            text-align: center;
            padding: 8px 20px;
            color: #999;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .scroll-indicator ion-icon {
            font-size: 1.1rem;
            animation: slideX 2s infinite;
        }

        @keyframes slideX {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(10px); }
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
            <div class="section-subtitle">
                <ion-icon name="cube-outline"></ion-icon> {{ $barang->total() }} Barang Tersedia
            </div>

            <div class="scroll-indicator">
                <ion-icon name="arrow-forward-outline"></ion-icon>
                Geser untuk melihat barang lain
            </div>

            <div class="barang-scroll-container">
                @foreach ($barang as $index => $d)
                    @php
                        $variants = ['variant-1', 'variant-2', 'variant-3', 'variant-4'];
                        $variantClass = $variants[$index % 4];
                        $badges = ['ITEM A', 'ITEM B', 'ITEM C', 'ITEM D'];
                        $badgeText = $badges[$index % 4];
                    @endphp
                    
                    <div class="pricing-card {{ $variantClass }}">
                        @if($d->foto)
                            <div class="card-background" style="background-image: url('{{ asset('storage/barang/' . $d->foto) }}');"></div>
                        @else
                            <div class="card-background" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                        @endif
                        
                        <div class="card-overlay"></div>
                        
                        <div class="card-content">
                            <div class="card-badge">{{ $d->kategori ?? $badgeText }}</div>
                            
                            <div class="card-title">
                                {{ textUpperCase($d->nama_barang) }}
                            </div>
                            
                            <div class="card-subtitle">
                                {{ $d->kode_barang }}
                            </div>
                            
                            <div class="card-stats">
                                @if($d->merk)
                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="pricetag-outline"></ion-icon> Merk
                                        </div>
                                        <div class="stat-value">
                                            {{ $d->merk }}
                                        </div>
                                    </div>
                                @endif

                                <div class="stat-item">
                                    <div class="stat-label">
                                        <ion-icon name="calculator-outline"></ion-icon> Jumlah
                                    </div>
                                    <div class="stat-value">
                                        <div class="stat-icon">{{ $d->jumlah }}</div>
                                        {{ $d->satuan }}
                                    </div>
                                </div>

                                <div class="stat-item">
                                    <div class="stat-label">
                                        <ion-icon name="checkmark-circle-outline"></ion-icon> Kondisi
                                    </div>
                                    <div class="stat-value">
                                        <span class="kondisi-badge 
                                            @if($d->kondisi == 'Baik') kondisi-baik
                                            @elseif($d->kondisi == 'Rusak Ringan') kondisi-rusak-ringan
                                            @else kondisi-rusak-berat
                                            @endif">
                                            {{ $d->kondisi }}
                                        </span>
                                    </div>
                                </div>

                                @if($d->harga_perolehan)
                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="cash-outline"></ion-icon> Harga
                                        </div>
                                        <div class="stat-value" style="font-size: 0.8rem;">
                                            Rp {{ number_format($d->harga_perolehan, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endif

                                @if($d->tanggal_perolehan)
                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="calendar-outline"></ion-icon> Tgl Perolehan
                                        </div>
                                        <div class="stat-value" style="font-size: 0.75rem;">
                                            {{ date('d/m/Y', strtotime($d->tanggal_perolehan)) }}
                                        </div>
                                    </div>
                                @endif

                                @if($d->spesifikasi)
                                    <div class="stat-item" style="flex-direction: column; align-items: flex-start; gap: 5px;">
                                        <div class="stat-label">
                                            <ion-icon name="list-outline"></ion-icon> Spesifikasi
                                        </div>
                                        <div style="font-size: 0.65rem; opacity: 0.9; line-height: 1.3;">
                                            {{ Str::limit($d->spesifikasi, 50) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="padding: 20px; text-align: center;">
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
