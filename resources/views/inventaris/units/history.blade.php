@extends('layouts.app')
@section('titlepage', 'History Unit: ' . $unit->kode_unit)

@section('content')
@section('navigasi')
    <span>
        <a href="{{ route('inventaris.index') }}">Master Data Inventaris</a> / 
        <a href="{{ route('inventaris.detail', $unit->inventaris_id) }}">Detail {{ $unit->inventaris->nama_barang }}</a> / 
        History Unit
    </span>
@endsection

<div class="row">
    <div class="col-lg-4 col-md-5 mb-3">
        <!-- Info Unit Card -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="ti ti-info-circle me-2"></i>Info Unit</h5>
            </div>
            <div class="card-body">
                @if($unit->foto_unit)
                    <img src="{{ Storage::url($unit->foto_unit) }}" class="img-fluid mb-3 rounded" alt="Foto Unit">
                @else
                    <div class="text-center p-4 mb-3" style="background: #f5f5f5; border-radius: 8px;">
                        <i class="ti ti-package" style="font-size: 64px; color: #ccc;"></i>
                    </div>
                @endif

                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 120px;">Kode Unit</td>
                        <td><strong>{{ $unit->kode_unit }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Inventaris</td>
                        <td>{{ $unit->inventaris->nama_barang }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kondisi</td>
                        <td>
                            <span class="badge {{ $unit->getKondisiBadgeClass() }}">
                                {{ $unit->getKondisiLabel() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge {{ $unit->getStatusBadgeClass() }}">
                                {{ $unit->getStatusLabel() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lokasi</td>
                        <td>{{ $unit->lokasi_saat_ini ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nomor Seri</td>
                        <td>{{ $unit->nomor_seri_unit ?? '-' }}</td>
                    </tr>
                </table>

                <div class="mt-3">
                    <a href="{{ route('inventaris.detail', $unit->inventaris_id) }}" class="btn btn-secondary w-100">
                        <i class="ti ti-arrow-left me-1"></i> Kembali ke Detail Inventaris
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-7">
        <!-- Filter & Action Card -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('inventaris.units.history', [$unit->inventaris_id, $unit->id]) }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Filter Minggu</label>
                        <select name="week" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @for($i = 0; $i < 8; $i++)
                                @php
                                    $startOfWeek = \Carbon\Carbon::now()->subWeeks($i)->startOfWeek();
                                    $endOfWeek = \Carbon\Carbon::now()->subWeeks($i)->endOfWeek();
                                    $weekLabel = $i == 0 ? 'Minggu Ini' : ($i == 1 ? 'Minggu Lalu' : $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M'));
                                    $weekValue = $startOfWeek->format('Y-m-d');
                                @endphp
                                <option value="{{ $weekValue }}" {{ request('week') == $weekValue ? 'selected' : '' }}>
                                    {{ $weekLabel }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pencarian</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari peminjam, catatan..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jenis Aktivitas</label>
                        <select name="jenis" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="pinjam" {{ request('jenis') == 'pinjam' ? 'selected' : '' }}>Peminjaman</option>
                            <option value="kembali" {{ request('jenis') == 'kembali' ? 'selected' : '' }}>Pengembalian</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i>
                        </button>
                        <a href="{{ route('inventaris.units.history', [$unit->inventaris_id, $unit->id]) }}" class="btn btn-secondary">
                            <i class="ti ti-x"></i>
                        </a>
                    </div>
                </form>
                
                <div class="mt-3">
                    <a href="{{ route('inventaris.units.history.pdf', [$unit->inventaris_id, $unit->id]) }}?week={{ request('week') }}&search={{ request('search') }}&jenis={{ request('jenis') }}" 
                       class="btn btn-danger" target="_blank">
                        <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Timeline History -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="ti ti-history me-2"></i>Timeline Peminjaman & Pengembalian
                    @if(request('week'))
                        <small class="ms-2">({{ \Carbon\Carbon::parse(request('week'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('week'))->endOfWeek()->format('d M Y') }})</small>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($histories->count() > 0)
                    <div class="timeline">
                        @foreach($histories as $history)
                            <div class="timeline-item mb-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-sm rounded-circle 
                                            {{ $history->jenis_aktivitas === 'pinjam' ? 'bg-warning' : 'bg-success' }}">
                                            <i class="ti {{ $history->jenis_aktivitas === 'pinjam' ? 'ti-arrow-right' : 'ti-arrow-back-up' }} text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0">
                                                        <span class="badge {{ $history->jenis_aktivitas === 'pinjam' ? 'bg-warning' : 'bg-success' }}">
                                                            {{ $history->jenis_aktivitas === 'pinjam' ? 'Peminjaman' : 'Pengembalian' }}
                                                        </span>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="ti ti-clock"></i>
                                                        {{ $history->created_at->format('d M Y H:i') }}
                                                        <span class="text-muted">({{ $history->created_at->diffForHumans() }})</span>
                                                    </small>
                                                </div>

                                                @if($history->jenis_aktivitas === 'pinjam')
                                                    <!-- Detail Peminjaman -->
                                                    @if($history->peminjaman)
                                                        <div class="alert alert-warning mb-2">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <small><i class="ti ti-user"></i> <strong>Peminjam:</strong> {{ $history->peminjaman->nama_peminjam ?? '-' }}</small>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <small><i class="ti ti-calendar"></i> <strong>Tgl Pinjam:</strong> {{ $history->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($history->peminjaman->tanggal_pinjam)->format('d M Y') : '-' }}</small>
                                                                </div>
                                                                <div class="col-md-12 mt-1">
                                                                    <small><i class="ti ti-calendar-due"></i> <strong>Rencana Kembali:</strong> {{ $history->peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($history->peminjaman->tanggal_kembali_rencana)->format('d M Y') : '-' }}</small>
                                                                </div>
                                                                <div class="col-md-12 mt-1">
                                                                    <small><i class="ti ti-file-text"></i> <strong>Keperluan:</strong> {{ $history->peminjaman->keperluan ?? '-' }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif($history->jenis_aktivitas === 'kembali')
                                                    <!-- Detail Pengembalian -->
                                                    @if($history->pengembalian)
                                                        <div class="alert alert-success mb-2">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <small><i class="ti ti-calendar-check"></i> <strong>Tgl Kembali:</strong> {{ $history->pengembalian->tanggal_pengembalian ? \Carbon\Carbon::parse($history->pengembalian->tanggal_pengembalian)->format('d M Y') : '-' }}</small>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <small><i class="ti ti-mood-check"></i> <strong>Kondisi:</strong> 
                                                                        <span class="badge bg-{{ $history->pengembalian->kondisi_barang === 'baik' ? 'success' : 'danger' }}">
                                                                            {{ ucfirst(str_replace('_', ' ', $history->pengembalian->kondisi_barang ?? 'baik')) }}
                                                                        </span>
                                                                    </small>
                                                                </div>
                                                                @if($history->pengembalian->denda > 0)
                                                                <div class="col-md-12 mt-1">
                                                                    <small><i class="ti ti-coin"></i> <strong>Denda:</strong> Rp {{ number_format($history->pengembalian->denda, 0, ',', '.') }}</small>
                                                                </div>
                                                                @endif
                                                                @if($history->pengembalian->catatan_pengembalian)
                                                                <div class="col-md-12 mt-1">
                                                                    <small><i class="ti ti-note"></i> <strong>Catatan:</strong> {{ $history->pengembalian->catatan_pengembalian }}</small>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif

                                                <p class="mb-2 text-muted">
                                                    <i class="ti ti-file-description"></i> {{ $history->deskripsi }}
                                                </p>

                                                @if($history->user)
                                                    <small class="text-muted">
                                                        <i class="ti ti-user-circle"></i> Oleh: <strong>{{ $history->user->name }}</strong>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-history" style="font-size: 64px; color: #ccc;"></i>
                        <p class="text-muted mt-3">Belum ada riwayat peminjaman atau pengembalian untuk unit ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    bottom: -30px;
    width: 2px;
    background: #e0e0e0;
}

.avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar i {
    font-size: 20px;
}
</style>
@endsection
