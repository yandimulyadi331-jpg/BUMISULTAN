@extends('layouts.mobile.app')
@section('content')
<style>
    body {
        background: var(--bg-primary);
        min-height: 100vh;
    }
    
    .perawatan-container {
        background: transparent;
        min-height: 100vh;
        padding: 20px;
    }
    
    /* Header */
    .page-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .back-button {
        width: 45px;
        height: 45px;
        background: var(--bg-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--icon-color);
        text-decoration: none;
        border: none;
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 10px 10px 20px var(--shadow-dark),
                   -10px -10px 20px var(--shadow-light);
    }
    
    .back-button:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }
    
    .header-info {
        flex: 1;
    }
    
    .header-title {
        color: var(--text-primary);
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }
    
    .header-subtitle {
        color: var(--text-secondary);
        font-size: 13px;
    }
    
    /* Filter Tabs */
    .filter-tabs {
        background: var(--bg-primary);
        border-radius: 16px;
        padding: 12px;
        margin-bottom: 20px;
        border: none;
        overflow-x: auto;
        scrollbar-width: none;
        box-shadow: 10px 10px 20px var(--shadow-dark),
                   -10px -10px 20px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    .filter-tabs::-webkit-scrollbar {
        display: none;
    }
    
    .filter-tabs .nav {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
    }
    
    .filter-tabs .nav-link {
        border: none;
        color: var(--text-primary);
        font-size: 13px;
        padding: 10px 20px;
        border-radius: 12px;
        background: var(--bg-primary);
        transition: all 0.3s ease;
        white-space: nowrap;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .filter-tabs .nav-link:hover {
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }
    
    .filter-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        color: #fff;
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                   inset -2px -2px 6px rgba(255,255,255,0.1),
                   0 4px 15px rgba(39, 174, 96, 0.4);
    }
    
    /* History Item */
    .history-item {
        background: var(--bg-primary);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        border: none;
        border-left: 4px solid var(--badge-green);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .history-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--badge-green), #52c77a);
        box-shadow: 0 0 10px rgba(39, 174, 96, 0.3);
    }
    
    .history-item:hover {
        transform: translateX(5px);
        box-shadow: 10px 10px 20px var(--shadow-dark),
                   -10px -10px 20px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.08);
    }
    
    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 10px;
    }
    
    .history-title {
        color: var(--text-primary);
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 8px;
        flex: 1;
    }
    
    .badge-completed {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        color: #fff;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: 4px 4px 8px rgba(0,0,0,0.2),
                   -2px -2px 6px rgba(255,255,255,0.1),
                   inset 0 1px 2px rgba(255,255,255,0.2);
    }
    
    .history-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    
    .kategori-badge {
        background: var(--bg-primary);
        color: var(--badge-green);
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        border: none;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }
    
    .history-time {
        color: var(--text-secondary);
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .note-section {
        background: var(--bg-primary);
        border-radius: 10px;
        padding: 10px;
        border: none;
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
        margin-top: 10px;
    }
    
    .note-text {
        color: var(--text-secondary);
        font-size: 12px;
    }
    
    .foto-bukti-thumb {
        width: 100%;
        max-height: 160px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        margin-top: 10px;
        border: none;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .foto-bukti-thumb:hover {
        transform: scale(1.02);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }
    
    .foto-caption {
        color: var(--text-secondary);
        font-size: 11px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: var(--bg-primary);
        border-radius: 20px;
        box-shadow: inset 4px 4px 12px var(--shadow-dark),
                   inset -4px -4px 12px var(--shadow-light);
        margin: 20px;
    }
    
    .empty-icon {
        font-size: 80px;
        color: var(--icon-color);
        opacity: 0.4;
        margin-bottom: 20px;
    }
    
    .empty-text {
        color: var(--text-secondary);
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        border: none;
        color: #fff;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        display: inline-block;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light),
                   inset 0 1px 2px rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light),
                   inset 0 1px 2px rgba(255,255,255,0.4);
    }
    
    /* Modal */
    .modal-content {
        background: var(--bg-primary);
        border: none;
        border-radius: 20px;
        box-shadow: 10px 10px 30px var(--shadow-dark),
                   -10px -10px 30px var(--shadow-light);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .modal-title {
        color: var(--text-primary);
    }
    
    .btn-close {
        filter: none;
        opacity: 0.6;
    }
    
    .modal-body {
        text-align: center;
    }
    
    .modal-body img {
        border-radius: 12px;
        border: none;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    /* Pagination */
    .pagination {
        margin-top: 20px;
        justify-content: center;
    }
    
    .page-link {
        background: var(--bg-primary);
        border: none;
        color: var(--text-primary);
        border-radius: 8px;
        margin: 0 4px;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background: var(--bg-primary);
        color: var(--badge-green);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        border: none;
        color: #fff;
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                   inset -2px -2px 6px rgba(255,255,255,0.1),
                   0 4px 12px rgba(39, 174, 96, 0.3);
    }
</style>

<div class="perawatan-container">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('perawatan.karyawan.index') }}" class="back-button">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div class="header-info">
            <h1 class="header-title"><i class="ti ti-history"></i> History</h1>
            <div class="header-subtitle">Riwayat checklist Anda</div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tipe === 'all' ? 'active' : '' }}" 
                   href="{{ route('perawatan.karyawan.history', ['tipe' => 'all']) }}">
                    Semua
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tipe === 'harian' ? 'active' : '' }}" 
                   href="{{ route('perawatan.karyawan.history', ['tipe' => 'harian']) }}">
                    Harian
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tipe === 'mingguan' ? 'active' : '' }}" 
                   href="{{ route('perawatan.karyawan.history', ['tipe' => 'mingguan']) }}">
                    Mingguan
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tipe === 'bulanan' ? 'active' : '' }}" 
                   href="{{ route('perawatan.karyawan.history', ['tipe' => 'bulanan']) }}">
                    Bulanan
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tipe === 'tahunan' ? 'active' : '' }}" 
                   href="{{ route('perawatan.karyawan.history', ['tipe' => 'tahunan']) }}">
                    Tahunan
                </a>
            </li>
        </ul>
    </div>

    <!-- History Items -->
    @forelse($histories as $history)
        <div class="history-item">
            <div class="history-header">
                <div class="history-title">{{ $history->masterPerawatan->nama_kegiatan }}</div>
                <span class="badge-completed">
                    <i class="ti ti-check"></i> Selesai
                </span>
            </div>
            
            <div class="history-badges">
                @php
                    $kategoriBadge = [
                        'kebersihan' => ['icon' => 'wash', 'text' => 'Kebersihan'],
                        'perawatan_rutin' => ['icon' => 'tool', 'text' => 'Perawatan Rutin'],
                        'pengecekan' => ['icon' => 'search', 'text' => 'Pengecekan'],
                        'lainnya' => ['icon' => 'list', 'text' => 'Lainnya']
                    ];
                    $badge = $kategoriBadge[$history->masterPerawatan->kategori] ?? ['icon' => 'list', 'text' => 'Lainnya'];
                @endphp
                <span class="kategori-badge">
                    <i class="ti ti-{{ $badge['icon'] }}"></i> {{ $badge['text'] }}
                </span>
                <span class="kategori-badge">
                    @if($history->masterPerawatan->tipe_periode === 'harian') <i class="ti ti-sun"></i>
                    @elseif($history->masterPerawatan->tipe_periode === 'mingguan') <i class="ti ti-calendar-week"></i>
                    @elseif($history->masterPerawatan->tipe_periode === 'bulanan') <i class="ti ti-calendar-month"></i>
                    @else <i class="ti ti-calendar-event"></i>
                    @endif
                    {{ ucfirst($history->masterPerawatan->tipe_periode) }}
                </span>
            </div>
            
            <div class="history-time">
                <span><i class="ti ti-calendar"></i> {{ $history->tanggal_eksekusi->format('d M Y') }}</span>
                <span><i class="ti ti-clock"></i> {{ date('H:i', strtotime($history->waktu_eksekusi)) }}</span>
            </div>
            
            @if($history->catatan)
            <div class="note-section">
                <div class="note-text">
                    <i class="ti ti-note"></i> <strong>Catatan:</strong> {{ $history->catatan }}
                </div>
            </div>
            @endif
            
            @if($history->foto_bukti)
            <div>
                <img src="{{ asset('storage/perawatan/' . $history->foto_bukti) }}" 
                     class="foto-bukti-thumb" 
                     alt="Foto Bukti"
                     data-bs-toggle="modal" 
                     data-bs-target="#modalFoto{{ $history->id }}">
                <div class="foto-caption">
                    <i class="ti ti-camera"></i> Klik untuk memperbesar
                </div>
            </div>
            
            <!-- Modal Foto -->
            <div class="modal fade" id="modalFoto{{ $history->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="ti ti-camera"></i> Foto Bukti</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('storage/perawatan/' . $history->foto_bukti) }}" 
                                 class="img-fluid" 
                                 alt="Foto Bukti">
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon"><i class="ti ti-clipboard-off"></i></div>
            <div class="empty-text">Belum ada history aktivitas</div>
            <a href="{{ route('perawatan.karyawan.index') }}" class="btn-primary-custom">
                <i class="ti ti-clipboard-check"></i> Mulai Checklist
            </a>
        </div>
    @endforelse
    
    <!-- Pagination -->
    @if($histories->hasPages())
    <div class="mt-4">
        {{ $histories->links() }}
    </div>
    @endif
</div>

<div style="height: 100px;"></div>

@endsection
