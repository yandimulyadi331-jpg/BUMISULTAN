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
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            --gradient-3: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
            --gradient-4: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            
            --color-1: #667eea;
            --color-2: #2563eb;
            --color-3: #10b981;
            --color-4: #f97316;
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
            height: auto;
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
            padding: 0 0 100px 0;
        }

        /* ========== SUMMARY STATS ========== */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 0 20px 20px 20px;
        }

        .stat-card {
            background: var(--bg-primary);
            border-radius: 18px;
            padding: 15px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .stat-card:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .stat-card:nth-child(2) .stat-icon {
            background: var(--gradient-2);
        }

        .stat-card:nth-child(3) .stat-icon {
            background: var(--gradient-3);
        }

        .stat-card .stat-info {
            text-align: center;
        }

        .stat-card .stat-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-value {
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--text-primary);
            line-height: 1;
        }

        /* ========== GEDUNG GRID ========== */
        .gedung-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 0 20px;
        }

        /* ========== GEDUNG CARD ========== */
        .gedung-card {
            background: var(--bg-primary);
            border-radius: 25px;
            padding: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .gedung-card:hover {
            box-shadow: 12px 12px 24px var(--shadow-dark),
                       -12px -12px 24px var(--shadow-light);
            transform: translateY(-5px);
        }

        /* Card Header */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }

        .card-title-section {
            flex: 1;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 900;
            color: var(--text-primary);
            margin: 0 0 5px 0;
            line-height: 1.2;
        }

        .card-code {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin: 0;
        }

        .card-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: white;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card-badge.variant-1 {
            background: var(--gradient-1);
        }

        .card-badge.variant-2 {
            background: var(--gradient-2);
        }

        .card-badge.variant-3 {
            background: var(--gradient-3);
        }

        .card-badge.variant-4 {
            background: var(--gradient-4);
        }

        /* Photo Section */
        .card-photo-section {
            width: 100%;
            height: 160px;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.1),
                        inset -2px -2px 6px rgba(255, 255, 255, 0.05);
        }

        .card-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gedung-card:hover .card-photo {
            transform: scale(1.08);
        }

        .card-photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
        }

        .card-photo-placeholder.variant-1 {
            background: var(--gradient-1);
        }

        .card-photo-placeholder.variant-2 {
            background: var(--gradient-2);
        }

        .card-photo-placeholder.variant-3 {
            background: var(--gradient-3);
        }

        .card-photo-placeholder.variant-4 {
            background: var(--gradient-4);
        }

        /* Stats Section */
        .card-stats-section {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            background: var(--bg-primary);
            border-radius: 14px;
            box-shadow: inset 2px 2px 5px var(--shadow-dark),
                        inset -2px -2px 5px var(--shadow-light);
            transition: all 0.2s ease;
        }

        .stat-item:hover {
            box-shadow: inset 3px 3px 7px var(--shadow-dark),
                        inset -3px -3px 7px var(--shadow-light);
        }

        .stat-icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-icon-wrapper.variant-1 {
            background: var(--gradient-1);
        }

        .stat-icon-wrapper.variant-2 {
            background: var(--gradient-2);
        }

        .stat-icon-wrapper.variant-3 {
            background: var(--gradient-3);
        }

        .stat-icon-wrapper.variant-4 {
            background: var(--gradient-4);
        }

        .stat-content {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 900;
            color: var(--text-primary);
        }

        /* Footer Button */
        .card-footer {
            margin-top: auto;
        }

        .btn-view-rooms {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .btn-view-rooms.variant-1 {
            background: var(--gradient-1);
        }

        .btn-view-rooms.variant-2 {
            background: var(--gradient-2);
        }

        .btn-view-rooms.variant-3 {
            background: var(--gradient-3);
        }

        .btn-view-rooms.variant-4 {
            background: var(--gradient-4);
        }

        .btn-view-rooms:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .btn-view-rooms:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-view-rooms ion-icon {
            font-size: 20px;
        }

        /* ========== ROOM LIST PANEL ========== */
        .room-list-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.3s ease,
                        margin 0.3s ease;
            opacity: 0;
            margin: 0 20px;
            background: var(--bg-primary);
            border-radius: 25px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
        }

        .room-list-panel.active {
            max-height: 800px;
            opacity: 1;
            margin: 15px 20px;
            overflow-y: auto;
        }

        .panel-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border-color);
            color: white;
            border-radius: 25px 25px 0 0;
        }

        .panel-header.variant-1 {
            background: var(--gradient-1);
        }

        .panel-header.variant-2 {
            background: var(--gradient-2);
        }

        .panel-header.variant-3 {
            background: var(--gradient-3);
        }

        .panel-header.variant-4 {
            background: var(--gradient-4);
        }

        .panel-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 700;
        }

        .panel-title ion-icon {
            font-size: 22px;
        }

        .btn-close-panel {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .btn-close-panel:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .panel-content {
            padding: 15px;
        }

        /* Room Item */
        .room-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
        }

        .room-item:last-child {
            border-bottom: none;
        }

        .room-item:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        body.dark-mode .room-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .room-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .room-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .room-icon.variant-1 {
            background: var(--gradient-1);
        }

        .room-icon.variant-2 {
            background: var(--gradient-2);
        }

        .room-icon.variant-3 {
            background: var(--gradient-3);
        }

        .room-icon.variant-4 {
            background: var(--gradient-4);
        }

        .room-details {
            flex: 1;
        }

        .room-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .room-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .room-code {
            font-weight: 600;
            background: var(--bg-primary);
            padding: 3px 10px;
            border-radius: 8px;
            box-shadow: inset 1px 1px 3px var(--shadow-dark),
                        inset -1px -1px 3px var(--shadow-light);
        }

        .room-capacity,
        .room-items {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .room-capacity ion-icon,
        .room-items ion-icon {
            font-size: 14px;
        }

        .room-actions {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: none;
            background: var(--bg-primary);
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 18px;
            color: white;
            text-decoration: none;
        }

        .btn-action.btn-view.variant-1 {
            background: var(--gradient-1);
        }

        .btn-action.btn-view.variant-2 {
            background: var(--gradient-2);
        }

        .btn-action.btn-view.variant-3 {
            background: var(--gradient-3);
        }

        .btn-action.btn-view.variant-4 {
            background: var(--gradient-4);
        }

        .btn-action.btn-navigate {
            background: var(--gradient-3);
        }

        .btn-action:active {
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                        inset -2px -2px 4px var(--shadow-light);
        }

        /* Empty Rooms */
        .empty-rooms {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-rooms ion-icon {
            font-size: 60px;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        .empty-rooms p {
            font-size: 0.9rem;
        }

        /* ========== EMPTY STATE ========== */
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

        /* ========== PAGINATION ========== */
        .pagination-wrapper {
            padding: 20px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 767px) {
            .gedung-grid {
                grid-template-columns: 1fr;
            }

            .summary-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 480px) {
            .card-title {
                font-size: 1rem;
            }

            .card-photo-section {
                height: 140px;
            }

            .btn-view-rooms {
                font-size: 0.85rem;
                padding: 12px;
            }

            .room-meta {
                flex-direction: column;
                gap: 5px;
            }
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
            {{-- Summary Stats --}}
            <div class="summary-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <ion-icon name="business"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Gedung</div>
                        <div class="stat-value">{{ $gedung->total() }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <ion-icon name="door-open"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Ruangan</div>
                        <div class="stat-value">{{ $gedung->sum('total_ruangan') }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <ion-icon name="cube"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Barang</div>
                        <div class="stat-value">{{ $gedung->sum('total_barang') }}</div>
                    </div>
                </div>
            </div>

            {{-- Gedung Grid --}}
            <div class="gedung-grid">
                @foreach ($gedung as $index => $d)
                    @php
                        $variants = ['variant-1', 'variant-2', 'variant-3', 'variant-4'];
                        $variantClass = $variants[$index % 4];
                        $badges = ['STANDARD', 'PREMIUM', 'BASIC', 'PLATINUM'];
                        $badgeText = $badges[$index % 4];
                    @endphp
                    
                    <div class="gedung-card-wrapper">
                        {{-- Gedung Card --}}
                        <div class="gedung-card {{ $variantClass }}" data-gedung-id="{{ $d->id }}">
                            {{-- Card Header --}}
                            <div class="card-header">
                                <div class="card-title-section">
                                    <h4 class="card-title">{{ textUpperCase($d->nama_gedung) }}</h4>
                                    <p class="card-code">{{ $d->kode_gedung }}</p>
                                </div>
                                <span class="card-badge {{ $variantClass }}">{{ $badgeText }}</span>
                            </div>

                            {{-- Photo Section --}}
                            <div class="card-photo-section">
                                @if($d->foto)
                                    <img src="{{ asset('storage/gedung/' . $d->foto) }}" 
                                         alt="{{ $d->nama_gedung }}"
                                         class="card-photo">
                                @else
                                    <div class="card-photo-placeholder {{ $variantClass }}">
                                        <ion-icon name="business-outline"></ion-icon>
                                    </div>
                                @endif
                            </div>

                            {{-- Stats Section --}}
                            <div class="card-stats-section">
                                <div class="stat-item">
                                    <div class="stat-icon-wrapper {{ $variantClass }}">
                                        <ion-icon name="layers"></ion-icon>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Lantai</span>
                                        <span class="stat-value">{{ $d->jumlah_lantai }}</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon-wrapper {{ $variantClass }}">
                                        <ion-icon name="door-open"></ion-icon>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Ruangan</span>
                                        <span class="stat-value">{{ $d->total_ruangan }}</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon-wrapper {{ $variantClass }}">
                                        <ion-icon name="cube"></ion-icon>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Barang</span>
                                        <span class="stat-value">{{ $d->total_barang }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Button --}}
                            <div class="card-footer">
                                <a href="{{ route('ruangan.karyawan', Crypt::encrypt($d->id)) }}" class="btn-view-rooms {{ $variantClass }}">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    <span>Lihat Ruangan</span>
                                </a>
                            </div>
                        </div>

                        {{-- Room List Panel (Hidden - no longer used) --}}
                        <div class="room-list-panel" id="room-list-{{ $d->id }}" style="display: none;">
                            <div class="panel-header {{ $variantClass }}">
                                <div class="panel-title">
                                    <ion-icon name="list-outline"></ion-icon>
                                    <span>Daftar Ruangan - {{ $d->nama_gedung }}</span>
                                </div>
                                <button class="btn-close-panel" 
                                        onclick="toggleRoomList('room-list-{{ $d->id }}', event)">
                                    <ion-icon name="close"></ion-icon>
                                </button>
                            </div>

                            <div class="panel-content">
                                @if($d->ruangans && $d->ruangans->count() > 0)
                                    @foreach($d->ruangans as $room)
                                        <div class="room-item">
                                            <div class="room-info">
                                                <div class="room-icon {{ $variantClass }}">
                                                    <ion-icon name="door-open"></ion-icon>
                                                </div>
                                                <div class="room-details">
                                                    <div class="room-name">{{ $room->nama_ruangan }}</div>
                                                    <div class="room-meta">
                                                        <span class="room-code">{{ $room->kode_ruangan }}</span>
                                                        @if($room->kapasitas)
                                                            <span class="room-capacity">
                                                                <ion-icon name="people-outline"></ion-icon>
                                                                {{ $room->kapasitas }} orang
                                                            </span>
                                                        @endif
                                                        @if($room->lantai)
                                                            <span class="room-capacity">
                                                                <ion-icon name="layers-outline"></ion-icon>
                                                                Lantai {{ $room->lantai }}
                                                            </span>
                                                        @endif
                                                        <span class="room-items">
                                                            <ion-icon name="cube-outline"></ion-icon>
                                                            {{ $room->total_barang ?? 0 }} item
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="room-actions">
                                                <button class="btn-action btn-view {{ $variantClass }}" 
                                                        onclick="viewRoomDetail('{{ $room->nama_ruangan }}', '{{ $room->kode_ruangan }}', {{ $room->kapasitas ?? 0 }}, {{ $room->total_barang ?? 0 }}, '{{ $room->lantai ?? '' }}', '{{ $room->foto ? asset('storage/uploads/ruangan/'.$room->foto) : '' }}')">
                                                    <ion-icon name="eye-outline"></ion-icon>
                                                </button>
                                                <a href="{{ route('barang.karyawan', ['gedung_id' => Crypt::encrypt($d->id), 'ruangan_id' => Crypt::encrypt($room->id)]) }}" class="btn-action btn-navigate">
                                                    <ion-icon name="arrow-forward-outline"></ion-icon>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-rooms">
                                        <ion-icon name="file-tray-outline"></ion-icon>
                                        <p>Belum ada ruangan di gedung ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($gedung->hasPages())
                <div class="pagination-wrapper">
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
        // Toggle Room List Panel
        function toggleRoomList(panelId, event) {
            event.preventDefault();
            event.stopPropagation();
            
            const panel = document.getElementById(panelId);
            const allPanels = document.querySelectorAll('.room-list-panel');
            
            // Close all other panels
            allPanels.forEach(p => {
                if (p.id !== panelId && p.classList.contains('active')) {
                    p.classList.remove('active');
                }
            });
            
            // Toggle current panel
            panel.classList.toggle('active');
            
            // Scroll to panel if opening
            if (panel.classList.contains('active')) {
                setTimeout(() => {
                    panel.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'nearest',
                        inline: 'nearest'
                    });
                }, 100);
            }
        }

        // View Room Detail Modal
        function viewRoomDetail(roomName, roomCode, kapasitas, totalBarang, lantai, foto) {
            let fotoHtml = '';
            if (foto) {
                fotoHtml = `<div style="text-align: center; margin-bottom: 20px;">
                    <img src="${foto}" alt="${roomName}" style="max-width: 100%; max-height: 200px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </div>`;
            }

            // Get computed styles for dark mode support
            const isDarkMode = document.body.classList.contains('dark-mode');
            const bgColor = isDarkMode ? '#2d3748' : '#e0e5ec';
            const textColor = isDarkMode ? '#f7fafc' : '#2d3748';
            const labelColor = isDarkMode ? '#a0aec0' : '#718096';
            const borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)';

            Swal.fire({
                title: roomName,
                html: `
                    ${fotoHtml}
                    <div style="background: ${bgColor}; padding: 15px; border-radius: 15px; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.1), inset -2px -2px 5px rgba(255,255,255,0.1);">
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kode Ruangan</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${roomCode}</span>
                        </div>
                        ${lantai ? `<div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Lantai</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${lantai}</span>
                        </div>` : ''}
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kapasitas</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kapasitas} orang</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Total Barang</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${totalBarang} item</span>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '450px',
                background: bgColor,
                color: textColor,
                customClass: {
                    popup: 'swal-neomorphic'
                }
            });
        }

        // Auto-close panels when clicking outside
        document.addEventListener('click', function(event) {
            const panels = document.querySelectorAll('.room-list-panel.active');
            const cards = document.querySelectorAll('.gedung-card');
            const buttons = document.querySelectorAll('.btn-view-rooms');
            
            let clickedInside = false;
            
            // Check if clicked inside any card or panel
            panels.forEach(panel => {
                if (panel.contains(event.target)) {
                    clickedInside = true;
                }
            });
            
            cards.forEach(card => {
                if (card.contains(event.target)) {
                    clickedInside = true;
                }
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) {
                    clickedInside = true;
                }
            });
            
            // Close all panels if clicked outside
            if (!clickedInside) {
                panels.forEach(panel => {
                    panel.classList.remove('active');
                });
            }
        });
    </script>
@endsection
