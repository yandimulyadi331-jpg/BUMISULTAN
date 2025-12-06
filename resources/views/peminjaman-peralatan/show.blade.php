@extends('layouts.app')
@section('titlepage', 'Detail Peminjaman Peralatan')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peminjaman-peralatan.index') }}">Peminjaman Peralatan</a> / Detail</span>
@endsection

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-file-description me-2"></i> Detail Peminjaman</h4>
                    <a href="{{ route('peminjaman-peralatan.index') }}" class="btn btn-light btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5>Informasi Peminjaman</h5>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Nomor Peminjaman</strong></td>
                                <td>{{ $peminjamanPeralatan->nomor_peminjaman }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    @if($peminjamanPeralatan->status == 'dipinjam')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @elseif($peminjamanPeralatan->status == 'terlambat')
                                        <span class="badge bg-danger">Terlambat</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pinjam</strong></td>
                                <td>{{ $peminjamanPeralatan->tanggal_pinjam->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rencana Kembali</strong></td>
                                <td>{{ $peminjamanPeralatan->tanggal_kembali_rencana->format('d F Y') }}</td>
                            </tr>
                            @if($peminjamanPeralatan->tanggal_kembali_aktual)
                            <tr>
                                <td><strong>Tanggal Dikembalikan</strong></td>
                                <td>{{ $peminjamanPeralatan->tanggal_kembali_aktual->format('d F Y') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informasi Peralatan</h5>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Nama Peralatan</strong></td>
                                <td>{{ $peminjamanPeralatan->peralatan->nama_peralatan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Peralatan</strong></td>
                                <td>{{ $peminjamanPeralatan->peralatan->kode_peralatan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Dipinjam</strong></td>
                                <td><strong class="text-primary">{{ $peminjamanPeralatan->jumlah_dipinjam }} {{ $peminjamanPeralatan->peralatan->satuan }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Keperluan</strong></td>
                                <td>{{ $peminjamanPeralatan->keperluan }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <h5>Informasi Peminjam</h5>
                        <table class="table table-sm">
                            <tr>
                                <td width="20%"><strong>Nama Peminjam</strong></td>
                                <td>{{ $peminjamanPeralatan->nama_peminjam }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5>Kondisi Saat Dipinjam</h5>
                        <div class="alert alert-info">
                            {{ $peminjamanPeralatan->kondisi_saat_dipinjam ?? 'Tidak ada catatan' }}
                        </div>
                    </div>
                    @if($peminjamanPeralatan->kondisi_saat_dikembalikan)
                    <div class="col-md-6">
                        <h5>Kondisi Saat Dikembalikan</h5>
                        <div class="alert alert-success">
                            {{ $peminjamanPeralatan->kondisi_saat_dikembalikan }}
                        </div>
                    </div>
                    @endif
                </div>

                @if($peminjamanPeralatan->catatan)
                <div class="row mb-3">
                    <div class="col-12">
                        <h5>Catatan</h5>
                        <div class="alert alert-secondary">
                            {{ $peminjamanPeralatan->catatan }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('peminjaman-peralatan.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                    @if($peminjamanPeralatan->status == 'dipinjam')
                    <a href="{{ route('peminjaman-peralatan.form-pengembalian', $peminjamanPeralatan->id) }}" class="btn btn-success">
                        <i class="ti ti-arrow-back-up me-1"></i> Kembalikan Peralatan
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
