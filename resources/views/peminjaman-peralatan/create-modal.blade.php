<form action="{{ route('peminjaman-peralatan.store') }}" method="POST" id="formPeminjaman">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Peralatan <span class="text-danger">*</span></label>
            <select name="peralatan_id" id="peralatan_id_modal" class="form-select" required>
                <option value="">Pilih Peralatan</option>
                @foreach($peralatan as $item)
                    <option value="{{ $item->id }}" data-stok="{{ $item->stok_tersedia }}" data-satuan="{{ $item->satuan }}">
                        {{ $item->nama_peralatan }} ({{ $item->kode_peralatan }}) - Stok: {{ $item->stok_tersedia }} {{ $item->satuan }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted" id="infoStokModal"></small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
            <input type="text" name="nama_peminjam" class="form-control" placeholder="Masukkan nama peminjam" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Jumlah Dipinjam <span class="text-danger">*</span></label>
            <input type="number" name="jumlah_dipinjam" id="jumlah_dipinjam_modal" class="form-control" value="1" min="1" required>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Keperluan <span class="text-danger">*</span></label>
            <input type="text" name="keperluan" class="form-control" placeholder="Contoh: Membersihkan ruangan" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_kembali_rencana" class="form-control" required>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Kondisi Saat Dipinjam</label>
            <textarea name="kondisi_saat_dipinjam" class="form-control" rows="2" placeholder="Kondisi barang saat dipinjam"></textarea>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control" rows="2"></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Simpan Peminjaman
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#peralatan_id_modal').change(function() {
        var stok = $(this).find(':selected').data('stok');
        var satuan = $(this).find(':selected').data('satuan');
        $('#infoStokModal').html('<i class="ti ti-info-circle"></i> Stok tersedia: <strong>' + stok + ' ' + satuan + '</strong>');
        $('#jumlah_dipinjam_modal').attr('max', stok);
    });
});
</script>
