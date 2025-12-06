<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Peminjaman Kendaraan - {{ $peminjaman->kode_peminjaman }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.25;
            color: #000;
        }
        
        /* Watermark Background */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 65pt;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.04);
            z-index: -1;
            letter-spacing: 6px;
            white-space: nowrap;
        }
        
        .page {
            width: 100%;
            padding: 5mm 12mm;
            position: relative;
        }
        
        /* Kop Surat */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 1.5px;
        }
        
        .header p {
            font-size: 8pt;
            margin: 1px 0;
        }
        
        /* Title */
        .title {
            text-align: center;
            margin: 8px 0;
        }
        
        .title h2 {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        
        .title .doc-number {
            font-size: 8.5pt;
            margin-top: 2px;
        }
        
        /* Label for section */
        .section-label {
            background: #f0f0f0;
            padding: 2px 6px;
            margin: 6px 0 2px 0;
            font-weight: bold;
            border-left: 3px solid #333;
            font-size: 8pt;
        }
        
        /* Content table */
        table {
            width: 100%;
            margin: 4px 0;
            border-collapse: collapse;
        }
        
        table td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 8.5pt;
        }
        
        table td:first-child {
            width: 32%;
            font-weight: 500;
        }
        
        table td:nth-child(2) {
            width: 3%;
        }
        
        /* Signature area */
        .signature-area {
            margin-top: 10px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 6px;
        }
        
        .signature-box p {
            margin: 2px 0;
            font-size: 8pt;
        }
        
        .signature-line {
            margin-top: 30px;
            border-bottom: 1px solid #000;
            width: 115px;
            display: inline-block;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 2px;
        }
        
        /* Dashed line untuk sobek */
        .tear-line {
            border: none;
            border-top: 2px dashed #999;
            margin: 10px 0;
            position: relative;
            page-break-inside: avoid;
        }
        
        .tear-line::before {
            content: "✂ POTONG DI SINI";
            position: absolute;
            left: 50%;
            top: -8px;
            transform: translateX(-50%);
            background: white;
            padding: 0 8px;
            font-size: 7pt;
            color: #999;
            font-weight: bold;
        }
        
        /* Copy label */
        .copy-label {
            position: absolute;
            top: 5mm;
            right: 12mm;
            font-size: 7pt;
            font-weight: bold;
            color: #666;
            border: 1px solid #666;
            padding: 1px 4px;
            border-radius: 2px;
        }
        
        /* Note box */
        .note-box {
            border: 1px solid #ddd;
            background: #f9f9f9;
            padding: 4px 6px;
            margin: 6px 0;
            font-size: 7.5pt;
        }
        
        .note-box strong {
            display: block;
            margin-bottom: 2px;
            color: #d9534f;
        }
        
        .note-box ul {
            margin-left: 12px;
            margin-top: 2px;
            margin-bottom: 0;
        }
        
        .note-box li {
            margin: 1px 0;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">BUMI SULTAN</div>
    
    <!-- BAGIAN ATAS: UNTUK ARSIP PERUSAHAAN -->
    <div class="page">
        <div class="copy-label">ARSIP PERUSAHAAN</div>
        
        <!-- Kop Surat -->
        <div class="header">
            <h1>BUMI SULTAN</h1>
            <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
            <p>Kabupaten Bogor, Jawa Barat 16830</p>
        </div>
        
        <!-- Title -->
        <div class="title">
            <h2>SURAT PEMINJAMAN KENDARAAN</h2>
            <div class="doc-number">{{ $peminjaman->kode_peminjaman }}</div>
        </div>
        
        <!-- Data Peminjam -->
        <div class="section-label">DATA PEMINJAM</div>
        <table>
            <tr>
                <td>Nama Peminjam</td>
                <td>:</td>
                <td><strong>{{ $peminjaman->nama_peminjam }}</strong></td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>:</td>
                <td>{{ $peminjaman->no_hp_peminjam }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $peminjaman->email_peminjam }}</td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>:</td>
                <td>{{ $peminjaman->keperluan }}</td>
            </tr>
        </table>
        
        <!-- Data Kendaraan -->
        <div class="section-label">DATA KENDARAAN</div>
        <table>
            <tr>
                <td>Jenis Kendaraan</td>
                <td>:</td>
                <td><strong>{{ $peminjaman->kendaraan->jenis ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>Nomor Polisi</td>
                <td>:</td>
                <td><strong>{{ $peminjaman->kendaraan->nomor_polisi ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>Merk/Type</td>
                <td>:</td>
                <td>{{ $peminjaman->kendaraan->merk ?? '-' }}</td>
            </tr>
            <tr>
                <td>Driver</td>
                <td>:</td>
                <td>{{ $peminjaman->driver ?? 'Tidak ada driver' }}</td>
            </tr>
        </table>
        
        <!-- Waktu Peminjaman -->
        <div class="section-label">WAKTU PEMINJAMAN</div>
        <table>
            <tr>
                <td>Waktu Pinjam</td>
                <td>:</td>
                <td><strong>{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d/m/Y H:i') }} WIB</strong></td>
            </tr>
            <tr>
                <td>Estimasi Kembali</td>
                <td>:</td>
                <td><strong>{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d/m/Y H:i') }} WIB</strong></td>
            </tr>
            <tr>
                <td>KM Awal</td>
                <td>:</td>
                <td>{{ number_format($peminjaman->km_awal ?? 0, 0, ',', '.') }} km</td>
            </tr>
            <tr>
                <td>Status BBM Pinjam</td>
                <td>:</td>
                <td>{{ ucfirst($peminjaman->status_bbm_pinjam ?? '-') }}</td>
            </tr>
        </table>
        
        <!-- Catatan -->
        <div class="note-box">
            <strong>KETENTUAN:</strong>
            <ul>
                <li>Peminjam bertanggung jawab penuh atas keamanan dan kondisi kendaraan</li>
                <li>Wajib mengisi BBM sebelum mengembalikan kendaraan</li>
                <li>Segera lapor jika terjadi kecelakaan atau kerusakan</li>
                <li>Kembalikan kendaraan sesuai waktu yang telah ditentukan</li>
            </ul>
        </div>
        
        <!-- Signature -->
        <div class="signature-area">
            <div class="signature-box">
                <p>Pihak Perusahaan</p>
                <div class="signature-line"></div>
                <p class="signature-name">(_________________)</p>
            </div>
            <div class="signature-box">
                <p>Peminjam</p>
                <div class="signature-line"></div>
                <p class="signature-name">{{ $peminjaman->nama_peminjam }}</p>
            </div>
        </div>
    </div>
    
    <!-- GARIS POTONG -->
    <div class="tear-line"></div>
    
    <!-- BAGIAN BAWAH: UNTUK PEMINJAM (RINGKAS - TANPA KOP SURAT) -->
    <div style="padding: 8px 12mm; position: relative;">
        <div class="copy-label">COPY PEMINJAM</div>
        
        <!-- Title Ringkas Saja -->
        <div style="text-align: center; margin: 5px 0 8px 0; border-bottom: 2px solid #000; padding-bottom: 5px;">
            <h3 style="font-size: 11pt; margin: 0; font-weight: bold;">BUKTI PEMINJAMAN KENDARAAN</h3>
            <div style="font-size: 8.5pt; margin-top: 2px;">{{ $peminjaman->kode_peminjaman }}</div>
        </div>
        
        <!-- Data Ringkas -->
        <table style="margin-top: 5px;">
            <tr>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>Peminjam</strong></td>
                <td style="font-size: 8pt; padding: 1px 3px;">:</td>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>{{ $peminjaman->nama_peminjam }}</strong></td>
            </tr>
            <tr>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>No. HP</strong></td>
                <td style="font-size: 8pt; padding: 1px 3px;">:</td>
                <td style="font-size: 8pt; padding: 1px 3px;">{{ $peminjaman->no_hp_peminjam }}</td>
            </tr>
            <tr>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>Kendaraan</strong></td>
                <td style="font-size: 8pt; padding: 1px 3px;">:</td>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>{{ $peminjaman->kendaraan->jenis ?? '-' }} - {{ $peminjaman->kendaraan->nomor_polisi ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>Waktu Pinjam</strong></td>
                <td style="font-size: 8pt; padding: 1px 3px;">:</td>
                <td style="font-size: 8pt; padding: 1px 3px;">{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>Estimasi Kembali</strong></td>
                <td style="font-size: 8pt; padding: 1px 3px;">:</td>
                <td style="font-size: 8pt; padding: 1px 3px;">{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td style="font-size: 8pt; padding: 1px 3px;"><strong>Keperluan</strong></td>
                <td style="font-size: 8pt; padding: 1px 3px;">:</td>
                <td style="font-size: 8pt; padding: 1px 3px;">{{ $peminjaman->keperluan }}</td>
            </tr>
        </table>
        
        <!-- Note box ringkas -->
        <div style="border: 1px solid #999; padding: 3px 5px; margin: 5px 0; background-color: #fffdf0;">
            <strong style="font-size: 7.5pt;">PENTING:</strong>
            <ul style="font-size: 7pt; margin: 1px 0; padding-left: 10px;">
                <li style="margin: 0;">Bukti peminjaman sah untuk penggunaan kendaraan</li>
                <li style="margin: 0;">Wajib dikembalikan sesuai waktu yang ditentukan</li>
                <li style="margin: 0;">Hubungi perusahaan jika ada kendala</li>
            </ul>
        </div>
        
        <!-- Signature ringkas -->
        <div style="margin-top: 12px; text-align: right;">
            <p style="margin: 0; font-size: 8pt;">Jonggol, {{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d M Y') }}</p>
            <p style="margin: 2px 0; font-size: 8pt;">Peminjam,</p>
            <div style="height: 25px;"></div>
            <p style="margin: 0; font-weight: bold; border-top: 1px solid #000; display: inline-block; padding-top: 2px; min-width: 110px; font-size: 8pt;">
                {{ $peminjaman->nama_peminjam }}
            </p>
        </div>
    </div>
</body>
</html>
