@extends('layouts.app')
@section('titlepage', 'Form Service Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Service</span>
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
            <div class="card-header bg-warning text-white">
                <h3 class="card-title text-white"><i class="ti ti-tool me-2"></i>Form Service Kendaraan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('service.proses', $kendaraan_id) }}" method="POST" id="formService" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Service <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_service" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jam Service <span class="text-danger">*</span></label>
                                <input type="time" name="jam_service" class="form-control" value="{{ date('H:i') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Service <span class="text-danger">*</span></label>
                                <select name="jenis_service" class="form-select">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Service Rutin">Service Rutin</option>
                                    <option value="Perbaikan">Perbaikan</option>
                                    <option value="Ganti Oli">Ganti Oli</option>
                                    <option value="Ganti Ban">Ganti Ban</option>
                                    <option value="Tune Up">Tune Up</option>
                                    <option value="Body Repair">Body Repair</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-building" label="Nama Bengkel" name="bengkel" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-gauge" label="KM Saat Service" name="km_service" type="number" />
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-coin" label="Estimasi Biaya" name="estimasi_biaya" type="number" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Deskripsi Kerusakan / Keluhan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_kerusakan" class="form-control" rows="3" placeholder="Jelaskan kerusakan atau keluhan kendaraan"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Pekerjaan Yang Akan Dilakukan</label>
                                <textarea name="pekerjaan" class="form-control" rows="3" placeholder="Daftar pekerjaan yang akan dilakukan"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Estimasi Selesai</label>
                                <input type="date" name="estimasi_selesai" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-user" label="PIC / Mekanik" name="pic" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Foto Kondisi Sebelum Service</label>
                                <input type="file" name="foto_before" class="form-control" id="foto_before" accept="image/*">
                                <small class="text-muted">Upload foto kondisi kendaraan sebelum service</small>
                                <div class="mt-2">
                                    <img id="preview_foto_before" style="max-width: 200px; display: none;" class="rounded">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Lokasi GPS Bengkel</label>
                                <input type="text" id="lokasi_display" class="form-control" readonly placeholder="Mencari lokasi...">
                                <input type="hidden" name="latitude_service" id="latitude_service">
                                <input type="hidden" name="longitude_service" id="longitude_service">
                                <small class="text-muted">GPS akan otomatis terdeteksi</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning w-100 text-white">
                                <i class="ti ti-tool me-2"></i>Proses Service
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
                        <td><strong>Status</strong></td>
                        <td><span class="badge bg-success">{{ ucfirst($kendaraan->status) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Preview foto before
        $('#foto_before').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_foto_before').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview_foto_before').hide();
            }
        });

        // Get GPS Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                
                $('#latitude_service').val(latitude);
                $('#longitude_service').val(longitude);
                $('#lokasi_display').val(latitude + ', ' + longitude);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Terdeteksi!',
                    text: 'GPS berhasil mendapatkan koordinat bengkel',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, function(error) {
                $('#lokasi_display').val('GPS tidak dapat diakses');
            });
        }
    });
</script>
@endpush
