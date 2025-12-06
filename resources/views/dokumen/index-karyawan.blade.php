@extends('layouts.mobile.app')
@section('titlepage', 'Manajemen Dokumen')

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
        .dokumen-card {
            margin-bottom: 30px;
        }
    }

    /* Minimal Document Card */
    .dokumen-card {
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
        aspect-ratio: 1 / 1.414;
        max-width: 100%;
    }

    .dokumen-card:hover {
        transform: translateY(-3px) scale(1.01);
        box-shadow: 8px 8px 20px var(--shadow-dark),
                   -8px -8px 20px var(--shadow-light);
    }

    /* Color Bar - Hidden */
    .dokumen-color-bar {
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 0;
        z-index: 10;
        display: none;
    }

    /* Document Header */
    .dokumen-header {
        padding: 30px 25px 20px;
        background: var(--bg-primary);
        position: relative;
        text-align: center;
        flex-shrink: 0;
    }

    /* Plus Icon */
    .dokumen-icon-plus {
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
    }
    
    /* Lock Icon for View Only Documents */
    .dokumen-icon-lock {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: var(--bg-primary);
        border-radius: 50%;
        color: #FF9800;
        z-index: 15;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
    }

    /* Document Title */
    .dokumen-nama {
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
    .dokumen-subtitle {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 600;
        margin: 0 0 15px 0;
        letter-spacing: 0.3px;
        line-height: 1.4;
        text-align: center;
    }

    /* Content Section */
    .dokumen-content {
        padding: 20px 25px;
        background: var(--bg-primary);
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 12px;
    }
    
    .dokumen-meta-item {
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
    .dokumen-footer {
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

    /* Jenis Badge */
    .jenis-badge {
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
        background: #1a1a1a;
        color: #ffffff;
    }

    /* New Badge */
    .badge-new {
        position: absolute;
        top: 10px;
        left: -5px;
        background: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
        color: white;
        font-size: 0.6rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 0 12px 12px 0;
        box-shadow: 4px 4px 12px rgba(16, 185, 129, 0.4),
                   -2px -2px 4px rgba(255, 255, 255, 0.1);
        z-index: 20;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    .pagination-info {
        text-align: center;
        color: #666;
        font-size: 0.85rem;
        margin-top: 20px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .stats-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 8px;
        background: #f8f9fa;
        color: #666;
        margin-right: 5px;
    }

    /* Badge Colors */
    .bg-aktif { background-color: #28a745; color: white; }
    .bg-arsip { background-color: #6c757d; color: white; }
    .bg-kadaluarsa { background-color: #dc3545; color: white; }
    .bg-public { background-color: #28a745; color: white; }
    .bg-view-only { background-color: #ffc107; color: #333; }
    .bg-restricted { background-color: #dc3545; color: white; }
    
    /* Jenis Dokumen Badge */
    .jenis-badge {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    
    .jenis-badge.badge-file {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .jenis-badge.badge-link {
        background: #fce4ec;
        color: #c2185b;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    /* Modal Styles */
    .modal {
        z-index: 9999 !important;
    }
    
    .modal-backdrop {
        z-index: 9998 !important;
    }

    /* Close Button Hover Effect */
    button[data-bs-dismiss="modal"]:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light) !important;
    }

    button[data-bs-dismiss="modal"]:active {
        transform: translateY(0);
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light) !important;
    }
</style>

<div id="header-section">
    <div class="header-content">
        <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>Manajemen Dokumen</h3>
            <p>Dokumen & Arsip Perusahaan</p>
        </div>
    </div>
</div>

<div id="content-section">
    <!-- Modern Minimalist Filter Card -->
    <div class="filter-card">
        <div class="filter-header">
            <h4><ion-icon name="search-outline" style="vertical-align: middle; margin-right: 8px;"></ion-icon> Cari Dokumen</h4>
        </div>
        
        <form action="{{ route('dokumen.karyawan.index') }}" method="GET">
            <div class="filter-body">
                <!-- Single Row Filter -->
                <div class="row g-3 align-items-end">
                    <!-- Search Input -->
                    <div class="col-md-4">
                        <label class="form-label">Pencarian</label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Kode / Nama / Loker..." 
                               value="{{ Request('search') }}">
                    </div>
                    
                    <!-- Category Select -->
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">Semua</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ Request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Select -->
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="aktif" {{ Request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="arsip" {{ Request('status') == 'arsip' ? 'selected' : '' }}>Arsip</option>
                        </select>
                    </div>

                    <!-- Loker Input -->
                    <div class="col-md-2">
                        <label class="form-label">No. Loker</label>
                        <input type="text" 
                               name="nomor_loker" 
                               class="form-control" 
                               placeholder="L001..." 
                               value="{{ Request('nomor_loker') }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-filter" style="flex: 1;">
                                <ion-icon name="search-outline" style="vertical-align: middle;"></ion-icon>
                            </button>
                            <a href="{{ route('dokumen.karyawan.index') }}" class="btn btn-reset">
                                <ion-icon name="refresh-outline" style="vertical-align: middle;"></ion-icon>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Info -->
    <div class="stats-info">
        <ion-icon name="documents-outline" style="vertical-align: middle; margin-right: 5px; font-size: 1.2rem;"></ion-icon>
        <strong>{{ $documents->total() }}</strong> dokumen tersedia
    </div>

    <!-- Data Cards - A4 Portrait Style Layout -->
    <div class="row">
        @forelse($documents as $doc)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="dokumen-card">
                <!-- Color Bar - Each category has different color -->
                <div class="dokumen-color-bar" style="background-color: {{ $doc->category->warna }};"></div>
                
                <!-- Badge: NEW for recent documents -->
                @if($doc->created_at && $doc->created_at->diffInDays(now()) <= 7)
                    <span class="badge-new" style="top: 15px; left: 0px;">
                        <ion-icon name="sparkles-outline" style="font-size: 0.7rem;"></ion-icon> NEW
                    </span>
                @endif
                
                <!-- Lock Icon for View Only Documents -->
                @if($doc->access_level === 'view_only')
                <div class="dokumen-icon-lock" title="View Only - Tidak dapat diunduh">
                    <ion-icon name="lock-closed"></ion-icon>
                </div>
                @endif
                
                <!-- Header Section -->
                <div class="dokumen-header">
                    <!-- Plus Icon -->
                    <div class="dokumen-icon-plus" style="color: {{ $doc->category->warna }};">
                        +
                    </div>
                    
                    <!-- Document Title -->
                    <h5 class="dokumen-nama">{{ $doc->nama_dokumen }}</h5>
                    
                    <!-- Category Subtitle -->
                    <p class="dokumen-subtitle">{{ $doc->category->nama_kategori }}</p>
                    
                    <!-- Jenis Badge: FILE or LINK - Moved under title -->
                    <div style="text-align: center; margin-top: 10px;">
                        @if($doc->jenis_dokumen === 'link')
                            <span class="jenis-badge" style="position: relative; top: auto; left: auto; display: inline-block;">
                                <ion-icon name="link-outline"></ion-icon> LINK
                            </span>
                        @else
                            <span class="jenis-badge" style="position: relative; top: auto; left: auto; display: inline-block;">
                                <ion-icon name="document-attach-outline"></ion-icon> FILE
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Content Section - Compact Info -->
                <div class="dokumen-content">
                    @if($doc->kode_dokumen)
                    <div class="dokumen-meta-item">
                        <span class="meta-label">Kode</span>
                        <span class="meta-value" style="color: {{ $doc->category->warna }};">{{ $doc->kode_dokumen }}</span>
                    </div>
                    @endif
                    
                    @if($doc->tanggal_dokumen)
                    <div class="dokumen-meta-item">
                        <span class="meta-label">Tanggal</span>
                        <span class="meta-value">{{ $doc->tanggal_dokumen->format('d M Y') }}</span>
                    </div>
                    @endif
                    
                    <div class="dokumen-meta-item">
                        <span class="meta-label">Status</span>
                        <span class="badge bg-{{ strtolower($doc->status) }}" style="font-size: 0.7rem; padding: 5px 12px; border-radius: 15px;">{{ ucfirst($doc->status) }}</span>
                    </div>
                </div>
                
                <!-- Footer Section - Action Buttons -->
                <div class="dokumen-footer">
                    <!-- Preview Button (Eye Icon) - Show document content in popup -->
                    <button onclick="previewDokumen({{ $doc->id }})" 
                            class="btn-action" 
                            title="Preview Dokumen">
                        <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                    </button>
                    
                    <!-- Download Button - Only if downloadable -->
                    @if($doc->access_level != 'view_only' && $doc->jenis_dokumen != 'link')
                    <a href="{{ route('dokumen.karyawan.download', $doc->id) }}" 
                       class="btn-action"
                       title="Download Dokumen">
                        <ion-icon name="download-outline" style="font-size: 18px;"></ion-icon>
                    </a>
                    @endif
                    
                    <!-- Link Button - Only for link type documents -->
                    @if($doc->jenis_dokumen === 'link' && $doc->link_url)
                    <a href="{{ $doc->link_url }}" 
                       target="_blank"
                       class="btn-action"
                       title="Buka Link Eksternal">
                        <ion-icon name="link-outline" style="font-size: 18px;"></ion-icon>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="ti ti-folder-off"></i>
                    <p><strong>Belum ada dokumen</strong></p>
                    <p class="text-muted small">Dokumen yang dapat diakses akan muncul di sini</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Info -->
    @if($documents->hasPages())
        <div class="pagination-info">
            Halaman {{ $documents->currentPage() }} dari {{ $documents->lastPage() }}
            <br>
            <div class="d-flex justify-content-center gap-2 mt-2">
                @if($documents->currentPage() > 1)
                    <a href="{{ $documents->previousPageUrl() }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-chevron-left"></i> Sebelumnya
                    </a>
                @endif
                @if($documents->hasMorePages())
                    <a href="{{ $documents->nextPageUrl() }}" class="btn btn-sm btn-outline-secondary">
                        Selanjutnya <i class="ti ti-chevron-right"></i>
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Modal Preview Dokumen - Large Modal with Document Viewer -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 0; max-height: 90vh;">
            <div class="modal-header" style="background: #ffffff; color: #333; border-bottom: 1px solid #dee2e6; padding: 10px 20px;">
                <h5 class="modal-title" style="font-size: 0.9rem; font-weight: 600;">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewModalBody" style="padding: 0; overflow: hidden;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat dokumen...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Dokumen - Fullscreen Neumorphic -->
<div class="modal fade" id="dokumenModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: var(--bg-primary); border: none;">
            <div class="modal-header" style="background: var(--bg-primary); border: none; padding: 20px 30px; box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light); border-radius: 0; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); closeDokumenModal();" aria-label="Close" style="background: var(--bg-primary); width: 45px; height: 45px; border-radius: 12px; box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light); opacity: 1; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; position: relative; padding: 0; margin: 0; transition: all 0.3s ease; z-index: 10;">
                    <ion-icon name="arrow-back-outline" style="font-size: 24px; color: var(--text-primary); pointer-events: none;"></ion-icon>
                </button>
                <h5 class="modal-title" style="color: var(--text-primary); font-weight: 700; display: flex; align-items: center; gap: 10px; margin: 0; flex: 1; justify-content: center;">
                    <ion-icon name="document-text-outline" style="font-size: 24px;"></ion-icon>
                    Preview Dokumen
                </h5>
                <div style="width: 45px;"></div>
            </div>
            <div class="modal-body" id="dokumenModalBody" style="padding: 0; background: var(--bg-primary);">
                <div class="text-center py-5">
                    <div class="spinner-border" style="color: var(--text-primary);" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Function to close dokumen modal
    function closeDokumenModal() {
        console.log('Close modal clicked');
        const modalElement = document.getElementById('dokumenModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        
        console.log('Modal element:', modalElement);
        console.log('Modal instance:', modal);
        
        if (modal) {
            console.log('Hiding with Bootstrap');
            modal.hide();
        } else {
            console.log('Hiding manually');
            // If no instance, hide manually
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
        // Clear modal content
        setTimeout(() => {
            document.getElementById('dokumenModalBody').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border" style="color: var(--text-primary);" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
        }, 300);
    }

    // Function Preview Dokumen - Show document content in modal
    function previewDokumen(id) {
        // Load document data via AJAX first to check type
        fetch(`/fasilitas/dokumen-karyawan/${id}/show`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const doc = data.document;
                    
                    // Check document type - If link, redirect immediately without showing modal
                    if(doc.jenis_dokumen === 'link' && doc.link_url) {
                        // Redirect to link in new tab
                        window.open(doc.link_url, '_blank');
                        return;
                    }
                    
                    // For file documents, show modal with viewer
                    var myModal = new bootstrap.Modal(document.getElementById('dokumenModal'));
                    myModal.show();
                    
                    let html = '';
                    
                    // Document Viewer Container - Full screen
                    html += '<div style="background: var(--bg-primary); min-height: 100vh; height: 100vh; overflow: hidden;">';
                    
                    // File type - Show file viewer based on extension
                    if(doc.file_path) {
                        const fileUrl = '/storage/' + doc.file_path;
                        const fileExt = doc.file_path.split('.').pop().toLowerCase();
                        
                        if(['pdf'].includes(fileExt)) {
                            // PDF Viewer - Full height without toolbar
                            html += `
                                <iframe src="${fileUrl}#toolbar=0&navpanes=0&scrollbar=1" 
                                        style="width: 100%; height: 100vh; border: none; background: var(--bg-primary);"></iframe>
                            `;
                        } else if(['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(fileExt)) {
                            // Image Viewer
                            html += `
                                <div class="text-center" style="background: var(--bg-primary); padding: 40px; height: 100vh; display: flex; align-items: center; justify-content: center;">
                                    <img src="${fileUrl}" 
                                         alt="${doc.nama_dokumen}"
                                         style="max-width: 90%; max-height: 90vh; height: auto; border-radius: 20px; box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);">
                                </div>
                            `;
                            } else if(['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(fileExt)) {
                                // Office document - Use Google Docs Viewer
                                const fullUrl = window.location.origin + fileUrl;
                                html += `
                                    <iframe src="https://docs.google.com/gview?url=${encodeURIComponent(fullUrl)}&embedded=true" 
                                            style="width: 100%; height: 100vh; border: none; background: var(--bg-primary);"></iframe>
                                `;
                            } else {
                                // Other file types - Show download option
                                html += `
                                    <div class="text-center" style="background: var(--bg-primary); padding: 80px 40px; height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                        <div style="width: 150px; height: 150px; border-radius: 50%; background: var(--bg-primary); box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light); display: flex; align-items: center; justify-content: center; margin-bottom: 30px;">
                                            <ion-icon name="document-text-outline" style="font-size: 80px; color: var(--icon-color);"></ion-icon>
                                        </div>
                                        <h5 class="mt-3" style="color: var(--text-primary); font-weight: 700;">${doc.file_name || 'Dokumen'}</h5>
                                        <p style="color: var(--text-secondary); margin-top: 10px;">Tipe: ${fileExt.toUpperCase()}</p>
                                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Preview tidak tersedia untuk tipe file ini</p>
                                        ${doc.access_level !== 'view_only' ? 
                                            '<a href="/fasilitas/dokumen-karyawan/' + doc.id + '/download" style="margin-top: 30px; padding: 15px 40px; background: var(--bg-primary); color: var(--text-primary); border-radius: 15px; text-decoration: none; font-weight: 600; box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light); display: inline-flex; align-items: center; gap: 10px;"><ion-icon name="download-outline" style="font-size: 20px;"></ion-icon>Download File</a>' 
                                            : '<p style="color: #FF9800; margin-top: 30px; padding: 15px 30px; background: var(--bg-primary); border-radius: 15px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);"><ion-icon name="lock-closed" style="vertical-align: middle;"></ion-icon> View Only - Download tidak tersedia</p>'}
                                    </div>
                                `;
                            }
                        } else {
                            html += '<div class="alert alert-warning m-4">File tidak ditemukan</div>';
                        }
                    
                    html += '</div>'; // Close viewer container
                    
                    document.getElementById('dokumenModalBody').innerHTML = html;
                } else {
                    document.getElementById('dokumenModalBody').innerHTML = `
                        <div style="text-align: center; padding: 80px 40px; color: var(--text-secondary);">
                            <ion-icon name="alert-circle-outline" style="font-size: 80px; margin-bottom: 20px;"></ion-icon>
                            <p>${data.message || 'Dokumen tidak ditemukan'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('dokumenModalBody').innerHTML = `
                    <div style="text-align: center; padding: 80px 40px; color: var(--text-secondary);">
                        <ion-icon name="alert-circle-outline" style="font-size: 80px; margin-bottom: 20px; color: #dc3545;"></ion-icon>
                        <p>Gagal memuat dokumen</p>
                    </div>
                `;
            });
    }
    
    function showDokumenModal(id) {
        // Tampilkan modal
        var myModal = new bootstrap.Modal(document.getElementById('dokumenModal'));
        myModal.show();
        
        // Set loading state
        document.getElementById('dokumenModalBody').innerHTML = `
            <div style="text-align: center; padding: 80px 40px;">
                <div class="spinner-border" style="color: var(--text-primary);" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p style="margin-top: 20px; color: var(--text-secondary);">Memuat data...</p>
            </div>
        `;
        
        // Load detail via AJAX
        fetch(`/fasilitas/dokumen-karyawan/${id}/show`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const doc = data.document;
                    let html = `
                        <div class="mb-3">
                            <strong style="color: #32745e;">Kode Dokumen:</strong><br>
                            <span class="badge bg-primary">${doc.kode_dokumen}</span>
                        </div>
                        <div class="mb-3">
                            <strong style="color: #32745e;">Nama Dokumen:</strong><br>
                            ${doc.nama_dokumen}
                        </div>
                        <div class="mb-3">
                            <strong style="color: #32745e;">Kategori:</strong><br>
                            <span class="badge" style="background-color: ${doc.category.warna};">
                                ${doc.category.nama_kategori}
                            </span>
                        </div>
                    `;
                    
                    // Tampilkan jenis dokumen dengan badge
                    if(doc.jenis_dokumen) {
                        const isLink = doc.jenis_dokumen === 'link';
                        const badgeClass = isLink ? 'badge-link' : 'badge-file';
                        const iconName = isLink ? 'link-outline' : 'document-attach-outline';
                        const jenisText = isLink ? 'Link Eksternal' : 'File Dokumen';
                        
                        html += `
                            <div class="mb-3">
                                <strong style="color: #32745e;">Jenis Dokumen:</strong><br>
                                <span class="jenis-badge ${badgeClass}">
                                    <ion-icon name="${iconName}"></ion-icon> ${jenisText}
                                </span>
                            </div>
                        `;
                        
                        // Jika link, tampilkan URL-nya
                        if(isLink && doc.link_url) {
                            html += `
                                <div class="mb-3">
                                    <strong style="color: #32745e;">Link URL:</strong><br>
                                    <a href="${doc.link_url}" target="_blank" style="color: #32745e; word-break: break-all;">
                                        <ion-icon name="open-outline"></ion-icon> ${doc.link_url}
                                    </a>
                                </div>
                            `;
                        }
                    }
                    
                    if(doc.deskripsi) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #32745e;">Deskripsi:</strong><br>
                                ${doc.deskripsi}
                            </div>
                        `;
                    }
                    
                    if(doc.nomor_loker) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #32745e;">Lokasi Fisik:</strong><br>
                                <i class="ti ti-archive"></i> Loker: ${doc.nomor_loker}<br>
                        `;
                        if(doc.lokasi_loker) html += `Lokasi: ${doc.lokasi_loker}<br>`;
                        if(doc.rak) html += `Rak: ${doc.rak}`;
                        if(doc.baris) html += ` / Baris: ${doc.baris}`;
                        html += `</div>`;
                    }
                    
                    if(doc.nomor_referensi) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #32745e;">Nomor Referensi:</strong><br>
                                ${doc.nomor_referensi}
                            </div>
                        `;
                    }
                    
                    if(doc.penerbit) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #32745e;">Penerbit:</strong><br>
                                ${doc.penerbit}
                            </div>
                        `;
                    }
                    
                    if(doc.tanggal_dokumen) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #32745e;">Tanggal Dokumen:</strong><br>
                                ${doc.tanggal_dokumen_formatted}
                            </div>
                        `;
                    }
                    
                    html += `
                        <div class="mb-3">
                            <strong style="color: #32745e;">Status:</strong><br>
                            <span class="badge bg-${doc.status.toLowerCase()}">${doc.status}</span>
                        </div>
                        <div class="mb-3">
                            <strong style="color: #32745e;">Statistik:</strong><br>
                            <i class="ti ti-eye"></i> ${doc.jumlah_view} views &nbsp;
                            <i class="ti ti-download"></i> ${doc.jumlah_download} downloads
                        </div>
                    `;
                    
                    document.getElementById('dokumenModalBody').innerHTML = html;
                } else {
                    document.getElementById('dokumenModalBody').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-circle"></i> ${data.message || 'Data tidak ditemukan'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('dokumenModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle"></i> Gagal memuat detail dokumen
                    </div>
                `;
            });
    }
    
    // Function for View Only documents - Special popup
    function showViewOnlyModal(id) {
        // Tampilkan modal
        var myModal = new bootstrap.Modal(document.getElementById('dokumenModal'));
        
        // Update modal title
        document.querySelector('#dokumenModal .modal-title').innerHTML = '<ion-icon name="eye-outline"></ion-icon> View Only Document';
        
        myModal.show();
        
        // Set loading state
        document.getElementById('dokumenModalBody').innerHTML = `
            <div style="text-align: center; padding: 80px 40px;">
                <div class="spinner-border" style="color: #FF9800;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p style="margin-top: 20px; color: var(--text-secondary);">Memuat dokumen...</p>
            </div>
        `;
        
        // Load detail via AJAX
        fetch(`/fasilitas/dokumen-karyawan/${id}/show`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const doc = data.document;
                    let html = `
                        <div class="alert alert-warning" style="background: #FFF3CD; border: 2px solid #FF6B00;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <ion-icon name="lock-closed" style="font-size: 2rem; color: #FF6B00;"></ion-icon>
                                <div>
                                    <strong style="color: #FF6B00;">Dokumen View Only</strong>
                                    <p style="margin: 0; font-size: 0.85rem; color: #666;">
                                        Anda hanya dapat melihat detail dokumen ini. Download tidak tersedia.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <strong style="color: #FF6B00;">Kode Dokumen:</strong><br>
                            <span class="badge" style="background: #FF6B00; color: white;">${doc.kode_dokumen}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong style="color: #FF6B00;">Nama Dokumen:</strong><br>
                            <h5 style="color: #1a1a1a; font-weight: 600;">${doc.nama_dokumen}</h5>
                        </div>
                        
                        <div class="mb-3">
                            <strong style="color: #FF6B00;">Kategori:</strong><br>
                            <span class="badge" style="background-color: ${doc.category.warna};">
                                ${doc.category.nama_kategori}
                            </span>
                        </div>
                    `;
                    
                    if(doc.deskripsi) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #FF6B00;">Deskripsi:</strong><br>
                                <p style="color: #666; margin: 5px 0 0 0;">${doc.deskripsi}</p>
                            </div>
                        `;
                    }
                    
                    if(doc.nomor_loker) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #FF6B00;">Lokasi Fisik:</strong><br>
                                <i class="ti ti-archive"></i> Loker: <strong>${doc.nomor_loker}</strong><br>
                        `;
                        if(doc.lokasi_loker) html += `<i class="ti ti-map-pin"></i> ${doc.lokasi_loker}<br>`;
                        if(doc.rak) html += `<i class="ti ti-box"></i> Rak: ${doc.rak}`;
                        if(doc.baris) html += ` / Baris: ${doc.baris}`;
                        html += `</div>`;
                    }
                    
                    if(doc.nomor_referensi) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #FF6B00;">Nomor Referensi:</strong><br>
                                ${doc.nomor_referensi}
                            </div>
                        `;
                    }
                    
                    if(doc.penerbit) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #FF6B00;">Penerbit:</strong><br>
                                ${doc.penerbit}
                            </div>
                        `;
                    }
                    
                    if(doc.tanggal_dokumen_formatted) {
                        html += `
                            <div class="mb-3">
                                <strong style="color: #FF6B00;">Tanggal Dokumen:</strong><br>
                                <i class="ti ti-calendar"></i> ${doc.tanggal_dokumen_formatted}
                            </div>
                        `;
                    }
                    
                    html += `
                        <div class="mb-3">
                            <strong style="color: #FF6B00;">Status:</strong><br>
                            <span class="badge bg-${doc.status.toLowerCase()}">${doc.status}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong style="color: #FF6B00;">Statistik:</strong><br>
                            <div style="display: flex; gap: 15px; margin-top: 10px;">
                                <div style="background: #f8f9fa; padding: 10px 15px; border-radius: 8px; flex: 1; text-align: center;">
                                    <i class="ti ti-eye" style="font-size: 1.5rem; color: #FF6B00;"></i>
                                    <div style="font-weight: 600; margin-top: 5px;">${doc.jumlah_view}</div>
                                    <div style="font-size: 0.75rem; color: #666;">Views</div>
                                </div>
                                <div style="background: #f8f9fa; padding: 10px 15px; border-radius: 8px; flex: 1; text-align: center;">
                                    <i class="ti ti-download" style="font-size: 1.5rem; color: #666;"></i>
                                    <div style="font-weight: 600; margin-top: 5px;">-</div>
                                    <div style="font-size: 0.75rem; color: #666;">No Download</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info" style="background: #E3F2FD; border: 1px solid #2196F3; margin-top: 20px;">
                            <small style="color: #1976D2;">
                                <i class="ti ti-info-circle"></i> 
                                <strong>Informasi:</strong> Dokumen ini hanya dapat dilihat. Jika Anda memerlukan salinan, 
                                silakan hubungi admin untuk permintaan akses download.
                            </small>
                        </div>
                    `;
                    
                    document.getElementById('dokumenModalBody').innerHTML = html;
                } else {
                    document.getElementById('dokumenModalBody').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-circle"></i> ${data.message || 'Data tidak ditemukan'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('dokumenModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle"></i> Gagal memuat detail dokumen
                    </div>
                `;
            });
    }
</script>
@endsection
