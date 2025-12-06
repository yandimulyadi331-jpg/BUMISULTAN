<!DOCTYPE html>
<html>
<head>
    <title>Detail Administrasi - {{ $administrasi->kode_administrasi }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #007bff;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #333;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-primary { background: #007bff; color: white; }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: black; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-info { background: #17a2b8; color: white; }
        .section-title {
            background: #007bff;
            color: white;
            padding: 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        .tindak-lanjut-item {
            border: 1px solid #dee2e6;
            padding: 10px;
            margin-bottom: 10px;
            background: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>ADMINISTRASI PERUSAHAAN</h2>
        <p><strong>{{ $administrasi->getJenisAdministrasiLabel() }}</strong></p>
        <p>Kode: {{ $administrasi->kode_administrasi }}</p>
    </div>

    <!-- Informasi Utama -->
    <div class="info-box">
        <div class="info-row">
            <span class="label">Nomor Surat/Dokumen:</span>
            <span class="value">{{ $administrasi->nomor_surat ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Perihal:</span>
            <span class="value"><strong>{{ $administrasi->perihal }}</strong></span>
        </div>
        <div class="info-row">
            <span class="label">Tanggal Surat:</span>
            <span class="value">{{ $administrasi->tanggal_surat ? $administrasi->tanggal_surat->format('d F Y') : '-' }}</span>
        </div>
        
        @if($administrasi->isMasuk())
        <div class="info-row">
            <span class="label">Pengirim:</span>
            <span class="value">{{ $administrasi->pengirim ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Tanggal Terima:</span>
            <span class="value">{{ $administrasi->tanggal_terima ? $administrasi->tanggal_terima->format('d F Y, H:i') : '-' }}</span>
        </div>
        @endif
        
        @if($administrasi->isKeluar())
        <div class="info-row">
            <span class="label">Penerima:</span>
            <span class="value">{{ $administrasi->penerima ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Tanggal Kirim:</span>
            <span class="value">{{ $administrasi->tanggal_kirim ? $administrasi->tanggal_kirim->format('d F Y, H:i') : '-' }}</span>
        </div>
        @endif
        
        <div class="info-row">
            <span class="label">Prioritas:</span>
            <span class="value">
                @if($administrasi->prioritas == 'urgent')
                    <span class="badge badge-danger">URGENT</span>
                @elseif($administrasi->prioritas == 'tinggi')
                    <span class="badge badge-warning">TINGGI</span>
                @elseif($administrasi->prioritas == 'normal')
                    <span class="badge badge-primary">NORMAL</span>
                @else
                    <span class="badge badge-info">RENDAH</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span class="value">
                @if($administrasi->status == 'selesai')
                    <span class="badge badge-success">SELESAI</span>
                @elseif($administrasi->status == 'proses')
                    <span class="badge badge-info">PROSES</span>
                @elseif($administrasi->status == 'ditolak')
                    <span class="badge badge-danger">DITOLAK</span>
                @else
                    <span class="badge badge-warning">PENDING</span>
                @endif
            </span>
        </div>
    </div>

    <!-- Ringkasan -->
    @if($administrasi->ringkasan)
    <div class="section-title">RINGKASAN</div>
    <div style="text-align: justify; padding: 10px; background: #f8f9fa;">
        {{ $administrasi->ringkasan }}
    </div>
    @endif

    <!-- Disposisi -->
    @if($administrasi->disposisi_ke)
    <div class="section-title">DISPOSISI</div>
    <div style="padding: 10px; background: #fff3cd;">
        <strong>Disposisi Kepada:</strong> {{ $administrasi->disposisi_ke }}
    </div>
    @endif

    <!-- History Tindak Lanjut -->
    @if($administrasi->tindakLanjut->count() > 0)
    <div class="section-title">HISTORY TINDAK LANJUT ({{ $administrasi->tindakLanjut->count() }} Tindakan)</div>
    @foreach($administrasi->tindakLanjut as $index => $tindak)
    <div class="tindak-lanjut-item">
        <div style="font-weight: bold; margin-bottom: 5px;">
            {{ $index + 1 }}. {{ $tindak->getJenisTindakLanjutLabel() }} - {{ $tindak->judul_tindak_lanjut }}
        </div>
        <div style="margin-bottom: 3px;">
            <span class="label">Kode:</span> {{ $tindak->kode_tindak_lanjut }}
        </div>
        <div style="margin-bottom: 3px;">
            <span class="label">Status:</span>
            @if($tindak->status_tindak_lanjut == 'selesai')
                <span class="badge badge-success">SELESAI</span>
            @elseif($tindak->status_tindak_lanjut == 'proses')
                <span class="badge badge-info">PROSES</span>
            @elseif($tindak->status_tindak_lanjut == 'ditolak')
                <span class="badge badge-danger">DITOLAK</span>
            @else
                <span class="badge badge-warning">PENDING</span>
            @endif
        </div>
        @if($tindak->deskripsi_tindak_lanjut)
        <div style="margin-top: 5px; font-style: italic; color: #666;">
            {{ $tindak->deskripsi_tindak_lanjut }}
        </div>
        @endif
        
        <!-- Detail Pencairan Dana -->
        @if($tindak->jenis_tindak_lanjut == 'pencairan_dana' && $tindak->nominal_pencairan)
        <div style="margin-top: 5px; background: #d4edda; padding: 5px; border-radius: 3px;">
            <strong>Nominal: {{ $tindak->formatNominalPencairan() }}</strong>
            @if($tindak->nama_penerima_dana)
                <br>Penerima: {{ $tindak->nama_penerima_dana }}
            @endif
            @if($tindak->tanggal_pencairan)
                <br>Tanggal: {{ $tindak->tanggal_pencairan->format('d F Y') }}
            @endif
        </div>
        @endif
        
        <!-- Detail Disposisi -->
        @if($tindak->jenis_tindak_lanjut == 'disposisi')
        <div style="margin-top: 5px;">
            @if($tindak->disposisi_kepada)
                <br><strong>Kepada:</strong> {{ $tindak->disposisi_kepada }}
            @endif
            @if($tindak->instruksi_disposisi)
                <br><strong>Instruksi:</strong> {{ $tindak->instruksi_disposisi }}
            @endif
        </div>
        @endif
        
        <div style="margin-top: 5px; font-size: 10px; color: #666;">
            Tanggal: {{ $tindak->created_at->format('d/m/Y H:i') }}
        </div>
    </div>
    @endforeach
    @endif

    <!-- Catatan dan Keterangan -->
    @if($administrasi->catatan || $administrasi->keterangan)
    <div class="section-title">CATATAN & KETERANGAN</div>
    <div style="padding: 10px; background: #f8f9fa;">
        @if($administrasi->catatan)
        <div style="margin-bottom: 10px;">
            <strong>Catatan:</strong><br>
            {{ $administrasi->catatan }}
        </div>
        @endif
        
        @if($administrasi->keterangan)
        <div>
            <strong>Keterangan:</strong><br>
            {{ $administrasi->keterangan }}
        </div>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat oleh: <strong>{{ $administrasi->creator->name ?? 'System' }}</strong></p>
        <p>Tanggal Cetak: {{ now()->format('d F Y, H:i:s') }} WIB</p>
        <p>Kode Administrasi: {{ $administrasi->kode_administrasi }}</p>
    </div>
</body>
</html>
