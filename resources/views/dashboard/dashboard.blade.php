@extends('layouts.app')
@section('titlepage', 'Dashboard')

@section('content')

<style>
/* Custom styles for real-time notifications */
.notification-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.notification-item.unread {
    background: linear-gradient(90deg, rgba(40, 167, 69, 0.1) 0%, rgba(255, 255, 255, 1) 100%) !important;
    border-left: 4px solid #28a745 !important;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
}

.notification-item.read {
    background: #ffffff;
    border-left: 4px solid #e9ecef;
}

.notification-item.unread:hover {
    background: linear-gradient(90deg, rgba(40, 167, 69, 0.15) 0%, rgba(255, 255, 255, 1) 100%) !important;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
}

#notifications-container {
    position: relative;
}

#notifications-container::-webkit-scrollbar {
    width: 6px;
}

#notifications-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#notifications-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#notifications-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.notification-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.bg-success-subtle {
    background-color: rgba(40, 167, 69, 0.1) !important;
}

.bg-unread {
    background-color: rgba(40, 167, 69, 0.08) !important;
    border-left: 3px solid #28a745 !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(23, 162, 184, 0.1) !important;
}

.bg-primary-subtle {
    background-color: rgba(0, 123, 255, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.bg-dark-subtle {
    background-color: rgba(52, 58, 64, 0.1) !important;
}

.ti-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@section('navigasi')
    <span>Dashboard</span>
@endsection
<div class="row mt-3">
    <div class="col">
        <form action="">
            <div class="row">
                <div class="col">
                    <x-input-with-icon label="Tanggal" icon="ti ti-calendar" name="tanggal" datepicker="flatpickr-date" value="{{ Request('tanggal') }}" />
                </div>
                <div class="col">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                        selected="{{ Request('kode_cabang') }}" />
                </div>
                <div class="col">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                        selected="{{ Request('kode_dept') }}" upperCase="true" />
                </div>
                <div class="col-1">
                    <button class="btn btn-primary"><i class="ti ti-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-success h-100" style="cursor: pointer;" onclick="showKaryawanModal('hadir')">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-success"><i class="ti ti-user-check"></i></span>
                    </div>
                    {{-- {{ var_dump($rekappresensi->hadir) }} --}}
                    <h4 class="mb-0">{{ $rekappresensi->hadir ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Hadir</p>
                <small class="text-muted"><i class="ti ti-click"></i> Klik untuk lihat detail</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-primary h-100" style="cursor: pointer;" onclick="showKaryawanModal('izin')">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-file-description"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $rekappresensi->izin ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Izin</p>
                <small class="text-muted"><i class="ti ti-click"></i> Klik untuk lihat detail</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-warning h-100" style="cursor: pointer;" onclick="showKaryawanModal('sakit')">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $rekappresensi->sakit ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Sakit</p>
                <small class="text-muted"><i class="ti ti-click"></i> Klik untuk lihat detail</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-primary h-100" style="cursor: pointer;" onclick="showKaryawanModal('cuti')">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-file"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $rekappresensi->cuti ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Cuti</p>
                <small class="text-muted"><i class="ti ti-click"></i> Klik untuk lihat detail</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">

                                <div>
                                    <p class="mb-1">Data Karyawn Aktif</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_aktif }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan1.png') }}" height="70" alt="view sales" class="me-3">
                            </div>

                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Karyawan Tetap</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_tetap }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan2.webp') }}" height="70" alt="view sales" class="me-3">
                            </div>

                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1">Karyawan Kontrak</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_kontrak }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan3.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">Outsourcing</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_outsourcing }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan4.webp') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-4 col-sm-6">
        <div class="card card-border-shadow-danger h-100" style="cursor: pointer;" onclick="showKendaraanModal('keluar')">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-car"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $jumlah_kendaraan_keluar ?? 0 }}</h4>
                </div>
                <p class="mb-1">Kendaraan Sedang Keluar</p>
                <small class="text-muted"><i class="ti ti-click"></i> Klik untuk lihat detail</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-sm-6">
        <div class="card card-border-shadow-warning h-100" style="cursor: pointer;" onclick="showKendaraanModal('dipinjam')">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-key"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $jumlah_kendaraan_dipinjam ?? 0 }}</h4>
                </div>
                <p class="mb-1">Kendaraan Sedang Dipinjam</p>
                <small class="text-muted"><i class="ti ti-click"></i> Klik untuk lihat detail</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-sm-6">
        <div class="card card-border-shadow-info h-100" style="cursor: pointer;" onclick="showTugasLuarModal()">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-info"><i class="ti ti-briefcase"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $jumlah_tugas_luar ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Tugas Luar</p>
                <small class="text-muted"><i class="ti ti-click"></i> Klik untuk lihat detail</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ti ti-cake fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="mb-0">Karyawan Ulang Tahun</h4>
                        <small class="text-muted">Selamat ulang tahun untuk karyawan yang berulang tahun hari ini</small>
                    </div>
                </div>
                <span class="badge bg-label-warning rounded-pill">{{ count($birthday) }} Karyawan</span>
            </div>
            <div class="card-body">
                @if (count($birthday) > 0)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-0">Kirim Ucapan Ulang Tahun</h6>
                            <small class="text-muted">Kirim ucapan ulang tahun ke semua karyawan yang berulang tahun hari ini</small>
                        </div>
                        <div>
                            <button type="button" class="btn btn-success btn-sm" id="btnKirimUcapan" onclick="kirimUcapanSemua()">
                                <i class="ti ti-brand-whatsapp me-1"></i>
                                <span id="btnText">Kirim ke Semua</span>
                                <span id="btnLoading" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach ($birthday as $d)
                            @php
                                $umur = \Carbon\Carbon::parse($d->tanggal_lahir)->age;
                                $colors = ['primary', 'success', 'info', 'warning', 'danger'];
                                $colorIndex = $loop->index % count($colors);
                                $color = $colors[$colorIndex];
                            @endphp
                            <div class="col-12">
                                <div class="card card-border-shadow-{{ $color }} birthday-card"
                                    style="transition: all 0.3s ease; cursor: pointer;"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3" style="width: 80px; height: 80px; position: relative;">
                                                @if (!empty($d->foto))
                                                    @if (Storage::disk('public')->exists('/karyawan/' . $d->foto))
                                                        <img src="{{ getfotoKaryawan($d->foto) }}" alt="{{ $d->nama_karyawan }}"
                                                            class="rounded-circle border border-{{ $color }} border-3"
                                                            style="width: 80px; height: 80px; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                                    @else
                                                        <div class="avatar-initial rounded-circle bg-label-{{ $color }} d-flex align-items-center justify-content-center border border-{{ $color }} border-3"
                                                            style="width: 80px; height: 80px; font-size: 32px;">
                                                            <i class="ti ti-user"></i>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="avatar-initial rounded-circle bg-label-{{ $color }} d-flex align-items-center justify-content-center border border-{{ $color }} border-3"
                                                        style="width: 80px; height: 80px; font-size: 32px;">
                                                        <i class="ti ti-user"></i>
                                                    </div>
                                                @endif
                                                <div class="position-absolute bottom-0 end-0 bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center border-white border-2"
                                                    style="width: 28px; height: 28px; font-size: 14px;">
                                                    <i class="ti ti-cake"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <h5 class="mb-0">{{ $d->nama_karyawan }}</h5>
                                                    <span class="badge bg-label-{{ $color }} rounded-pill">{{ $umur }} Tahun</span>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="ti ti-id me-2 text-{{ $color }}"></i>
                                                            <small class="text-muted">NIK:</small>
                                                            <strong class="ms-2">{{ $d->nik_show }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="ti ti-calendar me-2 text-{{ $color }}"></i>
                                                            <small class="text-muted">Tanggal Lahir:</small>
                                                            <strong class="ms-2">{{ date('d-m-Y', strtotime($d->tanggal_lahir)) }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="ti ti-briefcase me-2 text-{{ $color }}"></i>
                                                            <small class="text-muted">Jabatan:</small>
                                                            <strong class="ms-2">{{ $d->nama_jabatan }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="ti ti-building me-2 text-{{ $color }}"></i>
                                                            <small class="text-muted">Dept:</small>
                                                            <strong class="ms-2">{{ $d->kode_dept }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="ti ti-map-pin me-2 text-{{ $color }}"></i>
                                                            <small class="text-muted">Cabang:</small>
                                                            <strong class="ms-2">{{ $d->nama_cabang }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar mb-3" style="width: 100px; height: 100px; margin: 0 auto;">
                            <span class="avatar-initial rounded-circle bg-label-secondary d-flex align-items-center justify-content-center"
                                style="font-size: 48px;">
                                <i class="ti ti-cake-off"></i>
                            </span>
                        </div>
                        <h5 class="text-muted">Tidak ada karyawan yang ulang tahun hari ini</h5>
                        <p class="text-muted mb-0">Semua karyawan akan menunggu hari ulang tahun mereka!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

        
        <!-- KPI Crew Section -->
        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="ti ti-trophy text-warning me-2"></i>KPI Crew Bulan Ini - Top 10
                        </h4>
                        <span class="badge bg-label-primary">{{ date('F Y') }}</span>
                    </div>
                    <div class="card-body">
                        @if(isset($topKpiCrew) && $topKpiCrew->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Ranking</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th class="text-center">Kehadiran</th>
                                        <th class="text-center">Aktivitas</th>
                                        <th class="text-center">Perawatan</th>
                                        <th class="text-center">Total Point</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topKpiCrew as $index => $kpi)
                                    <tr>
                                        <td class="text-center">
                                            @if($index == 0)
                                                <span class="badge bg-warning"><i class="ti ti-crown"></i> 1</span>
                                            @elseif($index == 1)
                                                <span class="badge bg-label-secondary"><i class="ti ti-medal"></i> 2</span>
                                            @elseif($index == 2)
                                                <span class="badge bg-label-warning"><i class="ti ti-medal-2"></i> 3</span>
                                            @else
                                                <span class="badge bg-label-secondary">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $kpi->nik }}</strong></td>
                                        <td>
                                            @if($kpi->karyawan)
                                                {{ $kpi->karyawan->nama_karyawan }}
                                                <br><small class="text-muted">{{ $kpi->karyawan->nama_jabatan ?? '-' }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-success">{{ $kpi->kehadiran_count }}x</span>
                                            <br><small class="text-muted">{{ number_format($kpi->kehadiran_point, 1) }} pt</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-info">{{ $kpi->aktivitas_count }}x</span>
                                            <br><small class="text-muted">{{ number_format($kpi->aktivitas_point, 1) }} pt</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-primary">{{ $kpi->perawatan_count }}x</span>
                                            <br><small class="text-muted">{{ number_format($kpi->perawatan_point, 1) }} pt</small>
                                        </td>
                                        <td class="text-center">
                                            <h5 class="mb-0 text-primary"><strong>{{ number_format($kpi->total_point, 1) }}</strong></h5>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                Data KPI dihitung berdasarkan kehadiran, aktivitas, dan perawatan crew
                            </small>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="ti ti-trophy-off" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Belum ada data KPI crew untuk bulan ini</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Notifications Panel -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-bell me-2"></i>Aktivitas Real-time Hari Ini
                </h5>
                <div class="d-flex gap-2">
                    <span id="notification-count" class="badge bg-primary">0</span>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshNotifications(event)">
                        <i class="ti ti-refresh"></i> Refresh
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="markAllAsRead()">
                        <i class="ti ti-check"></i> Tandai Semua Dibaca
                    </button>
                </div>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <div id="notifications-container">
                    <!-- Notifikasi akan dimuat di sini -->
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-loader-2 ti-lg mb-3"></i>
                        <p>Memuat aktivitas...</p>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">
                    <i class="ti ti-clock"></i>
                    Update otomatis setiap 5 detik | Reset setiap hari baru
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup untuk menampilkan nama karyawan -->
<div class="modal fade" id="modalKaryawan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKaryawanTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalKaryawanContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup untuk menampilkan kendaraan -->
<div class="modal fade" id="modalKendaraan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKendaraanTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalKendaraanContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup untuk menampilkan tugas luar -->
<div class="modal fade" id="modalTugasLuar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-briefcase text-info"></i> Karyawan Tugas Luar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalTugasLuarContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script src="{{ $chart->cdn() }}"></script>
{{ $chart->script() }}
{{ $jkchart->script() }}
{{ $pddchart->script() }}
<script>
    // Data karyawan dari backend
    const dataKaryawan = {
        hadir: @json($nama_karyawan_hadir),
        izin: @json($nama_karyawan_izin),
        sakit: @json($nama_karyawan_sakit),
        cuti: @json($nama_karyawan_cuti)
    };

    // Data kendaraan dari backend
    const dataKendaraan = {
        keluar: @json($kendaraan_keluar),
        dipinjam: @json($kendaraan_dipinjam)
    };

    // Data tugas luar dari backend
    const dataTugasLuar = @json($tugas_luar ?? []);

    // Fungsi untuk menampilkan modal dengan daftar karyawan
    function showKaryawanModal(status) {
        let title = '';
        let icon = '';
        let badgeClass = '';
        
        switch(status) {
            case 'hadir':
                title = 'Karyawan Hadir';
                icon = '<i class="ti ti-user-check text-success"></i>';
                badgeClass = 'bg-label-success';
                break;
            case 'izin':
                title = 'Karyawan Izin';
                icon = '<i class="ti ti-file-description text-primary"></i>';
                badgeClass = 'bg-label-primary';
                break;
            case 'sakit':
                title = 'Karyawan Sakit';
                icon = '<i class="ti ti-mood-sick text-warning"></i>';
                badgeClass = 'bg-label-warning';
                break;
            case 'cuti':
                title = 'Karyawan Cuti';
                icon = '<i class="ti ti-file text-info"></i>';
                badgeClass = 'bg-label-info';
                break;
        }
        
        document.getElementById('modalKaryawanTitle').innerHTML = icon + ' ' + title;
        
        const karyawanList = dataKaryawan[status];
        let content = '';
        
        if (karyawanList.length > 0) {
            content = '<div class="list-group">';
            karyawanList.forEach((nama, index) => {
                content += `
                    <div class="list-group-item list-group-item-action d-flex align-items-center">
                        <span class="badge ${badgeClass} rounded-pill me-3">${index + 1}</span>
                        <span>${nama}</span>
                    </div>
                `;
            });
            content += '</div>';
            content += `<div class="mt-3 text-center"><small class="text-muted">Total: ${karyawanList.length} karyawan</small></div>`;
        } else {
            content = `
                <div class="text-center py-4">
                    <i class="ti ti-mood-empty" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-2">Tidak ada karyawan dengan status ini</p>
                </div>
            `;
        }
        
        document.getElementById('modalKaryawanContent').innerHTML = content;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('modalKaryawan'));
        modal.show();
    }

    // Fungsi untuk menampilkan modal dengan daftar kendaraan
    function showKendaraanModal(status) {
        let title = '';
        let icon = '';
        let badgeClass = '';
        
        switch(status) {
            case 'keluar':
                title = 'Kendaraan Sedang Keluar';
                icon = '<i class="ti ti-car text-danger"></i>';
                badgeClass = 'bg-label-danger';
                break;
            case 'dipinjam':
                title = 'Kendaraan Sedang Dipinjam';
                icon = '<i class="ti ti-key text-warning"></i>';
                badgeClass = 'bg-label-warning';
                break;
        }
        
        document.getElementById('modalKendaraanTitle').innerHTML = icon + ' ' + title;
        
        const kendaraanList = dataKendaraan[status];
        let content = '';
        
        if (kendaraanList.length > 0) {
            content = '<div class="table-responsive"><table class="table table-hover">';
            content += '<thead><tr>';
            content += '<th>No</th>';
            content += '<th>Kendaraan</th>';
            content += '<th>No Polisi</th>';
            
            if (status === 'keluar') {
                content += '<th>Driver</th>';
                content += '<th>Penumpang</th>';
                content += '<th>Tujuan</th>';
                content += '<th>Waktu Keluar</th>';
            } else {
                content += '<th>Peminjam</th>';
                content += '<th>Keperluan</th>';
                content += '<th>Tgl Pinjam</th>';
                content += '<th>Tgl Kembali</th>';
            }
            
            content += '</tr></thead><tbody>';
            
            kendaraanList.forEach((item, index) => {
                content += '<tr>';
                content += `<td>${index + 1}</td>`;
                content += `<td><strong>${item.kendaraan?.nama_kendaraan || '-'}</strong></td>`;
                content += `<td><span class="badge ${badgeClass}">${item.kendaraan?.no_polisi || '-'}</span></td>`;
                
                if (status === 'keluar') {
                    content += `<td>${item.driver || '-'}</td>`;
                    
                    // Tampilkan penumpang
                    let penumpangHTML = '-';
                    if (item.penumpang) {
                        try {
                            const penumpangArray = typeof item.penumpang === 'string' ? JSON.parse(item.penumpang) : item.penumpang;
                            if (Array.isArray(penumpangArray) && penumpangArray.length > 0) {
                                penumpangHTML = '<small>';
                                penumpangArray.forEach((p, idx) => {
                                    if (idx > 0) penumpangHTML += ', ';
                                    penumpangHTML += p;
                                });
                                penumpangHTML += `</small><br><span class="badge bg-label-info">${penumpangArray.length} orang</span>`;
                            }
                        } catch (e) {
                            penumpangHTML = item.penumpang;
                        }
                    }
                    content += `<td>${penumpangHTML}</td>`;
                    
                    content += `<td>${item.tujuan || '-'}</td>`;
                    const waktuKeluar = item.waktu_keluar ? new Date(item.waktu_keluar).toLocaleString('id-ID') : '-';
                    content += `<td><small>${waktuKeluar}</small></td>`;
                } else {
                    content += `<td>${item.nama_peminjam || '-'}<br><small class="text-muted">${item.departemen || ''}</small></td>`;
                    content += `<td><small>${item.keperluan || '-'}</small></td>`;
                    const tglPinjam = item.tanggal_pinjam ? new Date(item.tanggal_pinjam).toLocaleDateString('id-ID') : '-';
                    const tglKembali = item.tanggal_kembali ? new Date(item.tanggal_kembali).toLocaleDateString('id-ID') : '-';
                    const now = new Date();
                    const kembali = new Date(item.tanggal_kembali);
                    const terlambat = now > kembali;
                    
                    content += `<td><small>${tglPinjam}</small></td>`;
                    content += `<td><span class="badge ${terlambat ? 'bg-label-danger' : 'bg-label-success'}">${tglKembali}</span>`;
                    if (terlambat) {
                        content += '<br><small class="text-danger"><i class="ti ti-alert-circle ti-xs"></i> Terlambat</small>';
                    }
                    content += '</td>';
                }
                
                content += '</tr>';
            });
            
            content += '</tbody></table></div>';
            content += `<div class="mt-3 text-center"><small class="text-muted">Total: ${kendaraanList.length} unit</small></div>`;
        } else {
            const emptyIcon = status === 'keluar' ? 'ti-car-off' : 'ti-key-off';
            const emptyText = status === 'keluar' ? 'Tidak ada kendaraan yang sedang keluar' : 'Tidak ada kendaraan yang sedang dipinjam';
            content = `
                <div class="text-center py-4">
                    <i class="ti ${emptyIcon}" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-2">${emptyText}</p>
                </div>
            `;
        }
        
        document.getElementById('modalKendaraanContent').innerHTML = content;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('modalKendaraan'));
        modal.show();
    }

    // Fungsi untuk menampilkan modal tugas luar
    function showTugasLuarModal() {
        try {
            let content = '';
            
            if (dataTugasLuar && dataTugasLuar.length > 0) {
                content = '<div class="table-responsive"><table class="table table-hover">';
                content += '<thead><tr>';
                content += '<th>No</th>';
                content += '<th>Kode Tugas</th>';
                content += '<th>Karyawan</th>';
                content += '<th>Tujuan</th>';
                content += '<th>Waktu Keluar</th>';
                content += '<th>Status</th>';
                content += '</tr></thead><tbody>';
                
                dataTugasLuar.forEach((item, index) => {
                    // Ambil data karyawan dari karyawan_list
                    let karyawanList = item.karyawan_list || [];
                    
                    // Jika masih berupa string, parse sebagai JSON
                    if (typeof karyawanList === 'string') {
                        try {
                            karyawanList = JSON.parse(karyawanList);
                        } catch (e) {
                            karyawanList = [];
                        }
                    }
                    
                    // Pastikan karyawanList adalah array
                    if (!Array.isArray(karyawanList)) {
                        karyawanList = [];
                    }
                    
                    let karyawanHTML = '<small>';
                    if (karyawanList.length > 0) {
                        karyawanList.forEach((nik, idx) => {
                            if (idx > 0) karyawanHTML += '<br>';
                            karyawanHTML += nik || 'Unknown';
                        });
                    } else {
                        karyawanHTML += 'Tidak ada karyawan';
                    }
                    karyawanHTML += `</small><br><span class="badge bg-label-info">${karyawanList.length} orang</span>`;
                    
                    content += '<tr>';
                    content += `<td>${index + 1}</td>`;
                    content += `<td><strong>${item.kode_tugas}</strong></td>`;
                    content += `<td>${karyawanHTML}</td>`;
                    content += `<td>${item.tujuan || '-'}<br><small class="text-muted">${item.keterangan || ''}</small></td>`;
                    content += `<td><small><i class="ti ti-clock"></i> ${item.waktu_keluar}</small></td>`;
                    content += `<td><span class="badge bg-danger"><i class="ti ti-briefcase ti-xs"></i> Sedang Keluar</span></td>`;
                    content += '</tr>';
                });
                
                content += '</tbody></table></div>';
                content += `<div class="mt-3 text-center"><small class="text-muted">Total: ${dataTugasLuar.length} tugas</small></div>`;
            } else {
                content = `
                    <div class="text-center py-4">
                        <i class="ti ti-briefcase-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2">Tidak ada karyawan yang sedang tugas luar</p>
                    </div>
                `;
            }
            
            document.getElementById('modalTugasLuarContent').innerHTML = content;
            
            // Show modal
            const modalElement = document.getElementById('modalTugasLuar');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                console.error('Modal element modalTugasLuar tidak ditemukan');
                alert('Modal element tidak ditemukan di halaman');
            }
        } catch (globalError) {
            console.error('Global error in showTugasLuarModal:', globalError);
            alert('Terjadi kesalahan saat menampilkan modal tugas luar: ' + globalError.message);
        }
    }

    // Fungsi untuk mengirim ucapan ulang tahun ke semua karyawan menggunakan job
    function kirimUcapanSemua() {
        const btnKirim = document.getElementById('btnKirimUcapan');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        // Disable button dan tampilkan loading
        btnKirim.disabled = true;
        btnText.textContent = 'Mengirim...';
        btnLoading.classList.remove('d-none');

        // Ambil filter dari URL atau form
        const urlParams = new URLSearchParams(window.location.search);
        const kodeCabang = urlParams.get('kode_cabang') || '';
        const kodeDept = urlParams.get('kode_dept') || '';

        // Kirim request ke server
        fetch('{{ route('dashboard.kirim.ucapan.birthday') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    kode_cabang: kodeCabang,
                    kode_dept: kodeDept
                })
            })
            .then(response => response.json())
            .then(data => {
                // Enable button kembali
                btnKirim.disabled = false;
                btnText.textContent = 'Kirim ke Semua';
                btnLoading.classList.add('d-none');

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                // Enable button kembali
                btnKirim.disabled = false;
                btnText.textContent = 'Kirim ke Semua';
                btnLoading.classList.add('d-none');

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengirim ucapan: ' + error.message
                });
            });
    }

    // ========================================
    // REAL-TIME NOTIFICATIONS FUNCTIONS
    // ========================================
    let notificationPolling = null;
    let lastNotificationTime = null;

    // Inisialisasi notifikasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        startNotificationPolling();
    });

    // Fungsi untuk memuat notifikasi dari API
    function loadNotifications() {
        fetch('/api/notifications', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayNotifications(data.data);
                updateNotificationCount(data.total);
                lastNotificationTime = data.last_updated;
            } else {
                showNotificationError('Gagal memuat notifikasi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            showNotificationError('Terjadi kesalahan saat memuat notifikasi');
        });
    }

    // Fungsi untuk menampilkan notifikasi di UI
    function displayNotifications(notifications) {
        const container = document.getElementById('notifications-container');
        
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="ti ti-bell-off ti-lg mb-3"></i>
                    <p>Belum ada aktivitas hari ini</p>
                </div>
            `;
            return;
        }

        let html = '';
        notifications.forEach(notification => {
            const timeClass = notification.is_read ? 'text-muted' : 'text-primary fw-bold';
            const cardClass = notification.is_read ? 'border-light' : 'border-success';
            const readClass = notification.is_read ? 'read' : 'unread';
            const backgroundClass = notification.is_read ? '' : 'bg-unread';
            
            html += `
                <div class="notification-item border ${cardClass} rounded mb-2 p-3 ${readClass} ${backgroundClass}" data-id="${notification.id}">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <span class="avatar-sm d-flex align-items-center justify-content-center rounded bg-${notification.color}-subtle">
                                <i class="${notification.icon} text-${notification.color}"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 ${notification.is_read ? 'text-muted' : 'fw-bold'}">${notification.title}</h6>
                            <p class="mb-1 small ${notification.is_read ? 'text-muted' : ''}">${notification.message}</p>
                            <small class="${timeClass}">
                                <i class="ti ti-clock me-1"></i>${notification.time_ago}
                                ${!notification.is_read ? '<span class="badge bg-success ms-2">BELUM DIBACA</span>' : ''}
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-${notification.color}">${notification.type}</span>
                            ${!notification.is_read ? `
                                <button class="btn btn-sm btn-success ms-2" onclick="markAsRead(${notification.id})" title="Tandai sudah dibaca">
                                    <i class="ti ti-check"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Fungsi untuk memulai polling notifikasi
    function startNotificationPolling() {
        // Polling setiap 5 detik
        notificationPolling = setInterval(() => {
            loadNotifications();
        }, 5000);
    }

    // Fungsi untuk menghentikan polling
    function stopNotificationPolling() {
        if (notificationPolling) {
            clearInterval(notificationPolling);
            notificationPolling = null;
        }
    }

    // Fungsi untuk refresh manual notifikasi
    function refreshNotifications(event) {
        loadNotifications();
        
        // Tampilkan feedback refresh
        if (event && event.target) {
            const refreshBtn = event.target.closest('button');
            if (refreshBtn) {
                const originalText = refreshBtn.innerHTML;
                refreshBtn.innerHTML = '<i class="ti ti-loader-2 ti-spin"></i> Loading...';
                refreshBtn.disabled = true;
                
                setTimeout(() => {
                    refreshBtn.innerHTML = originalText;
                    refreshBtn.disabled = false;
                }, 1000);
            }
        }
    }

    // Fungsi untuk menandai notifikasi sebagai dibaca
    function markAsRead(notificationId) {
        // Update visual immediately
        const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
        if (notificationElement) {
            // Remove unread classes and add read classes
            notificationElement.classList.remove('unread', 'bg-unread', 'border-success');
            notificationElement.classList.add('read', 'border-light');
            
            // Update text colors to muted
            const title = notificationElement.querySelector('h6');
            const message = notificationElement.querySelector('p');
            const badge = notificationElement.querySelector('.badge.bg-success');
            const button = notificationElement.querySelector('button');
            
            if (title) title.classList.add('text-muted');
            if (message) message.classList.add('text-muted');
            if (badge) badge.remove();
            if (button) button.remove();
        }
        
        fetch(`/api/notifications/mark-read/${notificationId}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update notification count
                updateNotificationCount();
            } else {
                // Revert visual changes if failed
                loadNotifications();
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            // Revert visual changes if error
            loadNotifications();
        });
    }

    // Fungsi untuk menandai semua notifikasi sebagai dibaca
    function markAllAsRead() {
        // Visual update for all unread notifications
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        unreadNotifications.forEach(element => {
            element.classList.remove('unread', 'bg-unread', 'border-success');
            element.classList.add('read', 'border-light');
            
            const title = element.querySelector('h6');
            const message = element.querySelector('p');
            const badge = element.querySelector('.badge.bg-success');
            const button = element.querySelector('button');
            
            if (title) title.classList.add('text-muted');
            if (message) message.classList.add('text-muted');
            if (badge) badge.remove();
            if (button) button.remove();
        });
        
        fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Semua notifikasi ditandai sebagai dibaca',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                // Revert if failed
                loadNotifications();
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as read:', error);
            // Revert if error
            loadNotifications();
        });
    }

    // Fungsi untuk update count notifikasi
    function updateNotificationCount(count = null) {
        if (count !== null) {
            // Update dari parameter langsung
            const countElement = document.getElementById('notification-count');
            if (countElement) {
                countElement.textContent = count;
                countElement.className = count > 0 ? 'badge bg-danger' : 'badge bg-secondary';
            }
        } else {
            // Fetch dari API
            fetch('/api/notifications/count')
            .then(response => response.json())
            .then(data => {
                const countElement = document.getElementById('notification-count');
                if (countElement) {
                    countElement.textContent = data.unread_count || 0;
                    countElement.className = data.unread_count > 0 ? 'badge bg-danger' : 'badge bg-secondary';
                }
            })
            .catch(error => {
                console.error('Error updating notification count:', error);
            });
        }
    }

    // Fungsi untuk menampilkan error notifikasi
    function showNotificationError(message) {
        const container = document.getElementById('notifications-container');
        container.innerHTML = `
            <div class="text-center text-danger py-4">
                <i class="ti ti-alert-circle ti-lg mb-3"></i>
                <p>${message}</p>
                <button class="btn btn-sm btn-outline-primary" onclick="loadNotifications()">
                    <i class="ti ti-refresh"></i> Coba Lagi
                </button>
            </div>
        `;
    }

    // Reset polling saat tab tidak aktif untuk menghemat resource
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopNotificationPolling();
        } else {
            startNotificationPolling();
        }
    });

    // Auto cleanup saat halaman di-unload
    window.addEventListener('beforeunload', function() {
        stopNotificationPolling();
    });
</script>
@endpush
