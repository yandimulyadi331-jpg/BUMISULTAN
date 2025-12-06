@extends('layouts.app')
@section('titlepage', 'Edit Peminjaman Peralatan')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peminjaman-peralatan.index') }}">Peminjaman Peralatan</a> / Edit</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="ti ti-edit me-2"></i> Edit Peminjaman Peralatan</h4>
            </div>
            <form action="{{ route('peminjaman-peralatan.update', $peminjamanPeralatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Info Read-only -->
                    <div class="alert alert-info">
                        <h5 class="mb-3">Informasi Peminjaman</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nomor:</strong> {{ $peminjamanPeralatan->nomor_peminjaman }}<br>
                                <strong>Peralatan:</strong> {{ $peminjamanPeralatan->peralatan->nama_peralatan }}<br>
                                <strong>Peminjam:</strong> {{ $peminjamanPeralatan->nama_peminjam }}
                            </div>
                            <div class="col-md-6">
                                <strong>Jumlah:</strong> {{ $peminjamanPeralatan->jumlah_dipinjam }} {{ $peminjamanPeralatan->peralatan->satuan }}<br>
                                <strong>Tgl Pinjam:</strong> {{ $peminjamanPeralatan->tanggal_pinjam->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                                value="{{ old('tanggal_kembali_rencana', $peminjamanPeralatan->tanggal_kembali_rencana->format('Y-m-d')) }}" required>
                            @error('tanggal_kembali_rencana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                            <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                value="{{ old('keperluan', $peminjamanPeralatan->keperluan) }}" required>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                rows="3">{{ old('catatan', $peminjamanPeralatan->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="ti ti-info-circle me-2"></i>
                        Anda hanya bisa mengubah tanggal rencana kembali dan keperluan. Data lainnya tidak dapat diubah.
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('peminjaman-peralatan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
