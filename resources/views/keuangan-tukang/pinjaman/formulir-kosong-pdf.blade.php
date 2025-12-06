<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Formulir Permohonan Pinjaman Karyawan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            padding: 15mm;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
        }
        
        .header h1 {
            font-size: 16pt;
            margin-bottom: 3px;
            letter-spacing: 1px;
        }
        
        .header h2 {
            font-size: 13pt;
            margin-bottom: 8px;
            font-weight: normal;
            text-decoration: underline;
        }
        
        .header p {
            font-size: 9pt;
            line-height: 1.3;
            margin: 1px 0;
        }
        
        .nomor-form {
            text-align: right;
            margin: 10px 0;
            font-size: 10pt;
        }
        
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .section-header {
            background-color: #e8e8e8;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11pt;
            border-left: 4px solid #333;
            margin-bottom: 10px;
        }
        
        .field-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .field-label {
            display: table-cell;
            width: 35%;
            padding: 5px;
            vertical-align: top;
            font-size: 10pt;
        }
        
        .field-separator {
            display: table-cell;
            width: 3%;
            text-align: center;
            vertical-align: top;
            padding: 5px 0;
        }
        
        .field-value {
            display: table-cell;
            width: 62%;
            padding: 5px;
            border-bottom: 1px solid #000;
            min-height: 25px;
            vertical-align: top;
        }
        
        .field-value.multiline {
            min-height: 60px;
            border: 1px solid #000;
        }
        
        .syarat-list {
            margin-left: 20px;
            font-size: 9.5pt;
        }
        
        .syarat-list li {
            margin-bottom: 5px;
            text-align: justify;
        }
        
        .pernyataan-list {
            margin-left: 20px;
            font-size: 9.5pt;
        }
        
        .pernyataan-list li {
            margin-bottom: 5px;
            text-align: justify;
        }
        
        .signature-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 15px;
        }
        
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-box {
            min-height: 80px;
            margin: 10px 20px;
            position: relative;
        }
        
        .signature-label {
            font-size: 10pt;
            margin-bottom: 10px;
        }
        
        .signature-name {
            margin-top: 10px;
            border-top: 1px solid #000;
            display: inline-block;
            min-width: 150px;
            padding-top: 5px;
            font-size: 10pt;
        }
        
        .approval-box {
            border: 2px solid #000;
            padding: 10px;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        
        .approval-header {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 5px;
        }
        
        .checkbox-item {
            margin: 8px 0;
            font-size: 10pt;
        }
        
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            vertical-align: middle;
            margin-right: 5px;
        }
        
        .stamp-box {
            border: 2px dashed #666;
            min-height: 70px;
            margin: 10px auto;
            width: 150px;
            text-align: center;
            padding-top: 25px;
            color: #999;
            font-size: 9pt;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>BUMI SULTAN</h1>
        <h2>FORMULIR PERMOHONAN PINJAMAN TUKANG</h2>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
        <p>Kabupaten Bogor, Jawa Barat 16830</p>
    </div>
    
    <div class="nomor-form">
        No. Formulir: PIN/{{ date('m') }}/{{ date('Y') }}/{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}
    </div>
    
    <!-- BAGIAN I: DATA PEMOHON -->
    <div class="section">
        <div class="section-header">BAGIAN I: DATA PEMOHON</div>
        
        <div class="field-row">
            <div class="field-label">Nama Lengkap</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Kode Tukang</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Nomor Telepon</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Alamat</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Tanggal Pengajuan</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
    </div>
    
    <!-- BAGIAN II: DETAIL PINJAMAN -->
    <div class="section">
        <div class="section-header">BAGIAN II: DETAIL PINJAMAN</div>
        
        <div class="field-row">
            <div class="field-label">Jumlah Pinjaman</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Terbilang</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Keperluan/Tujuan Pinjaman</div>
            <div class="field-separator">:</div>
            <div class="field-value multiline">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Jangka Waktu Pengembalian</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Cicilan Per Minggu</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
        
        <div class="field-row">
            <div class="field-label">Metode Pembayaran</div>
            <div class="field-separator">:</div>
            <div class="field-value">&nbsp;</div>
        </div>
    </div>
    
    <!-- BAGIAN III: SYARAT DAN KETENTUAN -->
    <div class="section">
        <div class="section-header">BAGIAN III: SYARAT DAN KETENTUAN</div>
        
        <ol class="syarat-list">
            <li>Pinjaman akan dipotong langsung dari gaji/upah mingguan tukang sesuai dengan jumlah cicilan yang telah disepakati.</li>
            <li>Apabila tukang mengundurkan diri atau diberhentikan sebelum pinjaman lunas, maka sisa pinjaman harus dilunasi pada saat penyelesaian.</li>
            <li>Pembayaran cicilan dilakukan setiap minggu pada hari pembagian gaji (Kamis) atau sesuai kesepakatan.</li>
            <li>Jika terjadi keterlambatan atau tidak bisa membayar cicilan, tukang wajib memberitahukan kepada bagian keuangan.</li>
            <li>Pinjaman yang telah disetujui harus digunakan sesuai dengan tujuan yang telah disebutkan dalam formulir.</li>
            <li>Tukang tidak diperkenankan mengajukan pinjaman baru sebelum pinjaman sebelumnya lunas, kecuali dengan persetujuan khusus.</li>
            <li>Perusahaan berhak menolak permohonan pinjaman tanpa memberikan alasan tertentu.</li>
        </ol>
    </div>
    
    <!-- BAGIAN IV: PERNYATAAN PEMOHON -->
    <div class="section">
        <div class="section-header">BAGIAN IV: PERNYATAAN PEMOHON</div>
        
        <p style="margin-bottom: 10px; font-size: 10pt; text-align: justify;">
            Dengan ini saya menyatakan bahwa:
        </p>
        
        <ol class="pernyataan-list">
            <li>Data yang saya berikan dalam formulir ini adalah benar dan dapat dipertanggungjawabkan.</li>
            <li>Saya telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan pinjaman tukang.</li>
            <li>Saya bersedia menerima konsekuensi apabila tidak dapat memenuhi kewajiban pembayaran cicilan.</li>
            <li>Saya memberikan izin kepada perusahaan untuk memotong gaji/upah saya sesuai dengan jumlah cicilan yang telah disepakati.</li>
        </ol>
    </div>
    
    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-col">
                <div class="signature-label">Pemohon/Tukang</div>
                <div class="signature-box">
                    <!-- Kosong untuk TTD manual -->
                </div>
                <div class="signature-name">
                    ( _________________________ )
                </div>
                <div style="font-size: 9pt; margin-top: 5px;">
                    Tanggal: ___/___/______
                </div>
            </div>
            
            <div class="signature-col">
                <div class="signature-label">Bagian Keuangan</div>
                <div class="signature-box">
                    <div class="stamp-box">
                        STEMPEL<br>PERUSAHAAN
                    </div>
                </div>
                <div class="signature-name">
                    ( _________________________ )
                </div>
                <div style="font-size: 9pt; margin-top: 5px;">
                    Tanggal: ___/___/______
                </div>
            </div>
        </div>
    </div>
    
    <!-- BAGIAN UNTUK BAGIAN KEUANGAN -->
    <div class="approval-box">
        <div class="approval-header">UNTUK BAGIAN KEUANGAN</div>
        
        <div style="margin: 10px 0;">
            <div class="checkbox-item">
                <span class="checkbox"></span> Disetujui
            </div>
            <div class="checkbox-item">
                <span class="checkbox"></span> Ditolak
            </div>
            <div class="checkbox-item">
                <span class="checkbox"></span> Perlu Pertimbangan Lebih Lanjut
            </div>
        </div>
        
        <div style="margin-top: 15px;">
            <div style="font-size: 10pt; margin-bottom: 5px;">Catatan/Keterangan:</div>
            <div style="border: 1px solid #000; min-height: 60px; padding: 5px;">
                &nbsp;
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Formulir ini dicetak pada: {{ $tanggal_cetak }}</p>
        <p>Sistem Manajemen Keuangan Tukang - BUMI SULTAN</p>
    </div>
</body>
</html>
