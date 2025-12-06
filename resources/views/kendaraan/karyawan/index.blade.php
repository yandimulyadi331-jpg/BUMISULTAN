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

        /* ========== FILTER SECTION ========== */
        .filter-section {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin: 0 20px 20px 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
        }

        .filter-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-group:last-child {
            margin-bottom: 0;
        }

        .filter-input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 15px;
            border: 2px solid var(--border-color);
            background: var(--bg-primary);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            color: var(--text-primary);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--color-1);
        }

        .filter-button {
            width: 100%;
            padding: 12px 20px;
            border-radius: 15px;
            border: none;
            background: var(--gradient-1);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .filter-button:active {
            box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                       inset -4px -4px 8px rgba(255,255,255,0.1);
        }

        /* ========== SUMMARY STATS ========== */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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

        .stat-card:nth-child(4) .stat-icon {
            background: var(--gradient-4);
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

        /* ========== KENDARAAN GRID ========== */
        .kendaraan-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .kendaraan-card-wrapper {
            position: relative;
        }

        .kendaraan-card {
            background: var(--bg-primary);
            border-radius: 25px;
            padding: 18px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .kendaraan-card.variant-1 { --accent-color: var(--color-1); --accent-gradient: var(--gradient-1); }
        .kendaraan-card.variant-2 { --accent-color: var(--color-2); --accent-gradient: var(--gradient-2); }
        .kendaraan-card.variant-3 { --accent-color: var(--color-3); --accent-gradient: var(--gradient-3); }
        .kendaraan-card.variant-4 { --accent-color: var(--color-4); --accent-gradient: var(--gradient-4); }

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
            margin: 0 0 4px 0;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .card-code {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-family: 'Courier New', monospace;
            font-weight: 700;
            margin: 0;
        }

        .card-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .card-badge.variant-1 { background: var(--gradient-1); }
        .card-badge.variant-2 { background: var(--gradient-2); }
        .card-badge.variant-3 { background: var(--gradient-3); }
        .card-badge.variant-4 { background: var(--gradient-4); }

        /* Photo Section */
        .card-photo-section {
            width: 100%;
            height: 150px;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 2px 2px 8px var(--shadow-dark),
                       inset -2px -2px 8px var(--shadow-light);
        }

        .card-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .card-photo-placeholder.variant-1 { background: var(--gradient-1); }
        .card-photo-placeholder.variant-2 { background: var(--gradient-2); }
        .card-photo-placeholder.variant-3 { background: var(--gradient-3); }
        .card-photo-placeholder.variant-4 { background: var(--gradient-4); }

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
            border-radius: 15px;
            box-shadow: inset 2px 2px 5px var(--shadow-dark),
                       inset -2px -2px 5px var(--shadow-light);
        }

        .stat-icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-icon-wrapper.variant-1 { background: var(--gradient-1); }
        .stat-icon-wrapper.variant-2 { background: var(--gradient-2); }
        .stat-icon-wrapper.variant-3 { background: var(--gradient-3); }
        .stat-icon-wrapper.variant-4 { background: var(--gradient-4); }

        .stat-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 0.9rem;
            font-weight: 900;
            color: var(--text-primary);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Card Footer */
        .card-footer {
            margin-top: auto;
        }

        .btn-view-detail {
            width: 100%;
            padding: 14px;
            border-radius: 18px;
            border: none;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: white;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .btn-view-detail.variant-1 { background: var(--gradient-1); }
        .btn-view-detail.variant-2 { background: var(--gradient-2); }
        .btn-view-detail.variant-3 { background: var(--gradient-3); }
        .btn-view-detail.variant-4 { background: var(--gradient-4); }

        .btn-view-detail:active {
            box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                       inset -4px -4px 8px rgba(255,255,255,0.1);
        }

        .btn-view-detail ion-icon {
            font-size: 20px;
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

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .kendaraan-grid {
                grid-template-columns: 1fr;
            }
            
            .summary-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .card-title {
                font-size: 1rem;
            }

            .card-photo-section {
                height: 140px;
            }

            .btn-view-detail {
                font-size: 0.85rem;
                padding: 12px;
            }
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="/dashboard" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Manajemen Kendaraan</h3>
            <p>Pilih kendaraan untuk melihat detail & melakukan aksi</p>
        </div>
    </div>

    <div id="content-section">
        {{-- Filter Section --}}
        <div class="filter-section">
            <div class="filter-title">
                <ion-icon name="filter" style="font-size: 1rem; vertical-align: middle;"></ion-icon>
                Filter & Pencarian
            </div>
            <form action="{{ route('kendaraan.karyawan.index') }}" method="GET">
                <div class="filter-group">
                    <input type="text" name="nama_kendaraan" class="filter-input" 
                           placeholder="🔍 Cari nama kendaraan..." 
                           value="{{ Request('nama_kendaraan') }}">
                </div>
                <div class="filter-group">
                    <select name="jenis_kendaraan" class="filter-input">
                        <option value="">🚗 Semua Jenis</option>
                        <option value="Mobil" {{ Request('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="Motor" {{ Request('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor</option>
                        <option value="Truk" {{ Request('jenis_kendaraan') == 'Truk' ? 'selected' : '' }}>Truk</option>
                        <option value="Bus" {{ Request('jenis_kendaraan') == 'Bus' ? 'selected' : '' }}>Bus</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select name="status" class="filter-input">
                        <option value="">📊 Semua Status</option>
                        <option value="tersedia" {{ Request('status') == 'tersedia' ? 'selected' : '' }}>✅ Tersedia</option>
                        <option value="keluar" {{ Request('status') == 'keluar' ? 'selected' : '' }}>🚀 Keluar</option>
                        <option value="dipinjam" {{ Request('status') == 'dipinjam' ? 'selected' : '' }}>📦 Dipinjam</option>
                        <option value="service" {{ Request('status') == 'service' ? 'selected' : '' }}>🔧 Service</option>
                    </select>
                </div>
                <button type="submit" class="filter-button">
                    <ion-icon name="search"></ion-icon>
                    Cari Kendaraan
                </button>
            </form>
        </div>

        @if($kendaraan->count() > 0)
            {{-- Summary Stats --}}
            <div class="summary-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <ion-icon name="car"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Total</div>
                        <div class="stat-value">{{ $kendaraan->total() }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <ion-icon name="checkmark-circle"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Tersedia</div>
                        <div class="stat-value">{{ $kendaraan->where('status', 'tersedia')->count() }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <ion-icon name="log-out"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Keluar</div>
                        <div class="stat-value">{{ $kendaraan->where('status', 'keluar')->count() }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <ion-icon name="build"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Service</div>
                        <div class="stat-value">{{ $kendaraan->where('status', 'service')->count() }}</div>
                    </div>
                </div>
            </div>

            {{-- Kendaraan Grid --}}
            <div class="kendaraan-grid">
                @php
                    $variants = ['variant-1', 'variant-2', 'variant-3', 'variant-4'];
                    $statusLabels = [
                        'tersedia' => 'TERSEDIA',
                        'keluar' => 'KELUAR',
                        'dipinjam' => 'DIPINJAM',
                        'service' => 'SERVICE'
                    ];
                @endphp
                @foreach ($kendaraan as $index => $d)
                    @php
                        $variantClass = $variants[$index % 4];
                        $statusLabel = $statusLabels[$d->status] ?? strtoupper($d->status);
                    @endphp
                    
                    <div class="kendaraan-card-wrapper">
                        {{-- Kendaraan Card --}}
                        <div class="kendaraan-card {{ $variantClass }}">
                            {{-- Card Header --}}
                            <div class="card-header">
                                <div class="card-title-section">
                                    <h4 class="card-title">{{ strtoupper($d->nama_kendaraan) }}</h4>
                                    <p class="card-code">{{ $d->kode_kendaraan }}</p>
                                </div>
                                <span class="card-badge {{ $variantClass }}">{{ $statusLabel }}</span>
                            </div>

                            {{-- Photo Section --}}
                            <div class="card-photo-section">
                                @if($d->foto)
                                    <img src="{{ asset('storage/kendaraan/' . $d->foto) }}" 
                                         alt="{{ $d->nama_kendaraan }}"
                                         class="card-photo">
                                @else
                                    <div class="card-photo-placeholder {{ $variantClass }}">
                                        <ion-icon name="car-outline"></ion-icon>
                                    </div>
                                @endif
                            </div>

                            {{-- Stats Section --}}
                            <div class="card-stats-section">
                                <div class="stat-item">
                                    <div class="stat-icon-wrapper {{ $variantClass }}">
                                        <ion-icon name="pricetag"></ion-icon>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Jenis</span>
                                        <span class="stat-value">{{ $d->jenis_kendaraan }}</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon-wrapper {{ $variantClass }}">
                                        <ion-icon name="reader"></ion-icon>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">No. Polisi</span>
                                        <span class="stat-value">{{ $d->no_polisi }}</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon-wrapper {{ $variantClass }}">
                                        <ion-icon name="car-sport"></ion-icon>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Merk/Model</span>
                                        <span class="stat-value">{{ $d->merk ?? '-' }}{{ $d->model ? ' / '.$d->model : '' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Button --}}
                            <div class="card-footer">
                                <a href="{{ route('kendaraan.karyawan.detail', Crypt::encrypt($d->id)) }}?tab=aktivitas" 
                                   class="btn-view-detail {{ $variantClass }}">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    <span>Lihat Detail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div style="padding: 0 20px;">
                {{ $kendaraan->links() }}
            </div>
        @else
            <div class="empty-state">
                <ion-icon name="car-outline"></ion-icon>
                <h4>Tidak Ada Kendaraan</h4>
                <p>Belum ada data kendaraan yang tersedia</p>
            </div>
        @endif
    </div>
    <script>
        // Dark mode toggle support
        document.addEventListener('DOMContentLoaded', function() {
            // Check if dark mode is enabled from localStorage or system preference
            const isDarkMode = localStorage.getItem('darkMode') === 'true' || 
                             (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDarkMode) {
                document.body.classList.add('dark-mode');
            }
        });
    </script>
@endsection
