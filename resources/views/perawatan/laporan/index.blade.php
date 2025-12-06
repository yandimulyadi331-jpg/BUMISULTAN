@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                    Laporan Perawatan Gedung
                </h2>
                <div class="text-muted mt-1">History laporan perawatan yang telah dibuat</div>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('perawatan.index') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Laporan</h3>
                    </div>
                    <div class="card-body">
                        @if($laporans->isEmpty())
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                            </div>
                            <p class="empty-title">Belum ada laporan</p>
                            <p class="empty-subtitle text-muted">
                                Laporan akan muncul setelah Anda menyelesaikan semua checklist dan generate laporan
                            </p>
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Tipe</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Checklist</th>
                                        <th>Dibuat Oleh</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporans as $laporan)
                                    <tr>
                                        <td>
                                            <strong>{{ $laporan->periode_key }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $badgeColors = [
                                                    'harian' => 'blue',
                                                    'mingguan' => 'green',
                                                    'bulanan' => 'yellow',
                                                    'tahunan' => 'red'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $badgeColors[$laporan->tipe_laporan] ?? 'secondary' }}">
                                                {{ ucfirst($laporan->tipe_laporan) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $laporan->tanggal_laporan->format('d M Y') }}
                                            <div class="text-muted small">
                                                {{ $laporan->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2">
                                                    {{ $laporan->total_completed }}/{{ $laporan->total_checklist }}
                                                </span>
                                                @if($laporan->total_completed == $laporan->total_checklist)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 12l2 2l4 -4" /></svg>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            {{ $laporan->pembuatLaporan->name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('perawatan.laporan.download', $laporan->id) }}" 
                                               class="btn btn-sm btn-success" 
                                               target="_blank"
                                               title="Download PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><polyline points="7 11 12 16 17 11" /><line x1="12" y1="4" x2="12" y2="16" /></svg>
                                                Download PDF
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $laporans->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
