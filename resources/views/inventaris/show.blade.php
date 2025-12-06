@extends('layouts.app')
@section('titlepage', 'Detail Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Detail</span>
@endsection

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-box me-2"></i>Detail Inventaris</h4>
                    <div>
                        <a href="{{ route('inventaris.edit', $inventaris->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @if($inventaris->foto)
                        <div class="text-center mb-4">
                            <img src="{{ Storage::url($inventaris->foto) }}" alt="{{ $inventaris->nama_barang }}" 
                                class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                        @endif
                    </div>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th width="30%"><i class="ti ti-barcode me-2"></i>Kode Inventaris</th>
                        <td><strong class="text-primary">{{ $inventaris->kode_inventaris }}</strong></td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-box me-2"></i>Nama Barang</th>
                        <td><strong>{{ $inventaris->nama_barang }}</strong></td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-category me-2"></i>Kategori</th>
                        <td><span class="badge bg-info">{{ ucfirst($inventaris->kategori) }}</span></td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-package me-2"></i>Jumlah</th>
                        <td><strong>{{ $inventaris->jumlah }}</strong> {{ $inventaris->satuan }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-check me-2"></i>Tersedia</th>
                        <td><strong class="text-success">{{ $inventaris->jumlahTersedia() }}</strong> {{ $inventaris->satuan }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-flag me-2"></i>Status</th>
                        <td>
                            @if($inventaris->status == 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($inventaris->status == 'dipinjam')
                                <span class="badge bg-warning">Dipinjam</span>
                            @elseif($inventaris->status == 'rusak')
                                <span class="badge bg-danger">Rusak</span>
                            @elseif($inventaris->status == 'maintenance')
                                <span class="badge bg-info">Maintenance</span>
                            @else
                                <span class="badge bg-dark">Hilang</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-check-circle me-2"></i>Kondisi</th>
                        <td>
                            @if($inventaris->kondisi == 'baik')
                                <span class="badge bg-success">Baik</span>
                            @elseif($inventaris->kondisi == 'rusak_ringan')
                                <span class="badge bg-warning">Rusak Ringan</span>
                            @else
                                <span class="badge bg-danger">Rusak Berat</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-map-pin me-2"></i>Lokasi</th>
                        <td>{{ $inventaris->lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-building me-2"></i>Cabang</th>
                        <td>{{ $inventaris->cabang->nama_cabang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-file-text me-2"></i>Deskripsi</th>
                        <td>{{ $inventaris->deskripsi ?? '-' }}</td>
                    </tr>
                    @if($inventaris->spesifikasi)
                    <tr>
                        <th><i class="ti ti-notes me-2"></i>Spesifikasi</th>
                        <td>{{ $inventaris->spesifikasi }}</td>
                    </tr>
                    @endif
                    @if($inventaris->tanggal_perolehan)
                    <tr>
                        <th><i class="ti ti-calendar me-2"></i>Tanggal Perolehan</th>
                        <td>{{ $inventaris->tanggal_perolehan->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    @if($inventaris->catatan)
                    <tr>
                        <th><i class="ti ti-notes me-2"></i>Catatan</th>
                        <td>{{ $inventaris->catatan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th><i class="ti ti-user me-2"></i>Dibuat Oleh</th>
                        <td>{{ $inventaris->creator->nama ?? 'System' }} - {{ $inventaris->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-edit me-2"></i>Terakhir Diupdate</th>
                        <td>{{ $inventaris->updater->nama ?? 'System' }} - {{ $inventaris->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2 mt-3">
                    @if($inventaris->isTersedia())
                    <a href="{{ route('peminjaman-inventaris.create') }}?inventaris_id={{ $inventaris->id }}" class="btn btn-success">
                        <i class="fa fa-hand-holding me-2"></i>Pinjam Sekarang
                    </a>
                    @endif
                    <a href="{{ route('history-inventaris.by-inventaris', $inventaris->id) }}" class="btn btn-info">
                        <i class="fa fa-history me-2"></i>Lihat History
                    </a>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="card mt-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="inventarisTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="peminjaman-tab" data-bs-toggle="tab" data-bs-target="#peminjaman" type="button" role="tab">
                            <i class="ti ti-hand-grab me-1"></i> Peminjaman
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                            <i class="ti ti-history me-1"></i> History
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <div class="tab-content" id="inventarisTabContent">
                    <!-- Tab: Peminjaman -->
                    <div class="tab-pane fade show active" id="peminjaman" role="tabpanel">
                        <h5 class="mb-3"><i class="ti ti-hand-grab me-2"></i>Daftar Peminjaman Aktif</h5>
                        @if($peminjamanAktif->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Peminjam</th>
                                        <th>NIK</th>
                                        <th>Jumlah</th>
                                        <th>Tgl Pinjam</th>
                                        <th>Tgl Kembali Rencana</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamanAktif as $index => $pinjam)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pinjam->karyawan->nama ?? 'N/A' }}</td>
                                        <td>{{ $pinjam->karyawan_id }}</td>
                                        <td><span class="badge bg-info">{{ $pinjam->jumlah_pinjam }} {{ $inventaris->satuan }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-warning">Dipinjam</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>Tidak ada peminjaman aktif saat ini
                        </div>
                        @endif
                    </div>
                    
                    <!-- Tab: History -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <h5 class="mb-3"><i class="ti ti-history me-2"></i>Riwayat Aktivitas</h5>
                        @if(isset($recentHistories) && $recentHistories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Aktivitas</th>
                                        <th>User</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentHistories as $history)
                                    <tr>
                                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                        <td><span class="badge bg-primary">{{ $history->aktivitas }}</span></td>
                                        <td>{{ $history->user->name ?? 'System' }}</td>
                                        <td>{{ $history->keterangan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>Belum ada riwayat aktivitas
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Peminjaman Aktif -->
        @if($peminjamanAktif->count() > 0)
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fa fa-exclamation-triangle me-2"></i>Peminjaman Aktif</h5>
            </div>
            <div class="card-body">
                @foreach($peminjamanAktif as $pinjam)
                <div class="mb-3 pb-3 border-bottom">
                    <strong>{{ $pinjam->karyawan->nama ?? 'N/A' }}</strong><br>
                    <small class="text-muted">NIK: {{ $pinjam->karyawan_id }}</small><br>
                    <span class="badge bg-info mt-1">{{ $pinjam->jumlah_pinjam }} {{ $inventaris->satuan }}</span><br>
                    <small>Kembali: {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali_rencana)->format('d/m/Y') }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
