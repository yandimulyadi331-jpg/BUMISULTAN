<!-- Modal Kendaraan Keluar -->
<div class="modal fade" id="modalKeluar" tabindex="-1" role="dialog" aria-labelledby="modalKeluarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalKeluarLabel">
                    <ion-icon name="exit-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Kendaraan Keluar
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formKeluar" onsubmit="submitKeluar(event)">
                <div class="modal-body">
                    <input type="hidden" id="keluarKendaraanId" name="kendaraan_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_keluar" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Keluar <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="jam_keluar" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tujuan" placeholder="Contoh: Jakarta" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pengemudi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pengemudi" value="{{ auth()->user()->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP Pengemudi</label>
                        <input type="text" class="form-control" name="no_hp_pengemudi" placeholder="08xx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estimasi Kembali <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="estimasi_kembali" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">KM Awal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="km_awal" min="0" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">BBM Awal</label>
                            <select class="form-select" name="bbm_awal">
                                <option value="">Pilih</option>
                                <option value="Full">Full</option>
                                <option value="3/4">3/4</option>
                                <option value="1/2">1/2</option>
                                <option value="1/4">1/4</option>
                                <option value="Empty">Empty</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Dokumen (Opsional)</label>
                        <input type="file" class="form-control" name="dokumen[]" multiple accept=".pdf,.doc,.docx">
                        <small class="text-muted">Max 5MB per file</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Foto (Opsional)</label>
                        <input type="file" class="form-control" name="foto[]" multiple accept="image/*">
                        <small class="text-muted">Max 2MB per foto</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitKeluar">
                        <ion-icon name="checkmark-outline"></ion-icon> Submit Keluar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitKeluar(event) {
    event.preventDefault();
    
    const form = document.getElementById('formKeluar');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSubmitKeluar');
    
    // Add GPS location
    if (userLocation.latitude && userLocation.longitude) {
        formData.append('latitude', userLocation.latitude);
        formData.append('longitude', userLocation.longitude);
    }
    
    // Disable button
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
    
    $.ajax({
        url: '{{ route("kendaraan.karyawan.submit.keluar") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#modalKeluar').modal('hide');
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
            btn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Submit Keluar';
        }
    });
}
</script>
