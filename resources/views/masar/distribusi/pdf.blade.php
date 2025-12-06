<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Distribusi Hadiah Yayasan Masar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .info-section {
            margin-bottom: 20px;
            font-size: 12px;
        }
        .info-section p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        table thead {
            background-color: #f5f5f5;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }
        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border-right: 1px solid #ddd;
        }
        table td {
            padding: 8px;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-primary { background-color: #007bff; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN DISTRIBUSI HADIAH</h1>
        <p>YAYASAN MASAR</p>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <p><strong>Total Distribusi:</strong> {{ count($distribusi) }} data</p>
        <p><strong>Periode:</strong> 
            @if($distribusi->isNotEmpty())
                {{ $distribusi->min('tanggal_distribusi')->format('d-m-Y') }} s/d {{ $distribusi->max('tanggal_distribusi')->format('d-m-Y') }}
            @else
                -
            @endif
        </p>
    </div>

    <!-- Data Table -->
    @if($distribusi->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Nomor Distribusi</th>
                    <th style="width: 15%;">Nama Jamaah</th>
                    <th style="width: 12%;">Hadiah</th>
                    <th style="width: 8%;">Jumlah</th>
                    <th style="width: 10%;">Metode</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 18%;">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distribusi as $key => $d)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $d->nomor_distribusi }}</td>
                        <td>{{ $d->jamaah?->nama_jamaah ?? 'N/A' }}</td>
                        <td>{{ $d->hadiah->nama_hadiah }}</td>
                        <td style="text-align: center;">{{ $d->jumlah }}</td>
                        <td>
                            @if($d->metode_distribusi === 'langsung')
                                <span class="badge badge-primary">Langsung</span>
                            @elseif($d->metode_distribusi === 'undian')
                                <span class="badge badge-info">Undian</span>
                            @elseif($d->metode_distribusi === 'prestasi')
                                <span class="badge badge-success">Prestasi</span>
                            @else
                                <span class="badge badge-warning">Kehadiran</span>
                            @endif
                        </td>
                        <td>
                            @if($d->status_distribusi === 'diterima')
                                <span class="badge badge-success">Diterima</span>
                            @elseif($d->status_distribusi === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>{{ $d->tanggal_distribusi->format('d-m-Y') }}</td>
                        <td>{{ $d->petugas_distribusi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary">
            <div class="summary-row">
                <span><strong>Total Distribusi:</strong></span>
                <span>{{ count($distribusi) }} data</span>
            </div>
            <div class="summary-row">
                <span><strong>Total Hadiah Diterima:</strong></span>
                <span>{{ $distribusi->where('status_distribusi', 'diterima')->count() }} distribusi</span>
            </div>
            <div class="summary-row">
                <span><strong>Total Hadiah Pending:</strong></span>
                <span>{{ $distribusi->where('status_distribusi', 'pending')->count() }} distribusi</span>
            </div>
            <div class="summary-row">
                <span><strong>Total Hadiah Ditolak:</strong></span>
                <span>{{ $distribusi->where('status_distribusi', 'ditolak')->count() }} distribusi</span>
            </div>
            <div class="summary-row">
                <span><strong>Total Jumlah Hadiah:</strong></span>
                <span>{{ $distribusi->sum('jumlah') }} pcs</span>
            </div>
        </div>
    @else
        <p style="text-align: center; margin-top: 20px; color: #666;">Tidak ada data distribusi hadiah.</p>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'System' }}</p>
        <p>Tanggal: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
