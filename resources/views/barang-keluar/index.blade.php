@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-white mb-2">
                                <i class="ti ti-package-export me-2"></i>TRACKING BARANG KELUAR
                            </h3>
                            <p class="mb-0">Sistem tracking barang keluar untuk laundry, perbaikan, dan jasa lainnya</p>
                        </div>
                        <div>
                            <a href="{{ route('barang-keluar.create') }}" class="btn btn-light btn-lg">
                                <i class="ti ti-plus me-1"></i> Tambah Barang Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="row row-cols-1 row-cols-md-5 g-3 mb-4">
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-primary">
                        <i class="ti ti-package ti-lg"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['total'] }}</h4>
                    <small class="text-muted">Total Barang</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-secondary">
                        <i class="ti ti-clock ti-lg"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-warning">
                        <i class="ti ti-loader ti-lg"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['proses'] }}</h4>
                    <small class="text-muted">Dalam Proses</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-success">
                        <i class="ti ti-check ti-lg"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['selesai'] }}</h4>
                    <small class="text-muted">Selesai Vendor</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-danger">
                        <i class="ti ti-alert-triangle ti-lg"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['terlambat'] }}</h4>
                    <small class="text-muted">Terlambat</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('barang-keluar.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai_vendor" {{ request('status') == 'selesai_vendor' ? 'selected' : '' }}>Selesai Vendor</option>
                        <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jenis Barang</label>
                    <select name="jenis_barang" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisBarangList as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis_barang') == $jenis ? 'selected' : '' }}>
                                {{ ucfirst($jenis) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Prioritas</label>
                    <select name="prioritas" class="form-select">
                        <option value="">Semua Prioritas</option>
                        <option value="rendah" {{ request('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="normal" {{ request('prioritas') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="tinggi" {{ request('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="urgent" {{ request('prioritas') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Kode/Nama..." value="{{ request('search') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">
                        <i class="ti ti-refresh me-1"></i> Reset
                    </a>
                    <a href="{{ route('barang-keluar.export-pdf', request()->all()) }}" class="btn btn-danger ms-2" target="_blank">
                        <i class="ti ti-file-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="ti ti-list me-2"></i>Daftar Barang Keluar
            </h5>
            <span class="badge bg-primary">{{ $barangKeluar->total() }} Data</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Tanggal Keluar</th>
                            <th>Jenis & Nama Barang</th>
                            <th>Pemilik</th>
                            <th>Vendor</th>
                            <th>Estimasi Kembali</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangKeluar as $item)
                            <tr class="{{ $item->is_terlambat ? 'table-danger' : '' }}">
                                <td>{{ $barangKeluar->firstItem() + $loop->index }}</td>
                                <td>
                                    <strong>{{ $item->kode_transaksi }}</strong>
                                </td>
                                <td>
                                    <small>{{ $item->tanggal_keluar->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <strong>{{ ucfirst($item->jenis_barang) }}</strong><br>
                                    <small class="text-muted">{{ $item->nama_barang }}</small><br>
                                    <small class="badge bg-secondary">{{ $item->jumlah }} {{ $item->satuan }}</small>
                                </td>
                                <td>
                                    <strong>{{ $item->pemilik_barang }}</strong><br>
                                    @if($item->departemen)
                                        <small class="text-muted">{{ $item->departemen }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->nama_vendor }}</strong>
                                    @if($item->pic_vendor)
                                        <br><small class="text-muted">PIC: {{ $item->pic_vendor }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($item->estimasi_kembali)
                                        {{ $item->estimasi_kembali->format('d/m/Y') }}
                                        @if($item->is_terlambat)
                                            <br><span class="badge bg-danger">
                                                <i class="ti ti-alert-triangle"></i> 
                                                Terlambat {{ $item->hari_terlambat }} hari
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{!! $item->status_badge !!}</td>
                                <td>{!! $item->prioritas_badge !!}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('barang-keluar.show', $item->id) }}" 
                                           class="btn btn-info" 
                                           title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('barang-keluar.edit', $item->id) }}" 
                                           class="btn btn-warning" 
                                           title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('barang-keluar.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="ti ti-package-off ti-lg text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Tidak ada data barang keluar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $barangKeluar->links() }}
        </div>
    </div>

</div>

<style>
.card-hover {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.table-danger {
    background-color: #ffe5e5 !important;
}
</style>
@endsection
