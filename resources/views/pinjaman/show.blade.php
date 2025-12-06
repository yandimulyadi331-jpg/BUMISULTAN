@extends('layouts.app')

@section('content')
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 50px;
        padding-bottom: 30px;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: -30px;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }
    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .info-label {
        font-weight: 600;
        width: 200px;
        color: #495057;
    }
    .info-value {
        flex: 1;
        color: #212529;
    }
    .cicilan-card {
        transition: all 0.3s ease;
    }
    .cicilan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>

<!-- Header -->
<div class="bg-primary" style="padding: 2rem 0; margin: -1.5rem -1.5rem 1.5rem -1.5rem;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-1">
                    <i class="bi bi-file-text"></i> Detail Pinjaman
                </h3>
                <p class="text-white-50 mb-0">{{ $pinjaman->nomor_pinjaman }}</p>
            </div>
            <div>
                <a href="{{ route('pinjaman.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@php
    $cicilanTerlambat = $pinjaman->cicilan->where('status', 'terlambat');
    $totalDenda = $cicilanTerlambat->sum('denda');
@endphp

@if($cicilanTerlambat->count() > 0)
<div class="alert alert-danger alert-dismissible fade show">
    <h5 class="alert-heading">
        <i class="bi bi-exclamation-triangle-fill"></i> Peringatan Keterlambatan!
    </h5>
    <p class="mb-2">
        Terdapat <strong>{{ $cicilanTerlambat->count() }} cicilan terlambat</strong> dengan total denda 
        <strong>Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>
    </p>
    <hr>
    <small>
        <i class="bi bi-info-circle"></i> Segera lakukan pembayaran untuk menghindari denda yang terus bertambah (0.1% per hari dari sisa cicilan).
    </small>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <!-- Kolom Kiri -->
    <div class="col-lg-8">
        <!-- Info Pinjaman -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Pinjaman</h5>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">No. Pinjaman</div>
                    <div class="info-value"><strong>{{ $pinjaman->nomor_pinjaman }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori</div>
                    <div class="info-value">
                        <span class="badge {{ $pinjaman->kategori_peminjam == 'crew' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ strtoupper($pinjaman->kategori_peminjam) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nama Peminjam</div>
                    <div class="info-value">
                        <strong>{{ $pinjaman->nama_peminjam_lengkap }}</strong>
                        @if($pinjaman->kategori_peminjam == 'crew' && $pinjaman->karyawan)
                        <br><small class="text-muted">NIK: {{ $pinjaman->karyawan->nik }}</small>
                        @else
                        <br><small class="text-muted">NIK: {{ $pinjaman->nik_peminjam }}</small>
                        @endif
                    </div>
                </div>
                @if($pinjaman->kategori_peminjam == 'non_crew')
                <div class="info-row">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value">{{ $pinjaman->no_telp_peminjam }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Pekerjaan</div>
                    <div class="info-value">{{ $pinjaman->pekerjaan_peminjam }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $pinjaman->alamat_peminjam }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Tanggal Pengajuan</div>
                    <div class="info-value">{{ $pinjaman->tanggal_pengajuan->format('d F Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tujuan Pinjaman</div>
                    <div class="info-value">{{ $pinjaman->tujuan_pinjaman }}</div>
                </div>
                @if($pinjaman->keterangan)
                <div class="info-row">
                    <div class="info-label">Keterangan</div>
                    <div class="info-value">{{ $pinjaman->keterangan }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Detail Keuangan -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Detail Keuangan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Jumlah Pengajuan</div>
                            <div class="info-value">
                                <strong class="text-primary">Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @if($pinjaman->jumlah_disetujui)
                        <div class="info-row">
                            <div class="info-label">Jumlah Disetujui</div>
                            <div class="info-value">
                                <strong class="text-success">Rp {{ number_format($pinjaman->jumlah_disetujui, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Tenor</div>
                            <div class="info-value"><strong>{{ $pinjaman->tenor_bulan }} Bulan</strong></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Cicilan per Bulan</div>
                            <div class="info-value">
                                <strong class="text-info">Rp {{ number_format($pinjaman->cicilan_per_bulan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tanggal Jatuh Tempo</div>
                            <div class="info-value">
                                <span class="badge bg-info">
                                    <i class="bi bi-calendar-event"></i> 
                                    Setiap tanggal <strong>{{ $pinjaman->tanggal_jatuh_tempo_setiap_bulan ?? 1 }}</strong> per bulan
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($pinjaman->total_pinjaman > 0)
                        <div class="info-row">
                            <div class="info-label">Total Pinjaman</div>
                            <div class="info-value">
                                <strong class="text-danger">Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if(in_array($pinjaman->status, ['dicairkan', 'berjalan', 'lunas']))
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mb-3">Status Pembayaran</h6>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $pinjaman->persentase_pembayaran }}%">
                                {{ number_format($pinjaman->persentase_pembayaran, 1) }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">Terbayar:</small>
                                <strong class="text-success">Rp {{ number_format($pinjaman->total_terbayar, 0, ',', '.') }}</strong>
                            </div>
                            <div>
                                <small class="text-muted">Sisa:</small>
                                <strong class="text-danger">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @if($pinjaman->denda_keterlambatan > 0)
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Denda Keterlambatan: <strong>Rp {{ number_format($pinjaman->denda_keterlambatan, 0, ',', '.') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Jadwal Cicilan -->
        @if($pinjaman->cicilan->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Jadwal Cicilan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Cicilan Ke-</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah Cicilan</th>
                                <th>Status</th>
                                <th>Bukti Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pinjaman->cicilan as $cicilan)
                            <tr class="{{ $cicilan->status == 'lunas' ? 'table-success' : ($cicilan->status == 'terlambat' ? 'table-danger' : '') }}">
                                <td>
                                    <strong>{{ $cicilan->cicilan_ke }}</strong>
                                    @if($cicilan->is_hasil_tunda)
                                        <br><span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock-history"></i> Hasil Tunda
                                        </span>
                                    @endif
                                    @if($cicilan->is_ditunda)
                                        <br><span class="badge bg-info">
                                            <i class="bi bi-clock"></i> Ditunda
                                        </span>
                                    @endif
                                    @if($cicilan->status == 'terlambat')
                                        <br><span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle-fill"></i> TERLAMBAT
                                        </span>
                                    @endif
                                    @if($cicilan->sudah_dipotong && $pinjaman->kategori_peminjam == 'crew')
                                        <br><span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Dipotong Gaji
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $cicilan->tanggal_jatuh_tempo->format('d M Y') }}
                                    @if($cicilan->status == 'terlambat')
                                        <br><small class="text-danger fw-bold">
                                            <i class="bi bi-calendar-x"></i> Lewat {{ $cicilan->hari_terlambat }} hari
                                        </small>
                                    @endif
                                    @if($cicilan->is_ditunda && $cicilan->tanggal_ditunda)
                                        <br><small class="text-muted">Ditunda: {{ $cicilan->tanggal_ditunda->format('d M Y') }}</small>
                                    @endif
                                    @if($cicilan->sudah_dipotong && $cicilan->tanggal_dipotong)
                                        <br><small class="text-success">
                                            <i class="bi bi-check2"></i> Dipotong: {{ $cicilan->tanggal_dipotong->format('d M Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($cicilan->jumlah_cicilan, 0, ',', '.') }}</strong>
                                    @if($cicilan->status == 'terlambat' && $cicilan->denda > 0)
                                        <br><small class="text-danger">
                                            <i class="bi bi-exclamation-circle"></i> Denda: Rp {{ number_format($cicilan->denda, 0, ',', '.') }}
                                        </small>
                                        <br><small class="text-muted fw-bold">
                                            Total: Rp {{ number_format($cicilan->total_tagihan, 0, ',', '.') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $statusClass = [
                                        'belum_bayar' => 'secondary',
                                        'sebagian' => 'warning',
                                        'lunas' => 'success',
                                        'terlambat' => 'danger'
                                    ];
                                    @endphp
                                    <span class="badge bg-{{ $statusClass[$cicilan->status] ?? 'secondary' }}">
                                        {{ strtoupper(str_replace('_', ' ', $cicilan->status)) }}
                                    </span>
                                    @if($cicilan->hari_terlambat > 0 && $cicilan->status != 'lunas')
                                    <br><small class="text-danger">{{ $cicilan->hari_terlambat }} hari</small>
                                    @endif
                                </td>
                                <td>
                                    @if($cicilan->bukti_pembayaran)
                                        <button type="button" class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalBukti{{ $cicilan->id }}"
                                                title="Lihat bukti pembayaran">
                                            <i class="bi bi-image"></i> Lihat Foto
                                        </button>
                                    @else
                                        <span class="text-muted small">
                                            <i class="bi bi-x-circle"></i> Tidak ada
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($cicilan->status == 'lunas')
                                    <button class="btn btn-sm btn-success" disabled title="Cicilan sudah lunas">
                                        <i class="bi bi-check-circle-fill"></i> Lunas
                                    </button>
                                    <br><small class="text-muted">{{ $cicilan->tanggal_bayar ? $cicilan->tanggal_bayar->format('d M Y') : '-' }}</small>
                                    @elseif($pinjaman->status == 'lunas')
                                    <button class="btn btn-sm btn-secondary" disabled title="Pinjaman sudah lunas semua">
                                        <i class="bi bi-lock-fill"></i> Selesai
                                    </button>
                                    @elseif($cicilan->is_ditunda)
                                    <button class="btn btn-sm btn-info" disabled title="Cicilan ini ditunda, tidak perlu dibayar">
                                        <i class="bi bi-clock"></i> Ditunda
                                    </button>
                                    <br><small class="text-muted">{{ $cicilan->tanggal_ditunda ? $cicilan->tanggal_ditunda->format('d M Y') : '-' }}</small>
                                    @else
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                        data-bs-target="#modalBayar{{ $cicilan->id }}"
                                        title="Klik untuk bayar cicilan">
                                        <i class="bi bi-cash-coin"></i> Bayar
                                    </button>
                                    @if(!$cicilan->is_ditunda)
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                        data-bs-target="#modalTunda{{ $cicilan->id }}"
                                        title="Tunda cicilan ke akhir tenor">
                                        <i class="bi bi-clock-history"></i> Tunda
                                    </button>
                                    @endif
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Bukti Pembayaran -->
                            @if($cicilan->bukti_pembayaran)
                            <div class="modal fade" id="modalBukti{{ $cicilan->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-image"></i> Bukti Pembayaran Cicilan ke-{{ $cicilan->cicilan_ke }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted">Tanggal Bayar:</small><br>
                                                    <strong>{{ $cicilan->tanggal_bayar ? $cicilan->tanggal_bayar->format('d F Y H:i') : '-' }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">Metode Pembayaran:</small><br>
                                                    <strong>{{ strtoupper($cicilan->metode_pembayaran ?? '-') }}</strong>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted">Jumlah Dibayar:</small><br>
                                                    <strong class="text-success">Rp {{ number_format($cicilan->jumlah_dibayar, 0, ',', '.') }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">No. Referensi:</small><br>
                                                    <strong>{{ $cicilan->no_referensi ?? '-' }}</strong>
                                                </div>
                                            </div>
                                            @if($cicilan->keterangan)
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <small class="text-muted">Keterangan:</small><br>
                                                    <p class="mb-0">{{ $cicilan->keterangan }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            <hr>
                                            <div class="text-center">
                                                <img src="{{ Storage::url($cicilan->bukti_pembayaran) }}" 
                                                     alt="Bukti Pembayaran" 
                                                     class="img-fluid rounded"
                                                     style="max-height: 500px; cursor: pointer;"
                                                     onclick="window.open(this.src, '_blank')">
                                                <p class="text-muted small mt-2">
                                                    <i class="bi bi-info-circle"></i> Klik gambar untuk melihat ukuran penuh
                                                </p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ Storage::url($cicilan->bukti_pembayaran) }}" 
                                               class="btn btn-primary" 
                                               download
                                               target="_blank">
                                                <i class="bi bi-download"></i> Download Bukti
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Modal Bayar Cicilan -->
                            @if($cicilan->status != 'lunas')
                            <div class="modal fade" id="modalBayar{{ $cicilan->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('pinjaman.cicilan.bayar', $cicilan->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">Pembayaran Cicilan ke-{{ $cicilan->cicilan_ke }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <strong>Total Tagihan:</strong> 
                                                    Rp {{ number_format($cicilan->total_tagihan, 0, ',', '.') }}
                                                    @if($cicilan->denda > 0)
                                                    <br><small>(Termasuk denda: Rp {{ number_format($cicilan->denda, 0, ',', '.') }})</small>
                                                    @endif
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah Bayar</label>
                                                    <input type="number" name="jumlah_bayar" class="form-control" required
                                                        value="{{ $cicilan->total_tagihan }}" min="0">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Metode Pembayaran</label>
                                                    <select name="metode_pembayaran" class="form-select" required>
                                                        <option value="tunai">Tunai</option>
                                                        <option value="transfer">Transfer</option>
                                                        <option value="potong_gaji">Potong Gaji</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">No. Referensi <small>(opsional)</small></label>
                                                    <input type="text" name="no_referensi" class="form-control"
                                                        placeholder="No. transfer atau referensi">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Bukti Pembayaran <small>(opsional)</small></label>
                                                    <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="2"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Proses Pembayaran</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tunda Cicilan -->
                            @if(!$cicilan->is_ditunda && $cicilan->status != 'lunas')
                            <div class="modal fade" id="modalTunda{{ $cicilan->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('pinjaman.cicilan.tunda', $cicilan->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-clock-history"></i> Tunda Cicilan ke-{{ $cicilan->cicilan_ke }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle"></i> 
                                                    <strong>Perhatian!</strong>
                                                    <br>Menunda cicilan ini akan:
                                                    <ul class="mb-0 mt-2">
                                                        <li>Menambah 1 bulan tenor pinjaman</li>
                                                        <li>Membuat cicilan baru di akhir tenor</li>
                                                        <li>Cicilan ini akan ditandai sebagai "Ditunda"</li>
                                                    </ul>
                                                </div>

                                                <div class="mb-3">
                                                    <strong>Detail Cicilan:</strong><br>
                                                    <small class="text-muted">Tanggal Jatuh Tempo:</small> {{ $cicilan->tanggal_jatuh_tempo->format('d M Y') }}<br>
                                                    <small class="text-muted">Jumlah Cicilan:</small> <strong>Rp {{ number_format($cicilan->jumlah_cicilan, 0, ',', '.') }}</strong>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label required">Alasan Penundaan</label>
                                                    <textarea name="alasan_ditunda" class="form-control" rows="3" required
                                                        placeholder="Contoh: Karyawan sedang kesulitan keuangan, sakit, dll..."></textarea>
                                                    <small class="text-muted">Jelaskan alasan mengapa cicilan ini perlu ditunda</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="bi bi-clock-history"></i> Tunda Cicilan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Timeline History -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($pinjaman->history as $history)
                    <div class="timeline-item">
                        <div class="timeline-icon bg-primary">
                            <i class="bi bi-circle-fill"></i>
                        </div>
                        <div>
                            <strong>{{ ucfirst(str_replace('_', ' ', $history->aksi)) }}</strong>
                            @if($history->status_baru)
                            <span class="badge bg-info">{{ strtoupper($history->status_baru) }}</span>
                            @endif
                            <br>
                            <small class="text-muted">
                                {{ $history->created_at->format('d M Y H:i') }} - 
                                {{ $history->user_name }}
                            </small>
                            @if($history->keterangan)
                            <br><small>{{ $history->keterangan }}</small>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-flag"></i> Status Pinjaman</h5>
            </div>
            <div class="card-body text-center">
                @php
                $statusClass = [
                    'pengajuan' => 'warning',
                    'review' => 'info',
                    'disetujui' => 'primary',
                    'ditolak' => 'danger',
                    'dicairkan' => 'success',
                    'berjalan' => 'primary',
                    'lunas' => 'success',
                    'dibatalkan' => 'secondary'
                ];
                @endphp
                <h2 class="mb-3">
                    <span class="badge bg-{{ $statusClass[$pinjaman->status] ?? 'secondary' }}" style="font-size: 1.5rem;">
                        {{ strtoupper($pinjaman->status) }}
                    </span>
                </h2>

                @if($pinjaman->alasan_penolakan)
                <div class="alert alert-danger">
                    <strong>Alasan Penolakan:</strong><br>
                    {{ $pinjaman->alasan_penolakan }}
                </div>
                @endif

                <!-- Tombol Aksi Sesuai Status -->
                <div class="d-grid gap-2 mt-3">
                    @if($pinjaman->status == 'pengajuan')
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalReview">
                            <i class="bi bi-eye"></i> Review Pinjaman
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalApprove">
                            <i class="bi bi-check-circle"></i> Setujui Pinjaman
                        </button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalReject">
                            <i class="bi bi-x-circle"></i> Tolak Pinjaman
                        </button>
                    @elseif($pinjaman->status == 'review')
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalApprove">
                            <i class="bi bi-check-circle"></i> Setujui Pinjaman
                        </button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalReject">
                            <i class="bi bi-x-circle"></i> Tolak Pinjaman
                        </button>
                    @elseif($pinjaman->status == 'disetujui')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCairkan">
                            <i class="bi bi-cash-coin"></i> Cairkan Dana
                        </button>
                    @endif

                    @if(in_array($pinjaman->status, ['pengajuan', 'review']))
                        <a href="{{ route('pinjaman.edit', $pinjaman->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Pinjaman
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pengajuan Pinjaman Baru untuk Peminjam Ini -->
        @if(in_array($pinjaman->status, ['dicairkan', 'berjalan']))
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Pinjaman</h5>
            </div>
            <div class="card-body">
                <p class="mb-3 small">
                    <i class="bi bi-info-circle"></i> Tambahkan pinjaman baru untuk peminjam ini.
                    <strong>Angsuran akan digabung dengan angsuran yang sudah ada.</strong>
                </p>
                
                <div class="alert alert-info">
                    <strong>Sisa Pinjaman Saat Ini:</strong><br>
                    <h4 class="text-danger mb-0">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</h4>
                    <small>dari total Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</small>
                </div>
                
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalTambahPinjaman">
                    <i class="bi bi-plus-lg"></i> Tambah Pinjaman untuk {{ $pinjaman->nama_peminjam_lengkap }}
                </button>
                
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-calculator"></i> Sistem akan menghitung ulang cicilan dan menambahkan angsuran baru ke jadwal yang ada.
                </small>
            </div>
        </div>
        @endif

        <!-- Download Formulir Resmi -->
        <div class="card mb-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-file-pdf"></i> Formulir Resmi</h5>
            </div>
            <div class="card-body">
                <p class="mb-3 small text-muted">Download formulir pinjaman resmi dengan kop perusahaan dan tanda tangan</p>
                <a href="{{ route('pinjaman.download-formulir', $pinjaman->id) }}" 
                   class="btn btn-danger w-100" target="_blank">
                    <i class="bi bi-download"></i> Download Formulir PDF
                </a>
            </div>
        </div>

        <!-- Dokumen -->
        @if($pinjaman->dokumen_ktp || $pinjaman->dokumen_slip_gaji || $pinjaman->dokumen_pendukung_lain)
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-paperclip"></i> Dokumen</h5>
            </div>
            <div class="card-body">
                @if($pinjaman->dokumen_ktp)
                <a href="{{ Storage::url($pinjaman->dokumen_ktp) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="bi bi-file-earmark"></i> KTP
                </a>
                @endif
                @if($pinjaman->dokumen_slip_gaji)
                <a href="{{ Storage::url($pinjaman->dokumen_slip_gaji) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="bi bi-file-earmark"></i> Slip Gaji
                </a>
                @endif
                @if($pinjaman->dokumen_pendukung_lain)
                <a href="{{ Storage::url($pinjaman->dokumen_pendukung_lain) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                    <i class="bi bi-file-earmark"></i> Dokumen Lain
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Penjamin -->
        @if($pinjaman->nama_penjamin)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-shield-check"></i> Data Penjamin</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $pinjaman->nama_penjamin }}</strong></p>
                <small class="text-muted">{{ $pinjaman->hubungan_penjamin }}</small>
                @if($pinjaman->no_telp_penjamin)
                <p class="mb-1 mt-2"><i class="bi bi-telephone"></i> {{ $pinjaman->no_telp_penjamin }}</p>
                @endif
                @if($pinjaman->alamat_penjamin)
                <p class="mb-0"><small>{{ $pinjaman->alamat_penjamin }}</small></p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Review -->
<div class="modal fade" id="modalReview" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pinjaman.review', $pinjaman->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Review Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan Review</label>
                        <textarea name="catatan_review" class="form-control" rows="3" placeholder="Catatan review (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Tandai Sedang Direview</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="modalApprove" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pinjaman.approve', $pinjaman->id) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="approve">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Setujui Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Jumlah Pengajuan:</strong> Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Disetujui <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_disetujui" class="form-control" required
                            value="{{ $pinjaman->jumlah_pengajuan }}" min="0" step="100000">
                        <small class="text-muted">Bisa berbeda dari jumlah pengajuan</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Persetujuan</label>
                        <textarea name="catatan_persetujuan" class="form-control" rows="3" 
                            placeholder="Catatan persetujuan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui Pinjaman</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pinjaman.approve', $pinjaman->id) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="reject">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control" rows="4" required
                            placeholder="Jelaskan alasan penolakan pinjaman..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pinjaman</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cairkan -->
<div class="modal fade" id="modalCairkan" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pinjaman.cairkan', $pinjaman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Pencairan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <strong>Jumlah Disetujui:</strong> Rp {{ number_format($pinjaman->jumlah_disetujui ?? $pinjaman->jumlah_pengajuan, 0, ',', '.') }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pencairan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pencairan" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pencairan <span class="text-danger">*</span></label>
                        <select name="metode_pencairan" class="form-select" required id="metode_pencairan">
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div id="transfer-fields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">No. Rekening Tujuan</label>
                            <input type="text" name="no_rekening_tujuan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Pencairan</label>
                        <input type="file" name="bukti_pencairan" class="form-control" accept="image/*,.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Cairkan Dana</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Pinjaman -->
<div class="modal fade" id="modalTambahPinjaman" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('pinjaman.tambah-pinjaman', $pinjaman->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> Tambah Pinjaman untuk {{ $pinjaman->nama_peminjam_lengkap }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Info Pinjaman Saat Ini -->
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> <strong>Pinjaman Saat Ini:</strong></h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small>Nomor Pinjaman:</small><br>
                                <strong>{{ $pinjaman->nomor_pinjaman }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small>Status:</small><br>
                                <span class="badge bg-primary">{{ strtoupper($pinjaman->status) }}</span>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-md-4">
                                <small>Total Pinjaman:</small><br>
                                <strong>Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small>Terbayar:</small><br>
                                <strong class="text-success">Rp {{ number_format($pinjaman->total_terbayar, 0, ',', '.') }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small>Sisa:</small><br>
                                <strong class="text-danger">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah Pinjaman -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Jumlah Pinjaman Tambahan</label>
                                <input type="number" name="jumlah_tambahan" class="form-control" 
                                    placeholder="Contoh: 2000000" 
                                    min="100000" step="100000" required id="jumlah_tambahan">
                                <small class="text-muted">Min. Rp 100.000</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Cicilan per Bulan Baru</label>
                                <input type="number" name="cicilan_baru" class="form-control" 
                                    placeholder="Contoh: 500000" 
                                    min="10000" step="10000" required id="cicilan_baru">
                                <small class="text-muted">Akan dihitung ulang dengan sisa pinjaman</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Tujuan Pinjaman Tambahan</label>
                        <textarea name="tujuan_tambahan" class="form-control" rows="3" required
                            placeholder="Jelaskan tujuan pinjaman tambahan..."></textarea>
                    </div>

                    <!-- Preview Perhitungan -->
                    <div class="alert alert-warning" id="preview-perhitungan" style="display: none;">
                        <h6><i class="bi bi-calculator"></i> <strong>Preview Perhitungan:</strong></h6>
                        <div id="detail-perhitungan"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Tambahkan Pinjaman
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle transfer fields
    $('#metode_pencairan').on('change', function() {
        if ($(this).val() === 'transfer') {
            $('#transfer-fields').show();
            $('#transfer-fields input').prop('required', true);
        } else {
            $('#transfer-fields').hide();
            $('#transfer-fields input').prop('required', false);
        }
    });

    // Preview Perhitungan Tambah Pinjaman
    $('#jumlah_tambahan, #cicilan_baru').on('input change', function() {
        var sisaPinjaman = {{ $pinjaman->sisa_pinjaman }};
        var jumlahTambahan = parseFloat($('#jumlah_tambahan').val()) || 0;
        var cicilanBaru = parseFloat($('#cicilan_baru').val()) || 0;

        if (jumlahTambahan > 0 && cicilanBaru > 0) {
            var totalPinjamanBaru = sisaPinjaman + jumlahTambahan;
            var tenorBaru = Math.ceil(totalPinjamanBaru / cicilanBaru);
            var totalDibayar = cicilanBaru * tenorBaru;
            var selisih = totalDibayar - totalPinjamanBaru;

            $('#preview-perhitungan').show();
            $('#detail-perhitungan').html(
                '<div class="row">' +
                '<div class="col-md-6"><small>Sisa Pinjaman Lama:</small><br><strong>Rp ' + formatRupiah(sisaPinjaman) + '</strong></div>' +
                '<div class="col-md-6"><small>Pinjaman Tambahan:</small><br><strong>Rp ' + formatRupiah(jumlahTambahan) + '</strong></div>' +
                '</div><hr class="my-2">' +
                '<div class="row">' +
                '<div class="col-md-4"><small>Total Pinjaman Baru:</small><br><strong class="text-primary">Rp ' + formatRupiah(totalPinjamanBaru) + '</strong></div>' +
                '<div class="col-md-4"><small>Cicilan/Bulan:</small><br><strong class="text-success">Rp ' + formatRupiah(cicilanBaru) + '</strong></div>' +
                '<div class="col-md-4"><small>Tenor Baru:</small><br><strong class="text-info">' + tenorBaru + ' bulan</strong></div>' +
                '</div>' +
                (selisih > 0 ? '<small class="text-muted d-block mt-2">Cicilan terakhir akan dikurangi: Rp ' + formatRupiah(selisih) + '</small>' : '')
            );
        } else {
            $('#preview-perhitungan').hide();
        }
    });

    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
</script>
@endpush
