@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="text-white mb-2"><i class="ti ti-alert-circle me-2"></i>MENU TEMUAN</h3>
                    <p class="mb-0">Pusat pelaporan masalah atau kerusakan yang ditemukan oleh karyawan di lapangan</p>
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
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-4 mb-4">
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="d-block text-muted small">Total Temuan</span>
                            <h3 class="text-primary mb-0" id="total-temuan">0</h3>
                        </div>
                        <div class="avatar bg-label-primary">
                            <i class="ti ti-alert-circle ti-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="d-block text-muted small">Baru</span>
                            <h3 class="text-info mb-0" id="temuan-baru">0</h3>
                        </div>
                        <div class="avatar bg-label-info">
                            <i class="ti ti-inbox ti-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="d-block text-muted small">Sedang Diproses</span>
                            <h3 class="text-warning mb-0" id="temuan-proses">0</h3>
                        </div>
                        <div class="avatar bg-label-warning">
                            <i class="ti ti-tools ti-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="d-block text-muted small">Selesai</span>
                            <h3 class="text-success mb-0" id="temuan-selesai">0</h3>
                        </div>
                        <div class="avatar bg-label-success">
                            <i class="ti ti-check ti-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="d-block text-muted small">Kritis</span>
                            <h3 class="text-danger mb-0" id="temuan-kritis">0</h3>
                        </div>
                        <div class="avatar bg-label-danger">
                            <i class="ti ti-alert-triangle ti-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="ti ti-list me-2"></i>Daftar Temuan</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('temuan.exportPdf') }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                    <i class="ti ti-file-pdf me-1"></i> Export PDF
                </a>
            </div>
        </div>

        <div class="card-body border-bottom">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari judul, lokasi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="sedang_diproses" {{ request('status') == 'sedang_diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="sudah_diperbaiki" {{ request('status') == 'sudah_diperbaiki' ? 'selected' : '' }}>Sudah Diperbaiki</option>
                        <option value="tindaklanjuti" {{ request('status') == 'tindaklanjuti' ? 'selected' : '' }}>Tindaklanjuti</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="urgensi" class="form-select form-select-sm">
                        <option value="">Semua Urgensi</option>
                        <option value="rendah" {{ request('urgensi') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="sedang" {{ request('urgensi') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="tinggi" {{ request('urgensi') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="kritis" {{ request('urgensi') == 'kritis' ? 'selected' : '' }}>Kritis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="ti ti-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        @if($temuan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="ti ti-hash"></i></th>
                            <th>Judul Temuan</th>
                            <th>Lokasi</th>
                            <th>Pelapor</th>
                            <th>Urgensi</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($temuan as $t)
                            <tr>
                                <td><small>#{{ $t->id }}</small></td>
                                <td>
                                    <strong>{{ $t->judul }}</strong>
                                </td>
                                <td>{{ $t->lokasi }}</td>
                                <td>{{ $t->pelapor->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $t->getUrgensiBadgeColor() }}">
                                        {{ $t->getUrgensiLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $t->getStatusBadgeColor() }}">
                                        {{ $t->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $t->tanggal_temuan->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-text-secondary rounded-pill" 
                                                type="button" id="dropdownMenu{{ $t->id }}" 
                                                data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu{{ $t->id }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('temuan.show', $t->id) }}">
                                                    <i class="ti ti-eye me-2"></i>Lihat Detail
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('temuan.destroy', $t->id) }}" 
                                                      onsubmit="return confirm('Yakin ingin menghapus?');" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="ti ti-trash me-2"></i>Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer">
                {{ $temuan->links() }}
            </div>
        @else
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="ti ti-inbox ti-3x text-muted"></i>
                </div>
                <p class="text-muted">Belum ada laporan temuan</p>
            </div>
        @endif
    </div>
</div>

{{-- Update Statistics --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route('temuan.apiSummary') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-temuan').textContent = data.total;
            document.getElementById('temuan-baru').textContent = data.baru;
            document.getElementById('temuan-proses').textContent = data.sedang_diproses;
            document.getElementById('temuan-selesai').textContent = data.selesai;
            document.getElementById('temuan-kritis').textContent = data.kritis;
        });
});
</script>
@endsection
