@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Edit Master Checklist</h2>
                <div class="text-muted mt-1">Edit template checklist perawatan gedung</div>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('perawatan.master.index') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="{{ route('perawatan.master.update', $master->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Master Checklist</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" 
                                    placeholder="Contoh: Buang Sampah Ruang Tamu" value="{{ old('nama_kegiatan', $master->nama_kegiatan) }}" required>
                                @error('nama_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" 
                                    placeholder="Detail kegiatan perawatan...">{{ old('deskripsi', $master->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Tipe Periode</label>
                                        <select name="tipe_periode" id="tipe_periode" class="form-select @error('tipe_periode') is-invalid @enderror" required>
                                            <option value="">Pilih Periode...</option>
                                            <option value="harian" {{ old('tipe_periode', $master->tipe_periode) == 'harian' ? 'selected' : '' }}>
                                                Harian (Reset setiap hari)
                                            </option>
                                            <option value="mingguan" {{ old('tipe_periode', $master->tipe_periode) == 'mingguan' ? 'selected' : '' }}>
                                                Mingguan (Reset setiap minggu)
                                            </option>
                                            <option value="bulanan" {{ old('tipe_periode', $master->tipe_periode) == 'bulanan' ? 'selected' : '' }}>
                                                Bulanan (Reset setiap bulan)
                                            </option>
                                            <option value="tahunan" {{ old('tipe_periode', $master->tipe_periode) == 'tahunan' ? 'selected' : '' }}>
                                                Tahunan (Reset setiap tahun)
                                            </option>
                                        </select>
                                        @error('tipe_periode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Kategori</label>
                                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                            <option value="">Pilih Kategori...</option>
                                            <option value="kebersihan" {{ old('kategori', $master->kategori) == 'kebersihan' ? 'selected' : '' }}>
                                                🧹 Kebersihan
                                            </option>
                                            <option value="perawatan_rutin" {{ old('kategori', $master->kategori) == 'perawatan_rutin' ? 'selected' : '' }}>
                                                🔧 Perawatan Rutin
                                            </option>
                                            <option value="pengecekan" {{ old('kategori', $master->kategori) == 'pengecekan' ? 'selected' : '' }}>
                                                ✅ Pengecekan
                                            </option>
                                            <option value="lainnya" {{ old('kategori', $master->kategori) == 'lainnya' ? 'selected' : '' }}>
                                                📋 Lainnya
                                            </option>
                                        </select>
                                        @error('kategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Jadwal Harian -->
                            <div id="jadwal_harian" style="display: none;">
                                <div class="card bg-light mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">⏰ Jadwal Harian (Berbasis Jam)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Jam Mulai</label>
                                                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', $master->jam_mulai ? date('H:i', strtotime($master->jam_mulai)) : '') }}">
                                                    <small class="form-hint">Jam paling awal untuk checklist ini</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Jam Selesai</label>
                                                    <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai', $master->jam_selesai ? date('H:i', strtotime($master->jam_selesai)) : '') }}">
                                                    <small class="form-hint">Jam paling akhir untuk checklist ini</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info mb-0">
                                            <strong>Info:</strong> Checklist ini hanya akan tampil untuk shift yang jam kerjanya overlap dengan jam yang Anda tentukan.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jadwal Mingguan -->
                            <div id="jadwal_mingguan" style="display: none;">
                                <div class="card bg-light mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">📅 Jadwal Mingguan (Hari dalam Minggu)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Hari Target</label>
                                            <select name="hari_minggu" class="form-select">
                                                <option value="">Pilih Hari...</option>
                                                <option value="1" {{ old('hari_minggu', $master->hari_minggu) == '1' ? 'selected' : '' }}>Senin</option>
                                                <option value="2" {{ old('hari_minggu', $master->hari_minggu) == '2' ? 'selected' : '' }}>Selasa</option>
                                                <option value="3" {{ old('hari_minggu', $master->hari_minggu) == '3' ? 'selected' : '' }}>Rabu</option>
                                                <option value="4" {{ old('hari_minggu', $master->hari_minggu) == '4' ? 'selected' : '' }}>Kamis</option>
                                                <option value="5" {{ old('hari_minggu', $master->hari_minggu) == '5' ? 'selected' : '' }}>Jumat</option>
                                                <option value="6" {{ old('hari_minggu', $master->hari_minggu) == '6' ? 'selected' : '' }}>Sabtu</option>
                                                <option value="7" {{ old('hari_minggu', $master->hari_minggu) == '7' ? 'selected' : '' }}>Minggu</option>
                                            </select>
                                            <small class="form-hint">Checklist akan muncul setiap hari ini dalam minggu</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jadwal Bulanan -->
                            <div id="jadwal_bulanan" style="display: none;">
                                <div class="card bg-light mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">📆 Jadwal Bulanan (Tanggal dalam Bulan)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Target</label>
                                            <input type="number" name="tanggal_bulan" class="form-control" min="1" max="31" 
                                                placeholder="1-31" value="{{ old('tanggal_bulan', $master->tanggal_bulan) }}">
                                            <small class="form-hint">Checklist akan muncul setiap tanggal ini setiap bulan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jadwal Tahunan -->
                            <div id="jadwal_tahunan" style="display: none;">
                                <div class="card bg-light mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">📅 Jadwal Tahunan (Tanggal Spesifik)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Target</label>
                                            <input type="date" name="tanggal_target" class="form-control" value="{{ old('tanggal_target', $master->tanggal_target ? date('Y-m-d', strtotime($master->tanggal_target)) : '') }}">
                                            <small class="form-hint">Checklist akan muncul setiap tahun di tanggal ini</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor Urutan</label>
                                <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" 
                                    placeholder="0" value="{{ old('urutan', $master->urutan) }}" min="0">
                                @error('urutan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">Urutan tampilan checklist (semakin kecil, semakin atas)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div>
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                            {{ old('is_active', $master->is_active) ? 'checked' : '' }}>
                                        <span class="form-check-label">Checklist Aktif</span>
                                    </label>
                                </div>
                                <small class="form-hint">Nonaktifkan jika checklist tidak ingin ditampilkan</small>
                            </div>

                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="alert-title">Perhatian!</h4>
                                        <div class="text-muted">
                                            History eksekusi checklist tetap tersimpan meskipun checklist diedit atau dinonaktifkan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><circle cx="12" cy="14" r="2" /><polyline points="14 4 14 8 8 8 8 4" /></svg>
                                Update Checklist
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeSelect = document.getElementById('tipe_periode');
    const jadwalHarian = document.getElementById('jadwal_harian');
    const jadwalMingguan = document.getElementById('jadwal_mingguan');
    const jadwalBulanan = document.getElementById('jadwal_bulanan');
    const jadwalTahunan = document.getElementById('jadwal_tahunan');
    
    function toggleJadwal() {
        const tipe = tipeSelect.value;
        
        // Hide all
        jadwalHarian.style.display = 'none';
        jadwalMingguan.style.display = 'none';
        jadwalBulanan.style.display = 'none';
        jadwalTahunan.style.display = 'none';
        
        // Show relevant
        if (tipe === 'harian') {
            jadwalHarian.style.display = 'block';
        } else if (tipe === 'mingguan') {
            jadwalMingguan.style.display = 'block';
        } else if (tipe === 'bulanan') {
            jadwalBulanan.style.display = 'block';
        } else if (tipe === 'tahunan') {
            jadwalTahunan.style.display = 'block';
        }
    }
    
    tipeSelect.addEventListener('change', toggleJadwal);
    toggleJadwal(); // Initial load
});
</script>
@endsection
