@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
            --color-nav-hover: #3ab58c;
        }

        body {
            background: linear-gradient(180deg, #e3f4f9 0%, #f8fbfc 100%);
        }

        #header-section {
            height: auto;
            padding: 25px 20px;
            position: relative;
            background: linear-gradient(135deg, #1e5245 0%, #2d6a5a 100%);
            border-radius: 0 0 35px 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            color: #ffffff;
            font-size: 32px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            margin-top: 10px;
        }

        #header-title h3 {
            font-weight: 900;
            margin: 0;
            font-size: 1.8rem;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            letter-spacing: -0.5px;
        }

        #header-title p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 0.95rem;
            font-weight: 500;
        }

        #content-section {
            padding: 20px;
            padding-bottom: 100px;
        }

        .info-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(15px);
            padding: 20px;
            border-radius: 22px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
            border: 2px solid rgba(30, 82, 69, 0.15);
            margin-bottom: 20px;
        }

        .info-card h6 {
            font-weight: 900;
            color: #1e5245;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(30, 82, 69, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 700;
        }

        .info-value {
            font-size: 0.9rem;
            color: #222;
            font-weight: 900;
            text-align: right;
        }

        .form-card {
            background: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e5245;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #1e5245;
            box-shadow: 0 0 0 0.2rem rgba(30, 82, 69, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 900;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.3);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(22, 163, 74, 0.4);
        }

        .text-danger {
            color: #dc2626;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('barang.karyawan', [
                'gedung_id' => Crypt::encrypt($gedung->id),
                'ruangan_id' => Crypt::encrypt($ruangan->id)
            ]) }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Transfer Barang</h3>
            <p>{{ $barang->nama_barang }}</p>
        </div>
    </div>

    <div id="content-section">
        <!-- Info Barang -->
        <div class="info-card">
            <h6><ion-icon name="information-circle"></ion-icon> Informasi Barang</h6>
            <div class="info-row">
                <span class="info-label">Kode Barang</span>
                <span class="info-value">{{ $barang->kode_barang }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Barang</span>
                <span class="info-value">{{ $barang->nama_barang }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Lokasi Saat Ini</span>
                <span class="info-value">{{ $gedung->nama_gedung }} - {{ $ruangan->nama_ruangan }}</span>
            </div>
        </div>

        <!-- Form Transfer -->
        <div class="form-card">
            <form action="{{ route('barang.prosesTransferKaryawan', [
                'gedung_id' => Crypt::encrypt($gedung->id),
                'ruangan_id' => Crypt::encrypt($ruangan->id),
                'id' => Crypt::encrypt($barang->id)
            ]) }}" id="formTransferBarang" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="location-outline"></ion-icon> 
                        Ruangan Tujuan <span class="text-danger">*</span>
                    </label>
                    <select name="ruangan_tujuan_id" id="ruangan_tujuan_id" class="form-select">
                        <option value="">-- Pilih Ruangan Tujuan --</option>
                        @foreach ($all_ruangan as $r)
                            <option value="{{ $r->id }}">
                                {{ $r->gedung->nama_gedung }} - {{ $r->nama_ruangan }} 
                                @if($r->lantai) (Lantai {{ $r->lantai }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="cube-outline"></ion-icon> 
                        Jumlah Transfer <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="jumlah_transfer" id="jumlah_transfer" class="form-control" 
                        min="1" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="calendar-outline"></ion-icon> 
                        Tanggal Transfer <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="tanggal_transfer" id="tanggal_transfer" class="form-control" 
                        value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="person-outline"></ion-icon> 
                        Petugas
                    </label>
                    <input type="text" name="petugas" id="petugas" class="form-control" 
                        value="{{ auth()->user()->name }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="document-text-outline"></ion-icon> 
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <button class="btn-submit" type="submit">
                        <ion-icon name="arrow-forward-up" style="font-size: 1.3rem;"></ion-icon>
                        Proses Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $("#formTransferBarang").submit(function(e) {
            var ruangan_tujuan_id = $("#ruangan_tujuan_id").val();
            var jumlah_transfer = $("#jumlah_transfer").val();
            var tanggal_transfer = $("#tanggal_transfer").val();

            if (ruangan_tujuan_id == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Ruangan Tujuan Harus Dipilih!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    $("#ruangan_tujuan_id").focus();
                });
                return false;
            } else if (jumlah_transfer == "" || jumlah_transfer <= 0) {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Jumlah Transfer Harus Diisi dan Lebih dari 0!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    $("#jumlah_transfer").focus();
                });
                return false;
            } else if (tanggal_transfer == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal Transfer Harus Diisi!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    $("#tanggal_transfer").focus();
                });
                return false;
            }

            // Konfirmasi transfer
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Transfer',
                text: 'Apakah Anda yakin akan mentransfer ' + jumlah_transfer + ' item barang ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Transfer',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#formTransferBarang").unbind('submit').submit();
                }
            });
        });
    </script>
@endpush
