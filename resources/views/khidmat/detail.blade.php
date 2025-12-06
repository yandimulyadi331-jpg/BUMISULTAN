@extends('layouts.app')
@section('titlepage', 'Detail Jadwal Khidmat')

@section('content')
@section('navigasi')
    <span><a href="{{ route('khidmat.index') }}">Saung Santri / Khidmat</a> / Detail</span>
@endsection

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header bg-gradient-info">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="text-white mb-0">
                        <i class="ti ti-eye me-2"></i>Detail Jadwal Khidmat
                    </h5>
                    <a href="{{ route('khidmat.download-single-pdf', $jadwal->id) }}" class="btn btn-light btn-sm">
                        <i class="ti ti-file-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Info Jadwal -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Nama Kelompok</th>
                                <td>: <strong>{{ $jadwal->nama_kelompok }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>: {{ $jadwal->tanggal_jadwal->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status Kebersihan</th>
                                <td>: 
                                    @if($jadwal->status_kebersihan == 'bersih')
                                        <span class="badge bg-success">Bersih</span>
                                    @else
                                        <span class="badge bg-danger">Kotor</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Saldo Awal</th>
                                <td>: <span class="text-success">Rp {{ number_format($jadwal->saldo_awal, 0, ',', '.') }}</span></td>
                            </tr>
                            <tr>
                                <th>Saldo Masuk</th>
                                <td>: <span class="text-info">Rp {{ number_format($jadwal->saldo_masuk, 0, ',', '.') }}</span></td>
                            </tr>
                            <tr>
                                <th>Total Belanja</th>
                                <td>: <span class="text-danger">Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</span></td>
                            </tr>
                            <tr>
                                <th>Saldo Akhir</th>
                                <td>: <strong class="text-primary">Rp {{ number_format($jadwal->saldo_akhir, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Petugas Khidmat -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-users me-2"></i>Petugas Khidmat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($jadwal->petugas as $index => $petugas)
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="ti ti-user text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $petugas->santri->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $petugas->santri->kelas ?? 'Tidak ada kelas' }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Rincian Belanja -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-shopping-cart me-2"></i>Rincian Belanja</h6>
                    </div>
                    <div class="card-body">
                        @if($jadwal->belanja->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end">Total</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwal->belanja as $index => $belanja)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $belanja->nama_barang }}</td>
                                        <td class="text-center">{{ $belanja->jumlah }} {{ $belanja->satuan }}</td>
                                        <td class="text-end">Rp {{ number_format($belanja->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-end"><strong>Rp {{ number_format($belanja->total_harga, 0, ',', '.') }}</strong></td>
                                        <td>{{ $belanja->keterangan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="4" class="text-end"><strong>Total Belanja:</strong></td>
                                        <td class="text-end"><strong class="text-danger">Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</strong></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>Belum ada data belanja
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Foto Belanja -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-photo me-2"></i>Foto Rincian Belanja</h6>
                    </div>
                    <div class="card-body">
                        @if($jadwal->foto->count() > 0)
                        <div class="row">
                            @foreach($jadwal->foto as $foto)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <img src="{{ Storage::url($foto->path_file) }}" class="card-img-top" alt="Foto Belanja">
                                    <div class="card-body">
                                        <p class="card-text small">{{ $foto->keterangan ?? 'Foto belanja' }}</p>
                                        <small class="text-muted">{{ $foto->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>Belum ada foto belanja
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Keterangan -->
                @if($jadwal->keterangan)
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-notes me-2"></i>Keterangan</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $jadwal->keterangan }}</p>
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('khidmat.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
