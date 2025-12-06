<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran Tukang - {{ $bulanNama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .kop-surat {
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat .logo {
            text-align: center;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 24px;
            color: #1e3a5f;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .kop-surat .alamat {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-top: 5px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #1e3a5f;
            color: white;
            padding: 8px 5px;
            text-align: center;
            font-size: 10px;
            border: 1px solid #ddd;
        }
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-left {
            text-align: left !important;
        }
        .text-right {
            text-align: right !important;
        }
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        .footer-total {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-secondary { background-color: #6c757d; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="logo">
            <h1>BUMI SULTAN</h1>
        </div>
        <div class="alamat">
            Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol<br>
            Kabupaten Bogor, Jawa Barat 16830
        </div>
    </div>

    <div class="header">
        <h2>REKAP KEHADIRAN TUKANG</h2>
        <p>{{ $bulanNama }}</p>
        <p>Dicetak: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">No</th>
                <th rowspan="2" style="width: 7%;">Kode</th>
                <th rowspan="2" style="width: 15%;">Nama Tukang</th>
                <th rowspan="2" style="width: 10%;">Tarif/Hari</th>
                <th colspan="3" style="background-color: #28a745;">Kehadiran</th>
                <th colspan="4" style="background-color: #dc3545;">Lembur</th>
                <th rowspan="2" style="width: 12%;">Total Upah</th>
            </tr>
            <tr>
                <th style="width: 5%; background-color: #28a745; color: white;">Hadir</th>
                <th style="width: 5%; background-color: #ffc107; color: #000;">1/2 Hari</th>
                <th style="width: 5%; background-color: #6c757d; color: white;">Alfa</th>
                <th style="width: 5%; background-color: #dc3545; color: white;">L.Full</th>
                <th style="width: 5%; background-color: #fd7e14; color: white;">L.1/2</th>
                <th style="width: 5%; background-color: #20c997; color: white;">🔥 Full</th>
                <th style="width: 5%; background-color: #17a2b8; color: white;">🔥 1/2</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalKeseluruhan = 0;
                $totalHadir = 0;
                $totalSetengah = 0;
                $totalAlfa = 0;
                $totalLemburFull = 0;
                $totalLemburSetengah = 0;
                $totalLemburFullCash = 0;
                $totalLemburSetengahCash = 0;
            @endphp
            
            @foreach($tukangs as $index => $tukang)
                @php
                    $totalKeseluruhan += $tukang->total_upah;
                    $totalHadir += $tukang->total_hadir;
                    $totalSetengah += $tukang->total_setengah_hari;
                    $totalAlfa += $tukang->total_tidak_hadir;
                    $totalLemburFull += $tukang->total_lembur_full;
                    $totalLemburSetengah += $tukang->total_lembur_setengah;
                    $totalLemburFullCash += $tukang->total_lembur_full_cash;
                    $totalLemburSetengahCash += $tukang->total_lembur_setengah_cash;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $tukang->kode_tukang }}</strong></td>
                    <td class="text-left">{{ $tukang->nama_tukang }}</td>
                    <td class="text-right">Rp {{ number_format($tukang->tarif_harian, 0, ',', '.') }}</td>
                    <td><span class="badge badge-success">{{ $tukang->total_hadir }}</span></td>
                    <td><span class="badge badge-warning">{{ $tukang->total_setengah_hari }}</span></td>
                    <td><span class="badge badge-secondary">{{ $tukang->total_tidak_hadir }}</span></td>
                    <td><span class="badge badge-danger">{{ $tukang->total_lembur_full }}</span></td>
                    <td><span class="badge badge-warning">{{ $tukang->total_lembur_setengah }}</span></td>
                    <td><span class="badge badge-success">{{ $tukang->total_lembur_full_cash }}</span></td>
                    <td><span class="badge badge-info">{{ $tukang->total_lembur_setengah_cash }}</span></td>
                    <td class="text-right text-success">Rp {{ number_format($tukang->total_upah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="footer-total">
                <td colspan="4" class="text-right">TOTAL KESELURUHAN:</td>
                <td>{{ $totalHadir }}</td>
                <td>{{ $totalSetengah }}</td>
                <td>{{ $totalAlfa }}</td>
                <td>{{ $totalLemburFull }}</td>
                <td>{{ $totalLemburSetengah }}</td>
                <td>{{ $totalLemburFullCash }}</td>
                <td>{{ $totalLemburSetengahCash }}</td>
                <td class="text-right text-success">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; font-size: 10px;">
        <p><strong>Keterangan:</strong></p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li><strong>Hadir:</strong> Tukang hadir full day (8 jam kerja)</li>
            <li><strong>1/2 Hari:</strong> Tukang hadir setengah hari (4 jam kerja, upah 50%)</li>
            <li><strong>Alfa:</strong> Tukang tidak hadir tanpa keterangan</li>
            <li><strong>L.Full/L.1/2:</strong> Lembur normal (dibayar hari Kamis bersama gaji mingguan)</li>
            <li><strong>🔥 Full/🔥 1/2:</strong> Lembur CASH (dibayar hari yang sama, tidak termasuk dalam total upah)</li>
        </ul>
    </div>
</body>
</html>
