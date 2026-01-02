@extends('layouts.app')
@section('titlepage', 'Edit Event')

@section('content')
@section('navigasi')
    <span><a href="{{ route('qr-attendance.events.index') }}">Event QR Attendance</a> / Edit</span>
@endsection

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Edit Event: {{ $event->event_name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('qr-attendance.events.update', $event->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label>Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" class="form-control" 
                            value="{{ old('event_name', $event->event_name) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Tanggal Event <span class="text-danger">*</span></label>
                                <input type="date" name="event_date" class="form-control" 
                                    value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="event_start_time" class="form-control" 
                                    value="{{ old('event_start_time', date('H:i', strtotime($event->event_start_time))) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="event_end_time" class="form-control" 
                                    value="{{ old('event_end_time', date('H:i', strtotime($event->event_end_time))) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Venue</label>
                        <input type="text" name="venue_name" class="form-control" 
                            value="{{ old('venue_name', $event->venue_name) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label>Latitude <span class="text-danger">*</span></label>
                                <input type="text" name="venue_latitude" id="latitude" class="form-control" 
                                    value="{{ old('venue_latitude', $event->venue_latitude) }}" required step="any">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label>Longitude <span class="text-danger">*</span></label>
                                <input type="text" name="venue_longitude" id="longitude" class="form-control" 
                                    value="{{ old('venue_longitude', $event->venue_longitude) }}" required step="any">
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
                        <input type="number" name="venue_radius_meter" class="form-control" 
                            value="{{ old('venue_radius_meter', $event->venue_radius_meter) }}" 
                            required min="10" max="1000">
                    </div>

                    <div class="form-group mb-3">
                        <label>Cabang</label>
                        <select name="kode_cabang" class="form-control select2">
                            <option value="">Pilih Cabang</option>
                            @foreach($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" 
                                    {{ old('kode_cabang', $event->kode_cabang) == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update Event
                        </button>
                        <a href="{{ route('qr-attendance.events.show', $event->id) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
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
            $(this).html('<i class="ti ti-loader rotating"></i>').prop('disabled', true);
            
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#latitude').val(position.coords.latitude);
                $('#longitude').val(position.coords.longitude);
                $('#btnGetLocation').html('<i class="ti ti-current-location"></i> GPS').prop('disabled', false);
            });
        }
    });
});
</script>
@endpush
