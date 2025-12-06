@extends('layouts.mobile.app')
@section('content')
<style>
    body {
        background: linear-gradient(180deg, #e3f4f9 0%, #f8fbfc 100%);
    }

    #header-section {
        padding: 25px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0 0 35px 35px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        margin-bottom: 20px;
    }

    .back-btn {
        color: #ffffff;
        font-size: 28px;
        text-decoration: none;
    }

    #header-title h3 {
        color: #ffffff;
        font-weight: 900;
        margin: 15px 0 5px 0;
        font-size: 1.8rem;
        text-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }

    #header-title p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.95rem;
        margin: 0;
    }
    
    /* Ilustrasi Kendaraan Berjalan - Inline di samping tombol */
    .vehicle-animation-inline {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        display: none;
    }
    
    .vehicle-animation-inline.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .vehicle-moving-inline {
        position: absolute;
        top: 50%;
        transform: translateY(-50%) scaleX(-1);
        left: -50px;
        font-size: 32px;
        animation: moveVehicleInline 3s linear infinite;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
    }
    
    @keyframes moveVehicleInline {
        0% {
            left: -50px;
        }
        100% {
            left: 100%;
        }
    }
    
    .road-line-inline {
        position: absolute;
        top: 50%;
        width: 60px;
        height: 2px;
        background: rgba(255,255,255,0.5);
        animation: moveLineInline 1s linear infinite;
    }
    
    @keyframes moveLineInline {
        0% {
            left: 100%;
        }
        100% {
            left: -60px;
        }
    }
    
    .vehicle-status-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 12px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        z-index: 1;
        text-align: center;
    }
</style>

<!-- Header Section -->
<div id="header-section">
    <div id="section-back">
        <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div id="header-title">
        <h3>Detail Kendaraan</h3>
        <p>{{ $kendaraan->nama_kendaraan ?? 'Informasi Kendaraan' }}</p>
    </div>
</div>

<!-- Content Section -->
<div style="padding: 0 15px 100px 15px;">

<!-- Swipeable Kendaraan Cards -->
<div class="mb-4">
    <div class="position-relative">
        <!-- Navigation Arrows -->
        <button id="prevBtn" class="btn btn-light position-absolute" 
                style="left: -15px; top: 50%; transform: translateY(-50%); z-index: 10; border-radius: 50%; width: 40px; height: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <ion-icon name="chevron-back-outline" style="font-size: 20px;"></ion-icon>
        </button>
        <button id="nextBtn" class="btn btn-light position-absolute" 
                style="right: -15px; top: 50%; transform: translateY(-50%); z-index: 10; border-radius: 50%; width: 40px; height: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <ion-icon name="chevron-forward-outline" style="font-size: 20px;"></ion-icon>
        </button>
        
        <!-- Cards Container -->
        <div id="kendaraanCardsContainer" class="overflow-hidden">
            <div id="kendaraanCards" class="d-flex transition-all" style="transition: transform 0.3s ease;">
                @foreach($allKendaraan as $index => $k)
                <div class="kendaraan-card flex-shrink-0 {{ $index == $currentIndex ? 'active' : '' }}" 
                     style="width: 100%; padding: 0 5px;"
                     data-index="{{ $index }}"
                     data-id="{{ $k->id }}">
                    <div class="card" style="border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); {{ $index == $currentIndex ? 'border: 3px solid #667eea;' : '' }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    @if($k->foto)
                                        <img src="{{ asset('storage/kendaraan/' . $k->foto) }}" class="img-fluid rounded" alt="Foto" style="max-height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <ion-icon name="car-outline" style="font-size: 48px; color: #ccc;"></ion-icon>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-9">
                                    <h4 class="mb-2">{{ $k->nama_kendaraan }} 
                                        <span class="badge bg-primary">{{ $k->no_polisi }}</span>
                                    </h4>
                                    <p class="text-muted mb-2">{{ $k->jenis_kendaraan }} - {{ $k->merk }} {{ $k->model }}</p>
                                    <div class="row">
                                        <div class="col-6">
                                            <small><strong>Kode:</strong> {{ $k->kode_kendaraan }}</small><br>
                                            <small><strong>Warna:</strong> {{ $k->warna ?? '-' }}</small>
                                        </div>
                                        <div class="col-6">
                                            <small><strong>No. Mesin:</strong> {{ $k->no_mesin ?? '-' }}</small><br>
                                            <small><strong>Kapasitas:</strong> {{ $k->kapasitas ?? '-' }} orang</small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small><strong>Status:</strong></small>
                                        @if($k->status == 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($k->status == 'keluar')
                                            <span class="badge bg-info">Sedang Keluar</span>
                                        @elseif($k->status == 'dipinjam')
                                            <span class="badge bg-primary">Dipinjam</span>
                                        @else
                                            <span class="badge bg-danger">Service</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Indicator Dots -->
        <div class="text-center mt-3">
            <div id="carouselIndicators" class="d-inline-flex gap-2">
                @foreach($allKendaraan as $index => $k)
                <span class="indicator-dot {{ $index == $currentIndex ? 'active' : '' }}" 
                      data-index="{{ $index }}"
                      style="width: 8px; height: 8px; border-radius: 50%; background: {{ $index == $currentIndex ? '#667eea' : '#ddd' }}; cursor: pointer; transition: all 0.3s;"></span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'aktivitas' || !Request::get('tab') ? 'active' : '' }}" 
                data-toggle="tab" data-target="#aktivitas-tab">
            <i class="ti ti-car me-2"></i>Aktivitas Keluar/Masuk
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'peminjaman' ? 'active' : '' }}" 
                data-toggle="tab" data-target="#peminjaman-tab">
            <i class="ti ti-user-check me-2"></i>Peminjaman
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'service' ? 'active' : '' }}" 
                data-toggle="tab" data-target="#service-tab">
            <i class="ti ti-tool me-2"></i>Service
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    <!-- Aktivitas Tab -->
    <div class="tab-pane fade {{ Request::get('tab') == 'aktivitas' || !Request::get('tab') ? 'show active' : '' }}" id="aktivitas-tab">
        <div class="row">
            <!-- Action Cards -->
            <div class="col-md-12 mb-3">
                <div class="row g-3">
                    @if($kendaraan->status == 'tersedia')
                    <div class="col-md-3 col-6">
                        <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#modalKeluarKendaraan">
                            <div class="card bg-primary text-white hover-shadow" style="min-height: 80px;">
                                <div class="card-body text-center py-2">
                                    <ion-icon name="arrow-forward-outline" style="font-size: 32px;"></ion-icon>
                                    <h6 class="mt-2 mb-0">Keluar</h6>
                                    <small style="font-size: 11px;">Tandai Keluar</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    @if($kendaraan->aktivitasAktif)
                    <div class="col-md-6 col-12 mb-2">
                        <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#modalKembaliKendaraan">
                            <div class="card bg-success text-white hover-shadow" style="height: 100%; min-height: 80px;">
                                <div class="card-body d-flex align-items-center justify-content-center gap-2" style="padding: 15px;">
                                    <ion-icon name="arrow-back-outline" style="font-size: 32px;"></ion-icon>
                                    <div class="text-start">
                                        <h6 class="mb-0">Tandai Kembali</h6>
                                        <small style="font-size: 11px;">Kendaraan Kembali</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Animasi Kendaraan Berjalan -->
                    <div class="col-md-6 col-12 mb-2">
                        <div class="vehicle-animation-inline {{ $kendaraan->status == 'keluar' ? 'active' : '' }}" id="vehicleAnimationInline">
                            <div class="vehicle-status-text">
                                <ion-icon name="car-sport" style="font-size: 16px; vertical-align: middle;"></ion-icon>
                                Kendaraan Berjalan
                            </div>
                            <div class="vehicle-moving-inline">🚗</div>
                            <div class="road-line-inline" style="left: 0%;"></div>
                            <div class="road-line-inline" style="left: 25%;"></div>
                            <div class="road-line-inline" style="left: 50%;"></div>
                            <div class="road-line-inline" style="left: 75%;"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            @if($kendaraan->aktivitas->count() > 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Pengemudi</th>
                                        <th>Tujuan</th>
                                        <th>Penumpang</th>
                                        <th>Waktu Keluar</th>
                                        <th>Waktu Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->aktivitas as $a)
                                    <tr>
                                        <td>{{ $a->kode_aktivitas }}</td>
                                        <td>{{ $a->driver }}</td>
                                        <td>{{ Str::limit($a->tujuan, 30) }}</td>
                                        <td>
                                            @if($a->penumpang)
                                                {{ Str::limit($a->penumpang, 30) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($a->waktu_keluar)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($a->waktu_kembali)
                                                {{ \Carbon\Carbon::parse($a->waktu_kembali)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="badge bg-warning">Belum Kembali</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($a->status == 'keluar')
                                                <span class="badge bg-info">Keluar</span>
                                            @else
                                                <span class="badge bg-success">Kembali</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Peminjaman Tab -->
    <div class="tab-pane fade {{ Request::get('tab') == 'peminjaman' ? 'show active' : '' }}" id="peminjaman-tab">
        <div class="row">
            <!-- Action Cards -->
            <div class="col-md-12 mb-3">
                <div class="row g-3">
                    @if($kendaraan->status == 'tersedia')
                    <div class="col-md-3">
                        <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#modalPinjamKendaraan">
                            <div class="card bg-warning text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-hand-grab" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Pinjam</h5>
                                    <small>Pinjam Kendaraan</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    @if($kendaraan->peminjamanAktif)
                    <div class="col-md-3">
                        <a href="{{ route('kendaraan.peminjaman.kembali', Crypt::encrypt($kendaraan->peminjamanAktif->id)) }}" class="text-decoration-none">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-arrow-back" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Kembalikan</h5>
                                    <small>Kendaraan Dikembalikan</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('kendaraan.peminjaman.tracking', Crypt::encrypt($kendaraan->peminjamanAktif->id)) }}" class="text-decoration-none" target="_blank">
                            <div class="card bg-info text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-map" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">GPS Tracking</h5>
                                    <small>Riwayat GPS</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Peminjaman -->
            @if($kendaraan->peminjaman->count() > 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <ion-icon name="time-outline" style="vertical-align: middle;"></ion-icon>
                            Riwayat Peminjaman Kendaraan
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($kendaraan->peminjaman as $p)
                        <div class="border-bottom p-3 hover-bg-light" style="transition: all 0.2s;">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start">
                                        <!-- Foto Identitas -->
                                        <div class="me-3">
                                            @if($p->foto_identitas)
                                                <img src="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}" 
                                                    alt="Foto Identitas" 
                                                    class="rounded foto-popup" 
                                                    style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border: 2px solid #f59e0b;"
                                                    data-foto="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}"
                                                    data-title="Foto Identitas - {{ $p->nama_peminjam }}">
                                            @else
                                                <div class="rounded bg-warning d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <ion-icon name="person" style="font-size: 30px; color: white;"></ion-icon>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Info Peminjaman -->
                                        <div class="flex-grow-1">
                                            <div class="mb-2">
                                                <h6 class="mb-1 fw-bold">
                                                    <span class="badge bg-warning">{{ $p->kode_peminjaman }}</span>
                                                    {{ $p->nama_peminjam }}
                                                </h6>
                                                <p class="mb-1 text-muted small">
                                                    <ion-icon name="document-text-outline"></ion-icon>
                                                    <strong>Keperluan:</strong> {{ $p->keperluan }}
                                                </p>
                                            </div>
                                            
                                            <div class="row g-2 small">
                                                <div class="col-md-6">
                                                    <div class="mb-1">
                                                        <ion-icon name="calendar-outline" class="text-warning"></ion-icon>
                                                        <strong>Waktu Pinjam:</strong><br>
                                                        <span class="ms-3">{{ \Carbon\Carbon::parse($p->waktu_pinjam)->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <ion-icon name="calendar-outline" class="text-success"></ion-icon>
                                                        <strong>Waktu Kembali:</strong><br>
                                                        <span class="ms-3">
                                                            @if($p->waktu_kembali)
                                                                {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}
                                                            @else
                                                                <span class="badge bg-warning">Belum Kembali</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-1">
                                                        <ion-icon name="speedometer-outline" class="text-primary"></ion-icon>
                                                        <strong>KM:</strong> 
                                                        {{ number_format($p->km_awal ?? 0) }} km 
                                                        @if($p->km_akhir)
                                                            → {{ number_format($p->km_akhir) }} km
                                                            <span class="text-success">(+{{ number_format($p->km_akhir - ($p->km_awal ?? 0)) }} km)</span>
                                                        @endif
                                                    </div>
                                                    <div class="mb-1">
                                                        <ion-icon name="water-outline" class="text-info"></ion-icon>
                                                        <strong>BBM:</strong> 
                                                        {{ $p->status_bbm_pinjam ?? '-' }}
                                                        @if($p->status_bbm_kembali)
                                                            → {{ $p->status_bbm_kembali }}
                                                        @endif
                                                    </div>
                                                    @if($p->kondisi_kendaraan)
                                                    <div class="mb-1">
                                                        <ion-icon name="checkmark-circle-outline" class="text-success"></ion-icon>
                                                        <strong>Kondisi:</strong> {{ $p->kondisi_kendaraan }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($p->keterangan)
                                            <div class="mt-2 small">
                                                <div class="alert alert-info mb-0 py-1 px-2">
                                                    <ion-icon name="information-circle-outline"></ion-icon>
                                                    <strong>Keterangan:</strong> {{ $p->keterangan }}
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($p->catatan_kembali)
                                            <div class="mt-2 small">
                                                <div class="alert alert-success mb-0 py-1 px-2">
                                                    <ion-icon name="chatbox-outline"></ion-icon>
                                                    <strong>Catatan Pengembalian:</strong> {{ $p->catatan_kembali }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Status & Foto Kembali -->
                                <div class="col-md-4 text-end">
                                    <div class="mb-2">
                                        @if($p->status == 'dipinjam')
                                            <span class="badge bg-primary" style="font-size: 14px; padding: 8px 15px;">
                                                <ion-icon name="hand-left-outline"></ion-icon> Sedang Dipinjam
                                            </span>
                                        @else
                                            <span class="badge bg-success" style="font-size: 14px; padding: 8px 15px;">
                                                <ion-icon name="checkmark-circle-outline"></ion-icon> Sudah Dikembalikan
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($p->foto_kembali)
                                    <div class="mt-2">
                                        <small class="text-muted d-block mb-1">Foto Pengembalian:</small>
                                        <img src="{{ asset('storage/peminjaman/' . $p->foto_kembali) }}" 
                                            alt="Foto Kembali" 
                                            class="rounded foto-popup" 
                                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid #10b981;"
                                            data-foto="{{ asset('storage/peminjaman/' . $p->foto_kembali) }}"
                                            data-title="Foto Pengembalian - {{ $p->nama_peminjam }}">
                                    </div>
                                    @endif
                                    
                                    <!-- Button Detail -->
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-toggle="modal" 
                                                data-target="#modalDetailPeminjaman{{ $p->id }}">
                                            <ion-icon name="eye-outline"></ion-icon> Detail Lengkap
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Detail Peminjaman -->
                        <div class="modal fade" id="modalDetailPeminjaman{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title text-white">
                                            <ion-icon name="document-text-outline"></ion-icon>
                                            Detail Peminjaman - {{ $p->kode_peminjaman }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Peminjam:</strong><br>
                                                {{ $p->nama_peminjam }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>No. HP:</strong><br>
                                                {{ $p->no_hp ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <strong>Keperluan:</strong><br>
                                                {{ $p->keperluan }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Waktu Pinjam:</strong><br>
                                                {{ \Carbon\Carbon::parse($p->waktu_pinjam)->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Rencana Kembali:</strong><br>
                                                {{ $p->waktu_rencana_kembali ? \Carbon\Carbon::parse($p->waktu_rencana_kembali)->format('d/m/Y H:i') : '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Waktu Kembali Aktual:</strong><br>
                                                @if($p->waktu_kembali)
                                                    {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="badge bg-warning">Belum Kembali</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Status:</strong><br>
                                                @if($p->status == 'dipinjam')
                                                    <span class="badge bg-primary">Dipinjam</span>
                                                @else
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h6 class="fw-bold">Kondisi Kendaraan</h6>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>KM Awal:</strong><br>
                                                {{ number_format($p->km_awal ?? 0) }} km
                                            </div>
                                            <div class="col-md-6">
                                                <strong>KM Akhir:</strong><br>
                                                {{ $p->km_akhir ? number_format($p->km_akhir) . ' km' : '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>BBM Pinjam:</strong><br>
                                                {{ $p->status_bbm_pinjam ?? '-' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>BBM Kembali:</strong><br>
                                                {{ $p->status_bbm_kembali ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <strong>Kondisi Kendaraan:</strong><br>
                                                {{ $p->kondisi_kendaraan ?? '-' }}
                                            </div>
                                        </div>
                                        @if($p->keterangan || $p->catatan_kembali)
                                        <hr>
                                        <h6 class="fw-bold">Catatan</h6>
                                        @if($p->keterangan)
                                        <div class="mb-2">
                                            <strong>Keterangan Peminjaman:</strong><br>
                                            <div class="alert alert-info">{{ $p->keterangan }}</div>
                                        </div>
                                        @endif
                                        @if($p->catatan_kembali)
                                        <div class="mb-2">
                                            <strong>Catatan Pengembalian:</strong><br>
                                            <div class="alert alert-success">{{ $p->catatan_kembali }}</div>
                                        </div>
                                        @endif
                                        @endif
                                        @if($p->foto_identitas || $p->foto_kembali)
                                        <hr>
                                        <h6 class="fw-bold">Dokumentasi</h6>
                                        <div class="row">
                                            @if($p->foto_identitas)
                                            <div class="col-md-6 mb-2">
                                                <strong>Foto Identitas:</strong><br>
                                                <img src="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}" 
                                                    class="img-fluid rounded mt-2" alt="Foto Identitas">
                                            </div>
                                            @endif
                                            @if($p->foto_kembali)
                                            <div class="col-md-6 mb-2">
                                                <strong>Foto Pengembalian:</strong><br>
                                                <img src="{{ asset('storage/peminjaman/' . $p->foto_kembali) }}" 
                                                    class="img-fluid rounded mt-2" alt="Foto Kembali">
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-12">
                <div class="alert alert-info">
                    <ion-icon name="information-circle-outline"></ion-icon>
                    Belum ada riwayat peminjaman untuk kendaraan ini
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Service Tab -->
    <div class="tab-pane fade {{ Request::get('tab') == 'service' ? 'show active' : '' }}" id="service-tab">
        <div class="row">
            <!-- Action Cards -->
            <div class="col-md-12 mb-3">
                <div class="row g-3">
                    @if($kendaraan->status == 'tersedia')
                        <!-- Tombol Service dihilangkan, hanya admin yang bisa input sebelum service -->
                    @endif
                    
                    @if($kendaraan->serviceAktif)
                    <div class="col-md-3">
                        <button type="button" class="text-decoration-none border-0 p-0 w-100 btn-selesai-service-trigger" data-toggle="modal" data-target="#modalSelesaiService" style="background: none; cursor: pointer;">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-check" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Selesai Service</h5>
                                    <small>Tandai Selesai</small>
                                </div>
                            </div>
                        </button>
                    </div>
                    @else
                    <!-- Debug: Service Aktif tidak ada -->
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <ion-icon name="information-circle-outline"></ion-icon>
                            Tidak ada service aktif saat ini. Tombol "Selesai Service" akan muncul jika ada service yang sedang berjalan.
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Service -->
            @if($kendaraan->services->count() > 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Service Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Jenis Service</th>
                                        <th>Bengkel</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->services as $s)
                                    <tr>
                                        <td>{{ $s->kode_service }}</td>
                                        <td>{{ $s->jenis_service }}</td>
                                        <td>{{ $s->bengkel }}</td>
                                        <td>{{ \Carbon\Carbon::parse($s->waktu_service)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($s->waktu_selesai)
                                                {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('d/m/Y') }}
                                            @else
                                                <span class="badge bg-warning">Dalam Service</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($s->biaya_akhir ?? $s->estimasi_biaya ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @if($s->status == 'proses')
                                                <span class="badge bg-warning">Dalam Service</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalDetailService{{ $s->id }}" title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Modal Detail Service -->
                        @foreach($kendaraan->services as $s)
                        <div class="modal fade" id="modalDetailService{{ $s->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title text-white">Detail Service - {{ $s->kode_service }}</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Jenis Service:</strong><br>
                                                {{ $s->jenis_service }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Bengkel:</strong><br>
                                                {{ $s->bengkel }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Waktu Masuk:</strong><br>
                                                {{ \Carbon\Carbon::parse($s->waktu_service)->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Waktu Selesai:</strong><br>
                                                @if($s->waktu_selesai)
                                                    {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="badge bg-warning">Belum Selesai</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>KM Service:</strong><br>
                                                {{ number_format($s->km_service, 0, ',', '.') }} km
                                            </div>
                                            <div class="col-md-6">
                                                <strong>KM Selesai:</strong><br>
                                                {{ $s->km_selesai ? number_format($s->km_selesai, 0, ',', '.') . ' km' : '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Estimasi Biaya:</strong><br>
                                                Rp {{ number_format($s->estimasi_biaya, 0, ',', '.') }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Biaya Akhir:</strong><br>
                                                {{ $s->biaya_akhir ? 'Rp ' . number_format($s->biaya_akhir, 0, ',', '.') : '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <strong>Deskripsi Kerusakan:</strong><br>
                                                {{ $s->deskripsi_kerusakan }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <strong>Pekerjaan:</strong><br>
                                                {{ $s->pekerjaan_selesai ?? $s->pekerjaan ?? '-' }}
                                            </div>
                                        </div>
                                        @if($s->catatan_mekanik)
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <strong>Catatan Mekanik:</strong><br>
                                                <div class="alert alert-info mb-0">{{ $s->catatan_mekanik }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>PIC / Mekanik:</strong><br>
                                                {{ $s->pic_selesai ?? $s->pic ?? '-' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Kondisi Kendaraan:</strong><br>
                                                {{ $s->kondisi_kendaraan ?? '-' }}
                                            </div>
                                        </div>
                                        @if($s->foto_before || $s->foto_after)
                                        <div class="row">
                                            @if($s->foto_before)
                                            <div class="col-md-6">
                                                <strong>Foto Sebelum:</strong><br>
                                                <img src="{{ asset('storage/service/' . $s->foto_before) }}" class="img-fluid rounded mt-2" alt="Foto Before">
                                            </div>
                                            @endif
                                            @if($s->foto_after)
                                            <div class="col-md-6">
                                                <strong>Foto Setelah:</strong><br>
                                                <img src="{{ asset('storage/service/' . $s->foto_after) }}" class="img-fluid rounded mt-2" alt="Foto After">
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

</div>
<!-- End Content Section -->

@push('myscript')
<style>
    .hover-shadow:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .transition-all {
        transition: transform 0.3s ease;
    }
    .indicator-dot:hover {
        transform: scale(1.3);
    }
    .hover-bg-light:hover {
        background-color: #f9fafb;
    }
</style>

<!-- Modal Popup Foto -->
<div class="modal fade" id="modalFotoPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoPeminjamanTitle">Foto</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFotoPeminjamanImage" src="" alt="Foto" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Pinjam Kendaraan -->
<div class="modal fade" id="modalPinjamKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="hand-left-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Pinjam Kendaraan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form id="formPinjamKendaraan" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <input type="hidden" name="kendaraan_id" id="kendaraan_id_pinjam" value="{{ $kendaraan->id }}">
                
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Kendaraan -->
                    <div class="alert alert-warning" style="border-radius: 15px; border-left: 4px solid #f59e0b;">
                        <div class="d-flex align-items-center">
                            <ion-icon name="information-circle-outline" style="font-size: 32px; margin-right: 10px;"></ion-icon>
                            <div>
                                <strong>{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small>{{ $kendaraan->no_polisi }} - {{ $kendaraan->jenis_kendaraan }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="person-outline"></ion-icon> Nama Peminjam *
                        </label>
                        <input type="text" class="form-control" name="nama_peminjam" required 
                               value="{{ auth()->user()->name ?? '' }}"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="Nama peminjam">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="call-outline"></ion-icon> No HP Peminjam *
                        </label>
                        <input type="text" class="form-control" name="no_hp_peminjam" required 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="08xxxx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="mail-outline"></ion-icon> Email Peminjam
                        </label>
                        <input type="email" class="form-control" name="email_peminjam" 
                               value="{{ auth()->user()->email ?? '' }}"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="email@example.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="card-outline"></ion-icon> Foto KTP/Identitas *
                        </label>
                        <input type="file" class="form-control" name="foto_identitas" accept="image/*" required 
                               id="foto_identitas_pinjam"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        <small class="text-muted d-block mt-1">Upload foto KTP, SIM, atau identitas lainnya (Max: 2MB, Format: JPG/PNG)</small>
                        <div class="mt-2" id="preview_container_pinjam" style="display: none;">
                            <img id="preview_identitas_pinjam" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 10px;" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="document-text-outline"></ion-icon> Keperluan *
                        </label>
                        <textarea class="form-control" name="keperluan" rows="2" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Masukkan keperluan peminjaman"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="calendar-outline"></ion-icon> Tanggal Pinjam *
                                </label>
                                <input type="date" class="form-control" name="tanggal_pinjam" required 
                                       value="{{ date('Y-m-d') }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="time-outline"></ion-icon> Jam Pinjam *
                                </label>
                                <input type="time" class="form-control" name="jam_pinjam" required 
                                       value="{{ date('H:i') }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="calendar-outline"></ion-icon> Tanggal Kembali *
                                </label>
                                <input type="date" class="form-control" name="tanggal_rencana_kembali" required 
                                       value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="time-outline"></ion-icon> Jam Kembali *
                                </label>
                                <input type="time" class="form-control" name="jam_rencana_kembali" required 
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="speedometer-outline"></ion-icon> KM Awal
                                </label>
                                <input type="number" class="form-control" name="km_awal" 
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="water-outline"></ion-icon> Status BBM
                                </label>
                                <select class="form-select" name="status_bbm_pinjam" 
                                        style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                    <option value="Penuh">Penuh</option>
                                    <option value="3/4">3/4</option>
                                    <option value="1/2">1/2</option>
                                    <option value="1/4">1/4</option>
                                    <option value="Kosong">Kosong</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="location-outline"></ion-icon> Lokasi GPS Saat Ini
                        </label>
                        <input type="text" class="form-control" id="lokasi_display_pinjam" readonly 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px; background: #f9fafb;"
                               placeholder="Mencari lokasi...">
                        <input type="hidden" name="latitude_pinjam" id="latitude_pinjam">
                        <input type="hidden" name="longitude_pinjam" id="longitude_pinjam">
                        <small class="text-muted d-block mt-1">GPS akan otomatis terdeteksi</small>
                    </div>

                    <!-- Signature Pad -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="create-outline"></ion-icon> Tanda Tangan Digital Peminjam *
                        </label>
                        <div style="border: 2px solid #e3e6f0; border-radius: 10px; overflow: hidden; background: white;">
                            <canvas id="signature-pad-pinjam" style="width: 100%; height: 150px; display: block; touch-action: none;"></canvas>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearSignaturePinjam()" style="border-radius: 8px;">
                            <ion-icon name="refresh-outline"></ion-icon> Bersihkan
                        </button>
                        <small class="text-muted d-block mt-1">Tanda tangan di kotak putih di atas</small>
                        <input type="hidden" name="ttd_peminjam" id="signature-data-pinjam">
                    </div>
                </div>

                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-circle-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Keluar Kendaraan -->
<div class="modal fade" id="modalKeluarKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="car-sport-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Kendaraan Keluar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('kendaraan.aktivitas.prosesKeluar', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formKeluarKendaraan" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Kendaraan -->
                    <div class="alert alert-info" style="border-radius: 15px; border-left: 4px solid #667eea;">
                        <div class="d-flex align-items-center">
                            <ion-icon name="information-circle-outline" style="font-size: 32px; margin-right: 10px;"></ion-icon>
                            <div>
                                <strong>{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small>{{ $kendaraan->no_polisi }} - {{ $kendaraan->jenis_kendaraan }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="person-outline"></ion-icon> Nama Pengemudi *
                        </label>
                        <input type="text" class="form-control" name="nama_pengemudi" required 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="Masukkan nama pengemudi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="call-outline"></ion-icon> No. HP Pengemudi
                        </label>
                        <input type="text" class="form-control" name="no_hp_pengemudi" 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="08xxxx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="people-outline"></ion-icon> Penumpang
                        </label>
                        <input type="text" class="form-control" name="penumpang" 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="Nama penumpang (opsional)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="location-outline"></ion-icon> Tujuan *
                        </label>
                        <textarea class="form-control" name="tujuan" rows="2" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Tujuan perjalanan"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal *
                            </label>
                            <input type="date" class="form-control" name="tanggal_keluar" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam *
                            </label>
                            <input type="time" class="form-control" name="jam_keluar" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Awal
                            </label>
                            <input type="number" class="form-control" name="km_awal" 
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                   placeholder="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="water-outline"></ion-icon> Status BBM
                            </label>
                            <select class="form-select" name="status_bbm_keluar" 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">Pilih</option>
                                <option value="Penuh">Penuh</option>
                                <option value="3/4">3/4</option>
                                <option value="1/2">1/2</option>
                                <option value="1/4">1/4</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_keluar" id="latitude_keluar">
                    <input type="hidden" name="longitude_keluar" id="longitude_keluar">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-circle-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kembali Kendaraan -->
@if($kendaraan->aktivitasAktif)
<div class="modal fade" id="modalKembaliKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="checkmark-circle-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Kendaraan Kembali
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('kendaraan.aktivitas.prosesKembali', Crypt::encrypt($kendaraan->aktivitasAktif->id)) }}" method="POST" id="formKembaliKendaraan" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Aktivitas -->
                    <div class="alert alert-success" style="border-radius: 15px; border-left: 4px solid #10b981;">
                        <div class="d-flex align-items-center">
                            <ion-icon name="information-circle-outline" style="font-size: 32px; margin-right: 10px;"></ion-icon>
                            <div>
                                <strong>{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small>Driver: {{ $kendaraan->aktivitasAktif->driver }}</small><br>
                                <small>Tujuan: {{ $kendaraan->aktivitasAktif->tujuan }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal *
                            </label>
                            <input type="date" class="form-control" name="tanggal_kembali" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam *
                            </label>
                            <input type="time" class="form-control" name="jam_kembali" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Akhir *
                            </label>
                            <input type="number" class="form-control" name="km_akhir" required 
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                   placeholder="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="water-outline"></ion-icon> Status BBM
                            </label>
                            <select class="form-select" name="status_bbm_kembali" 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">Pilih</option>
                                <option value="Penuh">Penuh</option>
                                <option value="3/4">3/4</option>
                                <option value="1/2">1/2</option>
                                <option value="1/4">1/4</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="3" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_kembali" id="latitude_kembali">
                    <input type="hidden" name="longitude_kembali" id="longitude_kembali">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-done-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    let currentIndex = {{ $currentIndex }};
    let totalKendaraan = {{ count($allKendaraan) }};
    let allKendaraanData = @json($allKendaraan);
    
    // Initialize signature pad for pinjam modal
    let canvasPinjam = document.getElementById('signature-pad-pinjam');
    let signaturePadPinjam = null;
    
    if (canvasPinjam) {
        signaturePadPinjam = new SignaturePad(canvasPinjam, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });
        
        // Resize canvas
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvasPinjam.width = canvasPinjam.offsetWidth * ratio;
            canvasPinjam.height = canvasPinjam.offsetHeight * ratio;
            canvasPinjam.getContext("2d").scale(ratio, ratio);
            signaturePadPinjam.clear();
        }
        
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();
    }
    
    function clearSignaturePinjam() {
        if (signaturePadPinjam) {
            signaturePadPinjam.clear();
        }
    }
    
    $(document).ready(function() {
        console.log('Script loaded');
        console.log('jQuery version:', $.fn.jquery);
        console.log('Bootstrap:', typeof bootstrap);
        
        // Debug: Check if button exists
        console.log('🔍 Tombol Selesai Service:', $('.btn-selesai-service-trigger').length);
        console.log('🔍 Modal Selesai Service:', $('#modalSelesaiService').length);
        
        // Manual click handler untuk tombol selesai service
        $('.btn-selesai-service-trigger').on('click', function(e) {
            e.preventDefault();
            console.log('🟢 Tombol Selesai Service diklik!');
            var modalEl = document.getElementById('modalSelesaiService');
            if (modalEl) {
                $(modalEl).fadeIn().addClass('show').css('display', 'block');
                $('body').addClass('modal-open');
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop fade show"></div>');
                }
                console.log('✅ Modal ditampilkan');
            } else {
                console.error('❌ Modal tidak ditemukan');
            }
        });
        
        // Preview Foto Identitas untuk modal pinjam
        $('#foto_identitas_pinjam').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar!',
                        text: 'Ukuran file maksimal 2MB'
                    });
                    $(this).val('');
                    $('#preview_container_pinjam').hide();
                    return;
                }

                // Validasi tipe file
                if (!file.type.match('image.*')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Tidak Valid!',
                        text: 'Hanya file gambar yang diperbolehkan'
                    });
                    $(this).val('');
                    $('#preview_container_pinjam').hide();
                    return;
                }

                // Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_identitas_pinjam').attr('src', e.target.result);
                    $('#preview_container_pinjam').show();
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Auto-detect GPS saat modal pinjam dibuka
        $('#modalPinjamKendaraan').on('shown.modal', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    $('#latitude_pinjam').val(lat);
                    $('#longitude_pinjam').val(lng);
                    $('#lokasi_display_pinjam').val(lat.toFixed(4) + ', ' + lng.toFixed(4));
                    console.log('GPS detected:', lat, lng);
                }, function(error) {
                    $('#lokasi_display_pinjam').val('GPS tidak tersedia');
                    console.log('GPS error:', error);
                });
            }
        });
        
        // Form submission for pinjam kendaraan
        $('#formPinjamKendaraan').on('submit', function(e) {
            e.preventDefault();
            
            // Validate signature
            if (signaturePadPinjam && signaturePadPinjam.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Silakan tanda tangan terlebih dahulu'
                });
                return;
            }
            
            // Get signature data
            if (signaturePadPinjam) {
                $('#signature-data-pinjam').val(signaturePadPinjam.toDataURL());
            }
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengirim permohonan peminjaman',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Prepare FormData for file upload
            var formData = new FormData(this);
            
            $.ajax({
                url: '{{ route("kendaraan.karyawan.submit.pinjam") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#modalPinjamKendaraan').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Permohonan peminjaman berhasil dikirim',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });
        
        // Manual trigger modal untuk debugging
        $('a[data-toggle="modal"]').on('click', function(e) {
            e.preventDefault();
            var targetModal = $(this).data('target');
            console.log('Modal button clicked:', targetModal);
            
            // Coba manual trigger
            if (typeof bootstrap !== 'undefined') {
                var myModal = new bootstrap.Modal(document.querySelector(targetModal));
                myModal.show();
                console.log('Modal triggered via Bootstrap 5');
            } else {
                // Fallback ke jQuery (Bootstrap 4)
                $(targetModal).modal('show');
                console.log('Modal triggered via jQuery');
            }
        });
        // Update card position
        updateCardPosition();
        
        // Prev button
        $('#prevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Prev clicked, current index:', currentIndex);
            if (currentIndex > 0) {
                currentIndex--;
                updateCardPosition();
                // Navigate after animation
                setTimeout(function() {
                    navigateToKendaraan();
                }, 400);
            }
        });
        
        // Next button
        $('#nextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Next clicked, current index:', currentIndex);
            if (currentIndex < totalKendaraan - 1) {
                currentIndex++;
                updateCardPosition();
                // Navigate after animation
                setTimeout(function() {
                    navigateToKendaraan();
                }, 400);
            }
        });
        
        // Indicator dots click
        $('.indicator-dot').on('click', function(e) {
            e.preventDefault();
            var newIndex = $(this).data('index');
            console.log('Dot clicked, new index:', newIndex);
            if (newIndex !== currentIndex) {
                currentIndex = newIndex;
                updateCardPosition();
                // Navigate after animation
                setTimeout(function() {
                    navigateToKendaraan();
                }, 400);
            }
        });
        
        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        $('#kendaraanCardsContainer').on('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        $('#kendaraanCardsContainer').on('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            console.log('Swipe detected:', touchStartX, touchEndX);
            if (touchEndX < touchStartX - 50) {
                // Swipe left (next)
                if (currentIndex < totalKendaraan - 1) {
                    currentIndex++;
                    updateCardPosition();
                    setTimeout(function() {
                        navigateToKendaraan();
                    }, 400);
                }
            }
            if (touchEndX > touchStartX + 50) {
                // Swipe right (prev)
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCardPosition();
                    setTimeout(function() {
                        navigateToKendaraan();
                    }, 400);
                }
            }
        }
        
        function updateCardPosition() {
            console.log('Updating card position, index:', currentIndex);
            const offset = -currentIndex * 100;
            $('#kendaraanCards').css('transform', `translateX(${offset}%)`);
            
            // Update active card border
            $('.kendaraan-card').removeClass('active').find('.card').css('border', 'none');
            $(`.kendaraan-card[data-index="${currentIndex}"]`).addClass('active')
                .find('.card').css('border', '3px solid #667eea');
            
            // Update indicators
            $('.indicator-dot').css('background', '#ddd');
            $(`.indicator-dot[data-index="${currentIndex}"]`).css('background', '#667eea');
            
            // Update prev/next button state
            $('#prevBtn').prop('disabled', currentIndex === 0);
            $('#nextBtn').prop('disabled', currentIndex === totalKendaraan - 1);
            
            console.log('Card position updated');
        }
        
        function navigateToKendaraan() {
            const kendaraanId = allKendaraanData[currentIndex].id;
            
            // Show loading
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Navigate directly with unencrypted ID
            window.location.href = `/kendaraan-karyawan/${kendaraanId}/detail`;
        }
        
        // Popup Foto Peminjaman
        $(document).on('click', '.foto-popup', function() {
            var fotoUrl = $(this).data('foto');
            var title = $(this).data('title');
            
            $('#modalFotoPeminjamanTitle').text(title);
            $('#modalFotoPeminjamanImage').attr('src', fotoUrl);
            $('#modalFotoPeminjaman').modal('show');
        });
        
        // Auto-detect GPS location saat modal keluar dibuka
        $('#modalKeluarKendaraan').on('shown.modal', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#latitude_keluar').val(position.coords.latitude);
                    $('#longitude_keluar').val(position.coords.longitude);
                }, function(error) {
                    console.log('GPS error:', error);
                });
            }
        });
        
        // Auto-detect GPS location saat modal kembali dibuka
        $('#modalKembaliKendaraan').on('shown.modal', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#latitude_kembali').val(position.coords.latitude);
                    $('#longitude_kembali').val(position.coords.longitude);
                }, function(error) {
                    console.log('GPS error:', error);
                });
            }
        });
        
        // Handle form submission dengan AJAX
        $('#formKeluarKendaraan').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan data keluar kendaraan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kendaraan berhasil ditandai keluar',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload halaman untuk update status
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });
        
        // Handle form kembali submission dengan AJAX
        $('#formKembaliKendaraan').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan data kembali kendaraan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kendaraan berhasil ditandai kembali',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload halaman untuk update status dan hilangkan animasi
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });
        
        // ==================== SERVICE KENDARAAN ====================
        // Preview foto before service
        $('#foto_before_service').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_foto_before_service').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview_foto_before_service').hide();
            }
        });

        // Get GPS Location for Service when modal opens
        $('#modalFormService').on('shown.bs.modal', function() {
            console.log('📍 Getting GPS location for service...');
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    
                    $('#latitude_service').val(latitude);
                    $('#longitude_service').val(longitude);
                    
                    console.log('✅ GPS location obtained:', latitude, longitude);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Terdeteksi!',
                        text: 'GPS berhasil mendapatkan koordinat bengkel',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, function(error) {
                    console.warn('⚠️ GPS error:', error);
                });
            }
        });

        // Form Service Submit
        $('#formService').on('submit', function(e) {
            e.preventDefault();
            
            console.log('📝 Form service submit');
            
            var jenisService = $('select[name="jenis_service"]').val();
            var deskripsiKerusakan = $('textarea[name="deskripsi_kerusakan"]').val();
            
            if (!jenisService) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Jenis service harus dipilih!'
                });
                return false;
            }
            
            if (!deskripsiKerusakan.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Deskripsi kerusakan harus diisi!'
                });
                return false;
            }
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengirim data service',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Prepare FormData
            var formData = new FormData(this);
            
            // Submit via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Service berhasil:', response);
                    $('#modalFormService').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Kendaraan berhasil diproses untuk service',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    console.error('❌ Service gagal:', xhr);
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMsg
                    });
                }
            });
        });
        
        // ==================== SELESAI SERVICE ====================
        // Preview foto after service
        $('#foto_after_service').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_foto_after_service').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview_foto_after_service').hide();
            }
        });

        // Get GPS Location for Selesai Service when modal opens
        $('#modalSelesaiService').on('shown.bs.modal', function() {
            console.log('📍 Getting GPS location for selesai service...');
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    
                    $('#latitude_selesai').val(latitude);
                    $('#longitude_selesai').val(longitude);
                    
                    console.log('✅ GPS location obtained:', latitude, longitude);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Terdeteksi!',
                        text: 'GPS berhasil mendapatkan koordinat',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, function(error) {
                    console.warn('⚠️ GPS error:', error);
                });
            }
        });

        // Form Selesai Service Submit
        $('#formSelesaiService').on('submit', function(e) {
            e.preventDefault();
            
            console.log('📝 Form selesai service submit');
            
            var kondisi = $('#formSelesaiService select[name="kondisi_kendaraan"]').val();
            var pekerjaan = $('#formSelesaiService textarea[name="pekerjaan_selesai"]').val();
            
            if (!kondisi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Kondisi kendaraan harus dipilih!'
                });
                return false;
            }
            
            if (!pekerjaan.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pekerjaan yang telah dilakukan harus diisi!'
                });
                return false;
            }
            
            // Tutup modal langsung
            $('#modalSelesaiService').fadeOut().removeClass('show');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            
            // Prepare FormData
            var formData = new FormData(this);
            
            // Submit via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Selesai service berhasil:', response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Service berhasil diselesaikan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Reload langsung tanpa then
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    console.error('❌ Selesai service gagal:', xhr);
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMsg
                    });
                }
            });
        });
    });
</script>
@endpush

<!-- Modal Form Service -->
<div class="modal fade" id="modalFormService" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="construct-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Form Service Kendaraan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('service.proses', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formService" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Kendaraan -->
                    <div class="alert" style="border-radius: 15px; border-left: 4px solid #ef4444; background: #fee2e2;">
                        <div class="d-flex align-items-center">
                            @if($kendaraan->foto && Storage::disk('public')->exists('kendaraan/' . $kendaraan->foto))
                                <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" style="width: 60px; height: 60px; border-radius: 10px; object-fit: cover; margin-right: 15px;">
                            @else
                                <div style="width: 60px; height: 60px; border-radius: 10px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <ion-icon name="car-sport" style="font-size: 2rem; color: white; opacity: 0.7;"></ion-icon>
                                </div>
                            @endif
                            <div>
                                <strong style="color: #991b1b; font-size: 1.1rem;">{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small style="color: #7f1d1d;"><strong>No. Polisi:</strong> {{ $kendaraan->no_polisi }}</small><br>
                                <small style="color: #7f1d1d;"><strong>KM Terakhir:</strong> {{ number_format($kendaraan->km_terakhir ?? 0) }} km</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal Service *
                            </label>
                            <input type="date" class="form-control" name="tanggal_service" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam Service *
                            </label>
                            <input type="time" class="form-control" name="jam_service" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="construct-outline"></ion-icon> Jenis Service *
                            </label>
                            <select class="form-select" name="jenis_service" required 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Service Rutin">Service Rutin</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Ganti Oli">Ganti Oli</option>
                                <option value="Ganti Ban">Ganti Ban</option>
                                <option value="Tune Up">Tune Up</option>
                                <option value="Body Repair">Body Repair</option>
                                <option value="Cuci">Cuci</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="business-outline"></ion-icon> Nama Bengkel
                            </label>
                            <input type="text" class="form-control" name="bengkel" 
                                   placeholder="Nama bengkel"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Saat Service
                            </label>
                            <input type="number" class="form-control" name="km_service" min="0"
                                   placeholder="KM saat service"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="cash-outline"></ion-icon> Estimasi Biaya (Rp)
                            </label>
                            <input type="number" class="form-control" name="estimasi_biaya" min="0" step="1000"
                                   placeholder="0"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="document-text-outline"></ion-icon> Deskripsi Kerusakan / Keluhan *
                        </label>
                        <textarea class="form-control" name="deskripsi_kerusakan" rows="3" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Jelaskan kerusakan atau keluhan kendaraan"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="build-outline"></ion-icon> Pekerjaan Yang Akan Dilakukan
                        </label>
                        <textarea class="form-control" name="pekerjaan" rows="3" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Daftar pekerjaan yang akan dilakukan"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Estimasi Selesai
                            </label>
                            <input type="date" class="form-control" name="estimasi_selesai" 
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="person-outline"></ion-icon> PIC / Mekanik
                            </label>
                            <input type="text" class="form-control" name="pic" 
                                   placeholder="Nama mekanik"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="image-outline"></ion-icon> Foto Kondisi Sebelum Service
                        </label>
                        <input type="file" class="form-control" name="foto_before" accept="image/*" id="foto_before_service"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        <small class="text-muted d-block mt-1">Upload foto kondisi kendaraan sebelum service (Max: 2MB)</small>
                        <div class="mt-2">
                            <img id="preview_foto_before_service" style="max-width: 200px; display: none; border-radius: 10px;" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan Tambahan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_service" id="latitude_service">
                    <input type="hidden" name="longitude_service" id="longitude_service">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-circle-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Proses Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Form Selesai Service -->
@if($kendaraan->serviceAktif)
<div class="modal fade" id="modalSelesaiService" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="checkmark-circle-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Form Penyelesaian Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('service.prosesSelesai', Crypt::encrypt($kendaraan->serviceAktif->id)) }}" method="POST" id="formSelesaiService" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Service Aktif -->
                    <div class="alert" style="border-radius: 15px; border-left: 4px solid #10b981; background: #d1fae5;">
                        <div class="d-flex align-items-start">
                            <ion-icon name="information-circle" style="font-size: 2.5rem; color: #059669; margin-right: 15px;"></ion-icon>
                            <div style="flex: 1;">
                                <strong style="color: #065f46; font-size: 1rem; display: block; margin-bottom: 8px;">Informasi Service</strong>
                                <div style="color: #047857; font-size: 0.9rem;">
                                    <div class="row">
                                        <div class="col-6">
                                            <small><strong>Kode:</strong> {{ $kendaraan->serviceAktif->kode_service }}</small><br>
                                            <small><strong>Jenis:</strong> {{ $kendaraan->serviceAktif->jenis_service }}</small><br>
                                            <small><strong>Bengkel:</strong> {{ $kendaraan->serviceAktif->bengkel ?? '-' }}</small>
                                        </div>
                                        <div class="col-6">
                                            <small><strong>Waktu Masuk:</strong> {{ \Carbon\Carbon::parse($kendaraan->serviceAktif->waktu_service)->format('d/m/Y H:i') }}</small><br>
                                            <small><strong>Estimasi Biaya:</strong> Rp {{ number_format($kendaraan->serviceAktif->estimasi_biaya ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal Selesai *
                            </label>
                            <input type="date" class="form-control" name="tanggal_selesai" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam Selesai *
                            </label>
                            <input type="time" class="form-control" name="jam_selesai" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Setelah Service
                            </label>
                            <input type="number" class="form-control" name="km_selesai" min="0"
                                   placeholder="KM setelah service"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="cash-outline"></ion-icon> Biaya Akhir (Rp)
                            </label>
                            <input type="number" class="form-control" name="biaya_akhir" min="0" step="1000"
                                   value="{{ $kendaraan->serviceAktif->estimasi_biaya ?? 0 }}"
                                   placeholder="0"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="document-text-outline"></ion-icon> Pekerjaan Yang Telah Dilakukan *
                        </label>
                        <textarea class="form-control" name="pekerjaan_selesai" rows="3" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Daftar pekerjaan yang telah diselesaikan">{{ $kendaraan->serviceAktif->pekerjaan ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Catatan Mekanik
                        </label>
                        <textarea class="form-control" name="catatan_mekanik" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Catatan atau rekomendasi dari mekanik"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="shield-checkmark-outline"></ion-icon> Kondisi Setelah Service *
                            </label>
                            <select class="form-select" name="kondisi_kendaraan" required 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup Baik">Cukup Baik</option>
                                <option value="Perlu Perhatian">Perlu Perhatian</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="person-outline"></ion-icon> PIC / Mekanik
                            </label>
                            <input type="text" class="form-control" name="pic_selesai" 
                                   value="{{ $kendaraan->serviceAktif->pic ?? '' }}"
                                   placeholder="Nama mekanik"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="image-outline"></ion-icon> Foto Kondisi Setelah Service *
                        </label>
                        <input type="file" class="form-control" name="foto_after" accept="image/*" id="foto_after_service" required
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        <small class="text-muted d-block mt-1">Upload foto kondisi kendaraan setelah service (Max: 2MB)</small>
                        <div class="mt-2">
                            <img id="preview_foto_after_service" style="max-width: 200px; display: none; border-radius: 10px;" class="img-thumbnail">
                        </div>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_selesai" id="latitude_selesai">
                    <input type="hidden" name="longitude_selesai" id="longitude_selesai">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-done-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Selesaikan Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
