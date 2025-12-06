<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Ijin Santri - {{ $ijinSantri->santri->nama_lengkap }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm 1.5cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 3px double #000;
        }

        .header h1 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 18pt;
            font-weight: bold;
            margin: 3px 0;
            padding: 0;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 9pt;
            margin: 1px 0;
            padding: 0;
            line-height: 1.3;
        }

        .nomor-surat {
            text-align: center;
            margin: 12px 0;
        }

        .nomor-surat u {
            font-weight: bold;
        }

        .content {
            text-align: justify;
            margin: 10px 0;
            font-size: 10pt;
        }

        .content p {
            margin: 5px 0;
        }

        .data-santri {
            margin: 10px 0 10px 25px;
        }

        .data-santri table {
            width: 100%;
        }

        .data-santri td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10pt;
        }

        .data-santri td:first-child {
            width: 170px;
        }

        .data-santri td:nth-child(2) {
            width: 12px;
        }

        .ttd-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .ttd-container {
            display: table;
            width: 100%;
        }

        .ttd-box {
            display: table-cell;
            text-align: center;
            vertical-align: top;
            width: 33%;
        }

        .ttd-box p {
            margin: 3px 0;
            font-size: 9pt;
        }

        .ttd-space {
            height: 50px;
            margin: 5px 0;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer-note {
            margin-top: 12px;
            font-size: 8pt;
            font-style: italic;
            page-break-inside: avoid;
        }
        
        .footer-note ul {
            margin: 3px 0;
            padding-left: 18px;
        }
        
        .footer-note li {
            margin: 2px 0;
        }

        strong {
            font-weight: bold;
        }

        u {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header Kop Surat -->
    <div class="header">
        <h1>PONDOK PESANTREN</h1>
        <h2>SAUNG SANTRI</h2>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830</p>
        <p>Telp: (021) 87989999 | Email: info@saungan tri.com</p>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        <p>
            <strong>SURAT IJIN KEPULANGAN SANTRI</strong><br>
            Nomor: <u>{{ $ijinSantri->nomor_surat }}</u>
        </p>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Assalamu'alaikum Wr. Wb.</p>
        
        <p>Yang bertanda tangan di bawah ini, Pengurus Pondok Pesantren Saung Santri, dengan ini menerangkan bahwa:</p>

        <div class="data-santri">
            <table>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><strong>{{ $ijinSantri->santri->nama_lengkap }}</strong></td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <td><strong>{{ $ijinSantri->santri->nis }}</strong></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $ijinSantri->santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ $ijinSantri->santri->tempat_lahir }}, {{ \Carbon\Carbon::parse($ijinSantri->santri->tanggal_lahir)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $ijinSantri->santri->alamat_lengkap ?? '-' }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Tanggal Ijin</td>
                    <td>:</td>
                    <td><strong>{{ $ijinSantri->tanggal_ijin->format('d F Y') }}</strong></td>
                </tr>
                <tr>
                    <td>Rencana Kembali</td>
                    <td>:</td>
                    <td><strong>{{ $ijinSantri->tanggal_kembali_rencana->format('d F Y') }}</strong></td>
                </tr>
                <tr>
                    <td>Keperluan/Alasan</td>
                    <td>:</td>
                    <td>{{ $ijinSantri->alasan_ijin }}</td>
                </tr>
            </table>
        </div>

        <p>
            Adalah benar santri dari Pondok Pesantren Saung Santri yang meminta ijin untuk pulang ke rumah. 
            Surat ijin ini berlaku pada tanggal yang tertera di atas.
        </p>

        <p>
            Demikian surat ijin ini dibuat untuk dapat dipergunakan sebagaimana mestinya. 
            Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
        </p>

        <p>Wassalamu'alaikum Wr. Wb.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd-section">
        <div class="ttd-container">
            <div class="ttd-box">
                <p>Mengetahui,</p>
                <p><strong>Pengurus Pondok</strong></p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $ijinSantri->creator->name }}</p>
                <p>Pengurus</p>
            </div>

            <div class="ttd-box">
                <p>Menyetujui,</p>
                <p><strong>Ustadz Pembimbing</strong></p>
                <div class="ttd-space">
                    <p style="margin-top: 20px; color: #999; font-size: 8pt;">(Tanda tangan & cap)</p>
                </div>
                <p class="ttd-name">___________________</p>
                <p>Ustadz</p>
            </div>

            <div class="ttd-box">
                <p>Mengetahui,</p>
                <p><strong>Orang Tua/Wali</strong></p>
                <div class="ttd-space">
                    <p style="margin-top: 20px; color: #999; font-size: 8pt;">(Tanda tangan)</p>
                </div>
                <p class="ttd-name">{{ $ijinSantri->santri->nama_ayah ?? $ijinSantri->santri->nama_wali ?? '___________________' }}</p>
                <p>Orang Tua/Wali</p>
            </div>
        </div>
    </div>

    <!-- Catatan Penting -->
    <div class="footer-note">
        <p><strong>Catatan Penting:</strong></p>
        <ul>
            <li>Surat ini harus dibawa santri dan ditandatangani oleh Ustadz sebelum kepulangan</li>
            <li>Surat ini harus ditandatangani oleh Orang Tua/Wali selama di rumah</li>
            <li>Surat ini harus dikembalikan ke pengurus saat santri kembali ke pesantren</li>
            <li>Santri harus kembali sesuai tanggal yang telah ditentukan</li>
        </ul>
    </div>

    <!-- Footer -->
    <div style="margin-top: 10px; text-align: right; font-size: 8pt; color: #666;">
        <p>Dicetak tanggal: {{ now()->format('d F Y H:i') }} WIB</p>
    </div>
</body>
</html>
