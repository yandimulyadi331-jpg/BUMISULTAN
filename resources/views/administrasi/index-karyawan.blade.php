@extends('layouts.mobile.app')
@section('titlepage', 'Manajemen Administrasi')

@section('content')
<style>
    :root {
        /* Minimal Elegant Colors */
        --bg-body-light: #ecf0f3;
        --bg-primary-light: #ecf0f3;
        --shadow-dark-light: #bec3c9;
        --shadow-light-light: #ffffff;
        --text-primary-light: #3d5467;
        --text-secondary-light: #6b7c8d;
        --border-light: rgba(0, 0, 0, 0.05);
        --icon-color-light: #6b7c8d;

        --bg-body-dark: #1a202c;
        --bg-primary-dark: #2d3748;
        --shadow-dark-dark: #171923;
        --shadow-light-dark: #3f4c63;
        --text-primary-dark: #f7fafc;
        --text-secondary-dark: #a0aec0;
        --border-dark: rgba(255, 255, 255, 0.08);
        --icon-color-dark: #a0aec0;

        /* Accent Colors - Minimal */
        --accent-primary: #4a90a4;
        --accent-success: #26c281;
        --accent-warning: #f8b739;
        --accent-danger: #e74c3c;
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
        --icon-color: var(--icon-color-light);
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
        --icon-color: var(--icon-color-dark);
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
        margin-bottom: 0;
        box-shadow: 0 2px 15px rgba(0,0,0,0.03);
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
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
        transition: all 0.3s ease;
        text-decoration: none;
        flex-shrink: 0;
    }

    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }

    .back-btn:active {
        transform: scale(0.95);
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }

    .back-btn ion-icon {
        font-size: 22px;
        color: var(--icon-color);
    }

    .header-title {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .header-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .header-title p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
        margin-top: 4px;
        letter-spacing: 0.1px;
    }

    .btn-add {
        background: var(--bg-primary);
        border: none;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
        transition: all 0.3s ease;
        text-decoration: none;
        flex-shrink: 0;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }

    .btn-add:active {
        transform: scale(0.95);
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }

    .btn-add ion-icon {
        font-size: 26px;
        color: var(--accent-primary);
    }

    #content-section {
        padding: 25px 20px;
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 100px;
    }

    /* Minimal Filter Card */
    .filter-card {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 0;
        box-shadow: 5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .filter-header {
        background: var(--bg-primary);
        padding: 18px 22px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .filter-header h4 {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-header ion-icon {
        color: var(--icon-color);
        font-size: 18px;
    }
    
    .filter-body {
        padding: 22px;
        background: var(--bg-primary);
    }

    .form-control {
        font-size: 0.875rem;
        border-radius: 14px;
        border: none;
        padding: 14px 18px;
        background: var(--bg-primary);
        box-shadow: inset 3px 3px 8px var(--shadow-dark),
                   inset -3px -3px 8px var(--shadow-light);
        transition: all 0.3s ease;
        color: var(--text-primary);
        font-weight: 500;
    }
    
    .form-control:focus {
        outline: none;
        box-shadow: inset 4px 4px 10px var(--shadow-dark),
                   inset -4px -4px 10px var(--shadow-light);
        background: var(--bg-primary);
    }
    
    .form-control::placeholder {
        color: var(--text-secondary);
        font-weight: 400;
        opacity: 0.7;
    }

    .form-select {
        font-size: 0.875rem;
        border-radius: 14px;
        border: none;
        padding: 14px 18px;
        background: var(--bg-primary);
        box-shadow: inset 3px 3px 8px var(--shadow-dark),
                   inset -3px -3px 8px var(--shadow-light);
        transition: all 0.3s ease;
        cursor: pointer;
        color: var(--text-primary);
        font-weight: 500;
    }
    
    .form-select:focus {
        outline: none;
        box-shadow: inset 4px 4px 10px var(--shadow-dark),
                   inset -4px -4px 10px var(--shadow-light);
        background: var(--bg-primary);
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-secondary);
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }

    .btn-filter {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        padding: 14px 20px;
        border-radius: 14px;
        font-size: 0.8rem;
        font-weight: 700;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        cursor: pointer;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }

    .btn-filter:active {
        transform: scale(0.98);
        box-shadow: inset 3px 3px 8px var(--shadow-dark),
                   inset -3px -3px 8px var(--shadow-light);
    }
    
    .btn-reset {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        padding: 12px 18px;
        border-radius: 14px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }

    .btn-reset:active {
        transform: scale(0.98);
        box-shadow: inset 3px 3px 8px var(--shadow-dark),
                   inset -3px -3px 8px var(--shadow-light);
    }
    
    /* Stats Info */
    .stats-info {
        background: var(--bg-primary);
        padding: 16px 20px;
        margin-bottom: 25px;
        border-radius: 16px;
        box-shadow: 4px 4px 12px var(--shadow-dark),
                   -4px -4px 12px var(--shadow-light);
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .stats-info ion-icon {
        font-size: 20px;
        color: var(--icon-color);
    }
    
    .stats-info strong {
        color: var(--text-primary);
        font-size: 1.05rem;
        font-weight: 700;
    }

    
    /* Responsive Grid Adjustments */
    @media (max-width: 767px) {
        #content-section {
            padding: 15px 10px;
        }
        
        .filter-header {
            padding: 15px 20px;
        }
        
        .filter-body {
            padding: 20px;
        }
    }
    
    @media (min-width: 768px) {
        .administrasi-card {
            margin-bottom: 30px;
        }
    }

    /* Minimal Administrasi Card */
    .administrasi-card {
        background: var(--bg-primary);
        border-radius: 18px;
        padding: 0;
        box-shadow: 5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
        margin-bottom: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .administrasi-card:hover {
        transform: translateY(-3px) scale(1.01);
        box-shadow: 8px 8px 20px var(--shadow-dark),
                   -8px -8px 20px var(--shadow-light);
    }

    /* Document Header */
    .administrasi-header {
        padding: 30px 25px 20px;
        background: var(--bg-primary);
        position: relative;
        text-align: center;
        flex-shrink: 0;
    }

    /* Plus Icon */
    .administrasi-icon-plus {
        display: inline-block;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 15px;
        border-radius: 50%;
        background: var(--bg-primary);
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
        color: var(--accent-primary);
    }

    /* Administrasi Title */
    .administrasi-kode {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 10px 0;
        line-height: 1.3;
        letter-spacing: -0.5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-align: center;
    }

    /* Subtitle */
    .administrasi-perihal {
        font-size: 0.95rem;
        color: var(--text-secondary);
        font-weight: 600;
        margin: 0 0 15px 0;
        letter-spacing: 0.3px;
        line-height: 1.4;
        text-align: center;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Content Section */
    .administrasi-content {
        padding: 20px 25px;
        background: var(--bg-primary);
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 12px;
    }
    
    .administrasi-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        padding: 10px 15px;
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }
    
    .meta-label {
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 0.5px;
    }
    
    .meta-value {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.85rem;
    }

    /* Footer Section */
    .administrasi-footer {
        padding: 20px 25px;
        display: flex;
        gap: 10px;
        position: relative;
        background: var(--bg-primary);
        flex-shrink: 0;
    }

    /* Action Buttons */
    .btn-action {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
        flex: 1;
        text-decoration: none !important;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .btn-detail {
        background: var(--bg-primary);
        color: var(--text-primary);
    }

    .btn-download {
        background: var(--bg-primary);
        color: var(--text-primary);
    }

    /* Jenis Badge */
    .administrasi-jenis {
        position: absolute;
        top: 15px;
        left: 15px;
        font-size: 0.6rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 10px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        background: var(--bg-primary);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 15;
    }

    /* Status & Priority Badges */
    .badge-status, .badge-prioritas {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 10px;
        display: inline-block;
        text-align: center;
        box-shadow: 2px 2px 5px var(--shadow-dark),
                   -2px -2px 5px var(--shadow-light);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--text-secondary);
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state p {
        color: var(--text-primary);
    }

    .empty-state .text-muted {
        color: var(--text-secondary) !important;
    }

    .pagination-info {
        text-align: center;
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-top: 20px;
        padding: 16px 20px;
        background: var(--bg-primary);
        border-radius: 16px;
        box-shadow: 4px 4px 12px var(--shadow-dark),
                   -4px -4px 12px var(--shadow-light);
    }

    /* Badge Colors */
    .bg-info-custom { 
        background-color: #17a2b8; 
        color: white; 
    }
    .bg-success-custom { 
        background-color: #28a745; 
        color: white; 
    }
    .bg-warning-custom { 
        background-color: #ffc107; 
        color: #333; 
    }
    .bg-danger-custom { 
        background-color: #dc3545; 
        color: white; 
    }
    .bg-secondary-custom { 
        background-color: #6c757d; 
        color: white; 
    }
    .bg-primary-custom { 
        background-color: #007bff; 
        color: white; 
    }
</style>

<div id="header-section">
    <div class="header-content">
        <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>Manajemen Administrasi</h3>
            <p>Data Dokumen & Surat Menyurat</p>
        </div>
        <a href="{{ route('administrasi.karyawan.create') }}" class="btn-add">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
</div>

<div id="content-section">
    <!-- Modern Minimalist Filter Card -->
    <div class="filter-card">
        <div class="filter-header">
            <h4><ion-icon name="search-outline" style="vertical-align: middle; margin-right: 8px;"></ion-icon> Cari Administrasi</h4>
        </div>
        
        <form action="{{ route('administrasi.karyawan.index') }}" method="GET">
            <div class="filter-body">
                <!-- Search Input -->
                <div class="mb-3">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Kode / Nomor / Perihal..." 
                           value="{{ Request('search') }}">
                </div>
                
                <!-- Row for Jenis, Status, Prioritas -->
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label class="form-label">Jenis</label>
                        <select name="jenis_administrasi" class="form-select">
                            <option value="">Semua</option>
                            @foreach($jenisAdministrasi as $key => $value)
                                <option value="{{ $key }}" {{ Request('jenis_administrasi') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ Request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ Request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ Request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="expired" {{ Request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Prioritas</label>
                        <select name="prioritas" class="form-select">
                            <option value="">Semua</option>
                            <option value="rendah" {{ Request('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="normal" {{ Request('prioritas') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="tinggi" {{ Request('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ Request('prioritas') == 'urgent' ? 'selected' : '' }}>URGENT</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-filter flex-grow-1">
                        <ion-icon name="search-outline" style="vertical-align: middle;"></ion-icon> Cari
                    </button>
                    <a href="{{ route('administrasi.karyawan.index') }}" class="btn btn-reset">
                        <ion-icon name="refresh-outline" style="vertical-align: middle;"></ion-icon>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Info -->
    <div class="stats-info">
        <ion-icon name="documents-outline" style="vertical-align: middle; margin-right: 5px; font-size: 1.2rem;"></ion-icon>
        <strong>{{ $administrasi->total() }}</strong> administrasi tersedia
    </div>

    <!-- Data Cards - A4 Portrait Style Layout -->
    <div class="row">
        @forelse($administrasi as $item)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="administrasi-card">
                <!-- Jenis Badge -->
                <span class="administrasi-jenis bg-{{ $item->getJenisAdministrasiColor() }}">
                    {{ $item->getJenisAdministrasiLabel() }}
                </span>
                
                <!-- Header Section -->
                <div class="administrasi-header">
                    <!-- Plus Icon -->
                    <div class="administrasi-icon-plus">
                        +
                    </div>
                    
                    <!-- Document Title -->
                    <h5 class="administrasi-kode">{{ $item->kode_administrasi }}</h5>
                    
                    <!-- Perihal Subtitle -->
                    <p class="administrasi-perihal">{{ Str::limit($item->perihal, 60) }}</p>
                </div>

                <!-- Content Section -->
                <div class="administrasi-content">
                    @if($item->nomor_surat)
                    <div class="administrasi-meta">
                        <span class="meta-label">Nomor Surat</span>
                        <span class="meta-value">{{ Str::limit($item->nomor_surat, 20) }}</span>
                    </div>
                    @endif

                    @if($item->isMasuk() && $item->pengirim)
                    <div class="administrasi-meta">
                        <span class="meta-label">Dari</span>
                        <span class="meta-value">{{ Str::limit($item->pengirim, 20) }}</span>
                    </div>
                    @elseif($item->isKeluar() && $item->penerima)
                    <div class="administrasi-meta">
                        <span class="meta-label">Kepada</span>
                        <span class="meta-value">{{ Str::limit($item->penerima, 20) }}</span>
                    </div>
                    @endif

                    @if($item->tanggal_surat)
                    <div class="administrasi-meta">
                        <span class="meta-label">Tanggal</span>
                        <span class="meta-value">{{ $item->tanggal_surat->format('d/m/Y') }}</span>
                    </div>
                    @endif

                    <div class="administrasi-meta">
                        <span class="meta-label">Status</span>
                        <span class="meta-value">{!! $item->getStatusBadge() !!}</span>
                    </div>

                    <div class="administrasi-meta">
                        <span class="meta-label">Prioritas</span>
                        <span class="meta-value">{!! $item->getPrioritasBadge() !!}</span>
                    </div>
                </div>

                <!-- Footer Section -->
                <div class="administrasi-footer">
                    @if($item->file_dokumen)
                    <a href="{{ route('administrasi.karyawan.download', $item->id) }}" class="btn-action btn-download">
                        <ion-icon name="download-outline" style="font-size: 1rem;"></ion-icon>
                        Download
                    </a>
                    @endif
                    <button onclick="showAdministrasiModal({{ $item->id }})" class="btn-action btn-detail">
                        <ion-icon name="eye-outline" style="font-size: 1rem;"></ion-icon>
                        Detail
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="ti ti-folder-off"></i>
                <p><strong>Belum ada data administrasi</strong></p>
                <p class="text-muted small">Data dokumen akan muncul di sini</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination Info -->
    <!-- Pagination Info -->
    @if($administrasi->hasPages())
        <div class="pagination-info">
            Halaman {{ $administrasi->currentPage() }} dari {{ $administrasi->lastPage() }}
            <br>
            <small class="text-muted">
                Menampilkan {{ $administrasi->firstItem() ?? 0 }} - {{ $administrasi->lastItem() ?? 0 }} 
                dari {{ $administrasi->total() }} data
            </small>
            <div class="mt-2">
                {{ $administrasi->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal Popup Administrasi - Neumorphic Style -->
<div id="undanganModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; overflow-y: auto; padding: 20px;">
    <div style="max-width: 600px; margin: 20px auto; background: var(--bg-primary); border-radius: 20px; box-shadow: 8px 8px 20px var(--shadow-dark), -8px -8px 20px var(--shadow-light); position: relative;">
        <!-- Close Button -->
        <button onclick="closeUndanganModal()" style="position: absolute; top: 15px; right: 15px; background: var(--bg-primary); color: var(--accent-danger); border: none; width: 35px; height: 35px; border-radius: 50%; font-size: 1.2rem; cursor: pointer; z-index: 10; box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light); transition: all 0.3s ease;">
            ×
        </button>

        <!-- Header with Icon -->
        <div style="background: var(--bg-primary); padding: 30px 20px; border-radius: 20px 20px 0 0; text-align: center; position: relative;">
            <div style="width: 80px; height: 80px; background: var(--bg-primary); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);">
                <ion-icon id="modal-icon" name="document-text-outline" style="font-size: 3rem; color: var(--accent-primary);"></ion-icon>
            </div>
            <h2 id="modal-jenis-label" style="color: var(--text-primary); margin: 0; font-size: 1.5rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">
                ADMINISTRASI
            </h2>
            <div style="width: 60px; height: 3px; background: var(--accent-primary); margin: 10px auto 0; border-radius: 2px;"></div>
        </div>

        <!-- Content -->
        <div id="modalContent" style="padding: 30px; background: var(--bg-primary);">
            <!-- Kode & Nomor -->
            <div style="text-align: center; margin-bottom: 25px; padding: 15px; background: var(--bg-primary); border-radius: 12px; box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);">
                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Nomor</div>
                <div id="modal-kode" style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);"></div>
            </div>

            <!-- Perihal -->
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Perihal</div>
                <h3 id="modal-perihal" style="color: var(--text-primary); margin: 0; font-size: 1.3rem; font-weight: 700; line-height: 1.4;"></h3>
            </div>

            <!-- Detail Acara Box -->
            <div id="detail-acara-box" style="display: none;">
                <div style="background: var(--bg-primary); padding: 25px; border-radius: 15px; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light); position: relative; margin-bottom: 25px;">
                    <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--accent-primary); color: white; padding: 5px 20px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;">
                        DETAIL ACARA
                    </div>

                    <!-- Nama Acara -->
                    <div id="nama-acara-section" style="text-align: center; margin: 15px 0 25px; padding-top: 10px;">
                        <div style="font-size: 1.4rem; font-weight: 700; color: var(--text-primary); line-height: 1.3;" id="modal-nama-acara"></div>
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                        <div style="flex: 1; background: var(--bg-primary); padding: 15px; border-radius: 12px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <ion-icon name="calendar" style="font-size: 1.3rem; color: var(--accent-primary);"></ion-icon>
                                <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Tanggal</span>
                            </div>
                            <div id="modal-tanggal-acara" style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary); line-height: 1.4;"></div>
                        </div>
                        <div id="waktu-section" style="flex: 1; background: var(--bg-primary); padding: 15px; border-radius: 12px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); display: none;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <ion-icon name="time" style="font-size: 1.3rem; color: var(--accent-primary);"></ion-icon>
                                <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Waktu</span>
                            </div>
                            <div id="modal-waktu-acara" style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary);"></div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div id="lokasi-section" style="background: var(--bg-primary); padding: 15px; border-radius: 12px; margin-bottom: 15px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <ion-icon name="location" style="font-size: 1.3rem; color: var(--accent-primary);"></ion-icon>
                            <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Lokasi</span>
                        </div>
                        <div id="modal-lokasi-acara" style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary); margin-bottom: 5px;"></div>
                        <div id="modal-alamat-acara" style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.4;"></div>
                    </div>

                    <!-- Dress Code -->
                    <div id="dress-code-section" style="text-align: center; display: none;">
                        <span style="background: var(--accent-primary); color: white; padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                            <ion-icon name="shirt-outline" style="font-size: 1.1rem;"></ion-icon>
                            <span id="modal-dress-code"></span>
                        </span>
                    </div>
                </div>

                <!-- Catatan Acara -->
                <div id="catatan-acara-section" style="background: var(--bg-primary); padding: 15px; border-radius: 12px; border-left: 4px solid var(--accent-warning); margin-bottom: 20px; display: none; box-shadow: 3px 3px 8px var(--shadow-dark), -3px -3px 8px var(--shadow-light);">
                    <div style="display: flex; align-items: start; gap: 10px;">
                        <ion-icon name="information-circle" style="font-size: 1.5rem; color: var(--accent-warning); flex-shrink: 0;"></ion-icon>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 5px; font-size: 0.85rem;">Catatan Penting:</div>
                            <div id="modal-catatan-acara" style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Tambahan -->
            <div style="background: var(--bg-primary); padding: 20px; border-radius: 12px; box-shadow: inset 3px 3px 8px var(--shadow-dark), inset -3px -3px 8px var(--shadow-light); margin-bottom: 20px;">
                <div id="modal-pengirim-section" style="margin-bottom: 15px; display: none;">
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 5px;">Dari:</div>
                    <div id="modal-pengirim" style="font-weight: 600; color: var(--text-primary);"></div>
                </div>
                <div id="modal-penerima-section" style="margin-bottom: 15px; display: none;">
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 5px;">Kepada:</div>
                    <div id="modal-penerima" style="font-weight: 600; color: var(--text-primary);"></div>
                </div>
                <div id="modal-tanggal-surat-section">
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 5px;">Tanggal Surat:</div>
                    <div id="modal-tanggal-surat" style="font-weight: 600; color: var(--text-primary);"></div>
                </div>
            </div>

            <!-- Ringkasan -->
            <div id="modal-ringkasan-section" style="background: var(--bg-primary); padding: 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid var(--accent-primary); box-shadow: 3px 3px 8px var(--shadow-dark), -3px -3px 8px var(--shadow-light); display: none;">
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 10px; font-weight: 600;">Ringkasan:</div>
                <div id="modal-ringkasan" style="color: var(--text-primary); line-height: 1.6; text-align: justify; font-size: 0.9rem;"></div>
            </div>

            <!-- Status & Prioritas -->
            <div style="display: flex; gap: 10px; justify-content: center;">
                <div id="modal-prioritas" style="padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);"></div>
                <div id="modal-status" style="padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);"></div>
            </div>
        </div>

        <!-- Footer with Actions -->
        <div style="background: var(--bg-primary); padding: 20px; border-radius: 0 0 20px 20px; text-align: center; position: relative;">
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button id="modal-btn-download" onclick="downloadFile()" style="background: var(--bg-primary); color: var(--text-primary); padding: 12px 30px; border-radius: 14px; border: none; font-weight: 600; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light); transition: all 0.3s; cursor: pointer;">
                    <ion-icon name="download" style="font-size: 1.1rem; vertical-align: middle; margin-right: 5px;"></ion-icon>
                    Download
                </button>
                <button id="modal-btn-detail-full" onclick="goToDetail()" style="background: var(--bg-primary); color: var(--text-primary); padding: 12px 30px; border-radius: 14px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light);">
                    <ion-icon name="document-text" style="font-size: 1.1rem; vertical-align: middle; margin-right: 5px;"></ion-icon>
                    Detail Lengkap
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    const administrasiData = @json($administrasi->items());

    function showAdministrasiModal(id) {
        const data = administrasiData.find(item => item.id === id);
        if (!data) return;

        // Reset visibility
        document.getElementById('detail-acara-box').style.display = 'none';
        document.getElementById('waktu-section').style.display = 'none';
        document.getElementById('dress-code-section').style.display = 'none';
        document.getElementById('catatan-acara-section').style.display = 'none';
        document.getElementById('modal-pengirim-section').style.display = 'none';
        document.getElementById('modal-penerima-section').style.display = 'none';
        document.getElementById('modal-ringkasan-section').style.display = 'none';
        document.getElementById('modal-btn-download').style.display = 'inline-block';

        // Set Icon & Jenis Label based on type
        const modalIcon = document.getElementById('modal-icon');
        const jenisMap = {
            'surat_masuk': { label: 'SURAT MASUK', icon: 'mail-outline' },
            'surat_keluar': { label: 'SURAT KELUAR', icon: 'paper-plane-outline' },
            'undangan_masuk': { label: 'SURAT UNDANGAN', icon: 'mail-open-outline' },
            'undangan_keluar': { label: 'UNDANGAN KELUAR', icon: 'calendar-outline' },
            'memo': { label: 'MEMO', icon: 'clipboard-outline' },
            'sk': { label: 'SURAT KEPUTUSAN', icon: 'ribbon-outline' },
            'berita_acara': { label: 'BERITA ACARA', icon: 'newspaper-outline' },
            'laporan': { label: 'LAPORAN', icon: 'document-text-outline' },
            'notulensi': { label: 'NOTULENSI', icon: 'create-outline' },
            'surat_tugas': { label: 'SURAT TUGAS', icon: 'briefcase-outline' },
            'surat_izin': { label: 'SURAT IZIN', icon: 'checkmark-circle-outline' },
            'surat_keterangan': { label: 'SURAT KETERANGAN', icon: 'information-circle-outline' },
            'kontrak': { label: 'KONTRAK', icon: 'document-attach-outline' },
            'mou': { label: 'MOU', icon: 'handshake-outline' },
            'spo': { label: 'SPO', icon: 'list-outline' },
            'lainnya': { label: 'LAINNYA', icon: 'folder-outline' }
        };

        const jenisInfo = jenisMap[data.jenis_administrasi] || { label: 'ADMINISTRASI', icon: 'document-outline' };
        document.getElementById('modal-jenis-label').textContent = jenisInfo.label;
        modalIcon.setAttribute('name', jenisInfo.icon);

        // Set Basic Info
        document.getElementById('modal-kode').textContent = data.kode_administrasi;
        if (data.nomor_surat) {
            document.getElementById('modal-kode').textContent += ' | ' + data.nomor_surat;
        }
        document.getElementById('modal-perihal').textContent = data.perihal;

        // Detail Acara
        if (data.nama_acara) {
            document.getElementById('detail-acara-box').style.display = 'block';
            document.getElementById('modal-nama-acara').textContent = data.nama_acara;

            // Tanggal
            if (data.tanggal_acara_mulai) {
                const mulai = new Date(data.tanggal_acara_mulai);
                let tanggalText = mulai.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                
                if (data.tanggal_acara_selesai && data.tanggal_acara_selesai !== data.tanggal_acara_mulai) {
                    const selesai = new Date(data.tanggal_acara_selesai);
                    tanggalText += '<br><small style="color: #666;">sampai</small><br>' + 
                                   selesai.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                }
                document.getElementById('modal-tanggal-acara').innerHTML = tanggalText;
            }

            // Waktu
            if (data.waktu_acara_mulai || data.waktu_acara_selesai) {
                document.getElementById('waktu-section').style.display = 'block';
                let waktuText = data.waktu_acara_mulai ? data.waktu_acara_mulai.substring(0, 5) : '';
                if (data.waktu_acara_selesai) {
                    waktuText += ' - ' + data.waktu_acara_selesai.substring(0, 5);
                }
                waktuText += ' WIB';
                document.getElementById('modal-waktu-acara').textContent = waktuText;
            }

            // Lokasi
            if (data.lokasi_acara) {
                document.getElementById('modal-lokasi-acara').textContent = data.lokasi_acara;
                if (data.alamat_acara) {
                    document.getElementById('modal-alamat-acara').textContent = data.alamat_acara;
                } else {
                    document.getElementById('modal-alamat-acara').style.display = 'none';
                }
            }

            // Dress Code
            if (data.dress_code) {
                document.getElementById('dress-code-section').style.display = 'block';
                document.getElementById('modal-dress-code').textContent = data.dress_code;
            }

            // Catatan Acara
            if (data.catatan_acara) {
                document.getElementById('catatan-acara-section').style.display = 'block';
                document.getElementById('modal-catatan-acara').textContent = data.catatan_acara;
            }
        }

        // Pengirim/Penerima
        if (data.pengirim) {
            document.getElementById('modal-pengirim-section').style.display = 'block';
            document.getElementById('modal-pengirim').textContent = data.pengirim;
        }
        if (data.penerima) {
            document.getElementById('modal-penerima-section').style.display = 'block';
            document.getElementById('modal-penerima').textContent = data.penerima;
        }

        // Tanggal Surat
        if (data.tanggal_surat) {
            const tglSurat = new Date(data.tanggal_surat);
            document.getElementById('modal-tanggal-surat').textContent = tglSurat.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        // Ringkasan
        if (data.ringkasan) {
            document.getElementById('modal-ringkasan-section').style.display = 'block';
            document.getElementById('modal-ringkasan').textContent = data.ringkasan;
        }

        // Prioritas & Status
        const prioritasColors = {
            'rendah': { bg: '#6c757d', text: 'white' },
            'normal': { bg: '#17a2b8', text: 'white' },
            'tinggi': { bg: '#ffc107', text: '#333' },
            'urgent': { bg: '#dc3545', text: 'white' }
        };
        const statusColors = {
            'pending': { bg: '#ffc107', text: '#333' },
            'proses': { bg: '#17a2b8', text: 'white' },
            'selesai': { bg: '#28a745', text: 'white' },
            'ditolak': { bg: '#dc3545', text: 'white' },
            'expired': { bg: '#6c757d', text: 'white' }
        };

        const prioritasEl = document.getElementById('modal-prioritas');
        prioritasEl.textContent = data.prioritas.toUpperCase();
        prioritasEl.style.background = prioritasColors[data.prioritas].bg;
        prioritasEl.style.color = prioritasColors[data.prioritas].text;

        const statusEl = document.getElementById('modal-status');
        statusEl.textContent = data.status.toUpperCase();
        statusEl.style.background = statusColors[data.status].bg;
        statusEl.style.color = statusColors[data.status].text;

        // Store current ID for buttons
        window.currentAdministrasiId = data.id;
        window.currentHasFile = data.file_dokumen ? true : false;

        // Show/hide download button
        if (data.file_dokumen) {
            document.getElementById('modal-btn-download').style.display = 'inline-block';
        } else {
            document.getElementById('modal-btn-download').style.display = 'none';
        }

        // Show Modal
        document.getElementById('undanganModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeUndanganModal() {
        document.getElementById('undanganModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function goToDetail() {
        if (window.currentAdministrasiId) {
            window.location.href = `{{ url('/administrasi/karyawan') }}/${window.currentAdministrasiId}`;
        }
    }

    function downloadFile() {
        if (window.currentAdministrasiId && window.currentHasFile) {
            window.location.href = `{{ url('/administrasi/karyawan') }}/${window.currentAdministrasiId}/download`;
        }
    }

    // Close on outside click
    document.getElementById('undanganModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUndanganModal();
        }
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end',
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@endpush
