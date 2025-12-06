@extends('layouts.app')
@section('titlepage', 'Detail Kendaraan')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Detail</span>
@endsection

<style>
    /* Ilustrasi Kendaraan Berjalan - Inline di samping tombol */
    .vehicle-animation-inline {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 150px;
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
        font-size: 48px;
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
        font-size: 14px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        z-index: 1;
        text-align: center;
    }
    
    .hover-shadow {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>

<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Info Kendaraan -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        @if($kendaraan->foto)
                            <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="img-fluid rounded" alt="Foto">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="ti ti-car" style="font-size: 48px; color: #ccc;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h3>{{ $kendaraan->nama_kendaraan }} <span class="badge bg-primary">{{ $kendaraan->no_polisi }}</span></h3>
                        <p class="text-muted mb-2">{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->merk }} {{ $kendaraan->model }}</p>
                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1"><strong>Kode:</strong> {{ $kendaraan->kode_kendaraan }}</p>
                                <p class="mb-1"><strong>Tahun:</strong> {{ $kendaraan->tahun_pembuatan ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><strong>Warna:</strong> {{ $kendaraan->warna ?? '-' }}</p>
                                <p class="mb-1"><strong>No. Mesin:</strong> {{ $kendaraan->no_mesin ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><strong>No. Rangka:</strong> {{ $kendaraan->no_rangka ?? '-' }}</p>
                                <p class="mb-1"><strong>Kapasitas:</strong> {{ $kendaraan->kapasitas ?? '-' }} orang</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1"><strong>Status:</strong> 
                                    @if($kendaraan->status == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($kendaraan->status == 'keluar')
                                        <span class="badge bg-info">Sedang Keluar</span>
                                    @elseif($kendaraan->status == 'dipinjam')
                                        <span class="badge bg-primary">Dipinjam</span>
                                    @else
                                        <span class="badge bg-danger">Service</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'aktivitas' || !Request::get('tab') ? 'active' : '' }}" 
                data-bs-toggle="tab" data-bs-target="#aktivitas-tab">
            <i class="ti ti-car me-2"></i>Aktivitas Keluar/Masuk
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'peminjaman' ? 'active' : '' }}" 
                data-bs-toggle="tab" data-bs-target="#peminjaman-tab">
            <i class="ti ti-user-check me-2"></i>Peminjaman
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'service' ? 'active' : '' }}" 
                data-bs-toggle="tab" data-bs-target="#service-tab">
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
                    <div class="col-md-3">
                        <a href="{{ route('kendaraan.aktivitas.keluar', Crypt::encrypt($kendaraan->id)) }}" class="text-decoration-none">
                            <div class="card bg-primary text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-arrow-right" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Keluar</h5>
                                    <small>Tandai Kendaraan Keluar</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    @if($kendaraan->aktivitasAktif)
                    <div class="col-md-3">
                        <a href="{{ route('kendaraan.aktivitas.kembali', Crypt::encrypt($kendaraan->aktivitasAktif->id)) }}" class="text-decoration-none">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-arrow-back" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Tandai Kembali</h5>
                                    <small>Kendaraan Kembali</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.livetracking', Crypt::encrypt($kendaraan->aktivitasAktif->id)) }}" class="text-decoration-none" target="_blank">
                            <div class="card bg-info text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-live-view" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Live Tracking</h5>
                                    <small>Pantau Real-time</small>
                                </div>
                            </div>
                        </a>
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
                                        <th>Aksi</th>
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
                                            @php
                                                $waktuKembaliAktivitas = is_object($a) ? ($a->waktu_kembali ?? null) : (isset($a['waktu_kembali']) ? $a['waktu_kembali'] : null);
                                            @endphp
                                            @if($waktuKembaliAktivitas)
                                                {{ \Carbon\Carbon::parse($waktuKembaliAktivitas)->format('d/m/Y H:i') }}
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
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning editAktivitas" data-id="{{ Crypt::encrypt($a->id) }}">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('kendaraan.peminjaman.delete', Crypt::encrypt($a->id)) }}" method="POST" style="display: inline;" class="form-delete-peminjaman">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
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
                        <a href="{{ route('kendaraan.peminjaman.pinjam', Crypt::encrypt($kendaraan->id)) }}" class="text-decoration-none">
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
                    
                    {{-- Tombol Kembalikan untuk Workflow Pinjam --}}
                    @if($kendaraan->prosesAktif && $kendaraan->prosesAktif->jenis_proses == 'pinjam')
                    <div class="col-md-3">
                        <a href="javascript:void(0)" class="text-decoration-none" onclick="event.preventDefault(); openReturnModal({{ $kendaraan->id }}, {{ $kendaraan->prosesAktif->id }}, 'pinjam'); return false;">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-arrow-undo" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Kembalikan</h5>
                                    <small>Kendaraan Pinjaman</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    {{-- Animasi Kendaraan Berjalan saat Dipinjam --}}
                    <div class="col-md-6 mb-2">
                        <div class="vehicle-animation-inline active" id="vehicleAnimationPinjam">
                            <div class="vehicle-status-text">
                                <i class="ti ti-hand-grab" style="font-size: 16px; vertical-align: middle;"></i>
                                Kendaraan Sedang Dipinjam
                            </div>
                            <div class="vehicle-moving-inline">🚗</div>
                            <div class="road-line-inline" style="left: 0%;"></div>
                            <div class="road-line-inline" style="left: 25%;"></div>
                            <div class="road-line-inline" style="left: 50%;"></div>
                            <div class="road-line-inline" style="left: 75%;"></div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Tombol lama untuk backward compatibility --}}
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
                    <div class="card-header">
                        <h5 class="mb-0">Peminjaman Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Peminjam</th>
                                        <th>Keperluan</th>
                                        <th>Foto Identitas</th>
                                        <th>Waktu Pinjam</th>
                                        <th>Waktu Kembali</th>
                                        <th>Foto Kembali</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->peminjaman as $p)
                                    <tr>
                                        <td>{{ $p->kode_peminjaman }}</td>
                                        <td>{{ $p->nama_peminjam }}</td>
                                        <td>{{ Str::limit($p->keperluan, 30) }}</td>
                                        <td class="text-center">
                                            @if($p->foto_identitas)
                                                <img src="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}" 
                                                    alt="Foto Identitas" 
                                                    class="img-thumbnail foto-popup" 
                                                    style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
                                                    data-foto="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}"
                                                    data-title="Foto Identitas - {{ $p->nama_peminjam }}">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($p->waktu_pinjam)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @php
                                                $waktuKembali = is_object($p) ? ($p->waktu_kembali ?? null) : (isset($p['waktu_kembali']) ? $p['waktu_kembali'] : null);
                                            @endphp
                                            @if($waktuKembali)
                                                {{ \Carbon\Carbon::parse($waktuKembali)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="badge bg-warning">Belum Kembali</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($p->foto_kembali)
                                                <img src="{{ asset('storage/peminjaman/' . $p->foto_kembali) }}" 
                                                    alt="Foto Kembali" 
                                                    class="img-thumbnail foto-popup" 
                                                    style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
                                                    data-foto="{{ asset('storage/peminjaman/' . $p->foto_kembali) }}"
                                                    data-title="Foto Kembali - {{ $p->nama_peminjam }}">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->status == 'dipinjam')
                                                <span class="badge bg-primary">Dipinjam</span>
                                            @else
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($p->status == 'dipinjam')
                                                    <!-- Tombol Kembalikan -->
                                                    <a href="{{ route('kendaraan.peminjaman.kembali', Crypt::encrypt($p->id)) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Kembalikan Kendaraan">
                                                        <i class="ti ti-arrow-back-up"></i> Kembalikan
                                                    </a>
                                                @endif
                                                
                                                <!-- Tombol Download PDF -->
                                                <a href="{{ route('kendaraan.peminjaman.pdf.transportasi', Crypt::encrypt($p->id)) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Download PDF Surat Peminjaman">
                                                    <i class="ti ti-file-download"></i>
                                                </a>
                                                
                                                <!-- Tombol Edit -->
                                                <button class="btn btn-sm btn-warning editPeminjaman" 
                                                        data-id="{{ Crypt::encrypt($p->id) }}" 
                                                        title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                
                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('kendaraan.peminjaman.delete', Crypt::encrypt($p->id)) }}" 
                                                      method="POST" 
                                                      style="display: inline;" 
                                                      class="form-delete-peminjaman">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
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

    <!-- Service Tab -->
    <div class="tab-pane fade {{ Request::get('tab') == 'service' ? 'show active' : '' }}" id="service-tab">
        <div class="row">
            <!-- Action Cards -->
            <div class="col-md-12 mb-3">
                <div class="row g-3">
                    @if($kendaraan->status == 'tersedia')
                    <div class="col-md-3">
                        <a href="{{ route('service.form', Crypt::encrypt($kendaraan->id)) }}" class="text-decoration-none">
                            <div class="card bg-danger text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-tool" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Service</h5>
                                    <small>Tandai Service</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    @if($kendaraan->serviceAktif)
                    <div class="col-md-3">
                        <a href="{{ route('service.selesai', Crypt::encrypt($kendaraan->serviceAktif->id)) }}" class="text-decoration-none">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-check" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Selesai Service</h5>
                                    <small>Tandai Selesai</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    <div class="col-md-3">
                        <a href="{{ route('service.jadwal', Crypt::encrypt($kendaraan->id)) }}" class="text-decoration-none">
                            <div class="card bg-warning text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-calendar" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Jadwal Service</h5>
                                    <small>Atur Jadwal</small>
                                </div>
                            </div>
                        </a>
                    </div>
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
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetailService{{ $s->id }}" title="Lihat Detail">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                                <form action="{{ route('service.delete', Crypt::encrypt($s->id)) }}" method="POST" class="d-inline form-delete-service">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
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
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

@endsection

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
</style>

<!-- Modal Edit Aktivitas -->
<div class="modal fade" id="modalEditAktivitas" tabindex="-1" aria-labelledby="modalEditAktivitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white" id="modalEditAktivitasLabel">
                    <i class="ti ti-edit me-2"></i>Edit Aktivitas Kendaraan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditAktivitas" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pengemudi</label>
                                <input type="text" class="form-control" id="edit_driver" name="driver" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Penumpang</label>
                                <input type="text" class="form-control" id="edit_penumpang" name="penumpang">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tujuan</label>
                                <textarea class="form-control" id="edit_tujuan" name="tujuan" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waktu Keluar</label>
                                <input type="datetime-local" class="form-control" id="edit_waktu_keluar" name="waktu_keluar" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waktu Kembali</label>
                                <input type="datetime-local" class="form-control" id="edit_waktu_kembali" name="waktu_kembali">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KM Awal</label>
                                <input type="number" class="form-control" id="edit_km_awal" name="km_awal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KM Akhir</label>
                                <input type="number" class="form-control" id="edit_km_akhir" name="km_akhir">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Popup Foto -->
<div class="modal fade" id="modalFotoPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoPeminjamanTitle">Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFotoPeminjamanImage" src="" alt="Foto" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Peminjaman -->
<div class="modal fade" id="modalEditPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-edit me-2"></i>Edit Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPeminjaman" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Peminjam</label>
                                <input type="text" class="form-control" id="edit_nama_peminjam" name="nama_peminjam" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="edit_no_hp_peminjam" name="no_hp_peminjam">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email_peminjam" name="email_peminjam">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Keperluan</label>
                                <textarea class="form-control" id="edit_keperluan" name="keperluan" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waktu Pinjam</label>
                                <input type="datetime-local" class="form-control" id="edit_waktu_pinjam_peminjaman" name="waktu_pinjam" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estimasi Kembali</label>
                                <input type="datetime-local" class="form-control" id="edit_estimasi_kembali_peminjaman" name="estimasi_kembali" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waktu Kembali</label>
                                <input type="datetime-local" class="form-control" id="edit_waktu_kembali_peminjaman" name="waktu_kembali">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KM Awal</label>
                                <input type="number" class="form-control" id="edit_km_awal_peminjaman" name="km_awal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KM Akhir</label>
                                <input type="number" class="form-control" id="edit_km_akhir_peminjaman" name="km_akhir">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Popup Foto Peminjaman
        $(document).on('click', '.foto-popup', function() {
            var fotoUrl = $(this).data('foto');
            var title = $(this).data('title');
            
            $('#modalFotoPeminjamanTitle').text(title);
            $('#modalFotoPeminjamanImage').attr('src', fotoUrl);
            $('#modalFotoPeminjaman').modal('show');
        });

        // Edit Aktivitas
        $('.editAktivitas').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            
            // Get data aktivitas
            $.ajax({
                url: '/aktivitas/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#edit_driver').val(response.driver);
                    $('#edit_penumpang').val(response.penumpang);
                    $('#edit_tujuan').val(response.tujuan);
                    $('#edit_waktu_keluar').val(response.waktu_keluar);
                    $('#edit_waktu_kembali').val(response.waktu_kembali);
                    $('#edit_km_awal').val(response.km_awal);
                    $('#edit_km_akhir').val(response.km_akhir);
                    
                    $('#formEditAktivitas').attr('action', '/aktivitas/' + id + '/update');
                    $('#modalEditAktivitas').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Gagal mengambil data aktivitas', 'error');
                }
            });
        });

        // Edit Peminjaman
        $('.editPeminjaman').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            
            $.ajax({
                url: '/peminjaman/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#edit_nama_peminjam').val(response.nama_peminjam);
                    $('#edit_no_hp_peminjam').val(response.no_hp_peminjam);
                    $('#edit_email_peminjam').val(response.email_peminjam);
                    $('#edit_keperluan').val(response.keperluan);
                    $('#edit_waktu_pinjam_peminjaman').val(response.waktu_pinjam);
                    $('#edit_estimasi_kembali_peminjaman').val(response.estimasi_kembali);
                    $('#edit_waktu_kembali_peminjaman').val(response.waktu_kembali);
                    $('#edit_km_awal_peminjaman').val(response.km_awal);
                    $('#edit_km_akhir_peminjaman').val(response.km_akhir);
                    
                    $('#formEditPeminjaman').attr('action', '/peminjaman/' + id + '/update');
                    $('#modalEditPeminjaman').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Gagal mengambil data peminjaman', 'error');
                }
            });
        });
        
        // Handle delete aktivitas with SweetAlert2
        $('.form-delete-aktivitas').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Hapus Aktivitas?',
                text: "Data aktivitas akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        
        // Handle delete peminjaman with SweetAlert2
        $('.form-delete-peminjaman').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Hapus Peminjaman?',
                text: "Data peminjaman akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        
        // Handle delete service with SweetAlert2
        $('.form-delete-service').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Hapus Service?',
                text: "Data service akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        
        // ==================== RETURN PEMINJAMAN WORKFLOW ====================
        // Function to open return modal
        window.openReturnModal = function(kendaraanId, prosesId, jenisProses) {
            console.log('🔓 Opening return modal:', kendaraanId, prosesId, jenisProses);
            $('#return_kendaraan_id').val(kendaraanId);
            $('#return_proses_id').val(prosesId);
            
            // Show modal using Bootstrap 5
            var modalEl = document.getElementById('modalReturnPinjam');
            if (modalEl) {
                var modal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();
                console.log('✅ Modal shown (Bootstrap 5)');
            } else {
                console.error('❌ Modal element not found');
            }
        };
        
        // Handle return form submit
        $('#formReturnPinjam').on('submit', function(e) {
            e.preventDefault();
            
            console.log('📝 Form return peminjaman submit (Admin)');
            
            // Get GPS location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#return_latitude').val(position.coords.latitude);
                    $('#return_longitude').val(position.coords.longitude);
                    submitReturnFormAdmin();
                }, function(error) {
                    console.warn('⚠️ GPS error:', error);
                    submitReturnFormAdmin(); // Submit anyway
                });
            } else {
                submitReturnFormAdmin();
            }
        });
        
        function submitReturnFormAdmin() {
            var formData = new FormData($('#formReturnPinjam')[0]);
            
            console.log('✅ Submitting return via AJAX (Admin)...');
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses pengembalian kendaraan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: '{{ route("kendaraan.karyawan.submit.return.pinjam") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Return berhasil:', response);
                    $('#modalReturnPinjam').modal('hide');
                    
                    var message = response.message || 'Kendaraan berhasil dikembalikan';
                    if (response.data && response.data.terlambat) {
                        message += ' (Terlambat ' + response.data.hari_terlambat + ' hari)';
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    console.error('❌ Return gagal:', xhr);
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
        }
    });
</script>

<!-- Modal Return Peminjaman Kendaraan -->
<div class="modal fade" id="modalReturnPinjam" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="ti ti-arrow-undo me-2"></i>
                    Kembalikan Kendaraan Pinjaman
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formReturnPinjam">
                @csrf
                <input type="hidden" name="kendaraan_id" id="return_kendaraan_id">
                <input type="hidden" name="proses_id" id="return_proses_id">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Pengembalian Kendaraan</strong><br>
                        Pastikan semua data pengembalian diisi dengan benar
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_kembali" required 
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Kembali <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="jam_kembali" required 
                                   value="{{ date('H:i') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">KM Akhir <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="km_akhir" required 
                                   placeholder="0" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status BBM Akhir</label>
                            <select class="form-select" name="bbm_akhir">
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
                        <label class="form-label">Kondisi Kendaraan <span class="text-danger">*</span></label>
                        <select class="form-select" name="kondisi_kendaraan" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Kondisi Kendaraan <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="foto_kondisi[]" multiple accept="image/*" required>
                        <small class="text-muted">Wajib upload foto kondisi kendaraan saat dikembalikan (Max: 2MB per foto)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="3" 
                                  placeholder="Catatan tambahan pengembalian (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude" id="return_latitude">
                    <input type="hidden" name="longitude" id="return_longitude">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check me-2"></i>Kembalikan Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endpush
