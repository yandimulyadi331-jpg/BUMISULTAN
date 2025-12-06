@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="text-white mb-2"><i class="ti ti-building-warehouse me-2"></i>MANAJEMEN PERAWATAN GEDUNG</h3>
                    <p class="mb-0">Sistem kontrol perawatan dan kebersihan gedung dengan checklist harian, mingguan, bulanan & tahunan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Menu Cards --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
        {{-- Checklist Harian --}}
        <div class="col">
            <div class="card h-100 card-hover">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mx-auto mb-3 bg-label-primary">
                        <i class="ti ti-calendar-event ti-lg"></i>
                    </div>
                    <h5 class="card-title mb-2">Checklist Harian</h5>
                    <p class="text-muted small mb-3">Reset setiap hari pukul 00:00</p>
                    <a href="{{ route('perawatan.checklist.harian') }}" class="btn btn-primary btn-sm w-100">
                        <i class="ti ti-list-check me-1"></i> Buka Checklist
                    </a>
                </div>
            </div>
        </div>

        {{-- Checklist Mingguan --}}
        <div class="col">
            <div class="card h-100 card-hover">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mx-auto mb-3 bg-label-success">
                        <i class="ti ti-calendar-week ti-lg"></i>
                    </div>
                    <h5 class="card-title mb-2">Checklist Mingguan</h5>
                    <p class="text-muted small mb-3">Reset setiap Senin pukul 00:00</p>
                    <a href="{{ route('perawatan.checklist.mingguan') }}" class="btn btn-success btn-sm w-100">
                        <i class="ti ti-list-check me-1"></i> Buka Checklist
                    </a>
                </div>
            </div>
        </div>

        {{-- Checklist Bulanan --}}
        <div class="col">
            <div class="card h-100 card-hover">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mx-auto mb-3 bg-label-warning">
                        <i class="ti ti-calendar-month ti-lg"></i>
                    </div>
                    <h5 class="card-title mb-2">Checklist Bulanan</h5>
                    <p class="text-muted small mb-3">Reset setiap tanggal 1 pukul 00:00</p>
                    <a href="{{ route('perawatan.checklist.bulanan') }}" class="btn btn-warning btn-sm w-100">
                        <i class="ti ti-list-check me-1"></i> Buka Checklist
                    </a>
                </div>
            </div>
        </div>

        {{-- Checklist Tahunan --}}
        <div class="col">
            <div class="card h-100 card-hover">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mx-auto mb-3 bg-label-danger">
                        <i class="ti ti-calendar-year ti-lg"></i>
                    </div>
                    <h5 class="card-title mb-2">Checklist Tahunan</h5>
                    <p class="text-muted small mb-3">Reset setiap 1 Jan pukul 00:00</p>
                    <a href="{{ route('perawatan.checklist.tahunan') }}" class="btn btn-danger btn-sm w-100">
                        <i class="ti ti-list-check me-1"></i> Buka Checklist
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Management Cards --}}
    <div class="row row-cols-1 row-cols-md-2 g-4">
        {{-- Master Checklist --}}
        <div class="col">
            <div class="card h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="avatar avatar-lg bg-label-info me-3">
                            <i class="ti ti-clipboard-list ti-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Master Checklist</h5>
                            <p class="text-muted small mb-3">Kelola template checklist perawatan gedung. Tambah, edit, atau hapus kegiatan perawatan.</p>
                            <a href="{{ route('perawatan.master.index') }}" class="btn btn-info btn-sm">
                                <i class="ti ti-settings me-1"></i> Kelola Master
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Laporan --}}
        <div class="col">
            <div class="card h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="avatar avatar-lg bg-label-dark me-3">
                            <i class="ti ti-file-analytics ti-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Laporan Perawatan</h5>
                            <p class="text-muted small mb-3">Lihat dan download laporan perawatan yang telah dibuat. History lengkap semua periode.</p>
                            <a href="{{ route('perawatan.laporan.index') }}" class="btn btn-dark btn-sm">
                                <i class="ti ti-file-download me-1"></i> Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Section --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="ti ti-info-circle me-2"></i>Panduan Penggunaan</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary"><i class="ti ti-user-shield me-1"></i> Untuk Admin:</h6>
                            <ol class="small">
                                <li>Buat template checklist di <strong>Master Checklist</strong></li>
                                <li>Tentukan tipe periode (harian/mingguan/bulanan/tahunan)</li>
                                <li>Atur kategori: Kebersihan, Perawatan Rutin, Pengecekan, Lainnya</li>
                                <li>Aktifkan/nonaktifkan checklist sesuai kebutuhan</li>
                            </ol>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-success"><i class="ti ti-users me-1"></i> Untuk Karyawan:</h6>
                            <ol class="small">
                                <li>Pilih periode checklist yang ingin dikerjakan</li>
                                <li>Centang setiap kegiatan yang sudah selesai</li>
                                <li>Opsional: Tambahkan catatan dan foto bukti</li>
                                <li>Generate laporan setelah <strong>SEMUA</strong> checklist selesai</li>
                            </ol>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0 mt-2">
                        <h6 class="alert-heading mb-2"><i class="ti ti-refresh-alert me-1"></i> Sistem Auto-Reset</h6>
                        <p class="mb-2 small">Checklist akan otomatis direset sesuai periode:</p>
                        <ul class="mb-0 small">
                            <li><strong>Harian:</strong> Reset setiap pukul 00:00 (tengah malam)</li>
                            <li><strong>Mingguan:</strong> Reset setiap hari Senin pukul 00:00</li>
                            <li><strong>Bulanan:</strong> Reset setiap tanggal 1 pukul 00:00</li>
                            <li><strong>Tahunan:</strong> Reset setiap 1 Januari pukul 00:00</li>
                        </ul>
                        <p class="mb-0 mt-2 small"><strong>⚠️ Catatan:</strong> History dan laporan tetap tersimpan permanent dan tidak akan terhapus!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.card-hover {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    border-color: rgba(0,0,0,0.1);
}

.avatar-xl {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

.avatar-xl i {
    font-size: 2rem;
}
</style>
@endsection
