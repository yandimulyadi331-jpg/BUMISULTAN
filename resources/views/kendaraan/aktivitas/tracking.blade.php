@extends('layouts.app')
@section('titlepage', 'GPS Tracking Kendaraan')

@section('content')
@section('navigasi')
    <span>Tracking Aktivitas Kendaraan</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">GPS Tracking - {{ $aktivitas->kendaraan->nama_kendaraan }}</h5>
            </div>
            <div class="card-body">
                <!-- Info Panel -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title mb-1">Kendaraan</h6>
                                <p class="mb-0"><strong>{{ $aktivitas->kendaraan->nama_kendaraan }}</strong></p>
                                <small>{{ $aktivitas->kendaraan->no_polisi }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title mb-1">Pengemudi</h6>
                                <p class="mb-0"><strong>{{ $aktivitas->driver }}</strong></p>
                                <small>{{ $aktivitas->email_driver ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title mb-1">Tujuan</h6>
                                <p class="mb-0">{{ $aktivitas->tujuan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title mb-1">Status</h6>
                                <p class="mb-0">
                                    @if($aktivitas->status == 'keluar')
                                        <span class="badge bg-danger">Sedang Keluar</span>
                                    @else
                                        <span class="badge bg-success">Sudah Kembali</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Control Buttons -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" id="btn-refresh">
                                <i class="ti ti-refresh me-2"></i>Refresh
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn-reset-view">
                                <i class="ti ti-focus me-2"></i>Reset View
                            </button>
                            <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary ms-auto">
                                <i class="ti ti-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="row">
                    <div class="col-12">
                        <div id="map" style="height: 600px; border-radius: 8px; border: 1px solid #ddd;"></div>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Info:</strong> Peta menampilkan lokasi keluar dan kembali kendaraan berdasarkan koordinat GPS.
                            <ul class="mb-0 mt-2">
                                <li><strong>Marker Hijau</strong> - Lokasi Keluar</li>
                                <li><strong>Marker Merah</strong> - Lokasi Kembali</li>
                                <li><strong>Klik marker</strong> untuk melihat detail lengkap</li>
                                <li><strong>Klik area kosong di peta</strong> untuk melihat koordinat lokasi</li>
                                <li><strong>Tombol Copy</strong> untuk menyalin koordinat ke clipboard</li>
                                <li><strong>Tombol Add Marker</strong> untuk menambah custom marker</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Detail Cards -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="ti ti-arrow-right me-2"></i>Lokasi Keluar</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($aktivitas->waktu_keluar)->format('d/m/Y H:i') }}</p>
                                <p class="mb-2"><strong>KM Awal:</strong> {{ $aktivitas->km_awal ?? '-' }}</p>
                                <p class="mb-2"><strong>BBM:</strong> {{ $aktivitas->status_bbm_keluar ?? '-' }}</p>
                                <p class="mb-2"><strong>Koordinat:</strong> 
                                    @if($aktivitas->latitude_keluar && $aktivitas->longitude_keluar)
                                        <a href="https://www.google.com/maps?q={{ $aktivitas->latitude_keluar }},{{ $aktivitas->longitude_keluar }}" target="_blank">
                                            {{ number_format($aktivitas->latitude_keluar, 6) }}, {{ number_format($aktivitas->longitude_keluar, 6) }}
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada data GPS</span>
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Keterangan:</strong> {{ $aktivitas->keterangan_keluar ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0"><i class="ti ti-arrow-left me-2"></i>Lokasi Kembali</h6>
                            </div>
                            <div class="card-body">
                                @if($aktivitas->status == 'kembali')
                                    <p class="mb-2"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($aktivitas->waktu_kembali)->format('d/m/Y H:i') }}</p>
                                    <p class="mb-2"><strong>KM Akhir:</strong> {{ $aktivitas->km_akhir ?? '-' }}</p>
                                    <p class="mb-2"><strong>BBM:</strong> {{ $aktivitas->status_bbm_kembali ?? '-' }}</p>
                                    <p class="mb-2"><strong>Kondisi:</strong> {{ $aktivitas->kondisi_kendaraan ?? '-' }}</p>
                                    <p class="mb-2"><strong>Koordinat:</strong> 
                                        @if($aktivitas->latitude_kembali && $aktivitas->longitude_kembali)
                                            <a href="https://www.google.com/maps?q={{ $aktivitas->latitude_kembali }},{{ $aktivitas->longitude_kembali }}" target="_blank">
                                                {{ number_format($aktivitas->latitude_kembali, 6) }}, {{ number_format($aktivitas->longitude_kembali, 6) }}
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada data GPS</span>
                                        @endif
                                    </p>
                                    <p class="mb-0"><strong>Keterangan:</strong> {{ $aktivitas->keterangan_kembali ?? '-' }}</p>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="ti ti-alert-circle" style="font-size: 48px;"></i>
                                        <p class="mt-2">Kendaraan belum kembali</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<style>
    .custom-marker {
        background: transparent !important;
        border: none !important;
    }

    .custom-marker div {
        pointer-events: none;
    }

    .leaflet-marker-icon {
        z-index: 1000 !important;
    }

    .marker-label {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }
</style>
<script>
    $(document).ready(function() {
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
                    text: 'Tidak ada data GPS untuk aktivitas ini.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            var bounds = L.latLngBounds();
            var polylinePoints = [];

            data.forEach(function(markerData) {
                if (markerData.latitude && markerData.longitude) {
                    var markerColor = markerData.type === 'keluar' ? 'green' : 'red';
                    var markerLabel = markerData.type === 'keluar' ? 'Keluar' : 'Kembali';
                    var iconHtml = markerData.type === 'keluar' ? 'ti-arrow-right' : 'ti-arrow-left';

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
                                    ${markerData.keterangan ? `<p style="margin: 0 0 5px 0; font-size: 14px;"><strong>Keterangan:</strong> ${markerData.keterangan}</p>` : ''}
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
                    color: '#007bff',
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
                url: '{{ route('kendaraan.aktivitas.tracking.data', Crypt::encrypt($aktivitas->id)) }}',
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
