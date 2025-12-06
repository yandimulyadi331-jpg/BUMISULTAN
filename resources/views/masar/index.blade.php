@extends('layouts.app')
@section('titlepage', 'Majlis Ta\'lim Al-Ikhlas')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> MASAR
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="ti ti-mosque me-2"></i>MASAR</h4>
            </div>
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-pills mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('masar.jamaah.index') }}" class="nav-link {{ request()->is('masar/jamaah') && !request()->has('jenis_kelamin') ? 'active' : '' }}">
                            <i class="ti ti-users me-1"></i> Semua Jamaah
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('masar.jamaah.index') }}?jenis_kelamin=laki-laki" class="nav-link {{ request('jenis_kelamin') === 'laki-laki' ? 'active' : '' }}">
                            <i class="ti ti-user me-1"></i> Jamaah Laki-laki
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('masar.jamaah.index') }}?jenis_kelamin=perempuan" class="nav-link {{ request('jenis_kelamin') === 'perempuan' ? 'active' : '' }}">
                            <i class="ti ti-user-circle me-1"></i> Jamaah Perempuan
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('masar.hadiah.index') }}" class="nav-link {{ request()->is('masar/hadiah*') ? 'active' : '' }}">
                            <i class="ti ti-gift me-1"></i> Hadiah
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('masar.distribusi.index') }}" class="nav-link {{ request()->is('masar/distribusi*') ? 'active' : '' }}">
                            <i class="ti ti-truck-delivery me-1"></i> Distribusi Hadiah
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#laporanMenu">
                            <i class="ti ti-file-report me-1"></i> Laporan <i class="ti ti-chevron-down ms-1"></i>
                        </a>
                    </li>
                </ul>

                <!-- Laporan Dropdown Menu -->
                <div class="collapse {{ request()->is('masar/laporan*') ? 'show' : '' }}" id="laporanMenu">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <i class="ti ti-chart-bar display-4 text-primary mb-3"></i>
                                    <h5 class="card-title">Laporan Stok Per Ukuran</h5>
                                    <p class="card-text text-muted">Lihat detail stok hadiah berdasarkan ukuran</p>
                                    <a href="{{ route('masar.laporan.stokUkuran') }}" class="btn btn-primary">
                                        <i class="ti ti-eye me-1"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <i class="ti ti-file-report display-4 text-success mb-3"></i>
                                    <h5 class="card-title">Rekapitulasi Distribusi</h5>
                                    <p class="card-text text-muted">Rekap lengkap distribusi hadiah ke jamaah</p>
                                    <a href="{{ route('masar.laporan.rekapDistribusi') }}" class="btn btn-success">
                                        <i class="ti ti-eye me-1"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
