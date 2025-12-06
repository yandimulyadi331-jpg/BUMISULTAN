@extends('layouts.app')
@section('titlepage', 'Detail Pengembalian')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Pengembalian / Detail</span>
@endsection

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-undo me-2"></i>Detail Pengembalian</h4>
                    <a href="{{ route('pengembalian-inventaris.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fa fa-check-circle me-2"></i>
                    <strong>Pengembalian Berhasil!</strong><br>
                    Barang telah dikembalikan dengan kode: <strong>{{ $pengembalianInventaris->kode_pengembalian }}</strong>
                </div>

                <h5 class="mb-3"><i class="fa fa-info-circle me-2"></i>Informasi Pengembalian</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%"><i class="ti ti-barcode me-2"></i>Kode Pengembalian</th>
                        <td><strong class="text-success">{{ $pengembalianInventaris->kode_pengembalian }}</strong></td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-calendar me-2"></i>Tanggal Pengembalian</th>
                        <td>{{ \Carbon\Carbon::parse($pengembalianInventaris->tanggal_pengembalian)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-box me-2"></i>Inventaris</th>
                        <td>
                            <strong>{{ $pengembalianInventaris->peminjaman->inventaris->nama_barang }}</strong><br>
                            <small class="text-muted">{{ $pengembalianInventaris->peminjaman->inventaris->kode_inventaris }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-package me-2"></i>Jumlah Dikembalikan</th>
                        <td><strong>{{ $pengembalianInventaris->jumlah_kembali }}</strong> {{ $pengembalianInventaris->peminjaman->inventaris->satuan }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-user me-2"></i>Peminjam</th>
                        <td>
                            @if($pengembalianInventaris->peminjaman->karyawan)
                                <strong>{{ $pengembalianInventaris->peminjaman->karyawan->nama_lengkap }}</strong><br>
                                <small class="text-muted">NIK: {{ $pengembalianInventaris->peminjaman->karyawan->nik }}</small>
                            @else
                                <strong>{{ $pengembalianInventaris->peminjaman->nama_peminjam ?? '-' }}</strong>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-heart-rate-monitor me-2"></i>Kondisi Barang</th>
                        <td>
                            @if($pengembalianInventaris->kondisi_barang == 'baik')
                                <span class="badge bg-success">Baik</span>
                            @elseif($pengembalianInventaris->kondisi_barang == 'rusak_ringan')
                                <span class="badge bg-warning">Rusak Ringan</span>
                            @else
                                <span class="badge bg-danger">Rusak Berat</span>
                            @endif
                        </td>
                    </tr>
                    @if($pengembalianInventaris->terlambat)
                    <tr>
                        <th><i class="ti ti-alert-triangle me-2"></i>Status Keterlambatan</th>
                        <td>
                            <span class="badge bg-danger">TERLAMBAT</span>
                            <span class="text-danger ms-2">{{ $pengembalianInventaris->hari_keterlambatan }} hari</span>
                        </td>
                    </tr>
                    @endif
                    @if($pengembalianInventaris->denda > 0)
                    <tr>
                        <th><i class="ti ti-coin me-2"></i>Denda</th>
                        <td class="text-danger"><strong>Rp {{ number_format($pengembalianInventaris->denda, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                    @if($pengembalianInventaris->keterangan)
                    <tr>
                        <th><i class="ti ti-notes me-2"></i>Keterangan</th>
                        <td>{{ $pengembalianInventaris->keterangan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th><i class="ti ti-user-check me-2"></i>Diterima Oleh</th>
                        <td>
                            {{ $pengembalianInventaris->diterimаOleh->name ?? 'System' }}<br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($pengembalianInventaris->created_at)->format('d/m/Y H:i') }}</small>
                        </td>
                    </tr>
                </table>

                <h5 class="mb-3 mt-4"><i class="fa fa-file-image me-2"></i>Dokumentasi</h5>
                <div class="row">
                    @if($pengembalianInventaris->foto_pengembalian)
                    <div class="col-md-6 mb-3">
                        <h6><i class="ti ti-photo me-2"></i>Foto Barang Saat Dikembalikan</h6>
                        <img src="{{ Storage::url($pengembalianInventaris->foto_pengembalian) }}" alt="Foto Pengembalian" 
                            class="img-fluid rounded border" style="max-height: 300px;">
                    </div>
                    @endif
                    @if($pengembalianInventaris->ttd_peminjam)
                    <div class="col-md-6 mb-3">
                        <h6><i class="ti ti-writing me-2"></i>Tanda Tangan Peminjam</h6>
                        <div class="border rounded p-2 bg-white" style="display: inline-block;">
                            <img src="{{ Storage::url($pengembalianInventaris->ttd_peminjam) }}" alt="TTD Peminjam" 
                                class="img-fluid" style="max-height: 150px; max-width: 300px;">
                        </div>
                        <p class="text-muted mt-2 mb-0"><strong>{{ $pengembalianInventaris->peminjaman->karyawan ? $pengembalianInventaris->peminjaman->karyawan->nama_lengkap : $pengembalianInventaris->peminjaman->nama_peminjam }}</strong></p>
                    </div>
                    @endif
                    @if($pengembalianInventaris->ttd_petugas)
                    <div class="col-md-6 mb-3">
                        <h6><i class="ti ti-writing me-2"></i>Tanda Tangan Petugas</h6>
                        <div class="border rounded p-2 bg-white" style="display: inline-block;">
                            <img src="{{ Storage::url($pengembalianInventaris->ttd_petugas) }}" alt="TTD Petugas" 
                                class="img-fluid" style="max-height: 150px; max-width: 300px;">
                        </div>
                        <p class="text-muted mt-2 mb-0"><strong>{{ $pengembalianInventaris->diterimаOleh->name ?? 'Petugas' }}</strong></p>
                    </div>
                    @endif
                </div>

                <h5 class="mb-3 mt-4"><i class="fa fa-link me-2"></i>Informasi Peminjaman Terkait</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%"><i class="ti ti-barcode me-2"></i>Kode Peminjaman</th>
                        <td>
                            <a href="{{ route('peminjaman-inventaris.show', $pengembalianInventaris->peminjaman_inventaris_id) }}" target="_blank">
                                {{ $pengembalianInventaris->peminjaman->kode_peminjaman }}
                                <i class="fa fa-external-link-alt ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-calendar me-2"></i>Tanggal Pinjam</th>
                        <td>{{ \Carbon\Carbon::parse($pengembalianInventaris->peminjaman->tanggal_pinjam)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-calendar-event me-2"></i>Rencana Kembali</th>
                        <td>{{ \Carbon\Carbon::parse($pengembalianInventaris->peminjaman->tanggal_kembali_rencana)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th><i class="ti ti-clock me-2"></i>Durasi Peminjaman</th>
                        <td>
                            @php
                                $durasi = \Carbon\Carbon::parse($pengembalianInventaris->peminjaman->tanggal_pinjam)
                                    ->diffInDays(\Carbon\Carbon::parse($pengembalianInventaris->tanggal_pengembalian));
                            @endphp
                            {{ $durasi }} hari
                        </td>
                    </tr>
                </table>

                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('pengembalian-inventaris.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i>Kembali ke Daftar
                    </a>
                    <a href="{{ route('peminjaman-inventaris.show', $pengembalianInventaris->peminjaman_inventaris_id) }}" class="btn btn-primary">
                        <i class="fa fa-eye me-1"></i>Lihat Detail Peminjaman
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
