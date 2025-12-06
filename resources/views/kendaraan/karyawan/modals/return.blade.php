<!-- Modal Return Kendaraan -->
<div class="modal fade" id="modalReturn" tabindex="-1" role="dialog" aria-labelledby="modalReturnLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalReturnLabel">
                    <ion-icon name="arrow-undo-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Pengembalian Kendaraan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formReturn" onsubmit="submitReturn(event)">
                <div class="modal-body">
                    <input type="hidden" id="returnKendaraanId" name="kendaraan_id">
                    <input type="hidden" id="returnProsesId" name="proses_id">
                    <input type="hidden" id="returnJenisProses" name="jenis_proses">
                    
                    <div class="alert alert-info">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Pastikan semua data pengembalian diisi dengan benar.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_kembali" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Kembali <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="jam_kembali" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">KM Akhir <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="km_akhir" min="0" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">BBM Akhir</label>
                            <select class="form-select" name="bbm_akhir">
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
                        <label class="form-label">Kondisi Kendaraan <span class="text-danger">*</span></label>
                        <select class="form-select" name="kondisi_kendaraan" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Kondisi <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="foto_kondisi[]" multiple accept="image/*" required>
                        <small class="text-muted">Wajib upload foto kondisi kendaraan saat dikembalikan</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="btnSubmitReturn">
                        <ion-icon name="checkmark-outline"></ion-icon> Kembalikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitReturn(event) {
    event.preventDefault();
    
    const form = document.getElementById('formReturn');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSubmitReturn');
    const jenisProses = document.getElementById('returnJenisProses').value;
    
    // Add GPS location
    if (userLocation.latitude && userLocation.longitude) {
        formData.append('latitude', userLocation.latitude);
        formData.append('longitude', userLocation.longitude);
    }
    
    // Disable button
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
    
    const url = jenisProses === 'pinjam' 
        ? '{{ route("kendaraan.karyawan.submit.return.pinjam") }}'
        : '{{ route("kendaraan.karyawan.submit.return") }}';
    
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#modalReturn').modal('hide');
            form.reset();
            
            if (response.data?.terlambat) {
                showToast(`Kendaraan dikembalikan dengan keterlambatan ${response.data.hari_terlambat} hari`, 'warning');
            } else {
                showToast(response.message, 'success');
            }
            
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
            btn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Kembalikan';
        }
    });
}
</script>
