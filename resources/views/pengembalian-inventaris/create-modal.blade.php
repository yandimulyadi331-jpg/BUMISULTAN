@php
    $peminjaman = $peminjamanAktif->first();
@endphp

@if($peminjaman)
<form action="{{ route('pengembalian-inventaris.store') }}" method="POST" enctype="multipart/form-data" id="formPengembalianCreate">
    @csrf
    
    <input type="hidden" name="peminjaman_inventaris_id" value="{{ $peminjaman->id }}">
    
    <div class="alert alert-info mb-3">
        <strong>Informasi Peminjaman:</strong><br>
        Kode: {{ $peminjaman->kode_peminjaman }}<br>
        Peminjam: {{ $peminjaman->nama_peminjam }}<br>
        Tanggal Pinjam: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}<br>
        Jumlah: {{ $peminjaman->jumlah_pinjam }} unit
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pengembalian" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Kondisi Barang <span class="text-danger">*</span></label>
                <select name="kondisi_kembali" class="form-select" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Foto Barang Saat Dikembalikan</label>
        <input type="file" name="foto_pengembalian" class="form-control" accept="image/*">
        <small class="text-muted">Upload foto kondisi barang saat dikembalikan</small>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Catatan Pengembalian</label>
        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan kondisi atau kerusakan barang">{{ old('catatan') }}</textarea>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Denda (Rp)</label>
        <input type="number" name="denda" class="form-control" value="0" min="0" step="1000" placeholder="Masukkan denda jika ada">
        <small class="text-muted">Kosongkan jika tidak ada denda</small>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tanda Tangan Peminjam <span class="text-danger">*</span></label>
                <div style="border: 2px solid #dee2e6; border-radius: 8px; background: white;">
                    <canvas id="signaturePadPeminjam" style="width:100%; height:150px; cursor:crosshair; display:block;"></canvas>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" id="clearSignaturePeminjam">
                    <i class="ti ti-eraser"></i> Hapus TTD
                </button>
                <input type="hidden" name="ttd_peminjam" id="ttdPeminjam" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tanda Tangan Petugas</label>
                <div style="border: 2px solid #dee2e6; border-radius: 8px; background: white;">
                    <canvas id="signaturePadPetugas" style="width:100%; height:150px; cursor:crosshair; display:block;"></canvas>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" id="clearSignaturePetugas">
                    <i class="ti ti-eraser"></i> Hapus TTD
                </button>
                <input type="hidden" name="ttd_petugas" id="ttdPetugas">
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success" id="btnSubmitPengembalian">
            <i class="ti ti-check me-1"></i>Kembalikan Barang
        </button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Pengembalian Modal: Initializing...');
    
    // Wait for modal to be fully shown
    setTimeout(function() {
        const canvasPeminjam = document.getElementById('signaturePadPeminjam');
        const canvasPetugas = document.getElementById('signaturePadPetugas');
        
        console.log('Canvas Peminjam:', canvasPeminjam);
        console.log('Canvas Petugas:', canvasPetugas);
        
        if (canvasPeminjam && canvasPetugas) {
            function resizeCanvas(canvas) {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
            }
            
            // Resize canvas first
            resizeCanvas(canvasPeminjam);
            resizeCanvas(canvasPetugas);
            
            // Then initialize SignaturePad
            window.signaturePadPeminjam = new SignaturePad(canvasPeminjam, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 0.5,
                maxWidth: 2.5
            });
            
            window.signaturePadPetugas = new SignaturePad(canvasPetugas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 0.5,
                maxWidth: 2.5
            });
            
            console.log('Signature pads initialized successfully');
            console.log('SignaturePad Peminjam:', window.signaturePadPeminjam);
            console.log('SignaturePad Petugas:', window.signaturePadPetugas);

            $('#clearSignaturePeminjam').click(function() {
                console.log('Clearing signature peminjam');
                window.signaturePadPeminjam.clear();
                $('#ttdPeminjam').val('');
            });

            $('#clearSignaturePetugas').click(function() {
                console.log('Clearing signature petugas');
                window.signaturePadPetugas.clear();
                $('#ttdPetugas').val('');
            });
            
            // Re-resize on window resize
            $(window).on('resize', function() {
                resizeCanvas(canvasPeminjam);
                resizeCanvas(canvasPetugas);
            });
        } else {
            console.error('Canvas elements not found!');
        }
    }, 300);
});
</script>
@else
<div class="alert alert-warning">
    <i class="ti ti-alert-circle me-2"></i>
    Tidak ada peminjaman aktif untuk barang ini.
</div>
<div class="d-flex justify-content-end">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
@endif
