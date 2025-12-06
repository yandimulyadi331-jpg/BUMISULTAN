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
            background: linear-gradient(135deg, #2d6a5a 0%, #4a8a76 100%);
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
            font-size: 0.85rem;
        }

        #gedung-info {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            margin: -15px 20px 10px 20px;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-gedung-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 0.85rem;
        }

        .info-gedung-label {
            font-weight: 600;
            color: #666;
        }

        .info-gedung-value {
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
            font-size: 0.9rem;
            font-weight: 600;
        }

        .ruangan-scroll-container {
            display: flex;
            overflow-x: auto;
            padding: 0 15px 20px 15px;
            gap: 20px;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .ruangan-scroll-container::-webkit-scrollbar {
            display: none;
        }

        .pricing-card {
            min-width: 270px;
            max-width: 270px;
            height: 480px;
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
            opacity: 0.25;
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(45, 106, 90, 0.95) 0%, 
                rgba(74, 138, 118, 0.9) 100%);
        }

        .pricing-card.variant-1 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(139, 92, 246, 0.95) 0%, 
                rgba(167, 139, 250, 0.9) 100%);
        }

        .pricing-card.variant-2 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(59, 130, 246, 0.95) 0%, 
                rgba(96, 165, 250, 0.9) 100%);
        }

        .pricing-card.variant-3 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(34, 197, 94, 0.95) 0%, 
                rgba(74, 222, 128, 0.9) 100%);
        }

        .card-content {
            position: relative;
            z-index: 2;
            padding: 25px 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .card-badge {
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
            opacity: 0.9;
        }

        .card-title {
            text-align: center;
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            line-height: 1.2;
        }

        .card-subtitle {
            text-align: center;
            font-size: 0.75rem;
            opacity: 0.85;
            margin-bottom: 20px;
        }

        .card-stats {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 5px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            font-weight: 600;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-icon {
            width: 26px;
            height: 26px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .card-footer {
            margin-top: auto;
            padding-top: 15px;
        }

        .btn-card-action {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-card-action:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

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
            padding: 10px 20px;
            color: #999;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .scroll-indicator ion-icon {
            font-size: 1.2rem;
            animation: slideX 2s infinite;
        }

        @keyframes slideX {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(10px); }
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
            <div class="section-subtitle">
                <ion-icon name="door-open-outline"></ion-icon> {{ $ruangan->total() }} Ruangan Tersedia
            </div>

            <div class="scroll-indicator">
                <ion-icon name="arrow-forward-outline"></ion-icon>
                Geser untuk melihat ruangan lain
            </div>

            <div class="ruangan-scroll-container">
                @foreach ($ruangan as $index => $d)
                    @php
                        $variants = ['variant-1', 'variant-2', 'variant-3'];
                        $variantClass = $variants[$index % 3];
                        $badges = ['ROOM A', 'ROOM B', 'ROOM C'];
                        $badgeText = $badges[$index % 3];
                    @endphp
                    
                    <a href="{{ route('barang.karyawan', [
                        'gedung_id' => Crypt::encrypt($gedung->id),
                        'ruangan_id' => Crypt::encrypt($d->id)
                    ]) }}" style="text-decoration: none;">
                        <div class="pricing-card {{ $variantClass }}">
                            @if($d->foto)
                                <div class="card-background" style="background-image: url('{{ asset('storage/ruangan/' . $d->foto) }}');"></div>
                            @else
                                <div class="card-background" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                            @endif
                            
                            <div class="card-overlay"></div>
                            
                            <div class="card-content">
                                <div class="card-badge">{{ $badgeText }}</div>
                                
                                <div class="card-title">
                                    {{ textUpperCase($d->nama_ruangan) }}
                                </div>
                                
                                <div class="card-subtitle">
                                    {{ $d->kode_ruangan }}
                                </div>
                                
                                <div class="card-stats">
                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="layers-outline"></ion-icon> Lantai
                                        </div>
                                        <div class="stat-value">
                                            <div class="stat-icon">{{ $d->lantai ?? '-' }}</div>
                                            Lantai
                                        </div>
                                    </div>

                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="expand-outline"></ion-icon> Luas
                                        </div>
                                        <div class="stat-value">
                                            {{ $d->luas ? number_format($d->luas, 0) . ' m²' : '-' }}
                                        </div>
                                    </div>

                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="people-outline"></ion-icon> Kapasitas
                                        </div>
                                        <div class="stat-value">
                                            <div class="stat-icon">{{ $d->kapasitas ?? '0' }}</div>
                                            Orang
                                        </div>
                                    </div>

                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="cube-outline"></ion-icon> Barang
                                        </div>
                                        <div class="stat-value">
                                            <div class="stat-icon">{{ $d->total_barang }}</div>
                                            Item
                                        </div>
                                    </div>

                                    @if($d->keterangan)
                                        <div class="stat-item" style="flex-direction: column; align-items: flex-start; gap: 5px;">
                                            <div class="stat-label">
                                                <ion-icon name="information-circle-outline"></ion-icon> Keterangan
                                            </div>
                                            <div style="font-size: 0.7rem; opacity: 0.9; line-height: 1.3;">
                                                {{ Str::limit($d->keterangan, 60) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-footer">
                                    <button class="btn-card-action">
                                        Lihat Barang
                                        <ion-icon name="arrow-forward-outline"></ion-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="padding: 20px; text-align: center;">
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
