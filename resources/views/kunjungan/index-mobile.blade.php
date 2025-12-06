@extends('layouts.mobile.app')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Dark Mode Variables */
        :root {
            --bg-primary: #e8eef3;
            --bg-secondary: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --shadow-light: rgba(255, 255, 255, 0.9);
            --shadow-dark: rgba(94, 104, 121, 0.3);
            --border-color: #bdc3c7;
            --icon-color: #3498db;
        }

        body.dark-mode {
            --bg-primary: #2c3e50;
            --bg-secondary: #34495e;
            --text-primary: #ecf0f1;
            --text-secondary: #bdc3c7;
            --shadow-light: rgba(255, 255, 255, 0.05);
            --shadow-dark: rgba(0, 0, 0, 0.3);
            --border-color: #34495e;
            --icon-color: #3498db;
        }

        body {
            background: var(--bg-primary);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
            transition: background 0.3s ease;
        }

        /* Hide global theme toggle from layout */
        body > #theme-toggle {
            display: none !important;
        }

        /* Header Section */
        #header-section {
            background: var(--bg-primary);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .back-button {
            background: var(--bg-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .back-button ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        .logo-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .theme-toggle {
            background: var(--bg-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .theme-toggle:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .theme-toggle ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        .export-button {
            background: var(--bg-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            margin-left: 10px;
        }

        .export-button:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .export-button ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        /* Content Section */
        #content-section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding-top: 85px;
            padding-bottom: 200px;
            min-height: calc(100vh - 60px);
        }

        /* Filter Card with Animated Rainbow Border */
        .filter-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            position: relative;
            overflow: visible;
        }

        .filter-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #ff0000, #ff7f00, #ffff00, #00ff00,
                #0000ff, #4b0082, #9400d3, #ff0000
            );
            background-size: 400% 400%;
            border-radius: 22px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .filter-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 20px;
            z-index: -1;
        }

        @keyframes gradientMove {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .filter-input, .feedback-input {
            background: var(--bg-primary);
            border: none;
            border-radius: 12px;
            padding: 14px 18px;
            width: 100%;
            margin-bottom: 12px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .filter-input:focus, .feedback-input:focus {
            outline: none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
        }

        .filter-input::placeholder, .feedback-input::placeholder {
            color: var(--text-secondary);
        }

        .btn-search, .btn-primary {
            background: linear-gradient(145deg, #2196F3, #1976D2);
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:active {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2),
                       inset -4px -4px 8px rgba(255, 255, 255, 0.1);
            transform: scale(0.98);
        }

        /* Activity Card with Animated Rainbow Border */
        .activity-card, .timeline-item {
            background: var(--bg-primary);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: visible;
            cursor: pointer;
        }

        .activity-card::before, .timeline-item::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #00ff00, #00ff7f, #00ffff, #00bfff,
                #007fff, #003fff, #007fff, #00bfff,
                #00ffff, #00ff7f, #00ff00
            );
            background-size: 400% 400%;
            border-radius: 20px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .activity-card::after, .timeline-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 18px;
            z-index: -1;
        }

        .activity-card:active, .timeline-item:active {
            transform: scale(0.98);
        }

        .timeline-content {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .timeline-photo {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-info {
            flex: 1;
            min-width: 0;
        }

        .timeline-datetime-group {
            margin-bottom: 2px;
        }

        .timeline-time {
            font-size: 1.1em;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            display: block;
        }

        .timeline-date {
            font-size: 1.1em;
            color: var(--text-secondary);
            font-weight: 500;
            line-height: 1.2;
            margin-top: 2px;
            display: block;
        }

        .timeline-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
            line-height: 1.3;
        }

        .timeline-description {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .timeline-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .timeline-location {
            display: flex;
            align-items: center;
            color: #2196F3;
            font-size: 13px;
            font-weight: 500;
            flex: 1;
            min-width: 0;
        }

        .timeline-location ion-icon {
            margin-right: 6px;
            font-size: 16px;
            flex-shrink: 0;
        }

        .timeline-location span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .timeline-distance {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 10px;
            flex-shrink: 0;
        }

        /* Empty State */
        .empty-state, .timeline-empty {
            text-align: center;
            padding: 60px 20px;
            background: var(--bg-primary);
            border-radius: 18px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
        }

        .empty-state ion-icon, .timeline-empty i {
            font-size: 64px;
            color: var(--text-secondary);
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h4, .timeline-empty h4 {
            color: var(--text-primary);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .empty-state p, .timeline-empty p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Style untuk button hapus di sudut kanan card */
        .timeline-item .right {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }

        .timeline-item .right .price {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .timeline-item .right .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-item .right .btn ion-icon {
            font-size: 18px;
        }

        .timeline-item .right .deleteform {
            margin: 0;
        }

        /* Style untuk foto di modal */
        #modalPhoto img {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        /* Foto full width di mobile */
        @media (max-width: 576px) {
            #modalPhoto img {
                border-radius: 8px;
                max-height: 300px;
                object-fit: cover;
            }
        }

        /* Custom Modal Styles */
        .custom-modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
            z-index: 99999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            margin: 0 !important;
            border: none !important;
        }

        .custom-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Ensure no other elements interfere with backdrop */
        .custom-modal-overlay.show * {
            position: relative;
            z-index: 1;
        }

        /* Hide other elements when modal is open */
        body.modal-open {
            overflow: hidden !important;
        }

        body.modal-open .appHeader,
        body.modal-open .bottomMenu,
        body.modal-open .fab {
            z-index: 1 !important;
        }

        .custom-modal {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.8) translateY(20px);
            transition: all 0.3s ease;
        }

        .custom-modal-overlay.show .custom-modal {
            transform: scale(1) translateY(0);
        }

        .custom-modal-header {
            padding: 1.5rem 1.5rem 0 1.5rem;
            border-bottom: none;
            position: relative;
        }

        .custom-modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .custom-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .custom-modal-close:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        .custom-modal-body {
            padding: 1.5rem;
            max-height: calc(90vh - 120px);
            overflow-y: auto;
        }

        .custom-modal-footer {
            padding: 0 1.5rem 1.5rem 1.5rem;
            border-top: none;
            display: flex;
            justify-content: flex-end;
        }

        .custom-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .custom-btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .custom-btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .custom-modal-overlay {
                padding: 0.5rem !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 99999 !important;
                margin: 0 !important;
                border: none !important;
            }

            .custom-modal {
                max-width: 100%;
                max-height: 95vh;
                border-radius: 12px;
            }

            .custom-modal-header {
                padding: 1rem 1rem 0 1rem;
            }

            .custom-modal-body {
                padding: 1rem;
                max-height: calc(95vh - 100px);
            }

            .custom-modal-footer {
                padding: 0 1rem 1rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .custom-modal-overlay {
                padding: 0.25rem !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 99999 !important;
                margin: 0 !important;
                border: none !important;
            }

            .custom-modal {
                border-radius: 8px;
                max-height: 98vh;
            }

            .custom-modal-header {
                padding: 0.75rem 0.75rem 0 0.75rem;
            }

            .custom-modal-body {
                padding: 0.75rem;
                max-height: calc(98vh - 80px);
            }

            .custom-modal-footer {
                padding: 0 0.75rem 0.75rem 0.75rem;
            }
        }
    </style>

    <div id="header-section">
        <a href="{{ route('dashboard.index') }}" class="back-button">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
        <div class="logo-title">Kunjungan Saya</div>
        <div style="display: flex; align-items: center;">
            <button class="theme-toggle" id="themeToggle">
                <ion-icon name="moon-outline" id="themeIcon"></ion-icon>
            </button>
            <a href="{{ route('kunjungan.export.pdf', request()->query()) }}" class="export-button" target="_blank" title="Export PDF">
                <ion-icon name="document-text-outline"></ion-icon>
            </a>
        </div>
    </div>

    <div id="content-section">
        <div class="filter-card">
            <form action="{{ route('kunjungan.index') }}" method="GET">
                <input type="text" class="filter-input dari" name="tanggal_awal" placeholder="Dari" id="datePicker"
                    value="{{ Request('tanggal_awal', date('Y-m-d')) }}" />
                <input type="text" class="filter-input sampai" name="tanggal_akhir" placeholder="Sampai" id="datePicker2"
                    value="{{ Request('tanggal_akhir', date('Y-m-d')) }}" />
                <button class="btn-search" id="btnSimpan"><ion-icon name="search-circle-outline"></ion-icon>Cari</button>
            </form>
        </div>
        <div style="padding-bottom: 20px;">
            <!-- Kunjungan Cards -->
                @if ($kunjungan->count() > 0)
                    @foreach ($kunjungan as $index => $item)
                        @php
                            $prevItem = $kunjungan->get($index - 1);
                            $distance = '';
                            if ($prevItem && $item->lokasi && $prevItem->lokasi) {
                                $coords1 = explode(',', $item->lokasi);
                                $coords2 = explode(',', $prevItem->lokasi);
                                if (count($coords1) == 2 && count($coords2) == 2) {
                                    $lat1 = floatval($coords1[0]);
                                    $lon1 = floatval($coords1[1]);
                                    $lat2 = floatval($coords2[0]);
                                    $lon2 = floatval($coords2[1]);

                                    // Haversine formula untuk menghitung jarak
                                    $earthRadius = 6371; // km
                                    $dLat = deg2rad($lat2 - $lat1);
                                    $dLon = deg2rad($lon2 - $lon1);
                                    $a =
                                        sin($dLat / 2) * sin($dLat / 2) +
                                        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
                                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                                    $distanceKm = $earthRadius * $c;

                                    if ($distanceKm < 1) {
                                        $distance = round($distanceKm * 1000) . ' m';
                                    } else {
                                        $distance = round($distanceKm, 1) . ' km';
                                    }
                                }
                            }
                        @endphp

                        <div class="timeline-item"
                            onclick="showDetailModal({{ $item->id }}, '{{ $item->deskripsi }}', '{{ $item->tanggal_kunjungan->format('d M Y') }}', '{{ $item->created_at->format('H:i') }}', '{{ $item->lokasi }}', '{{ $item->foto }}')">

                            <div class="timeline-content">
                                <!-- Photo Section -->
                                @if ($item->foto)
                                    @if (str_starts_with($item->foto, 'http'))
                                        <img src="{{ $item->foto }}" alt="Foto Kunjungan" class="timeline-photo">
                                    @else
                                        <img src="{{ asset('storage/uploads/kunjungan/' . $item->foto) }}" alt="Foto Kunjungan"
                                            class="timeline-photo">
                                    @endif
                                @else
                                    <img src="https://placehold.co/60x60/f0f0f0/999999/png?text=Kunjungan" alt="Foto Kunjungan"
                                        class="timeline-photo">
                                @endif

                                <!-- Info Section -->
                                <div class="timeline-info">
                                    <div class="timeline-datetime-group">
                                        <div class="timeline-time">{{ $item->created_at->format('H:i') }}</div>
                                        <div class="timeline-date">{{ $item->tanggal_kunjungan->format('d M Y') }}</div>
                                    </div>

                                    <div class="timeline-title">Kunjungan</div>

                                    <div class="timeline-description">
                                        {{ $item->deskripsi }}
                                    </div>

                                    <div class="timeline-meta">
                                        <div class="timeline-location">
                                            <ion-icon name="location-outline"></ion-icon>
                                            <span>
                                                @if ($item->lokasi)
                                                    {{ $item->lokasi }}
                                                @else
                                                    Lokasi tidak tersedia
                                                @endif
                                            </span>
                                        </div>

                                        @if ($distance)
                                            <div class="timeline-distance">{{ $distance }}</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="timeline-empty">
                        <ion-icon name="map-outline"></ion-icon>
                        <h4>Belum Ada Kunjungan</h4>
                        <p>Mulai catat kunjungan harian Anda</p>
                    </div>
                @endif

                <!-- Pagination -->
                @if ($kunjungan->hasPages())
                    <div class="text-center mt-4">
                    {{ $kunjungan->appends(request()->query())->links() }}
                </div>
            @endif
        </div>        <!-- FAB Button -->
        <div class="fab-button animate bottom-right" style="margin-bottom:80px; position: fixed; bottom: 0; right: 20px; z-index: 99;">
            <a href="{{ route('kunjungan.create') }}" class="fab bg-primary">
                <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- Custom Detail Modal -->
    <div class="custom-modal-overlay" id="detailModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Detail Kunjungan</h5>

            </div>
            <div class="custom-modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal & Waktu</label>
                            <p id="modalDate" class="mb-0"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Kunjungan</label>
                            <div id="modalPhoto" class="text-center">
                                <p class="text-muted">Tidak ada foto</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi</label>
                            <p id="modalLocation" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Kunjungan</label>
                    <p id="modalDescription" class="mb-0"></p>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="custom-btn custom-btn-secondary" id="closeModalBtn">Tutup</button>
            </div>
        </div>
    </div>

@endsection

@push('myscript')
    <script>
        $(function() {
            // Dark Mode Toggle
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const body = document.body;

            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                body.classList.add('dark-mode');
                themeIcon.setAttribute('name', 'sunny-outline');
            }

            themeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-mode');
                const isDark = body.classList.contains('dark-mode');
                themeIcon.setAttribute('name', isDark ? 'sunny-outline' : 'moon-outline');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });

            var lang = {
                title: 'Pilih Tanggal',
                cancel: 'Batal',
                confirm: 'Set',
                year: '',
                month: '',
                day: '',
                hour: '',
                min: '',
                sec: ''
            };
            new Rolldate({
                el: '#datePicker',
                format: 'YYYY-MM-DD',
                beginYear: 2000,
                endYear: 2100,
                lang: lang,
                confirm: function(date) {

                }
            });

            new Rolldate({
                el: '#datePicker2',
                format: 'YYYY-MM-DD',
                beginYear: 2000,
                endYear: 2100,
                lang: lang,
                confirm: function(date) {

                }
            });

            function showDetailModal(id, deskripsi, tanggal, waktu, lokasi, foto) {
                // Set data ke modal
                document.getElementById('modalDate').textContent = tanggal + ' - ' + waktu;
                document.getElementById('modalDescription').textContent = deskripsi;

                // Set lokasi
                const modalLocation = document.getElementById('modalLocation');

                if (lokasi && lokasi !== '') {
                    modalLocation.textContent = lokasi;
                } else {
                    modalLocation.textContent = '-';
                }

                // Set foto
                const modalPhoto = document.getElementById('modalPhoto');
                if (foto && foto !== '') {
                    modalPhoto.innerHTML = '<img src="' + '{{ asset('storage/uploads/kunjungan/') }}/' + foto +
                        '" alt="Foto Kunjungan" class="img-fluid">';
                } else {
                    modalPhoto.innerHTML = '<p class="text-muted">Tidak ada foto</p>';
                }

                // Show custom modal
                const modal = document.getElementById('detailModal');
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
            }

            // Make function global
            window.showDetailModal = showDetailModal;

            function closeDetailModal() {
                const modal = document.getElementById('detailModal');

                // Hide custom modal
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }

            // Make function global
            window.closeDetailModal = closeDetailModal;

            // Event listener untuk button close
            $(document).ready(function() {
                // Event listener untuk button Tutup
                $(document).on('click', '#closeModalBtn', function() {
                    closeDetailModal();
                });

                // Event listener untuk button X
                $(document).on('click', '#closeModalHeaderBtn', function() {
                    closeDetailModal();
                });

                // Event listener untuk backdrop
                $(document).on('click', '#detailModal', function(e) {
                    if (e.target === this) {
                        closeDetailModal();
                    }
                });
            });

            $('.delete-confirm').click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus kunjungan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
