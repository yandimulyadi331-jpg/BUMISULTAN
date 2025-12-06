<form id="formEditUnit" action="{{ route('inventaris.units.update', [$inventaris->id, $unit->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="alert alert-info">
        <strong>Kode Unit:</strong> {{ $unit->kode_unit }}
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                <select class="form-select" name="kondisi" required>
                    <option value="baik" {{ $unit->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ $unit->kondisi == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ $unit->kondisi == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" name="status" required {{ $unit->status == 'dipinjam' ? 'disabled' : '' }}>
                    <option value="tersedia" {{ $unit->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ $unit->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="maintenance" {{ $unit->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="rusak" {{ $unit->status == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang" {{ $unit->status == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
                @if($unit->status == 'dipinjam')
                <small class="text-danger">Status tidak bisa diubah saat unit sedang dipinjam</small>
                @endif
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Lokasi Saat Ini</label>
                <input type="text" class="form-control" name="lokasi_saat_ini" value="{{ $unit->lokasi_saat_ini }}">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Nomor Seri/Serial Number</label>
                <input type="text" class="form-control" name="nomor_seri_unit" value="{{ $unit->nomor_seri_unit }}">
            </div>
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Terakhir Maintenance</label>
        <input type="date" class="form-control" name="terakhir_maintenance" value="{{ $unit->terakhir_maintenance ? $unit->terakhir_maintenance->format('Y-m-d') : '' }}">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Foto Unit</label>
        @if($unit->foto_unit)
        <div class="mb-2">
            <img src="{{ Storage::url($unit->foto_unit) }}" class="img-thumbnail" style="max-width: 200px;">
            <p class="text-muted small mt-1">Upload foto baru untuk mengganti</p>
        </div>
        @endif
        <input type="file" class="form-control" name="foto_unit" accept="image/*">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Catatan Kondisi</label>
        <textarea class="form-control" name="catatan_kondisi" rows="4">{{ $unit->catatan_kondisi }}</textarea>
    </div>
    
    @if($unit->status == 'dipinjam' && $unit->peminjamanAktif)
    <div class="alert alert-warning">
        <strong>Informasi Peminjaman Aktif:</strong><br>
        Dipinjam oleh: {{ $unit->dipinjam_oleh }}<br>
        Tanggal Pinjam: {{ $unit->tanggal_pinjam ? $unit->tanggal_pinjam->format('d M Y') : '-' }}<br>
        Kode Peminjaman: {{ $unit->peminjamanAktif->kode_peminjaman }}
    </div>
    @endif
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> Update Unit
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#formEditUnit').submit(function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Unit berhasil diupdate',
                    timer: 2000
                });
                $('#modalUnit').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error!', message, 'error');
                submitBtn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Update Unit');
            }
        });
    });
});
</script>
