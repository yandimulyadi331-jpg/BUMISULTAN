@extends('layouts.app')
@section('titlepage', 'Live Tracking - ' . $aktivitas->kendaraan->nama_kendaraan)

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Live Tracking</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
        <button type="button" class="btn btn-primary" id="btnRefresh">
            <i class="ti ti-refresh me-2"></i>Refresh
        </button>
        <button type="button" class="btn btn-success" id="btnCopyLink">
            <i class="ti ti-link me-2"></i>Copy Link Driver
        </button>
    </div>
</div>

<div class="row">
    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="card-title text-white mb-0"><i class="ti ti-car me-2"></i>Informasi Kendaraan</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $aktivitas->kendaraan->nama_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Polisi:</strong></td>
                        <td><span class="badge bg-primary">{{ $aktivitas->kendaraan->no_polisi }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Pengemudi:</strong></td>
                        <td>{{ $aktivitas->nama_pengemudi }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tujuan:</strong></td>
                        <td>{{ $aktivitas->tujuan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Keluar:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($aktivitas->waktu_keluar)->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title text-white mb-0"><i class="ti ti-activity me-2"></i>Status Real-time</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Status:</span>
                        <span class="badge bg-success" id="statusBadge">Bergerak</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Kecepatan:</span>
                        <strong id="speedDisplay">0 km/h</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Jarak Tempuh:</span>
                        <strong id="distanceDisplay">0 km</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Update Terakhir:</span>
                        <strong id="lastUpdateDisplay">-</strong>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Lokasi:</span>
                        <small id="coordinatesDisplay" class="text-muted">-</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-0">
                <div id="map" style="height: 600px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Link Driver Modal -->
<input type="hidden" id="driverLink" value="{{ route('driver.tracking', Crypt::encrypt($aktivitas->id)) }}">

@endsection

@push('myscript')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGbTkDQ_WKv-aT0UUXCMiKlj8njX0Xip0"></script>
<script>
    let map;
    let currentMarker;
    let trailPolyline;
    let trailCoordinates = [];
    let updateInterval;

    function initMap() {
        // Default center (Jakarta)
        const defaultCenter = { lat: -6.2088, lng: 106.8456 };

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: defaultCenter,
            mapTypeId: 'roadmap'
        });

        currentMarker = new google.maps.Marker({
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: '#4285F4',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 3
            },
            title: 'Lokasi Kendaraan'
        });

        trailPolyline = new google.maps.Polyline({
            map: map,
            strokeColor: '#4285F4',
            strokeOpacity: 0.8,
            strokeWeight: 4,
            geodesic: true
        });

        // Load initial data
        fetchLatestGPS();

        // Auto-refresh setiap 30 detik
        updateInterval = setInterval(fetchLatestGPS, 30000);
    }

    function fetchLatestGPS() {
        fetch("{{ route('api.gps.latest', Crypt::encrypt($aktivitas->id)) }}")
            .then(response => response.json())
            .then(data => {
                if (data.latest) {
                    updateMap(data.latest, data.trail, data.total_distance);
                } else {
                    document.getElementById('lastUpdateDisplay').textContent = 'Belum ada data GPS';
                }
            })
            .catch(error => {
                console.error('Error fetching GPS:', error);
            });
    }

    function updateMap(latest, trail, totalDistance) {
        const position = {
            lat: parseFloat(latest.latitude),
            lng: parseFloat(latest.longitude)
        };

        // Update marker position
        currentMarker.setPosition(position);
        map.setCenter(position);

        // Update trail
        trailCoordinates = trail.map(log => ({
            lat: parseFloat(log.latitude),
            lng: parseFloat(log.longitude)
        }));
        trailPolyline.setPath(trailCoordinates);

        // Fit bounds to show entire trail
        if (trailCoordinates.length > 1) {
            const bounds = new google.maps.LatLngBounds();
            trailCoordinates.forEach(coord => bounds.extend(coord));
            map.fitBounds(bounds);
        }

        // Update info panel
        document.getElementById('speedDisplay').textContent = Math.round(latest.speed) + ' km/h';
        document.getElementById('distanceDisplay').textContent = totalDistance + ' km';
        document.getElementById('coordinatesDisplay').textContent = 
            latest.latitude.toFixed(6) + ', ' + latest.longitude.toFixed(6);

        const lastUpdate = new Date(latest.created_at);
        const now = new Date();
        const secondsAgo = Math.floor((now - lastUpdate) / 1000);
        document.getElementById('lastUpdateDisplay').textContent = secondsAgo + ' detik lalu';

        // Update status badge
        const statusBadge = document.getElementById('statusBadge');
        if (latest.status === 'moving') {
            statusBadge.className = 'badge bg-success';
            statusBadge.textContent = 'Bergerak';
        } else {
            statusBadge.className = 'badge bg-warning';
            statusBadge.textContent = 'Berhenti';
        }
    }

    // Manual refresh button
    document.getElementById('btnRefresh').addEventListener('click', function() {
        fetchLatestGPS();
        Swal.fire({
            icon: 'success',
            title: 'Data Diperbarui',
            timer: 1500,
            showConfirmButton: false
        });
    });

    // Copy driver link
    document.getElementById('btnCopyLink').addEventListener('click', function() {
        const link = document.getElementById('driverLink').value;
        navigator.clipboard.writeText(link).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Link Berhasil Disalin!',
                html: 'Share link ini ke driver:<br><small class="text-muted">' + link + '</small>',
                confirmButtonText: 'OK'
            });
        });
    });

    // Initialize map on page load
    window.onload = initMap;
</script>
@endpush
