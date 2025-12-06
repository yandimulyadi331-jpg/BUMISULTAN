<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Peringatan - {{ $santri->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat h1 {
            font-size: 24pt;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .kop-surat h2 {
            font-size: 18pt;
            font-weight: bold;
            margin: 5px 0;
            color: #2c5f2d;
        }

        .kop-surat p {
            font-size: 10pt;
            margin: 3px 0;
            line-height: 1.4;
        }

        .nomor-surat {
            margin: 20px 0;
        }

        .nomor-surat table {
            width: 100%;
        }

        .nomor-surat td {
            padding: 3px 0;
        }

        .nomor-surat td:first-child {
            width: 120px;
        }

        .nomor-surat td:nth-child(2) {
            width: 10px;
        }

        .judul-surat {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 30px 0 20px 0;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .isi-surat {
            text-align: justify;
            margin: 20px 0;
        }

        .isi-surat p {
            margin: 15px 0;
            text-indent: 50px;
        }

        .data-santri {
            margin: 20px 0 20px 50px;
        }

        .data-santri table {
            width: 100%;
        }

        .data-santri td {
            padding: 5px 0;
        }

        .data-santri td:first-child {
            width: 180px;
        }

        .data-santri td:nth-child(2) {
            width: 10px;
        }

        .riwayat-pelanggaran {
            margin: 20px 0;
        }

        .riwayat-pelanggaran h4 {
            margin: 20px 0 10px 0;
            font-size: 12pt;
        }

        .riwayat-pelanggaran table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .riwayat-pelanggaran table,
        .riwayat-pelanggaran th,
        .riwayat-pelanggaran td {
            border: 1px solid #000;
        }

        .riwayat-pelanggaran th,
        .riwayat-pelanggaran td {
            padding: 8px;
            text-align: left;
        }

        .riwayat-pelanggaran th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .status-berat {
            color: #dc3545;
            font-weight: bold;
        }

        .penutup {
            margin: 30px 0;
        }

        .ttd {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 250px;
        }

        .ttd-space {
            margin: 80px 0 5px 0;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer {
            clear: both;
            margin-top: 100px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
        }

        .badge-berat {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <h1>PONDOK PESANTREN</h1>
        <h2>SAUNG SANTRI</h2>
        <p>Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol</p>
        <p>Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830</p>
        <p>Telp: (021) 1234567 | Email: info@saungsantri.ac.id</p>
    </div>

    <!-- NOMOR SURAT -->
    <div class="nomor-surat">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td><strong>{{ $nomorSurat }}</strong></td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>Surat Peringatan</strong></td>
            </tr>
        </table>
    </div>

    <!-- JUDUL -->
    <div class="judul-surat">
        SURAT PERINGATAN
    </div>

    <!-- ISI SURAT -->
    <div class="isi-surat">
        <p>
            Assalamu'alaikum Warahmatullahi Wabarakatuh
        </p>

        <p>
            Dengan ini kami sampaikan surat peringatan kepada santri yang bersangkutan atas pelanggaran tata tertib
            pondok pesantren yang telah dilakukan secara berulang kali.
        </p>
    </div>

    <!-- DATA SANTRI -->
    <div class="data-santri">
        <table>
            <tr>
                <td>Nama Santri</td>
                <td>:</td>
                <td><strong>{{ $santri->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NIK/No. Induk</td>
                <td>:</td>
                <td>{{ $santri->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Total Pelanggaran</td>
                <td>:</td>
                <td><strong class="status-berat">{{ $totalPelanggaran }} kali</strong></td>
            </tr>
            <tr>
                <td>Total Point Pelanggaran</td>
                <td>:</td>
                <td><strong>{{ $totalPoint }} point</strong></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>
                    <span class="badge badge-berat">{{ $statusInfo['status'] }}</span>
                </td>
            </tr>
            <tr>
                <td>Tanggal Surat</td>
                <td>:</td>
                <td>{{ $tanggalSurat }}</td>
            </tr>
        </table>
    </div>

    <!-- RIWAYAT PELANGGARAN -->
    <div class="riwayat-pelanggaran">
        <h4>Riwayat Pelanggaran Terakhir (10 Pelanggaran):</h4>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="60%">Keterangan Pelanggaran</th>
                    <th width="10%">Point</th>
                    <th width="10%">Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayatPelanggaran as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_pelanggaran->format('d/m/Y') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td style="text-align: center;">{{ $item->point }}</td>
                    <td>{{ $item->pencatat->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- PENUTUP -->
    <div class="isi-surat penutup">
        <p>
            Dengan melihat catatan pelanggaran yang telah mencapai <strong
                class="status-berat">{{ $totalPelanggaran }} kali</strong>, kami memberikan surat peringatan ini
            sebagai bentuk perhatian serius terhadap pelanggaran tata tertib pondok pesantren.
        </p>

        <p>
            Kami mengharapkan yang bersangkutan untuk:
        </p>
        <ol style="margin-left: 70px;">
            <li>Memperbaiki sikap dan perilaku</li>
            <li>Mentaati seluruh peraturan pondok pesantren</li>
            <li>Menjadi teladan bagi santri lainnya</li>
            <li>Meningkatkan kedisiplinan dalam beribadah dan belajar</li>
        </ol>

        <p>
            Apabila pelanggaran terus berlanjut, pihak pondok pesantren akan mengambil tindakan lebih lanjut sesuai
            dengan peraturan yang berlaku, termasuk namun tidak terbatas pada pemanggilan orang tua/wali santri atau
            tindakan administratif lainnya.
        </p>

        <p>
            Demikian surat peringatan ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.
        </p>

        <p>
            Wassalamu'alaikum Warahmatullahi Wabarakatuh
        </p>
    </div>

    <!-- TTD -->
    <div class="ttd">
        <p>Jonggol, {{ $tanggalSurat }}</p>
        <p><strong>Kepala Pondok Pesantren</strong></p>
        <p><strong>Saung Santri</strong></p>
        <div class="ttd-space"></div>
        <p class="ttd-name">_________________________</p>
        <p>Pengasuh Pondok</p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Saung Santri Management System</p>
        <p>Tanggal Cetak: {{ now()->locale('id')->translatedFormat('d F Y H:i:s') }}</p>
    </div>

    <!-- TEMBUSAN -->
    <div style="clear: both; margin-top: 20px; font-size: 10pt;">
        <p><strong>Tembusan:</strong></p>
        <ol style="margin-left: 20px;">
            <li>Arsip Pondok Pesantren</li>
            <li>Orang Tua/Wali Santri</li>
            <li>Santri yang bersangkutan</li>
        </ol>
    </div>
</body>

</html>
