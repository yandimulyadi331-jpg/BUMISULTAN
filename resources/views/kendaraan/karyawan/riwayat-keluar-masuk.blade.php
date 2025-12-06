@extends('layouts.mobile.app')
@section('content')
<div id="header-section">
    <div id="section-back">
        <a href="{{ route('kendaraan.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div id="header-title">
        <h3>Riwayat Keluar/Masuk Kendaraan</h3>
        <p>Aktivitas kendaraan yang kamu lakukan</p>
    </div>
</div>

<div id="content-section">
    <div class="section-subtitle">
        <ion-icon name="car-sport"></ion-icon>
        Riwayat Aktivitas
    </div>
    @if($riwayat->count() > 0)
        @foreach($riwayat as $log)
            <div class="card mb-3" style="border-radius:18px;box-shadow:0 4px 16px rgba(102,126,234,.08);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <ion-icon name="{{ $log->tipe == 'Keluar' ? 'exit-outline' : 'enter-outline' }}" style="font-size:1.5rem;color:{{ $log->tipe == 'Keluar' ? '#10b981' : '#f59e0b' }};"></ion-icon>
                        <div class="ms-2">
                            <strong>{{ $log->tipe }}</strong>
                            <span class="badge bg-{{ $log->tipe == 'Keluar' ? 'success' : 'warning' }} ms-2">{{ $log->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                    <div><b>Kendaraan:</b> {{ $log->kendaraan->nama_kendaraan ?? '-' }} ({{ $log->kendaraan->no_polisi ?? '-' }})</div>
                    <div><b>Pengemudi:</b> {{ $log->pengemudi }}</div>
                    <div><b>Petugas:</b> {{ $log->petugas }}</div>
                    <div><b>Kondisi:</b> {{ $log->tipe == 'Keluar' ? $log->kondisi_keluar : $log->kondisi_masuk }}</div>
                    <div><b>Jumlah Penumpang:</b> {{ $log->jumlah_penumpang ?? '-' }}</div>
                    <div><b>Tujuan:</b> {{ $log->tujuan ?? '-' }}</div>
                    <div><b>Keperluan:</b> {{ $log->keperluan ?? '-' }}</div>
                    <div><b>Keterangan:</b> {{ $log->keterangan ?? '-' }}</div>
                </div>
            </div>
        @endforeach
    @else
        <div style="text-align:center;padding:60px 20px;">
            <ion-icon name="car-sport-outline" style="font-size:5rem;color:#cbd5e0;"></ion-icon>
            <h4 style="margin-top:20px;color:#4a5568;">Belum Ada Aktivitas</h4>
            <p style="color:#718096;">Kamu belum melakukan aktivitas keluar/masuk kendaraan</p>
        </div>
    @endif
</div>
@endsection
