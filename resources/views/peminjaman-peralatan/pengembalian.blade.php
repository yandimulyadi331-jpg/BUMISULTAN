@extends('layouts.app')
@section('titlepage', 'Pengembalian Peralatan')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peminjaman-peralatan.index') }}">Peminjaman Peralatan</a> / Pengembalian</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="ti ti-arrow-back-up me-2"></i> Form Pengembalian Peralatan</h4>
            </div>
            <form action="{{ route('peminjaman-peralatan.pengembalian', $peminjamanPeralatan->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Info Peminjaman -->
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
                                <strong>Tgl Pinjam:</strong> {{ $peminjamanPeralatan->tanggal_pinjam->format('d/m/Y') }}<br>
                                <strong>Rencana Kembali:</strong> {{ $peminjamanPeralatan->tanggal_kembali_rencana->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Dikembalikan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_aktual" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" 
                                value="{{ old('tanggal_kembali_aktual', date('Y-m-d')) }}" required>
                            @error('tanggal_kembali_aktual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah yang Rusak</label>
                            <input type="number" name="jumlah_rusak" class="form-control @error('jumlah_rusak') is-invalid @enderror" 
                                value="{{ old('jumlah_rusak', 0) }}" min="0" max="{{ $peminjamanPeralatan->jumlah_dipinjam }}">
                            <small class="text-muted">Dari total {{ $peminjamanPeralatan->jumlah_dipinjam }} {{ $peminjamanPeralatan->peralatan->satuan }}</small>
                            @error('jumlah_rusak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Kondisi Saat Dikembalikan <span class="text-danger">*</span></label>
                            <textarea name="kondisi_saat_dikembalikan" class="form-control @error('kondisi_saat_dikembalikan') is-invalid @enderror" 
                                rows="3" placeholder="Jelaskan kondisi peralatan saat dikembalikan..." required>{{ old('kondisi_saat_dikembalikan', 'Baik, tidak ada kerusakan') }}</textarea>
                            @error('kondisi_saat_dikembalikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                rows="2" placeholder="Catatan tambahan jika ada...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Perhatian:</strong> Setelah proses pengembalian, stok peralatan akan otomatis diperbarui.
                        @if($peminjamanPeralatan->isTerlambat())
                            <br><span class="text-danger">Peminjaman ini <strong>TERLAMBAT</strong> dari jadwal yang ditentukan!</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('peminjaman-peralatan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-check me-1"></i> Proses Pengembalian
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
