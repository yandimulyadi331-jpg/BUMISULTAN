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

        /* ========== TABLE CONTAINER ========== */
        .table-container {
            padding: 0 20px 20px 20px;
        }

        .content-header {
            padding: 0 5px 15px 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0;
        }

        .content-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-title ion-icon {
            font-size: 1.4rem;
        }

        .item-count {
            color: var(--text-secondary);
            padding: 0;
            font-size: 0.9rem;
            font-weight: 700;
        }

        /* ========== TABLE ========== */
        .table-wrapper {
            background: var(--bg-primary);
            border-radius: 25px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            overflow: hidden;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: var(--bg-primary);
        }

        .data-table th {
            padding: 16px 12px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        .data-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        body.dark-mode .data-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .data-table tbody tr:last-child {
            border-bottom: none;
        }

        .data-table td {
            padding: 16px 12px;
            font-size: 0.9rem;
            color: var(--text-primary);
            vertical-align: middle;
        }

        /* Column Specific Styles */
        .col-name {
            font-weight: 700;
            color: var(--text-primary);
        }

        .col-code {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .col-category {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .col-kondisi {
            text-align: center;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .status-baik {
            background: transparent;
            color: #10b981;
            border: 1.5px solid #10b981;
        }

        .status-rusak-ringan {
            background: transparent;
            color: #f59e0b;
            border: 1.5px solid #f59e0b;
        }

        .status-rusak-berat {
            background: transparent;
            color: #ef4444;
            border: 1.5px solid #ef4444;
        }

        /* Action Badge */
        .action-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: auto;
            height: auto;
            border-radius: 0;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            background: transparent;
            font-size: 22px;
            padding: 0;
        }

        .action-badge.badge-view {
            color: #667eea;
        }

        .action-badge.badge-transfer {
            color: #10b981;
        }

        .action-badge.badge-history {
            color: #f97316;
        }

        .action-badge:hover {
            opacity: 0.7;
        }

        .action-badge:active {
            opacity: 0.5;
        }

        .action-group {
            display: flex;
            gap: 8px;
            justify-content: center;
            background: transparent !important;
        }

        .action-group * {
            background: transparent !important;
        }

        td:last-child {
            background: transparent !important;
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

        /* ========== MODAL TRANSFER ========== */
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
            background: var(--bg-primary);
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

        .modal-header {
            background: var(--bg-primary);
            padding: 25px;
            border-radius: 25px 25px 0 0;
            color: var(--text-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border-color);
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
            background: transparent;
            border: 2px solid var(--border-color);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-primary);
            font-size: 20px;
        }

        .modal-close:hover {
            background: transparent;
            border-color: #dc2626;
            color: #dc2626;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-info {
            background: var(--bg-primary);
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                        inset -4px -4px 8px var(--shadow-light);
        }

        .modal-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-info-row:last-child {
            border-bottom: none;
        }

        .modal-info-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 700;
        }

        .modal-info-value {
            font-size: 0.9rem;
            color: var(--text-primary);
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
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-label ion-icon {
            font-size: 1.1rem;
            color: var(--text-secondary);
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: var(--bg-primary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            font-weight: 900;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
        }

        .text-danger {
            color: #dc2626;
        }

        /* ========== PAGINATION ========== */
        .pagination-wrapper {
            padding: 20px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .data-table {
                font-size: 0.85rem;
            }

            .data-table th,
            .data-table td {
                padding: 12px 8px;
            }

            .col-code,
            .col-category {
                display: none;
            }

            .action-badge {
                width: 32px;
                height: 32px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .content-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .data-table th {
                font-size: 0.7rem;
                padding: 10px 6px;
            }

            .data-table td {
                font-size: 0.8rem;
                padding: 10px 6px;
            }
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('gedung.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Daftar Barang</h3>
            <p>{{ $ruangan->nama_ruangan }}</p>
        </div>
    </div>

    <div id="content-section">
        <div class="table-container">
            @if ($barang->count() > 0)
                {{-- Content Header --}}
                <div class="content-header">
                    <div class="content-title">
                        <ion-icon name="cube"></ion-icon>
                        Content
                    </div>
                    <div class="item-count">{{ $barang->total() }} Items</div>
                </div>

                {{-- Table --}}
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="col-code">Kode</th>
                                <th class="col-category">Kategori</th>
                                <th class="col-kondisi">Kondisi</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $d)
                                <tr>
                                    <td class="col-name">{{ $d->nama_barang }}</td>
                                    <td class="col-code">{{ $d->kode_barang }}</td>
                                    <td class="col-category">{{ $d->kategori ?? '-' }}</td>
                                    <td class="col-kondisi">
                                        @if($d->kondisi == 'baik')
                                            <span class="status-badge status-baik">
                                                <ion-icon name="checkmark-circle"></ion-icon>
                                                Baik
                                            </span>
                                        @elseif($d->kondisi == 'rusak-ringan')
                                            <span class="status-badge status-rusak-ringan">
                                                <ion-icon name="warning"></ion-icon>
                                                Rusak
                                            </span>
                                        @elseif($d->kondisi == 'rusak-berat')
                                            <span class="status-badge status-rusak-berat">
                                                <ion-icon name="close-circle"></ion-icon>
                                                Rusak
                                            </span>
                                        @else
                                            <span class="status-badge">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <button class="action-badge badge-view" 
                                                    onclick="viewDetail('{{ $d->nama_barang }}', '{{ $d->kode_barang }}', '{{ $d->kategori }}', '{{ $d->kondisi }}', '{{ $d->merk }}', '{{ $d->tahun_perolehan }}', '{{ number_format($d->harga_perolehan ?? 0, 0, ',', '.') }}', '{{ $d->keterangan }}', '{{ $d->foto ? asset('storage/uploads/barang/'.$d->foto) : '' }}')">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </button>
                                            <button class="action-badge badge-transfer" 
                                                    onclick="openTransferModal('{{ Crypt::encrypt($d->id) }}', '{{ $d->nama_barang }}', '{{ $d->kode_barang }}', '{{ $d->foto ? asset('storage/uploads/barang/'.$d->foto) : '' }}')">
                                                <ion-icon name="swap-horizontal-outline"></ion-icon>
                                            </button>
                                            <a href="{{ route('barang.riwayatKaryawan', [
                                                'gedung_id' => Crypt::encrypt($gedung->id),
                                                'ruangan_id' => Crypt::encrypt($ruangan->id),
                                                'id' => Crypt::encrypt($d->id)
                                            ]) }}" class="action-badge badge-history">
                                                <ion-icon name="time-outline"></ion-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($barang->hasPages())
                    <div class="pagination-wrapper">
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
    </div>

    {{-- Modal Transfer --}}
    <div class="modal-overlay" id="transferModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <ion-icon name="swap-horizontal-outline"></ion-icon>
                    Transfer Barang
                </h3>
                <button class="modal-close" onclick="closeTransferModal()">
                    <ion-icon name="close"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-foto-container" style="text-align: center; margin-bottom: 20px; display: none;">
                    <img id="modal-foto" src="" alt="Foto Barang" style="max-width: 100%; max-height: 200px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </div>
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
                        <select name="ruangan_tujuan_id" id="ruangan_tujuan_id" class="form-select" required>
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
                            min="1" value="1" required>
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
                            value="{{ auth()->user()->name }}" readonly>
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
                            <ion-icon name="checkmark-circle" style="font-size: 1.3rem;"></ion-icon>
                            Proses Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // View Detail Function
        function viewDetail(nama, kode, kategori, kondisi, merk, tahun, harga, keterangan, foto) {
            let kondisiText = 'Tidak Diketahui';
            let kondisiColor = '#6c757d';
            if (kondisi === 'baik') {
                kondisiText = 'Baik';
                kondisiColor = '#10b981';
            } else if (kondisi === 'rusak-ringan') {
                kondisiText = 'Rusak Ringan';
                kondisiColor = '#f59e0b';
            } else if (kondisi === 'rusak-berat') {
                kondisiText = 'Rusak Berat';
                kondisiColor = '#ef4444';
            }

            let fotoHtml = '';
            if (foto) {
                fotoHtml = `<div style="text-align: center; margin-bottom: 20px;">
                    <img src="${foto}" alt="${nama}" style="max-width: 100%; max-height: 200px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </div>`;
            }

            // Get computed styles for dark mode support
            const isDarkMode = document.body.classList.contains('dark-mode');
            const bgColor = isDarkMode ? '#2d3748' : '#e0e5ec';
            const textColor = isDarkMode ? '#f7fafc' : '#2d3748';
            const labelColor = isDarkMode ? '#a0aec0' : '#718096';
            const borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)';

            Swal.fire({
                title: nama,
                html: `
                    ${fotoHtml}
                    <div style="background: ${bgColor}; padding: 15px; border-radius: 15px; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.1), inset -2px -2px 5px rgba(255,255,255,0.1);">
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kode Barang</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kode}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kategori</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${kategori || '-'}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Merk</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${merk || '-'}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Tahun</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">${tahun || '-'}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid ${borderColor};">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Kondisi</span>
                            <span style="font-size: 0.9rem; color: ${kondisiColor}; font-weight: 900;">${kondisiText}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; ${keterangan ? 'border-bottom: 1px solid ' + borderColor + ';' : ''}">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700;">Harga</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 900;">Rp ${harga}</span>
                        </div>
                        ${keterangan ? `<div style="padding: 8px 0;">
                            <span style="font-size: 0.85rem; color: ${labelColor}; font-weight: 700; display: block; margin-bottom: 5px;">Keterangan</span>
                            <span style="font-size: 0.9rem; color: ${textColor}; font-weight: 500;">${keterangan}</span>
                        </div>` : ''}
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

        // Transfer Modal Functions
        function openTransferModal(encryptedBarangId, namaBarang, kodeBarang, foto) {
            document.getElementById('modal-kode').textContent = kodeBarang;
            document.getElementById('modal-nama').textContent = namaBarang;
            
            // Show foto if available
            const fotoContainer = document.getElementById('modal-foto-container');
            const fotoImg = document.getElementById('modal-foto');
            if (foto) {
                fotoImg.src = foto;
                fotoContainer.style.display = 'block';
            } else {
                fotoContainer.style.display = 'none';
            }
            
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

            if (!ruanganTujuan) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Pilih ruangan tujuan terlebih dahulu!'
                });
                return false;
            }

            if (!jumlahTransfer || jumlahTransfer < 1) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Jumlah transfer harus minimal 1!'
                });
                return false;
            }
        });
    </script>
@endsection
