@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --bg-primary: #e8f0f2;
            --bg-secondary: #ffffff;
            --text-primary: #2F5D62;
            --text-secondary: #5a7c7f;
            --shadow-light: #ffffff;
            --shadow-dark: #c5d3d5;
            --border-color: #c5d3d5;
            --icon-color: #2F5D62;
            --gradient-start: #14b8a6;
            --gradient-mid1: #06b6d4;
            --gradient-mid2: #f59e0b;
            --gradient-mid3: #f97316;
            --gradient-end: #ec4899;
        }

        body.dark-mode {
            --bg-primary: #1a1d23;
            --bg-secondary: #252932;
            --text-primary: #e8eaed;
            --text-secondary: #9ca3af;
            --shadow-light: #2d3139;
            --shadow-dark: #0d0e11;
            --border-color: #3a3f4b;
            --icon-color: #64b5f6;
        }

        .webcam-capture {
            display: inline-block;
            width: 100% !important;
            margin: 0 !important;
            margin-top: 20px !important;
            margin-bottom: 15px !important;
            padding: 0 !important;
            min-height: 380px !important;
            height: auto !important;
            border-radius: 25px;
            overflow: hidden;
            position: relative;
            flex: 1 1 100%;
            background: var(--bg-primary);
            box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        }

        .webcam-capture video {
            display: block;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            min-height: 380px !important;
            border-radius: 25px;
            object-fit: cover;
            position: relative;
            z-index: 1;
        }

        #facedetection {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;
            position: relative;
            min-height: 100%;
            height: auto;
            margin: 0 !important;
            padding: 0 10px !important;
            padding-bottom: 300px !important;
            width: 100% !important;
            gap: 15px;
        }

        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #header-section .appHeader {
            background: var(--bg-primary) !important;
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            border: none !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 15px 20px !important;
        }

        .headerButton {
            background: var(--bg-primary);
            width: 45px;
            height: 45px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            color: var(--icon-color);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .headerButton:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            transform: scale(0.95);
        }

        .pageTitle {
            color: var(--text-primary) !important;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        #content-section {
            margin-top: 45px;
            padding: 15px !important;
            padding-bottom: 120px !important;
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 45px);
            height: auto;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .scan-buttons {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 0;
            margin: 0;
            flex: 1 1 100%;
        }

        .scan-button {
            height: 50px !important;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48%;
            background: var(--bg-primary) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light) !important;
            color: var(--text-primary) !important;
            font-weight: 700 !important;
            transition: all 0.3s ease;
        }

        .scan-button:active {
            transform: scale(0.95);
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light) !important;
        }

        #map {
            height: 200px;
            width: 100%;
            position: relative;
            margin: 0;
            z-index: 10;
            border-radius: 20px;
            overflow: hidden;
            flex: 1 1 48%;
            background: var(--bg-primary);
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            border: 1px solid var(--border-color);
        }

        .jam-digital-malasngoding {
            background: var(--bg-primary);
            position: relative;
            width: 100%;
            margin: 0;
            border-radius: 20px;
            padding: 18px;
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            border: 1px solid var(--border-color);
            flex: 1 1 48%;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .jam-digital-malasngoding p {
            color: var(--text-primary);
            font-size: 11px;
            text-align: center;
            margin-top: 0;
            margin-bottom: 5px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .jam-digital-malasngoding p#jam {
            font-size: 36px;
            font-weight: 300;
            color: var(--text-primary);
            margin: 8px 0 10px 0;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            line-height: 1;
        }

        #listcabang {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            margin: 0;
            flex: 1 1 100%;
        }

        #listcabang select {
            width: 100%;
            height: 50px;
            border-radius: 20px;
            background: var(--bg-primary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 0 15px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        #listcabang select:focus {
            outline: none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark), inset -6px -6px 12px var(--shadow-light);
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <div id="header-section">
        <div class="appHeader text-light">
            <div class="left">
                <a href="javascript:;" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">E-Presensi Yayasan</div>
            <div class="right"></div>
        </div>
    </div>

    <div id="content-section">
        <div class="row" style="margin-top: 0; height: auto; min-height: 100%;">
            <div class="col" id="facedetection">
                <div class="webcam-capture"></div>
                <div id="map"></div>
                <div class="jam-digital-malasngoding">
                    <p>{{ DateToIndo(date('Y-m-d')) }}</p>
                    <p id="jam"></p>
                    <p>{{ $jam_kerja->nama_jam_kerja }}</p>
                    <p style="display: flex; justify-content:space-between">
                        <span>Mulai</span>
                        <span>{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }}</span>
                    </p>
                    <p style="display: flex; justify-content:space-between">
                        <span>Pulang</span>
                        <span>{{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</span>
                    </p>
                </div>
                @if ($general_setting->multi_lokasi)
                    <div id="listcabang">
                        <select name="cabang" id="cabang" class="form-control">
                            @foreach ($cabang as $item)
                                <option {{ $item->kode_cabang == $yayasan->kode_cabang ? 'selected' : '' }}
                                    value="{{ $item->lokasi_cabang }}">
                                    {{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="scan-buttons">
                    <button class="btn btn-success bg-primary scan-button" id="absenmasuk" statuspresensi="masuk">
                        <ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon>
                        <span style="font-size:14px">Masuk</span>
                    </button>
                    <button class="btn btn-danger scan-button" id="absenpulang" statuspresensi="pulang">
                        <ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon>
                        <span style="font-size:14px">Pulang</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <audio id="notifikasi_radius">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_mulaiabsen">
        <source src="{{ asset('assets/sound/mulaiabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_akhirabsen">
        <source src="{{ asset('assets/sound/akhirabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_sudahabsen">
        <source src="{{ asset('assets/sound/sudahabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenmasuk">
        <source src="{{ asset('assets/sound/absenmasuk.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenpulang">
        <source src="{{ asset('assets/sound/absenpulang.mp3') }}" type="audio/mpeg">
    </audio>

@endsection

@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());
            e.innerHTML = h + ':' + m + ':' + s;
            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>

    <script>
        $(function() {
            let lokasi;
            let multi_lokasi = {{ $general_setting->multi_lokasi }};
            let lokasi_cabang = multi_lokasi ? document.getElementById('cabang').value :
                "{{ $lokasi_kantor->lokasi_cabang }}";
            let map;

            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

            function initWebcam() {
                Webcam.set({
                    height: 480,
                    width: 640,
                    image_format: 'jpeg',
                    jpeg_quality: isMobile ? 80 : 95,
                    fps: isMobile ? 15 : 30,
                    constraints: {
                        video: {
                            width: { ideal: isMobile ? 240 : 640 },
                            height: { ideal: isMobile ? 180 : 480 },
                            facingMode: "user",
                            frameRate: { ideal: isMobile ? 15 : 30 }
                        }
                    }
                });
                Webcam.attach('.webcam-capture');
            }

            initWebcam();

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
            }

            function successCallback(position) {
                try {
                    map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
                    var lokasi_kantor = lokasi_cabang;
                    lokasi = position.coords.latitude + "," + position.coords.longitude;
                    var lok = lokasi_kantor.split(",");
                    var lat_kantor = lok[0];
                    var long_kantor = lok[1];
                    var radius = "{{ $lokasi_kantor->radius_cabang }}";

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
                    var circle = L.circle([lat_kantor, long_kantor], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: radius
                    }).addTo(map);

                    setTimeout(function() {
                        map.invalidateSize();
                    }, 500);
                } catch (error) {
                    console.error("Error initializing map:", error);
                }
            }

            function errorCallback(error) {
                console.error("Error getting geolocation:", error);
            }

            // Tombol Absen Masuk
            $("#absenmasuk").click(function() {
                let status = 1;
                takeAbsen(status);
            });

            // Tombol Absen Pulang
            $("#absenpulang").click(function() {
                let status = 2;
                takeAbsen(status);
            });

            function takeAbsen(status) {
                if (!lokasi) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal mendapatkan lokasi Anda!'
                    });
                    return;
                }

                Webcam.snap(function(data_uri) {
                    let kode_jam_kerja = "{{ $jam_kerja->kode_jam_kerja }}";
                    let lokasi_cabang_value = multi_lokasi ? document.getElementById('cabang').value :
                        "{{ $lokasi_kantor->lokasi_cabang }}";

                    $.ajax({
                        type: "POST",
                        url: "{{ route('yayasan-presensi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status,
                            lokasi: lokasi,
                            image: data_uri,
                            kode_jam_kerja: kode_jam_kerja,
                            lokasi_cabang: lokasi_cabang_value
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: respond.message,
                                    didClose: () => {
                                        window.location.href = "{{ route('yayasan-presensi.histori') }}";
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: respond.message
                                });
                            }
                        },
                        error: function(xhr) {
                            let response = JSON.parse(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    });
                });
            }

            if (multi_lokasi) {
                $("#cabang").change(function() {
                    lokasi_cabang = $(this).val();
                    location.reload();
                });
            }
        });
    </script>
@endpush
