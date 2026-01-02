@extends('layouts.app')
@section('titlepage', 'Buat Event Baru')

@section('content')
@section('navigasi')
    <span><a href="{{ route('qr-attendance.events.index') }}">Event QR Attendance</a> / Buat Baru</span>
@endsection

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Event Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('qr-attendance.events.store') }}" method="POST" id="formEvent">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" class="form-control @error('event_name') is-invalid @enderror" 
                            value="{{ old('event_name') }}" required>
                        @error('event_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Tanggal Event <span class="text-danger">*</span></label>
                                <input type="date" name="event_date" class="form-control @error('event_date') is-invalid @enderror" 
                                    value="{{ old('event_date', date('Y-m-d')) }}" required>
                                @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="event_start_time" class="form-control @error('event_start_time') is-invalid @enderror" 
                                    value="{{ old('event_start_time', '19:00') }}" required>
                                @error('event_start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="event_end_time" class="form-control @error('event_end_time') is-invalid @enderror" 
                                    value="{{ old('event_end_time', '21:00') }}" required>
                                @error('event_end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Venue</label>
                        <input type="text" name="venue_name" class="form-control" value="{{ old('venue_name') }}" 
                            placeholder="Contoh: Masjid Al-Ikhlas">
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label>Latitude <span class="text-danger">*</span></label>
                                <input type="text" name="venue_latitude" id="latitude" class="form-control @error('venue_latitude') is-invalid @enderror" 
                                    value="{{ old('venue_latitude') }}" required step="any" placeholder="-6.208812">
                                @error('venue_latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label>Longitude <span class="text-danger">*</span></label>
                                <input type="text" name="venue_longitude" id="longitude" class="form-control @error('venue_longitude') is-invalid @enderror" 
                                    value="{{ old('venue_longitude') }}" required step="any" placeholder="106.845599">
                                @error('venue_longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label>&nbsp;</label>
                                <button type="button" id="btnGetLocation" class="btn btn-info w-100">
                                    <i class="ti ti-current-location"></i> GPS
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Radius Geofencing (meter) <span class="text-danger">*</span></label>
                        <input type="number" name="venue_radius_meter" class="form-control @error('venue_radius_meter') is-invalid @enderror" 
                            value="{{ old('venue_radius_meter', 100) }}" required min="10" max="1000">
                        <small class="text-muted">Jarak maksimal jamaah dari venue untuk bisa absen (10-1000 meter)</small>
                        @error('venue_radius_meter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Cabang</label>
                        <select name="kode_cabang" class="form-control select2">
                            <option value="">Pilih Cabang</option>
                            @foreach($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ old('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Event
                        </button>
                        <a href="{{ route('qr-attendance.events.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="ti ti-info-circle me-1"></i> Panduan</h6>
            </div>
            <div class="card-body">
                <h6>Cara Mendapatkan GPS:</h6>
                <ol class="small">
                    <li>Klik tombol "GPS" di form</li>
                    <li>Izinkan browser mengakses lokasi</li>
                    <li>Atau gunakan Google Maps:
                        <ul>
                            <li>Buka maps.google.com</li>
                            <li>Klik kanan di lokasi venue</li>
                            <li>Pilih koordinat (angka pertama = Latitude, angka kedua = Longitude)</li>
                        </ul>
                    </li>
                </ol>
                
                <hr>
                
                <h6>Radius Geofencing:</h6>
                <ul class="small">
                    <li><strong>50m:</strong> Sangat ketat (gedung kecil)</li>
                    <li><strong>100m:</strong> Normal (masjid/aula)</li>
                    <li><strong>200m+:</strong> Longgar (area luas)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
$(document).ready(function() {
    // Get current location
    $('#btnGetLocation').click(function() {
        if (navigator.geolocation) {
            $(this).html('<i class="ti ti-loader rotating"></i> Loading...').prop('disabled', true);
            
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#latitude').val(position.coords.latitude);
                $('#longitude').val(position.coords.longitude);
                $('#btnGetLocation').html('<i class="ti ti-current-location"></i> GPS').prop('disabled', false);
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Berhasil Didapat!',
                    text: 'Koordinat GPS telah diisi otomatis',
                    timer: 2000
                });
            }, function(error) {
                $('#btnGetLocation').html('<i class="ti ti-current-location"></i> GPS').prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat mengakses GPS. Mohon izinkan akses lokasi.'
                });
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Browser tidak mendukung GPS'
            });
        }
    });

    // Form validation
    $('#formEvent').submit(function() {
        var startTime = $('input[name="event_start_time"]').val();
        var endTime = $('input[name="event_end_time"]').val();
        
        if (endTime <= startTime) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Jam selesai harus lebih besar dari jam mulai'
            });
            return false;
        }
    });
});
</script>
@endpush
