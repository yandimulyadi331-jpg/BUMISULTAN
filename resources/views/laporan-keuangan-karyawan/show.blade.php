@extends('layouts.mobile.app')

@section('content')
<style>
    body {
        background: var(--bg-primary);
        min-height: 100vh;
    }
    
    .pdf-viewer-container {
        background: var(--bg-primary);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light);
    }
    
    .pdf-toolbar {
        background: var(--bg-primary);
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
    }
    
    .btn-back {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
    }
    
    .btn-back:hover {
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        color: var(--text-primary);
    }
    
    .btn-back:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
    }
    
    .btn-download {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light), 0 0 20px rgba(239, 68, 68, 0.3);
    }
    
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light), 0 0 25px rgba(239, 68, 68, 0.4);
        color: white;
    }
    
    .btn-download:active {
        transform: translateY(0);
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2), inset -4px -4px 8px rgba(255,255,255,0.1);
    }
    
    .badge-period {
        background: var(--bg-primary);
        color: var(--badge-red);
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
    }
    
    .btn-download-alt {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light), 0 0 20px rgba(239, 68, 68, 0.3);
        transition: all 0.3s;
    }
    
    .btn-download-alt:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light), 0 0 25px rgba(239, 68, 68, 0.4);
        color: white;
    }
    
    .btn-excel {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light), 0 0 20px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }
    
    .btn-excel:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light), 0 0 25px rgba(16, 185, 129, 0.4);
        color: white;
    }
</style>

<div class="container" style="padding: 20px 16px 80px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('laporan-keuangan-karyawan.index') }}" class="btn-back">
            <ion-icon name="arrow-back" style="font-size: 18px;"></ion-icon>
            Kembali
        </a>
        
        @if($laporan->file_pdf)
        <a href="{{ route('laporan-keuangan-karyawan.download-pdf', $laporan->id) }}" class="btn-download">
            <ion-icon name="download-outline" style="font-size: 18px;"></ion-icon>
            Download
        </a>
        @endif
    </div>

    <!-- Title -->
    <div class="text-center mb-3">
        <h5 style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">{{ $laporan->nama_laporan }}</h5>
        <div class="d-flex gap-2 justify-content-center align-items-center flex-wrap">
            <span class="badge-period">
                {{ strtoupper($laporan->periode) }}
            </span>
            <span style="color: var(--text-secondary); font-size: 12px;">
                <ion-icon name="calendar-outline" style="vertical-align: middle;"></ion-icon>
                {{ \Carbon\Carbon::parse($laporan->published_at)->format('d M Y') }}
            </span>
        </div>
    </div>

    <!-- PDF Preview -->
    @if($laporan->file_pdf)
    <div class="pdf-viewer-container">
        <div class="pdf-toolbar">
            <div style="color: var(--text-primary); font-size: 13px; font-weight: 500;">
                <ion-icon name="document-text" style="vertical-align: middle; margin-right: 6px;"></ion-icon>
                Preview Laporan
            </div>
            <div style="color: var(--text-secondary); font-size: 12px;">
                {{ number_format((strlen($laporan->file_pdf ?? '') * 8) / 1024, 2) }} KB
            </div>
        </div>
        
        <div style="position: relative; width: 100%; height: 70vh; background: var(--bg-primary);">
            <iframe 
                src="{{ asset('storage/' . $laporan->file_pdf) }}#toolbar=1&navpanes=0&scrollbar=1" 
                style="width: 100%; height: 100%; border: none;"
                frameborder="0">
            </iframe>
        </div>
        
        <!-- Bottom Info -->
        <div style="background: var(--bg-primary); padding: 12px 16px; box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);">
            <div class="d-flex justify-content-between align-items-center">
                <small style="color: var(--text-secondary); font-size: 11px;">
                    <ion-icon name="person-circle-outline" style="vertical-align: middle;"></ion-icon>
                    Dipublish oleh {{ $laporan->publisher_name ?? 'Admin' }}
                </small>
                <small style="color: var(--text-secondary); font-size: 11px;">
                    {{ \Carbon\Carbon::parse($laporan->published_at)->format('d M Y H:i') }}
                </small>
            </div>
        </div>
    </div>
    @else
    <div class="pdf-viewer-container text-center" style="padding: 60px 20px;">
        <ion-icon name="document-text-outline" style="font-size: 80px; color: var(--text-secondary); margin-bottom: 20px; opacity: 0.5;"></ion-icon>
        <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 8px;">PDF Tidak Tersedia</h6>
        <p style="color: var(--text-secondary); font-size: 13px; margin: 0;">
            File PDF untuk laporan ini belum tersedia atau sudah dihapus.
        </p>
    </div>
    @endif

    <!-- Alternative Download Section (if PDF preview fails) -->
    <div class="text-center mt-3">
        <p style="color: var(--text-secondary); font-size: 12px; margin-bottom: 12px;">
            Tidak bisa melihat preview? Download file PDF-nya.
        </p>
        
        <div class="d-flex gap-2 justify-content-center">
            @if($laporan->file_pdf)
            <a href="{{ route('laporan-keuangan-karyawan.download-pdf', $laporan->id) }}" class="btn-download-alt">
                <ion-icon name="document-text" style="font-size: 18px;"></ion-icon>
                Download PDF
            </a>
            @endif

            @if($laporan->file_excel)
            <a href="{{ route('laporan-keuangan-karyawan.download-excel', $laporan->id) }}" class="btn-excel">
                <ion-icon name="document" style="font-size: 18px;"></ion-icon>
                Download Excel
            </a>
            @endif
        </div>
    </div>
</div>
@endsection