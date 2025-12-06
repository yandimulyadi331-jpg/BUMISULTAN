@extends('layouts.app')
@section('titlepage', 'Service Kendaraan Selesai')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Service Selesai</span>
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
        <!-- Info Service -->
        <div class="alert alert-info">
            <h5 class="alert-heading"><i class="ti ti-info-circle me-2"></i>Informasi Service</h5>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Kode Service:</strong> {{ $service->kode_service }}</p>
                    <p class="mb-1"><strong>Jenis Service:</strong> {{ $service->jenis_service }}</p>
                    <p class="mb-1"><strong>Bengkel:</strong> {{ $service->bengkel }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Waktu Masuk:</strong> {{ \Carbon\Carbon::parse($service->waktu_service)->format('d/m/Y H:i') }}</p>
                    <p class="mb-1"><strong>Estimasi Biaya:</strong> Rp {{ number_format($service->estimasi_biaya, 0, ',', '.') }}</p>
                    <p class="mb-1"><strong>Estimasi Selesai:</strong> {{ $service->estimasi_selesai ? \Carbon\Carbon::parse($service->estimasi_selesai)->format('d/m/Y') : '-' }}</p>
                </div>
            </div>
            @php
                $estimasi = \Carbon\Carbon::parse($service->estimasi_selesai);
                $sekarang = \Carbon\Carbon::now();
                $isLate = $sekarang->gt($estimasi);
            @endphp
            @if($service->estimasi_selesai && $isLate)
                <div class="alert alert-danger mt-2 mb-0">
                    <i class="ti ti-alert-triangle me-2"></i>Service melebihi estimasi waktu selesai!
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title text-white"><i class="ti ti-circle-check me-2"></i>Form Penyelesaian Service</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('service.prosesSelesai', Crypt::encrypt($service->id)) }}" method="POST" id="formSelesai" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_selesai" class="form-control" value="{{ date('H:i') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-gauge" label="KM Setelah Service" name="km_selesai" type="number" />
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-coin" label="Biaya Akhir" name="biaya_akhir" type="number" value="{{ $service->estimasi_biaya }}" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Pekerjaan Yang Telah Dilakukan <span class="text-danger">*</span></label>
                                <textarea name="pekerjaan_selesai" class="form-control" rows="3" placeholder="Daftar pekerjaan yang telah diselesaikan">{{ $service->pekerjaan }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Catatan Mekanik</label>
                                <textarea name="catatan_mekanik" class="form-control" rows="3" placeholder="Catatan atau rekomendasi dari mekanik"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Kondisi Setelah Service <span class="text-danger">*</span></label>
                                <select name="kondisi_kendaraan" class="form-select">
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="Sangat Baik">Sangat Baik</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Cukup Baik">Cukup Baik</option>
                                    <option value="Perlu Perhatian">Perlu Perhatian</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-user" label="PIC / Mekanik" name="pic_selesai" value="{{ $service->pic }}" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Foto Kondisi Setelah Service <span class="text-danger">*</span></label>
                                <input type="file" name="foto_after" class="form-control" id="foto_after" accept="image/*">
                                <small class="text-muted">Upload foto kondisi kendaraan setelah service</small>
                                <div class="mt-2">
                                    <img id="preview_foto_after" style="max-width: 200px; display: none;" class="rounded">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Lokasi GPS Saat Selesai</label>
                                <input type="text" id="lokasi_display" class="form-control" readonly placeholder="Mencari lokasi...">
                                <input type="hidden" name="latitude_selesai" id="latitude_selesai">
                                <input type="hidden" name="longitude_selesai" id="longitude_selesai">
                                <small class="text-muted">GPS akan otomatis terdeteksi</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="ti ti-circle-check me-2"></i>Tandai Service Selesai
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
                @if($service->kendaraan->foto)
                    <img src="{{ asset('storage/kendaraan/' . $service->kendaraan->foto) }}" class="img-fluid rounded mb-3" alt="Foto Kendaraan">
                @endif
                <table class="table table-sm">
                    <tr>
                        <td><strong>Kode</strong></td>
                        <td>{{ $service->kendaraan->kode_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $service->kendaraan->nama_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Polisi</strong></td>
                        <td><span class="badge bg-primary">{{ $service->kendaraan->no_polisi }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis</strong></td>
                        <td>{{ $service->kendaraan->jenis_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td><span class="badge bg-warning">{{ ucfirst($service->kendaraan->status) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        @if($service->foto_before)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Foto Sebelum Service</h5>
            </div>
            <div class="card-body">
                <img src="{{ asset('storage/service/' . $service->foto_before) }}" class="img-fluid rounded" alt="Foto Before">
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Preview foto after
        $('#foto_after').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_foto_after').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview_foto_after').hide();
            }
        });

        // Get GPS Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                
                $('#latitude_selesai').val(latitude);
                $('#longitude_selesai').val(longitude);
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
            });
        }
    });
</script>
@endpush
