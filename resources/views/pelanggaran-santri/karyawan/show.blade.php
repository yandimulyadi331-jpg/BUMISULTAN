@extends('layouts.mobile.app')
@section('content')
<style>
    :root {
        /* Minimal Elegant Colors */
        --bg-body-light: #ecf0f3;
        --bg-primary-light: #ecf0f3;
        --shadow-dark-light: #d1d9e6;
        --shadow-light-light: #ffffff;
        --text-primary-light: #2c3e50;
        --text-secondary-light: #6c7a89;
        --border-light: rgba(0, 0, 0, 0.05);

        --bg-body-dark: #1a202c;
        --bg-primary-dark: #2d3748;
        --shadow-dark-dark: #141923;
        --shadow-light-dark: #3a4555;
        --text-primary-dark: #f7fafc;
        --text-secondary-dark: #a0aec0;
        --border-dark: rgba(255, 255, 255, 0.08);

        /* Accent Colors */
        --gradient-1: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-body);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-bottom: 80px;
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
        margin-bottom: 20px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
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
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        transition: all 0.3s ease;
        text-decoration: none;
        flex-shrink: 0;
    }

    .back-btn:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .back-btn ion-icon {
        font-size: 24px;
        color: var(--text-primary);
    }

    .header-title {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .header-title h3 {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .header-title p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 600;
        margin-top: 5px;
        letter-spacing: 0.3px;
    }

    /* ========== CONTENT ========== */
    #content-section {
        padding: 20px;
        padding-bottom: 100px;
    }

    .info-card {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
    }

    .santri-profile {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--border-color);
    }

    .profile-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
    }

    .avatar-initial {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: var(--gradient-1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.5rem;
        margin-right: 15px;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
    }

    .profile-info h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .profile-info p {
        margin: 5px 0 0 0;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .info-item ion-icon {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .stat-box {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 18px;
        text-align: center;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .stat-label {
        font-size: 0.7rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        margin-bottom: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .status-box {
        text-align: center;
        margin-top: 20px;
    }

    .badge-status {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 15px;
    }

    .badge-aman {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    .badge-peringatan {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
    }

    .badge-bahaya {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title ion-icon {
        font-size: 20px;
    }

    .riwayat-item {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .riwayat-item:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .riwayat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .riwayat-date {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .riwayat-date ion-icon {
        font-size: 1rem;
    }

    .point-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .point-badge ion-icon {
        font-size: 1rem;
    }

    .riwayat-desc {
        font-size: 0.9rem;
        color: var(--text-primary);
        margin: 10px 0;
        line-height: 1.5;
    }

    .riwayat-foto {
        margin: 12px 0;
        border-radius: 12px;
        overflow: hidden;
        max-width: 200px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
    }

    .riwayat-foto img {
        width: 100%;
        max-height: 150px;
        object-fit: cover;
        display: block;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .riwayat-foto img:hover {
        transform: scale(1.05);
    }

    .riwayat-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        margin-top: 12px;
        border-top: 1px solid var(--border-light);
    }

    body.dark-mode .riwayat-meta {
        border-top-color: var(--border-dark);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .meta-item ion-icon {
        font-size: 0.9rem;
    }

    .empty-state {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 50px 20px;
        text-align: center;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
    }

    .empty-state ion-icon {
        font-size: 80px;
        color: var(--text-secondary);
        opacity: 0.5;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--text-secondary);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .riwayat-item {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .riwayat-item:nth-child(1) { animation-delay: 0.05s; }
    .riwayat-item:nth-child(2) { animation-delay: 0.1s; }
    .riwayat-item:nth-child(3) { animation-delay: 0.15s; }
    .riwayat-item:nth-child(4) { animation-delay: 0.2s; }
    .riwayat-item:nth-child(5) { animation-delay: 0.25s; }
</style>

<!-- HEADER -->
<div id="header-section">
    <div class="header-content">
        <a href="{{ route('pelanggaran-santri.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>Detail Riwayat Pelanggaran</h3>
            <p>Histori Lengkap Pelanggaran Santri</p>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div id="content-section">
    <!-- Info Card (Profile + Stats) -->
    <div class="info-card">
        <!-- Santri Profile -->
        <div class="santri-profile">
            @if($santri->foto && file_exists(public_path('storage/santri/' . $santri->foto)))
                <img src="{{ asset('storage/santri/' . $santri->foto) }}" 
                     alt="{{ $santri->nama_lengkap }}" 
                     class="profile-avatar">
            @else
                <div class="avatar-initial">
                    {{ strtoupper(substr($santri->nama_lengkap, 0, 2)) }}
                </div>
            @endif
            <div class="profile-info">
                <h4>{{ $santri->nama_lengkap }}</h4>
                <div class="info-item">
                    <ion-icon name="card-outline"></ion-icon>
                    <span>{{ $santri->nik ?? 'No NIK' }}</span>
                </div>
                <div class="info-item">
                    <ion-icon name="person-outline"></ion-icon>
                    <span>{{ $santri->nama_panggilan ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Pelanggaran</div>
                <div class="stat-value">{{ $totalPelanggaran }}x</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Point</div>
                <div class="stat-value">{{ $totalPoint }}</div>
            </div>
        </div>

        <div class="status-box mt-3">
            <div class="stat-label">Status Pelanggaran</div>
            <span class="badge-status 
                @if($totalPoint < 5) badge-aman
                @elseif($totalPoint < 8) badge-peringatan
                @else badge-bahaya
                @endif">
                {{ $statusInfo['status'] }}
            </span>
        </div>
    </div>

    <!-- Riwayat Pelanggaran -->
    <div class="section-title">
        <ion-icon name="time-outline"></ion-icon>
        <span>Riwayat Pelanggaran ({{ $riwayatPelanggaran->count() }})</span>
    </div>

    @forelse($riwayatPelanggaran as $pelanggaran)
    <div class="riwayat-item">
        <div class="riwayat-header">
            <div class="riwayat-date">
                <ion-icon name="calendar-outline"></ion-icon>
                <span>{{ \Carbon\Carbon::parse($pelanggaran->tanggal_pelanggaran)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
            </div>
            <div class="point-badge">
                <ion-icon name="alert-circle-outline"></ion-icon>
                <span>{{ $pelanggaran->point }} Point</span>
            </div>
        </div>

        <div class="riwayat-desc">
            {{ $pelanggaran->keterangan }}
        </div>

        @if($pelanggaran->foto)
        <div class="riwayat-foto">
            <img src="{{ asset('storage/' . $pelanggaran->foto) }}" 
                 alt="Foto Pelanggaran" 
                 onclick="window.open('{{ asset('storage/' . $pelanggaran->foto) }}', '_blank')">
        </div>
        @endif

        <div class="riwayat-meta">
            <div class="meta-item">
                <ion-icon name="person-outline"></ion-icon>
                <span>{{ $pelanggaran->pencatat->name ?? 'System' }}</span>
            </div>
            <div class="meta-item">
                <ion-icon name="time-outline"></ion-icon>
                <span>{{ \Carbon\Carbon::parse($pelanggaran->created_at)->diffForHumans() }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <ion-icon name="clipboard-outline"></ion-icon>
        <h5>Belum Ada Riwayat</h5>
        <p class="text-muted">Santri ini belum memiliki catatan pelanggaran</p>
    </div>
    @endforelse
</div>

@endsection
