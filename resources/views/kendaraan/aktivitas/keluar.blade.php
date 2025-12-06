@extends('layouts.app')
@section('titlepage', 'Aktivitas Keluar Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Aktivitas Keluar</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-white"><i class="ti ti-arrow-right me-2"></i>Form Aktivitas Keluar</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kendaraan.aktivitas.prosesKeluar', $kendaraan_id) }}" method="POST" id="formAktivitasKeluar">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-with-icon icon="ti ti-user" label="Nama Pengemudi" name="nama_pengemudi" />
                                </div>
                                <div class="col-md-6">
                                    <x-input-with-icon icon="ti ti-phone" label="No. HP Pengemudi" name="no_hp_pengemudi" type="text" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Penumpang</label>
                                        <textarea name="penumpang" class="form-control" rows="2" placeholder="Masukkan nama-nama penumpang (opsional)"></textarea>
                                        <small class="text-muted">Pisahkan dengan koma jika lebih dari satu</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                                        <textarea name="tujuan" class="form-control" rows="3" placeholder="Masukkan tujuan penggunaan kendaraan"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_keluar" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Jam Keluar <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_keluar" class="form-control" value="{{ date('H:i') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-with-icon icon="ti ti-gauge" label="KM Awal" name="km_awal" type="number" />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status BBM</label>
                                        <select name="status_bbm_keluar" class="form-select">
                                            <option value="Penuh">Penuh</option>
                                            <option value="3/4">3/4</option>
                                            <option value="1/2">1/2</option>
                                            <option value="1/4">1/4</option>
                                            <option value="Kosong">Kosong</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Lokasi GPS Saat Ini</label>
                                        <input type="text" id="lokasi_display" class="form-control" readonly placeholder="Mencari lokasi...">
                                        <input type="hidden" name="latitude_keluar" id="latitude_keluar">
                                        <input type="hidden" name="longitude_keluar" id="longitude_keluar">
                                        <small class="text-muted">GPS akan otomatis terdeteksi</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-arrow-right me-2"></i>Proses Keluar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title text-white"><i class="ti ti-car me-2"></i>Informasi Kendaraan</h3>
                    </div>
                    <div class="card-body">
                        @if($kendaraan->foto)
                            <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="img-fluid rounded mb-3" alt="Foto Kendaraan">
                        @endif
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Kode</strong></td>
                                <td>{{ $kendaraan->kode_kendaraan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>{{ $kendaraan->nama_kendaraan }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Polisi</strong></td>
                                <td><span class="badge bg-primary">{{ $kendaraan->no_polisi }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis</strong></td>
                                <td>{{ $kendaraan->jenis_kendaraan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Merk/Model</strong></td>
                                <td>{{ $kendaraan->merk ?? '-' }} / {{ $kendaraan->model ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><span class="badge bg-success">{{ ucfirst($kendaraan->status) }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Get GPS Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                
                $('#latitude_keluar').val(latitude);
                $('#longitude_keluar').val(longitude);
                $('#lokasi_display').val(latitude + ', ' + longitude);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Terdeteksi!',
                    text: 'GPS berhasil mendapatkan koordinat',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, function(error) {
                $('#lokasi_display').val('GPS tidak dapat diakses');
                Swal.fire({
                    icon: 'warning',
                    title: 'GPS Tidak Terdeteksi',
                    text: 'Pastikan GPS aktif atau izinkan akses lokasi',
                    confirmButtonText: 'OK'
                });
            });
        } else {
            $('#lokasi_display').val('Browser tidak support GPS');
            Swal.fire({
                icon: 'error',
                title: 'GPS Tidak Didukung',
                text: 'Browser Anda tidak mendukung geolocation',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
@endpush
