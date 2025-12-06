@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Laporan Rekap Pelanggaran Santri
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filter Laporan</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('pelanggaran-santri.laporan') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="10" cy="10" r="7"></circle>
                                            <line x1="21" y1="21" x2="15" y2="15"></line>
                                        </svg>
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend Status -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Keterangan Status Pelanggaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex">
                                        <div>
                                            <span class="badge bg-green-500 text-white">Ringan</span>
                                        </div>
                                        <div class="ms-3">
                                            <strong>Pelanggaran Ringan</strong><br>
                                            <small>Jumlah pelanggaran di bawah 35 kali</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex">
                                        <div>
                                            <span class="badge bg-yellow-500 text-white">Sedang</span>
                                        </div>
                                        <div class="ms-3">
                                            <strong>Pelanggaran Sedang</strong><br>
                                            <small>Jumlah pelanggaran antara 35-74 kali</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-danger mb-0">
                                    <div class="d-flex">
                                        <div>
                                            <span class="badge bg-red-500 text-white">Berat</span>
                                        </div>
                                        <div class="ms-3">
                                            <strong>Pelanggaran Berat</strong><br>
                                            <small>Jumlah pelanggaran 75 kali atau lebih</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Santri Bermasalah</div>
                        </div>
                        <div class="h1 mb-0">{{ $rekapSantri->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>

        <!-- Tabel Rekap -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rekap Pelanggaran Per Santri</h3>
                        <div class="ms-auto">
                            <a href="{{ route('pelanggaran-santri.index') }}" class="btn btn-secondary me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <line x1="5" y1="12" x2="9" y2="16"></line>
                                    <line x1="5" y1="12" x2="9" y2="8"></line>
                                </svg>
                                Kembali
                            </a>
                            <a href="{{ route('pelanggaran-santri.export.pdf', request()->query()) }}"
                                class="btn btn-danger me-2" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                    </path>
                                    <line x1="9" y1="9" x2="10" y2="9"></line>
                                    <line x1="9" y1="13" x2="15" y2="13"></line>
                                    <line x1="9" y1="17" x2="15" y2="17"></line>
                                </svg>
                                Export PDF
                            </a>
                            <a href="{{ route('pelanggaran-santri.export.excel', request()->query()) }}"
                                class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                    </path>
                                    <line x1="9" y1="9" x2="10" y2="9"></line>
                                    <line x1="9" y1="13" x2="15" y2="13"></line>
                                    <line x1="9" y1="17" x2="15" y2="17"></line>
                                </svg>
                                Export Excel
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Santri</th>
                                    <th>Total Pelanggaran</th>
                                    <th>Total Point</th>
                                    <th>Status</th>
                                    <th>Pelanggaran Terakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekapSantri as $item)
                                <tr class="{{ $item->bg_class }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nik ?? '-' }}</td>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td>
                                        <span class="badge bg-dark">{{ $item->total_pelanggaran }}x</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->total_point }} point</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->badge_class }} text-white">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->pelanggaran_terakhir)
                                        {{ \Carbon\Carbon::parse($item->pelanggaran_terakhir)->format('d/m/Y') }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('pelanggaran-santri.index', ['santri_id' => $item->id]) }}"
                                                class="btn btn-sm btn-primary" title="Detail Pelanggaran">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="12" cy="12" r="2"></circle>
                                                    <path
                                                        d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7">
                                                    </path>
                                                </svg>
                                            </a>
                                            
                                            @if($item->total_pelanggaran >= 75)
                                            <a href="{{ route('pelanggaran-santri.surat-peringatan', $item->id) }}"
                                                class="btn btn-sm btn-danger" title="Download Surat Peringatan"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                    <path
                                                        d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                                    </path>
                                                    <path d="M9 7l1 0"></path>
                                                    <path d="M9 13l6 0"></path>
                                                    <path d="M9 17l6 0"></path>
                                                </svg>
                                                Surat Peringatan
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="empty">
                                            <div class="empty-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="12" cy="12" r="9"></circle>
                                                    <line x1="9" y1="10" x2="9.01" y2="10"></line>
                                                    <line x1="15" y1="10" x2="15.01" y2="10"></line>
                                                    <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
                                                </svg>
                                            </div>
                                            <p class="empty-title">Tidak ada data</p>
                                            <p class="empty-subtitle text-muted">
                                                Tidak ada data pelanggaran untuk ditampilkan
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
