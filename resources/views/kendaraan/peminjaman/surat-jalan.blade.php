@extends('layouts.app')
@section('titlepage', 'Download Surat Jalan')

@section('content')
@section('navigasi')
    <span>Peminjaman Kendaraan</span> / <span>Surat Jalan</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="ti ti-circle-check me-2"></i>
                    <strong>Peminjaman Berhasil Dicatat!</strong>
                    <p class="mb-0 mt-2">Silakan download surat jalan di bawah ini:</p>
                </div>

                <div class="row mt-4">
                    <!-- Info Peminjaman -->
                    <div class="col-md-12 mb-4">
                        <div class="card border">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="ti ti-info-circle me-2"></i>Informasi Peminjaman
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="40%"><strong>Kode Peminjaman</strong></td>
                                                <td width="5%">:</td>
                                                <td><span class="badge bg-primary">{{ $peminjaman->kode_peminjaman }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nama Peminjam</strong></td>
                                                <td>:</td>
                                                <td>{{ $peminjaman->nama_peminjam }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. HP</strong></td>
                                                <td>:</td>
                                                <td>{{ $peminjaman->no_hp_peminjam ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Keperluan</strong></td>
                                                <td>:</td>
                                                <td>{{ $peminjaman->keperluan }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="40%"><strong>Kendaraan</strong></td>
                                                <td width="5%">:</td>
                                                <td>{{ $peminjaman->kendaraan->nama_kendaraan }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. Polisi</strong></td>
                                                <td>:</td>
                                                <td><span class="badge bg-dark">{{ $peminjaman->kendaraan->no_polisi }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Waktu Pinjam</strong></td>
                                                <td>:</td>
                                                <td>{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Estimasi Kembali</strong></td>
                                                <td>:</td>
                                                <td>{{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Button -->
                    <div class="col-md-8 offset-md-2 mb-3">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="ti ti-file-check" style="font-size: 64px; color: #198754;"></i>
                                </div>
                                <h5 class="card-title">Surat Peminjaman Kendaraan</h5>
                                <p class="text-muted mb-3">
                                    <small>Surat rangkap 2 dengan garis sobek - Bagian atas untuk arsip perusahaan, bagian bawah untuk peminjam</small>
                                </p>
                                <a href="{{ route('kendaraan.peminjaman.pdf.peminjam', Crypt::encrypt($peminjaman->id)) }}" 
                                   class="btn btn-success btn-lg w-100" target="_blank">
                                    <i class="ti ti-download me-2"></i>Download Surat Peminjaman
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Catatan Penting:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Surat berisi <strong>2 bagian dengan garis sobek</strong> di tengah</li>
                                <li><strong>Bagian atas</strong>: Untuk arsip perusahaan (lengkap dengan detail)</li>
                                <li><strong>Bagian bawah</strong>: Untuk peminjam (ringkas sebagai bukti)</li>
                                <li>Potong di garis putus-putus ✂️ setelah dicetak</li>
                                <li>Peminjam membawa bagian bawah selama menggunakan kendaraan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali ke Daftar Kendaraan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
