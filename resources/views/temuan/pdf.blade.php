<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Temuan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #0d6efd;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        .date {
            text-align: right;
            font-size: 12px;
            margin-bottom: 20px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #0d6efd;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0d6efd;
        }
        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        .badge-primary { background-color: #0d6efd; }
        .badge-info { background-color: #0dcaf0; color: #000; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .badge-success { background-color: #198754; }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .summary-box .number {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }
        .summary-box .label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 LAPORAN TEMUAN</h1>
        <p>Sistem Pelaporan Masalah dan Kerusakan Gedung</p>
        <p>Bumi Sultan Group</p>
    </div>

    <div class="date">
        Tanggal Laporan: {{ now()->format('d M Y H:i:s') }}
    </div>

    {{-- Summary Statistics --}}
    <div class="summary">
        <div class="summary-box">
            <div class="number">{{ $temuan->count() }}</div>
            <div class="label">Total Temuan</div>
        </div>
        <div class="summary-box">
            <div class="number">{{ $temuan->where('status', 'baru')->count() }}</div>
            <div class="label">Baru</div>
        </div>
        <div class="summary-box">
            <div class="number">{{ $temuan->where('status', 'sedang_diproses')->count() }}</div>
            <div class="label">Diproses</div>
        </div>
        <div class="summary-box">
            <div class="number">{{ $temuan->where('status', 'selesai')->count() }}</div>
            <div class="label">Selesai</div>
        </div>
        <div class="summary-box">
            <div class="number">{{ $temuan->where('urgensi', 'kritis')->count() }}</div>
            <div class="label">Kritis</div>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 15%">Judul</th>
                <th style="width: 12%">Lokasi</th>
                <th style="width: 15%">Pelapor</th>
                <th style="width: 10%">Urgensi</th>
                <th style="width: 12%">Status</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 9%">Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($temuan as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $t->judul }}</strong></td>
                    <td>{{ $t->lokasi }}</td>
                    <td>{{ $t->pelapor->name ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ match($t->urgensi) {
                            'rendah' => 'success',
                            'sedang' => 'warning',
                            'tinggi' => 'danger',
                            'kritis' => 'danger',
                            default => 'info'
                        } }}">
                            {{ match($t->urgensi) {
                                'rendah' => 'Rendah',
                                'sedang' => 'Sedang',
                                'tinggi' => 'Tinggi',
                                'kritis' => 'Kritis',
                                default => 'N/A'
                            } }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ match($t->status) {
                            'baru' => 'primary',
                            'sedang_diproses' => 'warning',
                            'sudah_diperbaiki' => 'info',
                            'tindaklanjuti' => 'info',
                            'selesai' => 'success',
                            default => 'info'
                        } }}">
                            {{ match($t->status) {
                                'baru' => 'Baru',
                                'sedang_diproses' => 'Diproses',
                                'sudah_diperbaiki' => 'Diperbaiki',
                                'tindaklanjuti' => 'Tindaklanjuti',
                                'selesai' => 'Selesai',
                                default => 'N/A'
                            } }}
                        </span>
                    </td>
                    <td>{{ $t->tanggal_temuan->format('d M Y') }}</td>
                    <td>{{ $t->admin->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Temuan Bumi Sultan.</p>
        <p>© 2025 Bumi Sultan Group. Semua hak dilindungi.</p>
    </div>
</body>
</html>
