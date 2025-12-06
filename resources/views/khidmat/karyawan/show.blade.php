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

    body {
        --bg-body: var(--bg-body-light);
        --bg-primary: var(--bg-primary-light);
        --shadow-dark: var(--shadow-dark-light);
        --shadow-light: var(--shadow-light-light);
        --text-primary: var(--text-primary-light);
        --text-secondary: var(--text-secondary-light);
        --border-color: var(--border-light);
        background: var(--bg-body);
        min-height: 100vh;
        padding-bottom: 80px;
        transition: background 0.3s ease, color 0.3s ease;
    }

    /* Dark Mode Support */
    body.dark-mode {
        --bg-body: var(--bg-body-dark);
        --bg-primary: var(--bg-primary-dark);
        --shadow-dark: var(--shadow-dark-dark);
        --shadow-light: var(--shadow-light-dark);
        --text-primary: var(--text-primary-dark);
        --text-secondary: var(--text-secondary-dark);
        --border-color: var(--border-dark);
    }

    .header-section {
        background: var(--bg-primary);
        padding: 25px 15px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        position: sticky;
        top: 0;
        z-index: 100;
        margin: 15px;
        border-radius: 20px;
    }

    .back-btn {
        color: var(--text-primary);
        font-size: 24px;
        text-decoration: none;
        position: absolute;
        left: 15px;
        top: 15px;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .back-btn:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .header-title {
        text-align: center;
        color: var(--text-primary);
    }

    .header-title h3 {
        font-weight: 800;
        margin: 0;
        font-size: 1.1rem;
    }

    .header-title p {
        margin: 5px 0 0 0;
        opacity: 0.8;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .content-section {
        padding: 0 15px;
    }

    .modern-card {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 15px;
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }

    .card-header-modern {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 15px;
    }

    .title-section h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .date-badge {
        background: var(--bg-primary);
        color: var(--text-primary);
        padding: 8px 14px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-block;
        margin-top: 5px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
    }

    .status-section {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: flex-end;
    }

    .status-pill {
        padding: 7px 16px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-selesai {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        color: white;
        box-shadow: 0 3px 8px rgba(76, 175, 80, 0.4),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .status-proses {
        background: linear-gradient(135deg, #FF9800, #FFB74D);
        color: white;
        box-shadow: 0 3px 8px rgba(255, 152, 0, 0.4),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .status-bersih {
        background: linear-gradient(135deg, #2196F3, #42A5F5);
        color: white;
        box-shadow: 0 3px 8px rgba(33, 150, 243, 0.4),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .status-kotor {
        background: linear-gradient(135deg, #f44336, #EF5350);
        color: white;
        box-shadow: 0 3px 8px rgba(244, 67, 54, 0.4),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .petugas-modern {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .petugas-title {
        font-size: 0.7rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .petugas-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .petugas-item {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .petugas-item:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .petugas-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--bg-primary);
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary);
        font-weight: 800;
        font-size: 0.85rem;
    }

    .petugas-name {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.3;
    }

    .saldo-modern {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .saldo-box-modern {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 15px;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .saldo-box-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
    }

    .saldo-box-modern.awal,
    .saldo-box-modern.masuk,
    .saldo-box-modern.belanja,
    .saldo-box-modern.akhir {
        background: var(--bg-primary);
    }

    .saldo-label-modern {
        font-size: 0.65rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .saldo-value-modern {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-primary);
        position: relative;
        z-index: 1;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0 12px 0;
        padding-left: 5px;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--bg-primary);
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .section-count {
        background: var(--bg-primary);
        color: var(--text-primary);
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .belanja-item-modern {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }

    .belanja-item-modern::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--bg-primary);
        border-radius: 15px 0 0 15px;
        box-shadow: inset 2px 0 4px var(--shadow-dark);
    }

    .belanja-item-modern:active {
        transform: none;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .belanja-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .belanja-name {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .belanja-price {
        background: var(--bg-primary);
        color: var(--text-primary);
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 800;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
    }

    .belanja-detail {
        display: flex;
        gap: 15px;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .belanja-note {
        margin-top: 8px;
        padding: 8px;
        background: var(--bg-primary);
        border-radius: 8px;
        font-size: 0.7rem;
        color: var(--text-secondary);
        font-style: italic;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .total-card {
        background: var(--bg-primary);
        color: var(--text-primary);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        margin-top: 15px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
    }

    .total-label {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-bottom: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-secondary);
    }

    .total-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .foto-gallery {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .foto-item-modern {
        position: relative;
        padding-top: 100%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .foto-item-modern:active {
        transform: scale(0.98);
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .foto-item-modern img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .foto-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        padding: 10px;
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .empty-state-modern {
        text-align: center;
        padding: 40px 20px;
        background: var(--bg-primary);
        border-radius: 20px;
        box-shadow: inset 5px 5px 10px var(--shadow-dark),
                   inset -5px -5px 10px var(--shadow-light);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        background: var(--bg-primary);
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 2.5rem;
        opacity: 0.6;
    }

    .modal-photo-modern {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        animation: fadeIn 0.3s;
    }

    .photo-container-modern {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .photo-container-modern img {
        max-width: 95%;
        max-height: 90vh;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(255, 255, 255, 0.2);
    }

    .close-photo-modern {
        position: absolute;
        top: 20px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: var(--bg-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary);
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        transition: all 0.3s ease;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5),
                   -5px -5px 10px rgba(255, 255, 255, 0.1);
    }

    .close-photo-modern:active {
        transform: scale(0.95);
        box-shadow: inset 3px 3px 6px rgba(0, 0, 0, 0.5),
                   inset -3px -3px 6px rgba(255, 255, 255, 0.1);
    }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="header-section">
    <a href="{{ route('khidmat.karyawan.index') }}" class="back-btn">
        ←
    </a>
    <div class="header-title">
        <h3>📋 Detail Khidmat</h3>
        <p>Laporan Lengkap Belanja</p>
    </div>
</div>

<div class="content-section">
    <!-- Info Jadwal Modern -->
    <div class="modern-card">
        <div class="card-header-modern">
            <div class="title-section">
                <h4>
                    🍳 {{ $jadwal->nama_kelompok }}
                </h4>
                <span class="date-badge">
                    📅 {{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </span>
            </div>
            <div class="status-section">
                <span class="status-pill {{ $jadwal->status_selesai ? 'status-selesai' : 'status-proses' }}">
                    {{ $jadwal->status_selesai ? '✓' : '○' }} {{ $jadwal->status_selesai ? 'Selesai' : 'Proses' }}
                </span>
                <span class="status-pill {{ $jadwal->status_kebersihan == 'bersih' ? 'status-bersih' : 'status-kotor' }}">
                    {{ $jadwal->status_kebersihan == 'bersih' ? '✓' : '⚠' }} {{ ucfirst($jadwal->status_kebersihan) }}
                </span>
            </div>
        </div>

        <!-- Petugas Modern -->
        <div class="petugas-modern">
            <div class="petugas-title">👥 Petugas Khidmat</div>
            <div class="petugas-grid">
                @foreach($jadwal->petugas as $petugas)
                <div class="petugas-item">
                    <div class="petugas-avatar">
                        {{ strtoupper(substr($petugas->santri->nama_lengkap, 0, 2)) }}
                    </div>
                    <div class="petugas-name">
                        {{ $petugas->santri->nama_lengkap }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Saldo Summary Modern -->
        <div class="saldo-modern">
            <div class="saldo-box-modern awal">
                <div class="saldo-label-modern">Saldo Awal</div>
                <div class="saldo-value-modern">Rp {{ number_format($jadwal->saldo_awal / 1000, 0) }}K</div>
            </div>
            <div class="saldo-box-modern masuk">
                <div class="saldo-label-modern">Saldo Masuk</div>
                <div class="saldo-value-modern">Rp {{ number_format($jadwal->saldo_masuk / 1000, 0) }}K</div>
            </div>
            <div class="saldo-box-modern belanja">
                <div class="saldo-label-modern">Total Belanja</div>
                <div class="saldo-value-modern">Rp {{ number_format($jadwal->total_belanja / 1000, 0) }}K</div>
            </div>
            <div class="saldo-box-modern akhir">
                <div class="saldo-label-modern">Saldo Akhir</div>
                <div class="saldo-value-modern">Rp {{ number_format($jadwal->saldo_akhir / 1000, 0) }}K</div>
            </div>
        </div>
    </div>

    <!-- Detail Belanja -->
    <div class="section-header">
        <div class="section-icon">🛒</div>
        <div class="section-title">Detail Belanja</div>
        <div class="section-count">{{ $jadwal->belanja->count() }} Item</div>
    </div>

    @forelse($jadwal->belanja as $item)
    <div class="belanja-item-modern">
        <div class="belanja-main">
            <div class="belanja-name">{{ $item->nama_barang }}</div>
            <div class="belanja-price">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
        </div>
        <div class="belanja-detail">
            <span>📦 {{ $item->jumlah }} {{ $item->satuan }}</span>
            <span>💵 @ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
        </div>
        @if($item->keterangan)
        <div class="belanja-note">
            📝 {{ $item->keterangan }}
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state-modern">
        <div class="empty-icon">🛒</div>
        <h6 style="margin: 0 0 5px 0; color: #2c3e50;">Belum Ada Belanja</h6>
        <p style="margin: 0; font-size: 0.8rem; color: #6c757d;">Belum ada data belanja yang tercatat</p>
    </div>
    @endforelse

    <!-- Total Belanja -->
    @if($jadwal->belanja->count() > 0)
    <div class="total-card">
        <div class="total-label">💰 Total Keseluruhan Belanja</div>
        <div class="total-value">Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</div>
    </div>
    @endif

    <!-- Foto Belanja -->
    @if($jadwal->foto->count() > 0)
    <div class="section-header">
        <div class="section-icon">📸</div>
        <div class="section-title">Foto Belanja</div>
        <div class="section-count">{{ $jadwal->foto->count() }} Foto</div>
    </div>

    <div class="modern-card">
        <div class="foto-gallery">
            @foreach($jadwal->foto as $foto)
            <div class="foto-item-modern" onclick="showPhoto('{{ asset('storage/' . $foto->path_file) }}')">
                <img src="{{ asset('storage/' . $foto->path_file) }}" alt="Foto Belanja" loading="lazy">
                <div class="foto-overlay">
                    🔍 Tap untuk zoom
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($jadwal->keterangan)
    <div class="section-header">
        <div class="section-icon">📝</div>
        <div class="section-title">Catatan</div>
    </div>
    <div class="modern-card">
        <p style="margin: 0; color: #6c757d; font-size: 0.85rem; line-height: 1.6;">{{ $jadwal->keterangan }}</p>
    </div>
    @endif
</div>

<!-- Modal Photo Modern -->
<div class="modal-photo-modern" id="modalPhoto" onclick="closePhoto()">
    <div class="close-photo-modern" onclick="closePhoto()">✕</div>
    <div class="photo-container-modern">
        <img id="photoImage" src="" alt="Foto Belanja">
    </div>
</div>

@endsection

@push('myscript')
<script>
function showPhoto(src) {
    document.getElementById('photoImage').src = src;
    document.getElementById('modalPhoto').style.display = 'flex';
}

function closePhoto() {
    document.getElementById('modalPhoto').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhoto();
    }
});
</script>
@endpush
