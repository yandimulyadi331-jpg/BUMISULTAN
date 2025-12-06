@extends('layouts.app')
@section('titlepage', 'Aktivitas Kembali Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Aktivitas Kembali</span>
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
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-white"><i class="ti ti-arrow-back me-2"></i>Form Aktivitas Kembali</h3>
                    </div>
                    <div class="card-body">
                        <!-- Info Aktivitas Keluar -->
                        <div class="alert alert-info">
                            <h4 class="alert-heading"><i class="ti ti-info-circle me-2"></i>Informasi Aktivitas Keluar</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Pengemudi:</strong> {{ $aktivitas->driver }}</p>
                                    @if($aktivitas->penumpang)
                                    <p class="mb-1"><strong>Penumpang:</strong> {{ $aktivitas->penumpang }}</p>
                                    @endif
                                    <p class="mb-1"><strong>Tujuan:</strong> {{ $aktivitas->tujuan }}</p>
                                    <p class="mb-1"><strong>Waktu Keluar:</strong> {{ \Carbon\Carbon::parse($aktivitas->waktu_keluar)->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>KM Awal:</strong> {{ $aktivitas->km_awal ?? '-' }} km</p>
                                    <p class="mb-1"><strong>BBM Keluar:</strong> {{ $aktivitas->status_bbm_keluar ?? '-' }}</p>
                                    <p class="mb-1"><strong>Durasi:</strong> {{ \Carbon\Carbon::parse($aktivitas->waktu_keluar)->diffForHumans(null, true) }}</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('kendaraan.aktivitas.prosesKembali', Crypt::encrypt($aktivitas->id)) }}" method="POST" id="formAktivitasKembali">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_kembali" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Jam Kembali <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_kembali" class="form-control" value="{{ date('H:i') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-input-with-icon icon="ti ti-gauge" label="KM Akhir" name="km_akhir" type="number" />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status BBM Kembali</label>
                                        <select name="status_bbm_kembali" class="form-select">
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
                                        <label class="form-label">Kondisi Kendaraan</label>
                                        <select name="kondisi_kendaraan" class="form-select">
                                            <option value="Baik">Baik</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan kondisi kendaraan atau catatan lainnya"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Lokasi GPS Saat Ini</label>
                                        <input type="text" id="lokasi_display" class="form-control" readonly placeholder="Mencari lokasi...">
                                        <input type="hidden" name="latitude_kembali" id="latitude_kembali">
                                        <input type="hidden" name="longitude_kembali" id="longitude_kembali">
                                        <small class="text-muted">GPS akan otomatis terdeteksi</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="ti ti-check me-2"></i>Tandai Kembali
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
                        @if($aktivitas->kendaraan->foto)
                            <img src="{{ asset('storage/kendaraan/' . $aktivitas->kendaraan->foto) }}" class="img-fluid rounded mb-3" alt="Foto Kendaraan">
                        @endif
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Kode</strong></td>
                                <td>{{ $aktivitas->kendaraan->kode_kendaraan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>{{ $aktivitas->kendaraan->nama_kendaraan }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Polisi</strong></td>
                                <td><span class="badge bg-primary">{{ $aktivitas->kendaraan->no_polisi }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis</strong></td>
                                <td>{{ $aktivitas->kendaraan->jenis_kendaraan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><span class="badge bg-info">Sedang Keluar</span></td>
                            </tr>
                        </table>
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
                
                $('#latitude_kembali').val(latitude);
                $('#longitude_kembali').val(longitude);
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
        }
    });
</script>
@endpush
