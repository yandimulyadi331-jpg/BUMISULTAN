<form action="{{ route('peralatan.store') }}" method="POST" enctype="multipart/form-data" id="formTambahPeralatan">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Peralatan</label>
            <input type="text" class="form-control" value="[Otomatis oleh sistem]" readonly style="background-color: #e9ecef;">
            <small class="text-muted">Kode akan digenerate otomatis saat menyimpan</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Peralatan <span class="text-danger">*</span></label>
            <input type="text" name="nama_peralatan" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kategori <span class="text-danger">*</span></label>
            <select name="kategori" class="form-select" required>
                <option value="">Pilih Kategori</option>
                <option value="Alat Kebersihan">Alat Kebersihan</option>
                <option value="Alat Tulis Kantor">Alat Tulis Kantor</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Peralatan Dapur">Peralatan Dapur</option>
                <option value="Peralatan Olahraga">Peralatan Olahraga</option>
                <option value="Peralatan Taman">Peralatan Taman</option>
                <option value="Perkakas">Perkakas</option>
                <option value="Keamanan">Keamanan</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
            <select name="kondisi" class="form-select" required>
                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
            <input type="number" name="stok_awal" class="form-control" min="0" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Satuan <span class="text-danger">*</span></label>
            <input type="text" name="satuan" class="form-control" placeholder="pcs, unit, set, dll" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Stok Minimum</label>
            <input type="number" name="stok_minimum" class="form-control" min="0" value="0">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Lokasi Penyimpanan</label>
            <input type="text" name="lokasi_penyimpanan" class="form-control" placeholder="Gudang, Kantor, dll">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Harga Satuan</label>
            <input type="number" name="harga_satuan" class="form-control" min="0" value="0">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Pembelian</label>
            <input type="date" name="tanggal_pembelian" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control">
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Foto Peralatan</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control" rows="2"></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Simpan
        </button>
    </div>
</form>
