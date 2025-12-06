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
            padding: 25px 20px;
            position: relative;
            background: linear-gradient(135deg, #32745e 0%, #58907D 100%);
            border-radius: 0 0 35px 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            color: #ffffff;
            font-size: 32px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            margin-top: 10px;
        }

        #header-title h3 {
            font-weight: 900;
            margin: 0;
            font-size: 1.8rem;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            letter-spacing: -0.5px;
        }

        #header-title p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 0.95rem;
            font-weight: 500;
        }

        #content-section {
            padding: 15px 0 100px 0;
            overflow-x: hidden;
        }

        .section-subtitle {
            padding: 0 20px 15px 20px;
            color: #555;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-subtitle ion-icon {
            font-size: 1.3rem;
            color: var(--bg-indicator);
        }

        /* Horizontal Scroll Container */
        .gedung-scroll-container {
            display: flex;
            overflow-x: auto;
            padding: 0 20px 25px 20px;
            gap: 25px;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .gedung-scroll-container::-webkit-scrollbar {
            display: none;
        }

        /* Modern Pricing Card Style */
        .pricing-card {
            min-width: 320px;
            max-width: 320px;
            height: 580px;
            border-radius: 30px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            scroll-snap-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25), 0 8px 20px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .pricing-card:hover {
            transform: translateY(-15px) scale(1.04);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35), 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .pricing-card:active {
            transform: translateY(-8px) scale(1.02);
        }

        /* Enhanced Background with Photo - SUPER CLEAR & DETAILED */
        .card-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0.85;
            filter: brightness(1.15) contrast(1.3) saturate(1.5) sharpen(1.2);
            transition: all 0.5s ease;
        }

        .pricing-card:hover .card-background {
            transform: scale(1.08);
            opacity: 0.95;
            filter: brightness(1.2) contrast(1.35) saturate(1.55) sharpen(1.3);
        }

        /* Gradient Overlay - MINIMAL for Maximum Photo Visibility */
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(50, 116, 94, 0.45) 0%, 
                rgba(88, 144, 125, 0.35) 100%);
        }

        /* Card Variants - More Vibrant */
        .pricing-card.variant-1 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(99, 102, 241, 0.45) 0%, 
                rgba(139, 92, 246, 0.38) 50%,
                rgba(168, 85, 247, 0.32) 100%);
        }

        .pricing-card.variant-2 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.45) 0%, 
                rgba(59, 130, 246, 0.38) 50%,
                rgba(96, 165, 250, 0.32) 100%);
        }

        .pricing-card.variant-3 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(16, 185, 129, 0.45) 0%, 
                rgba(34, 197, 94, 0.38) 50%,
                rgba(74, 222, 128, 0.32) 100%);
        }

        .pricing-card.variant-4 .card-overlay {
            background: linear-gradient(135deg, 
                rgba(249, 115, 22, 0.45) 0%, 
                rgba(251, 146, 60, 0.38) 50%,
                rgba(253, 186, 116, 0.32) 100%);
        }

        /* Card Content - Better Spacing */
        .card-content {
            position: relative;
            z-index: 2;
            padding: 35px 28px;
            height: 100%;
            display: flex;
            flex-direction: column;
            color: white;
        }

        /* Premium Badge with Glassmorphism */
        .card-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(20px) saturate(180%);
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 2px;
            margin-bottom: 22px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            animation: badgePulse 3s ease-in-out infinite;
            text-transform: uppercase;
            align-self: center;
        }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        /* Bold Title - Better Readability with Strong Shadow */
        .card-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 900;
            margin-bottom: 10px;
            line-height: 1.3;
            text-shadow: 0 3px 10px rgba(0, 0, 0, 0.7), 0 6px 20px rgba(0, 0, 0, 0.5), 0 1px 3px rgba(0, 0, 0, 0.9);
            letter-spacing: -0.5px;
            word-wrap: break-word;
            padding: 0 10px;
        }

        /* Modern Subtitle */
        .card-subtitle {
            text-align: center;
            font-size: 0.88rem;
            opacity: 0.98;
            margin-bottom: 25px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(15px);
            padding: 7px 16px;
            border-radius: 16px;
            display: inline-block;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            align-self: center;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        }

        /* Stats Container */
        .card-stats {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 5px;
        }

        /* Glassmorphism Stat Items - Smaller & Compact with Better Contrast */
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            margin-bottom: 0;
            background: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(15px) saturate(180%);
            border-radius: 12px;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-item:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(8px);
            border-color: rgba(255, 255, 255, 0.45);
        }

        .stat-label {
            font-size: 0.75rem;
            opacity: 0.92;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .stat-label ion-icon {
            font-size: 1rem;
            padding: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        .stat-value {
            font-weight: 900;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        }

        /* Enhanced Stat Icon - Smaller */
        .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.35) 0%, rgba(255, 255, 255, 0.18) 100%);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            font-weight: 900;
            font-size: 0.85rem;
            border: 2px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }

        /* Card Footer */
        .card-footer {
            margin-top: auto;
            padding-top: 20px;
            display: flex;
            gap: 10px;
        }

        /* Icon Only Buttons - Smaller & Side by Side */
        .btn-card-action {
            width: 48px;
            height: 48px;
            padding: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.18) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 14px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .btn-card-action:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 100%);
            color: #32745e;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .btn-card-action ion-icon {
            font-size: 1.5rem;
            transition: transform 0.4s ease;
        }

        .btn-card-action:hover ion-icon {
            transform: scale(1.15);
        }

        .btn-card-action.btn-navigate {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.3) 0%, rgba(34, 197, 94, 0.2) 100%);
            border-color: rgba(16, 185, 129, 0.5);
        }

        .btn-card-action.btn-navigate:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.98) 0%, rgba(34, 197, 94, 0.92) 100%);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
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
        }

        .empty-state p {
            opacity: 0.7;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            text-align: center;
            padding: 12px 20px 18px 20px;
            color: #777;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
        }

        .scroll-indicator ion-icon {
            font-size: 1.3rem;
            animation: slideX 2s infinite;
            color: var(--bg-indicator);
        }

        @keyframes slideX {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(12px); }
        }

        /* Detail Panel - Hidden by default */
        .detail-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.6s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease, transform 0.4s ease;
            opacity: 0;
            transform: translateY(-20px);
            margin: 15px 20px 20px 20px;
        }

        .detail-panel.active {
            max-height: 1500px;
            opacity: 1;
            transform: translateY(0);
        }

        .detail-content {
            background: linear-gradient(135deg, #32745e 0%, #58907D 100%);
            padding: 0;
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25), 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.5s ease;
            overflow: hidden;
        }

        /* Detail Panel Color Variants */
        .detail-panel.variant-1 .detail-content {
            background: linear-gradient(135deg, rgba(99, 102, 241, 1) 0%, rgba(139, 92, 246, 1) 100%);
        }

        .detail-panel.variant-2 .detail-content {
            background: linear-gradient(135deg, rgba(37, 99, 235, 1) 0%, rgba(59, 130, 246, 1) 100%);
        }

        .detail-panel.variant-3 .detail-content {
            background: linear-gradient(135deg, rgba(16, 185, 129, 1) 0%, rgba(34, 197, 94, 1) 100%);
        }

        .detail-panel.variant-4 .detail-content {
            background: linear-gradient(135deg, rgba(249, 115, 22, 1) 0%, rgba(251, 146, 60, 1) 100%);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .detail-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%);
            backdrop-filter: blur(15px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .detail-title {
            font-size: 1.2rem;
            font-weight: 900;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .detail-title ion-icon {
            font-size: 1.5rem;
            padding: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
        }

        .detail-close {
            background: rgba(239, 68, 68, 0.2);
            color: #ffffff;
            border: 2px solid rgba(239, 68, 68, 0.4);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .detail-close:hover {
            background: #dc2626;
            border-color: #dc2626;
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            padding: 20px;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            padding: 16px;
            border-radius: 18px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-item:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 255, 255, 0.35);
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .detail-label {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-label ion-icon {
            font-size: 1.1rem;
            padding: 4px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        .detail-value {
            font-size: 1rem;
            font-weight: 900;
            color: #ffffff;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            line-height: 1.4;
        }

        .detail-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 800;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
        }

        /* Hide stats in card, show only on detail */
        .card-stats {
            display: none;
        }

        /* Adjust card height */
        .pricing-card {
            height: 380px;
        }

        .card-content {
            justify-content: center;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Manajemen Gedung</h3>
            <p>Pilih Gedung untuk Melihat Detail</p>
        </div>
    </div>

    <div id="content-section">
        @if ($gedung->count() > 0)
            <div class="section-subtitle">
                <ion-icon name="business-outline"></ion-icon> {{ $gedung->total() }} Gedung Tersedia
            </div>

            <div class="scroll-indicator">
                <ion-icon name="arrow-forward-outline"></ion-icon>
                Geser untuk melihat gedung lain
            </div>

            <div class="gedung-scroll-container">
                @foreach ($gedung as $index => $d)
                    @php
                        $variants = ['variant-1', 'variant-2', 'variant-3', 'variant-4'];
                        $variantClass = $variants[$index % 4];
                        $badges = ['STANDARD', 'PREMIUM', 'BASIC', 'PLATINUM'];
                        $badgeText = $badges[$index % 4];
                    @endphp
                    
                    <div style="width: 100%;">
                        <div class="pricing-card {{ $variantClass }}" data-gedung-id="{{ $d->id }}" data-variant="{{ $variantClass }}">
                            {{-- Background Photo with Enhanced Effect --}}
                            @if($d->foto)
                                <div class="card-background" style="background-image: url('{{ asset('storage/gedung/' . $d->foto) }}');"></div>
                            @else
                                <div class="card-background" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                            @endif
                            
                            {{-- Gradient Overlay --}}
                            <div class="card-overlay"></div>
                            
                            {{-- Card Content --}}
                            <div class="card-content">
                                <div class="card-badge">{{ $badgeText }}</div>
                                
                                <div class="card-title">
                                    {{ textUpperCase($d->nama_gedung) }}
                                </div>
                                
                                <div class="card-subtitle">
                                    {{ $d->kode_gedung }}
                                </div>

                                <div class="card-footer">
                                    <button class="btn-card-action" onclick="toggleDetail('gedung-{{ $d->id }}', event)">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </button>
                                    <a href="{{ route('ruangan.karyawan', Crypt::encrypt($d->id)) }}" style="text-decoration: none;">
                                        <button class="btn-card-action btn-navigate">
                                            <ion-icon name="arrow-forward-outline"></ion-icon>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Panel --}}
                        <div class="detail-panel {{ $variantClass }}" id="gedung-{{ $d->id }}">
                            <div class="detail-content">
                                <div class="detail-header">
                                    <div class="detail-title">
                                        <ion-icon name="information-circle"></ion-icon>
                                        Informasi Detail Gedung
                                    </div>
                                    <button class="detail-close" onclick="toggleDetail('gedung-{{ $d->id }}', event)">
                                        <ion-icon name="close" style="font-size: 1.4rem;"></ion-icon>
                                    </button>
                                </div>
                                
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="business-outline"></ion-icon>
                                            Nama Gedung
                                        </div>
                                        <div class="detail-value">{{ $d->nama_gedung }}</div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="pricetag-outline"></ion-icon>
                                            Kode Gedung
                                        </div>
                                        <div class="detail-value">{{ $d->kode_gedung }}</div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="location-outline"></ion-icon>
                                            Lokasi Cabang
                                        </div>
                                        <div class="detail-value">{{ $d->cabang ? $d->cabang->nama_cabang : '-' }}</div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="layers-outline"></ion-icon>
                                            Jumlah Lantai
                                        </div>
                                        <div class="detail-value">
                                            <div class="detail-badge">
                                                <ion-icon name="layers"></ion-icon>
                                                {{ $d->jumlah_lantai }} Lantai
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="door-open-outline"></ion-icon>
                                            Total Ruangan
                                        </div>
                                        <div class="detail-value">
                                            <div class="detail-badge">
                                                <ion-icon name="door-open"></ion-icon>
                                                {{ $d->total_ruangan }} Ruangan
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="cube-outline"></ion-icon>
                                            Total Barang
                                        </div>
                                        <div class="detail-value">
                                            <div class="detail-badge">
                                                <ion-icon name="cube"></ion-icon>
                                                {{ $d->total_barang }} Item
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($d->alamat)
                                        <div class="detail-item full-width">
                                            <div class="detail-label">
                                                <ion-icon name="navigate-outline"></ion-icon>
                                                Alamat Lengkap
                                            </div>
                                            <div class="detail-value" style="line-height: 1.6; margin-top: 5px;">
                                                {{ $d->alamat }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($gedung->hasPages())
                <div style="padding: 20px;">
                    {{ $gedung->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <ion-icon name="business-outline"></ion-icon>
                <h4>Tidak Ada Gedung</h4>
                <p>Belum ada data gedung yang tersedia</p>
            </div>
        @endif
    </div>

    <script>
        function toggleDetail(id, event) {
            event.preventDefault();
            event.stopPropagation();
            
            const detailPanel = document.getElementById(id);
            const allPanels = document.querySelectorAll('.detail-panel');
            
            // Close all other panels
            allPanels.forEach(panel => {
                if (panel.id !== id && panel.classList.contains('active')) {
                    panel.classList.remove('active');
                }
            });
            
            // Toggle current panel
            detailPanel.classList.toggle('active');
            
            // Scroll to detail panel if opening
            if (detailPanel.classList.contains('active')) {
                setTimeout(() => {
                    detailPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        }
    </script>
@endsection
