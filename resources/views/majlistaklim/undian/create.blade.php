@extends('layouts.app')
@section('titlepage', 'Buat Undian Umroh')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan / Undian Umroh /</span> Buat Undian
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-trophy me-2"></i>Buat Undian Umroh Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('majlistaklim.undian.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Nama Undian -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Undian <span class="text-danger">*</span></label>
                            <input type="text" name="nama_undian" class="form-control @error('nama_undian') is-invalid @enderror" 
                                   value="{{ old('nama_undian') }}" placeholder="Contoh: Undian Umroh 2025" required>
                            @error('nama_undian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Undian -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Undian <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_undian" class="form-control @error('tanggal_undian') is-invalid @enderror" 
                                   value="{{ old('tanggal_undian', date('Y-m-d')) }}" required>
                            @error('tanggal_undian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Minimal Kehadiran -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Minimal Kehadiran <span class="text-danger">*</span></label>
                            <input type="number" name="min_kehadiran" class="form-control @error('min_kehadiran') is-invalid @enderror" 
                                   value="{{ old('min_kehadiran', 25) }}" min="1" required>
                            @error('min_kehadiran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jumlah kehadiran minimal untuk ikut undian</small>
                        </div>

                        <!-- Jumlah Pemenang -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jumlah Pemenang <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_pemenang" class="form-control @error('jumlah_pemenang') is-invalid @enderror" 
                                   value="{{ old('jumlah_pemenang', 1) }}" min="1" required>
                            @error('jumlah_pemenang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Berapa orang yang akan menang</small>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kriteria Tambahan -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Kriteria Tambahan</label>
                            <textarea name="kriteria_tambahan" class="form-control" rows="3">{{ old('kriteria_tambahan') }}</textarea>
                            <small class="text-muted">Contoh: Belum pernah umroh, minimal usia 17 tahun, dll</small>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Jamaah yang memenuhi syarat minimal kehadiran akan otomatis masuk peserta undian</li>
                            <li>Jamaah yang sudah pernah menang undian umroh tidak akan ikut lagi</li>
                            <li>Status undian bisa diubah nanti setelah dibuat</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('majlistaklim.undian.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Undian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
