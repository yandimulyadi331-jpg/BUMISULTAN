<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pelanggaran Santri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 5px 0;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .badge-ringan {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
        }

        .badge-sedang {
            background-color: #ffc107;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
        }

        .badge-berat {
            background-color: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN REKAP PELANGGARAN SANTRI</h2>
        <h3>PONDOK PESANTREN BUMI SULTAN</h3>
        @if($startDate && $endDate)
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        @else
        <p>Seluruh Data</p>
        @endif
    </div>

    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i:s') }}</p>
        <p><strong>Total Santri Bermasalah:</strong> {{ $rekapSantri->count() }} santri</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">NIK</th>
                <th width="25%">Nama Santri</th>
                <th width="12%" class="text-center">Total Pelanggaran</th>
                <th width="10%" class="text-center">Total Point</th>
                <th width="15%" class="text-center">Status</th>
                <th width="18%" class="text-center">Pelanggaran Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapSantri as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->nik ?? '-' }}</td>
                <td>{{ $item->name }}</td>
                <td class="text-center">{{ $item->total_pelanggaran }}x</td>
                <td class="text-center">{{ $item->total_point }}</td>
                <td class="text-center">
                    @if($item->status == 'Berat')
                    <span class="badge-berat">{{ $item->status }}</span>
                    @elseif($item->status == 'Sedang')
                    <span class="badge-sedang">{{ $item->status }}</span>
                    @else
                    <span class="badge-ringan">{{ $item->status }}</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->pelanggaran_terakhir)
                    {{ \Carbon\Carbon::parse($item->pelanggaran_terakhir)->format('d/m/Y') }}
                    @else
                    -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p><strong>Keterangan Status:</strong></p>
        <ul>
            <li><span class="badge-ringan">Ringan</span> : Jumlah pelanggaran di bawah 35 kali</li>
            <li><span class="badge-sedang">Sedang</span> : Jumlah pelanggaran antara 35-74 kali</li>
            <li><span class="badge-berat">Berat</span> : Jumlah pelanggaran 75 kali atau lebih</li>
        </ul>
    </div>

    <div style="margin-top: 50px; float: right; text-align: center;">
        <p>Mengetahui,</p>
        <br><br><br>
        <p>(__________________________)</p>
        <p>Kepala Pondok</p>
    </div>
</body>

</html>
