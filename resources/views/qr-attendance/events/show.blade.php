@extends('layouts.app')
@section('titlepage', 'Detail Event')

@section('content')
@section('navigasi')
    <span><a href="{{ route('qr-attendance.events.index') }}">Event QR Attendance</a> / Detail</span>
@endsection

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ $event->event_name }}</h5>
                <div>
                    @if($event->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Kode Event</th>
                        <td>{{ $event->event_code }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $event->event_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td>{{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}</td>
                    </tr>
                    <tr>
                        <th>Venue</th>
                        <td>{{ $event->venue_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>GPS Koordinat</th>
                        <td>{{ $event->venue_latitude }}, {{ $event->venue_longitude }}</td>
                    </tr>
                    <tr>
                        <th>Radius Geofence</th>
                        <td>{{ $event->venue_radius_meter }} meter</td>
                    </tr>
                    <tr>
                        <th>Cabang</th>
                        <td>{{ $event->cabang->nama_cabang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td>{{ $event->creator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $event->description ?? '-' }}</td>
                    </tr>
                </table>

                <div class="mt-3">
                    <a href="{{ route('qr-attendance.events.edit', $event->id) }}" class="btn btn-warning">
                        <i class="ti ti-edit"></i> Edit
                    </a>
                    @if($event->event_date->isToday())
                        <a href="{{ route('qr-attendance.events.display-qr', $event->id) }}" class="btn btn-success" target="_blank">
                            <i class="ti ti-qrcode"></i> Tampilkan QR Code
                        </a>
                    @endif
                    <a href="{{ route('qr-attendance.events.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>Statistik Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3 class="text-primary">{{ $statistics['total_attendance'] }}</h3>
                            <small class="text-muted">Total Hadir</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3 class="text-success">{{ $statistics['qr_code_method'] }}</h3>
                            <small class="text-muted">Via QR Code</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3 class="text-info">{{ $statistics['fingerprint_method'] }}</h3>
                            <small class="text-muted">Via Fingerprint</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3 class="text-danger">{{ $statistics['failed_scans'] }}</h3>
                            <small class="text-muted">Scan Gagal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>Log Aktivitas Terakhir</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Jamaah</th>
                                <th>Status</th>
                                <th>Jarak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td>{{ $log->scan_at->format('H:i:s') }}</td>
                                    <td>{{ $log->jamaah->nama ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $log->getStatusBadgeClass() }}">
                                            {{ $log->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $log->distance_from_venue ? round($log->distance_from_venue) . 'm' : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada aktivitas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
