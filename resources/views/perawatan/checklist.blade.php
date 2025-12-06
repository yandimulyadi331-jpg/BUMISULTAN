@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-white mb-1">
                                <i class="ti ti-clipboard-check me-2"></i>Checklist Perawatan {{ ucfirst($tipe) }}
                            </h4>
                            <p class="mb-0 small">
                                Periode: <strong>{{ $periodeKey }}</strong> | 
                                Progress: <strong>{{ $statusPeriode->total_completed }}/{{ $statusPeriode->total_checklist }}</strong>
                            </p>
                        </div>
                        <a href="{{ route('perawatan.index') }}" class="btn btn-white btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Card --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card {{ $statusPeriode->is_completed ? 'border-success' : 'border-warning' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">
                                @if($statusPeriode->is_completed)
                                    <i class="ti ti-circle-check text-success me-2"></i>Semua Checklist Selesai!
                                @else
                                    <i class="ti ti-hourglass-empty text-warning me-2"></i>Checklist Belum Selesai
                                @endif
                            </h5>
                            <p class="text-muted mb-2 small">
                                @if($statusPeriode->is_completed)
                                    Semua kegiatan sudah dikerjakan. Silakan generate laporan!
                                @else
                                    Selesaikan {{ $statusPeriode->total_checklist - $statusPeriode->total_completed }} kegiatan lagi untuk generate laporan
                                @endif
                            </p>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar {{ $statusPeriode->is_completed ? 'bg-success' : 'bg-warning' }}" 
                                     role="progressbar" 
                                     style="width: {{ $statusPeriode->total_checklist > 0 ? ($statusPeriode->total_completed / $statusPeriode->total_checklist) * 100 : 0 }}%">
                                    <strong>{{ $statusPeriode->total_checklist > 0 ? number_format(($statusPeriode->total_completed / $statusPeriode->total_checklist) * 100, 1) : 0 }}%</strong>
                                </div>
                            </div>
                        </div>
                        <div class="ms-3">
                            @if($statusPeriode->is_completed)
                                <button type="button" class="btn btn-success" id="btnGenerateLaporan">
                                    <i class="ti ti-file-download me-1"></i> Generate Laporan
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="ti ti-lock me-1"></i> Belum Bisa Generate
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Checklist Content --}}
    @if($masters->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-clipboard-off" style="font-size: 4rem; color: #ccc;"></i>
                    </div>
                    <h5 class="text-muted">Belum ada checklist {{ $tipe }}</h5>
                    <p class="text-muted small">
                        Admin belum membuat template checklist untuk periode ini.<br>
                        Hubungi admin untuk menambahkan checklist {{ $tipe }}.
                    </p>
                    <a href="{{ route('perawatan.master.index') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="ti ti-plus me-1"></i> Kelola Master Checklist
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Checklist by Kategori --}}
    @php
        $kategoris = $masters->groupBy('kategori');
        $kategoriIcons = [
            'kebersihan' => '🧹',
            'perawatan_rutin' => '🔧',
            'pengecekan' => '✅',
            'lainnya' => '📋'
        ];
        $kategoriColors = [
            'kebersihan' => 'primary',
            'perawatan_rutin' => 'success',
            'pengecekan' => 'warning',
            'lainnya' => 'secondary'
        ];
    @endphp

    @foreach($kategoris as $kategori => $items)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-{{ $kategoriColors[$kategori] ?? 'secondary' }}">
                    <h5 class="card-title text-white mb-0">
                        <span class="me-2">{{ $kategoriIcons[$kategori] ?? '📋' }}</span>
                        {{ ucfirst(str_replace('_', ' ', $kategori)) }}
                        <span class="badge bg-white text-{{ $kategoriColors[$kategori] ?? 'secondary' }} ms-2">
                            {{ $items->filter(fn($m) => isset($logs[$m->id]))->count() }}/{{ $items->count() }}
                        </span>
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($items as $master)
                    @php
                        $logData = isset($logs[$master->id]) ? $logs[$master->id] : null;
                        $isChecked = $logData !== null;
                    @endphp
                    <div class="list-group-item checklist-item {{ $isChecked ? 'bg-label-success' : '' }}" data-master-id="{{ $master->id }}">
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input class="form-check-input checklist-checkbox" 
                                       type="checkbox" 
                                       data-master-id="{{ $master->id }}" 
                                       {{ $isChecked ? 'checked' : '' }}
                                       style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 {{ $isChecked ? 'text-success' : '' }}">{{ $master->nama_kegiatan }}</h6>
                                @if($master->deskripsi)
                                    <p class="text-muted small mb-1">{{ $master->deskripsi }}</p>
                                @endif
                                @if($isChecked && $logData)
                                    <div class="mt-1">
                                        <span class="badge bg-label-primary me-1">
                                            <i class="ti ti-user"></i> {{ $logData->user ? $logData->user->name : 'Unknown' }}
                                        </span>
                                        <span class="badge bg-label-info">
                                            <i class="ti ti-clock"></i> {{ $logData->waktu_eksekusi ? date('H:i', strtotime($logData->waktu_eksekusi)) : '' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if($isChecked)
                                    <span class="badge bg-success">
                                        <i class="ti ti-check me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

</div>

@endsection

@push('styles')
<style>
    .checklist-item {
        transition: all 0.3s ease;
    }
    
    .checklist-item:hover {
        background-color: #f8f9fa;
    }
    
    .bg-label-success {
        background-color: #d4edda !important;
        border-left: 4px solid #28a745;
    }
    
    .checklist-checkbox:hover {
        transform: scale(1.1);
        cursor: pointer;
    }
    
    .card-header.bg-primary,
    .card-header.bg-success,
    .card-header.bg-warning,
    .card-header.bg-secondary {
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    const tipe = '{{ $tipe }}';
    
    // Handle checkbox change
    $('.checklist-checkbox').on('change', function() {
        const checkbox = $(this);
        const masterId = checkbox.data('master-id');
        const isChecked = checkbox.is(':checked');
        
        if (isChecked) {
            executeChecklist(masterId, checkbox);
        } else {
            uncheckChecklist(masterId, checkbox);
        }
    });

    // Execute checklist
    function executeChecklist(masterId, checkbox) {
        $.ajax({
            url: '{{ route("perawatan.checklist.execute") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                master_perawatan_id: masterId,
                tipe_periode: tipe
            },
            beforeSend: function() {
                checkbox.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // Update UI
                    const item = checkbox.closest('.checklist-item');
                    item.addClass('bg-label-success');
                    item.find('h6').addClass('text-success');
                    item.find('.badge').removeClass('bg-secondary').addClass('bg-success')
                        .html('<i class="ti ti-check me-1"></i> Selesai');
                    
                    // Show success toast
                    showToast('success', response.message);
                    
                    // Reload to update progress
                    setTimeout(() => location.reload(), 500);
                } else {
                    checkbox.prop('checked', false);
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                checkbox.prop('checked', false);
                const message = xhr.responseJSON?.message || 'Gagal mencentang checklist!';
                showToast('error', message);
            },
            complete: function() {
                checkbox.prop('disabled', false);
            }
        });
    }

    // Uncheck checklist
    function uncheckChecklist(masterId, checkbox) {
        // Show modern confirmation modal
        const masterName = checkbox.closest('.checklist-item').find('h6').text();
        
        const modalHtml = `
            <div class="modal fade" id="modalConfirmUncheck" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title">
                                <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Batalkan
                            </h5>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="mb-3">
                                <i class="ti ti-circle-x" style="font-size: 4rem; color: #ffc107;"></i>
                            </div>
                            <h5 class="mb-2">Batalkan Checklist?</h5>
                            <p class="text-muted mb-0">
                                <strong>${masterName}</strong><br>
                                Data log akan dihapus dan harus dicentang ulang.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCancelUncheck">
                                <i class="ti ti-x me-1"></i> Tidak
                            </button>
                            <button type="button" class="btn btn-warning" id="btnConfirmUncheck" data-master-id="${masterId}">
                                <i class="ti ti-check me-1"></i> Ya, Batalkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmUncheck'));
        modal.show();
        
        // Handle cancel
        $('#btnCancelUncheck').on('click', function() {
            checkbox.prop('checked', true);
        });
        
        $('#modalConfirmUncheck').on('hidden.bs.modal', function() {
            $(this).remove();
        });
        
        // Handle confirm uncheck
        $('#btnConfirmUncheck').on('click', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            
            $.ajax({
                url: '{{ route("perawatan.checklist.uncheck") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    master_perawatan_id: masterId,
                    tipe_periode: tipe
                },
                beforeSend: function() {
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Proses...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modalConfirmUncheck').modal('hide');
                        showToast('info', response.message);
                        
                        // Reload to update progress
                        setTimeout(() => location.reload(), 500);
                    }
                },
                error: function(xhr) {
                    checkbox.prop('checked', true);
                    showToast('error', 'Gagal membatalkan checklist!');
                    btn.html(originalHtml).prop('disabled', false);
                }
            });
        });
    }

    // Generate Laporan
    $('#btnGenerateLaporan').on('click', function() {
        // Show modern confirmation modal
        const modalHtml = `
            <div class="modal fade" id="modalConfirmLaporan" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="ti ti-file-check me-2"></i>Generate Laporan
                            </h5>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="mb-3">
                                <i class="ti ti-file-download" style="font-size: 4rem; color: #28a745;"></i>
                            </div>
                            <h5 class="mb-2">Generate Laporan Perawatan?</h5>
                            <p class="text-muted mb-0">
                                Laporan akan dibuat untuk periode <strong>{{ $periodeKey }}</strong><br>
                                dengan total <strong>{{ $statusPeriode->total_checklist }}</strong> kegiatan yang telah selesai.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i> Batal
                            </button>
                            <button type="button" class="btn btn-success" id="btnConfirmGenerate">
                                <i class="ti ti-check me-1"></i> Ya, Generate Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmLaporan'));
        modal.show();
        
        $('#modalConfirmLaporan').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    });
    
    // Handle confirm generate
    $(document).on('click', '#btnConfirmGenerate', function() {
        const btn = $(this);
        const originalHtml = btn.html();

        $.ajax({
            url: '{{ route("perawatan.laporan.generate") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipe_periode: tipe
            },
            beforeSend: function() {
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Generating...');
            },
            success: function(response) {
                if (response.success) {
                    $('#modalConfirmLaporan').modal('hide');
                    showToast('success', response.message);
                    
                    // Open download link
                    if (response.download_url) {
                        setTimeout(() => {
                            window.open(response.download_url, '_blank');
                        }, 500);
                    }
                    
                    setTimeout(() => {
                        window.location.href = '{{ route("perawatan.laporan.index") }}';
                    }, 1500);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Gagal generate laporan!';
                showToast('error', message);
                btn.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // Modern Toast Notification
    function showToast(type, message) {
        const icons = {
            success: '<i class="ti ti-circle-check fs-4"></i>',
            error: '<i class="ti ti-circle-x fs-4"></i>',
            info: '<i class="ti ti-info-circle fs-4"></i>',
            warning: '<i class="ti ti-alert-triangle fs-4"></i>'
        };
        
        const colors = {
            success: 'success',
            error: 'danger',
            info: 'info',
            warning: 'warning'
        };
        
        const bgColors = {
            success: '#28a745',
            error: '#dc3545',
            info: '#17a2b8',
            warning: '#ffc107'
        };
        
        const toastId = 'toast-' + Date.now();
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white border-0 position-fixed top-0 end-0 m-3" 
                 role="alert" aria-live="assertive" aria-atomic="true" 
                 style="z-index: 9999; min-width: 320px; background: ${bgColors[type]}; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <span class="me-2">${icons[type]}</span>
                        <span>${message}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        $('body').append(toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3500
        });
        
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', function () {
            toastElement.remove();
        });
    }
});
</script>
@endpush
