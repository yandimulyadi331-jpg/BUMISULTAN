@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            /* Light Mode Colors */
            --bg-body-light: #ecf0f3;
            --bg-primary-light: #ecf0f3;
            --shadow-dark-light: #d1d9e6;
            --shadow-light-light: #ffffff;
            --text-primary-light: #2c3e50;
            --text-secondary-light: #6c7a89;
            --border-light: rgba(0, 0, 0, 0.05);

            /* Dark Mode Colors */
            --bg-body-dark: #1a202c;
            --bg-primary-dark: #2d3748;
            --shadow-dark-dark: #141923;
            --shadow-light-dark: #3a4555;
            --text-primary-dark: #f7fafc;
            --text-secondary-dark: #a0aec0;
            --border-dark: rgba(255, 255, 255, 0.08);
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

        body {
            background: var(--bg-body);
            min-height: 100vh;
        }

        #header-section {
            height: auto;
            padding: 20px;
            position: relative;
            background: var(--bg-primary);
            box-shadow: 4px 4px 12px var(--shadow-dark),
                       -4px -4px 12px var(--shadow-light);
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        .back-btn {
            color: var(--text-primary);
            font-size: 30px;
            text-decoration: none;
            background: var(--bg-body);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        .back-btn:hover {
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .back-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        #header-title {
            text-align: center;
            color: var(--text-primary);
        }

        #header-title h3 {
            font-weight: 600;
            margin: 0;
            font-size: 1.3rem;
        }

        #content-section {
            padding: 20px;
            margin-top: -10px;
        }

        .table-container {
            background: var(--bg-primary);
            border-radius: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            overflow: hidden;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            background: var(--bg-body);
        }

        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px 8px 35px;
            border: none;
            background: var(--bg-primary);
            border-radius: 8px;
            font-size: 0.875rem;
            outline: none;
            color: var(--text-primary);
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .search-input:focus {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
        }

        .sort-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sort-select {
            padding: 8px 32px 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 0.875rem;
            background: white;
            outline: none;
            cursor: pointer;
        }

        .sort-select:focus {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: var(--bg-body);
            border-bottom: 2px solid var(--border-color);
        }

        .data-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f3f5;
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background: var(--bg-body);
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        .santri-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .santri-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
            cursor: pointer;
            transition: all 0.3s;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .santri-avatar:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .santri-avatar-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .santri-avatar-initial:hover {
            transform: scale(1.1);
        }

        .santri-details {
            min-width: 0;
        }

        .santri-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 2px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .santri-nis {
            font-size: 0.75rem;
            color: #6c757d;
            margin: 0;
        }

        .amount-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15),
                       -1px -1px 4px rgba(255, 255, 255, 0.1);
        }

        .badge-primary {
            background: #10b981;
            color: white;
        }

        .badge-success {
            background: #3b82f6;
            color: white;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #78350f;
        }

        .date-text {
            font-size: 0.8125rem;
            color: #6c757d;
        }

        .btn-action {
            background: #14b8a6;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2),
                       -1px -1px 4px rgba(255, 255, 255, 0.1);
        }

        .btn-action:hover {
            background: #0d9488;
            color: white;
            transform: translateY(-2px);
            box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.25),
                       -2px -2px 6px rgba(255, 255, 255, 0.1);
        }

        .pagination-wrapper {
            padding: 20px;
            border-top: 1px solid #e9ecef;
        }

        /* Modal Slip Styles */
        .modal-slip {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        .modal-slip.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .slip-container {
            background: var(--bg-primary);
            max-width: 450px;
            width: 90%;
            border-radius: 20px;
            box-shadow: 12px 12px 24px var(--shadow-dark),
                       -12px -12px 24px var(--shadow-light);
            animation: slideUp 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .slip-header {
            background: var(--bg-body);
            padding: 30px 20px;
            border-radius: 20px 20px 0 0;
            text-align: center;
            color: var(--text-primary);
            position: relative;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .slip-close {
            position: absolute;
            right: 15px;
            top: 15px;
            background: var(--bg-primary);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            color: var(--text-primary);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
        }

        .slip-close:hover {
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .slip-close:active {
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        .slip-icon {
            width: 60px;
            height: 60px;
            background: var(--bg-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            color: #14b8a6;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light),
                       inset -1px -1px 2px var(--shadow-dark),
                       inset 1px 1px 2px var(--shadow-light);
        }

        .slip-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .slip-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
        }

        .slip-body {
            padding: 25px;
        }

        .slip-info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .slip-info-row:last-child {
            border-bottom: none;
        }

        .slip-label {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 500;
        }

        .slip-value {
            font-size: 0.875rem;
            color: var(--text-primary);
            font-weight: 600;
            text-align: right;
        }

        .slip-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #dee2e6;
        }

        .slip-section-title {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .slip-amount-box {
            background: var(--bg-body);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 20px 0;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .slip-amount-label {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .slip-amount-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1971c2;
        }

        .slip-amount-value.positive {
            color: #2b8a3e;
        }

        .slip-amount-value.negative {
            color: #c92a2a;
        }

        .slip-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .slip-grid-item {
            background: var(--bg-body);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
        }

        .slip-grid-label {
            font-size: 0.7rem;
            color: #6c757d;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .slip-grid-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .slip-grid-value.success {
            color: #2b8a3e;
        }

        .slip-grid-value.danger {
            color: #c92a2a;
        }

        .slip-footer {
            padding: 20px 25px;
            background: var(--bg-body);
            border-radius: 0 0 20px 20px;
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-secondary);
            box-shadow: inset 0 3px 6px var(--shadow-dark);
        }

        /* Modal Photo Popup */
        .modal-photo {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(5px);
        }

        .modal-photo.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-container {
            max-width: 90%;
            max-height: 90%;
            position: relative;
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .photo-container img {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .photo-close {
            position: absolute;
            top: -15px;
            right: -15px;
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #333;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }

        .photo-close:hover {
            background: #f8f9fa;
            transform: rotate(90deg);
        }

        .photo-info {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
        }

        .photo-info h4 {
            margin: 0 0 5px 0;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .photo-info p {
            margin: 0;
            color: #6c757d;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper {
                max-width: 100%;
            }

            .data-table {
                font-size: 0.8125rem;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }

            .santri-avatar {
                width: 35px;
                height: 35px;
                font-size: 0.75rem;
            }
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('saungsantri.dashboard.karyawan') }}" class="back-btn">
                <i class="ti ti-arrow-left"></i>
            </a>
        </div>
        <div id="header-title">
            <h3>Keuangan Santri</h3>
        </div>
    </div>

    <div id="content-section">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Table Container --}}
        <div class="table-container">
            {{-- Table Header with Search & Sort --}}
            <div class="table-header">
                <div class="search-wrapper">
                    <i class="ti ti-search search-icon"></i>
                    <form method="GET" action="{{ route('keuangan-santri.karyawan.index') }}" id="searchForm">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               class="search-input" 
                               placeholder="Search documents"
                               onchange="this.form.submit()">
                    </form>
                </div>
                <div class="sort-wrapper">
                    <i class="ti ti-arrows-sort"></i>
                    <select class="sort-select" onchange="window.location.href='{{ route('keuangan-santri.karyawan.index') }}?status=' + this.value">
                        <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="cuti" {{ $status == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="alumni" {{ $status == 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="keluar" {{ $status == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
            </div>

            {{-- Data Table --}}
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Santri</th>
                        <th>Saldo Awal</th>
                        <th>Total Setoran</th>
                        <th>Pengeluaran</th>
                        <th>Sisa Saldo</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($santriList as $santri)
                        @php
                            $saldo = $santri->keuanganSaldo;
                            // Generate initial dari nama
                            $words = explode(' ', $santri->nama_lengkap);
                            $initial = '';
                            foreach($words as $w) {
                                if(!empty($w)) {
                                    $initial .= strtoupper(substr($w, 0, 1));
                                    if(strlen($initial) >= 2) break;
                                }
                            }
                            
                            // Avatar colors
                            $avatarColors = ['#FF9A76', '#9B88FA', '#4ECDC4', '#FFD93D', '#6BCF7F'];
                            $colorIndex = ord($initial[0] ?? 'A') % count($avatarColors);
                            
                            $saldoAwal = $saldo->saldo_awal ?? 0;
                            $totalSetoran = $saldo->total_pemasukan ?? 0;
                            $totalPengeluaran = $saldo->total_pengeluaran ?? 0;
                            $sisaSaldo = $saldo->saldo_akhir ?? 0;
                        @endphp
                        <tr>
                            <td>
                                <span style="font-size: 0.8125rem; color: #495057; font-weight: 500;">{{ $santri->nis }}</span>
                            </td>
                            <td>
                                <div class="santri-info">
                                    @if($santri->foto_santri && file_exists(public_path('storage/' . $santri->foto_santri)))
                                        <img src="{{ asset('storage/' . $santri->foto_santri) }}" 
                                             alt="{{ $santri->nama_lengkap }}" 
                                             class="santri-avatar"
                                             onclick="showPhoto('{{ asset('storage/' . $santri->foto_santri) }}', '{{ $santri->nama_lengkap }}', '{{ $santri->nis }}')">
                                    @else
                                        <div class="santri-avatar-initial" style="background: {{ $avatarColors[$colorIndex] }}">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <div class="santri-details">
                                        <p class="santri-name">{{ $santri->nama_lengkap }}</p>
                                        <p class="santri-nis">NIS: {{ $santri->nis }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="amount-value" style="color: #495057;">
                                    Rp {{ number_format($saldoAwal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="amount-value" style="color: #2b8a3e;">
                                    Rp {{ number_format($totalSetoran, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="amount-value" style="color: #c92a2a;">
                                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="amount-value" style="color: {{ $sisaSaldo > 0 ? '#1971c2' : '#6c757d' }}; font-weight: 700;">
                                    Rp {{ number_format($sisaSaldo, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($santri->status_santri == 'aktif')
                                    <span class="badge-status badge-primary">Aktif</span>
                                @elseif($santri->status_santri == 'cuti')
                                    <span class="badge-status badge-warning">Cuti</span>
                                @elseif($santri->status_santri == 'alumni')
                                    <span class="badge-status badge-success">Alumni</span>
                                @else
                                    <span class="badge-status badge-secondary">Keluar</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn-action" onclick="showSlip({{ $santri->id }}, '{{ $santri->nama_lengkap }}', '{{ $santri->nis }}', '{{ $santri->status_santri }}', {{ $saldoAwal }}, {{ $totalSetoran }}, {{ $totalPengeluaran }}, {{ $sisaSaldo }}, '{{ $saldo && $saldo->last_transaction_date ? \Carbon\Carbon::parse($saldo->last_transaction_date)->format('d/m/Y H:i') : '-' }}')">
                                    Lihat
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                <i class="ti ti-wallet-off" style="font-size: 3rem; opacity: 0.3; display: block; margin-bottom: 10px;"></i>
                                <strong>Tidak ada data santri</strong><br>
                                <small>Belum ada data santri {{ $status }} yang ditemukan</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($santriList->hasPages())
                <div class="pagination-wrapper">
                    {{ $santriList->links() }}
                </div>
            @endif
        </div>

        <div style="height: 80px;"></div>
    </div>

    {{-- Modal Slip --}}
    <div id="modalSlip" class="modal-slip" onclick="closeSlipIfOutside(event)">
        <div class="slip-container" onclick="event.stopPropagation()">
            <div class="slip-header" id="slipHeader">
                <button class="slip-close" onclick="closeSlip()">
                    <i class="ti ti-x"></i>
                </button>
                <div class="slip-icon">
                    <i class="ti ti-wallet"></i>
                </div>
                <h3 class="slip-title">Detail Keuangan Santri</h3>
                <p class="slip-subtitle" id="slipNama">-</p>
            </div>
            
            <div class="slip-body">
                {{-- Info Santri --}}
                <div>
                    <div class="slip-info-row">
                        <span class="slip-label">NIS</span>
                        <span class="slip-value" id="slipNIS">-</span>
                    </div>
                    <div class="slip-info-row">
                        <span class="slip-label">Nama Lengkap</span>
                        <span class="slip-value" id="slipNamaLengkap">-</span>
                    </div>
                    <div class="slip-info-row">
                        <span class="slip-label">Status Santri</span>
                        <span class="slip-value" id="slipStatus">-</span>
                    </div>
                </div>

                {{-- Saldo Section --}}
                <div class="slip-section">
                    <div class="slip-section-title">Ringkasan Keuangan</div>
                    
                    <div class="slip-grid">
                        <div class="slip-grid-item">
                            <div class="slip-grid-label">Saldo Awal</div>
                            <div class="slip-grid-value" id="slipSaldoAwal">Rp 0</div>
                        </div>
                        <div class="slip-grid-item">
                            <div class="slip-grid-label">Total Setoran</div>
                            <div class="slip-grid-value success" id="slipSetoran">Rp 0</div>
                        </div>
                        <div class="slip-grid-item">
                            <div class="slip-grid-label">Total Pengeluaran</div>
                            <div class="slip-grid-value danger" id="slipPengeluaran">Rp 0</div>
                        </div>
                        <div class="slip-grid-item">
                            <div class="slip-grid-label">Transaksi Terakhir</div>
                            <div class="slip-grid-value" id="slipTglTransaksi" style="font-size: 0.85rem;">-</div>
                        </div>
                    </div>

                    <div class="slip-amount-box">
                        <div class="slip-amount-label">Sisa Saldo Saat Ini</div>
                        <div class="slip-amount-value" id="slipSisaSaldo">Rp 0</div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <a href="#" id="slipLinkDetail" class="btn-action" style="padding: 10px 30px; text-decoration: none;" onclick="event.preventDefault(); downloadPdfResi();">
                            <i class="ti ti-download me-2"></i>Download PDF Resi
                        </a>
                    </div>
                </div>
            </div>

            <div class="slip-footer">
                <i class="ti ti-info-circle"></i> Data keuangan diperbarui secara real-time
            </div>
        </div>
    </div>

    {{-- Modal Photo Popup --}}
    <div id="modalPhoto" class="modal-photo" onclick="closePhoto()">
        <div class="photo-container" onclick="event.stopPropagation()">
            <button class="photo-close" onclick="closePhoto()">
                <i class="ti ti-x"></i>
            </button>
            <img id="photoImage" src="" alt="Foto Santri">
            <div class="photo-info">
                <h4 id="photoNama">-</h4>
                <p id="photoNIS">NIS: -</p>
            </div>
        </div>
    </div>

    @push('myscript')
    <script>
        // Auto hide alert setelah 3 detik
        @if(session('success'))
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        @endif

        // Variable untuk menyimpan ID santri saat ini
        let currentSantriId = null;

        // Function to show slip modal
        function showSlip(id, nama, nis, status, saldoAwal, setoran, pengeluaran, sisaSaldo, tglTransaksi) {
            // Simpan ID santri
            currentSantriId = id;
            
            // Set data
            document.getElementById('slipNama').textContent = nama;
            document.getElementById('slipNIS').textContent = nis;
            document.getElementById('slipNamaLengkap').textContent = nama;
            
            // Status badge
            let statusBadge = '';
            if(status === 'aktif') {
                statusBadge = '<span class="badge-status badge-primary">Aktif</span>';
            } else if(status === 'cuti') {
                statusBadge = '<span class="badge-status badge-warning">Cuti</span>';
            } else if(status === 'alumni') {
                statusBadge = '<span class="badge-status badge-success">Alumni</span>';
            } else {
                statusBadge = '<span class="badge-status badge-secondary">Keluar</span>';
            }
            document.getElementById('slipStatus').innerHTML = statusBadge;
            
            // Format currency
            document.getElementById('slipSaldoAwal').textContent = 'Rp ' + saldoAwal.toLocaleString('id-ID');
            document.getElementById('slipSetoran').textContent = 'Rp ' + setoran.toLocaleString('id-ID');
            document.getElementById('slipPengeluaran').textContent = 'Rp ' + pengeluaran.toLocaleString('id-ID');
            document.getElementById('slipSisaSaldo').textContent = 'Rp ' + sisaSaldo.toLocaleString('id-ID');
            document.getElementById('slipTglTransaksi').textContent = tglTransaksi;
            
            // Set color for sisa saldo
            const sisaSaldoElement = document.getElementById('slipSisaSaldo');
            if(sisaSaldo > 0) {
                sisaSaldoElement.className = 'slip-amount-value positive';
            } else if(sisaSaldo < 0) {
                sisaSaldoElement.className = 'slip-amount-value negative';
            } else {
                sisaSaldoElement.className = 'slip-amount-value';
            }
            
            // Show modal
            document.getElementById('modalSlip').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Function to download PDF
        function downloadPdfResi() {
            if(currentSantriId) {
                window.location.href = '{{ url("keuangan-santri-karyawan") }}/' + currentSantriId + '/download-resi';
            }
        }

        // Function to close slip modal
        function closeSlip() {
            document.getElementById('modalSlip').classList.remove('show');
            document.body.style.overflow = 'auto';
            currentSantriId = null;
        }

        // Close modal if clicking outside
        function closeSlipIfOutside(event) {
            if (event.target.id === 'modalSlip') {
                closeSlip();
            }
        }

        // Close with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSlip();
                closePhoto();
            }
        });

        // Function to show photo popup
        function showPhoto(photoUrl, nama, nis) {
            document.getElementById('photoImage').src = photoUrl;
            document.getElementById('photoNama').textContent = nama;
            document.getElementById('photoNIS').textContent = 'NIS: ' + nis;
            document.getElementById('modalPhoto').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Function to close photo popup
        function closePhoto() {
            document.getElementById('modalPhoto').classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection
