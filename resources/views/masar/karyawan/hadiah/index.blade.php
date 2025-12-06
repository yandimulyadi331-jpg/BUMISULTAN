@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --primary-gradient-start: #8e44ad;
            --primary-gradient-end: #3498db;
        }

        body {
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            min-height: 100vh;
        }

        #header-section {
            height: auto;
            padding: 20px;
            position: relative;
            background: transparent;
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            color: #ffffff;
            font-size: 30px;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            backdrop-filter: blur(10px);
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            margin-top: 10px;
        }

        #content-section {
            padding: 20px;
            margin-top: -20px;
        }

        .hadiah-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .hadiah-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .hadiah-name {
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }

        .hadiah-jenis {
            font-size: 0.8rem;
            color: #666;
            margin-top: 3px;
        }

        .stok-progress {
            background: #e0e0e0;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin: 10px 0;
        }

        .stok-bar {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: white;
            font-weight: 600;
        }

        .stok-bar.high { background: #27ae60; }
        .stok-bar.medium { background: #f39c12; }
        .stok-bar.low { background: #e74c3c; }

        .badge {
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .fab-button {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            text-decoration: none;
            z-index: 1000;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('masar.karyawan.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Manajemen Hadiah</h3>
            <p>MASAR</p>
        </div>
    </div>

    <div id="content-section">
        <div id="hadiahList">
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
        </div>

        <div style="height: 100px;"></div>
    </div>

    <!-- FAB untuk tambah hadiah -->
    <a href="{{ route('masar.karyawan.hadiah.create') }}" class="fab-button">
        <ion-icon name="add-outline"></ion-icon>
    </a>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadHadiah() {
        $.ajax({
            url: '{{ route("masar.karyawan.hadiah.index") }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                renderHadiah(response.data);
            },
            error: function() {
                $('#hadiahList').html('<div class="empty-state">Gagal memuat data</div>');
            }
        });
    }

    function renderHadiah(data) {
        if (data.length === 0) {
            $('#hadiahList').html('<div class="empty-state">Belum ada hadiah</div>');
            return;
        }

        let html = '';
        data.forEach(function(hadiah) {
            let percentage = hadiah.stok_awal > 0 ? (hadiah.stok_tersedia / hadiah.stok_awal) * 100 : 0;
            let barClass = percentage > 50 ? 'high' : (percentage > 20 ? 'medium' : 'low');
            let statusBadge = hadiah.status == 'tersedia' ? 
                '<span class="badge badge-success">Tersedia</span>' : 
                '<span class="badge badge-danger">Habis</span>';

            html += `
                <div class="hadiah-card">
                    <div class="hadiah-header">
                        <div>
                            <div class="hadiah-name">${hadiah.nama_hadiah}</div>
                            <div class="hadiah-jenis">${hadiah.jenis_hadiah}</div>
                        </div>
                        ${statusBadge}
                    </div>
                    <div class="stok-progress">
                        <div class="stok-bar ${barClass}" style="width: ${percentage}%">
                            ${hadiah.stok_tersedia}/${hadiah.stok_awal}
                        </div>
                    </div>
                </div>
            `;
        });

        $('#hadiahList').html(html);
    }

    loadHadiah();
});
</script>
@endsection

