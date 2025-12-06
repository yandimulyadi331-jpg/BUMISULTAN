<form id="formTambahUnit" action="{{ route('inventaris.units.store', $inventaris->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Jumlah Unit yang Ditambahkan <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="jumlah" min="1" max="100" value="1" required>
                <small class="text-muted">Maksimal 100 unit per input</small>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                <select class="form-select" name="kondisi" required>
                    <option value="baik" selected>Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Lokasi Saat Ini</label>
                <input type="text" class="form-control" name="lokasi_saat_ini" value="{{ $inventaris->lokasi_penyimpanan }}">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Tanggal Perolehan</label>
                <input type="date" class="form-control" name="tanggal_perolehan" value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Harga Perolehan (per unit)</label>
                <input type="number" class="form-control" name="harga_perolehan" step="0.01" value="{{ $inventaris->harga_perolehan }}">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Nomor Seri/Serial Number</label>
                <input type="text" class="form-control" name="nomor_seri_unit" placeholder="Opsional">
            </div>
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Foto Unit (opsional)</label>
        <input type="file" class="form-control" name="foto_unit" accept="image/*">
        <small class="text-muted">Foto akan digunakan untuk semua unit yang ditambahkan</small>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Catatan Kondisi</label>
        <textarea class="form-control" name="catatan_kondisi" rows="3" placeholder="Opsional - Catatan tambahan tentang kondisi unit"></textarea>
    </div>
    
    <hr>
    
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="createBatch" name="create_batch" value="1">
            <label class="form-check-label" for="createBatch">
                Buat Batch/Grouping (opsional)
            </label>
        </div>
        <small class="text-muted">Centang ini jika ingin mengelompokkan unit berdasarkan batch perolehan</small>
    </div>
    
    <div id="batchFields" style="display: none;">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="mb-3">Informasi Batch</h6>
                
                <div class="mb-3">
                    <label class="form-label">Kode Batch (opsional)</label>
                    <input type="text" class="form-control" name="batch_code" placeholder="Contoh: BATCH-001">
                    <small class="text-muted">Kosongkan untuk auto-generate</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <input type="text" class="form-control" name="supplier" placeholder="Nama supplier/vendor">
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> Simpan Unit
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Toggle batch fields
    $('#createBatch').change(function() {
        if ($(this).is(':checked')) {
            $('#batchFields').slideDown();
        } else {
            $('#batchFields').slideUp();
        }
    });
    
    // Submit form via AJAX
    $('#formTambahUnit').submit(function(e) {
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
                    text: response.message,
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
                submitBtn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Simpan Unit');
            }
        });
    });
});
</script>
