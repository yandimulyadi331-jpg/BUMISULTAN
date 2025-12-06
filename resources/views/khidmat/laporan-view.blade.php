@extends('layouts.app')
@section('titlepage', 'Laporan Keuangan Belanja')

@section('content')
@section('navigasi')
    <span><a href="{{ route('khidmat.index') }}">Saung Santri / Khidmat</a> / Laporan Keuangan</span>
@endsection

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header bg-gradient-info">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="text-white mb-0">
                        <i class="ti ti-file-invoice me-2"></i>Laporan Keuangan Belanja - {{ $jadwal->nama_kelompok }}
                    </h5>
                    <div>
                        @can('khidmat.laporan')
                        <a href="{{ route('khidmat.laporan', $jadwal->id) }}" class="btn btn-warning btn-sm">
                            <i class="ti ti-edit me-1"></i> Input/Edit Belanja
                        </a>
                        <a href="{{ route('khidmat.download-single-pdf', $jadwal->id) }}" class="btn btn-light btn-sm" target="_blank">
                            <i class="ti ti-download me-1"></i> Download PDF
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Notifikasi menggunakan Toastr -->
                
                <!-- Info Jadwal -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="mb-0"><i class="ti ti-info-circle me-2"></i>Informasi Jadwal</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Kelompok</th>
                                        <td>: <strong>{{ $jadwal->nama_kelompok }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td>: {{ $jadwal->tanggal_jadwal->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Petugas</th>
                                        <td>: 
                                            @foreach($jadwal->petugas as $petugas)
                                                <span class="badge bg-info">{{ $petugas->santri->nama_lengkap }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Saldo Awal</th>
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
                                        <th><strong>Saldo Akhir</strong></th>
                                        <td>: <strong class="text-primary fs-5">Rp {{ number_format($jadwal->saldo_akhir, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Belanja -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-shopping-cart me-2"></i>Rincian Belanja</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="25%">Nama Barang</th>
                                        <th width="10%" class="text-center">Jumlah</th>
                                        <th width="10%">Satuan</th>
                                        <th width="15%" class="text-end">Harga Satuan</th>
                                        <th width="15%" class="text-end">Total Harga</th>
                                        <th width="20%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwal->belanja as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-end"><strong>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong></td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-info">
                                    <tr>
                                        <th colspan="5" class="text-end">TOTAL BELANJA:</th>
                                        <th class="text-end">Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Foto Belanja -->
                @if($jadwal->foto->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-photo me-2"></i>Foto Belanja</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($jadwal->foto as $foto)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ Storage::url($foto->path_file) }}" class="card-img-top" alt="Foto Belanja" style="height: 200px; object-fit: cover;">
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted">{{ $foto->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Ringkasan Keuangan -->
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="ti ti-calculator me-2"></i>Ringkasan Keuangan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="p-3 bg-success bg-opacity-10 rounded">
                                    <small class="text-muted">Saldo Awal</small>
                                    <h4 class="text-success mb-0">Rp {{ number_format($jadwal->saldo_awal, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-info bg-opacity-10 rounded">
                                    <small class="text-muted">Saldo Masuk</small>
                                    <h4 class="text-info mb-0">Rp {{ number_format($jadwal->saldo_masuk, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-danger bg-opacity-10 rounded">
                                    <small class="text-muted">Total Belanja</small>
                                    <h4 class="text-danger mb-0">Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-primary bg-opacity-10 rounded">
                                    <small class="text-muted">Saldo Akhir</small>
                                    <h4 class="text-primary mb-0">Rp {{ number_format($jadwal->saldo_akhir, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 p-3 bg-light rounded">
                            <p class="mb-0"><strong>Formula:</strong> Saldo Akhir = (Saldo Awal + Saldo Masuk) - Total Belanja</p>
                            <p class="mb-0"><strong>Perhitungan:</strong> Rp {{ number_format($jadwal->saldo_akhir, 0, ',', '.') }} = (Rp {{ number_format($jadwal->saldo_awal, 0, ',', '.') }} + Rp {{ number_format($jadwal->saldo_masuk, 0, ',', '.') }}) - Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('khidmat.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                    <div>
                        @can('khidmat.laporan')
                        <a href="{{ route('khidmat.laporan', $jadwal->id) }}" class="btn btn-warning">
                            <i class="ti ti-edit me-1"></i> Input/Edit Belanja
                        </a>
                        <a href="{{ route('khidmat.download-single-pdf', $jadwal->id) }}" class="btn btn-primary" target="_blank">
                            <i class="ti ti-printer me-1"></i> Print / Download PDF
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
// Toastr Notifications dari Session
@if(session('success'))
    toastr.success('{{ session('success') }}', 'Berhasil!', {
        closeButton: true,
        progressBar: true,
        timeOut: 5000
    });
@endif

@if(session('error'))
    toastr.error('{{ session('error') }}', 'Gagal!', {
        closeButton: true,
        progressBar: true,
        timeOut: 8000
    });
@endif
</script>
@endpush

@endsection
