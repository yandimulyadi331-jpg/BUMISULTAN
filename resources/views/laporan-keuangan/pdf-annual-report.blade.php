<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan {{ $periode['nama_periode'] }} - Bumi Sultan</title>
    <style>
        /* ===== GLOBAL STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 9.5pt;
            color: #1a1a1a;
            line-height: 1.5;
        }

        /* ===== COVER PAGE ===== */
        .cover-page {
            page-break-after: always;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 60px;
            text-align: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .cover-logo {
            font-size: 52pt;
            font-weight: bold;
            margin-bottom: 25px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .cover-title {
            font-size: 36pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .cover-subtitle {
            font-size: 20pt;
            margin-bottom: 50px;
            font-weight: 300;
            border: 2px solid white;
            padding: 20px 50px;
            border-radius: 8px;
        }

        .cover-footer {
            position: absolute;
            bottom: 60px;
            font-size: 12pt;
            opacity: 0.95;
        }

        /* ===== CONTENT PAGES ===== */
        .content-page {
            padding: 40px 50px;
        }

        .section-title {
            font-size: 22pt;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 25px;
            border-bottom: 4px solid #1e3c72;
            padding-bottom: 12px;
        }

        .subsection-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2a5298;
            margin-top: 25px;
            margin-bottom: 12px;
            border-left: 4px solid #2a5298;
            padding-left: 12px;
        }

        /* ===== FINANCIAL HIGHLIGHTS ===== */
        .highlights-container {
            display: table;
            width: 100%;
            margin-bottom: 35px;
            border-spacing: 15px;
        }

        .highlight-box {
            display: table-cell;
            width: 33.33%;
            padding: 25px;
            text-align: center;
            border: 3px solid #1e3c72;
            border-radius: 8px;
            vertical-align: top;
        }

        .highlight-box.revenue {
            background-color: #e3f2fd;
            border-color: #2196F3;
        }

        .highlight-box.expense {
            background-color: #fce4ec;
            border-color: #e91e63;
        }

        .highlight-box.profit {
            background-color: #e8f5e9;
            border-color: #4caf50;
        }

        .highlight-label {
            font-size: 11pt;
            color: #555;
            margin-bottom: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .highlight-value {
            font-size: 24pt;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 10px;
        }

        .highlight-change {
            font-size: 10pt;
            margin-top: 8px;
            font-weight: 600;
        }

        .highlight-change.positive {
            color: #4caf50;
        }

        .highlight-change.negative {
            color: #e91e63;
        }

        /* ===== TABLES ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 9.5pt;
        }

        thead {
            background-color: #1e3c72;
            color: white;
        }

        thead th {
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #1e3c72;
            font-size: 10pt;
        }

        tbody td {
            padding: 11px 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        .table-total {
            background-color: #e3f2fd !important;
            font-weight: bold;
            font-size: 10pt;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ===== FINANCIAL RATIOS BOX ===== */
        .ratio-grid {
            display: table;
            width: 100%;
            border-spacing: 10px;
            margin-bottom: 30px;
        }

        .ratio-box {
            display: table-cell;
            width: 50%;
            padding: 18px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            background-color: #fafafa;
        }

        .ratio-name {
            font-size: 9.5pt;
            color: #666;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .ratio-value {
            font-size: 20pt;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 5px;
        }

        .ratio-status {
            font-size: 8.5pt;
            padding: 4px 10px;
            border-radius: 12px;
            display: inline-block;
            font-weight: 600;
        }

        .ratio-status.excellent {
            background-color: #c8e6c9;
            color: #2e7d32;
        }

        .ratio-status.good {
            background-color: #fff9c4;
            color: #f57f17;
        }

        .ratio-status.warning {
            background-color: #ffccbc;
            color: #d84315;
        }

        /* ===== SUMMARY BOXES ===== */
        .summary-box {
            background-color: #f8f9fa;
            border-left: 5px solid #1e3c72;
            padding: 18px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10pt;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 11pt;
            padding-top: 15px;
        }

        /* ===== PAGE BREAKS ===== */
        .page-break {
            page-break-after: always;
        }

        /* ===== UTILITIES ===== */
        .mb-20 {
            margin-bottom: 20px;
        }

        .text-success {
            color: #4caf50;
        }

        .text-danger {
            color: #e91e63;
        }

        .text-primary {
            color: #1e3c72;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        /* ===== BAR CHART ===== */
        .chart-container {
            background-color: #fafafa;
            border: 2px solid #e0e0e0;
            padding: 25px;
            margin-bottom: 30px;
            min-height: 320px;
            border-radius: 6px;
        }

        .chart-bar-row {
            margin-bottom: 18px;
            display: flex;
            align-items: center;
        }

        .chart-label-left {
            width: 120px;
            font-size: 9pt;
            font-weight: 600;
            text-align: right;
            padding-right: 12px;
            color: #555;
        }

        .chart-bar-container {
            flex: 1;
            background-color: #e0e0e0;
            height: 28px;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .chart-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #1e3c72 0%, #4a90e2 100%);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-size: 8.5pt;
            font-weight: 600;
        }

        .chart-value-right {
            width: 110px;
            text-align: right;
            padding-left: 12px;
            font-size: 9.5pt;
            font-weight: bold;
            color: #1e3c72;
        }

        /* ===== INSIGHT BOX ===== */
        .insight-box {
            background-color: #e7f3ff;
            border: 2px solid #2196F3;
            padding: 20px;
            margin: 25px 0;
            border-radius: 6px;
        }

        .insight-title {
            font-size: 11pt;
            font-weight: bold;
            color: #1565c0;
            margin-bottom: 12px;
        }

        .insight-text {
            font-size: 9.5pt;
            line-height: 1.7;
            color: #424242;
        }

        /* ===== FOOTER ===== */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 35px;
            background-color: #f5f5f5;
            border-top: 3px solid #1e3c72;
            text-align: center;
            line-height: 35px;
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>

    {{-- ===== COVER PAGE ===== --}}
    <div class="cover-page">
        <div class="cover-logo">📊 BUMI SULTAN</div>
        <div class="cover-title">LAPORAN KEUANGAN</div>
        <div class="cover-subtitle">{{ strtoupper($periode['type']) }} REPORT {{ $periode['tahun'] }}</div>
        <div style="font-size: 16pt; margin-bottom: 30px;">{{ $periode['nama_periode'] }}</div>
        <div class="cover-footer">
            Generated: {{ $tanggal_cetak }}
        </div>
    </div>

    {{-- ===== SECTION 1: FINANCIAL HIGHLIGHTS (IKHTISAR KEUANGAN) ===== --}}
    <div class="content-page page-break">
        <div class="section-title">1. FINANCIAL HIGHLIGHTS</div>

        <div class="highlights-container">
            <div class="highlight-box revenue">
                <div class="highlight-label">PENDAPATAN</div>
                <div class="highlight-value">Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</div>
                <div class="highlight-change {{ $data['perubahan_pendapatan'] >= 0 ? 'positive' : 'negative' }}">
                    {{ $data['perubahan_pendapatan'] >= 0 ? '▲' : '▼' }} 
                    {{ number_format(abs($data['perubahan_pendapatan']), 2) }}% YoY
                </div>
            </div>
            <div class="highlight-box expense">
                <div class="highlight-label">PENGELUARAN</div>
                <div class="highlight-value">Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</div>
                <div class="highlight-change {{ $data['perubahan_pengeluaran'] <= 0 ? 'positive' : 'negative' }}">
                    {{ $data['perubahan_pengeluaran'] >= 0 ? '▲' : '▼' }} 
                    {{ number_format(abs($data['perubahan_pengeluaran']), 2) }}% YoY
                </div>
            </div>
            <div class="highlight-box profit">
                <div class="highlight-label">{{ $data['laba_rugi'] >= 0 ? 'SURPLUS' : 'DEFISIT' }}</div>
                <div class="highlight-value" style="color: {{ $data['laba_rugi'] >= 0 ? '#4caf50' : '#e91e63' }}">
                    Rp {{ number_format(abs($data['laba_rugi']), 0, ',', '.') }}
                </div>
                <div class="highlight-change {{ $data['perubahan_laba_rugi'] >= 0 ? 'positive' : 'negative' }}">
                    {{ $data['perubahan_laba_rugi'] >= 0 ? '▲' : '▼' }} 
                    {{ number_format(abs($data['perubahan_laba_rugi']), 2) }}% YoY
                </div>
            </div>
        </div>

        <div class="subsection-title">Key Performance Indicators</div>
        <table>
            <thead>
                <tr>
                    <th>Indikator</th>
                    <th class="text-right">Periode Ini</th>
                    <th class="text-right">Periode Sebelumnya</th>
                    <th class="text-right">Perubahan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Pendapatan</strong></td>
                    <td class="text-right">Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['pendapatan_sebelumnya'], 0, ',', '.') }}</td>
                    <td class="text-right {{ $data['perubahan_pendapatan'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $data['perubahan_pendapatan'] >= 0 ? '+' : '' }}{{ number_format($data['perubahan_pendapatan'], 2) }}%
                    </td>
                    <td>
                        @if($data['perubahan_pendapatan'] >= 5)
                            <span class="ratio-status excellent">EXCELLENT</span>
                        @elseif($data['perubahan_pendapatan'] >= 0)
                            <span class="ratio-status good">STABLE</span>
                        @else
                            <span class="ratio-status warning">DECLINE</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Pengeluaran</strong></td>
                    <td class="text-right">Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['pengeluaran_sebelumnya'], 0, ',', '.') }}</td>
                    <td class="text-right {{ $data['perubahan_pengeluaran'] <= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $data['perubahan_pengeluaran'] >= 0 ? '+' : '' }}{{ number_format($data['perubahan_pengeluaran'], 2) }}%
                    </td>
                    <td>
                        @if($data['perubahan_pengeluaran'] < 0)
                            <span class="ratio-status excellent">EFFICIENT</span>
                        @elseif($data['perubahan_pengeluaran'] < 10)
                            <span class="ratio-status good">CONTROLLED</span>
                        @else
                            <span class="ratio-status warning">HIGH</span>
                        @endif
                    </td>
                </tr>
                <tr class="table-total">
                    <td><strong>Net Result</strong></td>
                    <td class="text-right">Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['laba_rugi_sebelumnya'], 0, ',', '.') }}</td>
                    <td class="text-right {{ $data['perubahan_laba_rugi'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $data['perubahan_laba_rugi'] >= 0 ? '+' : '' }}{{ number_format($data['perubahan_laba_rugi'], 2) }}%
                    </td>
                    <td>
                        @if($data['laba_rugi'] >= 0)
                            <span class="ratio-status excellent">SURPLUS</span>
                        @else
                            <span class="ratio-status warning">DEFICIT</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="insight-box">
            <div class="insight-title">💡 Executive Summary</div>
            <div class="insight-text">
                <strong>Kinerja {{ $periode['nama_periode'] }}:</strong> 
                Total pendapatan Rp {{ number_format($data['pendapatan'], 0, ',', '.') }} dengan 
                tingkat efisiensi {{ $data['pendapatan'] > 0 ? number_format((1 - $data['pengeluaran'] / $data['pendapatan']) * 100, 1) : 0 }}%. 
                {{ $data['laba_rugi'] >= 0 ? 'Surplus tercapai menunjukkan pengelolaan keuangan yang sehat' : 'Defisit memerlukan evaluasi pengeluaran' }}.
                Rata-rata transaksi harian {{ number_format($data['rata_rata_transaksi_harian'], 1) }} transaksi.
            </div>
        </div>
    </div>

    {{-- ===== SECTION 2: INCOME STATEMENT (LAPORAN LABA RUGI) ===== --}}
    <div class="content-page page-break">
        <div class="section-title">2. INCOME STATEMENT</div>
        <p style="margin-bottom: 25px; color: #666; font-size: 10pt;">
            Laporan Laba Rugi untuk periode {{ $periode['nama_periode'] }}
        </p>

        <div class="subsection-title">A. Pendapatan Operasional</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Kategori</th>
                    <th style="width: 25%;">Total (Rp)</th>
                    <th style="width: 15%;">% Total</th>
                    <th style="width: 15%;">Transaksi</th>
                    <th>Rata-rata/Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['pendapatan_per_kategori'] as $item)
                <tr>
                    <td><strong>{{ $item->kategori }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($item->total, 0, ',', '.') }}</strong></td>
                    <td class="text-right">{{ $data['pendapatan'] > 0 ? number_format(($item->total / $data['pendapatan']) * 100, 1) : 0 }}%</td>
                    <td class="text-center">{{ $item->jumlah_transaksi ?? 0 }}</td>
                    <td class="text-right">{{ number_format(($item->jumlah_transaksi ?? 0) > 0 ? $item->total / $item->jumlah_transaksi : 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="table-total">
                    <td><strong>TOTAL PENDAPATAN</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['pendapatan'], 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>100%</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">B. Beban Operasional</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Kategori</th>
                    <th style="width: 25%;">Total (Rp)</th>
                    <th style="width: 15%;">% Total</th>
                    <th style="width: 15%;">Transaksi</th>
                    <th>Rata-rata/Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['pengeluaran_per_kategori'] as $item)
                <tr>
                    <td><strong>{{ $item->kategori }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($item->total, 0, ',', '.') }}</strong></td>
                    <td class="text-right">{{ $data['pengeluaran'] > 0 ? number_format(($item->total / $data['pengeluaran']) * 100, 1) : 0 }}%</td>
                    <td class="text-center">{{ $item->jumlah_transaksi ?? 0 }}</td>
                    <td class="text-right">{{ number_format(($item->jumlah_transaksi ?? 0) > 0 ? $item->total / $item->jumlah_transaksi : 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="table-total">
                    <td><strong>TOTAL BEBAN</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['pengeluaran'], 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>100%</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <div class="summary-box" style="border-left-color: {{ $data['laba_rugi'] >= 0 ? '#4caf50' : '#e91e63' }}">
            <div class="summary-row">
                <span>PENDAPATAN OPERASIONAL</span>
                <span>Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>BEBAN OPERASIONAL</span>
                <span>(Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }})</span>
            </div>
            <div class="summary-row" style="color: {{ $data['laba_rugi'] >= 0 ? '#4caf50' : '#e91e63' }}; font-size: 12pt;">
                <span><strong>{{ $data['laba_rugi'] >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</strong></span>
                <span><strong>Rp {{ number_format($data['laba_rugi'], 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <div class="chart-container">
            <div style="font-weight: bold; margin-bottom: 20px; color: #1e3c72; font-size: 11pt;">Top 5 Expense Categories</div>
            @php $topExpenses = $data['pengeluaran_per_kategori']->take(5); $maxExpense = $topExpenses->first()->total ?? 1; @endphp
            @foreach($topExpenses as $item)
            <div class="chart-bar-row">
                <div class="chart-label-left">{{ $item->kategori }}</div>
                <div class="chart-bar-container">
                    <div class="chart-bar-fill" style="width: {{ ($item->total / $maxExpense) * 100 }}%;">
                        {{ $data['pengeluaran'] > 0 ? number_format(($item->total / $data['pengeluaran']) * 100, 1) : 0 }}%
                    </div>
                </div>
                <div class="chart-value-right">{{ number_format($item->total, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== SECTION 3: BALANCE SHEET (NERACA) ===== --}}
    <div class="content-page page-break">
        <div class="section-title">3. BALANCE SHEET</div>
        <p style="margin-bottom: 25px; color: #666; font-size: 10pt;">
            Posisi keuangan per {{ $periode['tanggal_akhir']->format('d F Y') }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th class="text-right">Awal Periode</th>
                    <th class="text-right">Akhir Periode</th>
                    <th class="text-right">Perubahan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="background-color: #e3f2fd; font-weight: bold; font-size: 10pt;">ASET</td>
                </tr>
                <tr>
                    <td style="padding-left: 25px;"><strong>Kas dan Setara Kas</strong></td>
                    <td class="text-right">{{ number_format($data['saldo_awal'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data['saldo_akhir'], 0, ',', '.') }}</td>
                    <td class="text-right {{ ($data['saldo_akhir'] - $data['saldo_awal']) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($data['saldo_akhir'] - $data['saldo_awal'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="table-total">
                    <td><strong>TOTAL ASET</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['saldo_awal'], 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['saldo_akhir'], 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['saldo_akhir'] - $data['saldo_awal'], 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Movement in Cash</div>
        <div class="summary-box">
            <div class="summary-row">
                <span>Saldo Kas Awal ({{ $periode['tanggal_awal']->format('d M Y') }})</span>
                <span>Rp {{ number_format($data['saldo_awal'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Kas Masuk dari Operasional</span>
                <span style="color: #4caf50;">+ Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Kas Keluar untuk Operasional</span>
                <span style="color: #e91e63;">- Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row" style="font-size: 12pt; padding-top: 18px;">
                <span><strong>Saldo Kas Akhir ({{ $periode['tanggal_akhir']->format('d M Y') }})</strong></span>
                <span><strong>Rp {{ number_format($data['saldo_akhir'], 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <div class="ratio-grid">
            <div class="ratio-box">
                <div class="ratio-name">Growth Rate</div>
                <div class="ratio-value">
                    {{ $data['saldo_awal'] > 0 ? number_format((($data['saldo_akhir'] - $data['saldo_awal']) / $data['saldo_awal']) * 100, 2) : 0 }}%
                </div>
                <span class="ratio-status {{ (($data['saldo_akhir'] - $data['saldo_awal']) / max($data['saldo_awal'], 1)) * 100 >= 0 ? 'excellent' : 'warning' }}">
                    {{ (($data['saldo_akhir'] - $data['saldo_awal']) / max($data['saldo_awal'], 1)) * 100 >= 0 ? 'POSITIVE GROWTH' : 'NEGATIVE GROWTH' }}
                </span>
            </div>
            <div class="ratio-box">
                <div class="ratio-name">Cash Turnover</div>
                <div class="ratio-value">
                    {{ $data['saldo_awal'] > 0 ? number_format($data['pengeluaran'] / $data['saldo_awal'], 2) : 0 }}x
                </div>
                <span class="ratio-status {{ ($data['pengeluaran'] / max($data['saldo_awal'], 1)) < 1 ? 'excellent' : 'good' }}">
                    {{ ($data['pengeluaran'] / max($data['saldo_awal'], 1)) < 1 ? 'LOW RISK' : 'MODERATE' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 4: CASH FLOW STATEMENT ===== --}}
    <div class="content-page page-break">
        <div class="section-title">4. CASH FLOW STATEMENT</div>
        <p style="margin-bottom: 25px; color: #666; font-size: 10pt;">
            Laporan Arus Kas untuk periode {{ $periode['nama_periode'] }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th class="text-center">Volume</th>
                    <th class="text-right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="background-color: #e3f2fd; font-weight: bold; font-size: 10pt;">ARUS KAS DARI AKTIVITAS OPERASIONAL</td>
                </tr>
                <tr>
                    <td style="padding-left: 25px;">Penerimaan dari operasional</td>
                    <td class="text-center">{{ number_format($data['arus_kas_masuk_count'], 0, ',', '.') }} transaksi</td>
                    <td class="text-right text-success">{{ number_format($data['arus_kas_masuk'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 25px;">Pembayaran untuk operasional</td>
                    <td class="text-center">{{ number_format($data['arus_kas_keluar_count'], 0, ',', '.') }} transaksi</td>
                    <td class="text-right text-danger">({{ number_format($data['arus_kas_keluar'], 0, ',', '.') }})</td>
                </tr>
                <tr class="table-total">
                    <td><strong>Arus Kas Bersih dari Aktivitas Operasional</strong></td>
                    <td class="text-center">{{ number_format($data['total_transaksi'], 0, ',', '.') }} total</td>
                    <td class="text-right" style="color: {{ $data['arus_kas_bersih'] >= 0 ? '#4caf50' : '#e91e63' }}">
                        <strong>{{ number_format($data['arus_kas_bersih'], 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Cash Flow Reconciliation</div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Kas awal periode</td>
                    <td class="text-right">{{ number_format($data['saldo_awal'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Arus kas bersih operasional</td>
                    <td class="text-right {{ $data['arus_kas_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($data['arus_kas_bersih'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="table-total">
                    <td><strong>Kas akhir periode</strong></td>
                    <td class="text-right"><strong>{{ number_format($data['saldo_akhir'], 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="ratio-grid">
            <div class="ratio-box">
                <div class="ratio-name">Operating Cash Flow Ratio</div>
                <div class="ratio-value">
                    {{ $data['pendapatan'] > 0 ? number_format(($data['arus_kas_bersih'] / $data['pendapatan']) * 100, 1) : 0 }}%
                </div>
                <span class="ratio-status {{ ($data['arus_kas_bersih'] / max($data['pendapatan'], 1)) * 100 > 20 ? 'excellent' : 'good' }}">
                    {{ ($data['arus_kas_bersih'] / max($data['pendapatan'], 1)) * 100 > 20 ? 'STRONG' : 'MODERATE' }}
                </span>
            </div>
            <div class="ratio-box">
                <div class="ratio-name">Daily Cash Burn</div>
                <div class="ratio-value">
                    Rp {{ number_format($data['pengeluaran'] / max(1, $periode['tanggal_awal']->diffInDays($periode['tanggal_akhir']) + 1), 0, ',', '.') }}
                </div>
                <span class="ratio-status good">Per Day</span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 5: FINANCIAL RATIOS & ANALYSIS ===== --}}
    <div class="content-page page-break">
        <div class="section-title">5. FINANCIAL RATIOS & ANALYSIS</div>
        <p style="margin-bottom: 25px; color: #666; font-size: 10pt;">
            Analisa rasio keuangan periode {{ $periode['nama_periode'] }}
        </p>

        <div class="subsection-title">Profitability Ratios</div>
        <table>
            <thead>
                <tr>
                    <th>Rasio</th>
                    <th class="text-right">Nilai</th>
                    <th>Formula</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Net Profit Margin</strong></td>
                    <td class="text-right"><strong>{{ $data['pendapatan'] > 0 ? number_format(($data['laba_rugi'] / $data['pendapatan']) * 100, 2) : 0 }}%</strong></td>
                    <td style="font-size: 9pt;">Laba Bersih / Pendapatan</td>
                    <td>
                        @php $npm = $data['pendapatan'] > 0 ? ($data['laba_rugi'] / $data['pendapatan']) * 100 : 0; @endphp
                        @if($npm > 10)
                            <span class="ratio-status excellent">EXCELLENT</span>
                        @elseif($npm > 0)
                            <span class="ratio-status good">GOOD</span>
                        @else
                            <span class="ratio-status warning">POOR</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Operating Expense Ratio</strong></td>
                    <td class="text-right"><strong>{{ $data['pendapatan'] > 0 ? number_format(($data['pengeluaran'] / $data['pendapatan']) * 100, 2) : 0 }}%</strong></td>
                    <td style="font-size: 9pt;">Pengeluaran / Pendapatan</td>
                    <td>
                        @php $oer = $data['pendapatan'] > 0 ? ($data['pengeluaran'] / $data['pendapatan']) * 100 : 0; @endphp
                        @if($oer < 80)
                            <span class="ratio-status excellent">EFFICIENT</span>
                        @elseif($oer < 95)
                            <span class="ratio-status good">GOOD</span>
                        @else
                            <span class="ratio-status warning">HIGH</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Return on Assets (ROA)</strong></td>
                    <td class="text-right"><strong>{{ $data['saldo_awal'] > 0 ? number_format(($data['laba_rugi'] / $data['saldo_awal']) * 100, 2) : 0 }}%</strong></td>
                    <td style="font-size: 9pt;">Laba Bersih / Total Aset Awal</td>
                    <td>
                        @php $roa = $data['saldo_awal'] > 0 ? ($data['laba_rugi'] / $data['saldo_awal']) * 100 : 0; @endphp
                        @if($roa > 5)
                            <span class="ratio-status excellent">STRONG</span>
                        @elseif($roa > 0)
                            <span class="ratio-status good">POSITIVE</span>
                        @else
                            <span class="ratio-status warning">NEGATIVE</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Efficiency Ratios</div>
        <table>
            <thead>
                <tr>
                    <th>Rasio</th>
                    <th class="text-right">Nilai</th>
                    <th>Interpretasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Transaction Efficiency</strong></td>
                    <td class="text-right">{{ number_format($data['rata_rata_transaksi_harian'], 2) }} transaksi/hari</td>
                    <td>Rata-rata aktivitas harian</td>
                </tr>
                <tr>
                    <td><strong>Average Transaction Size (Income)</strong></td>
                    <td class="text-right">Rp {{ number_format($data['arus_kas_masuk_count'] > 0 ? $data['pendapatan'] / $data['arus_kas_masuk_count'] : 0, 0, ',', '.') }}</td>
                    <td>Rata-rata nilai penerimaan</td>
                </tr>
                <tr>
                    <td><strong>Average Transaction Size (Expense)</strong></td>
                    <td class="text-right">Rp {{ number_format($data['arus_kas_keluar_count'] > 0 ? $data['pengeluaran'] / $data['arus_kas_keluar_count'] : 0, 0, ',', '.') }}</td>
                    <td>Rata-rata nilai pengeluaran</td>
                </tr>
            </tbody>
        </table>

        <div class="insight-box">
            <div class="insight-title">📊 Financial Health Assessment</div>
            <div class="insight-text">
                @php
                    $npm = $data['pendapatan'] > 0 ? ($data['laba_rugi'] / $data['pendapatan']) * 100 : 0;
                    $oer = $data['pendapatan'] > 0 ? ($data['pengeluaran'] / $data['pendapatan']) * 100 : 0;
                    $growth = $data['saldo_awal'] > 0 ? (($data['saldo_akhir'] - $data['saldo_awal']) / $data['saldo_awal']) * 100 : 0;
                @endphp
                <strong>Overall Score:</strong> 
                @if($npm > 10 && $oer < 80 && $growth > 0)
                    <span style="color: #4caf50; font-weight: bold;">A (EXCELLENT)</span> - 
                    Keuangan sangat sehat dengan profit margin tinggi dan pengeluaran efisien.
                @elseif($npm > 5 && $oer < 90 && $data['laba_rugi'] >= 0)
                    <span style="color: #8bc34a; font-weight: bold;">B (GOOD)</span> - 
                    Keuangan dalam kondisi baik, ada ruang untuk peningkatan efisiensi.
                @elseif($npm > 0 && $data['laba_rugi'] >= 0)
                    <span style="color: #ffc107; font-weight: bold;">C (FAIR)</span> - 
                    Keuangan stabil namun margin tipis, perlu peningkatan pendapatan atau efisiensi.
                @else
                    <span style="color: #ff9800; font-weight: bold;">D (NEEDS IMPROVEMENT)</span> - 
                    Defisit atau margin negatif, diperlukan evaluasi menyeluruh dan strategi perbaikan.
                @endif
            </div>
        </div>

        <div class="subsection-title">Top 10 Largest Transactions</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 12%;">Type</th>
                    <th style="width: 15%;">Kategori</th>
                    <th>Keterangan</th>
                    <th style="width: 18%;" class="text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['transaksi_terbesar'] as $index => $transaksi)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_realisasi)->format('d M Y') }}</td>
                    <td>
                        <span style="color: {{ $transaksi->tipe_transaksi == 'masuk' ? '#4caf50' : '#e91e63' }}; font-weight: 600;">
                            {{ $transaksi->tipe_transaksi == 'masuk' ? '▲ IN' : '▼ OUT' }}
                        </span>
                    </td>
                    <td><strong>{{ $transaksi->kategori }}</strong></td>
                    <td style="font-size: 9pt;">{{ Str::limit($transaksi->keterangan, 45) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== SECTION 6: MONTHLY PERFORMANCE (untuk tahunan) ===== --}}
    @if($periode['type'] == 'tahunan' && count($data['data_bulanan']) > 0)
    <div class="content-page page-break">
        <div class="section-title">6. MONTHLY PERFORMANCE TREND</div>
        <p style="margin-bottom: 25px; color: #666; font-size: 10pt;">
            Tren kinerja bulanan tahun {{ $periode['tahun'] }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-right">Pendapatan</th>
                    <th class="text-right">Pengeluaran</th>
                    <th class="text-right">Net Result</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['data_bulanan'] as $bulan)
                <tr>
                    <td><strong>{{ $bulan['bulan'] }}</strong></td>
                    <td class="text-right">{{ number_format($bulan['pendapatan'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($bulan['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="text-right {{ $bulan['laba_rugi'] >= 0 ? 'text-success' : 'text-danger' }}">
                        <strong>{{ number_format($bulan['laba_rugi'], 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center">
                        <span class="ratio-status {{ $bulan['laba_rugi'] >= 0 ? 'excellent' : 'warning' }}">
                            {{ $bulan['laba_rugi'] >= 0 ? 'SURPLUS' : 'DEFICIT' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="chart-container" style="min-height: 280px;">
            <div style="font-weight: bold; margin-bottom: 18px; color: #1e3c72; font-size: 11pt; text-align: center;">
                Revenue vs Expense - Monthly Comparison
            </div>
            <div style="display: flex; justify-content: center; margin-bottom: 15px; font-size: 9pt;">
                <div style="margin-right: 20px;">
                    <span style="display: inline-block; width: 15px; height: 15px; background-color: #4caf50; margin-right: 5px;"></span>
                    <span>Pendapatan</span>
                </div>
                <div>
                    <span style="display: inline-block; width: 15px; height: 15px; background-color: #e91e63; margin-right: 5px;"></span>
                    <span>Pengeluaran</span>
                </div>
            </div>
            <div style="text-align: center;">
                @foreach($data['data_bulanan'] as $bulan)
                    @php
                        $maxValue = max($data['data_bulanan']->pluck('pendapatan')->max(), $data['data_bulanan']->pluck('pengeluaran')->max());
                        $heightPendapatan = $maxValue > 0 ? ($bulan['pendapatan'] / $maxValue) * 180 : 0;
                        $heightPengeluaran = $maxValue > 0 ? ($bulan['pengeluaran'] / $maxValue) * 180 : 0;
                    @endphp
                    <div style="display: inline-block; margin: 0 6px; text-align: center; vertical-align: bottom;">
                        <div style="display: inline-block; width: 18px; height: {{ $heightPendapatan }}px; background-color: #4caf50; vertical-align: bottom;"></div>
                        <div style="display: inline-block; width: 18px; height: {{ $heightPengeluaran }}px; background-color: #e91e63; margin-left: 3px; vertical-align: bottom;"></div>
                        <div style="font-size: 8.5pt; margin-top: 5px; color: #666;">{{ $bulan['bulan'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="insight-box">
            <div class="insight-title">📈 Trend Analysis</div>
            <div class="insight-text">
                @php
                    $positiveMonths = collect($data['data_bulanan'])->where('laba_rugi', '>=', 0)->count();
                    $bestMonth = collect($data['data_bulanan'])->sortByDesc('laba_rugi')->first();
                    $worstMonth = collect($data['data_bulanan'])->sortBy('laba_rugi')->first();
                @endphp
                <strong>Tahun {{ $periode['tahun'] }}:</strong> Dari 12 bulan, 
                <strong>{{ $positiveMonths }} bulan surplus</strong> dan {{ 12 - $positiveMonths }} bulan defisit.
                Bulan terbaik: <strong>{{ $bestMonth['bulan'] }}</strong> (surplus Rp {{ number_format($bestMonth['laba_rugi'], 0, ',', '.') }}).
                @if($worstMonth['laba_rugi'] < 0)
                    Bulan terburuk: <strong>{{ $worstMonth['bulan'] }}</strong> (defisit Rp {{ number_format(abs($worstMonth['laba_rugi']), 0, ',', '.') }}).
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ===== SECTION AKHIR: DISCLAIMER ===== --}}
    <div class="content-page">
        <div class="section-title">{{ $periode['type'] == 'tahunan' ? '7' : '6' }}. NOTES & DISCLAIMER</div>
        
        <div class="subsection-title">Accounting Policies</div>
        <table style="font-size: 9pt;">
            <thead>
                <tr>
                    <th style="width: 30%;">Policy</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Basis Pencatatan</strong></td>
                    <td>Cash Basis - transaksi dicatat saat kas diterima atau dibayarkan</td>
                </tr>
                <tr>
                    <td><strong>Mata Uang</strong></td>
                    <td>Rupiah (Rp), dibulatkan ke angka terdekat</td>
                </tr>
                <tr>
                    <td><strong>Periode Pelaporan</strong></td>
                    <td>{{ $periode['tanggal_awal']->format('d M Y') }} - {{ $periode['tanggal_akhir']->format('d M Y') }} ({{ $periode['tanggal_awal']->diffInDays($periode['tanggal_akhir']) + 1 }} hari)</td>
                </tr>
                <tr>
                    <td><strong>Sumber Data</strong></td>
                    <td>Sistem Bumi Sultan - Real-time transaction recording</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title" style="margin-top: 30px;">Report Information</div>
        <div class="summary-box">
            <div class="summary-row">
                <span>Report Type</span>
                <span><strong>{{ strtoupper($periode['type']) }} FINANCIAL REPORT</strong></span>
            </div>
            <div class="summary-row">
                <span>Report Period</span>
                <span>{{ $periode['nama_periode'] }}</span>
            </div>
            <div class="summary-row">
                <span>Generated Date</span>
                <span>{{ $tanggal_cetak }}</span>
            </div>
            <div class="summary-row">
                <span>Total Transactions</span>
                <span>{{ number_format($data['total_transaksi'], 0, ',', '.') }} transaksi</span>
            </div>
            <div class="summary-row">
                <span>Data Source</span>
                <span>Bumi Sultan Financial System (Automated)</span>
            </div>
        </div>

        <div style="margin-top: 40px; padding: 25px; background-color: #f5f5f5; border: 2px solid #1e3c72; border-radius: 6px;">
            <p style="text-align: center; font-weight: bold; font-size: 11pt; margin-bottom: 12px; color: #1e3c72;">
                DISCLAIMER & AUTHENTICITY
            </p>
            <p style="text-align: center; font-size: 9.5pt; line-height: 1.7; color: #555;">
                Laporan ini dibuat secara otomatis oleh sistem Bumi Sultan berdasarkan data transaksi yang tercatat 
                secara real-time. Semua informasi yang disajikan adalah akurat per tanggal cetak. 
                Dokumen ini sah tanpa tanda tangan basah dan dapat diverifikasi melalui sistem.
            </p>
        </div>

        <div style="margin-top: 60px; text-align: center; color: #999; font-size: 9pt;">
            <p style="margin-bottom: 10px; font-weight: bold; font-size: 12pt; color: #1e3c72;">
                *** END OF FINANCIAL REPORT ***
            </p>
            <p style="margin-bottom: 5px;">© {{ date('Y') }} Bumi Sultan - All rights reserved</p>
            <p style="margin-bottom: 5px;">This is a computer-generated document</p>
            <p style="font-size: 8pt; margin-top: 15px;">
                All transactions are digitally recorded and auditable
            </p>
        </div>
    </div>

</body>
</html>
