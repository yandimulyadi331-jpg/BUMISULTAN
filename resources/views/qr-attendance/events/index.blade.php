@extends('layouts.app')
@section('titlepage', 'Kelola Event QR Attendance')

@section('content')
@section('navigasi')
    <span>Kelola Event QR Code Attendance</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Event Pengajian / Kajian</h5>
                    <a href="{{ route('qr-attendance.events.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Buat Event Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form action="{{ route('qr-attendance.events.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <x-input-with-icon label="Tanggal" value="{{ Request('tanggal') }}" name="tanggal" 
                                icon="ti ti-calendar" datepicker="flatpickr-date" />
                        </div>
                        <div class="col-md-3">
                            <x-select label="Cabang" name="kode_cabang" :data="$cabang" 
                                key="kode_cabang" textShow="nama_cabang" 
                                selected="{{ Request('kode_cabang') }}" select2="select2Kodecabang" />
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="active" {{ Request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ Request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Event</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Lokasi</th>
                                <th>Cabang</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $index => $event)
                                <tr>
                                    <td>{{ $events->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $event->event_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $event->event_code }}</small>
                                    </td>
                                    <td>{{ $event->event_date->format('d/m/Y') }}</td>
                                    <td>
                                        {{ date('H:i', strtotime($event->event_start_time)) }} - 
                                        {{ date('H:i', strtotime($event->event_end_time)) }}
                                    </td>
                                    <td>
                                        {{ $event->venue_name ?? 'N/A' }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="ti ti-map-pin"></i> 
                                            Radius: {{ $event->venue_radius_meter }}m
                                        </small>
                                    </td>
                                    <td>{{ $event->cabang->nama_cabang ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $event->attendances->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($event->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('qr-attendance.events.show', $event->id) }}" 
                                                class="btn btn-sm btn-info" title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('qr-attendance.events.edit', $event->id) }}" 
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            @if($event->event_date->isToday())
                                                <a href="{{ route('qr-attendance.events.display-qr', $event->id) }}" 
                                                    class="btn btn-sm btn-success" title="Tampilkan QR" target="_blank">
                                                    <i class="ti ti-qrcode"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('qr-attendance.events.toggle-status', $event->id) }}" 
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-secondary" 
                                                    title="{{ $event->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="ti ti-{{ $event->is_active ? 'toggle-right' : 'toggle-left' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data event</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Flatpickr date
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d"
        });
    });
</script>
@endpush
