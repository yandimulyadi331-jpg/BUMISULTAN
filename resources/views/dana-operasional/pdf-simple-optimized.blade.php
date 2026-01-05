<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan BUMI SULTAN</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 7pt; line-height: 1.2; }
        @page { margin: 10mm; }
        
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 10px; }
        .header h1 { font-size: 16pt; margin-bottom: 3px; }
        .header p { font-size: 7pt; color: #555; }
        
        .info { background: #f5f5f5; padding: 6px; margin-bottom: 8px; font-size: 7pt; }
        .info b { color: #000; }
        
        table { width: 100%; border-collapse: collapse; font-size: 6.5pt; }
        th { background: #333; color: #fff; padding: 4px 2px; text-align: left; font-weight: bold; }
        td { padding: 3px 2px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        
        .no { width: 3%; text-align: center; }
        .tgl { width: 9%; }
        .ket { width: 45%; }
        .amt { width: 13%; text-align: right; font-family: monospace; }
        
        .total { background: #333 !important; color: #fff; font-weight: bold; padding: 5px; }
        .summary { margin-top: 10px; padding: 8px; background: #f5f5f5; border: 1px solid #333; }
        .summary table { border: none; }
        .summary td { border: none; padding: 3px; }
        .summary .label { font-weight: bold; }
        .summary .value { text-align: right; font-family: monospace; font-weight: bold; }
        
        .footer { margin-top: 15px; text-align: right; font-size: 7pt; }
        .footer .box { display: inline-block; text-align: center; width: 150px; }
        .footer .line { margin-top: 40px; border-top: 1px solid #000; padding-top: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMI SULTAN</h1>
        <p>LAPORAN KEUANGAN - {{ strtoupper($periode_label) }}</p>
        <p>Periode: {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}</p>
    </div>
    
    <div class="info">
        <b>Total Transaksi:</b> {{ number_format($total_transaksi) }} | 
        <b>Dicetak:</b> {{ $tanggal_cetak }}
        @if($isLargeData) | <b>⚠ Data Besar</b> @endif
    </div>
    
    @if($total_transaksi > 0)
    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="tgl">Tanggal</th>
                <th>Kategori</th>
                <th class="ket">Keterangan</th>
                <th class="amt">Pemasukan</th>
                <th class="amt">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi_detail as $i => $t)
            <tr>
                <td class="no">{{ $i + 1 }}</td>
                <td class="tgl">{{ date('d/m/y', strtotime($t->tanggal_realisasi)) }}</td>
                <td>{{ strtoupper($t->kategori ?? 'UMUM') }}</td>
                <td class="ket">{{ $t->uraian ?? $t->keterangan ?? '-' }}</td>
                <td class="amt">
                    @if($t->tipe_transaksi == 'pemasukan' || $t->tipe_transaksi == 'masuk')
                        {{ number_format($t->nominal, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="amt">
                    @if($t->tipe_transaksi == 'pengeluaran' || $t->tipe_transaksi == 'keluar')
                        {{ number_format($t->nominal, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" style="text-align: right;"><b>TOTAL:</b></td>
                <td class="amt"><b>{{ number_format($total_pemasukan, 0, ',', '.') }}</b></td>
                <td class="amt"><b>{{ number_format($total_pengeluaran, 0, ',', '.') }}</b></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="summary">
        <table>
            <tr>
                <td class="label">Saldo Awal:</td>
                <td class="value">Rp {{ number_format($saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Pemasukan:</td>
                <td class="value" style="color: green;">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Pengeluaran:</td>
                <td class="value" style="color: red;">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Selisih:</td>
                <td class="value">Rp {{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: #333; color: #fff; font-size: 8pt;">
                <td class="label" style="padding: 5px;"><b>SALDO AKHIR:</b></td>
                <td class="value" style="padding: 5px;"><b>Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</b></td>
            </tr>
        </table>
    </div>
    @else
    <div style="padding: 30px; text-align: center; background: #f5f5f5;">
        <p><b>Tidak ada transaksi pada periode ini</b></p>
    </div>
    @endif
    
    <div class="footer">
        <div class="box">
            Bogor, {{ date('d/m/Y') }}<br>
            Dibuat oleh,
            <div class="line">Finance Manager</div>
        </div>
    </div>
</body>
</html>
