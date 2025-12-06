@extends('layouts.mobile.app')
@section('content')
    <style>
        /* FORCE SCROLL - SUPER AGGRESSIVE */
        * {
            -webkit-overflow-scrolling: touch !important;
        }

        html {
            overflow: visible !important;
            overflow-y: scroll !important;
            overflow-x: hidden !important;
            height: auto !important;
            max-height: none !important;
            position: relative !important;
        }

        body {
            overflow: visible !important;
            overflow-y: scroll !important;
            overflow-x: hidden !important;
            height: auto !important;
            max-height: none !important;
            min-height: 100vh !important;
            position: relative !important;
        }

        #appCapsule {
            overflow: visible !important;
            overflow-y: visible !important;
            height: auto !important;
            max-height: none !important;
            min-height: calc(100vh + 200px) !important;
            position: static !important;
            padding-bottom: 150px !important;
        }

        :root {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --text-primary: #1a202c;
            --text-secondary: #718096;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --text-primary: #f7fafc;
            --text-secondary: #cbd5e0;
            --border-color: #4a5568;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--bg-secondary);
        }

        #content-section {
            margin-top: 56px;
            padding: 8px 16px 150px 16px;
            position: relative;
            z-index: 1;
            background: var(--bg-primary);
            min-height: calc(100vh - 56px);
            transition: background 0.3s ease;
        }

        /* Search & Filter Section - Modern Style */
        .search-filter-section {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .feedback-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 14px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }

        .feedback-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* History Card - Modern 3D Style */
        .historicard {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 15px !important;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .historicard:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .historibordergreen {
            border-left-color: #10b981;
        }

        .historicontent {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .historidetail1 {
            display: flex;
            gap: 12px;
            align-items: center;
            flex: 1;
        }

        .iconpresence {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }

        .datepresence h4 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 4px 0;
        }

        .datepresence .timepresence {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .datepresence span {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .historidetail2 {
            text-align: right;
        }

        .historidetail2 h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 4px 0;
        }

        .historidetail2 .timepresence {
            font-size: 12px;
            color: var(--text-secondary);
        }

        /* Alert Style */
        .alert-warning {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 10px;
            padding: 12px 16px;
            color: #92400e;
        }

        /* Theme Toggle */
        .theme-toggle-btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }

        .theme-toggle-btn ion-icon {
            font-size: 22px;
            color: var(--text-primary);
        }

        .theme-toggle-btn:active {
            transform: scale(0.95);
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Histori Presensi</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <!-- Search & Filter Section -->
        <div class="search-filter-section">
            <button class="theme-toggle-btn" onclick="toggleDarkMode()">
                <ion-icon name="sunny-outline" id="themeIcon"></ion-icon>
            </button>
            <form action="{{ route('presensi.histori') }}" method="GET">
                <input type="text" class="feedback-input dari" name="dari" placeholder="Dari Tanggal" id="datePicker" value="{{ Request('dari') }}" />
                <input type="text" class="feedback-input sampai" name="sampai" placeholder="Sampai Tanggal" id="datePicker2"
                    value="{{ Request('sampai') }}" />
                <button class="btn btn-primary w-100" id="btnSimpan" type="submit">
                    <ion-icon name="search-circle-outline"></ion-icon>
                    Cari Data
                </button>
            </form>
        </div>
        </div>

        <!-- History List -->
        <div class="row">
            <div class="col">
                @if ($datapresensi->isEmpty())
                    <div class="alert alert-warning d-flex align-items-center">
                        <ion-icon name="information-circle-outline" style="font-size: 24px; margin-right: 8px;"></ion-icon>
                        <span style="font-size: 14px">Data Tidak Ditemukan</span>
                    </div>
                @endif
                @foreach ($datapresensi as $d)
                    @if ($d->status == 'h')
                        @php
                            $jam_in = date('Y-m-d H:i', strtotime($d->jam_in));
                            $jam_masuk = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_masuk));
                        @endphp
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="finger-print-outline" style="font-size: 48px"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <span class="timepresence">
                                            @if ($d->jam_in != null)
                                                {{ date('H:i', strtotime($d->jam_in)) }}
                                            @else
                                                <span class="text-danger">
                                                    <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                </span>
                                            @endif
                                            -
                                            @if ($d->jam_out != null)
                                                {{ date('H:i', strtotime($d->jam_out)) }}
                                            @else
                                                <span class="text-danger">
                                                    <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                </span>
                                            @endif
                                        </span>
                                        <br>
                                        @if ($d->jam_in != null)
                                            @php
                                                $terlambat = hitungjamterlambat(date('H:i', strtotime($jam_in)), date('H:i', strtotime($jam_masuk)));

                                            @endphp
                                            {!! $terlambat['show'] !!}
                                        @endif


                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($d->status == 'i')
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <h4 class="timepresence">
                                            Izin Absen
                                        </h4>
                                        <span>{{ $d->keterangan_izin }}</span>
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($d->status == 'i')
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <h4 class="timepresence">
                                            Izin Cuti
                                        </h4>
                                        <span>{{ $d->keterangan_cuti }}</span>
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($d->status == 's')
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="bag-add-outline" style="font-size: 48px; color: #d4095a"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <h4 class="timepresence">
                                            Izin Sakit
                                        </h4>
                                        <span>{{ $d->keterangan_sakit }}</span>
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                
                <!-- Spacer untuk memastikan bisa scroll -->
                <div style="height: 300px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 14px;">
                    <p>Scroll ke bawah untuk melihat lebih banyak data...</p>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('myscript')
    <script>
        // Force enable scrolling
        document.addEventListener('DOMContentLoaded', function() {
            document.documentElement.style.overflowY = 'scroll';
            document.body.style.overflowY = 'scroll';
            document.body.style.height = 'auto';
            document.body.style.maxHeight = 'none';
            
            const appCapsule = document.getElementById('appCapsule');
            if (appCapsule) {
                appCapsule.style.overflow = 'visible';
                appCapsule.style.height = 'auto';
                appCapsule.style.maxHeight = 'none';
            }

            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.getElementById('themeIcon');
            
            if (savedTheme === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
                if (themeIcon) themeIcon.setAttribute('name', 'moon-outline');
            }
        });

        // Dark Mode Toggle
        function toggleDarkMode() {
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            
            if (body.getAttribute('data-theme') === 'dark') {
                body.removeAttribute('data-theme');
                themeIcon.setAttribute('name', 'sunny-outline');
                localStorage.setItem('theme', 'light');
            } else {
                body.setAttribute('data-theme', 'dark');
                themeIcon.setAttribute('name', 'moon-outline');
                localStorage.setItem('theme', 'dark');
            }
        }

        var lang = {
            title: 'Pilih Tanggal',
            cancel: 'Batal',
            confirm: 'Set',
            year: '',
            month: '',
            day: '',
            hour: '',
            min: '',
            sec: ''
        };
        new Rolldate({
            el: '#datePicker',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            confirm: function(date) {

            }
        });

        new Rolldate({
            el: '#datePicker2',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            confirm: function(date) {

            }
        });
    </script>
@endpush
