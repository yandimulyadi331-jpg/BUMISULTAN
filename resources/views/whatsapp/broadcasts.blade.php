@extends('layouts.app')
@section('titlepage', 'Broadcast Center - WhatsApp')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('whatsapp.index') }}">WhatsApp</a> /</span> Broadcast Center
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Buat Broadcast Baru</h5>
        </div>
        <div class="card-body">
            <form id="broadcastForm">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Judul Broadcast <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Reminder Slip Gaji November" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Penerima <span class="text-danger">*</span></label>
                            <select name="target_type" id="targetType" class="form-select" required>
                                <option value="">-- Pilih Target --</option>
                                <option value="all">Semua Karyawan</option>
                                <option value="departemen">Filter by Departemen</option>
                                <option value="jabatan">Filter by Jabatan</option>
                                <option value="grup">Kirim ke Grup WhatsApp</option>
                                <option value="custom">Custom (Pilih Manual)</option>
                            </select>
                        </div>

                        {{-- Filter Departemen --}}
                        <div id="filterDepartemen" class="mb-3" style="display:none;">
                            <label class="form-label">Pilih Departemen</label>
                            <select name="target_filter[departemen_id]" class="form-select">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departemen as $dept)
                                    <option value="{{ $dept->kode_dept }}">{{ $dept->nama_dept }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Jabatan --}}
                        <div id="filterJabatan" class="mb-3" style="display:none;">
                            <label class="form-label">Pilih Jabatan</label>
                            <select name="target_filter[jabatan_id]" class="form-select">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatan as $jab)
                                    <option value="{{ $jab->kode_jabatan }}">{{ $jab->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Grup --}}
                        <div id="filterGrup" class="mb-3" style="display:none;">
                            <label class="form-label">Pilih Grup WhatsApp</label>
                            <div class="card">
                                <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                    @if($groups->count() > 0)
                                        @foreach($groups as $group)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-checkbox" type="checkbox" name="target_filter[group_ids][]" value="{{ $group->id }}" id="group{{ $group->id }}">
                                            <label class="form-check-label" for="group{{ $group->id }}">
                                                <strong>{{ $group->group_name }}</strong>
                                                <br><small class="text-muted">{{ $group->total_members }} members</small>
                                            </label>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-info mb-0">
                                            Belum ada grup. <a href="{{ route('whatsapp.devices') }}">Sync grup dari device</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea name="message_text" class="form-control" rows="8" placeholder="Ketik pesan Anda di sini..." required></textarea>
                            <small class="text-muted">Gunakan variable: {nama}, {nik}, {departemen}, {jabatan}</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Media (Optional)</label>
                            <input type="file" name="media" class="form-control" accept="image/*,.pdf">
                            <small class="text-muted">JPG, PNG, atau PDF (Max 5MB)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Schedule (Optional)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" name="schedule_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <input type="time" name="schedule_time" class="form-control">
                                </div>
                            </div>
                            <small class="text-muted">Kosongkan untuk kirim sekarang</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Preview</h6>
                                <div id="recipientPreview" class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Pilih target untuk melihat preview
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <h6 class="card-title">Tips</h6>
                                <ul class="mb-0" style="padding-left: 20px; font-size: 12px;">
                                    <li>Jeda antar pesan otomatis 5-10 detik</li>
                                    <li>Max 500 pesan per device per hari</li>
                                    <li>Pesan ke grup lebih efisien</li>
                                    <li>Test dulu dengan 1-2 penerima</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-send me-1"></i> Kirim Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Broadcast History --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Broadcast History</h5>
        </div>
        <div class="card-body">
            @if($broadcasts->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Target</th>
                                <th>Status</th>
                                <th>Terkirim</th>
                                <th>Tanggal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($broadcasts as $broadcast)
                            <tr>
                                <td><strong>{{ $broadcast->title }}</strong></td>
                                <td>
                                    <span class="badge bg-label-primary">{{ ucfirst($broadcast->target_type) }}</span>
                                </td>
                                <td>
                                    @if($broadcast->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($broadcast->status === 'sending')
                                        <span class="badge bg-warning">Sending...</span>
                                    @elseif($broadcast->status === 'scheduled')
                                        <span class="badge bg-info">Scheduled</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                                <td>{{ $broadcast->total_sent }}/{{ $broadcast->total_recipients }}</td>
                                <td>{{ $broadcast->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-info" onclick="viewBroadcast({{ $broadcast->id }})">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $broadcasts->links() }}
            @else
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    Belum ada broadcast. Buat broadcast pertama Anda sekarang!
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection

@section('myscript')
<script>
    // Toggle filter berdasarkan target type
    $('#targetType').on('change', function() {
        const targetType = $(this).val();
        
        // Hide all filters
        $('#filterDepartemen, #filterJabatan, #filterGrup').hide();
        
        // Show selected filter
        if (targetType === 'departemen') {
            $('#filterDepartemen').show();
        } else if (targetType === 'jabatan') {
            $('#filterJabatan').show();
        } else if (targetType === 'grup') {
            $('#filterGrup').show();
        }
        
        // Update preview
        updatePreview();
    });

    // Update preview saat filter berubah
    $('select[name="target_filter[departemen_id]"], select[name="target_filter[jabatan_id]"], .group-checkbox').on('change', function() {
        updatePreview();
    });

    function updatePreview() {
        const targetType = $('#targetType').val();
        let preview = '';
        
        if (targetType === 'all') {
            preview = '<i class="ti ti-users me-2"></i> <strong>Semua Karyawan</strong><br><small>Estimasi: ~{{ \App\Models\WaContact::where("type", "karyawan")->count() }} penerima</small>';
        } else if (targetType === 'grup') {
            const selectedGroups = $('.group-checkbox:checked').length;
            if (selectedGroups > 0) {
                preview = `<i class="ti ti-send me-2"></i> <strong>${selectedGroups} Grup Dipilih</strong><br><small>Pesan akan dikirim ke ${selectedGroups} grup WhatsApp</small>`;
            } else {
                preview = '<i class="ti ti-alert-triangle me-2"></i> Pilih minimal 1 grup';
            }
        } else {
            preview = '<i class="ti ti-info-circle me-2"></i> Pilih filter untuk melihat preview';
        }
        
        $('#recipientPreview').html(preview);
    }

    // Submit broadcast
    $('#broadcastForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Kirim Broadcast?',
            text: 'Pesan akan dikirim ke penerima yang dipilih',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(this);
                
                Swal.fire({
                    title: 'Mengirim Broadcast...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '{{ route("whatsapp.broadcasts.create") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan', 'error');
                    }
                });
            }
        });
    });

    function viewBroadcast(id) {
        // TODO: Show broadcast detail modal
        Swal.fire('Detail Broadcast', 'Fitur detail broadcast akan segera hadir', 'info');
    }
</script>
@endsection
