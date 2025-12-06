@extends('layouts.app')
@section('titlepage', 'Edit Service Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Edit Service</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('service.index', Crypt::encrypt($service->kendaraan_id)) }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h3 class="card-title text-white"><i class="ti ti-edit me-2"></i>Edit Service Kendaraan - {{ $service->kode_service }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('service.update', Crypt::encrypt($service->id)) }}" method="POST" id="formService" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Service <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_service" class="form-control" value="{{ \Carbon\Carbon::parse($service->waktu_service)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jam Service <span class="text-danger">*</span></label>
                                <input type="time" name="jam_service" class="form-control" value="{{ \Carbon\Carbon::parse($service->waktu_service)->format('H:i') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Service <span class="text-danger">*</span></label>
                                <select name="jenis_service" class="form-select">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Service Rutin" {{ $service->jenis_service == 'Service Rutin' ? 'selected' : '' }}>Service Rutin</option>
                                    <option value="Perbaikan" {{ $service->jenis_service == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                    <option value="Ganti Oli" {{ $service->jenis_service == 'Ganti Oli' ? 'selected' : '' }}>Ganti Oli</option>
                                    <option value="Ganti Ban" {{ $service->jenis_service == 'Ganti Ban' ? 'selected' : '' }}>Ganti Ban</option>
                                    <option value="Tune Up" {{ $service->jenis_service == 'Tune Up' ? 'selected' : '' }}>Tune Up</option>
                                    <option value="Body Repair" {{ $service->jenis_service == 'Body Repair' ? 'selected' : '' }}>Body Repair</option>
                                    <option value="Lainnya" {{ $service->jenis_service == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-building" label="Nama Bengkel" name="bengkel" value="{{ $service->bengkel }}" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-gauge" label="KM Saat Service" name="km_service" type="number" value="{{ $service->km_service }}" />
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-coin" label="Estimasi Biaya" name="estimasi_biaya" type="number" value="{{ $service->estimasi_biaya }}" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Deskripsi Kerusakan / Keluhan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_kerusakan" class="form-control" rows="3" placeholder="Jelaskan kerusakan atau keluhan kendaraan">{{ $service->deskripsi_kerusakan }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Pekerjaan Yang Akan Dilakukan</label>
                                <textarea name="pekerjaan" class="form-control" rows="3" placeholder="Daftar pekerjaan yang akan dilakukan">{{ $service->pekerjaan }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Estimasi Selesai</label>
                                <input type="date" name="estimasi_selesai" class="form-control" value="{{ $service->estimasi_selesai }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-user" label="PIC / Mekanik" name="pic" value="{{ $service->pic }}" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Foto Kondisi Sebelum Service</label>
                                @if($service->foto_before)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/service/' . $service->foto_before) }}" class="img-thumbnail" style="max-width: 200px;">
                                        <p class="text-muted small mt-1">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="foto_before" class="form-control" id="foto_before" accept="image/*">
                                <small class="text-muted">Upload foto baru untuk mengganti foto yang ada</small>
                                <div class="mt-2">
                                    <img id="preview_foto_before" style="max-width: 200px; display: none;" class="rounded">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning w-100 text-white">
                                <i class="ti ti-device-floppy me-2"></i>Update Service
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
                        <td><span class="badge bg-danger">Service</span></td>
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
    });
</script>
@endpush
