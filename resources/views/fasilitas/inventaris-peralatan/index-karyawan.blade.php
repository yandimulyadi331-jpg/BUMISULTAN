@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            /* Minimal Elegant Colors */
            --bg-body-light: #ecf0f3;
            --bg-primary-light: #ecf0f3;
            --shadow-dark-light: #d1d9e6;
            --shadow-light-light: #ffffff;
            --text-primary-light: #2c3e50;
            --text-secondary-light: #6c7a89;
            --border-light: rgba(0, 0, 0, 0.05);

            --bg-body-dark: #1a202c;
            --bg-primary-dark: #2d3748;
            --shadow-dark-dark: #141923;
            --shadow-light-dark: #3a4555;
            --text-primary-dark: #f7fafc;
            --text-secondary-dark: #a0aec0;
            --border-dark: rgba(255, 255, 255, 0.08);

            /* Accent Colors */
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

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg-body);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .item-card,
        .stat-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        /* ========== HEADER ========== */
        #header-section {
            background: var(--bg-primary);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .back-btn {
            background: var(--bg-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .back-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .back-btn ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        .header-title {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header-title h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .header-title p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin-top: 5px;
            letter-spacing: 0.3px;
        }

        /* ========== CONTENT ========== */
        #content-section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        /* ========== SUMMARY STATS ========== */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 28px;
            color: white;
            box-shadow: 4px 4px 12px rgba(0,0,0,0.2),
                       -2px -2px 8px rgba(255,255,255,0.05);
        }

        .stat-icon.variant-1 { background: var(--gradient-1); }
        .stat-icon.variant-2 { background: var(--gradient-2); }
        .stat-icon.variant-3 { background: var(--gradient-3); }
        .stat-icon.variant-4 { background: var(--gradient-4); }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-bottom: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 900;
            color: var(--text-primary);
            line-height: 1;
        }

        /* ========== TABS ========== */
        .tabs-container {
            margin-bottom: 25px;
        }

        .tabs-wrapper {
            display: flex;
            gap: 10px;
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 8px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .tab-btn {
            flex: 1;
            background: transparent;
            border: none;
            padding: 15px 20px;
            border-radius: 15px;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tab-btn.active {
            background: var(--bg-primary);
            color: var(--text-primary);
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ========== FILTER SECTION ========== */
        .filter-section {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .filter-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-row {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }

        .filter-input {
            flex: 1;
            background: var(--bg-primary);
            border: none;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .filter-input::placeholder {
            color: var(--text-secondary);
        }

        .filter-input:focus {
            outline: none;
        }

        .filter-btn {
            background: var(--bg-primary);
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        /* ========== ITEM GRID ========== */
        .items-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .item-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 18px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .item-card:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .item-name {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.3;
            flex: 1;
        }

        .item-badge {
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 10px;
        }

        .badge-tersedia {
            background: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
            color: white;
        }

        .badge-dipinjam {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
        }

        .badge-rusak {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .badge-baik {
            background: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
            color: white;
        }

        .item-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 0.9rem;
            color: var(--text-primary);
            font-weight: 800;
        }

        /* ========== PAGINATION ========== */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            gap: 10px;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            background: var(--bg-primary);
            border: none;
            padding: 10px 15px;
            border-radius: 10px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            color: var(--text-primary);
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination .page-link:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 60px;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .empty-text {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 576px) {
            .summary-stats {
                grid-template-columns: 1fr;
            }

            .filter-row {
                flex-direction: column;
            }

            .item-info {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Inventaris & Peralatan</h3>
                <p>BUMI SULTAN</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="content-section">
        <!-- Summary Stats -->
        <div class="summary-stats">
            <div class="stat-card">
                <div class="stat-icon variant-1">
                    <ion-icon name="cube-outline"></ion-icon>
                </div>
                <div class="stat-label">Total Inventaris</div>
                <div class="stat-value">{{ $totalInventaris }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon variant-2">
                    <ion-icon name="construct-outline"></ion-icon>
                </div>
                <div class="stat-label">Total Peralatan</div>
                <div class="stat-value">{{ $totalPeralatan }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon variant-3">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
                <div class="stat-label">Inventaris Tersedia</div>
                <div class="stat-value">{{ $inventarisTersedia }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon variant-4">
                    <ion-icon name="shield-checkmark-outline"></ion-icon>
                </div>
                <div class="stat-label">Peralatan Baik</div>
                <div class="stat-value">{{ $peralatanBaik }}</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs-wrapper">
                <button class="tab-btn active" data-tab="inventaris">
                    <ion-icon name="cube-outline"></ion-icon> Inventaris
                </button>
                <button class="tab-btn" data-tab="peralatan">
                    <ion-icon name="construct-outline"></ion-icon> Peralatan
                </button>
            </div>
        </div>

        <!-- TAB INVENTARIS -->
        <div class="tab-content active" id="tab-inventaris">
            <!-- Filter Inventaris -->
            <div class="filter-section">
                <div class="filter-title">🔍 Filter Inventaris</div>
                <form method="GET" action="{{ route('inventaris-peralatan.karyawan.index') }}">
                    <input type="hidden" name="active_tab" value="inventaris">
                    
                    <div class="filter-row">
                        <input type="text" name="search_inventaris" class="filter-input" 
                               placeholder="Cari nama / kode inventaris..." 
                               value="{{ Request('search_inventaris') }}">
                    </div>

                    <div class="filter-row">
                        <select name="kategori_inventaris" class="filter-input">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriInventaris as $kat)
                                <option value="{{ $kat }}" {{ Request('kategori_inventaris') == $kat ? 'selected' : '' }}>
                                    {{ ucfirst($kat) }}
                                </option>
                            @endforeach
                        </select>

                        <select name="status_inventaris" class="filter-input">
                            <option value="">Semua Status</option>
                            @foreach($statusInventaris as $stat)
                                <option value="{{ $stat }}" {{ Request('status_inventaris') == $stat ? 'selected' : '' }}>
                                    {{ ucfirst($stat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-row">
                        <button type="submit" class="filter-btn">
                            <ion-icon name="search-outline"></ion-icon> Cari
                        </button>
                        <a href="{{ route('inventaris-peralatan.karyawan.index') }}?active_tab=inventaris" class="filter-btn">
                            <ion-icon name="refresh-outline"></ion-icon> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- List Inventaris -->
            <div class="items-grid">
                @forelse($inventaris as $item)
                    <div class="item-card" onclick="viewInventarisDetail({{ $item->id }}, '{{ $item->nama_barang }}', '{{ $item->kode_inventaris }}', '{{ $item->kategori }}', {{ $item->jumlah }}, '{{ $item->status }}', '{{ $item->kondisi }}', '{{ $item->lokasi_penyimpanan ?? '-' }}', '{{ $item->foto ? Storage::url($item->foto) : '' }}')">
                        <div class="item-header">
                            <div class="item-name">{{ $item->nama_barang }}</div>
                            @if($item->status == 'tersedia')
                                <div class="item-badge badge-tersedia">Tersedia</div>
                            @elseif($item->status == 'dipinjam')
                                <div class="item-badge badge-dipinjam">Dipinjam</div>
                            @else
                                <div class="item-badge badge-rusak">{{ ucfirst($item->status) }}</div>
                            @endif
                        </div>

                        <div class="item-info">
                            <div class="info-item">
                                <div class="info-label">Kode</div>
                                <div class="info-value">{{ $item->kode_inventaris }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kategori</div>
                                <div class="info-value">{{ ucfirst($item->kategori) }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Jumlah</div>
                                <div class="info-value">{{ $item->jumlah }} {{ $item->satuan ?? 'unit' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kondisi</div>
                                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $item->kondisi)) }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <ion-icon name="file-tray-outline"></ion-icon>
                        </div>
                        <div class="empty-text">Tidak ada data inventaris</div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($inventaris->hasPages())
                <div class="pagination-wrapper">
                    {{ $inventaris->appends(Request::except('inventaris_page'))->links() }}
                </div>
            @endif
        </div>

        <!-- TAB PERALATAN -->
        <div class="tab-content" id="tab-peralatan">
            <!-- Filter Peralatan -->
            <div class="filter-section">
                <div class="filter-title">🔍 Filter Peralatan</div>
                <form method="GET" action="{{ route('inventaris-peralatan.karyawan.index') }}">
                    <input type="hidden" name="active_tab" value="peralatan">
                    
                    <div class="filter-row">
                        <input type="text" name="search_peralatan" class="filter-input" 
                               placeholder="Cari nama / kode peralatan..." 
                               value="{{ Request('search_peralatan') }}">
                    </div>

                    <div class="filter-row">
                        <select name="kategori_peralatan" class="filter-input">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriPeralatan as $kat)
                                <option value="{{ $kat }}" {{ Request('kategori_peralatan') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>

                        <select name="kondisi_peralatan" class="filter-input">
                            <option value="">Semua Kondisi</option>
                            @foreach($kondisiPeralatan as $kond)
                                <option value="{{ $kond }}" {{ Request('kondisi_peralatan') == $kond ? 'selected' : '' }}>
                                    {{ ucfirst($kond) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-row">
                        <button type="submit" class="filter-btn">
                            <ion-icon name="search-outline"></ion-icon> Cari
                        </button>
                        <a href="{{ route('inventaris-peralatan.karyawan.index') }}?active_tab=peralatan" class="filter-btn">
                            <ion-icon name="refresh-outline"></ion-icon> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- List Peralatan -->
            <div class="items-grid">
                @forelse($peralatan as $item)
                    <div class="item-card" onclick="viewPeralatanDetail({{ $item->id }}, '{{ $item->nama_peralatan }}', '{{ $item->kode_peralatan }}', '{{ $item->kategori }}', {{ $item->stok_awal }}, {{ $item->stok_tersedia }}, '{{ $item->kondisi }}', '{{ $item->lokasi_penyimpanan ?? '-' }}', '{{ $item->foto ? Storage::url($item->foto) : '' }}')">
                        <div class="item-header">
                            <div class="item-name">{{ $item->nama_peralatan }}</div>
                            @if($item->kondisi == 'baik')
                                <div class="item-badge badge-baik">Baik</div>
                            @else
                                <div class="item-badge badge-rusak">{{ ucfirst($item->kondisi) }}</div>
                            @endif
                        </div>

                        <div class="item-info">
                            <div class="info-item">
                                <div class="info-label">Kode</div>
                                <div class="info-value">{{ $item->kode_peralatan }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kategori</div>
                                <div class="info-value">{{ $item->kategori }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Stok Tersedia</div>
                                <div class="info-value">{{ $item->stok_tersedia }} {{ $item->satuan ?? 'pcs' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kondisi</div>
                                <div class="info-value">{{ ucfirst($item->kondisi) }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <ion-icon name="file-tray-outline"></ion-icon>
                        </div>
                        <div class="empty-text">Tidak ada data peralatan</div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($peralatan->hasPages())
                <div class="pagination-wrapper">
                    {{ $peralatan->appends(Request::except('peralatan_page'))->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                // Add active class to clicked tab
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById('tab-' + tabId).classList.add('active');
            });
        });

        // Check if there's an active tab from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('active_tab');
        if (activeTab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            const targetBtn = document.querySelector(`.tab-btn[data-tab="${activeTab}"]`);
            const targetContent = document.getElementById(`tab-${activeTab}`);
            
            if (targetBtn && targetContent) {
                targetBtn.classList.add('active');
                targetContent.classList.add('active');
            }
        }

        // View Inventaris Detail
        function viewInventarisDetail(id, nama, kode, kategori, jumlah, status, kondisi, lokasi, foto) {
            const isDarkMode = document.body.classList.contains('dark-mode');
            const bgColor = isDarkMode ? '#2d3748' : '#ecf0f3';
            const textColor = isDarkMode ? '#f7fafc' : '#2c3e50';
            const labelColor = isDarkMode ? '#a0aec0' : '#6c7a89';
            const borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)';

            let fotoHtml = '';
            if (foto) {
                fotoHtml = `<div style="text-align: center; margin-bottom: 20px;">
                    <img src="${foto}" alt="${nama}" style="max-width: 100%; max-height: 200px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </div>`;
            }

            Swal.fire({
                title: nama,
                html: `
                    ${fotoHtml}
                    <div style="background: ${bgColor}; padding: 15px; border-radius: 15px; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.1), inset -2px -2px 5px rgba(255,255,255,0.1);">
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kode</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kode}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kategori</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kategori}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Jumlah</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${jumlah} unit</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Status</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${status}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kondisi</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kondisi.replace('_', ' ')}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Lokasi</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${lokasi}</span>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '450px',
                background: bgColor,
                color: textColor
            });
        }

        // View Peralatan Detail
        function viewPeralatanDetail(id, nama, kode, kategori, stokAwal, stokTersedia, kondisi, lokasi, foto) {
            const isDarkMode = document.body.classList.contains('dark-mode');
            const bgColor = isDarkMode ? '#2d3748' : '#ecf0f3';
            const textColor = isDarkMode ? '#f7fafc' : '#2c3e50';
            const labelColor = isDarkMode ? '#a0aec0' : '#6c7a89';
            const borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)';

            let fotoHtml = '';
            if (foto) {
                fotoHtml = `<div style="text-align: center; margin-bottom: 20px;">
                    <img src="${foto}" alt="${nama}" style="max-width: 100%; max-height: 200px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </div>`;
            }

            Swal.fire({
                title: nama,
                html: `
                    ${fotoHtml}
                    <div style="background: ${bgColor}; padding: 15px; border-radius: 15px; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.1), inset -2px -2px 5px rgba(255,255,255,0.1);">
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kode</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kode}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kategori</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kategori}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Stok Awal</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${stokAwal}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Stok Tersedia</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${stokTersedia}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kondisi</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kondisi}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Lokasi</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${lokasi}</span>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '450px',
                background: bgColor,
                color: textColor
            });
        }
    </script>
@endsection
