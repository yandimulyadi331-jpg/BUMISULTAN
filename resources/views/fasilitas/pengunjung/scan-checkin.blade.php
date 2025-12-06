@extends('layouts.app')
@section('titlepage', 'Scan QR - Check-In')

@section('content')
@section('navigasi')
    <span>Check-In Pengunjung via QR Code</span>
@endsection
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0 text-center">Check-In Pengunjung</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fa fa-check-circle me-2"></i>
                    Silakan isi formulir berikut untuk melakukan check-in
                </div>

                <form action="{{ route('pengunjung.checkin') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Instansi/Perusahaan</label>
                                <input type="text" name="instansi" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Identitas (KTP/SIM)</label>
                                <input type="text" name="no_identitas" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" required>
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Cabang</label>
                                <select name="kode_cabang" class="form-select">
                                    <option value="">Pilih Cabang</option>
                                    @foreach($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Keperluan <span class="text-danger">*</span></label>
                                <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" required>
                                @error('keperluan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Bertemu Dengan</label>
                                <input type="text" name="bertemu_dengan" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Foto Pengunjung <span class="text-danger">*</span></label>
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*" required>
                                <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-sign-in me-2"></i> Check-In Sekarang
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
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Check-In Berhasil!',
            html: '<div class="mb-3"><i class="fa fa-check-circle" style="font-size: 50px; color: #28a745;"></i></div>' +
                  '<p>{{ session("success") }}</p>',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745'
        }).then(() => {
            // Reset form after success
            document.querySelector('form').reset();
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
</script>
@endpush
