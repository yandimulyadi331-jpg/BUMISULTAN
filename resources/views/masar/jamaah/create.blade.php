@extends('layouts.app')
@section('titlepage', 'Tambah Jamaah')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan / Data Jamaah /</span> Tambah Jamaah
@endsection

<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <form action="{{ route('masar.jamaah.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-user-plus me-2"></i>Form Tambah Jamaah</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Data Pribadi -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="ti ti-user me-2"></i>Data Pribadi</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nama_jamaah" class="form-label">Nama Jamaah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-user"></i></span>
                                <input type="text" class="form-control @error('nama_jamaah') is-invalid @enderror" 
                                    id="nama_jamaah" name="nama_jamaah" value="{{ old('nama_jamaah') }}" required>
                            </div>
                            @error('nama_jamaah')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK (16 Digit) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-id"></i></span>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                    id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" required>
                            </div>
                            @error('nik')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nomor jamaah akan di-generate otomatis oleh sistem</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-gender-bigender"></i></span>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            @error('jenis_kelamin')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                    id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            </div>
                            @error('tanggal_lahir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-map-pin"></i></span>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                    id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                            </div>
                            @error('alamat')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kontak -->
                        <div class="col-12 mt-3">
                            <h6 class="text-primary mb-3"><i class="ti ti-phone me-2"></i>Informasi Kontak</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="no_telepon" class="form-label">No. Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                    id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">
                            </div>
                            @error('no_telepon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Data Kepesertaan -->
                        <div class="col-12 mt-3">
                            <h6 class="text-primary mb-3"><i class="ti ti-mosque me-2"></i>Data Kepesertaan Majlis Ta'lim</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tahun_masuk" class="form-label">Tahun Masuk <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-calendar-event"></i></span>
                                <select class="form-select @error('tahun_masuk') is-invalid @enderror" id="tahun_masuk" name="tahun_masuk" required>
                                    <option value="">Pilih Tahun</option>
                                    @for($i = date('Y'); $i >= 2015; $i--)
                                        <option value="{{ $i }}" {{ old('tahun_masuk') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('tahun_masuk')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pin_fingerprint" class="form-label">PIN Fingerprint</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-fingerprint"></i></span>
                                <input type="text" class="form-control @error('pin_fingerprint') is-invalid @enderror" 
                                    id="pin_fingerprint" name="pin_fingerprint" value="{{ old('pin_fingerprint') }}" maxlength="10">
                            </div>
                            @error('pin_fingerprint')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">PIN untuk absensi di mesin fingerprint</small>
                        </div>

                        <!-- Foto -->
                        <div class="col-12 mt-3">
                            <h6 class="text-primary mb-3"><i class="ti ti-camera me-2"></i>Foto Jamaah</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Upload Foto</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                            @error('foto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preview Foto</label>
                            <div>
                                <img id="preview" src="{{ asset('assets/img/avatars/1.png') }}" 
                                    alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-12 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('masar.jamaah.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Preview foto
        $('#foto').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Validasi NIK harus 16 digit angka
        $('#nik').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 16) {
                this.value = this.value.slice(0, 16);
            }
        });

        // Validasi PIN hanya angka
        $('#pin_fingerprint').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endpush
