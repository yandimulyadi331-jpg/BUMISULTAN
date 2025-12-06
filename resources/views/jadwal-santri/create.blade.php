@extends('layouts.app')
@section('titlepage', 'Tambah Jadwal Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('jadwal-santri.index') }}">Manajemen Saung Santri</a> / Tambah Jadwal Santri</span>
@endsection
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h4 class="mb-0"><i class="ti ti-calendar-plus me-2"></i> Tambah Jadwal Santri Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('jadwal-santri.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Nama Jadwal -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_jadwal" class="form-label">
                                Nama Jadwal <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                class="form-control @error('nama_jadwal') is-invalid @enderror" 
                                id="nama_jadwal" 
                                name="nama_jadwal" 
                                value="{{ old('nama_jadwal') }}" 
                                placeholder="Contoh: Ngaji, Tahfidz, Kajian"
                                required>
                            @error('nama_jadwal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipe Jadwal -->
                        <div class="col-md-6 mb-3">
                            <label for="tipe_jadwal" class="form-label">
                                Tipe Jadwal <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('tipe_jadwal') is-invalid @enderror" 
                                id="tipe_jadwal" 
                                name="tipe_jadwal" 
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="harian" {{ old('tipe_jadwal') == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="mingguan" {{ old('tipe_jadwal') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan" {{ old('tipe_jadwal') == 'bulanan' ? 'selected' : '' }}>Bulanan/Sekali Waktu</option>
                            </select>
                            @error('tipe_jadwal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Hari (untuk mingguan) -->
                        <div class="col-md-6 mb-3" id="field_hari" style="display: none;">
                            <label for="hari" class="form-label">Hari</label>
                            <select class="form-select @error('hari') is-invalid @enderror" id="hari" name="hari">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                            </select>
                            @error('hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal (untuk bulanan) -->
                        <div class="col-md-6 mb-3" id="field_tanggal" style="display: none;">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" 
                                class="form-control @error('tanggal') is-invalid @enderror" 
                                id="tanggal" 
                                name="tanggal" 
                                value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jam Mulai -->
                        <div class="col-md-3 mb-3">
                            <label for="jam_mulai" class="form-label">
                                Jam Mulai <span class="text-danger">*</span>
                            </label>
                            <input type="time" 
                                class="form-control @error('jam_mulai') is-invalid @enderror" 
                                id="jam_mulai" 
                                name="jam_mulai" 
                                value="{{ old('jam_mulai') }}" 
                                required>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jam Selesai -->
                        <div class="col-md-3 mb-3">
                            <label for="jam_selesai" class="form-label">
                                Jam Selesai <span class="text-danger">*</span>
                            </label>
                            <input type="time" 
                                class="form-control @error('jam_selesai') is-invalid @enderror" 
                                id="jam_selesai" 
                                name="jam_selesai" 
                                value="{{ old('jam_selesai') }}" 
                                required>
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tempat -->
                        <div class="col-md-6 mb-3">
                            <label for="tempat" class="form-label">Tempat</label>
                            <input type="text" 
                                class="form-control @error('tempat') is-invalid @enderror" 
                                id="tempat" 
                                name="tempat" 
                                value="{{ old('tempat') }}" 
                                placeholder="Contoh: Masjid, Aula, Kelas A">
                            @error('tempat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pembimbing -->
                        <div class="col-md-6 mb-3">
                            <label for="pembimbing" class="form-label">Pembimbing/Ustadz</label>
                            <input type="text" 
                                class="form-control @error('pembimbing') is-invalid @enderror" 
                                id="pembimbing" 
                                name="pembimbing" 
                                value="{{ old('pembimbing') }}" 
                                placeholder="Nama Ustadz/Ustadzah">
                            @error('pembimbing')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                            id="deskripsi" 
                            name="deskripsi" 
                            rows="3"
                            placeholder="Deskripsi kegiatan...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-6 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" 
                                class="form-control @error('keterangan') is-invalid @enderror" 
                                id="keterangan" 
                                name="keterangan" 
                                value="{{ old('keterangan') }}" 
                                placeholder="Keterangan tambahan">
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('jadwal-santri.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeJadwal = document.getElementById('tipe_jadwal');
    const fieldHari = document.getElementById('field_hari');
    const fieldTanggal = document.getElementById('field_tanggal');
    
    function toggleFields() {
        const tipe = tipeJadwal.value;
        
        fieldHari.style.display = 'none';
        fieldTanggal.style.display = 'none';
        
        if (tipe === 'mingguan') {
            fieldHari.style.display = 'block';
        } else if (tipe === 'bulanan') {
            fieldTanggal.style.display = 'block';
        }
    }
    
    tipeJadwal.addEventListener('change', toggleFields);
    toggleFields(); // Run on load
});
</script>
@endsection
