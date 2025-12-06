<div class="modal-body">
    <form id="formPeminjamanUnit" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="inventaris_id" value="{{ $unit->inventaris_id }}">
        <input type="hidden" name="inventaris_detail_unit_id" value="{{ $unit->id }}">
        <input type="hidden" name="jumlah_pinjam" value="1">
        
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Unit:</strong> {{ $unit->kode_unit }}
                        </div>
                        <div class="col-md-6">
                            <strong>Barang:</strong> {{ $unit->inventaris->nama_barang }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label required">Tanggal Pinjam</label>
                <input type="date" class="form-control" name="tanggal_pinjam" required>
            </div>
            <div class="col-md-6">
                <label class="form-label required">Estimasi Kembali</label>
                <input type="date" class="form-control" name="tanggal_kembali_rencana" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label required">Nama Peminjam</label>
            <input type="text" class="form-control" name="nama_peminjam" placeholder="Masukkan nama peminjam" required>
        </div>

        <div class="mb-3">
            <label class="form-label required">Keperluan</label>
            <textarea class="form-control" name="keperluan" rows="3" placeholder="Jelaskan keperluan peminjaman..." required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi Tujuan</label>
            <input type="text" class="form-control" name="lokasi_tujuan" placeholder="Lokasi barang akan dibawa">
        </div>

        <div class="mb-3">
            <label class="form-label required">Tanda Tangan Peminjam</label>
            <div class="border rounded" style="background: #f8f9fa;">
                <canvas id="canvasPeminjamanUnit" width="460" height="200" style="cursor: crosshair; touch-action: none;"></canvas>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <button type="button" class="btn btn-sm btn-danger" id="btnClearSignPinjam">
                    <i class="ti ti-eraser"></i> Hapus Tanda Tangan
                </button>
                <small class="text-muted">Tanda tangan di area kotak</small>
            </div>
            <input type="hidden" name="ttd_peminjam" id="ttd_peminjam_unit">
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea class="form-control" name="catatan_peminjaman" rows="2" placeholder="Catatan tambahan..."></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="ti ti-x"></i> Batal
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-check"></i> Simpan Peminjaman
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Initialize Signature Pad
    const canvas = document.getElementById('canvasPeminjamanUnit');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    function startDrawing(e) {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        const x = (e.clientX || e.touches[0].clientX) - rect.left;
        const y = (e.clientY || e.touches[0].clientY) - rect.top;
        [lastX, lastY] = [x, y];
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        
        const rect = canvas.getBoundingClientRect();
        const x = (e.clientX || e.touches[0].clientX) - rect.left;
        const y = (e.clientY || e.touches[0].clientY) - rect.top;

        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(x, y);
        ctx.stroke();

        [lastX, lastY] = [x, y];
    }

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            // Save signature to hidden input
            const signatureData = canvas.toDataURL('image/png');
            $('#ttd_peminjam_unit').val(signatureData);
        }
    }

    // Mouse events
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Touch events for mobile
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    // Clear signature
    $('#btnClearSignPinjam').click(function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        $('#ttd_peminjam_unit').val('');
    });

    // Form submission
    $('#formPeminjamanUnit').submit(function(e) {
        e.preventDefault();
        
        // Validate signature
        if (!$('#ttd_peminjam_unit').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanda Tangan Diperlukan',
                text: 'Silakan buat tanda tangan terlebih dahulu'
            });
            return;
        }

        const formData = new FormData(this);
        
        $.ajax({
            url: '/peminjaman-inventaris',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Peminjaman berhasil disimpan',
                    timer: 2000
                }).then(() => {
                    $('#modalPeminjamanUnit').modal('hide');
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMsg
                });
            }
        });
    });
});
</script>
