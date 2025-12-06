<!-- Modal Peminjaman Kendaraan -->
<div class="modal fade" id="modalPinjam" tabindex="-1" role="dialog" aria-labelledby="modalPinjamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title" id="modalPinjamLabel">
                    <ion-icon name="hand-left-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Peminjaman Kendaraan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPinjam" onsubmit="submitPinjam(event)">
                <div class="modal-body">
                    <input type="hidden" id="pinjamKendaraanId" name="kendaraan_id">
                    
                    <div class="alert alert-info alert-sm">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        <small>Pengajuan akan melalui proses verifikasi admin</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_pinjam" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_kembali" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan Penggunaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tujuan_penggunaan" 
                               placeholder="Contoh: Dinas ke Jakarta" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keperluan" rows="3" 
                                  placeholder="Jelaskan keperluan penggunaan kendaraan..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">No. HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_hp" 
                                   placeholder="08xx" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Jumlah Penumpang</label>
                            <input type="number" class="form-control" name="jumlah_penumpang" min="1" placeholder="Orang">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" name="keterangan" rows="2" 
                                  placeholder="Informasi tambahan (opsional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanda Tangan Digital</label>
                        <div class="border rounded p-2" style="background: white;">
                            <canvas id="signaturePad" width="450" height="150" 
                                    style="border: 1px dashed #ccc; width: 100%; touch-action: none;"></canvas>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature()">
                                <ion-icon name="trash-outline"></ion-icon> Hapus Tanda Tangan
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Foto (Opsional)</label>
                        <input type="file" class="form-control" name="foto[]" multiple accept="image/*">
                        <small class="text-muted">Max 2MB per foto</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white" id="btnSubmitPinjam" 
                            style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <ion-icon name="checkmark-outline"></ion-icon> Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<script>
let signaturePad;

// Initialize signature pad when modal is shown
$('#modalPinjam').on('shown.modal', function() {
    const canvas = document.getElementById('signaturePad');
    if (canvas) {
        signaturePad = new SignaturePad(canvas);
    }
});

function clearSignature() {
    if (signaturePad) {
        signaturePad.clear();
    }
}

function submitPinjam(event) {
    event.preventDefault();
    
    const form = document.getElementById('formPinjam');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSubmitPinjam');
    
    // Add signature if not empty
    if (signaturePad && !signaturePad.isEmpty()) {
        const signatureData = signaturePad.toDataURL();
        formData.append('signature', signatureData);
    }
    
    // Add GPS location
    if (userLocation.latitude && userLocation.longitude) {
        formData.append('latitude', userLocation.latitude);
        formData.append('longitude', userLocation.longitude);
    }
    
    // Disable button
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
    
    $.ajax({
        url: '{{ route("kendaraan.karyawan.submit.pinjam") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#modalPinjam').modal('hide');
            form.reset();
            if (signaturePad) signaturePad.clear();
            
            showToast(response.message || 'Pengajuan peminjaman berhasil dikirim', 'success');
            
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
            btn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Ajukan Peminjaman';
        }
    });
}
</script>
