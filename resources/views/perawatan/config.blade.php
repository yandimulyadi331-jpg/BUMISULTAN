@extends('layouts.app')

@section('title', 'Konfigurasi Checklist Perawatan')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold text-primary">
                                <i class="ti ti-settings fs-3 me-2"></i>Konfigurasi Checklist Perawatan
                            </h4>
                            <p class="text-muted mb-0 small">
                                Kelola pengaturan aktivasi dan kewajiban checklist per periode
                            </p>
                        </div>
                        <a href="{{ route('perawatan.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-start" role="alert">
                <i class="ti ti-info-circle fs-4 me-3 mt-1"></i>
                <div>
                    <strong>Cara Kerja:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        <li><strong>Nonaktif:</strong> Checklist tidak muncul, karyawan bisa checkout tanpa checklist</li>
                        <li><strong>Aktif & Opsional:</strong> Checklist muncul, tapi karyawan boleh checkout tanpa menyelesaikan</li>
                        <li><strong>Aktif & Wajib:</strong> Checklist harus diselesaikan 100% sebelum karyawan bisa checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Config Cards -->
    <div class="row g-4">
        @foreach ($configs as $config)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white fw-bold">
                            <i class="ti ti-calendar-event me-2"></i>
                            Checklist {{ strtoupper($config->tipe_periode) }}
                        </h5>
                        <span class="badge {{ $config->badge_class }} status-badge-{{ $config->tipe_periode }}">
                            {{ $config->status_text }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <form class="config-form" data-tipe="{{ $config->tipe_periode }}">
                        <!-- Toggle Aktif/Nonaktif -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-semibold">
                                    <i class="ti ti-power me-1 text-primary"></i>Status Checklist
                                </label>
                                <div class="form-check form-switch" style="font-size: 1.5rem;">
                                    <input class="form-check-input toggle-enabled" 
                                           type="checkbox" 
                                           role="switch" 
                                           id="enabled_{{ $config->tipe_periode }}"
                                           {{ $config->is_enabled ? 'checked' : '' }}>
                                </div>
                            </div>
                            <p class="text-muted small mb-0">
                                <i class="ti ti-info-circle me-1"></i>
                                <span class="enabled-text-{{ $config->tipe_periode }}">
                                    {{ $config->is_enabled ? 'Checklist aktif dan akan muncul untuk karyawan' : 'Checklist nonaktif, karyawan bisa checkout langsung' }}
                                </span>
                            </p>
                        </div>

                        <!-- Toggle Wajib/Opsional (only visible if enabled) -->
                        <div class="mb-4 mandatory-section-{{ $config->tipe_periode }}" style="display: {{ $config->is_enabled ? 'block' : 'none' }};">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-semibold">
                                    <i class="ti ti-alert-circle me-1 text-warning"></i>Kewajiban
                                </label>
                                <div class="form-check form-switch" style="font-size: 1.5rem;">
                                    <input class="form-check-input toggle-mandatory" 
                                           type="checkbox" 
                                           role="switch" 
                                           id="mandatory_{{ $config->tipe_periode }}"
                                           {{ $config->is_mandatory ? 'checked' : '' }}>
                                </div>
                            </div>
                            <p class="text-muted small mb-0">
                                <i class="ti ti-info-circle me-1"></i>
                                <span class="mandatory-text-{{ $config->tipe_periode }}">
                                    {{ $config->is_mandatory ? 'Checklist WAJIB diselesaikan sebelum checkout' : 'Checklist OPSIONAL, karyawan boleh skip' }}
                                </span>
                            </p>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="ti ti-message-circle me-1 text-success"></i>Keterangan untuk Karyawan
                            </label>
                            <textarea class="form-control input-keterangan" 
                                      rows="3" 
                                      placeholder="Tulis keterangan atau instruksi untuk karyawan..."
                                      maxlength="500">{{ $config->keterangan }}</textarea>
                            <div class="text-end text-muted small mt-1">
                                <span class="char-count-{{ $config->tipe_periode }}">{{ strlen($config->keterangan) }}</span>/500
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-save">
                                <i class="ti ti-device-floppy me-2"></i>Simpan Konfigurasi
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="ti ti-clock me-1"></i>
                        Terakhir diubah: {{ $config->updated_at->format('d M Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.bg-gradient {
    position: relative;
    overflow: hidden;
}

.bg-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    transform: skewY(-5deg);
    transform-origin: top left;
}

.form-check-input {
    cursor: pointer;
    width: 3rem;
    height: 1.5rem;
}

.form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 600;
    padding: 12px;
    transition: all 0.3s;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.input-keterangan {
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    transition: border-color 0.3s;
}

.input-keterangan:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle enabled/disabled handler
    $('.toggle-enabled').on('change', function() {
        const tipe = $(this).closest('.config-form').data('tipe');
        const isEnabled = $(this).is(':checked');
        
        // Update UI text
        if (isEnabled) {
            $(`.enabled-text-${tipe}`).text('Checklist aktif dan akan muncul untuk karyawan');
            $(`.mandatory-section-${tipe}`).slideDown();
        } else {
            $(`.enabled-text-${tipe}`).text('Checklist nonaktif, karyawan bisa checkout langsung');
            $(`.mandatory-section-${tipe}`).slideUp();
            // Auto uncheck mandatory if disabled
            $(`#mandatory_${tipe}`).prop('checked', false);
        }
    });

    // Toggle mandatory/optional handler
    $('.toggle-mandatory').on('change', function() {
        const tipe = $(this).closest('.config-form').data('tipe');
        const isMandatory = $(this).is(':checked');
        
        // Update UI text
        if (isMandatory) {
            $(`.mandatory-text-${tipe}`).text('Checklist WAJIB diselesaikan sebelum checkout');
        } else {
            $(`.mandatory-text-${tipe}`).text('Checklist OPSIONAL, karyawan boleh skip');
        }
    });

    // Character counter for keterangan
    $('.input-keterangan').on('input', function() {
        const tipe = $(this).closest('.config-form').data('tipe');
        const length = $(this).val().length;
        $(`.char-count-${tipe}`).text(length);
    });

    // Form submit handler
    $('.config-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const tipe = form.data('tipe');
        const isEnabled = $(`#enabled_${tipe}`).is(':checked');
        const isMandatory = $(`#mandatory_${tipe}`).is(':checked');
        const keterangan = form.find('.input-keterangan').val();
        const btnSave = form.find('.btn-save');

        // Disable button
        btnSave.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

        $.ajax({
            url: '{{ route("perawatan.config.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipe_periode: tipe,
                is_enabled: isEnabled ? 1 : 0,
                is_mandatory: isMandatory ? 1 : 0,
                keterangan: keterangan
            },
            success: function(response) {
                // Update badge
                $(`.status-badge-${tipe}`).removeClass('bg-secondary bg-success bg-danger')
                    .addClass(response.data.badge_class)
                    .text(response.data.status_text);

                // Success alert
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan konfigurasi',
                });
            },
            complete: function() {
                // Enable button
                btnSave.prop('disabled', false).html('<i class="ti ti-device-floppy me-2"></i>Simpan Konfigurasi');
            }
        });
    });
});
</script>
@endsection
