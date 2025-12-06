@extends('layouts.mobile.app')
@section('content')
<style>
/* FORCE SCROLL - SUPER AGGRESSIVE */
* {
    -webkit-overflow-scrolling: touch !important;
}

html {
    overflow: visible !important;
    overflow-y: scroll !important;
    overflow-x: hidden !important;
    height: auto !important;
    max-height: none !important;
    position: relative !important;
}

body {
    overflow: visible !important;
    overflow-y: scroll !important;
    overflow-x: hidden !important;
    height: auto !important;
    max-height: none !important;
    min-height: 100vh !important;
    position: relative !important;
}

#appCapsule {
    overflow: visible !important;
    overflow-y: visible !important;
    height: auto !important;
    max-height: none !important;
    min-height: calc(100vh + 200px) !important;
    position: static !important;
    padding-bottom: 150px !important;
}

:root {
    --bg-primary: #f8f9fa;
    --bg-secondary: #ffffff;
    --text-primary: #1a202c;
    --text-secondary: #718096;
    --border-color: #e2e8f0;
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

[data-theme="dark"] {
    --bg-primary: #1a202c;
    --bg-secondary: #2d3748;
    --text-primary: #f7fafc;
    --text-secondary: #cbd5e0;
    --border-color: #4a5568;
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

#header-section {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: var(--bg-secondary);
}

#content-section {
    margin-top: 56px;
    padding: 20px 16px;
    position: relative;
    z-index: 1;
    padding-bottom: 150px;
    background: var(--bg-primary);
    min-height: calc(100vh - 56px);
    transition: background 0.3s ease;
}

.search-filter-section {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.search-box {
    position: relative;
    margin-bottom: 12px;
}

.search-box input {
    width: 100%;
    padding: 12px 16px 12px 44px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.search-box ion-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: var(--text-secondary);
}

.filter-row {
    display: flex;
    gap: 8px;
    align-items: center;
}

.sort-dropdown {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    font-size: 13px;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.theme-toggle-btn {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .theme-toggle-btn ion-icon {
            font-size: 22px;
            color: var(--text-primary);
        }

        .theme-toggle-btn:active {
            transform: scale(0.95);
        }

.table-container {
    background: var(--bg-secondary);
    border-radius: 12px;
    overflow: visible;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    margin-bottom: 50px;
}

.table-responsive {
    overflow-x: auto;
    max-height: none;
    overflow-y: visible;
}        .izin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .izin-table thead {
            background: var(--bg-primary);
        }

        .izin-table th {
            padding: 14px 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            border-bottom: 2px solid var(--border-color);
        }

        .izin-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
        }

        .izin-table tbody tr:hover {
            background: var(--bg-primary);
        }

        .izin-table tbody tr:last-child {
            border-bottom: none;
        }

        .izin-table td {
            padding: 16px 12px;
            font-size: 14px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .santri-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .santri-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .avatar-i { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .avatar-s { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .avatar-c { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .avatar-d { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .avatar-tl { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }

        .santri-details {
            flex: 1;
            min-width: 0;
        }

        .santri-name {
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 2px;
            font-size: 15px;
        }

        .santri-nis {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .amount {
            font-weight: 700;
            white-space: nowrap;
        }

        .amount.green { color: #10b981; }
        .amount.red { color: #ef4444; }
        .amount.blue { color: #3b82f6; }
        .amount.gray { color: var(--text-secondary); }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
            white-space: nowrap;
        }

        .status-aktif {
            background: #10b98120;
            color: #10b981;
        }

        .status-pending {
            background: #f59e0b20;
            color: #f59e0b;
        }

        .status-ditolak {
            background: #ef444420;
            color: #ef4444;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        .btn-table {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-aktif {
            background: #10b981;
            color: white;
        }

        .btn-aktif:active {
            background: #059669;
            transform: scale(0.97);
        }

        .btn-lihat {
            background: #14b8a6;
            color: white;
        }

        .btn-lihat:active {
            background: #0d9488;
            transform: scale(0.97);
        }

        .btn-hapus {
            background: #ef4444;
            color: white;
        }

        .btn-hapus:active {
            background: #dc2626;
            transform: scale(0.97);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: var(--bg-secondary);
            border-radius: 12px;
            margin-top: 20px;
            box-shadow: var(--shadow);
        }

        .empty-state ion-icon {
            font-size: 80px;
            color: var(--text-secondary);
            opacity: 0.5;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-top: 16px;
            font-size: 15px;
            font-weight: 600;
        }

.fab-button {
    position: fixed;
    bottom: 85px;
    left: 20px;
    z-index: 999;
    margin-bottom: 10px;
}        .fab {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .fab:active {
            transform: scale(0.94);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .dropdown-menu {
            min-width: 200px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border: 1px solid var(--border-color);
            padding: 8px;
            background: var(--bg-secondary);
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            color: var(--text-primary);
            text-decoration: none;
        }

        .dropdown-item:last-child {
            margin-bottom: 0;
        }

        .dropdown-item:hover,
        .dropdown-item:active {
            background: var(--bg-primary);
            transform: scale(0.98);
        }

        .dropdown-item ion-icon {
            font-size: 20px;
        }

        .dropdown-item p {
            margin: 0;
        }

        .dropdown-item.item-izin { color: #667eea; }
        .dropdown-item.item-sakit { color: #f5576c; }
        .dropdown-item.item-cuti { color: #00f2fe; }
        .dropdown-item.item-dinas { color: #38f9d7; }
        .dropdown-item.item-tl { color: #fee140; }

        /* Mobile responsive */
        @media (max-width: 768px) {
            #content-section {
                padding: 12px;
            }

            .izin-table th,
            .izin-table td {
                padding: 10px 8px;
                font-size: 12px;
            }

            .santri-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .santri-name {
                font-size: 14px;
            }

            .btn-table {
                padding: 6px 12px;
                font-size: 12px;
            }
        }

        /* Custom Modal Styles */
        .custom-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease;
        }

        .custom-modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .custom-modal {
            background: var(--bg-secondary);
            border-radius: 20px;
            padding: 0;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 24px;
            text-align: center;
            position: relative;
        }

        .modal-header ion-icon {
            font-size: 48px;
            color: white;
            margin-bottom: 8px;
        }

        .modal-header h3 {
            color: white;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-detail-row {
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .modal-detail-label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-detail-value {
            font-size: 15px;
            color: var(--text-primary);
            font-weight: 600;
        }

        .modal-footer {
            padding: 16px 24px 24px;
        }

        .modal-btn {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: none;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-btn-primary:active {
            transform: scale(0.97);
        }

        /* Confirm Modal Styles */
        .confirm-modal .modal-header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
        }

        .modal-btn-cancel {
            flex: 1;
            background: var(--bg-primary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .modal-btn-confirm {
            flex: 1;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .modal-message {
            text-align: center;
            font-size: 15px;
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* FAB Button - ensure it doesn't block scroll */
        .fab-button {
            position: fixed !important;
            bottom: 80px !important;
            right: 20px !important;
            z-index: 999 !important;
        }

        /* Ensure bottom navigation doesn't block content */
        .appBottomMenu {
            position: fixed !important;
            bottom: 0 !important;
            z-index: 1000 !important;
        }
</style>

<div id="header-section">
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Pengajuan Izin</div>
        <div class="right"></div>
    </div>
</div>

<div id="content-section">
    <!-- Search & Filter Section -->
    <div class="search-filter-section">
        <div class="search-box">
            <ion-icon name="search-outline"></ion-icon>
            <input type="text" placeholder="Search documents" id="searchInput">
        </div>
        <div class="filter-row">
            <button class="theme-toggle-btn" onclick="toggleDarkMode()">
                <ion-icon name="sunny-outline" id="themeIcon"></ion-icon>
            </button>
            <select class="sort-dropdown" id="sortSelect">
                <option value="aktif">Aktif</option>
                <option value="pending">Pending</option>
                <option value="ditolak">Ditolak</option>
                <option value="semua">Semua</option>
            </select>
        </div>
    </div>

    <!-- Table Container -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="izin-table">
                    <thead>
                        <tr>
                            <th>JENIS IZIN</th>
                            <th>DARI</th>
                            <th>SAMPAI</th>
                            <th>KETERANGAN</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
        @forelse ($pengajuan_izin as $index => $d)
            @php
                if ($d->ket == 'i') {
                    $route = 'izinabsen.delete';
                    $avatarClass = 'avatar-i';
                    $jenisIzin = 'Izin Absen';
                } elseif ($d->ket == 's') {
                    $route = 'izinsakit.delete';
                    $avatarClass = 'avatar-s';
                    $jenisIzin = 'Izin Sakit';
                } elseif ($d->ket == 'c') {
                    $route = 'izincuti.delete';
                    $avatarClass = 'avatar-c';
                    $jenisIzin = 'Izin Cuti';
                } elseif ($d->ket == 'd') {
                    $route = 'izindinas.delete';
                    $avatarClass = 'avatar-d';
                    $jenisIzin = 'Izin Dinas';
                } elseif ($d->ket == 'tl') {
                    $route = 'tugasluar.kembali';
                    $avatarClass = 'avatar-tl';
                    $jenisIzin = 'Tugas Luar';
                }
                
                // Status
                if ($d->status_izin == '1') {
                    $statusClass = 'status-aktif';
                    $statusText = $d->ket == 'tl' ? 'Kembali' : 'Disetujui';
                } elseif ($d->status_izin == '2') {
                    $statusClass = 'status-ditolak';
                    $statusText = 'Ditolak';
                } else {
                    $statusClass = 'status-pending';
                    $statusText = $d->ket == 'tl' ? 'Keluar' : 'Pending';
                }
            @endphp
            
            @if($route)
                @if($d->ket == 'tl')
                    <form method="POST" name="kembaliForm-{{ $d->kode }}" class="kembaliForm" action="{{ route($route, Crypt::encrypt($d->kode)) }}" style="display: inline;">
                        @csrf
                @else
                    <form method="POST" name="deleteform-{{ $d->kode }}" class="deleteform" action="{{ route($route, Crypt::encrypt($d->kode)) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                @endif
            @endif
            
                        <tr data-status="{{ $statusClass }}">
                            <td>
                                <div class="santri-info">
                                    <div class="santri-avatar {{ $avatarClass }}">
                                        {{ textUpperCase($d->ket) }}
                                    </div>
                                    <div class="santri-details">
                                        <div class="santri-name">{{ $jenisIzin }}</div>
                                        <div class="santri-nis">{{ $d->kode }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="amount gray">{{ DateToIndo($d->dari) }}</span></td>
                            <td><span class="amount blue">{{ DateToIndo($d->sampai) }}</span></td>
                            <td><span class="amount gray">{{ Str::limit($d->keterangan, 30) }}</span></td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if ($d->ket == 'tl')
                                        @if($d->status_izin == '0')
                                            <button type="button" class="btn-table btn-hapus"
                                                    onclick="showKembaliModal(this)">
                                                Kembali
                                            </button>
                                        @else
                                            <button type="button" class="btn-table btn-aktif" disabled>
                                                Sudah Kembali
                                            </button>
                                        @endif
                                    @elseif ($d->status_izin == '1')
                                        <button type="button" class="btn-table btn-aktif" disabled>
                                            Aktif
                                        </button>
                                    @endif
                                    <button type="button" class="btn-table btn-lihat" 
                                            onclick="showDetailModal('{{ $jenisIzin }}', '{{ DateToIndo($d->dari) }}', '{{ DateToIndo($d->sampai) }}', '{{ addslashes($d->keterangan) }}')">
                                        Lihat
                                    </button>
                                </div>
                            </td>
                        </tr>
            
            @if($route)
            </form>
            @endif
        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <ion-icon name="document-text-outline"></ion-icon>
                                    <p>Belum ada pengajuan izin</p>
                                </div>
                            </td>
                        </tr>
        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="custom-modal-overlay" id="detailModal" onclick="closeModal('detailModal')">
        <div class="custom-modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <ion-icon name="information-circle-outline"></ion-icon>
                <h3>Detail Pengajuan Izin</h3>
            </div>
            <div class="modal-body">
                <div class="modal-detail-row">
                    <div class="modal-detail-label">Jenis Izin</div>
                    <div class="modal-detail-value" id="modalJenisIzin">-</div>
                </div>
                <div class="modal-detail-row">
                    <div class="modal-detail-label">Dari Tanggal</div>
                    <div class="modal-detail-value" id="modalDari">-</div>
                </div>
                <div class="modal-detail-row">
                    <div class="modal-detail-label">Sampai Tanggal</div>
                    <div class="modal-detail-value" id="modalSampai">-</div>
                </div>
                <div class="modal-detail-row">
                    <div class="modal-detail-label">Keterangan</div>
                    <div class="modal-detail-value" id="modalKeterangan">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-primary" onclick="closeModal('detailModal')">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div class="custom-modal-overlay" id="confirmModal" onclick="closeModal('confirmModal')">
        <div class="custom-modal confirm-modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <ion-icon name="warning-outline"></ion-icon>
                <h3>Konfirmasi Pembatalan</h3>
            </div>
            <div class="modal-body">
                <p class="modal-message">Yakin ingin membatalkan pengajuan izin ini?</p>
            </div>
            <div class="modal-footer">
                <div class="modal-buttons">
                    <button class="modal-btn modal-btn-cancel" onclick="closeModal('confirmModal')">
                        Batal
                    </button>
                    <button class="modal-btn modal-btn-confirm" onclick="confirmDelete()">
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Kembali Modal -->
    <div class="custom-modal-overlay" id="kembaliModal" onclick="closeModal('kembaliModal')">
        <div class="custom-modal confirm-modal" onclick="event.stopPropagation()">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                <h3>Konfirmasi Kembali</h3>
            </div>
            <div class="modal-body">
                <p class="modal-message">Apakah Anda sudah kembali dari tugas luar?</p>
            </div>
            <div class="modal-footer">
                <div class="modal-buttons">
                    <button class="modal-btn modal-btn-cancel" onclick="closeModal('kembaliModal')">
                        Belum
                    </button>
                    <button class="modal-btn modal-btn-confirm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);" onclick="confirmKembali()">
                        Ya, Sudah Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="fab-button animate dropdown">
        <a href="#" class="fab" data-toggle="dropdown">
            <ion-icon name="add-outline"></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item item-izin" href="{{ route('izinabsen.create') }}">
                <ion-icon name="document-outline"></ion-icon>
                <p>Izin Absen</p>
            </a>

            <a class="dropdown-item item-sakit" href="{{ route('izinsakit.create') }}">
                <ion-icon name="bag-add-outline"></ion-icon>
                <p>Izin Sakit</p>
            </a>

            <a class="dropdown-item item-cuti" href="{{ route('izincuti.create') }}">
                <ion-icon name="calendar-outline"></ion-icon>
                <p>Izin Cuti</p>
            </a>

            <a class="dropdown-item item-dinas" href="{{ route('izindinas.create') }}">
                <ion-icon name="airplane-outline"></ion-icon>
                <p>Izin Dinas</p>
            </a>

            <a class="dropdown-item item-tl" href="{{ route('tugasluar.create') }}">
                <ion-icon name="briefcase-outline"></ion-icon>
                <p>Tugas Luar</p>
            </a>
        </div>
    </div>

@endsection

@push('myscript')
<script>
    let formToSubmit = null;

    // Modal Functions
    function showDetailModal(jenisIzin, dari, sampai, keterangan) {
        document.getElementById('modalJenisIzin').textContent = jenisIzin;
        document.getElementById('modalDari').textContent = dari;
        document.getElementById('modalSampai').textContent = sampai;
        document.getElementById('modalKeterangan').textContent = keterangan;
        document.getElementById('detailModal').classList.add('active');
    }

    function showConfirmModal(button) {
        formToSubmit = button.closest('form');
        document.getElementById('confirmModal').classList.add('active');
    }

    function showKembaliModal(button) {
        formToSubmit = button.closest('form');
        document.getElementById('kembaliModal').classList.add('active');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        if (modalId === 'confirmModal' || modalId === 'kembaliModal') {
            formToSubmit = null;
        }
    }

    function confirmDelete() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
        closeModal('confirmModal');
    }

    function confirmKembali() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
        closeModal('kembaliModal');
    }

    // Dark Mode Toggle
    function toggleDarkMode() {
        const body = document.body;
        const themeIcon = document.getElementById('themeIcon');
        
        if (body.getAttribute('data-theme') === 'dark') {
            body.removeAttribute('data-theme');
            themeIcon.setAttribute('name', 'sunny-outline');
            localStorage.setItem('theme', 'light');
        } else {
            body.setAttribute('data-theme', 'dark');
            themeIcon.setAttribute('name', 'moon-outline');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const themeIcon = document.getElementById('themeIcon');
        
        if (savedTheme === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
            themeIcon.setAttribute('name', 'moon-outline');
        }

        // Force enable scrolling
        document.documentElement.style.overflowY = 'scroll';
        document.body.style.overflowY = 'scroll';
        document.body.style.height = 'auto';
        document.body.style.maxHeight = 'none';
        
        const appCapsule = document.getElementById('appCapsule');
        if (appCapsule) {
            appCapsule.style.overflow = 'visible';
            appCapsule.style.height = 'auto';
            appCapsule.style.maxHeight = 'none';
        }
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.izin-table tbody tr[data-status]');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Sort functionality
    document.getElementById('sortSelect').addEventListener('change', function(e) {
        const filterValue = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.izin-table tbody tr[data-status]');
        
        rows.forEach(row => {
            if (filterValue === 'semua') {
                row.style.display = '';
            } else {
                const status = row.getAttribute('data-status');
                if (status.includes(filterValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });

    // Cancel confirm for delete
    $(document).on('click', '.cancel-confirm', function(e) {
        if ($(e.target).hasClass('btn-hapus') || $(e.target).closest('.btn-hapus').length) {
            return true; // Let the form handle the confirm
        }
    });
</script>
@endpush
