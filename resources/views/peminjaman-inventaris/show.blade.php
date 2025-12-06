@extends('layouts.app')
@section('titlepage', 'Detail Peminjaman')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Peminjaman / Detail</span>
@endsection

@php
    $peminjaman = $peminjamanInventaris;
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-hand-holding me-2"></i>Detail Peminjaman</h4>
                    <div>
                        @if($peminjaman->status == 'dipinjam')
                        <a href="{{ route('pengembalian-inventaris.create', $peminjaman->id) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-undo me-1"></i>Kembalikan
                        </a>
                        @endif
                        <a href="{{ route('peminjaman-inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%"><i class="ti ti-barcode me-2"></i>Kode Peminjaman</th>
                        <td><strong class="text-primary">{{ $peminjaman->kode_peminjaman }}</strong></td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-box me-2"></i>Inventaris</th>
                        <td>
                            @if($peminjaman->inventaris)
                                <strong>{{ $peminjaman->inventaris->nama_barang }}</strong><br>
                                <small class="text-muted">{{ $peminjaman->inventaris->kode_inventaris }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-user me-2"></i>Peminjam</th>
                        <td>
                            @if($peminjaman->karyawan)
                                <strong>{{ $peminjaman->karyawan->nama_lengkap }}</strong><br>
                                <small class="text-muted">NIK: {{ $peminjaman->karyawan->nik }}</small>
                            @else
                                <strong>{{ $peminjaman->nama_peminjam ?? '-' }}</strong>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-package me-2"></i>Jumlah Pinjam</th>
                        <td><strong>{{ $peminjaman->jumlah_pinjam }}</strong> {{ $peminjaman->inventaris ? $peminjaman->inventaris->satuan : '' }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-calendar me-2"></i>Tanggal Pinjam</th>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-calendar-event me-2"></i>Rencana Kembali</th>
                        <td>
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y H:i') }}
                            @if($peminjaman->isTerlambat())
                                <span class="badge bg-danger ms-2">TERLAMBAT!</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-flag me-2"></i>Status</th>
                        <td>
                            @if($peminjaman->status == 'pending')
                                <span class="badge bg-warning">Pending Approval</span>
                            @elseif($peminjaman->status == 'disetujui')
                                <span class="badge bg-info">Disetujui</span>
                            @elseif($peminjaman->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($peminjaman->status == 'dipinjam')
                                <span class="badge bg-primary">Sedang Dipinjam</span>
                            @else
                                <span class="badge bg-success">Sudah Dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-file-text me-2"></i>Keperluan</th>
                        <td>{{ $peminjaman->keperluan }}</td>
                    </tr>
                    @if($peminjaman->catatan)
                    <tr>
                        <th><i class="ti ti-notes me-2"></i>Catatan</th>
                        <td>{{ $peminjaman->catatan }}</td>
                    </tr>
                    @endif
                    @if($peminjaman->status == 'disetujui' || $peminjaman->status == 'dipinjam' || $peminjaman->status == 'dikembalikan')
                    <tr>
                        <th><i class="ti ti-user-check me-2"></i>Disetujui Oleh</th>
                        <td>
                            {{ $peminjaman->approver->nama ?? '-' }}<br>
                            <small class="text-muted">{{ $peminjaman->tanggal_disetujui ? \Carbon\Carbon::parse($peminjaman->tanggal_disetujui)->format('d/m/Y H:i') : '-' }}</small>
                        </td>
                    </tr>
                    @endif
                    @if($peminjaman->status == 'ditolak')
                    <tr>
                        <th><i class="ti ti-user-x me-2"></i>Ditolak Oleh</th>
                        <td>
                            {{ $peminjaman->rejector->nama ?? '-' }}<br>
                            <small class="text-muted">{{ $peminjaman->tanggal_ditolak ? \Carbon\Carbon::parse($peminjaman->tanggal_ditolak)->format('d/m/Y H:i') : '-' }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-message me-2"></i>Alasan Penolakan</th>
                        <td class="text-danger">{{ $peminjaman->alasan_penolakan }}</td>
                    </tr>
                    @endif
                </table>

                <div class="row mt-4">
                    @if($peminjaman->foto_barang_pinjam)
                    <div class="col-md-6 mb-3">
                        <h6><i class="ti ti-photo me-2"></i>Foto Barang Saat Dipinjam</h6>
                        <img src="{{ Storage::url($peminjaman->foto_barang_pinjam) }}" alt="Foto Barang" 
                            class="img-fluid rounded border" style="max-height: 300px;">
                    </div>
                    @endif
                    @if($peminjaman->ttd_peminjam)
                    <div class="col-md-6 mb-3">
                        <h6><i class="ti ti-writing me-2"></i>Tanda Tangan Peminjam</h6>
                        <div class="border rounded p-2 bg-white" style="display: inline-block;">
                            <img src="{{ Storage::url($peminjaman->ttd_peminjam) }}" alt="TTD Peminjam" 
                                class="img-fluid" style="max-height: 150px; max-width: 300px;">
                        </div>
                        <p class="text-muted mt-2 mb-0"><strong>{{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_lengkap : $peminjaman->nama_peminjam }}</strong></p>
                    </div>
                    @endif
                    @if($peminjaman->ttd_petugas)
                    <div class="col-md-6 mb-3">
                        <h6><i class="ti ti-writing me-2"></i>Tanda Tangan Petugas</h6>
                        <div class="border rounded p-2 bg-white" style="display: inline-block;">
                            <img src="{{ Storage::url($peminjaman->ttd_petugas) }}" alt="TTD Petugas" 
                                class="img-fluid" style="max-height: 150px; max-width: 300px;">
                        </div>
                        <p class="text-muted mt-2 mb-0"><strong>{{ $peminjaman->approver->nama ?? 'Petugas' }}</strong></p>
                    </div>
                    @endif
                </div>

                @if($peminjaman->status == 'pending')
                <div class="mt-4">
                    <div class="alert alert-warning">
                        <i class="fa fa-clock me-2"></i>
                        <strong>Status: Menunggu Approval</strong><br>
                        Peminjaman ini masih menunggu persetujuan dari admin/petugas.
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" id="btnApprove">
                            <i class="fa fa-check me-2"></i>Setujui Peminjaman
                        </button>
                        <button type="button" class="btn btn-danger" id="btnReject">
                            <i class="fa fa-times me-2"></i>Tolak Peminjaman
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Timeline Status -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-history me-2"></i>Timeline Status</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Peminjaman Diajukan</h6>
                            <small class="text-muted">{{ $peminjaman->created_at ? $peminjaman->created_at->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                    </div>

                    @if($peminjaman->tanggal_disetujui)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Disetujui</h6>
                            <small class="text-muted">{{ $peminjaman->tanggal_disetujui ? \Carbon\Carbon::parse($peminjaman->tanggal_disetujui)->format('d/m/Y H:i') : '-' }}</small><br>
                            <small>oleh: {{ $peminjaman->disetujuiOleh ? $peminjaman->disetujuiOleh->name : '-' }}</small>
                        </div>
                    </div>
                    @endif

                    @if($peminjaman->tanggal_ditolak)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Ditolak</h6>
                            <small class="text-muted">{{ $peminjaman->tanggal_ditolak ? \Carbon\Carbon::parse($peminjaman->tanggal_ditolak)->format('d/m/Y H:i') : '-' }}</small><br>
                            <small>oleh: {{ $peminjaman->rejector->nama ?? '-' }}</small>
                        </div>
                    </div>
                    @endif

                    @if($peminjaman->pengembalian)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Dikembalikan</h6>
                            <small class="text-muted">{{ $peminjaman->pengembalian->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali)->format('d/m/Y H:i') : '-' }}</small>
                            @if($peminjaman->pengembalian->status_keterlambatan == 'terlambat')
                                <br><span class="badge bg-danger mt-1">Terlambat {{ $peminjaman->pengembalian->lama_keterlambatan }} hari</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Pengembalian jika sudah dikembalikan -->
        @if($peminjaman->pengembalian)
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-undo me-2"></i>Info Pengembalian</h5>
            </div>
            <div class="card-body">
                <p><strong>Kode:</strong> {{ $peminjaman->pengembalian->kode_pengembalian }}</p>
                <p><strong>Tanggal:</strong> {{ $peminjaman->pengembalian && $peminjaman->pengembalian->tanggal_pengembalian ? \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_pengembalian)->format('d/m/Y H:i') : '-' }}</p>
                <p><strong>Keterlambatan:</strong> 
                    @if($peminjaman->pengembalian->lama_keterlambatan > 0)
                        <span class="badge bg-danger">{{ $peminjaman->pengembalian->lama_keterlambatan }} hari</span>
                    @else
                        <span class="badge bg-success">Tepat Waktu</span>
                    @endif
                </p>
                @if($peminjaman->pengembalian->denda > 0)
                <p><strong>Denda:</strong> <span class="text-danger">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span></p>
                @endif
                <a href="{{ route('pengembalian-inventaris.show', $peminjaman->pengembalian->id) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('myscript')
<script>
    $('#btnApprove').on('click', function() {
        Swal.fire({
            title: 'Setujui Peminjaman?',
            text: "Peminjaman akan disetujui dan status berubah menjadi 'Dipinjam'",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("peminjaman-inventaris.setujui", ["peminjamanInventaris" => $peminjaman->id]) }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });

    $('#btnReject').on('click', function() {
        Swal.fire({
            title: 'Tolak Peminjaman?',
            text: 'Masukkan alasan penolakan',
            input: 'textarea',
            inputPlaceholder: 'Alasan penolakan...',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan harus diisi!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("peminjaman-inventaris.tolak", ["peminjamanInventaris" => $peminjaman->id]) }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        alasan_penolakan: result.value
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -23px;
    top: 10px;
    bottom: -10px;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    padding-left: 10px;
}
</style>
@endpush
@endsection
