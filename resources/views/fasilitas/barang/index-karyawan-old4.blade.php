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
            background: linear-gradient(135deg, #1e5245 0%, #2d6a5a 100%);
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
            padding: 10px 0 100px 0;
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
            color: #1e5245;
        }

        /* Horizontal Scroll Container */
        .barang-scroll-container {
            display: flex;
            overflow-x: auto;
            padding: 0 20px 25px 20px;
            gap: 25px;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .barang-scroll-container::-webkit-scrollbar {
            display: none;
        }

        /* Modern Pricing Card Style */
        .pricing-card {
            min-width: 320px;
            max-width: 320px;
            height: 380px;
            border-radius: 30px;
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            scroll-snap-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25), 0 8px 20px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .pricing-card:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3), 0 10px 25px rgba(0, 0, 0, 0.18);
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
                rgba(30, 82, 69, 0.45) 0%, 
                rgba(45, 106, 90, 0.35) 100%);
        }

        /* Card Variants - Item Colors */
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

        /* Stats Container - Hidden */
        .card-stats {
            display: none;
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

        /* Modern Kondisi Badge - Smaller */
        .kondisi-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: 900;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .kondisi-baik {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.35) 0%, rgba(34, 197, 94, 0.25) 100%);
            color: #ffffff;
            border-color: rgba(16, 185, 129, 0.5);
        }

        .kondisi-rusak-ringan {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.35) 0%, rgba(245, 158, 11, 0.25) 100%);
            color: #ffffff;
            border-color: rgba(251, 191, 36, 0.5);
        }

        .kondisi-rusak-berat {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.35) 0%, rgba(220, 38, 38, 0.25) 100%);
            color: #ffffff;
            border-color: rgba(239, 68, 68, 0.5);
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
            color: #1e5245;
        }

        @keyframes slideX {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(12px); }
        }

        /* Card Footer */
        .card-footer {
            margin-top: auto;
            padding-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        /* Icon Only Button - Centered */
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
            color: #1e5245;
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

        .btn-card-action.btn-transfer {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.3) 0%, rgba(34, 197, 94, 0.2) 100%);
            border-color: rgba(16, 185, 129, 0.5);
        }

        .btn-card-action.btn-transfer:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.98) 0%, rgba(34, 197, 94, 0.92) 100%);
            color: white;
        }

        /* Modal Transfer */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 25px;
            width: 100%;
            max-width: 480px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
            animation: slideUp 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-header {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            padding: 25px;
            border-radius: 25px 25px 0 0;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-weight: 900;
            font-size: 1.3rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.4);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(220, 38, 38, 0.9);
            border-color: #dc2626;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-info {
            background: #f0fdf4;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #16a34a;
        }

        .modal-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dcfce7;
        }

        .modal-info-row:last-child {
            border-bottom: none;
        }

        .modal-info-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 700;
        }

        .modal-info-value {
            font-size: 0.9rem;
            color: #222;
            font-weight: 900;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-label ion-icon {
            font-size: 1.1rem;
            color: #16a34a;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d1d5db;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-weight: 900;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(22, 163, 74, 0.4);
        }

        .text-danger {
            color: #dc2626;
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
            max-height: 1800px;
            opacity: 1;
            transform: translateY(0);
        }

        .detail-content {
            background: linear-gradient(135deg, #1e5245 0%, #2d6a5a 100%);
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
                    
                    <div style="width: 100%;">
                        <div class="pricing-card {{ $variantClass }}" data-barang-id="{{ $d->id }}" data-variant="{{ $variantClass }}">
                        {{-- Background Photo with Enhanced Effect --}}
                        @if($d->foto)
                            <div class="card-background" style="background-image: url('{{ asset('storage/barang/' . $d->foto) }}');"></div>
                        @else
                            <div class="card-background" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                        @endif
                        
                        {{-- Gradient Overlay --}}
                        <div class="card-overlay"></div>
                        
                        {{-- Card Content --}}
                        <div class="card-content">
                            <div class="card-badge">{{ $d->kategori ?? $badgeText }}</div>
                            
                            <div class="card-title">
                                {{ textUpperCase($d->nama_barang) }}
                            </div>
                            
                            <div class="card-subtitle">
                                {{ $d->kode_barang }}
                            </div>
                            
                            <div class="card-stats">
                                {{-- Merk --}}
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

                                {{-- Tahun Perolehan --}}
                                @if($d->tahun_perolehan)
                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="calendar-outline"></ion-icon> Tahun
                                        </div>
                                        <div class="stat-value">
                                            {{ $d->tahun_perolehan }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Kondisi --}}
                                <div class="stat-item" style="flex-direction: column; align-items: flex-start; gap: 10px;">
                                    <div class="stat-label">
                                        <ion-icon name="pulse-outline"></ion-icon> Kondisi Barang
                                    </div>
                                    @if($d->kondisi == 'baik')
                                        <span class="kondisi-badge kondisi-baik">
                                            ✓ Baik
                                        </span>
                                    @elseif($d->kondisi == 'rusak-ringan')
                                        <span class="kondisi-badge kondisi-rusak-ringan">
                                            ⚠ Rusak Ringan
                                        </span>
                                    @elseif($d->kondisi == 'rusak-berat')
                                        <span class="kondisi-badge kondisi-rusak-berat">
                                            ✕ Rusak Berat
                                        </span>
                                    @else
                                        <span class="kondisi-badge kondisi-baik">
                                            - Tidak Diketahui
                                        </span>
                                    @endif
                                </div>

                                {{-- Harga Perolehan --}}
                                @if($d->harga_perolehan)
                                    <div class="stat-item">
                                        <div class="stat-label">
                                            <ion-icon name="cash-outline"></ion-icon> Harga
                                        </div>
                                        <div class="stat-value">
                                            Rp {{ number_format($d->harga_perolehan, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Keterangan --}}
                                @if($d->keterangan)
                                    <div class="stat-item" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                                        <div class="stat-label">
                                            <ion-icon name="information-circle-outline"></ion-icon> Keterangan
                                        </div>
                                        <div style="font-size: 0.78rem; opacity: 0.92; line-height: 1.45; font-weight: 500;">
                                            {{ $d->keterangan }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer">
                                <button class="btn-card-action" onclick="toggleDetail('barang-{{ $d->id }}', event)">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </button>
                                <button class="btn-card-action btn-transfer" onclick="openTransferModal('{{ Crypt::encrypt($d->id) }}', '{{ $d->nama_barang }}', '{{ $d->kode_barang }}', event)">
                                    <ion-icon name="arrow-forward-up"></ion-icon>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Panel --}}
                    <div class="detail-panel {{ $variantClass }}" id="barang-{{ $d->id }}">
                        <div class="detail-content">
                            <div class="detail-header">
                                <div class="detail-title">
                                    <ion-icon name="information-circle"></ion-icon>
                                    Informasi Detail Barang
                                </div>
                                <button class="detail-close" onclick="toggleDetail('barang-{{ $d->id }}', event)">
                                    <ion-icon name="close" style="font-size: 1.4rem;"></ion-icon>
                                </button>
                            </div>
                            
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <ion-icon name="cube-outline"></ion-icon>
                                        Nama Barang
                                    </div>
                                    <div class="detail-value">{{ $d->nama_barang }}</div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <ion-icon name="pricetag-outline"></ion-icon>
                                        Kode Barang
                                    </div>
                                    <div class="detail-value">{{ $d->kode_barang }}</div>
                                </div>
                                
                                @if($d->kategori)
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="apps-outline"></ion-icon>
                                            Kategori
                                        </div>
                                        <div class="detail-value">
                                            <div class="detail-badge">
                                                <ion-icon name="apps"></ion-icon>
                                                {{ $d->kategori }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($d->merk)
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="ribbon-outline"></ion-icon>
                                            Merk
                                        </div>
                                        <div class="detail-value">{{ $d->merk }}</div>
                                    </div>
                                @endif
                                
                                @if($d->tahun_perolehan)
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <ion-icon name="calendar-outline"></ion-icon>
                                            Tahun Perolehan
                                        </div>
                                        <div class="detail-value">
                                            <div class="detail-badge">
                                                <ion-icon name="calendar"></ion-icon>
                                                {{ $d->tahun_perolehan }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <ion-icon name="pulse-outline"></ion-icon>
                                        Kondisi
                                    </div>
                                    <div class="detail-value">
                                        @if($d->kondisi == 'baik')
                                            <span class="kondisi-badge kondisi-baik">
                                                ✓ Baik
                                            </span>
                                        @elseif($d->kondisi == 'rusak-ringan')
                                            <span class="kondisi-badge kondisi-rusak-ringan">
                                                ⚠ Rusak Ringan
                                            </span>
                                        @elseif($d->kondisi == 'rusak-berat')
                                            <span class="kondisi-badge kondisi-rusak-berat">
                                                ✕ Rusak Berat
                                            </span>
                                        @else
                                            <span class="kondisi-badge kondisi-baik">
                                                - Tidak Diketahui
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($d->harga_perolehan)
                                    <div class="detail-item full-width">
                                        <div class="detail-label">
                                            <ion-icon name="cash-outline"></ion-icon>
                                            Harga Perolehan
                                        </div>
                                        <div class="detail-value" style="font-size: 1.3rem;">
                                            Rp {{ number_format($d->harga_perolehan, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($d->keterangan)
                                    <div class="detail-item full-width">
                                        <div class="detail-label">
                                            <ion-icon name="information-circle-outline"></ion-icon>
                                            Keterangan
                                        </div>
                                        <div class="detail-value" style="line-height: 1.6; margin-top: 5px;">
                                            {{ $d->keterangan }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Action Button - Riwayat Only --}}
                                <div class="detail-item full-width">
                                    <a href="{{ route('barang.riwayatKaryawan', [
                                        'gedung_id' => Crypt::encrypt($gedung->id),
                                        'ruangan_id' => Crypt::encrypt($ruangan->id),
                                            'id' => Crypt::encrypt($d->id)
                                        ]) }}" style="text-decoration: none; display: block;">
                                        <button style="width: 100%; padding: 14px; background: linear-gradient(135deg, rgba(251, 146, 60, 0.95) 0%, rgba(249, 115, 22, 0.95) 100%); border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 16px; color: white; font-weight: 900; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);">
                                            <ion-icon name="time-outline" style="font-size: 1.3rem;"></ion-icon>
                                            Riwayat Transfer
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($barang->hasPages())
                <div style="padding: 20px;">
                    {{ $barang->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <ion-icon name="cube-outline"></ion-icon>
                <h4>Tidak Ada Barang</h4>
                <p>Belum ada data barang yang tersedia di ruangan ini</p>
            </div>
        @endif
    </div>

    {{-- Modal Transfer --}}
    <div class="modal-overlay" id="transferModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <ion-icon name="arrow-forward-up"></ion-icon>
                    Transfer Barang
                </h3>
                <button class="modal-close" onclick="closeTransferModal()">
                    <ion-icon name="close" style="font-size: 1.3rem; color: white;"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-info">
                    <div class="modal-info-row">
                        <span class="modal-info-label">Kode Barang</span>
                        <span class="modal-info-value" id="modal-kode"></span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-label">Nama Barang</span>
                        <span class="modal-info-value" id="modal-nama"></span>
                    </div>
                </div>

                <form id="formTransferBarang" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">
                            <ion-icon name="location-outline"></ion-icon> 
                            Ruangan Tujuan <span class="text-danger">*</span>
                        </label>
                        <select name="ruangan_tujuan_id" id="ruangan_tujuan_id" class="form-select">
                            <option value="">-- Pilih Ruangan Tujuan --</option>
                            @foreach ($all_ruangan ?? [] as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->gedung->nama_gedung }} - {{ $r->nama_ruangan }} 
                                    @if($r->lantai) (Lantai {{ $r->lantai }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <ion-icon name="cube-outline"></ion-icon> 
                            Jumlah Transfer <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="jumlah_transfer" id="jumlah_transfer" class="form-control" 
                            min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <ion-icon name="calendar-outline"></ion-icon> 
                            Tanggal Transfer <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="tanggal_transfer" id="tanggal_transfer" class="form-control" 
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <ion-icon name="person-outline"></ion-icon> 
                            Petugas
                        </label>
                        <input type="text" name="petugas" id="petugas" class="form-control" 
                            value="{{ auth()->user()->name }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <ion-icon name="document-text-outline"></ion-icon> 
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button class="btn-submit" type="submit">
                            <ion-icon name="arrow-forward-up" style="font-size: 1.3rem;"></ion-icon>
                            Proses Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let allRuangan = @json($all_ruangan ?? []);

        function openTransferModal(encryptedBarangId, namaBarang, kodeBarang, event) {
            event.preventDefault();
            event.stopPropagation();
            
            document.getElementById('modal-kode').textContent = kodeBarang;
            document.getElementById('modal-nama').textContent = namaBarang;
            
            // Set form action with encrypted barang_id
            const form = document.getElementById('formTransferBarang');
            const baseUrl = '{{ route("barang.prosesTransferKaryawan", [
                "gedung_id" => Crypt::encrypt($gedung->id),
                "ruangan_id" => Crypt::encrypt($ruangan->id),
                "id" => "__BARANG_ID__"
            ]) }}';
            form.action = baseUrl.replace('__BARANG_ID__', encryptedBarangId);
            
            document.getElementById('transferModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeTransferModal() {
            document.getElementById('transferModal').classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('formTransferBarang').reset();
        }

        // Close modal when clicking overlay
        document.getElementById('transferModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransferModal();
            }
        });

        // Form validation
        document.getElementById('formTransferBarang').addEventListener('submit', function(e) {
            const ruanganTujuan = document.getElementById('ruangan_tujuan_id').value;
            const jumlahTransfer = document.getElementById('jumlah_transfer').value;
            const tanggalTransfer = document.getElementById('tanggal_transfer').value;

            if (!ruanganTujuan) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih ruangan tujuan!',
                    confirmButtonColor: '#16a34a'
                });
                return false;
            }

            if (!jumlahTransfer || jumlahTransfer < 1) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Jumlah transfer minimal 1!',
                    confirmButtonColor: '#16a34a'
                });
                return false;
            }

            if (!tanggalTransfer) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Tanggal transfer harus diisi!',
                    confirmButtonColor: '#16a34a'
                });
                return false;
            }

            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses transfer barang',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        function toggleDetail(panelId, event) {
            event.preventDefault();
            event.stopPropagation();
            
            const panel = document.getElementById(panelId);
            const allPanels = document.querySelectorAll('.detail-panel');
            
            // Close all other panels
            allPanels.forEach(p => {
                if (p.id !== panelId) {
                    p.classList.remove('active');
                }
            });
            
            // Toggle current panel
            panel.classList.toggle('active');
            
            // Scroll to panel if opening
            if (panel.classList.contains('active')) {
                setTimeout(() => {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        }
    </script>
@endsection
