@extends('layouts.app')
@section('titlepage', 'Inventaris Event')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Event</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('inventaris-event.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i> Tambah Event
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('inventaris-event.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                    <x-input-with-icon label="Cari Nama / Kode Event" value="{{ Request('search') }}" 
                                        name="search" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="draft" {{ Request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="disetujui" {{ Request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="berlangsung" {{ Request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                            <option value="selesai" {{ Request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="dibatalkan" {{ Request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ Request('tanggal_mulai') }}" placeholder="Tanggal Mulai">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fa fa-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('inventaris-event.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Event</th>
                                <th>Nama Event</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Jumlah Item</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td>{{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}</td>
                                <td><strong>{{ $event->kode_event }}</strong></td>
                                <td>
                                    <strong>{{ $event->nama_event }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($event->deskripsi, 40) }}</small>
                                </td>
                                <td>{{ $event->lokasi_event }}</td>
                                <td>
                                    <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d/m/Y H:i') }}<br>
                                    <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $event->items->count() }} item</span>
                                </td>
                                <td>
                                    @if($event->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($event->status == 'disetujui')
                                        <span class="badge bg-info">Disetujui</span>
                                    @elseif($event->status == 'berlangsung')
                                        <span class="badge bg-success">Berlangsung</span>
                                    @elseif($event->status == 'selesai')
                                        <span class="badge bg-dark">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('inventaris-event.show', $event->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if($event->status == 'draft' || $event->status == 'disetujui')
                                            <a href="{{ route('inventaris-event.edit', $event->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($event->status == 'disetujui')
                                            <button type="button" class="btn btn-sm btn-success btn-distribusi" 
                                                data-id="{{ $event->id }}" title="Distribusi">
                                                <i class="fa fa-share"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('inventaris-event.export-pdf', $event->id) }}" class="btn btn-sm btn-danger" target="_blank" title="Export PDF">
                                            <i class="fa fa-file-pdf"></i>
                                        </a>
                                        @if($event->status == 'draft')
                                            <form action="{{ route('inventaris-event.destroy', $event->id) }}" method="POST" class="d-inline" 
                                                onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data event</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-3">
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h5>Draft</h5>
                <h2>{{ $events->where('status', 'draft')->count() }}</h2>
                <p class="mb-0"><i class="fa fa-file"></i> Event</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Disetujui</h5>
                <h2>{{ $events->where('status', 'disetujui')->count() }}</h2>
                <p class="mb-0"><i class="fa fa-check"></i> Event</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Berlangsung</h5>
                <h2>{{ $events->where('status', 'berlangsung')->count() }}</h2>
                <p class="mb-0"><i class="fa fa-play"></i> Event</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <h5>Selesai</h5>
                <h2>{{ $events->where('status', 'selesai')->count() }}</h2>
                <p class="mb-0"><i class="fa fa-flag-checkered"></i> Event</p>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    $(document).on('click', '.btn-distribusi', function() {
        let id = $(this).data('id');
        window.location.href = `/inventaris-event/${id}/distribusi`;
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 3000
        });
    @endif
</script>
@endpush
@endsection
