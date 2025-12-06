<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - Divisi Transportasi</title>
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
        
        .detail-box {
            border: 1px solid #000;
            padding: 15px;
            margin: 20px 0;
            background-color: #f9f9f9;
        }
        
        .detail-box h4 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            text-decoration: underline;
        }
        
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-box {
            margin-top: 10px;
            min-height: 80px;
        }
        
        .signature-image {
            max-width: 150px;
            max-height: 70px;
            margin: 10px auto;
        }
        
        .signature-name {
            margin-top: 10px;
            font-weight: bold;
            text-decoration: underline;
        }
        
        .signature-role {
            font-size: 10pt;
            font-style: italic;
        }
        
        .footer-note {
            margin-top: 30px;
            font-size: 9pt;
            font-style: italic;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .stamp-area {
            text-align: center;
            margin-top: 20px;
            font-size: 9pt;
            font-style: italic;
            color: #666;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #0d6efd;
            color: white;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-3 {
            margin-top: 15px;
        }
        
        .mb-3 {
            margin-bottom: 15px;
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
        No: {{ $peminjaman->kode_peminjaman }}/SJ-TRANS/{{ date('m/Y', strtotime($peminjaman->waktu_pinjam)) }}
    </div>
    
    <!-- Informasi Peminjaman -->
    <div class="info-section">
        <p>Yang bertanda tangan di bawah ini, Divisi Transportasi Yayasan Bumi Sultan menyatakan bahwa:</p>
    </div>
    
    <div class="detail-box">
        <h4>DATA PEMINJAMAN KENDARAAN</h4>
        
        <table class="info-table">
            <tr>
                <td class="label">Kode Peminjaman</td>
                <td class="separator">:</td>
                <td class="value"><span class="badge">{{ $peminjaman->kode_peminjaman }}</span></td>
            </tr>
            <tr>
                <td class="label">Tanggal Peminjaman</td>
                <td class="separator">:</td>
                <td class="value">{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Estimasi Pengembalian</td>
                <td class="separator">:</td>
                <td class="value">{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d F Y, H:i') }} WIB</td>
            </tr>
        </table>
    </div>
    
    <div class="detail-box">
        <h4>DATA KENDARAAN</h4>
        
        <table class="info-table">
            <tr>
                <td class="label">Kode Kendaraan</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $peminjaman->kendaraan->kode_kendaraan }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nama Kendaraan</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->kendaraan->nama_kendaraan }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kendaraan</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->kendaraan->jenis_kendaraan }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Polisi</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $peminjaman->kendaraan->no_polisi }}</strong></td>
            </tr>
            <tr>
                <td class="label">Warna</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->kendaraan->warna ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Pembuatan</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->kendaraan->tahun_pembuatan ?? '-' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="detail-box">
        <h4>DATA PEMINJAM</h4>
        
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
            @if($peminjaman->keterangan)
            <tr>
                <td class="label">Keterangan Tambahan</td>
                <td class="separator">:</td>
                <td class="value">{{ $peminjaman->keterangan }}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <!-- Ketentuan -->
    <div class="info-section mt-3">
        <p><strong>KETENTUAN PEMINJAMAN:</strong></p>
        <ol style="margin-left: 20px; margin-top: 10px;">
            <li>Peminjam wajib mengembalikan kendaraan sesuai estimasi waktu yang telah ditentukan</li>
            <li>Peminjam bertanggung jawab penuh atas kerusakan yang terjadi selama masa peminjaman</li>
            <li>Kendaraan harus dikembalikan dalam kondisi bersih dan bahan bakar terisi</li>
            <li>Apabila terjadi kecelakaan atau kehilangan, segera laporkan ke Divisi Transportasi</li>
            <li>Surat jalan ini wajib dibawa selama menggunakan kendaraan</li>
        </ol>
    </div>
    
    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-col">
                <p><strong>Bagian Transportasi</strong></p>
                <div class="signature-box">
                    @if($peminjaman->ttd_transportasi)
                        <img src="{{ $peminjaman->ttd_transportasi }}" class="signature-image" alt="Tanda Tangan Transportasi">
                    @else
                        <div style="min-height: 70px;"></div>
                    @endif
                </div>
                <p class="signature-name">(____________________)</p>
                <p class="signature-role">Penanggung Jawab Transportasi</p>
            </div>
            
            <div class="signature-col">
                <p><strong>Peminjam</strong></p>
                <div class="signature-box">
                    @if($peminjaman->ttd_pinjam)
                        <img src="{{ $peminjaman->ttd_pinjam }}" class="signature-image" alt="Tanda Tangan Peminjam">
                    @endif
                </div>
                <p class="signature-name">{{ $peminjaman->nama_peminjam }}</p>
                <p class="signature-role">Peminjam Kendaraan</p>
            </div>
        </div>
    </div>
    
    <div class="stamp-area">
        <p>[ STEMPEL DIVISI TRANSPORTASI ]</p>
    </div>
    
    <!-- Footer -->
    <div class="footer-note">
        <p><strong>CATATAN UNTUK DIVISI TRANSPORTASI:</strong></p>
        <p>- Dokumen ini merupakan arsip resmi peminjaman kendaraan</p>
        <p>- Simpan dengan baik sebagai bukti serah terima kendaraan</p>
        <p>- Lakukan pengecekan kondisi kendaraan saat pengembalian</p>
        <p>- Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB</p>
    </div>
</body>
</html>
