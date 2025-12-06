@extends('layouts.app')
@section('titlepage', 'WhatsApp Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Dashboard /</span> WhatsApp
    </h4>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="ti ti-device-mobile menu-icon tf-icons rounded" style="font-size: 2rem; color: #28a745;"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Devices</span>
                    <h3 class="card-title mb-2">{{ $connectedDevices }}/{{ $totalDevices }}</h3>
                    <small class="text-success fw-semibold">
                        <i class="ti ti-check"></i> Connected
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="ti ti-message menu-icon tf-icons rounded" style="font-size: 2rem; color: #17a2b8;"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Messages</span>
                    <h3 class="card-title mb-2">{{ number_format($totalMessages) }}</h3>
                    <small class="text-muted fw-semibold">Hari ini</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="ti ti-send menu-icon tf-icons rounded" style="font-size: 2rem; color: #ffc107;"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Broadcasts</span>
                    <h3 class="card-title mb-2">{{ $totalBroadcasts }}</h3>
                    <small class="text-warning fw-semibold">Scheduled</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="ti ti-users menu-icon tf-icons rounded" style="font-size: 2rem; color: #6c757d;"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Contacts</span>
                    <h3 class="card-title mb-2">{{ number_format($totalContacts) }}</h3>
                    <small class="text-muted fw-semibold">Total kontak</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-6 mb-3">
                    <a href="{{ route('whatsapp.broadcasts') }}" class="btn btn-primary w-100">
                        <i class="ti ti-send me-1"></i> Broadcast Baru
                    </a>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <a href="{{ route('whatsapp.devices') }}" class="btn btn-secondary w-100">
                        <i class="ti ti-device-mobile me-1"></i> Kelola Device
                    </a>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <a href="{{ route('whatsapp.contacts') }}" class="btn btn-warning w-100">
                        <i class="ti ti-users me-1"></i> Contacts
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Device Status --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Device Status</h5>
            <a href="{{ route('whatsapp.devices') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus me-1"></i> Tambah Device
            </a>
        </div>
        <div class="card-body">
            @if($devices->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Last Seen</th>
                                <th>Groups</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                            <tr>
                                <td>
                                    <strong>{{ $device->device_name }}</strong>
                                </td>
                                <td>{{ $device->phone_number }}</td>
                                <td>
                                    @if($device->status === 'connected')
                                        <span class="badge bg-success">
                                            <i class="ti ti-circle-check"></i> Connected
                                        </span>
                                    @elseif($device->status === 'scanning')
                                        <span class="badge bg-warning">
                                            <i class="ti ti-qrcode"></i> Scanning
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="ti ti-circle-x"></i> Disconnected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $device->last_seen ? $device->last_seen->diffForHumans() : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $totalGroups }} groups</span>
                                </td>
                                <td>
                                    <a href="{{ route('whatsapp.devices') }}" class="btn btn-sm btn-icon btn-info">
                                        <i class="ti ti-settings"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    Belum ada device terdaftar. <a href="{{ route('whatsapp.devices') }}">Tambah device sekarang</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Messages --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Messages</h5>
        </div>
        <div class="card-body">
            @if($recentMessages->count() > 0)
                <div class="list-group">
                    @foreach($recentMessages as $message)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $message->from_number }}</h6>
                                <p class="mb-1 text-muted">{{ Str::limit($message->message_text, 100) }}</p>
                                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                @if($message->is_group)
                                    <span class="badge bg-label-info">
                                        <i class="ti ti-users"></i> Group
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    Belum ada pesan masuk hari ini.
                </div>
            @endif
        </div>
    </div>

</div>
</div>
@endsection

@section('myscript')
<script>
    // Auto refresh status setiap 30 detik
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
@endsection
