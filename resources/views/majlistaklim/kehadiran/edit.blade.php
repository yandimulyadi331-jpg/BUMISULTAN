@extends('layouts.app')
@section('titlepage', 'Edit Rekap Kehadiran')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                @include('majlistaklim.partials.navigation')
                <h2 class="page-title">Edit Rekap Kehadiran</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <form id="formEditRekapKehadiran" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Rekap Kehadiran</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Tanggal Pertemuan</label>
                                        <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ $rekap->tanggal->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Jumlah Jamaah Hadir</label>
                                        <input type="number" class="form-control" name="jumlah_hadir" id="jumlah_hadir" value="{{ $rekap->jumlah_hadir }}" min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Total Jamaah Terdaftar</label>
                                        <input type="number" class="form-control" name="total_jamaah" id="total_jamaah" value="{{ $rekap->total_jamaah ?? $totalJamaah }}" min="1">
                                        <small class="form-hint">Saat ini ada <strong>{{ $totalJamaah }} jamaah</strong> terdaftar</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Persentase Kehadiran</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="persentase_display" value="{{ $rekap->persentase }}" readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="3">{{ $rekap->keterangan }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="{{ route('majlistaklim.kehadiran.index') }}" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <i class="ti ti-device-floppy me-1"></i> Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
$(document).ready(function() {
    // Auto-calculate persentase
    function hitungPersentase() {
        const jumlahHadir = parseInt($('#jumlah_hadir').val()) || 0;
        const totalJamaah = parseInt($('#total_jamaah').val()) || 1;
        
        if (totalJamaah > 0) {
            const persentase = ((jumlahHadir / totalJamaah) * 100).toFixed(2);
            $('#persentase_display').val(persentase);
        }
    }

    $('#jumlah_hadir, #total_jamaah').on('input', hitungPersentase);

    // Form submit
    $('#formEditRekapKehadiran').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('majlistaklim.kehadiran.update', $rekap->id) }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "{{ route('majlistaklim.kehadiran.index') }}";
                });
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan saat update data';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });
});
</script>
@endpush
