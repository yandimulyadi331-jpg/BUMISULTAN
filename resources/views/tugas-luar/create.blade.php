@extends('layouts.app')
@section('titlepage', 'Tambah Tugas Luar')

@section('content')
@section('navigasi')
    <a href="{{ route('tugas-luar.index') }}">Tugas Luar</a>
    <span>Tambah Tugas Luar</span>
@endsection

<div class="row mt-3">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-briefcase me-2"></i>
                    Form Tugas Luar Karyawan
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tugas-luar.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                    id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($karyawanHadir as $karyawan)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="karyawan_list[]" 
                                                value="{{ $karyawan->nik }}" id="karyawan{{ $karyawan->nik }}"
                                                {{ in_array($karyawan->nik, old('karyawan_list', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="karyawan{{ $karyawan->nik }}">
                                                <strong>{{ $karyawan->nama_karyawan }}</strong> - {{ $karyawan->nik }}
                                                <br><small class="text-muted">
                                                    {{ $karyawan->jabatan->nama_jabatan ?? '-' }} | 
                                                    {{ $karyawan->departemen->nama_dept ?? '-' }}
                                                </small>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="alert alert-warning mb-0">
                                            <i class="ti ti-alert-circle"></i>
                                            Tidak ada karyawan yang hadir hari ini
                                        </div>
                                    @endforelse
                                </div>
                                @error('karyawan_list')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Pilih satu atau lebih karyawan yang akan tugas luar</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="tujuan" class="form-label">Tujuan Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tujuan') is-invalid @enderror" 
                            id="tujuan" name="tujuan" value="{{ old('tujuan') }}" 
                            placeholder="Contoh: Meeting dengan Client, Survey Lokasi, dll" required>
                        @error('tujuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                            id="keterangan" name="keterangan" rows="3" 
                            placeholder="Keterangan tambahan mengenai tugas...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="waktu_keluar" class="form-label">Waktu Keluar <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('waktu_keluar') is-invalid @enderror" 
                                    id="waktu_keluar" name="waktu_keluar" value="{{ old('waktu_keluar') }}" required>
                                @error('waktu_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        <strong>Catatan:</strong>
                        <ul class="mb-0">
                            <li>Pastikan karyawan sudah melakukan presensi hadir</li>
                            <li>Tugas luar akan tercatat sebagai karyawan sedang bertugas di luar kantor</li>
                            <li>Status dapat diubah menjadi selesai setelah karyawan kembali</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tugas-luar.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    // Auto select current time for waktu_keluar
    document.getElementById('waktu_keluar').value = new Date().toLocaleTimeString('en-GB', {hour: '2-digit', minute:'2-digit'});
</script>
@endpush
