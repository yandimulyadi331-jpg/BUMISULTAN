@extends('layouts.mobile.app')
@section('content')

<style>
    body {
        background: #f5f7fb;
    }
    
    .header-section {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        padding: 20px;
        color: white;
        margin: -16px -16px 20px -16px;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: white;
        text-decoration: none;
        margin-bottom: 15px;
    }
    
    .peminjaman-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .peminjaman-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }
    
    .vehicle-name {
        font-weight: 900;
        font-size: 1.1rem;
        color: #1a202c;
    }
    
    .peminjaman-info {
        margin-top: 10px;
        font-size: 0.9rem;
        line-height: 1.8;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 8px;
    }
    
    .info-label {
        flex: 0 0 120px;
        color: #718096;
        font-weight: 700;
    }
    
    .info-value {
        flex: 1;
        color: #2d3748;
        font-weight: 600;
    }
</style>

<div class="header-section">
    <a href="{{ route('kendaraan.karyawan.index') }}" class="back-btn">
        <ion-icon name="arrow-back-outline" style="font-size: 1.3rem;"></ion-icon>
    </a>
    <h2 style="font-weight: 900; margin: 0;">Riwayat Peminjaman</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Daftar Pengajuan Peminjaman Saya</p>
</div>

@if($peminjaman->count() > 0)
    @foreach($peminjaman as $p)
        <div class="peminjaman-card">
            <div class="peminjaman-header">
                <div>
                    <div class="vehicle-name">
                        <ion-icon name="car-sport"></ion-icon>
                        {{ $p->kendaraan->nama_kendaraan }}
                    </div>
                    <small style="color: #718096;">{{ $p->kode_peminjaman }}</small>
                </div>
                <div>
                    @php
                        $statusColors = [
                            'Pending' => 'warning',
                            'Disetujui' => 'success',
                            'Ditolak' => 'danger',
                            'Selesai' => 'info',
                            'Batal' => 'secondary'
                        ];
                        $statusColor = $statusColors[$p->status_pengajuan] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusColor }}">{{ $p->status_pengajuan }}</span>
                </div>
            </div>
            
            <div class="peminjaman-info">
                <div class="info-row">
                    <div class="info-label">
                        <ion-icon name="calendar-outline"></ion-icon> Pinjam
                    </div>
                    <div class="info-value">{{ $p->tanggal_pinjam->format('d M Y H:i') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">
                        <ion-icon name="calendar-outline"></ion-icon> Kembali
                    </div>
                    <div class="info-value">{{ $p->tanggal_kembali->format('d M Y H:i') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">
                        <ion-icon name="location-outline"></ion-icon> Tujuan
                    </div>
                    <div class="info-value">{{ $p->tujuan_penggunaan }}</div>
                </div>
                
                @if($p->disetujui_oleh)
                <div class="info-row">
                    <div class="info-label">
                        <ion-icon name="person-outline"></ion-icon> Disetujui
                    </div>
                    <div class="info-value">{{ $p->disetujui_oleh }}</div>
                </div>
                @endif
                
                @if($p->catatan_persetujuan)
                <div style="margin-top: 10px; padding: 10px; background: #f7fafc; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #718096; font-weight: 700; margin-bottom: 5px;">CATATAN:</div>
                    <div style="font-size: 0.85rem; color: #2d3748;">{{ $p->catatan_persetujuan }}</div>
                </div>
                @endif
            </div>
        </div>
    @endforeach
    
    @if($peminjaman->hasPages())
        <div style="margin-top: 20px;">
            {{ $peminjaman->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif
@else
    <div style="text-align: center; padding: 60px 20px;">
        <ion-icon name="calendar-outline" style="font-size: 5rem; color: #cbd5e0;"></ion-icon>
        <h4 style="margin-top: 20px; color: #4a5568;">Belum Ada Peminjaman</h4>
        <p style="color: #718096;">Anda belum pernah mengajukan peminjaman kendaraan</p>
    </div>
@endif

<div style="height: 80px;"></div>

@endsection