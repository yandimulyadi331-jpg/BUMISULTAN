@extends('layouts.app')
@section('titlepage', 'Edit Pengunjung')

@section('content')
@section('navigasi')
    <span><a href="{{ route('pengunjung.index') }}">Manajemen Pengunjung</a> / Edit</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Data Pengunjung</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pengunjung.update', $pengunjung->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                    value="{{ old('nama_lengkap', $pengunjung->nama_lengkap) }}" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Instansi/Perusahaan</label>
                                <input type="text" name="instansi" class="form-control @error('instansi') is-invalid @enderror" 
                                    value="{{ old('instansi', $pengunjung->instansi) }}">
                                @error('instansi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Identitas (KTP/SIM)</label>
                                <input type="text" name="no_identitas" class="form-control @error('no_identitas') is-invalid @enderror" 
                                    value="{{ old('no_identitas', $pengunjung->no_identitas) }}">
                                @error('no_identitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" 
                                    value="{{ old('no_telepon', $pengunjung->no_telepon) }}" required>
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $pengunjung->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Cabang</label>
                                <select name="kode_cabang" class="form-select @error('kode_cabang') is-invalid @enderror">
                                    <option value="">Pilih Cabang</option>
                                    @foreach($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}" {{ old('kode_cabang', $pengunjung->kode_cabang) == $c->kode_cabang ? 'selected' : '' }}>
                                            {{ $c->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_cabang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat', $pengunjung->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Keperluan <span class="text-danger">*</span></label>
                                <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                    value="{{ old('keperluan', $pengunjung->keperluan) }}" required>
                                @error('keperluan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Bertemu Dengan</label>
                                <input type="text" name="bertemu_dengan" class="form-control @error('bertemu_dengan') is-invalid @enderror" 
                                    value="{{ old('bertemu_dengan', $pengunjung->bertemu_dengan) }}">
                                @error('bertemu_dengan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Foto Pengunjung</label>
                                @if($pengunjung->foto)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($pengunjung->foto) }}" alt="Foto Saat Ini" 
                                            class="img-thumbnail" style="max-height: 150px;">
                                        <p class="text-muted small">Foto saat ini (akan diganti jika upload foto baru)</p>
                                    </div>
                                @endif
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2">{{ old('catatan', $pengunjung->catatan) }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('pengunjung.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Simpan Perubahan
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
    $(document).ready(function() {
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session("success") }}'
            });
        @endif

        @if($errors->any())
            let errorList = '<ul class="text-start mb-0">';
            @foreach($errors->all() as $error)
                errorList += '<li>{{ $error }}</li>';
            @endforeach
            errorList += '</ul>';
            
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errorList,
                confirmButtonColor: '#3085d6'
            });
        @endif
    });
</script>
@endpush
