<!-- Modal Service Kendaraan -->
<div class="modal fade" id="modalService" tabindex="-1" role="dialog" aria-labelledby="modalServiceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); color: white;">
                <h5 class="modal-title" id="modalServiceLabel">
                    <ion-icon name="construct-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Request Service Kendaraan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formService" onsubmit="submitService(event)">
                <div class="modal-body">
                    <input type="hidden" id="serviceKendaraanId" name="kendaraan_id">
                    
                    <div class="alert alert-warning">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        Request service akan diproses oleh admin untuk penjadwalan.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Service <span class="text-danger">*</span></label>
                        <select class="form-select" name="jenis_service" required>
                            <option value="">Pilih Jenis Service</option>
                            <option value="Service Rutin">Service Rutin</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Ganti Oli">Ganti Oli</option>
                            <option value="Body Repair">Body Repair</option>
                            <option value="Ganti Ban">Ganti Ban</option>
                            <option value="Tune Up">Tune Up</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                        <select class="form-select" name="prioritas" required>
                            <option value="">Pilih Prioritas</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Masalah / Kebutuhan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="deskripsi" rows="4" 
                                  placeholder="Jelaskan kondisi atau kebutuhan service..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estimasi Biaya</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="estimasi_biaya" 
                                   placeholder="0" min="0" step="1000">
                        </div>
                        <small class="text-muted">Kosongkan jika belum tahu estimasi biaya</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Bengkel / Tempat Service</label>
                        <input type="text" class="form-control" name="bengkel" 
                               placeholder="Contoh: Bengkel Auto 2000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Foto (Opsional)</label>
                        <input type="file" class="form-control" name="foto[]" multiple accept="image/*">
                        <small class="text-muted">Foto kondisi kendaraan atau bagian yang bermasalah</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitService" 
                            style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); border: none;">
                        <ion-icon name="checkmark-outline"></ion-icon> Ajukan Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitService(event) {
    event.preventDefault();
    
    const form = document.getElementById('formService');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSubmitService');
    
    // Disable button
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
    
    $.ajax({
        url: '{{ route("kendaraan.karyawan.submit.service") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#modalService').modal('hide');
            form.reset();
            
            showToast(response.message, 'success');
            
            // Reload page after 1 second
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        },
        error: function(xhr) {
            showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Ajukan Service';
        }
    });
}
</script>
