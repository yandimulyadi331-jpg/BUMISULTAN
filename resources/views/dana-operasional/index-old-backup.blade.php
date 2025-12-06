@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="text-white mb-2"><i class="ti ti-cash me-2"></i>DANA OPERASIONAL HARIAN</h3>
                    <p class="mb-0">Sistem Pengajuan, Pencairan & Realisasi Dana Operasional</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Transaksi</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                            <i class="ti ti-upload me-1"></i> Import Excel
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDownloadPdf">
                            <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                        </button>
                        <a href="{{ route('dana-operasional.download-template') }}" class="btn btn-info btn-sm">
                            <i class="ti ti-file-download me-1"></i> Download Template
                        </a>
                    </div>
                </div>
                
                <!-- Filter Pencarian -->
                <div class="card-body border-bottom">
                    <form id="formFilterTransaksi" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tipe Filter</label>
                            <select class="form-select" id="filter_tipe" name="filter_tipe">
                                <option value="range" {{ request('filter_tipe') == 'range' ? 'selected' : '' }}>Range Tanggal</option>
                                <option value="bulan" {{ request('filter_tipe', 'bulan') == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                                <option value="tahun" {{ request('filter_tipe') == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="filter_tanggal_awal_wrapper">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="filter_tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                        </div>
                        
                        <div class="col-md-3" id="filter_tanggal_akhir_wrapper">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="filter_tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                        </div>
                        
                        <div class="col-md-3" id="filter_bulan_wrapper" style="display: none;">
                            <label class="form-label">Pilih Bulan</label>
                            <input type="month" class="form-control" id="filter_bulan" name="bulan" value="{{ request('bulan', date('Y-m')) }}">
                        </div>
                        
                        <div class="col-md-3" id="filter_tahun_wrapper" style="display: none;">
                            <label class="form-label">Pilih Tahun</label>
                            <select class="form-select" id="filter_tahun" name="tahun">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-search me-1"></i> Cari
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetFilter()">
                                <i class="bx bx-reset me-1"></i> Reset
                            </button>
                        </div>
                    </form>
                    
                    @if(isset($tanggalAwal) && isset($tanggalAkhir))
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Filter Aktif:</strong> 
                        Menampilkan transaksi dari <strong>{{ $tanggalAwal->format('d M Y') }}</strong> 
                        sampai <strong>{{ $tanggalAkhir->format('d M Y') }}</strong>
                    </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="3%" rowspan="2" style="vertical-align: middle;">NO</th>
                                    <th width="8%" rowspan="2" style="vertical-align: middle;">
                                        TANGGAL<br>
                                        <small class="text-muted fw-normal">DATE</small>
                                    </th>
                                    <th width="8%" rowspan="2" style="vertical-align: middle;">
                                        NO. TRANSAKSI<br>
                                        <small class="text-muted fw-normal">TRANSACTION NO</small>
                                    </th>
                                    <th width="20%" rowspan="2" style="vertical-align: middle;">
                                        KETERANGAN<br>
                                        <small class="text-muted fw-normal">REMARKS</small>
                                    </th>
                                    <th class="text-center" width="10%">
                                        DANA MASUK (IDR)<br>
                                        <small class="text-muted fw-normal">INCOMING TRANSACTIONS</small>
                                    </th>
                                    <th class="text-center" width="10%">
                                        DANA KELUAR (IDR)<br>
                                        <small class="text-muted fw-normal">OUTGOING TRANSACTIONS</small>
                                    </th>
                                    <th class="text-center" width="10%" rowspan="2" style="vertical-align: middle;">
                                        SALDO (IDR)<br>
                                        <small class="text-muted fw-normal">BALANCE</small>
                                    </th>
                                    <th class="text-center" width="6%" rowspan="2" style="vertical-align: middle;">
                                        FOTO<br>
                                        <small class="text-muted fw-normal">PHOTO</small>
                                    </th>
                                    <th class="text-center" width="12%" rowspan="2" style="vertical-align: middle;">
                                        AKSI<br>
                                        <small class="text-muted fw-normal">ACTIONS</small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 
                                    LOGIKA TAMPILAN PER HARI:
                                    1. Saldo Awal Hari Ini = Saldo Akhir kemarin (terakumulasi dari hari sebelumnya)
                                    2. Pencairan Dana (jika ada) - Dana Masuk dari pengajuan yang dicairkan
                                    3. Semua Transaksi Realisasi (dari pengajuan manual + import Excel)
                                       - Running balance dihitung real-time: Masuk (+), Keluar (-)
                                    4. Tombol Tambah Transaksi (selalu ada di akhir transaksi)
                                    5. Saldo Akhir Hari Ini = Saldo Awal + Total Masuk - Total Keluar
                                       - Saldo akhir ini akan menjadi Saldo Awal untuk hari berikutnya
                                    
                                    Integrasi: Saldo hari ke hari saling terintegrasi otomatis
                                --}}
                                @php
                                    $no = 1;
                                    $runningBalance = 0; // Initial, akan di-set per hari
                                @endphp
                                
                                @forelse($riwayatSaldo as $saldo)
                                    @php
                                        // LOGIKA RUNNING BALANCE PER HARI:
                                        // Setiap hari START dari saldo_awal (yang sudah di-set dari hari kemarin)
                                        // Ini memastikan saldo baris pertama setiap hari = saldo awal hari itu
                                        
                                        // SIMPAN saldo awal hari ini untuk tracking
                                        $saldoAwalHariIni = $saldo->saldo_awal;
                                        
                                        // Set running balance ke saldo awal hari ini
                                        // Ini akan tampil di baris "Saldo Awal Hari Ini"
                                        $runningBalance = $saldoAwalHariIni;
                                        
                                        // Hitung TOTAL MASUK dan TOTAL KELUAR untuk hari ini (untuk tampilan summary)
                                        $totalMasukHariIni = 0;
                                        $totalKeluarHariIni = 0;
                                        
                                        // 1. Saldo Awal masuk ke total (tapi tidak ubah running balance dulu)
                                        if ($saldo->saldo_awal > 0) {
                                            $totalMasukHariIni += $saldo->saldo_awal;
                                        } elseif ($saldo->saldo_awal < 0) {
                                            $totalKeluarHariIni += abs($saldo->saldo_awal);
                                        }
                                        
                                        // 2. Dana Masuk dari Pencairan - TIDAK DIGUNAKAN LAGI
                                        // (dana_masuk sekarang = 0, tidak ada pencairan dari pengajuan)
                                        
                                        // 3. Transaksi dari Pengajuan Manual
                                        if ($saldo->pengajuan && $saldo->pengajuan->realisasi->count() > 0) {
                                            foreach ($saldo->pengajuan->realisasi as $realisasi) {
                                                if ($realisasi->tipe_transaksi == 'masuk') {
                                                    $totalMasukHariIni += $realisasi->nominal;
                                                } else {
                                                    $totalKeluarHariIni += $realisasi->nominal;
                                                }
                                            }
                                        }
                                        
                                        // 4. Transaksi dari Import Excel
                                        $tanggalKey = $saldo->tanggal->format('Y-m-d');
                                        if (isset($realisasiPerTanggal[$tanggalKey])) {
                                            $realisasiTanpaAjuan = $realisasiPerTanggal[$tanggalKey]->where('pengajuan_id', null);
                                            foreach ($realisasiTanpaAjuan as $realisasi) {
                                                if ($realisasi->tipe_transaksi == 'masuk') {
                                                    $totalMasukHariIni += $realisasi->nominal;
                                                } else {
                                                    $totalKeluarHariIni += $realisasi->nominal;
                                                }
                                            }
                                        }
                                        
                                        // Saldo Akhir (untuk summary di bawah)
                                        $saldoAkhirHariIni = $saldo->saldo_awal + $totalMasukHariIni - $totalKeluarHariIni;
                                    @endphp
                                    
                                    {{-- 1. SALDO AWAL HARI INI (dari kemarin) --}}
                                    @if($saldo->saldo_awal != 0)
                                    <tr class="table-secondary">
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>
                                            <strong>{{ $saldo->tanggal->format('d M Y') }}</strong><br>
                                            <small class="text-muted">00:00:00 WIB</small>
                                        </td>
                                        <td colspan="2">
                                            <strong>Saldo Awal Hari Ini</strong><br>
                                            <small class="text-muted">Opening Balance (dari kemarin)</small>
                                        </td>
                                        {{-- LOGIKA BARU: 
                                            - Saldo POSITIF (+) → Masuk ke kolom DANA MASUK 
                                            - Saldo NEGATIF (-) → Masuk ke kolom DANA KELUAR 
                                        --}}
                                        <td class="text-end">
                                            @if($saldo->saldo_awal > 0)
                                                <strong class="text-success">{{ number_format($saldo->saldo_awal, 2, ',', '.') }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($saldo->saldo_awal < 0)
                                                <strong class="text-danger">{{ number_format(abs($saldo->saldo_awal), 2, ',', '.') }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <strong class="{{ $runningBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format(abs($runningBalance), 2, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                    </tr>
                                    @endif

                                    {{-- 2. PENCAIRAN DANA - TIDAK DIGUNAKAN LAGI --}}
                                    {{-- Dana masuk sekarang = 0 karena tidak ada pencairan dari pengajuan --}}
                                    {{-- Semua transaksi sudah tercatat di realisasi_dana_operasional --}}

                                    {{-- 3. TRANSAKSI REALISASI DARI PENGAJUAN (Manual Entry) --}}
                                    @if($saldo->pengajuan && $saldo->pengajuan->realisasi->count() > 0)
                                        @foreach($saldo->pengajuan->realisasi as $realisasi)
                                        @php
                                            // Gunakan saldo_running dari database (sudah dihitung saat import)
                                            // Jika ada saldo_running, gunakan itu. Jika tidak, hitung manual
                                            if ($realisasi->saldo_running !== null) {
                                                $runningBalance = $realisasi->saldo_running;
                                            } else {
                                                // Fallback: hitung manual jika belum ada saldo_running
                                                if ($realisasi->tipe_transaksi == 'masuk') {
                                                    $runningBalance += $realisasi->nominal;
                                                } else {
                                                    $runningBalance -= $realisasi->nominal;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>
                                                <strong>{{ $realisasi->tanggal_realisasi->format('d M Y') }}</strong><br>
                                                <small class="text-muted">{{ $realisasi->created_at->format('H:i:s') }} WIB</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-primary">{{ $realisasi->nomor_realisasi }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $realisasi->uraian }}</strong><br>
                                                @if($realisasi->kategori)
                                                    <span class="badge bg-label-info">{{ $realisasi->kategori }}</span>
                                                @endif
                                                @if($realisasi->keterangan)
                                                    <br><small class="text-muted">{{ $realisasi->keterangan }}</small>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($realisasi->tipe_transaksi == 'masuk')
                                                    <strong class="text-success">{{ number_format($realisasi->nominal, 2, ',', '.') }}</strong>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($realisasi->tipe_transaksi == 'keluar')
                                                    <strong class="text-danger">{{ number_format($realisasi->nominal, 2, ',', '.') }}</strong>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <strong class="{{ $runningBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format(abs($runningBalance), 2, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                @if($realisasi->file_bukti)
                                                    <button type="button" class="btn btn-sm btn-icon btn-outline-info" 
                                                            onclick="window.open('{{ Storage::url($realisasi->file_bukti) }}', '_blank')">
                                                        <i class="bx bx-image"></i>
                                                    </button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle hide-arrow" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('dana-operasional.detail', $realisasi->id) }}">
                                                            <i class="bx bx-show me-1"></i> Detail
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    
                                    {{-- 4. TRANSAKSI REALISASI DARI IMPORT EXCEL (Tanpa Pengajuan) --}}
                                    @php
                                        $tanggalKey = $saldo->tanggal->format('Y-m-d');
                                        $realisasiTanpaAjuan = isset($realisasiPerTanggal[$tanggalKey]) 
                                            ? $realisasiPerTanggal[$tanggalKey]->where('pengajuan_id', null) 
                                            : collect([]);
                                    @endphp
                                    
                                    @foreach($realisasiTanpaAjuan as $realisasi)
                                    @php
                                        // Gunakan saldo_running dari database (sudah dihitung saat import Excel)
                                        // Ini memastikan saldo sesuai dengan urutan Excel yang di-upload
                                        if ($realisasi->saldo_running !== null) {
                                            $runningBalance = $realisasi->saldo_running;
                                        } else {
                                            // Fallback: hitung manual jika belum ada saldo_running
                                            if ($realisasi->tipe_transaksi == 'masuk') {
                                                $runningBalance += $realisasi->nominal;
                                            } else {
                                                $runningBalance -= $realisasi->nominal;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>
                                            <strong>{{ $realisasi->tanggal_realisasi->format('d M Y') }}</strong><br>
                                            <small class="text-muted">{{ $realisasi->created_at->format('H:i:s') }} WIB</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-primary">{{ $realisasi->nomor_realisasi }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $realisasi->uraian }}</strong><br>
                                            @if($realisasi->kategori)
                                                <span class="badge bg-label-info">{{ $realisasi->kategori }}</span>
                                            @endif
                                            @if($realisasi->keterangan)
                                                <br><small class="text-muted">{{ $realisasi->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($realisasi->tipe_transaksi == 'masuk')
                                                <strong class="text-success">{{ number_format($realisasi->nominal, 2, ',', '.') }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($realisasi->tipe_transaksi == 'keluar')
                                                <strong class="text-danger">{{ number_format($realisasi->nominal, 2, ',', '.') }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <strong class="{{ $runningBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format(abs($runningBalance), 2, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            @if($realisasi->foto_bukti)
                                                <button type="button" class="btn btn-sm btn-success" onclick="showFoto('{{ Storage::url($realisasi->foto_bukti) }}')" title="Lihat Foto">
                                                    <i class="bx bx-image-alt"></i> Lihat
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="uploadFoto({{ $realisasi->id }})" title="Upload Foto">
                                                    <i class="bx bx-cloud-upload"></i> Upload
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-info px-2" onclick="detailTransaksi({{ $realisasi->id }})" title="Detail">
                                                    <i class="bx bx-info-circle fs-5"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning px-2" onclick="editTransaksi({{ $realisasi->id }})" title="Edit">
                                                    <i class="bx bx-edit-alt fs-5"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger px-2" onclick="hapusTransaksi({{ $realisasi->id }})" title="Hapus">
                                                    <i class="bx bx-trash-alt fs-5"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                    {{-- 5. TOMBOL TAMBAH TRANSAKSI (Selalu ada di akhir sebelum Saldo Akhir) --}}
                                    <tr class="add-transaction-row">
                                        <td colspan="9" class="p-0">
                                            <div style="display: flex; align-items: center; padding: 8px 0;">
                                                <div style="flex: 1; height: 2px; background: linear-gradient(to right, transparent, #d0d0d0 20%, #d0d0d0 80%, transparent);"></div>
                                                <button type="button" class="btn btn-sm btn-outline-primary mx-2" 
                                                        onclick="tambahTransaksiManual('{{ $saldo->tanggal->format('Y-m-d') }}', {{ $saldo->pengajuan ? $saldo->pengajuan->id : 'null' }})" 
                                                        title="Tambah Transaksi Manual"
                                                        style="border-radius: 50%; width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bx bx-plus" style="font-size: 20px;"></i>
                                                </button>
                                                <div style="flex: 1; height: 2px; background: linear-gradient(to right, transparent, #d0d0d0 20%, #d0d0d0 80%, transparent);"></div>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- 6. SALDO AKHIR - Hanya tampil kalau ada transaksi di tanggal ini --}}
                                    @php
                                        $tanggalKey = $saldo->tanggal->format('Y-m-d');
                                        $adaTransaksiPengajuan = $saldo->pengajuan && $saldo->pengajuan->realisasi->count() > 0;
                                        $adaTransaksiImport = isset($realisasiPerTanggal[$tanggalKey]) && $realisasiPerTanggal[$tanggalKey]->where('pengajuan_id', null)->count() > 0;
                                        $adaTransaksi = $adaTransaksiPengajuan || $adaTransaksiImport || $saldo->dana_masuk > 0;
                                    @endphp
                                    
                                    @if($adaTransaksi)
                                    <tr class="table-info">
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td colspan="3">
                                            <strong>Saldo Akhir - {{ $saldo->tanggal->format('d M Y') }}</strong><br>
                                            <small class="text-muted">Closing Balance</small>
                                        </td>
                                        <td class="text-end">
                                            <small class="text-muted">Total Masuk:</small><br>
                                            <strong class="text-success">{{ number_format($totalMasukHariIni, 2, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-end">
                                            <small class="text-muted">Total Keluar:</small><br>
                                            <strong class="text-danger">{{ number_format($totalKeluarHariIni, 2, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="{{ $saldoAkhirHariIni >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format(abs($saldoAkhirHariIni), 2, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                    </tr>
                                    @endif
                                    
                                    {{-- Separator antar hari - hanya tampil kalau ada transaksi --}}
                                    @if($adaTransaksi)
                                    <tr class="table-light">
                                        <td colspan="9" style="height: 10px;"></td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="ti ti-database-off ti-lg text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada transaksi dalam 7 hari terakhir</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="modalDetailTransaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white">
                    <i class="bx bx-show me-2"></i>Detail Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailTransaksiContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Transaksi -->
<div class="modal fade" id="modalEditTransaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">
                    <i class="bx bx-edit me-2"></i>Edit Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditTransaksi">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editTransaksiContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Transaksi Manual -->
<div class="modal fade" id="modalTambahTransaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="bx bx-plus-circle me-2"></i>Tambah Transaksi Manual
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambahTransaksi">
                @csrf
                <input type="hidden" id="tambah_tanggal">
                <input type="hidden" id="tambah_pengajuan_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tambah_tanggal_display" name="tanggal_realisasi" 
                                    value="{{ date('Y-m-d') }}" 
                                    max="{{ date('Y-m-d') }}" 
                                    required>
                                <small class="text-muted">Bisa pilih tanggal lampau untuk input data historis</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipe_transaksi" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="keluar">Dana Keluar</option>
                                    <option value="masuk">Dana Masuk</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Transport & Kendaraan">Transport & Kendaraan</option>
                            <option value="ATK & Perlengkapan">ATK & Perlengkapan</option>
                            <option value="Konsumsi">Konsumsi</option>
                            <option value="Utilitas">Utilitas</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Kebersihan">Kebersihan</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Komunikasi">Komunikasi</option>
                            <option value="Administrasi">Administrasi</option>
                            <option value="Operasional">Operasional</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan/Uraian <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="uraian" rows="3" required placeholder="Contoh: Bensin motor untuk operasional"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="nominal" step="0.01" required placeholder="0.00">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea class="form-control" name="keterangan" rows="2" placeholder="Catatan opsional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-save me-1"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload Foto -->
<div class="modal fade" id="modalUploadFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">
                    <i class="bx bx-upload me-2"></i>Upload Foto Bukti
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUploadFoto" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="upload_realisasi_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="foto" id="inputFoto" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                    </div>
                    <div id="previewContainer" class="text-center" style="display:none;">
                        <img id="previewImage" src="" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-upload me-1"></i> Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="bx bx-image me-2"></i>Foto Bukti Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="previewFotoImg" src="" alt="Foto Bukti" class="img-fluid rounded" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <a id="downloadFotoBtn" href="" download class="btn btn-primary">
                    <i class="bx bx-download me-1"></i> Download Foto
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-upload me-2"></i>Import Transaksi dari Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dana-operasional.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Import Excel dengan Tanggal:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Download <strong>template Excel</strong> terlebih dahulu</li>
                            <li>Isi <strong>4 kolom wajib</strong>: 
                                <span class="badge bg-primary">Tanggal</span>, 
                                <span class="badge bg-primary">Keterangan</span>, 
                                <span class="badge bg-success">Dana Masuk</span>, 
                                <span class="badge bg-danger">Dana Keluar</span>
                            </li>
                            <li>Format tanggal: <strong>2025-01-05</strong> atau <strong>05/01/2025</strong></li>
                            <li>Isi Dana Masuk ATAU Dana Keluar (salah satu saja)</li>
                            <li>Sistem otomatis: Deteksi kategori, Generate nomor transaksi</li>
                            <li>Format: .xlsx, .xls, .csv (Max: 5MB)</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih File Excel <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Format: .xlsx, .xls, .csv (Max: 5MB)</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Penting:</strong> Pastikan format tanggal sesuai template. Tanggal bisa lampau/historis!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-upload me-1"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Download PDF -->
<div class="modal fade" id="modalDownloadPdf" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-file-type-pdf me-2"></i>Download Laporan PDF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dana-operasional.export-pdf') }}" method="GET" id="formDownloadPdf">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Laporan PDF Profesional</strong>
                        <p class="mb-0 mt-2">Desain bergaya bank internasional dengan header BUMI SULTAN lengkap, watermark keamanan, dan ringkasan keuangan detail.</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Dari <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_dari" id="pdf_tanggal_dari" value="{{ date('Y-m-01') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Sampai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_sampai" id="pdf_tanggal_sampai" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Quick Filters -->
                    <div class="mb-3">
                        <label class="form-label">Filter Cepat:</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-filter-pdf" data-filter="today">Hari Ini</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-filter-pdf" data-filter="week">Minggu Ini</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-filter-pdf" data-filter="month">Bulan Ini</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-filter-pdf" data-filter="year">Tahun Ini</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnDownloadPdf">
                        <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Debug: Test jika script loaded
console.log('Dana Operasional scripts loaded');

// Quick Filter PDF Buttons
$('.quick-filter-pdf').on('click', function() {
    const filter = $(this).data('filter');
    const today = new Date();
    let startDate, endDate;

    switch(filter) {
        case 'today':
            startDate = today;
            endDate = today;
            break;
        case 'week':
            const firstDayOfWeek = new Date(today);
            firstDayOfWeek.setDate(today.getDate() - today.getDay());
            startDate = firstDayOfWeek;
            endDate = today;
            break;
        case 'month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = today;
            break;
        case 'year':
            startDate = new Date(today.getFullYear(), 0, 1); // 1 Januari tahun ini
            endDate = today;
            break;
    }

    $('#pdf_tanggal_dari').val(formatDate(startDate));
    $('#pdf_tanggal_sampai').val(formatDate(endDate));

    // Highlight active button
    $('.quick-filter-pdf').removeClass('active');
    $(this).addClass('active');
});

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Form PDF validation
$('#formDownloadPdf').on('submit', function(e) {
    const tanggalDari = new Date($('#pdf_tanggal_dari').val());
    const tanggalSampai = new Date($('#pdf_tanggal_sampai').val());

    if (tanggalSampai < tanggalDari) {
        e.preventDefault();
        alert('Tanggal sampai tidak boleh lebih kecil dari tanggal dari!');
        return false;
    }

    // Show loading state
    const btnDownload = $('#btnDownloadPdf');
    btnDownload.prop('disabled', true);
    btnDownload.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Menghasilkan PDF...');

    // Reset button after 3 seconds
    setTimeout(function() {
        btnDownload.prop('disabled', false);
        btnDownload.html('<i class="ti ti-file-type-pdf me-1"></i> Download PDF');
        $('#modalDownloadPdf').modal('hide');
    }, 3000);
});

// Filter Type Change Handler
$('#filter_tipe').on('change', function() {
    const tipe = $(this).val();
    
    // Hide all filter fields first
    $('#filter_tanggal_awal_wrapper, #filter_tanggal_akhir_wrapper, #filter_bulan_wrapper, #filter_tahun_wrapper').hide();
    
    if (tipe === 'range') {
        $('#filter_tanggal_awal_wrapper, #filter_tanggal_akhir_wrapper').show();
    } else if (tipe === 'bulan') {
        $('#filter_bulan_wrapper').show();
    } else if (tipe === 'tahun') {
        $('#filter_tahun_wrapper').show();
    }
});

// Initialize filter on page load
$(document).ready(function() {
    $('#filter_tipe').trigger('change');
});

// Submit Filter Form
$('#formFilterTransaksi').on('submit', function(e) {
    e.preventDefault();
    
    const tipe = $('#filter_tipe').val();
    let url = '/dana-operasional?';
    
    if (tipe === 'range') {
        const tanggalAwal = $('#filter_tanggal_awal').val();
        const tanggalAkhir = $('#filter_tanggal_akhir').val();
        
        if (!tanggalAwal || !tanggalAkhir) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih tanggal awal dan akhir'
            });
            return;
        }
        
        url += `filter_tipe=range&tanggal_awal=${tanggalAwal}&tanggal_akhir=${tanggalAkhir}`;
    } else if (tipe === 'bulan') {
        const bulan = $('#filter_bulan').val();
        url += `filter_tipe=bulan&bulan=${bulan}`;
    } else if (tipe === 'tahun') {
        const tahun = $('#filter_tahun').val();
        url += `filter_tipe=tahun&tahun=${tahun}`;
    }
    
    window.location.href = url;
});

// Reset Filter
function resetFilter() {
    window.location.href = '/dana-operasional';
}

// Upload Foto
function uploadFoto(id) {
    console.log('uploadFoto called with id:', id);
    $('#upload_realisasi_id').val(id);
    $('#inputFoto').val('');
    $('#previewContainer').hide();
    $('#modalUploadFoto').modal('show');
}

// Preview foto sebelum upload
$('#inputFoto').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImage').attr('src', e.target.result);
            $('#previewContainer').show();
        };
        reader.readAsDataURL(file);
    }
});

// Submit Upload Foto
$('#formUploadFoto').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = $('#upload_realisasi_id').val();
    
    $.ajax({
        url: `/dana-operasional/${id}/upload-foto`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#modalUploadFoto').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Foto berhasil diupload',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            let message = 'Gagal upload foto';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message
            });
        }
    });
});

// Preview Foto
function showFoto(url) {
    console.log('showFoto called with url:', url);
    $('#previewFotoImg').attr('src', url);
    $('#downloadFotoBtn').attr('href', url);
    $('#modalPreviewFoto').modal('show');
}

// Detail Transaksi
function detailTransaksi(id) {
    console.log('detailTransaksi called with id:', id);
    $('#modalDetailTransaksi').modal('show');
    
    $.ajax({
        url: `/dana-operasional/${id}/detail`,
        method: 'GET',
        success: function(response) {
            $('#detailTransaksiContent').html(response);
        },
        error: function() {
            $('#detailTransaksiContent').html(`
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle me-2"></i>
                    Gagal memuat detail transaksi
                </div>
            `);
        }
    });
}

// Edit Transaksi
function editTransaksi(id) {
    console.log('editTransaksi called with id:', id);
    $('#modalEditTransaksi').modal('show');
    
    $.ajax({
        url: `/dana-operasional/${id}/edit`,
        method: 'GET',
        success: function(response) {
            $('#editTransaksiContent').html(response);
        },
        error: function() {
            $('#editTransaksiContent').html(`
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle me-2"></i>
                    Gagal memuat form edit
                </div>
            `);
        }
    });
}

// Submit Edit Form
$('#formEditTransaksi').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = $('#edit_id').val();
    
    $.ajax({
        url: `/dana-operasional/${id}/update`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#modalEditTransaksi').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Transaksi berhasil diupdate',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            let message = 'Gagal mengupdate transaksi';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message
            });
        }
    });
});

// Hapus Transaksi
function hapusTransaksi(id) {
    console.log('hapusTransaksi called with id:', id);
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/dana-operasional/${id}/delete`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: 'Transaksi berhasil dihapus',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let message = 'Gagal menghapus transaksi';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: message
                    });
                }
            });
        }
    });
}

// Tambah Transaksi Manual
function tambahTransaksiManual(tanggal, pengajuanId) {
    console.log('tambahTransaksiManual called with tanggal:', tanggal, 'pengajuanId:', pengajuanId);
    
    // Set default tanggal dari parameter (bisa diubah user)
    $('#tambah_tanggal').val(tanggal);
    $('#tambah_tanggal_display').val(tanggal);
    $('#tambah_pengajuan_id').val(pengajuanId);
    
    // Reset form tapi keep tanggal
    const savedTanggal = $('#tambah_tanggal_display').val();
    $('#formTambahTransaksi')[0].reset();
    $('#tambah_tanggal_display').val(savedTanggal);
    
    $('#modalTambahTransaksi').modal('show');
}

// Submit Tambah Transaksi Form
$('#formTambahTransaksi').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const pengajuanId = $('#tambah_pengajuan_id').val();
    
    // Add pengajuan_id to formData
    if (pengajuanId && pengajuanId !== 'null') {
        formData.append('pengajuan_id', pengajuanId);
    }
    
    $.ajax({
        url: '/dana-operasional/create',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#modalTambahTransaksi').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Transaksi berhasil ditambahkan',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            let message = 'Gagal menambah transaksi';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message
            });
        }
    });
});
</script>
@endpush

