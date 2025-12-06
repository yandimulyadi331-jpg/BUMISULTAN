<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Dokumen Perusahaan - Bumi Sultan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
        }
        
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .kop-surat h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
        }
        
        .kop-surat h2 {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
            color: #F97316;
        }
        
        .kop-surat p {
            font-size: 9px;
            margin: 2px 0;
        }
        
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .info {
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th {
            background-color: #F97316;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            border: 1px solid #000;
        }
        
        table td {
            padding: 5px 3px;
            border: 1px solid #000;
            font-size: 8px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #FFA500;
            color: white;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        
        .footer {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <h1>PT BUMI SULTAN</h1>
        <h2>BUMI SULTAN</h2>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830</p>
        <p>Telepon: (021) 22966797 | Email: info@bumisultan.com | Website: www.bumisultan.com</p>
    </div>
    
    <!-- Judul Laporan -->
    <div class="title">
        DAFTAR DOKUMEN PERUSAHAAN
    </div>
    
    <!-- Info Laporan -->
    <div class="info">
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 150px;">Tanggal Cetak</td>
                <td style="border: none; width: 10px;">:</td>
                <td style="border: none;">{{ date('d F Y, H:i:s') }}</td>
            </tr>
            <tr>
                <td style="border: none;">Total Dokumen</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ $documents->count() }} dokumen</td>
            </tr>
        </table>
    </div>
    
    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="10%">Kode Dokumen</th>
                <th width="18%">Nama Dokumen</th>
                <th width="10%">Kategori</th>
                <th width="8%">Nomor Loker</th>
                <th width="8%">Lokasi / Rak / Baris</th>
                <th width="8%">Tipe/Akses</th>
                <th width="8%" class="text-center">Status</th>
                <th width="10%">Tanggal Dokumen</th>
                <th width="10%">Berlaku s/d</th>
                <th width="7%" class="text-center">Views</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $index => $doc)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $doc->kode_dokumen }}</strong></td>
                <td>{{ $doc->nama_dokumen }}</td>
                <td>{{ $doc->category->nama_kategori ?? '-' }}</td>
                <td class="text-center">{{ $doc->nomor_loker ?? '-' }}</td>
                <td>
                    @if($doc->lokasi_loker || $doc->rak || $doc->baris)
                        {{ $doc->lokasi_loker }}
                        @if($doc->rak)/ Rak {{ $doc->rak }}@endif
                        @if($doc->baris)/ Brs {{ $doc->baris }}@endif
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($doc->jenis_dokumen == 'file')
                        <span class="badge badge-primary">File</span>
                    @else
                        <span class="badge badge-info">Link</span>
                    @endif
                    <br>
                    @if($doc->access_level == 'public')
                        <span class="badge badge-success">Public</span>
                    @elseif($doc->access_level == 'view_only')
                        <span class="badge badge-warning">View</span>
                    @else
                        <span class="badge badge-danger">Restricted</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($doc->status == 'aktif')
                        <span class="badge badge-success">Aktif</span>
                    @elseif($doc->status == 'arsip')
                        <span class="badge badge-warning">Arsip</span>
                    @else
                        <span class="badge badge-danger">Kadaluarsa</span>
                    @endif
                </td>
                <td class="text-center">{{ $doc->tanggal_dokumen ? date('d/m/Y', strtotime($doc->tanggal_dokumen)) : '-' }}</td>
                <td class="text-center">{{ $doc->tanggal_berakhir ? date('d/m/Y', strtotime($doc->tanggal_berakhir)) : '-' }}</td>
                <td class="text-center">{{ $doc->view_count ?? 0 }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">Tidak ada data dokumen</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Summary -->
    <div style="margin-top: 15px; padding: 8px; background-color: #f0f0f0; border: 1px solid #ccc;">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 25%;"><strong>Total Dokumen:</strong> {{ $documents->count() }}</td>
                <td style="border: none; width: 25%;"><strong>Aktif:</strong> {{ $documents->where('status', 'aktif')->count() }}</td>
                <td style="border: none; width: 25%;"><strong>Arsip:</strong> {{ $documents->where('status', 'arsip')->count() }}</td>
                <td style="border: none; width: 25%;"><strong>Kadaluarsa:</strong> {{ $documents->where('status', 'kadaluarsa')->count() }}</td>
            </tr>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
    </div>
    
    <!-- Tanda Tangan -->
    <div style="margin-top: 40px; text-align: center;">
        <p>Divisi Administrasi,</p>
        <div style="border-top: 1px solid #000; width: 200px; margin: 60px auto 0;"></div>
        <p style="margin-top: 5px;">(............................)</p>
    </div>
</body>
</html>
