@extends('layouts.app')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <i class="ti ti-package me-2"></i>Manajemen Inventaris
                    </h2>
                    <div class="text-muted mt-1">Sistem manajemen inventaris perusahaan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            
            <!-- Statistik Cards -->
            <div class="row row-deck row-cards mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Inventaris</div>
                            </div>
                            <div class="h1 mb-3">{{ $totalInventaris }}</div>
                            <div class="d-flex mb-2">
                                <div>Item terdaftar</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Peminjaman</div>
                            </div>
                            <div class="h1 mb-3">{{ $totalPeminjaman }}</div>
                            <div class="d-flex mb-2">
                                <div>Semua transaksi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Sedang Dipinjam</div>
                            </div>
                            <div class="h1 mb-3 text-warning">{{ $peminjamanAktif }}</div>
                            <div class="d-flex mb-2">
                                <div>Belum dikembalikan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Event</div>
                            </div>
                            <div class="h1 mb-3">{{ $totalEvent }}</div>
                            <div class="d-flex mb-2">
                                <div>Event terdaftar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Cards -->
            <div class="row row-cards">
                
                <!-- Master Inventaris -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('inventaris.index') }}" class="card card-link card-link-pop">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg rounded" style="background-color: #206bc4;">
                                        <i class="ti ti-box fs-1"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium mb-1 fs-3">Master Inventaris</div>
                                    <div class="text-muted">Kelola data inventaris, tambah, edit, dan hapus item</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Peminjaman -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('peminjaman-inventaris.index') }}" class="card card-link card-link-pop">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg rounded" style="background-color: #d63939;">
                                        <i class="ti ti-hand-grab fs-1"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium mb-1 fs-3">Peminjaman</div>
                                    <div class="text-muted">Kelola peminjaman inventaris, approval, dan tracking</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Pengembalian -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('pengembalian-inventaris.index') }}" class="card card-link card-link-pop">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg rounded" style="background-color: #2fb344;">
                                        <i class="ti ti-arrow-back-up fs-1"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium mb-1 fs-3">Pengembalian</div>
                                    <div class="text-muted">Proses pengembalian inventaris dan kondisi barang</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- PERALATAN BS -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('peralatan.index') }}" class="card card-link card-link-pop">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg rounded" style="background-color: #f76707;">
                                        <i class="ti ti-tools fs-1"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium mb-1 fs-3">PERALATAN BS</div>
                                    <div class="text-muted">Kelola peralatan operasional sehari-hari</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- History & Tracking -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('history-inventaris.index') }}" class="card card-link card-link-pop">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg rounded" style="background-color: #ae3ec9;">
                                        <i class="ti ti-history fs-1"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium mb-1 fs-3">History & Tracking</div>
                                    <div class="text-muted">Riwayat aktivitas dan tracking inventaris</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Dashboard Analytics -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('history-inventaris.dashboard') }}" class="card card-link card-link-pop">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg rounded" style="background-color: #1e293b;">
                                        <i class="ti ti-chart-line fs-1"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium mb-1 fs-3">Dashboard Analytics</div>
                                    <div class="text-muted">Analisis, statistik, dan laporan inventaris</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <!-- Info Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="ti ti-info-circle me-2"></i>Informasi Sistem</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Fitur Manajemen Inventaris:</h4>
                                    <ul>
                                        <li><strong>Master Inventaris:</strong> Kelola data inventaris dengan lengkap (foto, spesifikasi, lokasi)</li>
                                        <li><strong>Peminjaman:</strong> Sistem peminjaman dengan TTD Digital dan approval workflow</li>
                                        <li><strong>Pengembalian:</strong> Proses pengembalian dengan validasi kondisi dan denda otomatis</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h4>Fitur Lanjutan:</h4>
                                    <ul>
                                        <li><strong>Event Management:</strong> Distribusi inventaris untuk event/kegiatan</li>
                                        <li><strong>History & Tracking:</strong> Tracking lengkap aktivitas inventaris</li>
                                        <li><strong>Analytics Dashboard:</strong> Laporan, statistik, dan visualisasi data</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('myscript')
<style>
    .card-link-pop:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .avatar i {
        color: white;
    }
</style>
@endpush
