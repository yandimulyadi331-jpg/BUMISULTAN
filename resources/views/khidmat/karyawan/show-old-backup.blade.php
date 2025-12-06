@extends('layouts.mobile.app')
@section('content')
<style>
    body {
        background: #f0f2f5;
        min-height: 100vh;
        padding-bottom: 80px;
    }

    .header-section {
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        padding: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .back-btn {
        color: white;
        font-size: 24px;
        text-decoration: none;
        position: absolute;
        left: 15px;
        top: 15px;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .header-title {
        text-align: center;
        color: white;
    }

    .header-title h3 {
        font-weight: bold;
        margin: 0;
        font-size: 1.1rem;
    }

    .header-title p {
        margin: 3px 0 0 0;
        opacity: 0.9;
        font-size: 0.75rem;
    }

    .content-section {
        padding: 15px 10px;
    }

    .modern-card {
        background: white;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #e9ecef;
    }

    .card-header-modern {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f2f5;
        margin-bottom: 12px;
    }

    .title-section h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .date-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 5px;
    }

    .status-section {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: flex-end;
    }

    .status-pill {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-selesai {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .status-proses {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .status-bersih {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .status-kotor {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .petugas-modern {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
    }

    .petugas-title {
        font-size: 0.7rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .petugas-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .petugas-item {
        background: white;
        border-radius: 10px;
        padding: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .petugas-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.8rem;
    }

    .petugas-name {
        font-size: 0.75rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.2;
    }

    .saldo-modern {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .saldo-box-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .saldo-box-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .saldo-box-modern.awal {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .saldo-box-modern.masuk {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .saldo-box-modern.belanja {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .saldo-box-modern.akhir {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .saldo-label-modern {
        font-size: 0.65rem;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
        letter-spacing: 0.5px;
    }

    .saldo-value-modern {
        font-size: 0.95rem;
        font-weight: 700;
        color: white;
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
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .section-count {
        background: #e9ecef;
        color: #6c757d;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .belanja-item-modern {
        background: white;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .belanja-item-modern:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .belanja-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .belanja-name {
        font-weight: 700;
        color: #2c3e50;
        font-size: 0.9rem;
    }

    .belanja-price {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .belanja-detail {
        display: flex;
        gap: 15px;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .belanja-note {
        margin-top: 8px;
        padding: 8px;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 0.7rem;
        color: #6c757d;
        font-style: italic;
    }

    .total-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        margin-top: 15px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .total-label {
        font-size: 0.8rem;
        opacity: 0.95;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .total-value {
        font-size: 1.8rem;
        font-weight: 800;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
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
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .foto-item-modern:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
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
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        transition: all 0.3s ease;
    }

    .close-photo-modern:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
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
