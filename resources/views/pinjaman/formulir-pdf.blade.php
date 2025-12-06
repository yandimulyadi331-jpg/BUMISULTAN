<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resi Pinjaman - {{ $pinjaman->nomor_pinjaman }}</title>
    <style>
        @page {
            margin: 20mm 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        
        /* KOP SURAT */
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .kop-surat h1 {
            font-size: 24pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            color: #1a5490;
        }
        
        .kop-surat .alamat {
            font-size: 9pt;
            margin: 5px 0 0 0;
            line-height: 1.3;
        }
        
        /* HEADER FORM */
        .form-header {
            text-align: center;
            margin: 20px 0;
        }
        
        .form-header h2 {
            font-size: 16pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }
        
        .form-header .nomor {
            font-size: 10pt;
            margin-top: 5px;
        }
        
        /* CONTENT */
        .content {
            margin: 20px 0;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .info-table td {
            padding: 6px;
            vertical-align: top;
        }
        
        .info-table td.label {
            width: 200px;
            font-weight: bold;
        }
        
        .info-table td.separator {
            width: 20px;
            text-align: center;
        }
        
        .section-title {
            background: #1a5490;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        /* JADWAL CICILAN TABLE */
        .cicilan-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9pt;
        }
        
        .cicilan-table th,
        .cicilan-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        
        .cicilan-table th {
            background: #1a5490;
            color: white;
            font-weight: bold;
        }
        
        .cicilan-table td {
            text-align: right;
        }
        
        .cicilan-table td:first-child,
        .cicilan-table td:nth-child(2) {
            text-align: center;
        }
        
        /* TANDA TANGAN */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .signature-box .title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .signature-box .space {
            height: 70px;
            margin: 10px 0;
        }
        
        .signature-box .name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            display: inline-block;
            min-width: 150px;
        }
        
        .signature-box .position {
            font-size: 9pt;
            margin-top: 2px;
        }
        
        /* CATATAN FOOTER */
        .footer-note {
            margin-top: 30px;
            padding: 10px;
            background: #f0f0f0;
            border-left: 4px solid #1a5490;
            font-size: 9pt;
        }
        
        /* BADGE STATUS */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        
        .badge-crew {
            background: #1a5490;
            color: white;
        }
        
        .badge-non-crew {
            background: #6c757d;
            color: white;
        }
        
        /* RINGKASAN KEUANGAN BOX */
        .finance-box {
            border: 2px solid #1a5490;
            padding: 15px;
            margin: 20px 0;
            background: #f8f9fa;
        }
        
        .finance-box table {
            width: 100%;
        }
        
        .finance-box td {
            padding: 5px;
        }
        
        .finance-box td:first-child {
            font-weight: bold;
            width: 180px;
        }
        
        .finance-box .total-row {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 12pt;
            color: #1a5490;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <h1>BUMI SULTAN</h1>
        <div class="alamat">
            Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol<br>
            Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830<br>
            Telp: (021) xxxx-xxxx | Email: info@bumisultan.com
        </div>
    </div>

    <!-- HEADER RESI -->
    <div class="form-header">
        <h2>BUKTI RESI PINJAMAN</h2>
        <div class="nomor">
            Nomor Pinjaman: <strong>{{ $pinjaman->nomor_pinjaman }}</strong><br>
            Status: 
            @php
                $statusColors = [
                    'menunggu_review' => '#ffc107',
                    'direview' => '#17a2b8',
                    'menunggu_persetujuan' => '#fd7e14',
                    'disetujui' => '#28a745',
                    'ditolak' => '#dc3545',
                    'dicairkan' => '#007bff',
                    'aktif' => '#6f42c1',
                    'lunas' => '#20c997',
                    'dibatalkan' => '#6c757d'
                ];
                $statusLabels = [
                    'menunggu_review' => 'MENUNGGU REVIEW',
                    'direview' => 'DIREVIEW',
                    'menunggu_persetujuan' => 'MENUNGGU PERSETUJUAN',
                    'disetujui' => 'DISETUJUI',
                    'ditolak' => 'DITOLAK',
                    'dicairkan' => 'DICAIRKAN',
                    'aktif' => 'AKTIF',
                    'lunas' => 'LUNAS',
                    'dibatalkan' => 'DIBATALKAN'
                ];
                $statusColor = $statusColors[$pinjaman->status] ?? '#6c757d';
                $statusLabel = $statusLabels[$pinjaman->status] ?? strtoupper($pinjaman->status);
            @endphp
            <span style="display: inline-block; padding: 4px 12px; background: {{ $statusColor }}; color: white; font-weight: bold; border-radius: 3px;">
                {{ $statusLabel }}
            </span><br>
            Tanggal Pengajuan: {{ $pinjaman->tanggal_pengajuan->format('d F Y') }}
            @if($pinjaman->tanggal_disetujui)
                <br>Tanggal Disetujui: {{ $pinjaman->tanggal_disetujui->format('d F Y') }}
            @endif
            @if($pinjaman->tanggal_cair)
                <br>Tanggal Pencairan: {{ $pinjaman->tanggal_cair->format('d F Y') }}
            @endif
        </div>
    </div>

    <!-- SECTION: INFORMASI TRANSAKSI PINJAMAN -->
    <div class="section-title">I. INFORMASI TRANSAKSI PINJAMAN</div>
    <table class="info-table">
        <tr>
            <td class="label">Kategori Peminjam</td>
            <td class="separator">:</td>
            <td>
                <span class="badge {{ $pinjaman->kategori_peminjam == 'crew' ? 'badge-crew' : 'badge-non-crew' }}">
                    {{ strtoupper($pinjaman->kategori_peminjam) }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="separator">:</td>
            <td><strong>{{ $pinjaman->nama_peminjam_lengkap }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="separator">:</td>
            <td>
                @if($pinjaman->kategori_peminjam == 'crew' && $pinjaman->karyawan)
                    {{ $pinjaman->karyawan->nik }}
                @else
                    {{ $pinjaman->nik_peminjam }}
                @endif
            </td>
        </tr>
        @if($pinjaman->kategori_peminjam == 'non_crew')
        <tr>
            <td class="label">No. Telepon</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->no_telp_peminjam }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->pekerjaan_peminjam }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->alamat_peminjam }}</td>
        </tr>
        @endif
    </table>

    @if($pinjaman->nama_penjamin)
    <!-- SECTION: INFORMASI PENJAMIN -->
    <div class="section-title">II. INFORMASI PENJAMIN</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Penjamin</td>
            <td class="separator">:</td>
            <td><strong>{{ $pinjaman->nama_penjamin }}</strong></td>
        </tr>
        <tr>
            <td class="label">Hubungan</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->hubungan_penjamin }}</td>
        </tr>
        @if($pinjaman->no_telp_penjamin)
        <tr>
            <td class="label">No. Telepon</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->no_telp_penjamin }}</td>
        </tr>
        @endif
        @if($pinjaman->alamat_penjamin)
        <tr>
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->alamat_penjamin }}</td>
        </tr>
        @endif
    </table>
    @endif

    <!-- SECTION: RINCIAN KEUANGAN PINJAMAN -->
    <div class="section-title">{{ $pinjaman->nama_penjamin ? 'III' : 'II' }}. RINCIAN KEUANGAN PINJAMAN</div>
    <table class="info-table">
        <tr>
            <td class="label">Tujuan Pinjaman</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->tujuan_pinjaman }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah Pengajuan</td>
            <td class="separator">:</td>
            <td><strong>Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}</strong></td>
        </tr>
        @if($pinjaman->jumlah_disetujui)
        <tr>
            <td class="label">Jumlah Disetujui</td>
            <td class="separator">:</td>
            <td><strong style="color: green;">Rp {{ number_format($pinjaman->jumlah_disetujui, 0, ',', '.') }}</strong></td>
        </tr>
        @endif
        <tr>
            <td class="label">Jangka Waktu (Tenor)</td>
            <td class="separator">:</td>
            <td><strong>{{ $pinjaman->tenor_bulan }} Bulan</strong></td>
        </tr>
        <tr>
            <td class="label">Cicilan per Bulan</td>
            <td class="separator">:</td>
            <td><strong>Rp {{ number_format($pinjaman->cicilan_per_bulan, 0, ',', '.') }}</strong></td>
        </tr>
        @if($pinjaman->keterangan)
        <tr>
            <td class="label">Keterangan</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->keterangan }}</td>
        </tr>
        @endif
    </table>

    <!-- RINGKASAN KEUANGAN -->
    @if($pinjaman->total_pinjaman > 0)
    <div class="finance-box">
        <table>
            <tr>
                <td>Total Pinjaman</td>
                <td>:</td>
                <td style="text-align: right;"><strong>Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Cicilan per Bulan</td>
                <td>:</td>
                <td style="text-align: right;"><strong>Rp {{ number_format($pinjaman->cicilan_per_bulan, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Tenor</td>
                <td>:</td>
                <td style="text-align: right;"><strong>{{ $pinjaman->tenor_bulan }} Bulan</strong></td>
            </tr>
        </table>
    </div>
    @endif

    <!-- SECTION: JAMINAN/COLLATERAL -->
    @if($pinjaman->jenis_jaminan)
    <div class="section-title">{{ $pinjaman->nama_penjamin ? 'IV' : 'III' }}. INFORMASI JAMINAN</div>
    <table class="info-table">
        <tr>
            <td class="label">Jenis Jaminan</td>
            <td class="separator">:</td>
            <td><strong>{{ $pinjaman->jenis_jaminan }}</strong></td>
        </tr>
        @if($pinjaman->nomor_jaminan)
        <tr>
            <td class="label">Nomor/Identitas Jaminan</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->nomor_jaminan }}</td>
        </tr>
        @endif
        @if($pinjaman->deskripsi_jaminan)
        <tr>
            <td class="label">Deskripsi Jaminan</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->deskripsi_jaminan }}</td>
        </tr>
        @endif
        @if($pinjaman->nilai_jaminan)
        <tr>
            <td class="label">Nilai Taksiran Jaminan</td>
            <td class="separator">:</td>
            <td><strong>Rp {{ number_format($pinjaman->nilai_jaminan, 0, ',', '.') }}</strong></td>
        </tr>
        @endif
        @if($pinjaman->atas_nama_jaminan)
        <tr>
            <td class="label">Atas Nama</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->atas_nama_jaminan }}</td>
        </tr>
        @endif
        @if($pinjaman->kondisi_jaminan)
        <tr>
            <td class="label">Kondisi</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->kondisi_jaminan }}</td>
        </tr>
        @endif
        @if($pinjaman->keterangan_jaminan)
        <tr>
            <td class="label">Keterangan</td>
            <td class="separator">:</td>
            <td>{{ $pinjaman->keterangan_jaminan }}</td>
        </tr>
        @endif
    </table>
    @endif

    <!-- JADWAL CICILAN -->
    @if($pinjaman->cicilan->count() > 0)
    <div style="page-break-before: always;">
        @php
            $sectionNumber = 'III';
            if($pinjaman->nama_penjamin) $sectionNumber = 'IV';
            if($pinjaman->jenis_jaminan) {
                $sectionNumber = $pinjaman->nama_penjamin ? 'V' : 'IV';
            }
            
            // Hitung summary
            $totalCicilan = $pinjaman->cicilan->sum('jumlah_cicilan');
            $totalDibayar = $pinjaman->cicilan->sum('jumlah_dibayar');
            $sisaPinjaman = $totalCicilan - $totalDibayar;
            $cicilanLunas = $pinjaman->cicilan->where('status', 'lunas')->count();
            $totalCicilan_count = $pinjaman->cicilan->count();
        @endphp
        
        <!-- SUMMARY PEMBAYARAN DI ATAS -->
        <div class="section-title">{{ $sectionNumber }}. RINGKASAN STATUS PINJAMAN</div>
        <div class="finance-box">
            <table>
                <tr>
                    <td>Total Pinjaman</td>
                    <td>:</td>
                    <td style="text-align: right;"><strong>Rp {{ number_format($totalCicilan, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Total Sudah Dibayar</td>
                    <td>:</td>
                    <td style="text-align: right; color: green;"><strong>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</strong></td>
                </tr>
                <tr style="border-top: 2px solid #000;">
                    <td><strong>Sisa Pinjaman</strong></td>
                    <td>:</td>
                    <td style="text-align: right; color: #dc3545; font-size: 14pt;"><strong>Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Cicilan Lunas</td>
                    <td>:</td>
                    <td style="text-align: right;"><strong>{{ $cicilanLunas }} dari {{ $totalCicilan_count }} cicilan</strong></td>
                </tr>
                <tr>
                    <td>Progress Pembayaran</td>
                    <td>:</td>
                    <td style="text-align: right;">
                        <strong style="font-size: 12pt; color: {{ $cicilanLunas == $totalCicilan_count ? 'green' : '#1a5490' }}">
                            {{ $totalCicilan_count > 0 ? number_format(($cicilanLunas / $totalCicilan_count) * 100, 1) : 0 }}%
                        </strong>
                    </td>
                </tr>
            </table>
        </div>

        @php
            $nextSectionNumber = (int)str_replace(['IV', 'V', 'III', 'II'], [4, 5, 3, 2], $sectionNumber) + 1;
            $nextSectionRoman = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII'][$nextSectionNumber] ?? 'VI';
        @endphp
        
        <div class="section-title">{{ $nextSectionRoman }}. RIWAYAT & JADWAL PEMBAYARAN ANGSURAN</div>
        <table class="cicilan-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Cicilan Ke-</th>
                    <th style="width: 12%;">Jatuh Tempo</th>
                    <th style="width: 15%;">Jumlah Cicilan</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 12%;">Tanggal Bayar</th>
                    <th style="width: 15%;">Jumlah Dibayar</th>
                    <th style="width: 15%;">Sisa</th>
                    <th style="width: 11%;">Metode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pinjaman->cicilan as $cicilan)
                <tr style="background: {{ $cicilan->status == 'lunas' ? '#d4edda' : ($cicilan->status == 'sebagian' ? '#fff3cd' : '#ffffff') }}">
                    <td>{{ $cicilan->cicilan_ke }}</td>
                    <td>{{ $cicilan->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                    <td><strong>Rp {{ number_format($cicilan->jumlah_cicilan, 0, ',', '.') }}</strong></td>
                    <td>
                        @if($cicilan->status == 'lunas')
                            <span style="color: green; font-weight: bold;">✓ LUNAS</span>
                        @elseif($cicilan->status == 'sebagian')
                            <span style="color: orange; font-weight: bold;">⚠ SEBAGIAN</span>
                        @elseif($cicilan->status == 'terlambat')
                            <span style="color: red; font-weight: bold;">⚠ TERLAMBAT</span>
                        @else
                            <span style="color: red; font-weight: bold;">✗ BELUM BAYAR</span>
                        @endif
                    </td>
                    <td>
                        @if($cicilan->tanggal_bayar)
                            {{ $cicilan->tanggal_bayar->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="{{ $cicilan->jumlah_dibayar > 0 ? 'color: green; font-weight: bold;' : '' }}">
                        @if($cicilan->jumlah_dibayar > 0)
                            Rp {{ number_format($cicilan->jumlah_dibayar, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="{{ $cicilan->sisa_cicilan > 0 ? 'color: red; font-weight: bold;' : 'color: green;' }}">
                        @if($cicilan->sisa_cicilan > 0)
                            Rp {{ number_format($cicilan->sisa_cicilan, 0, ',', '.') }}
                        @else
                            <span style="color: green;">LUNAS</span>
                        @endif
                    </td>
                    <td style="font-size: 8pt;">
                        @if($cicilan->metode_pembayaran)
                            {{ strtoupper($cicilan->metode_pembayaran) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #1a5490; color: white; font-weight: bold;">
                    <td colspan="2" style="text-align: center;">TOTAL</td>
                    <td>Rp {{ number_format($totalCicilan, 0, ',', '.') }}</td>
                    <td>{{ $cicilanLunas }}/{{ $totalCicilan_count }}</td>
                    <td>-</td>
                    <td>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- KETENTUAN -->
    <div class="footer-note">
        <strong>KETENTUAN & INFORMASI PINJAMAN:</strong><br>
        1. Resi ini adalah bukti sah transaksi pinjaman yang telah disetujui dan dicairkan.<br>
        2. Peminjam wajib membayar cicilan sesuai jadwal yang telah ditentukan.<br>
        3. Pinjaman dapat dilunasi lebih cepat tanpa dikenakan penalti.<br>
        4. Segala perubahan data atau informasi harus dilaporkan kepada perusahaan.<br>
        5. Dokumen ini merupakan bukti resmi dan memiliki kekuatan hukum.
    </div>

    <!-- FOOTER -->
    <div style="margin-top: 40px; text-align: center; font-size: 8pt; color: #666; border-top: 1px solid #ddd; padding-top: 10px;">
        <strong>BUKTI RESI PINJAMAN YANG SAH</strong><br>
        Dokumen ini dicetak otomatis dari sistem Bumi Sultan pada {{ now()->format('d F Y H:i') }}<br>
        Nomor Referensi: {{ $pinjaman->nomor_pinjaman }} | Status: {{ strtoupper(str_replace('_', ' ', $pinjaman->status)) }}
    </div>
</body>
</html>
