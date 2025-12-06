@extends('layouts.app')@extends('layouts.app')

@section('titlepage', 'GPS Tracking Peminjaman Kendaraan')@section('titlepage', 'GPS Tracking Peminjaman')



@section('content')@section('navigasi')

@section('navigasi')    <span>Fasilitas & Asset / Manajemen Kendaraan / GPS Tracking Peminjaman</span>

    <span>Tracking Peminjaman Kendaraan</span>@endsection

@endsection

@section('content')

<div class="row"><style>

    <div class="col-12">    #map {

        <div class="card">        height: 600px;

            <div class="card-header">        width: 100%;

                <h5 class="card-title mb-0">GPS Tracking - {{ $peminjaman->kendaraan->nama_kendaraan }}</h5>        border-radius: 10px;

            </div>    }

            <div class="card-body">    .info-panel {

                <!-- Info Panel -->        background: white;

                <div class="row mb-4">        padding: 15px;

                    <div class="col-md-3">        border-radius: 10px;

                        <div class="card bg-primary text-white">        box-shadow: 0 2px 10px rgba(0,0,0,0.1);

                            <div class="card-body">    }

                                <h6 class="card-title mb-1">Kendaraan</h6></style>

                                <p class="mb-0"><strong>{{ $peminjaman->kendaraan->nama_kendaraan }}</strong></p>

                                <small>{{ $peminjaman->kendaraan->no_polisi }}</small><div class="row mb-3">

                            </div>    <div class="col-12 d-flex justify-content-end">

                        </div>        <button class="btn btn-primary me-2" id="btnRefresh">

                    </div>            <i class="ti ti-refresh me-2"></i>Refresh Lokasi

                    <div class="col-md-3">        </button>

                        <div class="card bg-info text-white">        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">

                            <div class="card-body">            <i class="ti ti-arrow-left me-2"></i>Kembali

                                <h6 class="card-title mb-1">Peminjam</h6>        </a>

                                <p class="mb-0"><strong>{{ $peminjaman->nama_peminjam }}</strong></p>    </div>

                                <small>{{ $peminjaman->no_hp_peminjam ?? '-' }}</small></div>

                            </div>

                        </div><div class="row mb-3">

                    </div>    <div class="col-md-12">

                    <div class="col-md-3">        <div class="info-panel">

                        <div class="card bg-success text-white">            <div class="row">

                            <div class="card-body">                <div class="col-md-3">

                                <h6 class="card-title mb-1">Keperluan</h6>                    <h6 class="text-muted mb-1">Kendaraan</h6>

                                <p class="mb-0">{{ $peminjaman->keperluan }}</p>                    <p class="mb-0"><strong>{{ $peminjaman->kendaraan->nama_kendaraan }}</strong><br>

                            </div>                    <span class="badge bg-primary">{{ $peminjaman->kendaraan->no_polisi }}</span></p>

                        </div>                </div>

                    </div>                <div class="col-md-3">

                    <div class="col-md-3">                    <h6 class="text-muted mb-1">Peminjam</h6>

                        <div class="card bg-warning text-white">                    <p class="mb-0"><strong>{{ $peminjaman->nama_peminjam }}</strong><br>

                            <div class="card-body">                    <small>{{ $peminjaman->no_hp_peminjam ?? '-' }}</small></p>

                                <h6 class="card-title mb-1">Status</h6>                </div>

                                <p class="mb-0">                <div class="col-md-3">

                                    @if($peminjaman->status == 'pinjam')                    <h6 class="text-muted mb-1">Keperluan</h6>

                                        <span class="badge bg-danger">Sedang Dipinjam</span>                    <p class="mb-0">{{ $peminjaman->keperluan }}</p>

                                    @else                </div>

                                        <span class="badge bg-success">Sudah Kembali</span>                <div class="col-md-3">

                                    @endif                    <h6 class="text-muted mb-1">Waktu Pinjam</h6>

                                </p>                    <p class="mb-0">{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d/m/Y H:i') }}<br>

                            </div>                    <small class="text-muted">{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->diffForHumans() }}</small></p>

                        </div>                </div>

                    </div>            </div>

                </div>        </div>

    </div>

                <!-- Control Buttons --></div>

                <div class="row mb-3">

                    <div class="col-12"><div class="row">

                        <div class="d-flex gap-2">    <div class="col-12">

                            <button type="button" class="btn btn-primary" id="btn-refresh">        <div class="card">

                                <i class="ti ti-refresh me-2"></i>Refresh            <div class="card-header">

                            </button>                <h3 class="card-title"><i class="ti ti-map-pin me-2"></i>Peta Tracking Realtime</h3>

                            <button type="button" class="btn btn-secondary" id="btn-reset-view">            </div>

                                <i class="ti ti-focus me-2"></i>Reset View            <div class="card-body">

                            </button>                <div id="map"></div>

                            <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary ms-auto">                <div class="mt-3">

                                <i class="ti ti-arrow-left me-2"></i>Kembali                    <div class="row">

                            </a>                        <div class="col-md-6">

                        </div>                            <p class="mb-1"><strong>Lokasi Pinjam:</strong> 

                    </div>                                @if($peminjaman->latitude_pinjam && $peminjaman->longitude_pinjam)

                </div>                                    <a href="https://www.google.com/maps?q={{ $peminjaman->latitude_pinjam }},{{ $peminjaman->longitude_pinjam }}" target="_blank">

                                        {{ number_format($peminjaman->latitude_pinjam, 6) }}, {{ number_format($peminjaman->longitude_pinjam, 6) }}

                <!-- Map Container -->                                    </a>

                <div class="row">                                @else

                    <div class="col-12">                                    <span class="text-muted">Tidak ada data GPS</span>

                        <div id="map" style="height: 600px; border-radius: 8px; border: 1px solid #ddd;"></div>                                @endif

                    </div>                            </p>

                </div>                        </div>

                        <div class="col-md-6">

                <!-- Info Panel -->                            <p class="mb-1"><strong>Status:</strong> 

                <div class="row mt-3">                                @if($peminjaman->status == 'dipinjam')

                    <div class="col-12">                                    <span class="badge bg-primary">Sedang Dipinjam</span>

                        <div class="alert alert-info">                                @else

                            <i class="ti ti-info-circle me-2"></i>                                    <span class="badge bg-success">Sudah Dikembalikan</span>

                            <strong>Info:</strong> Peta menampilkan lokasi pinjam dan kembali kendaraan berdasarkan koordinat GPS.                                @endif

                            <ul class="mb-0 mt-2">                            </p>

                                <li><strong>Marker Biru</strong> - Lokasi Pinjam</li>                        </div>

                                <li><strong>Marker Hijau</strong> - Lokasi Kembali</li>                    </div>

                                <li><strong>Klik marker</strong> untuk melihat detail lengkap</li>                </div>

                                <li><strong>Klik area kosong di peta</strong> untuk melihat koordinat lokasi</li>            </div>

                                <li><strong>Tombol Copy</strong> untuk menyalin koordinat ke clipboard</li>        </div>

                                <li><strong>Tombol Add Marker</strong> untuk menambah custom marker</li>    </div>

                            </ul></div>

                        </div>

                    </div>@endsection

                </div>

@push('myscript')

                <!-- Detail Cards --><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGbTkDQ_WKv-aT0UUXCMiKlj8njX0Xip0&callback=initMap" async defer></script>

                <div class="row mt-3"><script>

                    <div class="col-md-6">    let map;

                        <div class="card border-primary">    let marker;

                            <div class="card-header bg-primary text-white">    let startMarker;

                                <h6 class="mb-0"><i class="ti ti-arrow-right me-2"></i>Lokasi Pinjam</h6>

                            </div>    function initMap() {

                            <div class="card-body">        const startLocation = {

                                <p class="mb-2"><strong>Waktu Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d/m/Y H:i') }}</p>            lat: parseFloat('{{ $peminjaman->latitude_pinjam ?? -6.200000 }}'),

                                <p class="mb-2"><strong>Estimasi Kembali:</strong> {{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d/m/Y H:i') }}</p>            lng: parseFloat('{{ $peminjaman->longitude_pinjam ?? 106.816666 }}')

                                <p class="mb-2"><strong>KM Awal:</strong> {{ $peminjaman->km_awal ?? '-' }}</p>        };

                                <p class="mb-2"><strong>BBM:</strong> {{ $peminjaman->status_bbm_pinjam ?? '-' }}</p>

                                <p class="mb-2"><strong>Koordinat:</strong>         map = new google.maps.Map(document.getElementById('map'), {

                                    @if($peminjaman->latitude_pinjam && $peminjaman->longitude_pinjam)            zoom: 15,

                                        <a href="https://www.google.com/maps?q={{ $peminjaman->latitude_pinjam }},{{ $peminjaman->longitude_pinjam }}" target="_blank">            center: startLocation,

                                            {{ number_format($peminjaman->latitude_pinjam, 6) }}, {{ number_format($peminjaman->longitude_pinjam, 6) }}            mapTypeId: 'roadmap'

                                        </a>        });

                                    @else

                                        <span class="text-muted">Tidak ada data GPS</span>        startMarker = new google.maps.Marker({

                                    @endif            position: startLocation,

                                </p>            map: map,

                                <p class="mb-0"><strong>Keperluan:</strong> {{ $peminjaman->keperluan }}</p>            title: 'Lokasi Pinjam',

                            </div>            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',

                        </div>            label: 'Start'

                    </div>        });

                    <div class="col-md-6">

                        <div class="card border-success">        const startInfoWindow = new google.maps.InfoWindow({

                            <div class="card-header bg-success text-white">            content: '<div style="padding:10px;"><strong>Lokasi Pinjam</strong><br>' +

                                <h6 class="mb-0"><i class="ti ti-arrow-left me-2"></i>Lokasi Kembali</h6>                    'Waktu: {{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format("d/m/Y H:i") }}<br>' +

                            </div>                    'Peminjam: {{ $peminjaman->nama_peminjam }}<br>' +

                            <div class="card-body">                    'Koordinat: ' + startLocation.lat.toFixed(6) + ', ' + startLocation.lng.toFixed(6) + '</div>'

                                @if($peminjaman->status == 'kembali')        });

                                    <p class="mb-2"><strong>Waktu Kembali:</strong> {{ \Carbon\Carbon::parse($peminjaman->waktu_kembali)->format('d/m/Y H:i') }}</p>

                                    <p class="mb-2"><strong>KM Akhir:</strong> {{ $peminjaman->km_akhir ?? '-' }}</p>        startMarker.addListener('click', function() {

                                    <p class="mb-2"><strong>BBM:</strong> {{ $peminjaman->status_bbm_kembali ?? '-' }}</p>            startInfoWindow.open(map, startMarker);

                                    <p class="mb-2"><strong>Kondisi:</strong> {{ $peminjaman->kondisi_kendaraan ?? '-' }}</p>        });

                                    <p class="mb-2"><strong>Koordinat:</strong> 

                                        @if($peminjaman->latitude_kembali && $peminjaman->longitude_kembali)        @if($peminjaman->latitude_kembali && $peminjaman->longitude_kembali)

                                            <a href="https://www.google.com/maps?q={{ $peminjaman->latitude_kembali }},{{ $peminjaman->longitude_kembali }}" target="_blank">        const endLocation = {

                                                {{ number_format($peminjaman->latitude_kembali, 6) }}, {{ number_format($peminjaman->longitude_kembali, 6) }}            lat: parseFloat('{{ $peminjaman->latitude_kembali }}'),

                                            </a>            lng: parseFloat('{{ $peminjaman->longitude_kembali }}')

                                        @else        };

                                            <span class="text-muted">Tidak ada data GPS</span>

                                        @endif        marker = new google.maps.Marker({

                                    </p>            position: endLocation,

                                @else            map: map,

                                    <div class="text-center text-muted">            title: 'Lokasi Kembali',

                                        <i class="ti ti-alert-circle" style="font-size: 48px;"></i>            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',

                                        <p class="mt-2">Kendaraan belum dikembalikan</p>            label: 'End'

                                    </div>        });

                                @endif

                            </div>        const endInfoWindow = new google.maps.InfoWindow({

                        </div>            content: '<div style="padding:10px;"><strong>Lokasi Kembali</strong><br>' +

                    </div>                    'Waktu: {{ (is_object($peminjaman) && isset($peminjaman->waktu_kembali) && $peminjaman->waktu_kembali) ? \Carbon\Carbon::parse($peminjaman->waktu_kembali)->format("d/m/Y H:i") : "-" }}<br>' +

                </div>                    'Koordinat: ' + endLocation.lat.toFixed(6) + ', ' + endLocation.lng.toFixed(6) + '</div>'

            </div>        });

        </div>

    </div>        marker.addListener('click', function() {

</div>            endInfoWindow.open(map, marker);

@endsection        });



@push('myscript')        const routePath = new google.maps.Polyline({

<style>            path: [startLocation, endLocation],

    .custom-marker {            geodesic: true,

        background: transparent !important;            strokeColor: '#FF0000',

        border: none !important;            strokeOpacity: 1.0,

    }            strokeWeight: 3

        });

    .custom-marker div {        routePath.setMap(map);

        pointer-events: none;

    }        const bounds = new google.maps.LatLngBounds();

        bounds.extend(startLocation);

    .leaflet-marker-icon {        bounds.extend(endLocation);

        z-index: 1000 !important;        map.fitBounds(bounds);

    }        @endif

    }

    .marker-label {

        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;    $('#btnRefresh').click(function() {

        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);        location.reload();

    }    });

</style></script>

<script>@endpush

    $(document).ready(function() {@endsection

        // Initialize map
        var map = L.map('map').setView([-6.2088, 106.8456], 10); // Default Jakarta

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add click event to map to show coordinates
        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            L.popup()
                .setLatLng(e.latlng)
                .setContent(`
                    <div style="text-align: center; min-width: 200px;">
                        <h6 style="margin: 0 0 10px 0; color: #007bff;">
                            <i class="ti ti-map-pin me-2"></i>Koordinat Lokasi
                        </h6>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Latitude:</strong> ${lat}
                            </p>
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Longitude:</strong> ${lng}
                            </p>
                        </div>
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <button class="btn btn-sm btn-primary" onclick="copyToClipboard('${lat}, ${lng}')" style="font-size: 11px; padding: 4px 8px;">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                            <button class="btn btn-sm btn-success" onclick="addCustomMarker(${lat}, ${lng})" style="font-size: 11px; padding: 4px 8px;">
                                <i class="ti ti-plus me-1"></i>Add Marker
                            </button>
                        </div>
                    </div>
                `)
                .openOn(map);
        });

        var markers = [];
        var customMarkers = [];
        var polyline = null;
        var markersData = @json($markers);

        // Function to add markers to map
        function addMarkersToMap(data) {
            // Clear existing markers
            markers.forEach(function(marker) {
                map.removeLayer(marker);
            });
            markers = [];

            // Clear existing polyline
            if (polyline) {
                map.removeLayer(polyline);
                polyline = null;
            }

            if (data.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ada Data',
                    text: 'Tidak ada data GPS untuk peminjaman ini.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            var bounds = L.latLngBounds();
            var polylinePoints = [];

            data.forEach(function(markerData) {
                if (markerData.latitude && markerData.longitude) {
                    var markerColor = markerData.type === 'pinjam' ? 'blue' : 'green';
                    var markerLabel = markerData.type === 'pinjam' ? 'Pinjam' : 'Kembali';
                    var iconHtml = markerData.type === 'pinjam' ? 'ti-arrow-right' : 'ti-arrow-left';

                    // Create custom icon with vehicle icon
                    var customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `
                            <div style="position: relative; display: inline-block;">
                                <!-- Marker Circle -->
                                <div style="background-color: ${markerColor}; width: 50px; height: 50px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                                    <i class="ti ti-car" style="color: white; font-size: 24px;"></i>
                                </div>
                                <!-- Status Indicator -->
                                <div style="position: absolute; bottom: -2px; right: -2px; width: 18px; height: 18px; background-color: ${markerColor}; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="${iconHtml}" style="color: white; font-size: 10px;"></i>
                                </div>
                                <!-- Label -->
                                <div class="marker-label" style="position: absolute; top: -40px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.8); color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.3); z-index: 1000;">
                                    ${markerLabel}
                                    <div style="position: absolute; top: 100%; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid rgba(0,0,0,0.8);"></div>
                                </div>
                            </div>
                        `,
                        iconSize: [50, 50],
                        iconAnchor: [25, 50]
                    });

                    var marker = L.marker([markerData.latitude, markerData.longitude], {
                            icon: customIcon
                        })
                        .addTo(map)
                        .bindPopup(`
                            <div style="min-width: 250px;">
                                <h6 style="margin: 0 0 10px 0; color: ${markerColor};">
                                    <i class="ti ti-map-pin me-2"></i>${markerLabel}
                                </h6>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                                    ${markerData.peminjam ? `<p style="margin: 0 0 5px 0; font-size: 14px;"><strong>Peminjam:</strong> ${markerData.peminjam}</p>` : ''}
                                    <p style="margin: 0 0 5px 0; font-size: 14px;">
                                        <strong>Waktu:</strong> ${markerData.waktu ? new Date(markerData.waktu).toLocaleString('id-ID') : '-'}
                                    </p>
                                    <p style="margin: 0 0 5px 0; font-size: 14px;">
                                        <strong>KM:</strong> ${markerData.km ?? '-'}
                                    </p>
                                    <p style="margin: 0 0 5px 0; font-size: 14px;">
                                        <strong>BBM:</strong> ${markerData.bbm ?? '-'}
                                    </p>
                                    ${markerData.kondisi ? `<p style="margin: 0 0 5px 0; font-size: 14px;"><strong>Kondisi:</strong> ${markerData.kondisi}</p>` : ''}
                                    ${markerData.keperluan ? `<p style="margin: 0 0 5px 0; font-size: 14px;"><strong>Keperluan:</strong> ${markerData.keperluan}</p>` : ''}
                                    <p style="margin: 0; font-size: 14px;">
                                        <strong>Koordinat:</strong> ${markerData.latitude.toFixed(6)}, ${markerData.longitude.toFixed(6)}
                                    </p>
                                </div>
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <button class="btn btn-sm btn-primary" onclick="copyToClipboard('${markerData.latitude}, ${markerData.longitude}')" style="font-size: 11px; padding: 4px 8px;">
                                        <i class="ti ti-copy me-1"></i>Copy
                                    </button>
                                    <a href="https://www.google.com/maps?q=${markerData.latitude},${markerData.longitude}" target="_blank" class="btn btn-sm btn-info" style="font-size: 11px; padding: 4px 8px;">
                                        <i class="ti ti-external-link me-1"></i>Google Maps
                                    </a>
                                </div>
                            </div>
                        `);

                    markers.push(marker);
                    bounds.extend([markerData.latitude, markerData.longitude]);
                    polylinePoints.push([markerData.latitude, markerData.longitude]);
                }
            });

            // Draw polyline if there are multiple points
            if (polylinePoints.length > 1) {
                polyline = L.polyline(polylinePoints, {
                    color: '#28a745',
                    weight: 4,
                    opacity: 0.7,
                    dashArray: '10, 10'
                }).addTo(map);
            }

            // Fit map to show all markers
            if (markers.length > 0) {
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            }
        }

        // Function to copy coordinates to clipboard
        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Koordinat berhasil disalin: ' + text,
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(function(err) {
                var textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Koordinat berhasil disalin: ' + text,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        };

        // Function to add custom marker
        window.addCustomMarker = function(lat, lng) {
            var customIcon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div style="position: relative; display: inline-block;">
                        <div style="background-color: #ff6b35; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-map-pin" style="color: white; font-size: 12px;"></i>
                        </div>
                    </div>
                `,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            var marker = L.marker([lat, lng], {
                    icon: customIcon
                })
                .addTo(map)
                .bindPopup(`
                    <div style="text-align: center; min-width: 200px;">
                        <h6 style="margin: 0 0 10px 0; color: #ff6b35;">
                            <i class="ti ti-map-pin me-2"></i>Custom Marker
                        </h6>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Latitude:</strong> ${lat}
                            </p>
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Longitude:</strong> ${lng}
                            </p>
                        </div>
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <button class="btn btn-sm btn-danger" onclick="removeCustomMarker(${customMarkers.length})" style="font-size: 11px; padding: 4px 8px;">
                                <i class="ti ti-trash me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                `);

            customMarkers.push(marker);

            Swal.fire({
                icon: 'success',
                title: 'Marker Ditambahkan!',
                text: 'Custom marker berhasil ditambahkan',
                timer: 2000,
                showConfirmButton: false
            });
        };

        // Function to remove custom marker
        window.removeCustomMarker = function(index) {
            if (customMarkers[index]) {
                map.removeLayer(customMarkers[index]);
                customMarkers.splice(index, 1);

                Swal.fire({
                    icon: 'success',
                    title: 'Marker Dihapus!',
                    text: 'Custom marker berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        };

        // Initial load markers
        addMarkersToMap(markersData);

        // Refresh button click
        $('#btn-refresh').click(function() {
            Swal.fire({
                title: 'Memuat Data...',
                text: 'Sedang mengambil data tracking terbaru',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route('kendaraan.peminjaman.tracking.data', Crypt::encrypt($peminjaman->id)) }}',
                type: 'GET',
                success: function(response) {
                    Swal.close();
                    addMarkersToMap(response.markers);
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengambil data tracking',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Reset view button click
        $('#btn-reset-view').click(function() {
            if (markers.length > 0) {
                var bounds = L.latLngBounds();
                markers.forEach(function(marker) {
                    bounds.extend(marker.getLatLng());
                });
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            } else {
                map.setView([-6.2088, 106.8456], 10);
            }
        });
    });
</script>
@endpush
