@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-event"></i> Jadwal & Agenda Perusahaan</h2>
        <a href="{{ route('agenda.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Agenda Baru</a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <h6 class="text-muted">Hari Ini</h6>
                <h3>{{ $stats['hari_ini'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <h6 class="text-muted">Minggu Ini</h6>
                <h3>{{ $stats['minggu_ini'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <h6 class="text-muted">Terjadwal</h6>
                <h3>{{ $stats['total_terjadwal'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <h6 class="text-muted">Urgent</h6>
                <h3 class="text-danger">{{ $stats['total_urgent'] }}</h3>
            </div></div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agenda as $item)
                    <tr>
                        <td>{{ $item->nomor_agenda }}</td>
                        <td><strong>{{ $item->judul }}</strong></td>
                        <td>{{ $item->tanggal_mulai->format('d M Y') }}<br><small>{{ substr($item->waktu_mulai, 0, 5) }}</small></td>
                        <td>{{ $item->lokasi }}</td>
                        <td><span class="badge bg-{{ $item->status_badge }}">{{ $item->status }}</span></td>
                        <td><span class="badge bg-{{ $item->prioritas_badge }}">{{ $item->prioritas }}</span></td>
                        <td>
                            <a href="{{ route('agenda.show', $item) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('agenda.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('agenda.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus agenda {{ $item->nomor_agenda }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada agenda</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $agenda->links() }}
        </div>
    </div>
</div>
@endsection
