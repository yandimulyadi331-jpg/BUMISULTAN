<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Peminjam - {{ $peminjaman->kode_peminjaman }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            padding: 20px;
        }
        
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .kop-surat h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .kop-surat h2 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .kop-surat p {
            font-size: 10pt;
            margin: 2px 0;
        }
        
        .judul-dokumen {
            text-align: center;
            margin: 20px 0;
        }
        
        .judul-dokumen h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        .nomor-surat {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 20px;
        }
        
        .info-section {
            margin: 15px 0;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        
        .info-table .label {
            width: 35%;
            font-weight: bold;
        }
        
        .info-table .separator {
            width: 5%;
        }
        
        .info-table .value {
            width: 60%;
        }
        
        .highlight-box {
            border: 2px solid #0d6efd;
            padding: 20px;
            margin: 20px 0;
            background-color: #e7f3ff;
            border-radius: 5px;
        }
        
        .highlight-box h4 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            color: #0d6efd;
        }
        
        .detail-box {
            border: 1px solid #000;
            padding: 15px;
            margin: 15px 0;
            background-color: #fff;
        }
        
        .detail-box h4 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .warning-box {
            border: 2px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            background-color: #fff8e1;
            border-radius: 5px;
        }
        
        .warning-box h4 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
            color: #ff9800;
        }
        
        .warning-box ul {
            margin-left: 20px;
        }
        
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 15px;
        }
        
        .signature-box {
            margin-top: 10px;
            min-height: 70px;
        }
        
        .signature-image {
            max-width: 130px;
            max-height: 60px;
            margin: 10px auto;
        }
        
        .signature-name {
            margin-top: 10px;
            font-weight: bold;
            text-decoration: underline;
        }
        
        .signature-role {
            font-size: 9pt;
            font-style: italic;
        }
        
        .footer-note {
            margin-top: 25px;
            font-size: 9pt;
            border-top: 1px dashed #999;
            padding-top: 10px;
            color: #666;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #198754;
            color: white;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-important {
            color: #dc3545;
            font-weight: bold;
        }
        
        .mt-2 {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <h1>YAYASAN BUMI SULTAN</h1>
        <h2>DIVISI TRANSPORTASI</h2>
        <p>Jl. Contoh Alamat No. 123, Kota, Provinsi 12345</p>
        <p>Telp: (021) 1234567 | Email: transportasi@bumisultan.com</p>
    </div>
    
    <!-- Judul Dokumen -->
    <div class="judul-dokumen">
        <h3>SURAT JALAN PEMINJAMAN KENDARAAN</h3>
    </div>
    
    <div class="nomor-surat">
        No: {{ $peminjaman->kode_peminjaman }}/SJ/{{ date('m/Y', strtotime($peminjaman->waktu_pinjam)) }}
    </div>
    
    <!-- Highlight Box - Info Penting -->
    <div class="highlight-box">
        <h4>📋 BUKTI PEMINJAMAN KENDARAAN SAH</h4>
        <p class="text-center">
            Surat ini merupakan bukti sah peminjaman kendaraan dan<br>
            <strong class="text-important">WAJIB DIBAWA</strong> selama menggunakan kendaraan
        </p>
    </div>
    
    <!-- Data Peminjam -->
    <div class="detail-box">
        <h4>IDENTITAS PEMINJAM</h4>
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $peminjaman->nama_peminjam }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nomor HP/WA</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->no_hp_peminjam ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Divisi/Unit</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->divisi_peminjam ?? '-' }}</td>
            </tr>
        </table>
    </div>
    
    <!-- Data Kendaraan -->
    <div class="detail-box">
        <h4>KENDARAAN YANG DIPINJAM</h4>
        <table class="info-table">
            <tr>
                <td class="label">Kode Kendaraan</td>
                <td class="separator">:</td>
                <td class="value"><span class="badge">{{ $peminjaman->kendaraan->kode_kendaraan }}</span></td>
            </tr>
            <tr>
                <td class="label">Nama/Tipe</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $peminjaman->kendaraan->nama_kendaraan }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nomor Polisi</td>
                <td class="separator">:</td>
                <td class="value"><strong style="font-size: 13pt;">{{ $peminjaman->kendaraan->no_polisi }}</strong></td>
            </tr>
            <tr>
                <td class="label">Jenis</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->kendaraan->jenis_kendaraan }}</td>
            </tr>
            <tr>
                <td class="label">Warna</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->kendaraan->warna ?? '-' }}</td>
            </tr>
        </table>
    </div>
    
    <!-- Waktu Peminjaman -->
    <div class="detail-box">
        <h4>WAKTU PEMINJAMAN</h4>
        <table class="info-table">
            <tr>
                <td class="label">Waktu Pinjam</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d F Y, H:i') }} WIB</strong></td>
            </tr>
            <tr>
                <td class="label">Estimasi Kembali</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d F Y, H:i') }} WIB</strong></td>
            </tr>
            <tr>
                <td class="label">Keperluan</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->keperluan }}</td>
            </tr>
            @if($peminjaman->tujuan)
            <tr>
                <td class="label">Tujuan</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->tujuan }}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <!-- Warning Box - Kewajiban Peminjam -->
    <div class="warning-box">
        <h4>⚠️ KEWAJIBAN PEMINJAM</h4>
        <ul style="font-size: 10pt;">
            <li><strong>WAJIB</strong> membawa surat ini selama menggunakan kendaraan</li>
            <li><strong>WAJIB</strong> mengembalikan kendaraan sesuai waktu yang ditentukan</li>
            <li><strong>WAJIB</strong> mengembalikan dalam kondisi bersih dan BBM terisi</li>
            <li><strong>BERTANGGUNG JAWAB</strong> penuh atas kerusakan atau kehilangan</li>
            <li><strong>SEGERA LAPOR</strong> ke Divisi Transportasi jika terjadi kecelakaan</li>
        </ul>
    </div>
    
    <!-- Tanda Tangan -->
    <div class="signature-section">
        <p class="text-center" style="margin-bottom: 20px;">
            <strong>PERSETUJUAN DAN KONFIRMASI</strong>
        </p>
        
        <div class="signature-row">
            <div class="signature-col">
                <p><strong>Bagian Transportasi</strong></p>
                <p style="font-size: 9pt; margin-top: 5px;">Menyetujui peminjaman</p>
                <div class="signature-box">
                    @if($peminjaman->ttd_transportasi)
                        <img src="{{ $peminjaman->ttd_transportasi }}" class="signature-image" alt="TTD Transportasi">
                    @else
                        <div style="min-height: 60px;"></div>
                    @endif
                </div>
                <p class="signature-name">(____________________)</p>
                <p class="signature-role">Divisi Transportasi</p>
            </div>
            
            <div class="signature-col">
                <p><strong>Peminjam</strong></p>
                <p style="font-size: 9pt; margin-top: 5px;">Setuju dengan ketentuan</p>
                <div class="signature-box">
                    @if($peminjaman->ttd_pinjam)
                        <img src="{{ $peminjaman->ttd_pinjam }}" class="signature-image" alt="TTD Peminjam">
                    @endif
                </div>
                <p class="signature-name">{{ $peminjaman->nama_peminjam }}</p>
                <p class="signature-role">Peminjam Kendaraan</p>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer-note">
        <p class="text-center"><strong>INFORMASI PENTING</strong></p>
        <p>✓ Simpan surat ini dengan baik sebagai bukti peminjaman yang sah</p>
        <p>✓ Tunjukkan surat ini jika diminta oleh petugas yang berwenang</p>
        <p>✓ Hubungi Divisi Transportasi jika ada kendala: (021) 1234567</p>
        <p style="margin-top: 10px; text-align: center; font-size: 8pt;">
            Dicetak: {{ now()->format('d/m/Y H:i') }} WIB | Kode: {{ $peminjaman->kode_peminjaman }}
        </p>
    </div>
</body>
</html>
