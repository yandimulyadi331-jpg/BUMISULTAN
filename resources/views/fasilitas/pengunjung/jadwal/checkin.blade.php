@extends('layouts.app')
@section('titlepage', 'Check-In dari Jadwal')

@section('content')
@section('navigasi')
    <span><a href="{{ route('pengunjung.jadwal.index') }}">Jadwal Pengunjung</a> / Check-In</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Check-In Pengunjung dari Jadwal</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Jadwal:</strong> {{ $jadwal->kode_jadwal }} - {{ $jadwal->nama_lengkap }}
                </div>

                <form action="{{ route('pengunjung.checkin') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jadwal_pengunjung_id" value="{{ $jadwal->id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ $jadwal->nama_lengkap }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Instansi/Perusahaan</label>
                                <input type="text" name="instansi" class="form-control" value="{{ $jadwal->instansi }}">
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
                                <input type="text" name="no_telepon" class="form-control" value="{{ $jadwal->no_telepon }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $jadwal->email }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Cabang</label>
                                <select name="kode_cabang" class="form-select">
                                    <option value="">Pilih Cabang</option>
                                    @foreach($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}" {{ $jadwal->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                            {{ $c->nama_cabang }}
                                        </option>
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
                                <input type="text" name="keperluan" class="form-control" value="{{ $jadwal->keperluan }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Bertemu Dengan</label>
                                <input type="text" name="bertemu_dengan" class="form-control" value="{{ $jadwal->bertemu_dengan }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Foto Pengunjung <span class="text-danger">*</span></label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required>
                                <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control" rows="2">{{ $jadwal->catatan }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('pengunjung.jadwal.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
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
    $(document).ready(function() {
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
