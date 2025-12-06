<style>
    #map {
        height: 300px;
        width: 100%;
    }

    #map_out {
        height: 300px;
        width: 100%;
    }
</style>

@if ($status == 'in')
    <div class="row">
        <div class="col-4 text-center">
            @if (!empty($presensi->foto_in))
                @if (Storage::disk('public')->exists('/uploads/absensi/yayasan/' . $presensi->foto_in))
                    <img src="{{ url('/storage/uploads/absensi/yayasan/' . $presensi->foto_in) }}" class="card-img rounded thumbnail" alt="">
                @else
                    <i class="ti ti-fingerprint text-success" style="font-size: 10rem;"></i>
                @endif
            @else
                <i class="ti ti-fingerprint text-success" style="font-size: 10rem;"></i>
            @endif
        </div>
        <div class="col-8">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td>{{ $presensi->kode_yayasan }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $presensi->nama }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($presensi->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Jam Masuk</th>
                    <td>{{ date('d-m-Y H:i', strtotime($presensi->jam_in)) }}</td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>
                        @php
                            if (!empty($presensi->lokasi_in)) {
                                $lokasi_in = explode(',', $presensi->lokasi_in);
                                $lat = $lokasi_in[0];
                                $lng = $lokasi_in[1];
                            }
                        @endphp
                        <a href="https://maps.google.com/?q={{ $lat ?? '-' }},{{ $lng ?? '-' }}" target="_blank">Lihat Lokasi</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="map"></div>
        </div>
    </div>
@elseif($status == 'out')
    <div class="row">
        <div class="col-4 text-center">
            @if (!empty($presensi->foto_out))
                @if (Storage::disk('public')->exists('/uploads/absensi/yayasan/' . $presensi->foto_out))
                    <img src="{{ url('/storage/uploads/absensi/yayasan/' . $presensi->foto_out) }}" class="card-img rounded thumbnail" alt="">
                @else
                    <i class="ti ti-fingerprint text-danger" style="font-size: 10rem;"></i>
                @endif
            @else
                <i class="ti ti-fingerprint text-danger" style="font-size: 10rem;"></i>
            @endif
        </div>
        <div class="col-8">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td>{{ $presensi->kode_yayasan }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $presensi->nama }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($presensi->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Jam Pulang</th>
                    <td>{{ date('d-m-Y H:i', strtotime($presensi->jam_out)) }}</td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>
                        @php
                            if (!empty($presensi->lokasi_out)) {
                                $lokasi_out = explode(',', $presensi->lokasi_out);
                                $lat = $lokasi_out[0];
                                $lng = $lokasi_out[1];
                            }
                        @endphp
                        <a href="https://maps.google.com/?q={{ $lat ?? '-' }},{{ $lng ?? '-' }}" target="_blank">Lihat Lokasi</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="map_out"></div>
        </div>
    </div>
@endif

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    @if ($status == 'in' && isset($lat) && isset($lng))
        let map = L.map('map').setView([{{ $lat }}, {{ $lng }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([{{ $lat }}, {{ $lng }}]).addTo(map).bindPopup('Lokasi Absen Masuk');

        let kantor_lat = {{ $latitude }};
        let kantor_lng = {{ $longitude }};
        L.circleMarker([kantor_lat, kantor_lng], {
            radius: 50,
            fillColor: "#ff7800",
            color: "#000",
            weight: 2,
            opacity: 0.8,
            fillOpacity: 0.2
        }).addTo(map).bindPopup('Radius Kantor');
    @endif

    @if ($status == 'out' && isset($lat) && isset($lng))
        let map_out = L.map('map_out').setView([{{ $lat }}, {{ $lng }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map_out);
        L.marker([{{ $lat }}, {{ $lng }}]).addTo(map_out).bindPopup('Lokasi Absen Pulang');

        let kantor_lat_out = {{ $latitude }};
        let kantor_lng_out = {{ $longitude }};
        L.circleMarker([kantor_lat_out, kantor_lng_out], {
            radius: 50,
            fillColor: "#ff7800",
            color: "#000",
            weight: 2,
            opacity: 0.8,
            fillOpacity: 0.2
        }).addTo(map_out).bindPopup('Radius Kantor');
    @endif
</script>
