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
        padding: 8px 16px 150px 16px;
        position: relative;
        z-index: 1;
        background: var(--bg-primary);
        min-height: calc(100vh - 56px);
        transition: background 0.3s ease;
    }

    /* Search & Filter Section - Modern Style */
    .search-filter-section {
        background: var(--bg-secondary);
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }

    .filter-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        background: var(--bg-primary);
        color: var(--text-primary);
        transition: all 0.3s ease;
        margin-bottom: 8px;
    }

    .filter-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        width: 100%;
        padding: 10px 12px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: #5568d3;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-filter:active {
        transform: scale(0.98);
    }

    /* Temuan Card - Neumorphism Style */
    .temuan-card {
        background: var(--bg-secondary);
        border: none;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .temuan-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .temuan-card:active {
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .temuan-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        gap: 8px;
    }

    .temuan-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        flex-grow: 1;
    }

    .temuan-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-baru {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-sedang_diproses {
        background: #fef3c7;
        color: #92400e;
    }

    .status-sudah_diperbaiki {
        background: #dcfce7;
        color: #166534;
    }

    .status-tindaklanjuti {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-selesai {
        background: #bbf7d0;
        color: #065f46;
    }

    .temuan-location {
        font-size: 13px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 12px;
    }

    .temuan-desc {
        font-size: 14px;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .temuan-photo {
        width: 100%;
        height: 150px;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 12px;
        border: 1px solid var(--border-color);
    }

    .temuan-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 12px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        color: var(--text-secondary);
    }

    .urgency-high {
        color: #dc2626;
    }

    .urgency-medium {
        color: #f59e0b;
    }

    .urgency-low {
        color: #10b981;
    }

    .temuan-notes {
        background: var(--bg-primary);
        border-left: 3px solid #667eea;
        padding: 10px;
        border-radius: 4px;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .temuan-notes-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .temuan-notes-text {
        color: var(--text-secondary);
    }

    .temuan-actions {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 8px;
    }

    .btn-detail {
        padding: 10px 12px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 4px;
        justify-content: center;
    }

    .btn-detail:hover {
        background: #5568d3;
    }

    .btn-delete {
        padding: 10px 12px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .header-top {
        background: var(--bg-secondary);
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }

    .header-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .btn-add {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-add:hover {
        background: #5568d3;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        font-size: 48px;
        color: var(--text-secondary);
        margin-bottom: 16px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .empty-desc {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 16px;
    }

    .btn-primary {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #5568d3;
    }

    .alert-success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .pagination-container a,
    .pagination-container span {
        padding: 8px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination-container a:hover {
        background: #667eea;
        color: white;
    }

    .pagination-container .active span {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
</style>

<div id="content-section">
    {{-- Header Top --}}
    <div class="header-top">
        <div class="header-title">📋 Temuan Saya</div>
        <a href="{{ route('temuan.karyawan.create') }}" class="btn-add">
            <ion-icon name="add-outline"></ion-icon> Lapor
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert-success">
            <strong>✓ Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="search-filter-section">
        <form method="GET" class="row g-0">
            <div class="col-12 mb-2">
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>🆕 Baru</option>
                    <option value="sedang_diproses" {{ request('status') == 'sedang_diproses' ? 'selected' : '' }}>⏳ Sedang Diproses</option>
                    <option value="sudah_diperbaiki" {{ request('status') == 'sudah_diperbaiki' ? 'selected' : '' }}>✅ Sudah Diperbaiki</option>
                    <option value="tindaklanjuti" {{ request('status') == 'tindaklanjuti' ? 'selected' : '' }}>📝 Tindaklanjuti</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>🎉 Selesai</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn-filter">
                    🔍 Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Laporan List --}}
    @if($temuan->count() > 0)
        @foreach($temuan as $t)
            <div class="temuan-card">
                <div class="temuan-header">
                    <div>
                        <h3 class="temuan-title">{{ $t->judul }}</h3>
                        <div class="temuan-location">
                            <ion-icon name="location-outline" style="font-size: 14px;"></ion-icon>
                            {{ $t->lokasi }}
                        </div>
                    </div>
                    <span class="temuan-status status-{{ $t->status }}">
                        {{ $t->getStatusLabel() }}
                    </span>
                </div>

                <div class="temuan-desc">
                    {{ Str::limit($t->deskripsi, 120) }}
                </div>

                {{-- Foto Preview --}}
                @if($t->foto_path)
                    <img src="{{ Storage::url($t->foto_path) }}" alt="Foto Temuan" class="temuan-photo">
                @endif

                {{-- Meta Info --}}
                <div class="temuan-meta">
                    <div class="meta-item">
                        <ion-icon name="warning-outline" class="urgency-{{ $t->urgensi }}"></ion-icon>
                        {{ $t->getUrgensiLabel() }}
                    </div>
                    <div class="meta-item">
                        <ion-icon name="calendar-outline"></ion-icon>
                        {{ $t->tanggal_temuan->format('d M Y') }}
                    </div>
                </div>

                {{-- Catatan Admin --}}
                @if($t->catatan_admin)
                    <div class="temuan-notes">
                        <div class="temuan-notes-title">📝 Catatan Admin:</div>
                        <div class="temuan-notes-text">{{ Str::limit($t->catatan_admin, 100) }}</div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="temuan-actions">
                    <a href="{{ route('temuan.karyawan.show', $t->id) }}" class="btn-detail">
                        <ion-icon name="eye-outline"></ion-icon> Detail
                    </a>
                    @if($t->status == 'baru')
                        <form method="POST" action="{{ route('temuan.karyawan.destroy', $t->id) }}" 
                              onsubmit="return confirm('Yakin ingin menghapus laporan ini?');" style="margin: 0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Hapus">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Pagination --}}
        <div class="pagination-container">
            {{ $temuan->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div class="empty-title">Belum Ada Laporan</div>
            <div class="empty-desc">Anda belum mengirimkan laporan temuan apapun</div>
            <a href="{{ route('temuan.karyawan.create') }}" class="btn-primary">
                <ion-icon name="add-outline" style="margin-right: 4px;"></ion-icon> Buat Laporan Pertama
            </a>
        </div>
    @endif
</div>

@endsection
