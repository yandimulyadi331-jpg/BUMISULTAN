@extends('layouts.app')
@section('titlepage', 'Tambah Jadwal Khidmat')

@section('content')
@section('navigasi')
    <span><a href="{{ route('khidmat.index') }}">Saung Santri / Khidmat</a> / Tambah Jadwal</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <h5 class="text-white mb-0">
                    <i class="ti ti-plus me-2"></i>Tambah Jadwal Khidmat
                </h5>
            </div>

            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('khidmat.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nama_kelompok" class="form-label">Nama Kelompok <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelompok" id="nama_kelompok" 
                               class="form-control @error('nama_kelompok') is-invalid @enderror" 
                               value="{{ old('nama_kelompok') }}" 
                               placeholder="Contoh: Kelompok Khidmat 1" required>
                        @error('nama_kelompok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_jadwal" class="form-label">Tanggal Jadwal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_jadwal" id="tanggal_jadwal" 
                               class="form-control @error('tanggal_jadwal') is-invalid @enderror" 
                               value="{{ old('tanggal_jadwal') }}" required>
                        @error('tanggal_jadwal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="saldo_masuk" class="form-label">Saldo Masuk</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="saldo_masuk" id="saldo_masuk" 
                                   class="form-control @error('saldo_masuk') is-invalid @enderror" 
                                   value="{{ old('saldo_masuk', 0) }}" min="0" step="0.01">
                            @error('saldo_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Jumlah saldo yang masuk untuk jadwal ini</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Petugas Khidmat (Min. 3 Santri) <span class="text-danger">*</span></label>
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>Silakan pilih minimal 3 santri sebagai petugas khidmat
                        </div>
                        <select name="petugas[]" id="petugas" class="form-select @error('petugas') is-invalid @enderror" 
                                multiple="multiple" required>
                            @foreach($santri as $s)
                                <option value="{{ $s->id }}" {{ in_array($s->id, old('petugas', [])) ? 'selected' : '' }}>
                                    {{ $s->nama_lengkap }} - {{ $s->kelas ?? 'Tidak ada kelas' }}
                                </option>
                            @endforeach
                        </select>
                        @error('petugas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tekan Ctrl/Cmd untuk memilih lebih dari satu</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Saldo Awal</label>
                        <input type="text" class="form-control" value="Rp {{ number_format($saldoAwal, 0, ',', '.') }}" readonly>
                        <small class="text-muted">Saldo awal diambil dari saldo akhir jadwal sebelumnya</small>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" 
                                  class="form-control @error('keterangan') is-invalid @enderror" 
                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('khidmat.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#petugas').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih petugas khidmat',
        allowClear: true
    });
});
</script>
@endpush

@endsection
