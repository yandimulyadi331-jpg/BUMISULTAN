<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Pinjaman Karyawan</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }
        .nomor {
            text-align: center;
            font-size: 11px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 13px;
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-left: 4px solid #333;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 200px;
            font-weight: bold;
        }
        .info-table td:nth-child(2) {
            width: 20px;
        }
        .box {
            border: 1px solid #333;
            padding: 10px;
            margin: 10px 0;
            background-color: #fafafa;
        }
        .syarat-list {
            margin-left: 20px;
            line-height: 1.8;
        }
        .syarat-list li {
            margin-bottom: 5px;
        }
        .pernyataan {
            text-align: justify;
            line-height: 1.8;
            margin: 15px 0;
        }
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            display: table;
            width: 100%;
        }
        .signature-col {
            display: table-cell;
            width: 48%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
            min-width: 200px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .stamp-box {
            border: 2px dashed #999;
            padding: 40px 20px;
            text-align: center;
            color: #999;
            margin: 10px 0;
        }
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 2px solid #333;
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMI SULTAN</h1>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
        <p>Kabupaten Bogor, Jawa Barat 16830</p>
        <p>Telp: (021) xxxx-xxxx | Email: info@bumisultan.com</p>
    </div>

    <div class="title">FORMULIR PERMOHONAN PINJAMAN KARYAWAN</div>
    <div class="nomor">No. Formulir: PIN/{{ $pinjaman->id }}/{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('m/Y') }}</div>

    <!-- BAGIAN I: DATA PEMOHON -->
    <div class="section">
        <div class="section-title">BAGIAN I: DATA PEMOHON</div>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $pinjaman->tukang->nama_tukang }}</td>
            </tr>
            <tr>
                <td>Kode Karyawan</td>
                <td>:</td>
                <td>{{ $pinjaman->tukang->kode_tukang }}</td>
            </tr>
            <tr>
                <td>Nomor Telepon</td>
                <td>:</td>
                <td>{{ $pinjaman->tukang->no_telpon ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $pinjaman->tukang->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Pengajuan</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN II: DETAIL PINJAMAN -->
    <div class="section">
        <div class="section-title">BAGIAN II: DETAIL PINJAMAN</div>
        <table class="info-table">
            <tr>
                <td>Jumlah Pinjaman</td>
                <td>:</td>
                <td><strong style="font-size: 14px;">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td>:</td>
                <td><em>{{ ucwords(terbilang($pinjaman->jumlah_pinjaman)) }} Rupiah</em></td>
            </tr>
            <tr>
                <td>Keperluan/Tujuan Pinjaman</td>
                <td>:</td>
                <td>{{ $pinjaman->keterangan ?? 'Keperluan Pribadi' }}</td>
            </tr>
            <tr>
                <td>Jangka Waktu Pengembalian</td>
                <td>:</td>
                <td>{{ $pinjaman->cicilan_per_minggu > 0 ? ceil($pinjaman->jumlah_pinjaman / $pinjaman->cicilan_per_minggu) : '-' }} Minggu</td>
            </tr>
            <tr>
                <td>Cicilan Per Minggu</td>
                <td>:</td>
                <td><strong>Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>:</td>
                <td>Potong Gaji Mingguan</td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN III: SYARAT DAN KETENTUAN -->
    <div class="section">
        <div class="section-title">BAGIAN III: SYARAT DAN KETENTUAN</div>
        <ol class="syarat-list">
            <li>Pemohon wajib mengembalikan pinjaman sesuai dengan jadwal cicilan yang telah disepakati.</li>
            <li>Cicilan akan dipotong langsung dari gaji mingguan karyawan.</li>
            <li>Apabila terjadi keterlambatan atau tidak ada pembayaran, perusahaan berhak melakukan pemotongan penuh dari gaji.</li>
            <li>Pinjaman ini tidak dikenakan bunga/bagi hasil.</li>
            <li>Pemohon bertanggung jawab penuh atas pinjaman yang diajukan.</li>
            <li>Apabila pemohon mengundurkan diri atau diberhentikan, sisa pinjaman wajib dilunasi.</li>
            <li>Formulir ini berlaku sebagai bukti kesepakatan pinjaman antara karyawan dan perusahaan.</li>
        </ol>
    </div>

    <!-- BAGIAN IV: PERNYATAAN PEMOHON -->
    <div class="section">
        <div class="section-title">BAGIAN IV: PERNYATAAN PEMOHON</div>
        <div class="pernyataan">
            Saya yang bertanda tangan di bawah ini menyatakan bahwa:
            <ol class="syarat-list">
                <li>Data yang saya berikan dalam formulir ini adalah benar dan dapat dipertanggungjawabkan.</li>
                <li>Saya telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.</li>
                <li>Saya bersedia gaji saya dipotong setiap minggu sesuai dengan nominal cicilan yang telah ditentukan.</li>
                <li>Apabila saya mengundurkan diri atau diberhentikan sebelum pinjaman lunas, saya bersedia melunasi seluruh sisa pinjaman.</li>
            </ol>
        </div>
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-col">
                <div class="signature-label">Pemohon,<br><small>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d F Y') }}</small></div>
                @if($pinjaman->foto_bukti)
                    <div style="margin: 10px 0;">
                        <img src="{{ public_path('storage/' . $pinjaman->foto_bukti) }}" style="max-height: 60px;" alt="TTD">
                    </div>
                @endif
                <div class="signature-line">
                    <strong>{{ $pinjaman->tukang->nama_tukang }}</strong><br>
                    <small>{{ $pinjaman->tukang->kode_tukang }}</small>
                </div>
            </div>
            <div class="signature-col">
                <div class="signature-label">Menyetujui,<br>Divisi Keuangan</div>
                <div class="stamp-box">
                    <strong>STEMPEL<br>PERUSAHAAN</strong>
                </div>
                <div class="signature-line">
                    <strong>_____________________</strong><br>
                    <small>Kepala Divisi Keuangan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- CATATAN KEUANGAN -->
    <div class="box">
        <strong>CATATAN DIVISI KEUANGAN:</strong><br>
        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td style="width: 30%;">Status Persetujuan:</td>
                <td>
                    <span class="checkbox"></span> Disetujui &nbsp;&nbsp;
                    <span class="checkbox"></span> Ditolak &nbsp;&nbsp;
                    <span class="checkbox"></span> Pending
                </td>
            </tr>
            <tr>
                <td>Tanggal Disetujui:</td>
                <td>_______________________________</td>
            </tr>
            <tr>
                <td>Catatan:</td>
                <td>_______________________________</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p><strong>Dokumen ini dicetak secara otomatis dari sistem Manajemen Bumi Sultan</strong></p>
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
        <p><em>Formulir ini sah tanpa tanda tangan basah jika dicetak dari sistem</em></p>
    </div>
</body>
</html>

@php
function terbilang($angka) {
    $angka = abs($angka);
    $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $terbilang = "";
    
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " Belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " Seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " Seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
    }
    
    return $terbilang;
}
@endphp
