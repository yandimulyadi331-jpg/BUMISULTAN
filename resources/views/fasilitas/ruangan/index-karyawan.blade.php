@extends('layouts.mobile.app')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg-primary);
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

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        /* ========== HEADER ========== */
        #header-section {
            background: var(--bg-primary);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
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
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .back-btn:hover {
            transform: translateY(-2px);
        }

        .back-btn:active {
            transform: translateY(0);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
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
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.5px;
            margin: 0;
        }
        
        .header-title p {
            font-size: 0.75rem;
            color: var(--text-primary);
            font-weight: 500;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        /* ========== CONTENT ========== */
        #content-section {
            padding: 20px;
            padding-bottom: 100px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ========== SEARCH & FILTER SECTION ========== */
        .search-filter-section {
            background: var(--bg-primary);
            padding: 0 0 20px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-box {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box ion-icon {
            position: absolute;
            left: 15px;
            font-size: 20px;
            color: var(--icon-color);
            pointer-events: none;
            z-index: 1;
        }

        .search-box input {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: none;
            background: var(--bg-primary);
            border-radius: 15px;
            font-size: 0.9rem;
            color: var(--text-primary);
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
        }

        .search-box input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .search-box input:focus {
            box-shadow: inset 8px 8px 16px var(--shadow-dark),
                       inset -8px -8px 16px var(--shadow-light);
        }

        .sort-dropdown {
            position: relative;
            min-width: 130px;
        }

        .sort-dropdown select {
            width: 100%;
            padding: 14px 40px 14px 15px;
            border: none;
            background: var(--bg-primary);
            border-radius: 15px;
            font-size: 0.85rem;
            color: var(--text-primary);
            font-weight: 600;
            outline: none;
            cursor: pointer;
            appearance: none;
            transition: all 0.3s ease;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .sort-dropdown select:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .sort-dropdown ion-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--icon-color);
            pointer-events: none;
        }

        /* ========== TABLE CONTAINER ========== */
        .table-container {
            padding: 0;
        }

        .table-header {
            display: grid;
            grid-template-columns: 100px 1fr 80px 100px 80px 90px 90px;
            gap: 10px;
            padding: 15px 20px;
            background: var(--bg-primary);
            border-radius: 16px;
            margin-bottom: 15px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .th {
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }

        .table-body {
            padding: 0;
        }

        .table-row {
            display: grid;
            grid-template-columns: 100px 1fr 80px 100px 80px 90px 90px;
            gap: 10px;
            padding: 18px 20px;
            margin-bottom: 15px;
            border-radius: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: var(--bg-primary);
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .table-row:hover {
            transform: translateY(-4px);
            box-shadow: 12px 12px 24px var(--shadow-dark),
                       -12px -12px 24px var(--shadow-light);
        }

        .table-row:active {
            transform: translateY(0) scale(0.98);
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
        }

        .td {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
        }

        /* NIS Column */
        .td-nis {
            justify-content: flex-start;
        }

        .nis-code {
            font-family: 'Courier New', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-secondary);
            background: var(--bg-primary);
            padding: 6px 10px;
            border-radius: 8px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        /* Nama Column */
        .td-nama {
            gap: 12px;
            justify-content: flex-start;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
            color: white;
            flex-shrink: 0;
            box-shadow: 4px 4px 8px rgba(0,0,0,0.2),
                       -2px -2px 6px rgba(255,255,255,0.1);
            position: relative;
        }

        .nama-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nama-santri {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .nis-santri {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        /* Amount Columns */
        .td-saldo,
        .td-total,
        .td-pengeluaran {
            justify-content: flex-end;
        }

        .amount-saldo {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .amount-positive {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--badge-green);
        }

        .amount-negative {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--badge-red);
        }

        /* Status Column */
        .td-status {
            justify-content: center;
        }

        .status-badge {
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .status-aktif {
            background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
            color: white;
        }

        /* Actions Column */
        .td-actions {
            justify-content: center;
        }

        .btn-lihat {
            padding: 10px 18px;
            background: var(--bg-primary);
            color: var(--text-primary);
            border: none;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .btn-lihat:hover {
            transform: translateY(-2px);
        }

        .btn-lihat:active {
            transform: translateY(0);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: var(--bg-primary);
            border-radius: 25px;
            box-shadow: inset 8px 8px 16px var(--shadow-dark),
                       inset -8px -8px 16px var(--shadow-light);
        }

        .empty-state ion-icon {
            font-size: 100px;
            margin-bottom: 20px;
            opacity: 0.3;
            color: var(--icon-color);
        }

        .empty-state h4 {
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 12px;
            color: var(--text-primary);
        }

        .empty-state p {
            opacity: 0.8;
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        /* ========== LOADING SPINNER ========== */
        .loading-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top-color: #14b8a6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ========== SWEET ALERT CUSTOM STYLES ========== */
        .swal-neomorphic {
            border-radius: 20px !important;
            padding: 32px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
            border: 2px solid rgba(38, 166, 154, 0.2) !important;
        }

        .swal2-title {
            font-size: 1.8rem !important;
            font-weight: 900 !important;
            letter-spacing: -0.5px !important;
            margin-bottom: 24px !important;
            color: #263238 !important;
        }

        .swal2-close {
            width: 40px !important;
            height: 40px !important;
            border-radius: 10px !important;
            background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%) !important;
            transition: all 0.3s ease !important;
            color: #546e7a !important;
            font-size: 24px !important;
            box-shadow: 0 2px 8px rgba(0, 150, 136, 0.1) !important;
        }

        .swal2-close:hover {
            transform: translateY(-2px) rotate(90deg) !important;
            box-shadow: 0 4px 12px rgba(0, 150, 136, 0.2) !important;
        }

        /* Modal Image */
        .modal-image-container {
            margin-bottom: 24px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .modal-image {
            width: 100%;
            height: auto;
            display: block;
        }
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light),
                       0 6px 20px rgba(0,0,0,0.25) !important;
            color: #ef4444 !important;
        }

        .swal2-close:active {
            transform: translateY(0) scale(0.92) rotate(90deg) !important;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light) !important;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Modal Image Styling */
        .modal-image-container {
            text-align: center;
            margin-bottom: 28px;
            position: relative;
            padding: 8px;
        }

        .modal-image {
            max-width: 100%;
            max-height: 240px;
            border-radius: 20px;
            object-fit: cover;
            box-shadow: 0 16px 48px rgba(0,0,0,0.4),
                       0 8px 24px rgba(0,0,0,0.3),
                       inset 0 2px 1px rgba(255,255,255,0.15),
                       inset 0 -2px 1px rgba(0,0,0,0.1);
            border: 3px solid var(--border-color);
            transform: translateZ(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-image:hover {
            transform: translateZ(30px) scale(1.03);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5),
                       0 10px 30px rgba(0,0,0,0.4);
        }

        /* Modal Info Card */
        .modal-info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f8f6 100%);
            padding: 24px;
            border-radius: 16px;
            border: 2px solid rgba(38, 166, 154, 0.2);
            box-shadow: 0 4px 15px rgba(0, 150, 136, 0.1);
        }

        .modal-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 12px;
            border-bottom: 1px solid rgba(38, 166, 154, 0.1);
            transition: all 0.3s ease;
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .modal-info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .modal-info-row:hover {
            background: rgba(38, 166, 154, 0.05);
            transform: translateX(4px);
        }

        .modal-label {
            font-size: 0.95rem;
            color: #546e7a;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-label ion-icon {
            font-size: 22px;
            color: #26a69a;
        }

        .modal-value {
            font-size: 1.1rem;
            color: #263238;
            font-weight: 900;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-badge {
            background: linear-gradient(135deg, #26a69a 0%, #4db6ac 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 14px;
            font-size: 0.9rem;
            font-weight: 900;
            box-shadow: 0 2px 10px rgba(38, 166, 154, 0.3);
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .modal-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(38, 166, 154, 0.4);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .table-header {
                grid-template-columns: 80px 1fr 60px 80px 70px 80px 80px;
                padding: 12px 15px;
                font-size: 0.65rem;
            }

            .table-row {
                grid-template-columns: 80px 1fr 60px 80px 70px 80px 80px;
                padding: 12px 15px;
            }

            .avatar {
                width: 35px;
                height: 35px;
                font-size: 0.7rem;
            }

            .nama-santri {
                font-size: 0.85rem;
            }

            .nis-santri {
                font-size: 0.65rem;
            }

            .search-filter-section {
                padding: 15px;
                gap: 10px;
            }

            .sort-dropdown {
                min-width: 100px;
            }
        }

        @media (max-width: 480px) {
            #header-section {
                padding: 15px;
            }

            .header-title h3 {
                font-size: 1.2rem;
            }

            .header-title p {
                font-size: 0.75rem;
            }

            .table-header {
                grid-template-columns: 70px 1fr 50px 70px 60px 70px 70px;
                padding: 10px 12px;
                gap: 8px;
                top: 135px;
            }

            .table-row {
                grid-template-columns: 70px 1fr 50px 70px 60px 70px 70px;
                padding: 10px 12px;
                gap: 8px;
            }

            .th {
                font-size: 0.6rem;
            }

            .td {
                font-size: 0.75rem;
            }

            .avatar {
                width: 32px;
                height: 32px;
                font-size: 0.65rem;
            }

            .nama-santri {
                font-size: 0.8rem;
            }

            .nis-santri {
                font-size: 0.6rem;
            }

            .nis-code {
                font-size: 0.65rem;
                padding: 3px 6px;
            }

            .amount-positive,
            .amount-negative {
                font-size: 0.8rem;
            }

            .status-badge {
                padding: 5px 10px;
                font-size: 0.6rem;
            }

            .btn-lihat {
                padding: 6px 12px;
                font-size: 0.7rem;
            }

            .search-filter-section {
                padding: 12px;
                top: 75px;
            }

            .search-box input {
                padding: 10px 10px 10px 40px;
                font-size: 0.85rem;
            }

            .sort-dropdown {
                min-width: 90px;
            }

            .sort-dropdown select {
                padding: 10px 30px 10px 12px;
                font-size: 0.8rem;
            }
        }
    </style>

    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('gedung.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Daftar Ruangan</h3>
                <p>{{ $gedung->nama_gedung }}</p>
            </div>
        </div>
    </div>

    <div id="content-section">
        @if($ruangan->count() > 0)
            {{-- Search & Filter Section --}}
            <div class="search-filter-section">
                <div class="search-box">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" id="searchInput" placeholder="Search documents" onkeyup="filterTable()">
                </div>
                <div class="sort-dropdown">
                    <select id="sortSelect" onchange="sortTable()">
                        <option value="name-asc">Aktif</option>
                        <option value="name-desc">Nama Z-A</option>
                        <option value="capacity-desc">Kapasitas Tinggi</option>
                        <option value="capacity-asc">Kapasitas Rendah</option>
                    </select>
                    <ion-icon name="chevron-down-outline"></ion-icon>
                </div>
            </div>

            {{-- Table Header --}}
            <div class="table-container">
                <div class="table-header">
                    <div class="th th-nis">KODE</div>
                    <div class="th th-nama">NAMA RUANGAN</div>
                    <div class="th th-saldo">LANTAI</div>
                    <div class="th th-total">KAPASITAS</div>
                    <div class="th th-pengeluaran">BARANG</div>
                    <div class="th th-status">STATUS</div>
                    <div class="th th-actions">ACTIONS</div>
                </div>

                {{-- Table Body --}}
                <div class="table-body" id="tableBody">
                    @php
                        $avatarColors = ['#FF9A76', '#9B88FA', '#4ECDC4', '#FFD93D', '#6BCF7F'];
                    @endphp
                    @foreach ($ruangan as $index => $d)
                        @php
                            $avatarColor = $avatarColors[$index % 5];
                            $initial = strtoupper(substr($d->nama_ruangan, 0, 1));
                            if (preg_match('/\s(\w)/', $d->nama_ruangan, $matches)) {
                                $initial .= strtoupper($matches[1]);
                            }
                        @endphp
                        <div class="table-row" 
                             data-name="{{ strtolower($d->nama_ruangan) }}" 
                             data-code="{{ strtolower($d->kode_ruangan) }}"
                             data-capacity="{{ $d->kapasitas ?? 0 }}"
                             data-barang="{{ $d->total_barang ?? 0 }}">
                            <div class="td td-nis">
                                <span class="nis-code">{{ $d->kode_ruangan }}</span>
                            </div>
                            <div class="td td-nama">
                                <div class="avatar" style="background: {{ $avatarColor }};">
                                    {{ $initial }}
                                </div>
                                <div class="nama-info">
                                    <div class="nama-santri">{{ $d->nama_ruangan }}</div>
                                    <div class="nis-santri">{{ $d->kode_ruangan }}</div>
                                </div>
                            </div>
                            <div class="td td-saldo">
                                <span class="amount-saldo">{{ $d->lantai ?? '-' }}</span>
                            </div>
                            <div class="td td-total">
                                <span class="amount-positive">{{ $d->kapasitas ?? 0 }}</span>
                            </div>
                            <div class="td td-pengeluaran">
                                <span class="amount-negative">{{ $d->total_barang ?? 0 }}</span>
                            </div>
                            <div class="td td-status">
                                <span class="status-badge status-aktif">Aktif</span>
                            </div>
                            <div class="td td-actions">
                                <button class="btn-lihat" onclick="viewRuanganDetail('{{ $d->nama_ruangan }}', '{{ $d->kode_ruangan }}', {{ $d->kapasitas ?? 0 }}, {{ $d->total_barang ?? 0 }}, '{{ $d->lantai ?? '' }}', '{{ $d->foto ? asset('storage/uploads/ruangan/'.$d->foto) : '' }}')">
                                    Lihat
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state">
                <ion-icon name="business-outline"></ion-icon>
                <h4>Tidak Ada Ruangan</h4>
                <p>Belum ada data ruangan yang tersedia di gedung ini</p>
            </div>
        @endif
    </div>

    <script>
        // Filter Table
        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.table-row');
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const code = row.getAttribute('data-code');
                
                if (name.includes(searchValue) || code.includes(searchValue)) {
                    row.style.display = 'grid';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Sort Table
        function sortTable() {
            const sortValue = document.getElementById('sortSelect').value;
            const tableBody = document.getElementById('tableBody');
            const rows = Array.from(document.querySelectorAll('.table-row'));
            
            rows.sort((a, b) => {
                switch(sortValue) {
                    case 'name-asc':
                        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                    case 'name-desc':
                        return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                    case 'capacity-desc':
                        return parseInt(b.getAttribute('data-capacity')) - parseInt(a.getAttribute('data-capacity'));
                    case 'capacity-asc':
                        return parseInt(a.getAttribute('data-capacity')) - parseInt(b.getAttribute('data-capacity'));
                    default:
                        return 0;
                }
            });
            
            // Re-append sorted rows
            rows.forEach(row => tableBody.appendChild(row));
        }

        // View Ruangan Detail Modal
        function viewRuanganDetail(roomName, roomCode, kapasitas, totalBarang, lantai, foto) {
            let fotoHtml = '';
            if (foto) {
                fotoHtml = `
                    <div class="modal-image-container">
                        <img src="${foto}" alt="${roomName}" class="modal-image">
                    </div>
                `;
            }

            // ID Badge dengan styling khusus
            const idBadge = `
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="display: inline-block; background: linear-gradient(135deg, #26a69a 0%, #4db6ac 100%); padding: 8px 24px; border-radius: 20px; box-shadow: 0 4px 15px rgba(38, 166, 154, 0.3);">
                        <span style="color: white; font-size: 0.85rem; font-weight: 900; letter-spacing: 2px;">${roomCode}</span>
                    </div>
                </div>
            `;

            const infoHtml = `
                ${fotoHtml}
                ${idBadge}
                <div class="modal-info-card">
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <ion-icon name="qr-code-outline"></ion-icon>
                            Kode Ruangan
                        </span>
                        <span class="modal-value">
                            <span class="modal-badge">${roomCode}</span>
                        </span>
                    </div>
                    ${lantai ? `
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <ion-icon name="layers-outline"></ion-icon>
                            Lantai
                        </span>
                        <span class="modal-value" style="font-size: 1.2rem;">
                            ${lantai}
                        </span>
                    </div>
                    ` : ''}
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <ion-icon name="people-outline"></ion-icon>
                            Kapasitas
                        </span>
                        <span class="modal-value" style="color: #26a69a; font-size: 1.3rem;">
                            ${kapasitas} <span style="font-size: 0.8rem; opacity: 0.9; font-weight: 700;">orang</span>
                        </span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <ion-icon name="cube-outline"></ion-icon>
                            Total Barang
                        </span>
                        <span class="modal-value" style="color: #ff9800; font-size: 1.3rem;">
                            ${totalBarang} <span style="font-size: 0.8rem; opacity: 0.9; font-weight: 700;">item</span>
                        </span>
                    </div>
                </div>
            `;

            Swal.fire({
                title: roomName,
                html: infoHtml,
                showCloseButton: true,
                showConfirmButton: false,
                width: '500px',
                background: '#ffffff',
                color: '#263238',
                customClass: {
                    popup: 'swal-neomorphic'
                },
                didOpen: () => {
                    // Add entrance animation
                    const popup = document.querySelector('.swal-neomorphic');
                    popup.style.animation = 'modalEntrance 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                }
            });
        }

        // Add keyframe animation for modal entrance
        const style = document.createElement('style');
        style.textContent = `
            @keyframes modalEntrance {
                from {
                    opacity: 0;
                    transform: scale(0.9) translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
