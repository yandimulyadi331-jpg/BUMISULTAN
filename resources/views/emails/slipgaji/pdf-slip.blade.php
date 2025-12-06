<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji {{ $karyawan->nama_karyawan }} - {{ $nama_bulan }} {{ $tahun }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 20px;
            font-size: 11px;
            line-height: 1.3;
        }
        .slip-struk {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            background: white;
            border: 2px solid #333;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .company-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 3px;
        }
        .slip-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
        }
        .periode {
            font-size: 10px;
            color: #666;
        }
        .employee-section {
            margin-bottom: 10px;
            border-bottom: 1px dotted #666;
            padding-bottom: 8px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 10px;
        }
        .label {
            font-weight: bold;
        }
        .value {
            text-align: right;
        }
        .section-title {
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            margin: 10px 0 5px 0;
            padding: 3px;
            border: 1px solid #333;
        }
        .earning {
            background: #e8f5e8;
        }
        .deduction {
            background: #fde8e8;
        }
        .adjustment {
            background: #e8f4f8;
        }
        .total-row {
            font-weight: bold;
            border-top: 1px dotted #333;
            padding-top: 3px;
            margin-top: 3px;
        }
        .net-salary {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 12px;
            padding: 8px;
            background: #f8f9fa;
            border: 2px solid #333;
            margin-top: 10px;
        }
        .work-info {
            font-size: 9px;
            color: #666;
            text-align: center;
            margin: 8px 0;
            border-top: 1px dotted #666;
            padding-top: 6px;
        }
        .footer {
            text-align: center;
            font-size: 8px;
            color: #888;
            margin-top: 10px;
            border-top: 1px dashed #333;
            padding-top: 8px;
        }
        .currency {
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="slip-struk">
        <!-- Header -->
        <div class="header">
            <div class="company-name">BUMI SULTAN</div>
            <div class="slip-title">SLIP GAJI</div>
            <div class="periode">{{ $nama_bulan }} {{ $tahun }}</div>
        </div>

        <!-- Employee Info -->
        <div class="employee-section">
            <div class="row">
                <span class="label">NIK:</span>
                <span class="value">{{ $karyawan->nik_show ?? $karyawan->nik }}</span>
            </div>
            <div class="row">
                <span class="label">Nama:</span>
                <span class="value">{{ $karyawan->nama_karyawan }}</span>
            </div>
            @if($karyawan->jabatan)
            <div class="row">
                <span class="label">Jabatan:</span>
                <span class="value">{{ $karyawan->jabatan->nama_jabatan }}</span>
            </div>
            @endif
            @if($karyawan->departemen)
            <div class="row">
                <span class="label">Dept:</span>
                <span class="value">{{ $karyawan->departemen->kode_dept }}</span>
            </div>
            @endif
        </div>

        <!-- Work Summary -->
        <div class="work-info">
            Untuk rincian lengkap, silakan akses aplikasi
        </div>

        <!-- Penghasilan -->
        <div class="section-title earning">PENGHASILAN</div>
        <div class="row">
            <span>Gaji Pokok</span>
            <span class="currency">-</span>
        </div>
        <div class="row">
            <span>Tunjangan</span>
            <span class="currency">-</span>
        </div>
        <div class="row total-row">
            <span>Sub Total</span>
            <span class="currency">-</span>
        </div>

        <!-- Potongan -->
        <div class="section-title deduction">POTONGAN</div>
        <div class="row">
            <span>Denda & Pot. Jam</span>
            <span class="currency">-</span>
        </div>
        <div class="row">
            <span>BPJS Kesehatan</span>
            <span class="currency">-</span>
        </div>
        <div class="row">
            <span>BPJS Ketenagakerjaan</span>
            <span class="currency">-</span>
        </div>
        <div class="row">
            <span>Potongan Pinjaman</span>
            <span class="currency">-</span>
        </div>
        <div class="row total-row">
            <span>Sub Total</span>
            <span class="currency">-</span>
        </div>

        <!-- Total -->
        <div class="net-salary">
            <span>GAJI BERSIH</span>
            <span class="currency">-</span>
        </div>

        <!-- Footer -->
        <div class="footer">
            Dicetak: {{ date('d/m/Y H:i') }}<br>
            Untuk detail lengkap, silakan hubungi HRD<br>
            Bumi Sultan | 0857-1537-5490
        </div>
    </div>
</body>
</html>
