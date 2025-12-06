@extends('layouts.app')
@section('titlepage', 'Buat Ijin Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('ijin-santri.index') }}">Ijin Santri</a> / Buat Ijin</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-sm-12 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="ti ti-file-plus me-2"></i> Form Buat Ijin Santri</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('ijin-santri.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Santri <span class="text-danger">*</span></label>
                        <select name="santri_id" class="form-select @error('santri_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santri as $s)
                                <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nis }} - {{ $s->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('santri_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Ijin <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_ijin" 
                                   class="form-control @error('tanggal_ijin') is-invalid @enderror" 
                                   value="{{ old('tanggal_ijin', date('Y-m-d')) }}" required>
                            @error('tanggal_ijin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana" 
                                   class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                                   value="{{ old('tanggal_kembali_rencana') }}" required>
                            @error('tanggal_kembali_rencana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan Ijin <span class="text-danger">*</span></label>
                        <textarea name="alasan_ijin" rows="4" 
                                  class="form-control @error('alasan_ijin') is-invalid @enderror" 
                                  placeholder="Contoh: Keperluan keluarga, sakit, dll..."
                                  required>{{ old('alasan_ijin') }}</textarea>
                        @error('alasan_ijin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="3" 
                                  class="form-control @error('catatan') is-invalid @enderror" 
                                  placeholder="Catatan tambahan jika ada...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Info:</strong> Setelah ijin dibuat, silakan download PDF surat ijin untuk diserahkan ke Ustadz untuk TTD.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ijin-santri.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Ijin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
