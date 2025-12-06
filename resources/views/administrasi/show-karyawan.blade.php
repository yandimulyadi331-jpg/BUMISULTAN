@extends('layouts.mobile.app')
@section('titlepage', 'Detail Administrasi')

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
        --accent-info: #17a2b8;
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

    body {
        background: var(--bg-body);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

    .detail-card {
        background: var(--bg-primary);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .detail-header {
        background: var(--bg-primary);
        padding: 22px;
        border-radius: 18px;
        margin-bottom: 20px;
        box-shadow: 5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
    }

    .detail-row {
        margin-bottom: 18px;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--border-color);
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        font-size: 0.7rem;
        color: var(--text-secondary);
        margin-bottom: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 0.95rem;
        color: var(--text-primary);
        font-weight: 500;
        line-height: 1.6;
    }

    .detail-value strong {
        color: var(--accent-primary);
        font-weight: 700;
    }

    .badge-custom {
        font-size: 0.7rem;
        padding: 6px 14px;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
    }

    .btn-download {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        padding: 14px 20px;
        border-radius: 14px;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
        margin-top: 10px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }

    .btn-download:active {
        transform: scale(0.98);
        box-shadow: inset 3px 3px 8px var(--shadow-dark),
                   inset -3px -3px 8px var(--shadow-light);
    }

    .img-preview {
        width: 100%;
        border-radius: 14px;
        margin-top: 10px;
        cursor: pointer;
        box-shadow: 4px 4px 10px var(--shadow-dark),
                   -4px -4px 10px var(--shadow-light);
    }

    .tindak-lanjut-card {
        background: var(--bg-primary);
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 15px;
        box-shadow: 5px 5px 12px var(--shadow-dark),
                   -5px -5px 12px var(--shadow-light);
        border-left: 4px solid var(--accent-primary);
        transition: all 0.3s ease;
    }

    .tindak-lanjut-card:hover {
        transform: translateY(-2px);
        box-shadow: 6px 6px 15px var(--shadow-dark),
                   -6px -6px 15px var(--shadow-light);
    }

    .tindak-lanjut-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }

    .tindak-lanjut-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .tindak-lanjut-meta {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 20px 0 15px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--accent-primary);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-secondary);
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        color: var(--text-primary);
    }

    .empty-state .text-muted {
        color: var(--text-secondary) !important;
    }

    .alert {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 12px;
        margin: 10px 0;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .btn-sm {
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
    }

    .btn-sm:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    /* SweetAlert2 Custom Styling for Neumorphic Theme */
    .swal2-popup {
        background: var(--bg-primary) !important;
        border-radius: 20px !important;
        box-shadow: 8px 8px 20px var(--shadow-dark), 
                   -8px -8px 20px var(--shadow-light) !important;
        border: none !important;
    }

    .swal2-title {
        color: var(--text-primary) !important;
        font-weight: 700 !important;
    }

    .swal2-html-container {
        color: var(--text-primary) !important;
    }

    .swal2-close {
        background: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light) !important;
        transition: all 0.3s ease !important;
    }

    .swal2-close:hover {
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light) !important;
    }

    .swal2-html-container .table {
        background: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
    }

    .swal2-html-container .table th {
        background: var(--bg-primary) !important;
        color: var(--text-secondary) !important;
        font-weight: 700 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-color: var(--border-color) !important;
        padding: 12px !important;
    }

    .swal2-html-container .table td {
        background: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
        padding: 12px !important;
    }

    .swal2-html-container .table-bordered {
        border: 1px solid var(--border-color) !important;
    }

    .swal2-html-container .bg-light {
        background: var(--bg-primary) !important;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light) !important;
    }

    .swal2-html-container .badge {
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light) !important;
        padding: 6px 12px !important;
        border-radius: 10px !important;
    }

    .swal2-html-container .img-thumbnail {
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 4px 4px 10px var(--shadow-dark),
                   -4px -4px 10px var(--shadow-light) !important;
        background: var(--bg-primary) !important;
        padding: 8px !important;
    }

    .swal2-html-container code {
        background: var(--bg-primary) !important;
        color: var(--accent-primary) !important;
        padding: 4px 8px !important;
        border-radius: 6px !important;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light) !important;
        font-weight: 600 !important;
    }

    /* Scrollbar styling for modal */
    .swal2-html-container::-webkit-scrollbar {
        width: 8px;
    }

    .swal2-html-container::-webkit-scrollbar-track {
        background: var(--bg-primary);
        border-radius: 10px;
    }

    .swal2-html-container::-webkit-scrollbar-thumb {
        background: var(--shadow-dark);
        border-radius: 10px;
    }

    .swal2-html-container::-webkit-scrollbar-thumb:hover {
        background: var(--text-secondary);
    }
</style>

<div id="header-section">
    <div class="header-content">
        <a href="{{ route('administrasi.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>Detail Administrasi</h3>
            <p>{{ $administrasi->getJenisAdministrasiLabel() }}</p>
        </div>
    </div>
</div>

<div id="content-section">
    <!-- Header Card with Status -->
    <div class="detail-header">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0 text-white">{{ $administrasi->kode_administrasi }}</h5>
            {!! $administrasi->getPrioritasBadge() !!}
        </div>
        <div class="text-center mt-2">
            {!! $administrasi->getStatusBadge() !!}
        </div>
    </div>

    <!-- Informasi Utama -->
    <div class="detail-card">
        <div class="detail-row">
            <div class="detail-label">Nomor Surat/Dokumen</div>
            <div class="detail-value"><strong>{{ $administrasi->nomor_surat ?? '-' }}</strong></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Jenis Administrasi</div>
            <div class="detail-value">
                <span class="badge-custom bg-{{ $administrasi->getJenisAdministrasiColor() }} text-white">
                    <i class="{{ $administrasi->getJenisAdministrasiIcon() }}"></i>
                    {{ $administrasi->getJenisAdministrasiLabel() }}
                </span>
            </div>
        </div>

        @if($administrasi->tanggal_surat)
        <div class="detail-row">
            <div class="detail-label">Tanggal Surat</div>
            <div class="detail-value">
                <i class="ti ti-calendar me-1"></i>{{ $administrasi->tanggal_surat->format('d F Y') }}
            </div>
        </div>
        @endif

        @if($administrasi->isMasuk())
        <div class="detail-row">
            <div class="detail-label">Pengirim</div>
            <div class="detail-value">
                <i class="ti ti-user me-1"></i>{{ $administrasi->pengirim ?? '-' }}
            </div>
        </div>

        @if($administrasi->tanggal_terima)
        <div class="detail-row">
            <div class="detail-label">Tanggal Terima</div>
            <div class="detail-value">
                <i class="ti ti-clock me-1"></i>{{ $administrasi->tanggal_terima->format('d F Y, H:i') }}
            </div>
        </div>
        @endif
        @endif

        @if($administrasi->isKeluar())
        <div class="detail-row">
            <div class="detail-label">Penerima</div>
            <div class="detail-value">
                <i class="ti ti-send me-1"></i>{{ $administrasi->penerima ?? '-' }}
            </div>
        </div>

        @if($administrasi->tanggal_kirim)
        <div class="detail-row">
            <div class="detail-label">Tanggal Kirim</div>
            <div class="detail-value">
                <i class="ti ti-clock me-1"></i>{{ $administrasi->tanggal_kirim->format('d F Y, H:i') }}
            </div>
        </div>
        @endif
        @endif
    </div>

    <!-- Perihal dan Ringkasan -->
    <div class="detail-card">
        <div class="detail-row">
            <div class="detail-label">Perihal / Judul</div>
            <div class="detail-value">
                <strong>{{ $administrasi->perihal }}</strong>
            </div>
        </div>

        @if($administrasi->ringkasan)
        <div class="detail-row">
            <div class="detail-label">Ringkasan</div>
            <div class="detail-value" style="text-align: justify;">
                {{ $administrasi->ringkasan }}
            </div>
        </div>
        @endif

        @if($administrasi->disposisi_ke)
        <div class="detail-row">
            <div class="detail-label">Disposisi Kepada</div>
            <div class="detail-value">
                <div class="alert alert-info p-2 mb-0">
                    <i class="ti ti-share me-1"></i><strong>{{ $administrasi->disposisi_ke }}</strong>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- DETAIL ACARA UNDANGAN -->
    @if(in_array($administrasi->jenis_administrasi, ['undangan_masuk', 'undangan_keluar']) && $administrasi->nama_acara)
    <div class="detail-card" style="border-left: 4px solid var(--accent-info);">
        <div style="background: var(--bg-primary); color: var(--text-primary); padding: 14px; border-radius: 12px; margin-bottom: 18px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);">
            <h6 style="margin: 0; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                <ion-icon name="calendar-outline" style="font-size: 1.2rem; vertical-align: middle; color: var(--accent-info);"></ion-icon>
                Detail Acara Undangan
            </h6>
        </div>

        @if($administrasi->nama_acara)
        <div class="detail-row">
            <div class="detail-label">Nama Acara</div>
            <div class="detail-value">
                <strong style="color: var(--accent-info); font-size: 1.05rem;">{{ $administrasi->nama_acara }}</strong>
            </div>
        </div>
        @endif

        @if($administrasi->tanggal_acara_mulai)
        <div class="detail-row">
            <div class="detail-label">
                <ion-icon name="calendar" style="font-size: 1rem;"></ion-icon>
                Tanggal Acara
            </div>
            <div class="detail-value">
                @if($administrasi->tanggal_acara_selesai && $administrasi->tanggal_acara_mulai != $administrasi->tanggal_acara_selesai)
                    <strong>{{ $administrasi->tanggal_acara_mulai->format('d F Y') }}</strong>
                    <br>
                    <small style="color: var(--text-secondary);">sampai</small>
                    <br>
                    <strong>{{ $administrasi->tanggal_acara_selesai->format('d F Y') }}</strong>
                    <br>
                    <small style="color: var(--accent-info);">
                        ({{ $administrasi->tanggal_acara_mulai->diffInDays($administrasi->tanggal_acara_selesai) + 1 }} hari)
                    </small>
                @else
                    <strong>{{ $administrasi->tanggal_acara_mulai->format('d F Y') }}</strong>
                    <br>
                    <small style="color: var(--text-secondary);">{{ $administrasi->tanggal_acara_mulai->translatedFormat('l') }}</small>
                @endif
            </div>
        </div>
        @endif

        @if($administrasi->waktu_acara_mulai || $administrasi->waktu_acara_selesai)
        <div class="detail-row">
            <div class="detail-label">
                <ion-icon name="time-outline" style="font-size: 1rem;"></ion-icon>
                Waktu
            </div>
            <div class="detail-value">
                @if($administrasi->waktu_acara_mulai && $administrasi->waktu_acara_selesai)
                    <strong>{{ date('H:i', strtotime($administrasi->waktu_acara_mulai)) }}</strong> 
                    - 
                    <strong>{{ date('H:i', strtotime($administrasi->waktu_acara_selesai)) }}</strong> WIB
                @elseif($administrasi->waktu_acara_mulai)
                    <strong>{{ date('H:i', strtotime($administrasi->waktu_acara_mulai)) }}</strong> WIB
                @endif
            </div>
        </div>
        @endif

        @if($administrasi->lokasi_acara)
        <div class="detail-row">
            <div class="detail-label">
                <ion-icon name="location-outline" style="font-size: 1rem;"></ion-icon>
                Lokasi
            </div>
            <div class="detail-value">
                <strong style="color: var(--text-primary);">{{ $administrasi->lokasi_acara }}</strong>
                @if($administrasi->alamat_acara)
                    <br>
                    <small style="color: var(--text-secondary); line-height: 1.5;">{{ $administrasi->alamat_acara }}</small>
                @endif
            </div>
        </div>
        @endif

        @if($administrasi->dress_code)
        <div class="detail-row">
            <div class="detail-label">
                <ion-icon name="shirt-outline" style="font-size: 1rem;"></ion-icon>
                Dress Code
            </div>
            <div class="detail-value">
                <span style="background: var(--accent-info); color: white; padding: 6px 14px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);">
                    {{ $administrasi->dress_code }}
                </span>
            </div>
        </div>
        @endif

        @if($administrasi->catatan_acara)
        <div class="detail-row">
            <div class="detail-label">
                <ion-icon name="information-circle-outline" style="font-size: 1rem;"></ion-icon>
                Catatan Acara
            </div>
            <div class="detail-value" style="background: var(--bg-primary); padding: 12px; border-radius: 10px; border-left: 3px solid var(--accent-info); box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);">
                {{ $administrasi->catatan_acara }}
            </div>
        </div>
        @endif
    </div>
    @endif
    <!-- END DETAIL ACARA -->

    <!-- File dan Foto -->
    @if($administrasi->file_dokumen || $administrasi->foto)
    <div class="detail-card">
        <div class="detail-label mb-3">Dokumen & File</div>

        @if($administrasi->file_dokumen)
        <a href="{{ route('administrasi.karyawan.download', $administrasi->id) }}" class="btn-download">
            <i class="ti ti-download"></i>
            Download Dokumen
        </a>
        @endif

        @if($administrasi->foto)
        <div class="mt-3">
            <div class="detail-label">Foto Dokumen</div>
            <img src="{{ Storage::url($administrasi->foto) }}" 
                 alt="Foto Dokumen" 
                 class="img-preview"
                 onclick="window.open(this.src, '_blank')">
            <small class="text-muted d-block mt-2 text-center">
                <i class="ti ti-info-circle"></i> Klik gambar untuk memperbesar
            </small>
        </div>
        @endif
    </div>
    @endif

    <!-- Catatan dan Keterangan -->
    @if($administrasi->catatan || $administrasi->keterangan)
    <div class="detail-card">
        @if($administrasi->catatan)
        <div class="detail-row">
            <div class="detail-label">Catatan</div>
            <div class="detail-value">{{ $administrasi->catatan }}</div>
        </div>
        @endif

        @if($administrasi->keterangan)
        <div class="detail-row">
            <div class="detail-label">Keterangan</div>
            <div class="detail-value">{{ $administrasi->keterangan }}</div>
        </div>
        @endif
    </div>
    @endif

    <!-- Info Pembuat -->
    <div class="detail-card">
        <div class="detail-row">
            <div class="detail-label">Dibuat Oleh</div>
            <div class="detail-value">
                <i class="ti ti-user me-1"></i>{{ $administrasi->creator->name ?? '-' }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Waktu Dibuat</div>
            <div class="detail-value">
                <i class="ti ti-calendar me-1"></i>{{ $administrasi->created_at->format('d F Y, H:i') }}
            </div>
        </div>
    </div>

    <!-- History Tindak Lanjut -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">
            <i class="ti ti-list-check me-2"></i>History Tindak Lanjut
        </h5>
        <a href="{{ route('administrasi.karyawan.tindak-lanjut.create', $administrasi->id) }}" 
           class="btn btn-sm" 
           style="display: inline-flex; align-items: center; gap: 5px;">
            <ion-icon name="add-circle-outline" style="font-size: 1rem;"></ion-icon>
            Tambah
        </a>
    </div>

    @forelse($administrasi->tindakLanjut as $tindak)
    <div class="tindak-lanjut-card">
        <div class="tindak-lanjut-header">
            <div>
                <span class="badge-custom bg-{{ $tindak->status_tindak_lanjut == 'selesai' ? 'success' : 'warning' }} text-white">
                    <i class="{{ $tindak->getJenisTindakLanjutIcon() }}"></i>
                    {{ $tindak->getJenisTindakLanjutLabel() }}
                </span>
            </div>
            {!! $tindak->getStatusBadge() !!}
        </div>

        <div class="tindak-lanjut-title">{{ $tindak->judul_tindak_lanjut }}</div>

        <div class="tindak-lanjut-meta">
            <i class="ti ti-code me-1"></i><strong>Kode:</strong> {{ $tindak->kode_tindak_lanjut }}
        </div>

        @if($tindak->deskripsi_tindak_lanjut)
        <div class="tindak-lanjut-meta">
            <i class="ti ti-file-text me-1"></i>{{ Str::limit($tindak->deskripsi_tindak_lanjut, 150) }}
        </div>
        @endif

        @if($tindak->jenis_tindak_lanjut == 'pencairan_dana' && $tindak->nominal_pencairan)
        <div class="alert alert-success p-2 mt-2 mb-2" style="border-left: 3px solid var(--accent-success);">
            <strong class="d-block">{{ $tindak->formatNominalPencairan() }}</strong>
            @if($tindak->nama_penerima_dana)
                <small>Penerima: {{ $tindak->nama_penerima_dana }}</small>
            @endif
        </div>
        @endif

        @if($tindak->jenis_tindak_lanjut == 'disposisi' && $tindak->disposisi_kepada)
        <div class="tindak-lanjut-meta">
            <i class="ti ti-arrow-right me-1"></i><strong>Kepada:</strong> {{ $tindak->disposisi_kepada }}
        </div>
        @endif

        <div class="tindak-lanjut-meta">
            <i class="ti ti-calendar me-1"></i>{{ $tindak->created_at->format('d/m/Y H:i') }}
            @if($tindak->user)
                <span class="ms-2"><i class="ti ti-user me-1"></i>{{ $tindak->user->name }}</span>
            @endif
        </div>

        <button class="btn btn-sm mt-2 w-100" 
                onclick="showDetailTindakLanjut({{ $tindak->id }})">
            <i class="ti ti-eye me-1"></i> Lihat Detail Lengkap
        </button>
    </div>
    @empty
    <div class="empty-state">
        <i class="ti ti-clipboard-off"></i>
        <p><strong>Belum ada tindak lanjut</strong></p>
        <p class="text-muted small">History tindak lanjut akan muncul di sini</p>
    </div>
    @endforelse
</div>

@endsection

@push('myscript')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    @endif

    function showDetailTindakLanjut(tindakLanjutId) {
        const tindakLanjutData = @json($administrasi->tindakLanjut);
        const tindak = tindakLanjutData.find(t => t.id === tindakLanjutId);
        
        if (!tindak) return;

        let detailHtml = `
            <div class="text-start" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th width="40%">Kode</th>
                        <td><strong>${tindak.kode_tindak_lanjut}</strong></td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td><span class="badge bg-info">${getJenisLabel(tindak.jenis_tindak_lanjut)}</span></td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td><strong>${tindak.judul_tindak_lanjut}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>${formatDate(tindak.tanggal_tindak_lanjut)}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge bg-${tindak.status_tindak_lanjut === 'selesai' ? 'success' : (tindak.status_tindak_lanjut === 'proses' ? 'info' : 'warning')}">${tindak.status_tindak_lanjut.toUpperCase()}</span></td>
                    </tr>
        `;

        if (tindak.deskripsi_tindak_lanjut) {
            detailHtml += `
                    <tr>
                        <th>Deskripsi</th>
                        <td>${tindak.deskripsi_tindak_lanjut}</td>
                    </tr>
            `;
        }

        // Add specific fields based on jenis
        if (tindak.jenis_tindak_lanjut === 'pencairan_dana') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Pencairan Dana</strong></td></tr>';
            if (tindak.nominal_pencairan) {
                detailHtml += `
                    <tr>
                        <th>Nominal</th>
                        <td><strong class="text-success fs-6">Rp ${formatRupiah(tindak.nominal_pencairan)}</strong></td>
                    </tr>
                `;
            }
            if (tindak.metode_pencairan) {
                detailHtml += `<tr><th>Metode</th><td>${tindak.metode_pencairan}</td></tr>`;
            }
            if (tindak.nama_penerima_dana) {
                detailHtml += `<tr><th>Penerima</th><td>${tindak.nama_penerima_dana}</td></tr>`;
            }
            if (tindak.nomor_rekening) {
                detailHtml += `<tr><th>No. Rekening</th><td><code>${tindak.nomor_rekening}</code></td></tr>`;
            }
            if (tindak.tanggal_pencairan) {
                detailHtml += `<tr><th>Tgl Pencairan</th><td>${formatDate(tindak.tanggal_pencairan)}</td></tr>`;
            }
            if (tindak.bukti_pencairan) {
                detailHtml += `
                    <tr>
                        <th>Bukti</th>
                        <td>
                            <img src="/storage/${tindak.bukti_pencairan}" 
                                 class="img-thumbnail mt-1" 
                                 style="max-width: 100%; cursor: pointer;" 
                                 onclick="window.open('/storage/${tindak.bukti_pencairan}', '_blank')">
                        </td>
                    </tr>
                `;
            }
        } else if (tindak.jenis_tindak_lanjut === 'disposisi') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Disposisi</strong></td></tr>';
            if (tindak.disposisi_dari) detailHtml += `<tr><th>Dari</th><td>${tindak.disposisi_dari}</td></tr>`;
            if (tindak.disposisi_kepada) detailHtml += `<tr><th>Kepada</th><td><strong>${tindak.disposisi_kepada}</strong></td></tr>`;
            if (tindak.instruksi_disposisi) detailHtml += `<tr><th>Instruksi</th><td>${tindak.instruksi_disposisi}</td></tr>`;
            if (tindak.deadline_disposisi) detailHtml += `<tr><th>Deadline</th><td>${formatDate(tindak.deadline_disposisi)}</td></tr>`;
        } else if (tindak.jenis_tindak_lanjut === 'konfirmasi_terima' || tindak.jenis_tindak_lanjut === 'konfirmasi_kirim') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Konfirmasi</strong></td></tr>';
            if (tindak.nama_penerima_paket) detailHtml += `<tr><th>Penerima</th><td>${tindak.nama_penerima_paket}</td></tr>`;
            if (tindak.kondisi_paket) detailHtml += `<tr><th>Kondisi</th><td><span class="badge bg-${tindak.kondisi_paket === 'Baik' ? 'success' : 'danger'}">${tindak.kondisi_paket}</span></td></tr>`;
            if (tindak.resi_pengiriman) detailHtml += `<tr><th>Resi</th><td><code>${tindak.resi_pengiriman}</code></td></tr>`;
            if (tindak.foto_paket) {
                detailHtml += `
                    <tr>
                        <th>Foto</th>
                        <td>
                            <img src="/storage/${tindak.foto_paket}" 
                                 class="img-thumbnail mt-1" 
                                 style="max-width: 100%; cursor: pointer;" 
                                 onclick="window.open('/storage/${tindak.foto_paket}', '_blank')">
                        </td>
                    </tr>
                `;
            }
        }

        if (tindak.catatan) {
            detailHtml += `<tr><th>Catatan</th><td>${tindak.catatan}</td></tr>`;
        }

        if (tindak.user) {
            detailHtml += `<tr><th>Dibuat Oleh</th><td>${tindak.user.name}</td></tr>`;
        }

        detailHtml += `
                    <tr>
                        <th>Waktu Dibuat</th>
                        <td>${formatDateTime(tindak.created_at)}</td>
                    </tr>
                </table>
            </div>
        `;

        Swal.fire({
            title: '<strong>Detail Tindak Lanjut</strong>',
            html: detailHtml,
            width: '90%',
            showCloseButton: true,
            showConfirmButton: false
        });
    }

    function getJenisLabel(jenis) {
        const labels = {
            'pencairan_dana': 'Pencairan Dana',
            'disposisi': 'Disposisi',
            'konfirmasi_terima': 'Konfirmasi Terima',
            'konfirmasi_kirim': 'Konfirmasi Kirim',
            'rapat_pembahasan': 'Rapat',
            'penerbitan_sk': 'Penerbitan SK',
            'tandatangan': 'Penandatanganan',
            'verifikasi': 'Verifikasi',
            'approval': 'Approval',
            'revisi': 'Revisi',
            'arsip': 'Pengarsipan',
            'lainnya': 'Lainnya'
        };
        return labels[jenis] || jenis;
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    }

    function formatDateTime(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: '2-digit', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
</script>
@endpush
