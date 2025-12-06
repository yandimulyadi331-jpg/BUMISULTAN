<form action="{{ route('peminjaman-peralatan.pengembalian', $peminjamanPeralatan->id) }}" method="POST">
    @csrf
    <div class="alert alert-info">
        <strong>Informasi Peminjaman:</strong><br>
        Nomor: <strong>{{ $peminjamanPeralatan->nomor_peminjaman }}</strong><br>
        Peralatan: <strong>{{ $peminjamanPeralatan->peralatan->nama_peralatan }}</strong><br>
        Peminjam: <strong>{{ $peminjamanPeralatan->nama_peminjam }}</strong><br>
        Jumlah: <strong>{{ $peminjamanPeralatan->jumlah_dipinjam }} {{ $peminjamanPeralatan->peralatan->satuan }}</strong>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_kembali_aktual" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Jumlah Rusak</label>
            <input type="number" name="jumlah_rusak" class="form-control" value="0" min="0" max="{{ $peminjamanPeralatan->jumlah_dipinjam }}">
            <small class="text-muted">Jumlah barang yang rusak dari total {{ $peminjamanPeralatan->jumlah_dipinjam }}</small>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Kondisi Saat Dikembalikan <span class="text-danger">*</span></label>
            <textarea name="kondisi_saat_dikembalikan" class="form-control" rows="3" required placeholder="Jelaskan kondisi barang saat dikembalikan..."></textarea>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan Pengembalian</label>
            <textarea name="catatan_pengembalian" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">
            <i class="ti ti-check me-1"></i> Proses Pengembalian
        </button>
    </div>
</form>
