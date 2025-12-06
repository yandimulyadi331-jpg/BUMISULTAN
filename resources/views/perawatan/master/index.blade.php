@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                        <path d="M9 14l2 2l4 -4"></path>
                    </svg>
                    Manajemen Perawatan Gedung
                </h2>
                <div class="text-muted mt-1">Kelola template checklist perawatan gedung</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Navigation Tabs -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="btn-list">
                            <a href="{{ route('perawatan.index') }}" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('perawatan.master.index') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                                Master Checklist
                            </a>
                            <a href="{{ route('perawatan.laporan.index') }}" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                                Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Master Checklist</h3>
                        <div class="card-actions">
                            <a href="{{ route('perawatan.master.create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                Tambah Checklist
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tabs per Periode -->
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#harian" class="nav-link active" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /></svg>
                                    Harian ({{ $masters->where('tipe_periode', 'harian')->count() }})
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#mingguan" class="nav-link" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><path d="M7 14h.01" /><path d="M11 14h.01" /></svg>
                                    Mingguan ({{ $masters->where('tipe_periode', 'mingguan')->count() }})
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#bulanan" class="nav-link" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><rect x="8" y="15" width="2" height="2" /></svg>
                                    Bulanan ({{ $masters->where('tipe_periode', 'bulanan')->count() }})
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tahunan" class="nav-link" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                    Tahunan ({{ $masters->where('tipe_periode', 'tahunan')->count() }})
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            @foreach(['harian', 'mingguan', 'bulanan', 'tahunan'] as $tipe)
                            <div class="tab-pane {{ $loop->first ? 'active show' : '' }}" id="{{ $tipe }}" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">Urutan</th>
                                                <th width="25%">Nama Kegiatan</th>
                                                <th width="20%">Deskripsi</th>
                                                <th width="15%">Kategori</th>
                                                <th width="10%" class="text-center">Status</th>
                                                <th width="10%" class="text-center">Eksekusi (30hr)</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($masters->where('tipe_periode', $tipe) as $master)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $master->urutan }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $master->nama_kegiatan }}</strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ Str::limit($master->deskripsi, 50) }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $badgeColors = [
                                                            'kebersihan' => 'bg-blue',
                                                            'perawatan_rutin' => 'bg-green',
                                                            'pengecekan' => 'bg-yellow',
                                                            'lainnya' => 'bg-gray'
                                                        ];
                                                    @endphp
                                                    <span class="badge {{ $badgeColors[$master->kategori] ?? 'bg-secondary' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $master->kategori)) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($master->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger">Nonaktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">{{ $master->logs_count ?? 0 }}x</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('perawatan.master.edit', $master->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                        </a>
                                                        <form action="{{ route('perawatan.master.destroy', $master->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus checklist ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <div class="empty">
                                                        <div class="empty-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                                                        </div>
                                                        <p class="empty-title">Belum ada checklist {{ $tipe }}</p>
                                                        <p class="empty-subtitle text-muted">
                                                            Tambahkan checklist baru untuk periode {{ $tipe }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
