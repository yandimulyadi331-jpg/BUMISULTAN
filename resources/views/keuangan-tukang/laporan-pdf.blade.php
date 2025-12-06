<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan Tukang - {{ $bulanNama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #333;
        }
        
        .header h1 {
            font-size: 18pt;
            margin-bottom: 3px;
            color: #333;
        }
        
        .header h2 {
            font-size: 14pt;
            margin-bottom: 5px;
            color: #666;
        }
        
        .header p {
            font-size: 9pt;
            color: #666;
            margin: 2px 0;
        }
        
        .info-periode {
            text-align: center;
            margin: 15px 0;
            padding: 8px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .info-periode h3 {
            font-size: 12pt;
            color: #333;
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        table th {
            background-color: #333;
            color: white;
            padding: 8px 5px;
            font-size: 9pt;
            border: 1px solid #333;
            text-align: center;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ccc;
            font-size: 8.5pt;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        
        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }
        
        .text-primary {
            color: #007bff;
            font-weight: bold;
        }
        
        .text-warning {
            color: #ffc107;
            font-weight: bold;
        }
        
        tfoot {
            background-color: #f0f0f0;
        }
        
        tfoot th {
            background-color: #666;
            color: white;
            padding: 8px 5px;
            font-size: 9pt;
            border: 1px solid #666;
        }
        
        .summary-boxes {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        
        .summary-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 2px solid #ccc;
            margin: 0 5px;
        }
        
        .summary-box.success {
            background-color: #d4edda;
            border-color: #28a745;
        }
        
        .summary-box.danger {
            background-color: #f8d7da;
            border-color: #dc3545;
        }
        
        .summary-box.primary {
            background-color: #d1ecf1;
            border-color: #007bff;
        }
        
        .summary-box.warning {
            background-color: #fff3cd;
            border-color: #ffc107;
        }
        
        .summary-box h4 {
            font-size: 9pt;
            margin-bottom: 5px;
            color: #666;
        }
        
        .summary-box h2 {
            font-size: 12pt;
            margin: 0;
            color: #333;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 2px solid #ccc;
            text-align: right;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMI SULTAN</h1>
        <h2>LAPORAN KEUANGAN TUKANG</h2>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
        <p>Kabupaten Bogor, Jawa Barat 16830</p>
    </div>
    
    <div class="info-periode">
        <h3>Periode: {{ $bulanNama }}</h3>
    </div>
    
    <!-- Summary Boxes -->
    <table style="border: none; margin-bottom: 20px;">
        <tr style="border: none;">
            <td class="summary-box success" style="border: 2px solid #28a745;">
                <h4>Total Pendapatan</h4>
                <h2>Rp {{ number_format($tukangs->sum('total_debit'), 0, ',', '.') }}</h2>
            </td>
            <td style="border: none; width: 10px;"></td>
            <td class="summary-box danger" style="border: 2px solid #dc3545;">
                <h4>Total Potongan</h4>
                <h2>Rp {{ number_format($tukangs->sum('total_kredit'), 0, ',', '.') }}</h2>
            </td>
            <td style="border: none; width: 10px;"></td>
            <td class="summary-box primary" style="border: 2px solid #007bff;">
                <h4>Total Bersih</h4>
                <h2>Rp {{ number_format($tukangs->sum('total_bersih'), 0, ',', '.') }}</h2>
            </td>
            <td style="border: none; width: 10px;"></td>
            <td class="summary-box warning" style="border: 2px solid #ffc107;">
                <h4>Pinjaman Aktif</h4>
                <h2>Rp {{ number_format($tukangs->sum('pinjaman_aktif'), 0, ',', '.') }}</h2>
            </td>
        </tr>
    </table>
    
    <!-- Tabel Detail -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="22%">Nama Tukang</th>
                <th width="15%">Total Pendapatan</th>
                <th width="15%">Total Potongan</th>
                <th width="15%">Total Bersih</th>
                <th width="15%">Pinjaman Aktif</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tukangs as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $t->kode_tukang }}</td>
                    <td>{{ $t->nama_tukang }}</td>
                    <td class="text-right text-success">Rp {{ number_format($t->total_debit, 0, ',', '.') }}</td>
                    <td class="text-right text-danger">Rp {{ number_format($t->total_kredit, 0, ',', '.') }}</td>
                    <td class="text-right text-primary">Rp {{ number_format($t->total_bersih, 0, ',', '.') }}</td>
                    <td class="text-right text-warning">Rp {{ number_format($t->pinjaman_aktif ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">TOTAL KESELURUHAN:</th>
                <th class="text-right">Rp {{ number_format($tukangs->sum('total_debit'), 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($tukangs->sum('total_kredit'), 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($tukangs->sum('total_bersih'), 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($tukangs->sum('pinjaman_aktif'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm:ss') }}</p>
        <p>Sistem Manajemen Keuangan Tukang - BUMI SULTAN</p>
    </div>
</body>
</html>
