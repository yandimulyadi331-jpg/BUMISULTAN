<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            padding: 15mm 15mm 15mm 15mm;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 10pt;
            margin: 3px 0;
        }

        .form-info {
            margin: 15px 0;
            padding: 10px;
            background: #f5f5f5;
            border: 1px solid #ccc;
        }

        .form-info table {
            width: 100%;
        }

        .form-info td {
            padding: 3px;
            font-size: 10pt;
        }

        .section-title {
            background: #667eea;
            color: white;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 15px;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .form-label {
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
            font-size: 10pt;
        }

        .form-field {
            border-bottom: 1px solid #000;
            min-height: 25px;
            padding: 3px 5px;
            width: 100%;
            display: block;
        }

        .form-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .form-col {
            display: table-cell;
            padding-right: 10px;
            vertical-align: top;
        }

        .form-col:last-child {
            padding-right: 0;
        }

        .form-col-6 {
            width: 50%;
        }

        .form-col-4 {
            width: 33.33%;
        }

        .form-col-8 {
            width: 66.67%;
        }

        .checkbox-group {
            margin: 5px 0;
        }

        .checkbox-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }

        .checkbox-box {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
        }

        .photo-box {
            border: 2px solid #000;
            width: 4cm;
            height: 6cm;
            float: right;
            text-align: center;
            padding-top: 2.5cm;
            font-size: 9pt;
            color: #666;
            margin-left: 15px;
            margin-bottom: 15px;
        }

        .instructions {
            background: #fffacd;
            border: 1px solid #ffd700;
            padding: 10px;
            margin: 15px 0;
            font-size: 9pt;
            overflow: hidden;
        }

        .instructions-content {
            float: left;
            width: calc(100% - 4.5cm);
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
        }

        .signature-box {
            border: 1px solid #000;
            height: 80px;
            margin: 10px 20px;
        }

        .notes-box {
            border: 1px solid #000;
            min-height: 80px;
            padding: 10px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table.data-table td {
            padding: 5px;
            vertical-align: top;
        }

        table.data-table td.label {
            width: 35%;
            font-weight: 600;
        }

        table.data-table td.colon {
            width: 3%;
            text-align: center;
        }

        table.data-table td.value {
            width: 62%;
            border-bottom: 1px dotted #000;
        }

        .instructions h4 {
            margin-bottom: 8px;
            color: #d2691e;
            margin-top: 0;
        }

        .instructions ul {
            margin-left: 20px;
            margin-bottom: 5px;
        }

        .instructions li {
            margin-bottom: 3px;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <h1>PONDOK PESANTREN SAUNG SANTRI</h1>
        <h2>FORMULIR PENDAFTARAN SANTRI BARU</h2>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830</p>
    </div>

    <!-- INFO FORMULIR -->
    <div class="form-info">
        <table>
            <tr>
                <td style="width: 70%;">
                    <strong>No. Formulir:</strong> {{ $no_formulir }}<br>
                    <strong>Tahun Ajaran:</strong> {{ $tahun }}/{{ $tahun + 1 }}
                </td>
                <td style="width: 30%; text-align: right;">
                    <strong>Tanggal Pengisian:</strong><br>
                    ......../......../..........
                </td>
            </tr>
        </table>
    </div>

    <!-- INSTRUKSI PENGISIAN dengan PAS FOTO -->
    <div class="instructions">
        <!-- PAS FOTO di kanan -->
        <div class="photo-box">
            <strong>PAS FOTO</strong><br>
            4 x 6 cm<br>
            <small>Latar Belakang Putih</small>
        </div>
        
        <div class="instructions-content">
            <h4>📋 PETUNJUK PENGISIAN FORMULIR:</h4>
            <ul>
                <li>Isi formulir dengan LENGKAP dan JELAS menggunakan huruf KAPITAL</li>
                <li>Gunakan tinta hitam atau biru untuk mengisi formulir</li>
                <li>Tempelkan pas foto terbaru ukuran 4x6 cm pada kotak yang telah disediakan</li>
                <li>Formulir yang sudah diisi diserahkan ke bagian pendaftaran bersama dokumen persyaratan</li>
                <li>Untuk informasi lebih lanjut hubungi: <strong>085715375490</strong></li>
            </ul>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- BAGIAN I: DATA PRIBADI SANTRI -->
    <div class="section-title">📝 BAGIAN I: DATA PRIBADI SANTRI</div>

    <table class="data-table">
        <tr>
            <td class="label">1. Nama Lengkap</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">2. Nama Panggilan</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">3. NIK (Nomor Induk Kependudukan)</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
    </table>

    <div class="form-group">
        <span class="form-label">4. Jenis Kelamin:</span>
        <div class="checkbox-group">
            <span class="checkbox-item">
                <span class="checkbox-box"></span> Laki-laki (L)
            </span>
            <span class="checkbox-item">
                <span class="checkbox-box"></span> Perempuan (P)
            </span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-col form-col-6">
            <span class="form-label">5. Tempat Lahir:</span>
            <span class="form-field"></span>
        </div>
        <div class="form-col form-col-6">
            <span class="form-label">6. Tanggal Lahir:</span>
            <span class="form-field">........../........../.............</span>
        </div>
    </div>

    <div class="form-group">
        <span class="form-label">7. Alamat Lengkap:</span>
        <span class="form-field"></span>
        <span class="form-field"></span>
    </div>

    <div class="form-row">
        <div class="form-col form-col-6">
            <span class="form-label">8. Provinsi:</span>
            <span class="form-field"></span>
        </div>
        <div class="form-col form-col-6">
            <span class="form-label">9. Kabupaten/Kota:</span>
            <span class="form-field"></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-col form-col-4">
            <span class="form-label">10. Kecamatan:</span>
            <span class="form-field"></span>
        </div>
        <div class="form-col form-col-4">
            <span class="form-label">11. Kelurahan/Desa:</span>
            <span class="form-field"></span>
        </div>
        <div class="form-col form-col-4">
            <span class="form-label">12. Kode Pos:</span>
            <span class="form-field"></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-col form-col-6">
            <span class="form-label">13. No. HP/WhatsApp:</span>
            <span class="form-field"></span>
        </div>
        <div class="form-col form-col-6">
            <span class="form-label">14. Email (jika ada):</span>
            <span class="form-field"></span>
        </div>
    </div>

    <!-- BAGIAN II: DATA ORANG TUA / WALI -->
    <div class="section-title" style="background: #764ba2;">👨‍👩‍👦 BAGIAN II: DATA ORANG TUA / WALI</div>

    <h4 style="margin: 10px 0; font-size: 11pt;">A. DATA AYAH</h4>
    <table class="data-table">
        <tr>
            <td class="label">15. Nama Lengkap Ayah</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">16. Pekerjaan Ayah</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">17. No. HP Ayah</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
    </table>

    <h4 style="margin: 10px 0; font-size: 11pt;">B. DATA IBU</h4>
    <table class="data-table">
        <tr>
            <td class="label">18. Nama Lengkap Ibu</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">19. Pekerjaan Ibu</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">20. No. HP Ibu</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
    </table>

    <h4 style="margin: 10px 0; font-size: 11pt;">C. DATA WALI (Jika Ada)</h4>
    <table class="data-table">
        <tr>
            <td class="label">21. Nama Lengkap Wali</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">22. Hubungan dengan Santri</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">23. No. HP Wali</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
    </table>

    <!-- PAGE BREAK -->
    <div style="page-break-after: always;"></div>

    <!-- HEADER HALAMAN 2 (Ringkas) -->
    <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px;">
        <h2 style="margin: 0; font-size: 14pt;">FORMULIR PENDAFTARAN SANTRI BARU - HALAMAN 2</h2>
        <p style="font-size: 9pt; margin: 5px 0;">No. Formulir: {{ $no_formulir }}</p>
    </div>

    <!-- BAGIAN III: DATA PENDIDIKAN -->
    <div class="section-title" style="background: #f39c12;">🎓 BAGIAN III: RIWAYAT PENDIDIKAN</div>

    <table class="data-table">
        <tr>
            <td class="label">24. Asal Sekolah</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">25. Tingkat Pendidikan Terakhir</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
    </table>

    <div class="form-group">
        <span class="form-label">26. Status Pendidikan Saat Mendaftar:</span>
        <div class="checkbox-group">
            <span class="checkbox-item">
                <span class="checkbox-box"></span> Aktif
            </span>
            <span class="checkbox-item">
                <span class="checkbox-box"></span> Cuti
            </span>
            <span class="checkbox-item">
                <span class="checkbox-box"></span> Alumni
            </span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-col form-col-6">
            <span class="form-label">27. Tahun Masuk yang Diinginkan:</span>
            <span class="form-field"></span>
        </div>
        <div class="form-col form-col-6">
            <span class="form-label">28. Tanggal Masuk yang Diinginkan:</span>
            <span class="form-field">........../........../.............</span>
        </div>
    </div>

    <!-- BAGIAN IV: DATA HAFALAN (KHUSUS TAHFIDZ) -->
    <div class="section-title" style="background: #27ae60;">📖 BAGIAN IV: DATA HAFALAN AL-QUR'AN (Jika Ada)</div>

    <table class="data-table">
        <tr>
            <td class="label">29. Jumlah Juz yang Sudah Dihafal</td>
            <td class="colon">:</td>
            <td class="value">............. Juz</td>
        </tr>
        <tr>
            <td class="label">30. Jumlah Halaman yang Sudah Dihafal</td>
            <td class="colon">:</td>
            <td class="value">............. Halaman</td>
        </tr>
        <tr>
            <td class="label">31. Target Hafalan</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">32. Tanggal Mulai Tahfidz</td>
            <td class="colon">:</td>
            <td class="value">........../........../.............</td>
        </tr>
    </table>

    <div class="form-group">
        <span class="form-label">33. Catatan Hafalan / Prestasi:</span>
        <div class="notes-box"></div>
    </div>

    <!-- BAGIAN V: PILIHAN ASRAMA -->
    <div class="section-title" style="background: #e74c3c;">🏠 BAGIAN V: PILIHAN ASRAMA & KAMAR</div>

    <table class="data-table">
        <tr>
            <td class="label">34. Nama Asrama yang Diinginkan</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">35. Pilihan Nomor Kamar (Jika Ada)</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">36. Nama Pembina yang Diinginkan</td>
            <td class="colon">:</td>
            <td class="value">&nbsp;</td>
        </tr>
    </table>

    <!-- BAGIAN VI: KETERANGAN TAMBAHAN -->
    <div class="section-title" style="background: #95a5a6;">📝 BAGIAN VI: KETERANGAN TAMBAHAN</div>

    <div class="form-group">
        <span class="form-label">37. Keterangan Kesehatan / Alergi / Kondisi Khusus:</span>
        <div class="notes-box"></div>
    </div>

    <div class="form-group">
        <span class="form-label">38. Motivasi Masuk Pondok Pesantren:</span>
        <div class="notes-box"></div>
    </div>

    <!-- PERNYATAAN -->
    <div style="border: 2px solid #000; padding: 15px; margin: 20px 0; background: #f9f9f9;">
        <h4 style="text-align: center; margin-bottom: 10px;">PERNYATAAN</h4>
        <p style="text-align: justify; font-size: 10pt; line-height: 1.5;">
            Dengan ini saya menyatakan bahwa semua data yang saya isi dalam formulir ini adalah <strong>BENAR</strong> 
            dan dapat dipertanggungjawabkan. Apabila dikemudian hari ditemukan ketidaksesuaian data, maka saya 
            bersedia menerima sanksi sesuai ketentuan yang berlaku di Pondok Pesantren Saung Santri.
        </p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-col">
                <p style="margin-bottom: 5px;">Orang Tua / Wali</p>
                <div class="signature-box"></div>
                <p style="margin-top: 5px;">
                    <strong>Nama:</strong> ................................<br>
                    <strong>Tanggal:</strong> ......../......../..........
                </p>
            </div>
            <div class="signature-col">
                <p style="margin-bottom: 5px;">Calon Santri</p>
                <div class="signature-box"></div>
                <p style="margin-top: 5px;">
                    <strong>Nama:</strong> ................................<br>
                    <strong>Tanggal:</strong> ......../......../..........
                </p>
            </div>
        </div>
    </div>

    <!-- BAGIAN PETUGAS (JANGAN DIISI) -->
    <div style="border: 2px dashed #999; padding: 15px; margin-top: 30px; background: #f0f0f0;">
        <h4 style="text-align: center; margin-bottom: 10px; color: #666;">
            ⚠️ BAGIAN INI DIISI OLEH PETUGAS PENDAFTARAN
        </h4>
        <table style="width: 100%; font-size: 10pt;">
            <tr>
                <td style="width: 25%; padding: 5px;"><strong>NIS Diberikan:</strong></td>
                <td style="border-bottom: 1px solid #666; padding: 5px;">.........................................................</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Status Pendaftaran:</strong></td>
                <td style="padding: 5px;">
                    <span class="checkbox-box"></span> Diterima &nbsp;&nbsp;
                    <span class="checkbox-box"></span> Ditolak &nbsp;&nbsp;
                    <span class="checkbox-box"></span> Cadangan
                </td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Petugas:</strong></td>
                <td style="border-bottom: 1px solid #666; padding: 5px;">.........................................................</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Tanggal Verifikasi:</strong></td>
                <td style="border-bottom: 1px solid #666; padding: 5px;">......../......../.............</td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p><strong>PONDOK PESANTREN SAUNG SANTRI</strong></p>
        <p>Mendidik dengan Hati, Membimbing dengan Cinta</p>
        <p style="font-size: 8pt; margin-top: 5px;">
            Formulir ini adalah dokumen resmi. Harap disimpan dengan baik.
        </p>
    </div>

</body>
</html>
