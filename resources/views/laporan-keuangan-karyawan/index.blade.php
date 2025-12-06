@extends('layouts.mobile.app')

@section('content')
<style>
    body {
        background: var(--bg-primary);
        min-height: 100vh;
    }
    
    .container {
        padding-bottom: 80px;
        padding-top: 20px;
    }
    
    .report-card {
        background: var(--bg-primary);
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        max-width: 100%;
        box-shadow: 10px 10px 20px var(--shadow-dark),
                   -10px -10px 20px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    .report-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--badge-green), #52c77a);
        opacity: 0;
        transition: opacity 0.3s;
        box-shadow: 0 0 10px rgba(39, 174, 96, 0.5);
    }
    
    .report-card:hover::before {
        opacity: 1;
    }
    
    .report-card:hover {
        transform: translateY(-6px);
        box-shadow: 12px 12px 24px var(--shadow-dark),
                   -12px -12px 24px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
    
    .file-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light),
                   inset 0 2px 4px rgba(255, 255, 255, 0.3);
    }
    
    .file-icon::before {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 0 16px 0 16px;
    }
    
    .progress-ring {
        font-size: 48px;
        font-weight: 700;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 2px 2px 4px var(--shadow-dark);
    }
    
    .filter-pill {
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .filter-pill.active {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                   inset -2px -2px 6px rgba(255,255,255,0.1),
                   0 4px 15px rgba(239, 68, 68, 0.4);
    }
    
    .filter-pill:not(.active):hover {
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        transform: translateY(-2px);
    }
    
    .badge-period {
        background: var(--bg-primary);
        color: #ef4444;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }
    
    .btn-download {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light),
                   inset 0 1px 2px rgba(255, 255, 255, 0.3);
    }
    
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light),
                   inset 0 1px 2px rgba(255, 255, 255, 0.4);
        color: white;
    }
    
    .btn-download:active {
        transform: translateY(0);
        box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2),
                   inset -2px -2px 4px rgba(255, 255, 255, 0.1);
    }
    
    .btn-detail {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .btn-detail:hover {
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        transform: translateY(-2px);
        color: var(--text-primary);
    }
    
    .btn-detail:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
        transform: translateY(0);
    }
</style>

<div class="container" style="padding-bottom: 80px; padding-top: 20px;">
    <!-- Header -->
    <div class="text-center mb-4">
        <h4 class="mb-2" style="font-weight: 700; color: var(--text-primary); letter-spacing: -0.5px;">
            Laporan Keuangan
        </h4>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Riwayat Laporan Keuangan Anda
        </p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-4 text-center">
        <div class="d-inline-flex gap-2 p-3" style="border-radius: 16px; background: var(--bg-primary); box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light), inset 0 1px 0 rgba(255, 255, 255, 0.1);">
            <a href="{{ route('laporan-keuangan-karyawan.index') }}" 
               class="filter-pill {{ !request('jenis') ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('laporan-keuangan-karyawan.index', ['jenis' => 'mingguan']) }}" 
               class="filter-pill {{ request('jenis') == 'mingguan' ? 'active' : '' }}">
                Mingguan
            </a>
            <a href="{{ route('laporan-keuangan-karyawan.index', ['jenis' => 'bulanan']) }}" 
               class="filter-pill {{ request('jenis') == 'bulanan' ? 'active' : '' }}">
                Bulanan
            </a>
            <a href="{{ route('laporan-keuangan-karyawan.index', ['jenis' => 'tahunan']) }}" 
               class="filter-pill {{ request('jenis') == 'tahunan' ? 'active' : '' }}">
                Tahunan
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid #10b981;">
        <ion-icon name="checkmark-circle" style="font-size: 20px; vertical-align: middle; margin-right: 8px;"></ion-icon>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid #ef4444;">
        <ion-icon name="alert-circle" style="font-size: 20px; vertical-align: middle; margin-right: 8px;"></ion-icon>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- List Laporan -->
    @if($laporans->count() > 0)
        @foreach($laporans as $laporan)
        <div class="report-card mb-3">
            <div style="padding: 20px;">
                <!-- Close Button -->
                <div class="text-end mb-2">
                    <button onclick="closeCard(this)" style="background: var(--bg-primary); border: none; color: var(--text-secondary); width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);">
                        <ion-icon name="close" style="font-size: 24px;"></ion-icon>
                    </button>
                </div>

                <!-- File Icon & Name -->
                <div class="text-center mb-3">
                    <!-- Big File Icon -->
                    <div style="display: inline-block; position: relative; margin-bottom: 16px;">
                        <!-- Main File Icon -->
                        <div style="width: 100px; height: 120px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; position: relative; box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);">
                            <!-- Folded Corner -->
                            <div style="position: absolute; top: 0; right: 0; width: 30px; height: 30px; background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.1) 100%); border-radius: 0 12px 0 0; clip-path: polygon(0 0, 100% 0, 100% 100%);"></div>
                            
                            <!-- PDF Badge -->
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--bg-primary); padding: 8px 16px; border-radius: 8px; box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);">
                                <span style="color: var(--text-primary); font-weight: 700; font-size: 16px; letter-spacing: 1px;">PDF</span>
                            </div>
                        </div>
                    </div>

                    <!-- File Name & Size -->
                    <h6 style="color: var(--text-primary); font-weight: 600; font-size: 15px; margin-bottom: 4px;">
                        {{ $laporan->nama_laporan }}.pdf
                    </h6>
                    <p style="color: var(--text-secondary); font-size: 13px; margin: 0;">
                        {{ number_format((strlen($laporan->file_pdf ?? '') * 8) / 1024, 2) }} KB
                    </p>
                </div>


            </div>

            <!-- Actions Footer (Outside main card padding) -->
            <div style="padding: 0 20px 16px 20px;">
                <div class="d-flex gap-2">
                    <a href="{{ route('laporan-keuangan-karyawan.show', $laporan->id) }}" 
                       class="btn-detail" style="flex: 1; text-decoration: none;">
                        <ion-icon name="eye-outline" style="font-size: 16px;"></ion-icon>
                        Detail
                    </a>
                    
                    @if($laporan->file_pdf)
                    <a href="{{ route('laporan-keuangan-karyawan.download-pdf', $laporan->id) }}" 
                       class="btn-download" style="text-decoration: none;">
                        <ion-icon name="download-outline" style="font-size: 16px;"></ion-icon>
                        PDF
                    </a>
                    @endif
                </div>

                <!-- Metadata -->
                <div class="d-flex gap-2 mt-2 justify-content-between align-items-center">
                    <span class="badge-period">
                        {{ strtoupper($laporan->periode) }}
                    </span>
                    <small style="color: var(--text-secondary); font-size: 11px;">
                        {{ \Carbon\Carbon::parse($laporan->published_at)->format('d M Y') }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-4">
            {{ $laporans->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center" style="padding: 60px 20px; border-radius: 20px; background: var(--bg-primary); box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light);">
            <ion-icon name="file-tray-outline" style="font-size: 80px; color: var(--text-secondary); margin-bottom: 20px; opacity: 0.5;"></ion-icon>
            <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 8px;">Belum Ada Laporan</h6>
            <p style="color: var(--text-secondary); font-size: 13px; margin: 0;">
                {{ request('jenis') ? 'Belum ada laporan ' . request('jenis') . ' yang dipublish.' : 'Belum ada laporan keuangan yang dipublish oleh admin.' }}
            </p>
        </div>
    @endif


</div>

<style>
@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.spinner-border {
    animation: spinner-border .75s linear infinite;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}

button:hover {
    background: rgba(255, 255, 255, 0.2) !important;
}
</style>

<script>
function closeCard(btn) {
    const card = btn.closest('.report-card');
    card.style.transition = 'all 0.3s ease';
    card.style.opacity = '0';
    card.style.transform = 'scale(0.9)';
    setTimeout(() => {
        card.style.display = 'none';
    }, 300);
}
</script>
@endsection
