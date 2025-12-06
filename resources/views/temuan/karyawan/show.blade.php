@extends('layouts.mobile.app')

@section('content')
<style>
    * {
        -webkit-overflow-scrolling: touch !important;
    }

    html, body {
        overflow: visible !important;
        overflow-y: scroll !important;
        overflow-x: hidden !important;
        height: auto !important;
        max-height: none !important;
        min-height: 100vh !important;
    }

    #appCapsule {
        overflow: visible !important;
        height: auto !important;
        max-height: none !important;
        min-height: calc(100vh + 200px) !important;
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

    #content-section {
        margin-top: 56px;
        padding: 8px 16px 150px 16px;
        background: var(--bg-primary);
        min-height: calc(100vh - 56px);
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

    .btn-back {
        background: none;
        border: none;
        color: var(--text-primary);
        font-size: 24px;
        cursor: pointer;
        padding: 0;
    }

    .header-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        flex: 1;
        text-align: center;
        margin: 0 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .detail-container {
        background: var(--bg-secondary);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: var(--shadow);
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
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

    .urgency-high {
        background: #fee2e2;
        color: #dc2626;
    }

    .urgency-medium {
        background: #fef3c7;
        color: #f59e0b;
    }

    .urgency-low {
        background: #dcfce7;
        color: #16a34a;
    }

    .detail-photo {
        width: 100%;
        border-radius: 8px;
        object-fit: cover;
        max-height: 300px;
        border: 1px solid var(--border-color);
    }

    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .meta-box {
        background: var(--bg-primary);
        padding: 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .meta-label {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 4px;
        font-weight: 600;
    }

    .meta-value {
        font-size: 14px;
        color: var(--text-primary);
        font-weight: 700;
    }

    .timeline-item {
        padding: 12px;
        border-left: 3px solid #667eea;
        background: var(--bg-primary);
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .timeline-date {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 4px;
    }

    .timeline-text {
        font-size: 13px;
        color: var(--text-primary);
    }

    .notes-box {
        background: var(--bg-primary);
        border-left: 3px solid #667eea;
        padding: 12px;
        border-radius: 4px;
        font-size: 13px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .progress-bar-custom {
        width: 100%;
        height: 8px;
        background: var(--border-color);
        border-radius: 4px;
        overflow: hidden;
        margin: 8px 0;
    }

    .progress-fill {
        height: 100%;
        background: #667eea;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .alert-box {
        background: var(--bg-primary);
        border-left: 3px solid #667eea;
        padding: 12px;
        border-radius: 4px;
        font-size: 13px;
    }

    .btn-back-list {
        width: 100%;
        padding: 12px 16px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-back-list:hover {
        background: #5568d3;
    }

    .btn-delete {
        width: 100%;
        padding: 12px 16px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 12px;
    }

    .btn-delete:hover {
        background: #dc2626;
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

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .desc-text {
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
        color: var(--text-primary);
        font-size: 14px;
    }
</style>

<div id="content-section">
    {{-- Header --}}
    <div class="header-top">
        <a href="{{ route('temuan.karyawan.list') }}" class="btn-back">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">Detail Temuan</div>
        <div style="width: 40px;"></div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            ⚠ {{ session('error') }}
        </div>
    @endif

    {{-- Main Heading --}}
    <div class="detail-container">
        <h2 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0; line-height: 1.4; margin-bottom: 12px;">
            {{ $temuan->judul }}
        </h2>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <span class="status-badge status-{{ $temuan->status }}">{{ $temuan->getStatusLabel() }}</span>
            <span class="status-badge urgency-{{ $temuan->urgensi }}">{{ $temuan->getUrgensiLabel() }}</span>
        </div>
    </div>

    {{-- Meta Information --}}
    <div class="detail-container">
        <div class="meta-grid">
            <div class="meta-box">
                <div class="meta-label">📅 Tanggal Lapor</div>
                <div class="meta-value">{{ $temuan->tanggal_temuan->format('d M Y') }}</div>
                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">{{ $temuan->tanggal_temuan->format('H:i') }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">📍 Lokasi</div>
                <div class="meta-value" style="font-size: 13px;">{{ $temuan->lokasi }}</div>
            </div>
        </div>
    </div>

    {{-- Photo Section --}}
    @if($temuan->foto_path)
        <div class="detail-container">
            <div class="section-title">📸 Foto Bukti</div>
            <img src="{{ Storage::url($temuan->foto_path) }}" alt="Foto Temuan" class="detail-photo">
        </div>
    @endif

    {{-- Description --}}
    <div class="detail-container">
        <div class="section-title">📝 Deskripsi Temuan</div>
        <div class="desc-text">{{ $temuan->deskripsi }}</div>
    </div>

    {{-- Status Progress --}}
    <div class="detail-container">
        <div class="section-title">⏳ Status Penanganan</div>
        <div style="margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 13px; color: var(--text-primary); font-weight: 600;">{{ $temuan->getStatusLabel() }}</span>
                <span style="font-size: 12px; color: var(--text-secondary);">
                    {{ match($temuan->status) {
                        'baru' => '25%',
                        'sedang_diproses' => '50%',
                        'sudah_diperbaiki' => '75%',
                        'tindaklanjuti' => '60%',
                        'selesai' => '100%',
                        default => '0%'
                    } }}
                </span>
            </div>
            <div class="progress-bar-custom">
                <div class="progress-fill" style="width: {{ match($temuan->status) {
                    'baru' => '25',
                    'sedang_diproses' => '50',
                    'sudah_diperbaiki' => '75',
                    'tindaklanjuti' => '60',
                    'selesai' => '100',
                    default => '0'
                } }}%"></div>
            </div>
        </div>

        <div class="alert-box">
            @if($temuan->status == 'baru')
                ℹ️ Laporan Anda telah diterima dan akan segera ditinjau oleh tim admin.
            @elseif($temuan->status == 'sedang_diproses')
                🔧 Tim admin sedang menangani laporan Anda.
            @elseif($temuan->status == 'sudah_diperbaiki')
                ✅ Perbaikan sudah dilakukan dan sedang dalam tahap verifikasi.
            @elseif($temuan->status == 'tindaklanjuti')
                📋 Tindak lanjut sedang dilakukan.
            @elseif($temuan->status == 'selesai')
                🎉 Perbaikan telah selesai dilakukan.
            @endif
        </div>
    </div>

    {{-- Admin Notes --}}
    @if($temuan->catatan_admin)
        <div class="detail-container">
            <div class="section-title">💬 Catatan Admin</div>
            <div class="notes-box">{{ $temuan->catatan_admin }}</div>
        </div>
    @endif

    {{-- Timeline --}}
    <div class="detail-container">
        <div class="section-title">⏱️ Riwayat Update</div>
        <div class="timeline-item">
            <div class="timeline-date">{{ $temuan->created_at->format('d M Y H:i') }}</div>
            <div class="timeline-text">📤 Laporan dikirimkan</div>
        </div>
        @if($temuan->updated_at && $temuan->updated_at->ne($temuan->created_at))
            <div class="timeline-item">
                <div class="timeline-date">{{ $temuan->updated_at->format('d M Y H:i') }}</div>
                <div class="timeline-text">🔄 Diperbarui - Status: <strong>{{ $temuan->getStatusLabel() }}</strong></div>
            </div>
        @endif
    </div>

    {{-- Back & Delete Buttons --}}
    <a href="{{ route('temuan.karyawan.list') }}" class="btn-back-list">
        ← Kembali ke Daftar
    </a>

    @if($temuan->status == 'baru')
        <form method="POST" action="{{ route('temuan.karyawan.destroy', $temuan->id) }}" 
              onsubmit="return confirm('⚠️ Yakin ingin menghapus laporan ini? Tindakan ini tidak bisa dibatalkan.');">
            @csrf @method('DELETE')
            <button type="submit" class="btn-delete">
                🗑️ Hapus Laporan
            </button>
        </form>
    @endif
</div>

@endsection
