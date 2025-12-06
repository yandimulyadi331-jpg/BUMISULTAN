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

        .container-fluid {
            padding: 0 !important;
        }

        .appHeader {
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

        .pageContent {
            padding: 15px;
            padding-bottom: 100px;
            min-height: 100vh;
        }

        .card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            margin-bottom: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:active {
            transform: scale(0.98);
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .jam-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        .jam-info span {
            font-weight: 600;
            color: var(--text-primary);
        }

        .btn-select {
            width: 100%;
            height: 50px;
            border-radius: 15px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 700;
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-select:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            transform: scale(0.98);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-state ion-icon {
            font-size: 48px;
            display: block;
            margin-bottom: 15px;
            opacity: 0.5;
        }
    </style>

    <div class="appHeader text-light">
        <div class="left">
            <a href="javascript:history.back()" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Pilih Jam Kerja</div>
        <div class="right"></div>
    </div>

    <div class="pageContent">
        @if ($jam_kerja_list->count() > 0)
            @foreach ($jam_kerja_list as $jk)
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ $jk->nama_jam_kerja }}</div>
                        <div class="jam-info">
                            <span>Masuk:</span>
                            <span>{{ date('H:i', strtotime($jk->jam_masuk)) }}</span>
                        </div>
                        <div class="jam-info">
                            <span>Pulang:</span>
                            <span>{{ date('H:i', strtotime($jk->jam_pulang)) }}</span>
                        </div>
                        <button type="button" class="btn-select mt-3" onclick="selectJamKerja('{{ $jk->kode_jam_kerja }}')">
                            <ion-icon name="checkmark-circle-outline" style="vertical-align: middle; margin-right: 5px;"></ion-icon>
                            Pilih
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <ion-icon name="information-circle-outline"></ion-icon>
                <p>Tidak ada jam kerja yang tersedia</p>
            </div>
        @endif
    </div>

@endsection

@push('myscript')
    <script>
        function selectJamKerja(kode_jam_kerja) {
            $.ajax({
                type: "POST",
                url: "{{ route('yayasan-presensi.create') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jam_kerja: kode_jam_kerja
                },
                success: function(respond) {
                    if (respond.status == true) {
                        window.location.href = "{{ route('yayasan-presensi.create') }}";
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
        }
    </script>
@endpush
