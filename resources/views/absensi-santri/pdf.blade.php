<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Santri - Saung Santri</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            letter-spacing: 2px;
        }
        .kop-surat .subtitle {
            margin: 3px 0;
            font-size: 12px;
            font-weight: normal;
            color: #555;
        }
        .kop-surat .alamat {
            margin: 5px 0;
            font-size: 10px;
            color: #666;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 10px 0 5px 0;
            font-size: 16px;
            text-decoration: underline;
            color: #333;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 11px;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-section table {
            width: 100%;
            font-size: 11px;
        }
        .info-section td {
            padding: 2px 0;
        }
        .statistik {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .statistik th, .statistik td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
            font-size: 11px;
        }
        .statistik th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 5px;
            font-size: 10px;
        }
        .data-table th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .data-table td {
            text-align: left;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-primary { background-color: #007bff; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer p {
            margin: 5px 0;
            font-size: 11px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Kop Surat SAUNG SANTRI -->
    <div class="kop-surat">
        <h1>SAUNG SANTRI</h1>
        <div class="subtitle">Pondok Pesantren Tahfidz Al-Qur'an</div>
        <div class="alamat">
            Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol<br>
            Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830
        </div>
    </div>

    <!-- Header Laporan -->
    <div class="header">
        <h2>LAPORAN ABSENSI SANTRI</h2>
        <p><strong>Periode: {{ $namaBulan }} {{ $tahun }}</strong></p>
        @if($jadwal)
            <p>Jadwal: <strong>{{ $jadwal->nama_jadwal }}</strong></p>
        @endif
    </div>

    <!-- Informasi Laporan -->
    <div class="info-section">
        <table>
            <tr>
                <td width="20%"><strong>Periode</strong></td>
                <td width="30%">: {{ $namaBulan }} {{ $tahun }}</td>
                <td width="20%"><strong>Tanggal Cetak</strong></td>
                <td width="30%">: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
            @if($jadwal)
            <tr>
                <td><strong>Nama Jadwal</strong></td>
                <td>: {{ $jadwal->nama_jadwal }}</td>
                <td><strong>Waktu Kegiatan</strong></td>
                <td>: {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Tempat</strong></td>
                <td>: {{ $jadwal->tempat ?? '-' }}</td>
                <td><strong>Pembimbing</strong></td>
                <td>: {{ $jadwal->pembimbing ?? '-' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Statistik Kehadiran -->
    <table class="statistik">
        <thead>
            <tr>
                <th>HADIR</th>
                <th>IJIN</th>
                <th>SAKIT</th>
                <th>KHIDMAT</th>
                <th>ABSEN</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $statistik['hadir'] }}</strong></td>
                <td><strong>{{ $statistik['ijin'] }}</strong></td>
                <td><strong>{{ $statistik['sakit'] }}</strong></td>
                <td><strong>{{ $statistik['khidmat'] }}</strong></td>
                <td><strong>{{ $statistik['absen'] }}</strong></td>
                <td><strong>{{ $statistik['total'] }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Tabel Data Absensi -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Jadwal</th>
                <th width="10%">NIS</th>
                <th width="20%">Nama Santri</th>
                <th width="12%">Status</th>
                <th width="18%">Keterangan</th>
                <th width="8%">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensiList as $index => $absensi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $absensi->tanggal_absensi->translatedFormat('d M Y') }}</td>
                <td>{{ $absensi->jadwalSantri->nama_jadwal }}</td>
                <td>{{ $absensi->santri->nis }}</td>
                <td>{{ $absensi->santri->nama_lengkap }}</td>
                <td class="text-center">
                    @if($absensi->status_kehadiran == 'hadir')
                        <span class="badge badge-success">HADIR</span>
                    @elseif($absensi->status_kehadiran == 'ijin')
                        <span class="badge badge-warning">IJIN</span>
                    @elseif($absensi->status_kehadiran == 'sakit')
                        <span class="badge badge-info">SAKIT</span>
                    @elseif($absensi->status_kehadiran == 'khidmat')
                        <span class="badge badge-primary">KHIDMAT</span>
                    @else
                        <span class="badge badge-danger">ABSEN</span>
                    @endif
                </td>
                <td>{{ $absensi->keterangan ?? '-' }}</td>
                <td class="text-center">{{ $absensi->waktu_absensi ? \Carbon\Carbon::parse($absensi->waktu_absensi)->format('H:i') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">
                    Tidak ada data absensi untuk periode yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer dengan TTD -->
    <div class="footer">
        <p>Jonggol, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <br><br><br>
        <p>
            <strong>_______________________</strong><br>
            Pembimbing/Penanggung Jawab
        </p>
    </div>
</body>
</html>
