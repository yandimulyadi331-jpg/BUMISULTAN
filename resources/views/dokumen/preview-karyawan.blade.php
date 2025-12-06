@extends('layouts.mobile.app')
@section('titlepage', 'Preview Dokumen')

@section('content')
<style>
    :root {
        --bg-body: #f5f5f5;
        --primary-color: {{ $document->category->warna }};
    }

    body {
        background: var(--bg-body);
    }

    #header-section {
        height: auto;
        padding: 20px;
        position: relative;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 100%);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    #section-back {
        position: absolute;
        left: 15px;
        top: 15px;
    }

    .back-btn {
        color: #ffffff;
        font-size: 30px;
        text-decoration: none;
    }

    .back-btn:hover {
        color: #f0f0f0;
    }

    #header-title {
        text-align: center;
        color: #ffffff;
        margin-top: 10px;
    }

    #header-title h3 {
        font-weight: bold;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 1.2rem;
    }

    #header-title p {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }

    #content-section {
        padding: 20px 15px;
        padding-bottom: 100px;
    }

    /* Document Preview Card */
    .preview-card {
        background: #ffffff;
        border-radius: 0;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
    }

    .preview-header {
        background: var(--primary-color);
        color: white;
        padding: 25px;
        border-bottom: 3px solid rgba(0, 0, 0, 0.1);
    }

    .preview-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        line-height: 1.3;
    }

    .preview-subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
        margin: 0;
    }

    .preview-body {
        padding: 30px 25px;
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 0.7rem;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--primary-color);
    }

    .detail-item {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        flex: 0 0 140px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #666;
    }

    .detail-value {
        flex: 1;
        font-size: 0.85rem;
        color: #1a1a1a;
        font-weight: 500;
    }

    /* Badge Styles */
    .badge-custom {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-view-only {
        background: #FF6B00;
        color: white;
    }

    .badge-public {
        background: #28a745;
        color: white;
    }

    /* Document Viewer */
    .document-viewer {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
        text-align: center;
    }

    .document-icon {
        font-size: 4rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .document-info {
        font-size: 0.85rem;
        color: #666;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn-action {
        flex: 1;
        background: #1a1a1a;
        color: white;
        border: none;
        padding: 15px 20px;
        border-radius: 0;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-action:hover {
        background: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: #1a1a1a;
        border: 2px solid #1a1a1a;
    }

    .btn-secondary:hover {
        background: #1a1a1a;
        color: white;
    }

    /* Alert Box */
    .alert-view-only {
        background: #FFF3CD;
        border: 2px solid #FF6B00;
        padding: 20px;
        border-radius: 0;
        margin-bottom: 30px;
    }

    .alert-view-only .alert-icon {
        font-size: 2rem;
        color: #FF6B00;
        margin-right: 15px;
    }

    .alert-view-only .alert-content {
        flex: 1;
    }

    .alert-view-only .alert-title {
        font-size: 1rem;
        font-weight: 700;
        color: #FF6B00;
        margin: 0 0 5px 0;
    }

    .alert-view-only .alert-text {
        font-size: 0.85rem;
        color: #666;
        margin: 0;
    }
</style>

<div id="header-section">
    <div id="section-back">
        <a href="{{ route('dokumen.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div id="header-title">
        <h3>Preview Dokumen</h3>
        <p>{{ $document->category->nama_kategori }}</p>
    </div>
</div>

<div id="content-section">
    <!-- Alert for View Only Documents -->
    @if($document->access_level === 'view_only')
    <div class="alert-view-only" style="display: flex; align-items: center;">
        <div class="alert-icon">
            <ion-icon name="lock-closed"></ion-icon>
        </div>
        <div class="alert-content">
            <h4 class="alert-title">Dokumen View Only</h4>
            <p class="alert-text">Anda hanya dapat melihat detail dokumen ini. Download tidak tersedia. Hubungi admin untuk permintaan akses download.</p>
        </div>
    </div>
    @endif

    <!-- Document Preview Card -->
    <div class="preview-card">
        <!-- Header -->
        <div class="preview-header">
            <h1 class="preview-title">{{ $document->nama_dokumen }}</h1>
            <p class="preview-subtitle">
                <ion-icon name="pricetag-outline" style="vertical-align: middle;"></ion-icon> 
                {{ $document->kode_dokumen }}
            </p>
        </div>

        <!-- Body -->
        <div class="preview-body">
            <!-- Document Information Section -->
            <div class="detail-section">
                <h3 class="section-title">Informasi Dokumen</h3>
                
                <div class="detail-item">
                    <span class="detail-label">Kode Dokumen</span>
                    <span class="detail-value"><strong>{{ $document->kode_dokumen }}</strong></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Nama Dokumen</span>
                    <span class="detail-value">{{ $document->nama_dokumen }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Kategori</span>
                    <span class="detail-value">
                        <span class="badge" style="background-color: {{ $document->category->warna }}; color: white; padding: 4px 10px; border-radius: 3px; font-size: 0.75rem;">
                            {{ $document->category->nama_kategori }}
                        </span>
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Jenis Dokumen</span>
                    <span class="detail-value">
                        @if($document->jenis_dokumen === 'link')
                            <ion-icon name="link-outline" style="vertical-align: middle;"></ion-icon> Link Eksternal
                        @else
                            <ion-icon name="document-attach-outline" style="vertical-align: middle;"></ion-icon> File Dokumen
                        @endif
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="badge bg-{{ strtolower($document->status) }}" style="padding: 4px 10px; border-radius: 3px; font-size: 0.7rem;">
                            {{ ucfirst($document->status) }}
                        </span>
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Hak Akses</span>
                    <span class="detail-value">
                        @if($document->access_level === 'view_only')
                            <span class="badge-custom badge-view-only">
                                <ion-icon name="eye-outline" style="vertical-align: middle;"></ion-icon> View Only
                            </span>
                        @else
                            <span class="badge-custom badge-public">
                                <ion-icon name="checkmark-circle-outline" style="vertical-align: middle;"></ion-icon> Public
                            </span>
                        @endif
                    </span>
                </div>
            </div>

            @if($document->deskripsi)
            <!-- Description Section -->
            <div class="detail-section">
                <h3 class="section-title">Deskripsi</h3>
                <p style="color: #666; line-height: 1.6; margin: 0;">{{ $document->deskripsi }}</p>
            </div>
            @endif

            <!-- Physical Location Section -->
            @if($document->nomor_loker || $document->nomor_referensi || $document->penerbit)
            <div class="detail-section">
                <h3 class="section-title">Detail Tambahan</h3>
                
                @if($document->nomor_loker)
                <div class="detail-item">
                    <span class="detail-label">Nomor Loker</span>
                    <span class="detail-value">
                        <ion-icon name="archive-outline" style="vertical-align: middle;"></ion-icon> 
                        <strong>{{ $document->nomor_loker }}</strong>
                    </span>
                </div>
                @endif
                
                @if($document->lokasi_loker)
                <div class="detail-item">
                    <span class="detail-label">Lokasi Loker</span>
                    <span class="detail-value">{{ $document->lokasi_loker }}</span>
                </div>
                @endif
                
                @if($document->rak || $document->baris)
                <div class="detail-item">
                    <span class="detail-label">Rak / Baris</span>
                    <span class="detail-value">{{ $document->rak }}{{ $document->baris ? ' / '.$document->baris : '' }}</span>
                </div>
                @endif
                
                @if($document->nomor_referensi)
                <div class="detail-item">
                    <span class="detail-label">No. Referensi</span>
                    <span class="detail-value">{{ $document->nomor_referensi }}</span>
                </div>
                @endif
                
                @if($document->penerbit)
                <div class="detail-item">
                    <span class="detail-label">Penerbit</span>
                    <span class="detail-value">{{ $document->penerbit }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- Date Information Section -->
            @if($document->tanggal_dokumen || $document->tanggal_berlaku || $document->tanggal_berakhir)
            <div class="detail-section">
                <h3 class="section-title">Informasi Tanggal</h3>
                
                @if($document->tanggal_dokumen)
                <div class="detail-item">
                    <span class="detail-label">Tanggal Dokumen</span>
                    <span class="detail-value">
                        <ion-icon name="calendar-outline" style="vertical-align: middle;"></ion-icon> 
                        {{ $document->tanggal_dokumen->format('d M Y') }}
                    </span>
                </div>
                @endif
                
                @if($document->tanggal_berlaku)
                <div class="detail-item">
                    <span class="detail-label">Tanggal Berlaku</span>
                    <span class="detail-value">{{ $document->tanggal_berlaku->format('d M Y') }}</span>
                </div>
                @endif
                
                @if($document->tanggal_berakhir)
                <div class="detail-item">
                    <span class="detail-label">Tanggal Berakhir</span>
                    <span class="detail-value">{{ $document->tanggal_berakhir->format('d M Y') }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- Statistics Section -->
            <div class="detail-section">
                <h3 class="section-title">Statistik</h3>
                
                <div style="display: flex; gap: 15px; margin-top: 15px;">
                    <div style="flex: 1; background: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px;">
                        <ion-icon name="eye-outline" style="font-size: 2rem; color: var(--primary-color);"></ion-icon>
                        <div style="font-size: 1.5rem; font-weight: 700; margin: 10px 0 5px;">{{ $document->jumlah_view }}</div>
                        <div style="font-size: 0.75rem; color: #666; text-transform: uppercase;">Views</div>
                    </div>
                    
                    <div style="flex: 1; background: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px;">
                        <ion-icon name="download-outline" style="font-size: 2rem; color: var(--primary-color);"></ion-icon>
                        <div style="font-size: 1.5rem; font-weight: 700; margin: 10px 0 5px;">{{ $document->jumlah_download }}</div>
                        <div style="font-size: 0.75rem; color: #666; text-transform: uppercase;">Downloads</div>
                    </div>
                </div>
            </div>

            <!-- Document Viewer -->
            <div class="document-viewer">
                <div class="document-icon">
                    @if($document->jenis_dokumen === 'link')
                        <ion-icon name="link"></ion-icon>
                    @else
                        <ion-icon name="document-text"></ion-icon>
                    @endif
                </div>
                <div class="document-info">
                    @if($document->jenis_dokumen === 'file')
                        <strong>{{ $document->nama_dokumen }}.{{ $document->file_extension }}</strong><br>
                        <span style="color: #999;">{{ $document->file_size }}</span>
                    @else
                        <strong>Link Eksternal</strong><br>
                        <a href="{{ $document->file_path }}" target="_blank" style="color: var(--primary-color); word-break: break-all;">
                            {{ $document->file_path }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('dokumen.karyawan.index') }}" class="btn-action btn-secondary">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    Kembali
                </a>
                
                @if($document->access_level !== 'view_only')
                    @if($document->jenis_dokumen === 'link')
                        <a href="{{ $document->file_path }}" target="_blank" class="btn-action">
                            <ion-icon name="open-outline"></ion-icon>
                            Buka Link
                        </a>
                    @else
                        <a href="{{ route('dokumen.karyawan.download', $document->id) }}" class="btn-action">
                            <ion-icon name="download-outline"></ion-icon>
                            Download
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
