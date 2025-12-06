@extends('layouts.app')
@section('titlepage', 'Tambah Rekap Kehadiran')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                @include('majlistaklim.partials.navigation')
                <h2 class="page-title">Tambah Rekap Kehadiran</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <form id="formRekapKehadiran" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Input Rekap Kehadiran</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Tanggal Pertemuan</label>
                                        <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Jumlah Jamaah Hadir</label>
                                        <input type="number" class="form-control" name="jumlah_hadir" id="jumlah_hadir" min="0" required>
                                        <small class="form-hint">Masukkan jumlah jamaah yang hadir pada pertemuan ini</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Total Jamaah Terdaftar</label>
                                        <input type="number" class="form-control" name="total_jamaah" id="total_jamaah" value="{{ $totalJamaah }}" min="1">
                                        <small class="form-hint">Saat ini ada <strong>{{ $totalJamaah }} jamaah</strong> terdaftar</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Persentase Kehadiran</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="persentase_display" readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <small class="form-hint">Otomatis dihitung berdasarkan jumlah hadir</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="3" placeholder="Contoh: Pengajian rutin mingguan, Kajian kitab kuning, dll"></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="{{ route('majlistaklim.kehadiran.index') }}" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <i class="ti ti-device-floppy me-1"></i> Simpan
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

    // Set tanggal hari ini
    $('#tanggal').val(new Date().toISOString().split('T')[0]);

    // Form submit
    $('#formRekapKehadiran').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('majlistaklim.kehadiran.store') }}",
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
                let errorMsg = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });

    // Initial calculation
    hitungPersentase();
});
</script>
@endpush
