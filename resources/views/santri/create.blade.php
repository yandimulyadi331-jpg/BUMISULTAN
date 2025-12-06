@extends('layouts.app')
@section('titlepage', 'Tambah Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('santri.index') }}">Manajemen Saung Santri</a> / Tambah Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data" id="formSantri">
            @csrf
            
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h4 class="mb-0"><i class="ti ti-user-plus me-2"></i> Tambah Data Santri Baru</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Terdapat beberapa kesalahan:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#dataPribadi">
                                <i class="ti ti-user me-1"></i> Data Pribadi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dataKeluarga">
                                <i class="ti ti-users me-1"></i> Data Keluarga
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dataPendidikan">
                                <i class="ti ti-school me-1"></i> Data Pendidikan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dataHafalan">
                                <i class="ti ti-book me-1"></i> Data Hafalan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dataAsrama">
                                <i class="ti ti-building me-1"></i> Data Asrama
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Data Pribadi -->
                        <div class="tab-pane fade show active" id="dataPribadi">
                            <!-- Info NIS Otomatis -->
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>NIS (Nomor Induk Santri) akan dibuat otomatis oleh sistem</strong>
                                <br>
                                <small>Format: SS-TAHUN-NOMOR (Contoh: {{ $nisPreview ?? 'SS-2025-0001' }})</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">NIK (KTP)</label>
                                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                            value="{{ old('nik') }}" maxlength="16">
                                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                            value="{{ old('nama_lengkap') }}" required>
                                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Panggilan</label>
                                        <input type="text" name="nama_panggilan" class="form-control @error('nama_panggilan') is-invalid @enderror" 
                                            value="{{ old('nama_panggilan') }}">
                                        @error('nama_panggilan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                            <option value="">Pilih</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                            value="{{ old('tempat_lahir') }}" required>
                                        @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                            value="{{ old('tanggal_lahir') }}" required>
                                        @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="alamat_lengkap" class="form-control @error('alamat_lengkap') is-invalid @enderror" 
                                    rows="3" required>{{ old('alamat_lengkap') }}</textarea>
                                @error('alamat_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Kabupaten/Kota</label>
                                        <input type="text" name="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Kecamatan</label>
                                        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Kelurahan</label>
                                        <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos') }}" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">No. HP</label>
                                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                            value="{{ old('email') }}">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Foto</label>
                                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                        @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="text-muted">Max 2MB (JPG, PNG)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Keluarga -->
                        <div class="tab-pane fade" id="dataKeluarga">
                            <h5 class="mb-3">Data Orang Tua</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" 
                                            value="{{ old('nama_ayah') }}" required>
                                        @error('nama_ayah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" 
                                            value="{{ old('nama_ibu') }}" required>
                                        @error('nama_ibu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pekerjaan Ayah</label>
                                        <input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pekerjaan Ibu</label>
                                        <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">No. HP Ayah</label>
                                        <input type="text" name="no_hp_ayah" class="form-control" value="{{ old('no_hp_ayah') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">No. HP Ibu</label>
                                        <input type="text" name="no_hp_ibu" class="form-control" value="{{ old('no_hp_ibu') }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3">Data Wali (Jika Ada)</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Wali</label>
                                        <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Hubungan dengan Wali</label>
                                        <input type="text" name="hubungan_wali" class="form-control" 
                                            value="{{ old('hubungan_wali') }}" placeholder="Contoh: Paman, Kakak, dll">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">No. HP Wali</label>
                                        <input type="text" name="no_hp_wali" class="form-control" value="{{ old('no_hp_wali') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pendidikan -->
                        <div class="tab-pane fade" id="dataPendidikan">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Asal Sekolah</label>
                                        <input type="text" name="asal_sekolah" class="form-control" value="{{ old('asal_sekolah') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tingkat Pendidikan</label>
                                        <select name="tingkat_pendidikan" class="form-select">
                                            <option value="">Pilih</option>
                                            <option value="SD" {{ old('tingkat_pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                                            <option value="SMP" {{ old('tingkat_pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA" {{ old('tingkat_pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="Lainnya" {{ old('tingkat_pendidikan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Masuk <span class="text-danger">*</span></label>
                                        <input type="number" name="tahun_masuk" class="form-control @error('tahun_masuk') is-invalid @enderror" 
                                            value="{{ old('tahun_masuk', date('Y')) }}" min="2000" max="{{ date('Y')+1 }}" required>
                                        @error('tahun_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                            value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                                        @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Status Santri <span class="text-danger">*</span></label>
                                        <select name="status_santri" class="form-select @error('status_santri') is-invalid @enderror" required>
                                            <option value="aktif" {{ old('status_santri', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="cuti" {{ old('status_santri') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                            <option value="alumni" {{ old('status_santri') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                            <option value="keluar" {{ old('status_santri') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                        </select>
                                        @error('status_santri')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Aktif <span class="text-danger">*</span></label>
                                <select name="status_aktif" class="form-select" required>
                                    <option value="aktif" {{ old('status_aktif', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status_aktif') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Data Hafalan -->
                        <div class="tab-pane fade" id="dataHafalan">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>Info:</strong> Bagian ini untuk mencatat pencapaian hafalan Al-Qur'an santri
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Juz Hafalan</label>
                                        <input type="number" name="jumlah_juz_hafalan" class="form-control" 
                                            value="{{ old('jumlah_juz_hafalan', 0) }}" min="0" max="30">
                                        <small class="text-muted">Maksimal 30 Juz</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Halaman Hafalan</label>
                                        <input type="number" name="jumlah_halaman_hafalan" class="form-control" 
                                            value="{{ old('jumlah_halaman_hafalan', 0) }}" min="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Target Hafalan</label>
                                        <input type="text" name="target_hafalan" class="form-control" 
                                            value="{{ old('target_hafalan') }}" placeholder="Contoh: 30 Juz">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Mulai Tahfidz</label>
                                        <input type="date" name="tanggal_mulai_tahfidz" class="form-control" 
                                            value="{{ old('tanggal_mulai_tahfidz') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Khatam Terakhir</label>
                                        <input type="date" name="tanggal_khatam_terakhir" class="form-control" 
                                            value="{{ old('tanggal_khatam_terakhir') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan Hafalan</label>
                                <textarea name="catatan_hafalan" class="form-control" rows="4" 
                                    placeholder="Catatan progress hafalan, prestasi, atau informasi lainnya">{{ old('catatan_hafalan') }}</textarea>
                            </div>
                        </div>

                        <!-- Data Asrama -->
                        <div class="tab-pane fade" id="dataAsrama">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Asrama</label>
                                        <input type="text" name="nama_asrama" class="form-control" value="{{ old('nama_asrama') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Kamar</label>
                                        <input type="text" name="nomor_kamar" class="form-control" value="{{ old('nomor_kamar') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Pembina</label>
                                        <input type="text" name="nama_pembina" class="form-control" value="{{ old('nama_pembina') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan Tambahan</label>
                                <textarea name="keterangan" class="form-control" rows="4" 
                                    placeholder="Keterangan atau informasi tambahan lainnya">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('santri.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Data Santri
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #666;
}
.nav-tabs .nav-link.active {
    color: #667eea;
    font-weight: 600;
}
</style>
@endsection
