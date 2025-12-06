<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Pengajuan Pinjaman - Bumi Sultan</title>
    <style>
        @page {
            margin: 15mm 15mm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
        }
        
        /* KOP SURAT */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .kop-surat .company-name {
            font-size: 22pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 2px;
        }
        
        .kop-surat .tagline {
            font-size: 8pt;
            font-style: italic;
            margin: 2px 0;
            color: #555;
        }
        
        .kop-surat .contact-info {
            font-size: 8pt;
            margin: 5px 0 0 0;
            line-height: 1.3;
        }
        
        /* HEADER FORM */
        .form-header {
            background: #000;
            color: white;
            text-align: center;
            padding: 10px;
            margin: 15px 0;
        }
        
        .form-header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .form-header .subtitle {
            font-size: 9pt;
            margin-top: 3px;
        }
        
        /* NOMOR DAN TANGGAL */
        .doc-info {
            margin: 10px 0 15px 0;
            font-size: 9pt;
        }
        
        .doc-info table {
            width: 100%;
        }
        
        .doc-info td {
            padding: 3px 0;
        }
        
        .doc-info .label {
            width: 150px;
            font-weight: bold;
        }
        
        /* SECTION */
        .section {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #333;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
            border-left: 5px solid #000;
        }
        
        /* FORM TABLE */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        
        .form-table td {
            padding: 5px;
            vertical-align: top;
        }
        
        .form-table .label {
            width: 35%;
            font-weight: 600;
            padding-left: 10px;
        }
        
        .form-table .separator {
            width: 15px;
            text-align: center;
        }
        
        .form-table .field {
            border-bottom: 1px dotted #666;
            min-height: 20px;
            padding-left: 5px;
        }
        
        /* CHECKBOX */
        .checkbox-option {
            display: inline-block;
            margin-right: 25px;
        }
        
        .checkbox-box {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #333;
            margin-right: 6px;
            vertical-align: middle;
        }
        
        /* BOX INFO */
        .info-box {
            border: 2px solid #333;
            padding: 12px;
            margin: 15px 0;
            background: #f9f9f9;
        }
        
        .info-box .title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 8px;
            text-decoration: underline;
        }
        
        .info-box table {
            width: 100%;
        }
        
        .info-box td {
            padding: 5px;
        }
        
        .info-box .label {
            width: 40%;
            font-weight: bold;
        }
        
        /* PERNYATAAN */
        .declaration-box {
            border: 2px solid #000;
            padding: 12px;
            margin: 20px 0;
            background: #fff;
        }
        
        .declaration-box .title {
            font-weight: bold;
            font-size: 11pt;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .declaration-box .content {
            text-align: justify;
            line-height: 1.6;
        }
        
        /* PERSYARATAN */
        .requirements-box {
            border: 1px solid #666;
            padding: 10px;
            margin: 15px 0;
            background: #f5f5f5;
        }
        
        .requirements-box .title {
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .requirements-box ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        
        .requirements-box li {
            margin: 3px 0;
            line-height: 1.4;
        }
        
        /* SIGNATURE */
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        
        .signature-grid {
            display: table;
            width: 100%;
            margin-top: 25px;
        }
        
        .signature-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 8px;
        }
        
        .signature-cell .role {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 3px;
        }
        
        .signature-cell .desc {
            font-size: 8pt;
            color: #555;
            margin-bottom: 8px;
        }
        
        .signature-cell .sign-space {
            height: 70px;
            margin: 8px 0;
        }
        
        .signature-cell .name-line {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin: 0 auto;
            width: 140px;
            font-weight: bold;
            min-height: 18px;
        }
        
        .signature-cell .position {
            font-size: 8pt;
            margin-top: 3px;
        }
        
        /* FOOTER */
        .doc-footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 7pt;
            color: #666;
        }
        
        /* WATERMARK */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(0, 0, 0, 0.03);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">BUMI SULTAN</div>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="company-name">BUMI SULTAN</div>
        <div class="contact-info">
            Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol<br>
            Kabupaten Bogor, Jawa Barat 16830
        </div>
    </div>

    <!-- HEADER FORMULIR -->
    <div class="form-header">
        <h2>FORMULIR PENGAJUAN PINJAMAN</h2>
        <div class="subtitle">Loan Application Form</div>
    </div>

    <!-- NOMOR DAN TANGGAL -->
    <div class="doc-info">
        <table>
            <tr>
                <td class="label">Nomor Formulir</td>
                <td>: <strong>FORM-PNJ-{{ date('Ymd') }}-{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}</strong></td>
                <td style="text-align: right; width: 200px;">Tanggal: <strong>{{ date('d/m/Y') }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- SECTION A: INFORMASI PEMINJAM -->
    <div class="section">
        <div class="section-title">A. INFORMASI PEMINJAM (Borrower Information)</div>
        
        <table class="form-table">
            <tr>
                <td class="label">Kategori Peminjam</td>
                <td class="separator">:</td>
                <td>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> <strong>CREW / KARYAWAN</strong>
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> <strong>NON-CREW / UMUM</strong>
                    </span>
                </td>
            </tr>
        </table>
        
        <table class="form-table">
            <tr>
                <td class="label">Nama Lengkap (Sesuai KTP)</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">NIK / No. Karyawan</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="separator">:</td>
                <td>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Laki-laki
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Perempuan
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Status Pernikahan</td>
                <td class="separator">:</td>
                <td>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Belum Menikah
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Menikah
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Cerai
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">No. Telepon / HP</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Alamat Lengkap (Sesuai KTP)</td>
                <td class="separator">:</td>
                <td class="field" style="min-height: 35px;"></td>
            </tr>
            <tr>
                <td class="label">Alamat Domisili (Jika Berbeda)</td>
                <td class="separator">:</td>
                <td class="field" style="min-height: 35px;"></td>
            </tr>
            <tr>
                <td class="label">Pekerjaan / Jabatan</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Lama Bekerja</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
        </table>
    </div>

    <!-- SECTION B: DETAIL PINJAMAN -->
    <div class="section">
        <div class="section-title">B. DETAIL PINJAMAN YANG DIAJUKAN (Loan Details)</div>
        
        <div class="info-box">
            <table>
                <tr>
                    <td class="label">Jumlah Pinjaman yang Diajukan</td>
                    <td>:</td>
                    <td><strong>Rp</strong> <span class="field" style="display: inline-block; width: 300px;"></span></td>
                </tr>
                <tr>
                    <td class="label">Terbilang</td>
                    <td>:</td>
                    <td class="field" style="font-style: italic;"></td>
                </tr>
                <tr>
                    <td class="label">Jangka Waktu (Tenor)</td>
                    <td>:</td>
                    <td><span class="field" style="display: inline-block; width: 80px;"></span> <strong>Bulan</strong></td>
                </tr>
                <tr>
                    <td class="label">Cicilan per Bulan</td>
                    <td>:</td>
                    <td><strong>Rp</strong> <span class="field" style="display: inline-block; width: 300px;"></span></td>
                </tr>
                <tr>
                    <td class="label">Tanggal Jatuh Tempo Setiap Bulan</td>
                    <td>:</td>
                    <td>Tanggal <span class="field" style="display: inline-block; width: 80px;"></span> <strong>setiap bulannya</strong></td>
                </tr>
                <tr>
                    <td class="label">Tujuan Penggunaan Pinjaman</td>
                    <td>:</td>
                    <td class="field" style="min-height: 30px;"></td>
                </tr>
            </table>
        </div>
        
        <table class="form-table">
            <tr>
                <td class="label">Metode Pembayaran Cicilan</td>
                <td class="separator">:</td>
                <td>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Potong Gaji Otomatis
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Transfer Bank
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Tunai
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- SECTION C: DATA JAMINAN -->
    <div class="section">
        <div class="section-title">C. DATA JAMINAN / AGUNAN (Collateral Information) - Jika Ada</div>
        
        <table class="form-table">
            <tr>
                <td class="label">Jenis Jaminan</td>
                <td class="separator">:</td>
                <td>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> BPKB Kendaraan
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Sertifikat Tanah/Rumah
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Elektronik
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Lainnya
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Deskripsi Jaminan</td>
                <td class="separator">:</td>
                <td class="field" style="min-height: 30px;"></td>
            </tr>
            <tr>
                <td class="label">Nomor / Identitas Jaminan</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Estimasi Nilai Jaminan</td>
                <td class="separator">:</td>
                <td><strong>Rp</strong> <span class="field" style="display: inline-block; width: 250px;"></span></td>
            </tr>
            <tr>
                <td class="label">Atas Nama</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Kondisi Jaminan</td>
                <td class="separator">:</td>
                <td>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Baru
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Bekas (Baik)
                    </span>
                    <span class="checkbox-option">
                        <span class="checkbox-box"></span> Bekas (Cukup)
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Keterangan Tambahan</td>
                <td class="separator">:</td>
                <td class="field" style="min-height: 25px;"></td>
            </tr>
        </table>
    </div>

    <!-- SECTION D: DATA PENJAMIN -->
    <div class="section">
        <div class="section-title">D. DATA PENJAMIN / REFERENSI (Guarantor Information) - Opsional</div>
        
        <table class="form-table">
            <tr>
                <td class="label">Nama Lengkap Penjamin</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Hubungan dengan Peminjam</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">No. Telepon Penjamin</td>
                <td class="separator">:</td>
                <td class="field"></td>
            </tr>
            <tr>
                <td class="label">Alamat Penjamin</td>
                <td class="separator">:</td>
                <td class="field" style="min-height: 30px;"></td>
            </tr>
        </table>
    </div>

    <!-- PERSYARATAN DOKUMEN -->
    <div class="requirements-box">
        <div class="title">PERSYARATAN DOKUMEN YANG HARUS DILAMPIRKAN:</div>
        <ul>
            <li><strong>Untuk Crew/Karyawan:</strong> Fotocopy KTP, Slip Gaji 3 bulan terakhir, Surat Keterangan Kerja</li>
            <li><strong>Untuk Non-Crew:</strong> Fotocopy KTP, Kartu Keluarga, NPWP (jika ada), Slip Gaji/Bukti Penghasilan, Rekening Koran 3 bulan terakhir</li>
            <li>Dokumen Pendukung Lainnya (jika diperlukan): Surat Kepemilikan Aset, Bukti Pembayaran Tagihan, dll</li>
        </ul>
    </div>

    <!-- PERNYATAAN -->
    <div class="declaration-box">
        <div class="title">PERNYATAAN DAN KESANGGUPAN</div>
        <div class="content">
            Dengan ini saya yang bertanda tangan di bawah ini menyatakan dengan sesungguhnya bahwa:
            <ol style="margin: 10px 0; padding-left: 25px;">
                <li>Seluruh data dan informasi yang saya berikan dalam formulir ini adalah <strong>BENAR, LENGKAP, dan AKURAT</strong>.</li>
                <li>Saya <strong>SANGGUP dan BERSEDIA</strong> membayar cicilan pinjaman sesuai dengan jadwal yang telah ditetapkan oleh perusahaan.</li>
                <li>Saya memahami dan <strong>MENYETUJUI</strong> semua syarat dan ketentuan pinjaman yang berlaku di BUMI SULTAN.</li>
                <li>Apabila terjadi perubahan data (alamat, no. telepon, status pekerjaan, dll), saya akan <strong>SEGERA MELAPORKAN</strong> kepada pihak perusahaan.</li>
                <li>Saya bersedia menerima <strong>KONSEKUENSI</strong> sesuai ketentuan yang berlaku apabila terjadi keterlambatan atau pelanggaran dalam pembayaran.</li>
            </ol>
            Pernyataan ini dibuat dengan penuh kesadaran dan tanpa paksaan dari pihak manapun.
        </div>
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div style="text-align: right; margin-bottom: 8px; font-size: 10pt;">
            Jonggol, _________________________ 2025
        </div>
        
        <div class="signature-grid">
            <div class="signature-cell">
                <div class="role">PEMINJAM</div>
                <div class="desc">(Applicant)</div>
                <div class="sign-space"></div>
                <div class="name-line"></div>
                <div class="position">Nama Lengkap & Tanda Tangan</div>
            </div>
            
            <div class="signature-cell">
                <div class="role">MENYETUJUI</div>
                <div class="desc">(Approved By)</div>
                <div class="sign-space"></div>
                <div class="name-line"></div>
                <div class="position">Bagian Keuangan</div>
            </div>
            
            <div class="signature-cell">
                <div class="role">MENGETAHUI</div>
                <div class="desc">(Acknowledged By)</div>
                <div class="sign-space"></div>
                <div class="name-line"></div>
                <div class="position">PIMPINAN</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="doc-footer">
        <strong>PT BUMI SULTAN</strong><br>
        Formulir ini merupakan dokumen resmi dan harus diisi dengan lengkap dan benar.<br>
        Setelah disetujui, dokumen ini memiliki kekuatan hukum yang mengikat kedua belah pihak.<br>
        © 2025 Bumi Sultan. All Rights Reserved.
    </div>
</body>
</html>
