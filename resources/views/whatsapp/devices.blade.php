@extends('layouts.app')
@section('titlepage', 'Device Management - WhatsApp')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('whatsapp.index') }}">WhatsApp</a> /</span> Device Management
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">WhatsApp Devices</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                <i class="ti ti-plus me-1"></i> Tambah Device
            </button>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>
                <strong>Info:</strong> Anda bisa menambahkan beberapa device WhatsApp untuk load balancing dan backup.
                Maksimal 5 device aktif direkomendasikan.
            </div>

            @if($devices->count() > 0)
                <div class="row">
                    @foreach($devices as $device)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border {{ $device->status === 'connected' ? 'border-success' : ($device->status === 'scanning' ? 'border-warning' : 'border-danger') }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $device->device_name }}</h5>
                                        <p class="text-muted mb-0">{{ $device->phone_number }}</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="syncGroups({{ $device->id }})">
                                                    <i class="ti ti-refresh me-2"></i> Sync Groups
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteDevice({{ $device->id }}, '{{ $device->device_name }}')">
                                                    <i class="ti ti-trash me-2"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    @if($device->status === 'connected')
                                        <span class="badge bg-success w-100 py-2">
                                            <i class="ti ti-circle-check"></i> Connected
                                        </span>
                                    @elseif($device->status === 'scanning')
                                        <span class="badge bg-warning w-100 py-2">
                                            <i class="ti ti-qrcode"></i> Scanning QR Code
                                        </span>
                                    @else
                                        <span class="badge bg-danger w-100 py-2">
                                            <i class="ti ti-circle-x"></i> Disconnected
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Groups:</small>
                                    <small><strong>{{ $device->groups_count }}</strong></small>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Messages:</small>
                                    <small><strong>{{ number_format($device->messages_count) }}</strong></small>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Last Seen:</small>
                                    <small>{{ $device->last_seen ? $device->last_seen->diffForHumans() : '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ti ti-device-mobile" style="font-size: 5rem; color: #ddd;"></i>
                    <h5 class="mt-3">Belum Ada Device</h5>
                    <p class="text-muted">Tambahkan device WhatsApp pertama Anda untuk mulai mengirim pesan.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                        <i class="ti ti-plus me-1"></i> Tambah Device Sekarang
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
</div>

{{-- Modal Add Device --}}
<div class="modal fade" id="addDeviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Device WhatsApp - Fonnte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDeviceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Device <span class="text-danger">*</span></label>
                        <input type="text" name="device_name" class="form-control" placeholder="Contoh: WhatsApp HRD" required>
                        <small class="text-muted">Nama untuk identifikasi device ini</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">API Key Fonnte <span class="text-danger">*</span></label>
                        <input type="text" name="api_key" class="form-control" placeholder="Masukkan API Key dari Fonnte" required>
                        <small class="text-muted">
                            Dapatkan API Key di <a href="https://fonnte.com" target="_blank">fonnte.com</a>
                        </small>
                    </div>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>
                            <strong>Cara mendapatkan API Key:</strong><br>
                            1. Daftar/Login di <a href="https://fonnte.com" target="_blank">fonnte.com</a><br>
                            2. Beli paket WhatsApp API (mulai Rp 115k/bulan)<br>
                            3. Copy API Key dari dashboard Fonnte<br>
                            4. Paste API Key di form ini
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Tambah Device
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('myscript')
<script>
    // Add Device
    $('#addDeviceForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');
        
        $.ajax({
            url: '{{ route("whatsapp.devices.add") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan',
                    confirmButtonText: 'OK'
                });
                submitBtn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i> Tambah Device');
            }
        });
    });

    // Delete Device
    function deleteDevice(id, name) {
        Swal.fire({
            title: 'Hapus Device?',
            text: `Yakin ingin menghapus device "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/whatsapp/devices/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan', 'error');
                    }
                });
            }
        });
    }

    // Sync Groups
    function syncGroups(deviceId) {
        Swal.fire({
            title: 'Sync Groups',
            text: 'Mengambil daftar grup dari WhatsApp...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ route("whatsapp.groups.sync") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                device_id: deviceId
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil!', response.message, 'success');
                }
            },
            error: function(xhr) {
                Swal.fire('Gagal!', 'Tidak dapat sync grup', 'error');
            }
        });
    }
</script>
@endsection
