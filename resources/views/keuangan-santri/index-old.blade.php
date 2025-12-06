@extends('layouts.app')
@section('titlepage', 'Keuangan Santri')

@section('content')
@section('navigasi')
    <span>Saung Santri / Keuangan Santri</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white mb-0">
                            <i class="ti ti-wallet me-2"></i>Dashboard Keuangan Santri
                        </h5>
                    </div>
                    <div>
                        <a href="{{ route('keuangan-santri.laporan') }}" class="btn btn-light me-2">
                            <i class="ti ti-report me-2"></i> Laporan
                        </a>
                        <a href="{{ route('keuangan-santri.import.form') }}" class="btn btn-light me-2">
                            <i class="ti ti-upload me-2"></i> Import Excel
                        </a>
                        <a href="{{ route('keuangan-santri.create') }}" class="btn btn-light">
                            <i class="ti ti-plus me-2"></i> Tambah Transaksi
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <!-- Total Pemasukan -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Total Pemasukan</p>
                                        <h4 class="text-success mb-0">
                                            Rp {{ number_format($statistik['total_pemasukan'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-arrow-up text-success" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pengeluaran -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Total Pengeluaran</p>
                                        <h4 class="text-danger mb-0">
                                            Rp {{ number_format($statistik['total_pengeluaran'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-arrow-down text-danger" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selisih -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Selisih</p>
                                        <h4 class="{{ $statistik['selisih'] >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                                            Rp {{ number_format($statistik['selisih'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-chart-line text-info" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Transaksi -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Jumlah Transaksi</p>
                                        <h4 class="text-primary mb-0">
                                            {{ number_format($statistik['total_transaksi'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-list text-primary" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form method="GET" action="{{ route('keuangan-santri.index') }}">
                            <div class="row g-2">
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">Santri</label>
                                        <select name="santri_id" class="form-select">
                                            <option value="">-- Semua Santri --</option>
                                            @foreach($santriList as $santri)
                                                <option value="{{ $santri->id }}" {{ $santriId == $santri->id ? 'selected' : '' }}>
                                                    {{ $santri->nama_lengkap }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">Periode</label>
                                        <select name="periode" class="form-select">
                                            <option value="hari" {{ $periode == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                                            <option value="minggu" {{ $periode == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                            <option value="bulan" {{ $periode == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                            <option value="tahun" {{ $periode == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 col-sm-12">
                                    <label class="form-label small">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fa fa-search me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('keuangan-santri.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-refresh me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Saldo Akhir -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">
                                {{ $saldoSantri ? 'Saldo Akhir' : 'Jumlah Transaksi' }}
                            </p>
                            <p class="text-2xl font-bold text-indigo-600">
                                @if($saldoSantri)
                                    Rp {{ number_format($saldoSantri->saldo_akhir, 0, ',', '.') }}
                                @else
                                    {{ $transactions->total() }}
                                @endif
                            </p>
                        </div>
                        <div class="rounded-full bg-indigo-100 p-3">
                            <i class="fas fa-wallet text-2xl text-indigo-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mb-4 flex flex-wrap gap-2">
            <a href="{{ route('keuangan-santri.create', ['santri_id' => $santriId]) }}" 
               class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Tambah Transaksi
            </a>
            <a href="{{ route('keuangan-santri.laporan') }}" 
               class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                <i class="fas fa-file-alt mr-2"></i>Lihat Laporan
            </a>
            <a href="{{ route('keuangan-santri.import.form') }}" 
               class="rounded-md bg-purple-600 px-4 py-2 text-white hover:bg-purple-700">
                <i class="fas fa-file-import mr-2"></i>Import Excel
            </a>
        </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 100px;">Tanggal</th>
                                <th style="width: 130px;">Kode</th>
                                <th>Santri</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th style="width: 100px;">Jenis</th>
                                <th style="width: 130px;" class="text-end">Jumlah</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                                <td>
                                    <small>{{ $transaction->tanggal_transaksi->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $transaction->kode_transaksi }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $transaction->santri->nama_lengkap ?? '-' }}</strong><br>
                                    <small class="text-muted">NIS: {{ $transaction->santri->nis ?? '-' }}</small>
                                </td>
                                <td>
                                    {{ Str::limit($transaction->deskripsi, 40) }}
                                    @if($transaction->catatan)
                                        <br><small class="text-muted">{{ Str::limit($transaction->catatan, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->category)
                                        <span class="badge bg-{{ $transaction->category->warna }}">
                                            <i class="{{ $transaction->category->icon }} me-1"></i>
                                            {{ $transaction->category->nama_kategori }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Uncategorized</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->jenis === 'pemasukan')
                                        <span class="badge bg-success">
                                            <i class="ti ti-arrow-up me-1"></i> Masuk
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="ti ti-arrow-down me-1"></i> Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong class="{{ $transaction->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('keuangan-santri.show', $transaction->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('keuangan-santri.edit', $transaction->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('keuangan-santri.destroy', $transaction->id) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="ti ti-info-circle me-2"></i>Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
