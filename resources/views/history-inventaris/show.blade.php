@extends('layouts.app')
@section('titlepage', 'Detail History Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / History Inventaris / Detail</span>
@endsection

<div class="row">
    <div class="col-lg-12">
        <!-- Header Card -->
        <div class="card mb-3">
            <div class="card-header bg-{{ $historyInventaris->getJenisAktivitasColor() }} text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fa fa-history me-2"></i>{{ $historyInventaris->getJenisAktivitasLabel() }}
                        </h4>
                        <p class="mb-0 mt-1">
                            <i class="fa fa-calendar me-1"></i> {{ $historyInventaris->created_at->format('d F Y, H:i:s') }} WIB
                            <span class="ms-2">({{ $historyInventaris->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    <a href="{{ route('history-inventaris.index') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Informasi Barang -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa fa-box me-2"></i>Informasi Barang</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Kode Inventaris</label>
                        <p class="mb-0 fw-bold fs-5 text-primary">{{ $historyInventaris->inventaris->kode_inventaris }}</p>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="text-muted small">Nama Barang</label>
                        <p class="mb-0 fw-bold fs-5">{{ $historyInventaris->inventaris->nama_barang }}</p>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="text-muted small">Kategori</label>
                        <p class="mb-0">{{ ucfirst($historyInventaris->inventaris->kategori) }}</p>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="text-muted small">Jumlah</label>
                        <p class="mb-0 fw-bold">{{ $historyInventaris->jumlah ?? 1 }} {{ $historyInventaris->inventaris->satuan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Peminjaman (jika ada) -->
        @if($historyInventaris->peminjaman)
        <div class="card mb-3 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fa fa-hand-holding me-2"></i>Detail Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Kode Peminjaman</label>
                        <p class="mb-0 fw-bold text-primary">{{ $historyInventaris->peminjaman->kode_peminjaman }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Nama Peminjam</label>
                        <p class="mb-0 fw-bold">{{ $historyInventaris->peminjaman->nama_peminjam }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">No. Identitas</label>
                        <p class="mb-0">{{ $historyInventaris->peminjaman->no_identitas ?? '-' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Tanggal Pinjam</label>
                        <p class="mb-0">
                            <i class="fa fa-calendar text-success"></i> 
                            {{ \Carbon\Carbon::parse($historyInventaris->peminjaman->tanggal_pinjam)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Rencana Kembali</label>
                        <p class="mb-0">
                            <i class="fa fa-calendar text-warning"></i> 
                            {{ \Carbon\Carbon::parse($historyInventaris->peminjaman->tanggal_kembali_rencana)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Jumlah Pinjam</label>
                        <p class="mb-0 fw-bold">{{ $historyInventaris->peminjaman->jumlah_pinjam }} {{ $historyInventaris->inventaris->satuan }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $historyInventaris->peminjaman->status_peminjaman == 'disetujui' ? 'success' : 'warning' }}">
                                {{ ucfirst($historyInventaris->peminjaman->status_peminjaman) }}
                            </span>
                        </p>
                    </div>
                </div>

                @if($historyInventaris->peminjaman->keperluan)
                <div class="mb-3">
                    <label class="text-muted small">Keperluan</label>
                    <p class="mb-0">{{ $historyInventaris->peminjaman->keperluan }}</p>
                </div>
                @endif

                @if($historyInventaris->peminjaman->catatan_peminjaman)
                <div class="mb-3">
                    <label class="text-muted small">Catatan Peminjaman</label>
                    <p class="mb-0">{{ $historyInventaris->peminjaman->catatan_peminjaman }}</p>
                </div>
                @endif

                <!-- Tanda Tangan Peminjaman -->
                <div class="row mt-4">
                    @if($historyInventaris->peminjaman->ttd_peminjam)
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <label class="text-muted small d-block mb-2">Tanda Tangan Peminjam</label>
                            <img src="{{ Storage::url($historyInventaris->peminjaman->ttd_peminjam) }}" 
                                 alt="TTD Peminjam" 
                                 class="img-fluid" 
                                 style="max-height: 150px; border: 1px solid #ddd; padding: 10px; background: white;">
                            <p class="mb-0 mt-2 fw-bold">{{ $historyInventaris->peminjaman->nama_peminjam }}</p>
                        </div>
                    </div>
                    @endif

                    @if($historyInventaris->peminjaman->ttd_petugas)
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <label class="text-muted small d-block mb-2">Tanda Tangan Petugas</label>
                            <img src="{{ Storage::url($historyInventaris->peminjaman->ttd_petugas) }}" 
                                 alt="TTD Petugas" 
                                 class="img-fluid" 
                                 style="max-height: 150px; border: 1px solid #ddd; padding: 10px; background: white;">
                            <p class="mb-0 mt-2 fw-bold">{{ $historyInventaris->peminjaman->createdBy->name ?? 'Petugas' }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if($historyInventaris->peminjaman->foto_barang)
                <div class="mt-3">
                    <label class="text-muted small d-block mb-2">Foto Barang Saat Dipinjam</label>
                    <img src="{{ Storage::url($historyInventaris->peminjaman->foto_barang) }}" 
                         alt="Foto Barang" 
                         class="img-fluid rounded" 
                         style="max-height: 300px;">
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Detail Pengembalian (jika ada) -->
        @if($historyInventaris->pengembalian)
        <div class="card mb-3 border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-undo me-2"></i>Detail Pengembalian</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Kode Pengembalian</label>
                        <p class="mb-0 fw-bold text-success">{{ $historyInventaris->pengembalian->kode_pengembalian }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Tanggal Pengembalian</label>
                        <p class="mb-0">
                            <i class="fa fa-calendar text-success"></i> 
                            {{ \Carbon\Carbon::parse($historyInventaris->pengembalian->tanggal_pengembalian)->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Jumlah Kembali</label>
                        <p class="mb-0 fw-bold">{{ $historyInventaris->pengembalian->jumlah_kembali }} {{ $historyInventaris->inventaris->satuan }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Kondisi Barang</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $historyInventaris->pengembalian->kondisi_barang == 'baik' ? 'success' : 'warning' }}">
                                {{ ucfirst(str_replace('_', ' ', $historyInventaris->pengembalian->kondisi_barang)) }}
                            </span>
                        </p>
                    </div>
                    @if($historyInventaris->pengembalian->denda > 0)
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Denda</label>
                        <p class="mb-0 fw-bold text-danger">Rp {{ number_format($historyInventaris->pengembalian->denda, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Diterima Oleh</label>
                        <p class="mb-0">{{ $historyInventaris->pengembalian->diterimaOleh->name ?? '-' }}</p>
                    </div>
                </div>

                @if($historyInventaris->pengembalian->keterangan)
                <div class="mb-3">
                    <label class="text-muted small">Keterangan</label>
                    <p class="mb-0">{{ $historyInventaris->pengembalian->keterangan }}</p>
                </div>
                @endif

                <!-- Tanda Tangan Pengembalian -->
                <div class="row mt-4">
                    @if($historyInventaris->pengembalian->ttd_peminjam)
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <label class="text-muted small d-block mb-2">Tanda Tangan Pengembalian</label>
                            <img src="{{ Storage::url($historyInventaris->pengembalian->ttd_peminjam) }}" 
                                 alt="TTD Peminjam" 
                                 class="img-fluid" 
                                 style="max-height: 150px; border: 1px solid #ddd; padding: 10px; background: white;">
                            <p class="mb-0 mt-2 fw-bold">{{ $historyInventaris->pengembalian->peminjaman->nama_peminjam ?? 'Peminjam' }}</p>
                        </div>
                    </div>
                    @endif

                    @if($historyInventaris->pengembalian->ttd_petugas)
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <label class="text-muted small d-block mb-2">Tanda Tangan Petugas Penerima</label>
                            <img src="{{ Storage::url($historyInventaris->pengembalian->ttd_petugas) }}" 
                                 alt="TTD Petugas" 
                                 class="img-fluid" 
                                 style="max-height: 150px; border: 1px solid #ddd; padding: 10px; background: white;">
                            <p class="mb-0 mt-2 fw-bold">{{ $historyInventaris->pengembalian->diterimaOleh->name ?? 'Petugas' }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if($historyInventaris->pengembalian->foto_pengembalian)
                <div class="mt-3">
                    <label class="text-muted small d-block mb-2">Foto Barang Saat Dikembalikan</label>
                    <img src="{{ Storage::url($historyInventaris->pengembalian->foto_pengembalian) }}" 
                         alt="Foto Pengembalian" 
                         class="img-fluid rounded" 
                         style="max-height: 300px;">
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Informasi Sistem -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa fa-cog me-2"></i>Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Diproses Oleh</label>
                        <p class="mb-0">{{ $historyInventaris->user->name ?? 'System' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted small">Waktu Pencatatan</label>
                        <p class="mb-0">{{ $historyInventaris->created_at->format('d F Y, H:i:s') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
