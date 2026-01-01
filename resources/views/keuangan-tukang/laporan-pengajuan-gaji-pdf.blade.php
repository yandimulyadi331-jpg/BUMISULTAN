<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengajuan Gaji Tukang</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 0 0 3px 0;
            font-size: 14px;
            color: #333;
        }
        .header p {
            margin: 2px 0;
            font-size: 9px;
            color: #666;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 8px;
        }
        .tukang-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
            border: 1px solid #ddd;
            padding: 8px;
        }
        .tukang-header {
            background-color: #f5f5f5;
            padding: 6px;
            margin-bottom: 8px;
            border-left: 4px solid #007bff;
        }
        .tukang-header h4 {
            margin: 0;
            font-size: 11px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 8px;
        }
        table.kehadiran th,
        table.kehadiran td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }
        table.kehadiran th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 8px;
        }
        table.kehadiran tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table.summary {
            width: 100%;
            margin-top: 5px;
        }
        table.summary td {
            padding: 3px 5px;
            border: none;
        }
        table.summary .label {
            width: 60%;
            text-align: right;
            font-weight: bold;
        }
        table.summary .value {
            width: 40%;
            text-align: right;
            padding-right: 10px;
        }
        .potongan-detail {
            background-color: #fff8e1;
            padding: 5px;
            margin: 5px 0;
            border-left: 3px solid #ffc107;
        }
        .potongan-detail h5 {
            margin: 0 0 5px 0;
            font-size: 9px;
            color: #f57c00;
        }
        .potongan-item {
            margin: 3px 0;
            padding: 3px;
            background: white;
            border-radius: 2px;
        }
        .total-row {
            background-color: #e3f2fd;
            font-weight: bold;
            border-top: 2px solid #007bff !important;
        }
        .grand-total {
            background-color: #c8e6c9;
            font-size: 10px;
            font-weight: bold;
            color: #2e7d32;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-danger {
            color: #d32f2f;
        }
        .text-success {
            color: #388e3c;
        }
        .text-primary {
            color: #1976d2;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #4caf50;
            color: white;
        }
        .badge-danger {
            background-color: #f44336;
            color: white;
        }
        .badge-warning {
            background-color: #ff9800;
            color: white;
        }
        .badge-info {
            background-color: #2196f3;
            color: white;
        }
        .status-pembayaran {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            margin-left: 10px;
        }
        .status-pending {
            background-color: #ff9800;
            color: white;
        }
        .status-lunas {
            background-color: #4caf50;
            color: white;
        }
        .payment-info {
            font-size: 7px;
            color: #666;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>📋 LAPORAN PENGAJUAN GAJI TUKANG</h2>
        <p><strong>Periode: {{ $periodeText }}</strong></p>
        <p>Dicetak pada: {{ $tanggalCetak }} oleh {{ Auth::user()->name ?? 'Admin' }}</p>
    </div>

    @foreach($dataLaporan as $data)
    <div class="tukang-section">
        <div class="tukang-header">
            <h4>
                {{ $data['tukang']->kode_tukang }} - {{ $data['tukang']->nama_tukang }}
                
                @php
                    // Cek status pembayaran untuk periode ini
                    $statusPembayaran = App\Models\PembayaranGajiTukang::where('tukang_id', $data['tukang']->id)
                        ->where('periode_mulai', '<=', $data['periode']['kamis'])
                        ->where('periode_akhir', '>=', $data['periode']['sabtu'])
                        ->where('status', 'lunas')
                        ->first();
                @endphp
                
                @if($statusPembayaran)
                    <span class="status-pembayaran status-lunas">✅ SUDAH DIBAYAR</span>
                    <span class="payment-info">
                        ({{ $statusPembayaran->tanggal_bayar ? $statusPembayaran->tanggal_bayar->format('d M Y H:i') : '-' }} 
                        oleh {{ $statusPembayaran->dibayar_oleh ?? 'Admin' }})
                    </span>
                @else
                    <span class="status-pembayaran status-pending">⏳ PENDING - BELUM DIBAYAR</span>
                @endif
            </h4>
        </div>

        <!-- RINCIAN KEHADIRAN -->
        <h5 style="margin: 8px 0 5px 0; font-size: 10px; color: #1976d2;">📅 Rincian Kehadiran ({{ $data['jumlah_hadir'] }} hari)</h5>
        <table class="kehadiran">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="12%">Hari</th>
                    <th width="12%">Status</th>
                    <th width="15%">Upah Harian</th>
                    <th width="12%">Lembur</th>
                    <th width="15%">Upah Lembur</th>
                    <th width="11%">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['kehadirans'] as $index => $kehadiran)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ Carbon\Carbon::parse($kehadiran->tanggal)->format('d M Y') }}</td>
                    <td>{{ Carbon\Carbon::parse($kehadiran->tanggal)->locale('id')->isoFormat('dddd') }}</td>
                    <td class="text-center">
                        @if($kehadiran->status == 'hadir')
                            <span class="badge badge-success">HADIR</span>
                        @elseif($kehadiran->status == 'sakit')
                            <span class="badge badge-warning">SAKIT</span>
                        @elseif($kehadiran->status == 'izin')
                            <span class="badge badge-info">IZIN</span>
                        @else
                            <span class="badge badge-danger">ALPHA</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($kehadiran->upah_harian, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($kehadiran->lembur == 'full')
                            <span class="badge badge-info">FULL</span>
                        @elseif($kehadiran->lembur == 'setengah')
                            <span class="badge badge-info">1/2</span>
                        @elseif($kehadiran->lembur == 'cash')
                            <span class="badge badge-success">CASH</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if($kehadiran->lembur != 'tidak')
                            Rp {{ number_format($kehadiran->upah_lembur, 0, ',', '.') }}
                            @if($kehadiran->lembur_dibayar_cash)
                                <br><small style="color: #4caf50;">(Cash terbayar)</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        <strong>Rp {{ number_format($kehadiran->upah_harian + ($kehadiran->lembur_dibayar_cash ? 0 : $kehadiran->upah_lembur), 0, ',', '.') }}</strong>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data kehadiran</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- RINCIAN POTONGAN -->
        @if(($data['pinjamanAktif']->count() > 0 && $data['tukang']->auto_potong_pinjaman) || $data['potonganLain']->count() > 0)
        <div class="potongan-detail">
            <h5>💳 Rincian Potongan</h5>
            
            @if($data['pinjamanAktif']->count() > 0 && $data['tukang']->auto_potong_pinjaman)
                <div style="margin-bottom: 5px;">
                    <strong style="font-size: 8px; color: #f57c00;">Cicilan Pinjaman (AUTO POTONG AKTIF):</strong>
                    @foreach($data['pinjamanAktif'] as $pinjaman)
                    <div class="potongan-item">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="width: 60%; padding: 2px;">
                                    <strong>{{ $pinjaman->keterangan }}</strong><br>
                                    <small>Tanggal: {{ $pinjaman->tanggal_pinjaman->format('d M Y') }}</small><br>
                                    <small>Sisa Pinjaman: Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</small>
                                </td>
                                <td style="width: 40%; text-align: right; padding: 2px;">
                                    <strong class="text-danger">- Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @endforeach
                </div>
            @elseif($data['pinjamanAktif']->count() > 0 && !$data['tukang']->auto_potong_pinjaman)
                <div style="margin-bottom: 5px; padding: 5px; background: #fff3cd; border-left: 3px solid #ffc107;">
                    <strong style="font-size: 8px; color: #856404;">⚠️ Pinjaman Aktif (AUTO POTONG NONAKTIF):</strong><br>
                    <small>Tukang memiliki {{ $data['pinjamanAktif']->count() }} pinjaman aktif, tapi auto potong dinonaktifkan.</small><br>
                    <small>Total sisa pinjaman: <strong>Rp {{ number_format($data['pinjamanAktif']->sum('sisa_pinjaman'), 0, ',', '.') }}</strong></small>
                </div>
            @endif

            @if($data['potonganLain']->count() > 0)
                <div>
                    <strong style="font-size: 8px; color: #f57c00;">Potongan Lain:</strong>
                    @foreach($data['potonganLain'] as $potongan)
                    <div class="potongan-item">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="width: 60%; padding: 2px;">
                                    <strong>{{ ucwords(str_replace('_', ' ', $potongan->jenis_potongan)) }}</strong><br>
                                    <small>{{ $potongan->keterangan }}</small><br>
                                    <small>Tanggal: {{ Carbon\Carbon::parse($potongan->tanggal)->format('d M Y') }}</small>
                                </td>
                                <td style="width: 40%; text-align: right; padding: 2px;">
                                    <strong class="text-danger">- Rp {{ number_format($potongan->jumlah, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        <!-- RINGKASAN PERHITUNGAN -->
        <table class="summary">
            <tr>
                <td class="label">Total Upah Harian:</td>
                <td class="value">Rp {{ number_format($data['total_upah_harian'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Upah Lembur:</td>
                <td class="value">Rp {{ number_format($data['total_upah_lembur'], 0, ',', '.') }}</td>
            </tr>
            @if($data['lembur_cash_terbayar'] > 0)
            <tr>
                <td class="label">Lembur Cash (Terbayar):</td>
                <td class="value text-danger">- Rp {{ number_format($data['lembur_cash_terbayar'], 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="label">TOTAL KOTOR:</td>
                <td class="value text-primary">Rp {{ number_format($data['total_kotor'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Potongan Pinjaman:</td>
                <td class="value text-danger">
                    - Rp {{ number_format($data['total_potongan_pinjaman'], 0, ',', '.') }}
                    @if($data['total_potongan_pinjaman'] > 0)
                        <br><small style="color: #4caf50;">(Auto Potong: AKTIF)</small>
                    @elseif($data['pinjamanAktif']->count() > 0)
                        <br><small style="color: #ff9800;">(Auto Potong: NONAKTIF)</small>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Potongan Lain:</td>
                <td class="value text-danger">- Rp {{ number_format($data['total_potongan_lain'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td class="label">TOTAL POTONGAN:</td>
                <td class="value text-danger">Rp {{ number_format($data['total_potongan'], 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td class="label" style="font-size: 11px;">GAJI BERSIH (NETT):</td>
                <td class="value" style="font-size: 11px;">Rp {{ number_format($data['total_nett'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @endforeach

    <!-- GRAND TOTAL SEMUA TUKANG -->
    <div style="margin-top: 20px; border: 2px solid #2e7d32; padding: 10px; background-color: #e8f5e9;">
        <h4 style="margin: 0 0 8px 0; text-align: center; color: #2e7d32; font-size: 12px;">GRAND TOTAL SELURUH TUKANG</h4>
        <table style="width: 100%; font-size: 10px;">
            <tr>
                <td style="width: 60%; text-align: right; padding: 3px;"><strong>Total Gaji Kotor:</strong></td>
                <td style="width: 40%; text-align: right; padding: 3px;">
                    <strong>Rp {{ number_format(collect($dataLaporan)->sum('total_kotor'), 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 60%; text-align: right; padding: 3px;"><strong>Total Potongan:</strong></td>
                <td style="width: 40%; text-align: right; padding: 3px; color: #d32f2f;">
                    <strong>Rp {{ number_format(collect($dataLaporan)->sum('total_potongan'), 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr style="border-top: 2px solid #2e7d32;">
                <td style="width: 60%; text-align: right; padding: 5px; font-size: 12px;"><strong>GAJI BERSIH DIBAYARKAN:</strong></td>
                <td style="width: 40%; text-align: right; padding: 5px; color: #2e7d32; font-size: 12px;">
                    <strong>Rp {{ number_format(collect($dataLaporan)->sum('total_nett'), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh Sistem Manajemen Bumi Sultan</p>
        <p>© {{ date('Y') }} Bumi Sultan - Confidential Document</p>
    </div>
</body>
</html>
