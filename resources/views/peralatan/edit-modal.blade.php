<form action="{{ route('peralatan.update', $peralatan->id) }}" method="POST" enctype="multipart/form-data" id="formEditPeralatan">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Peralatan</label>
            <input type="text" class="form-control" 
                value="{{ $peralatan->kode_peralatan }}" readonly style="background-color: #e9ecef;">
            <small class="text-muted">Kode peralatan tidak dapat diubah</small>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Peralatan <span class="text-danger">*</span></label>
            <input type="text" name="nama_peralatan" class="form-control" 
                value="{{ $peralatan->nama_peralatan }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kategori <span class="text-danger">*</span></label>
            <select name="kategori" class="form-select" required>
                <option value="">Pilih Kategori</option>
                <option value="Alat Kebersihan" {{ $peralatan->kategori == 'Alat Kebersihan' ? 'selected' : '' }}>Alat Kebersihan</option>
                <option value="Alat Tulis Kantor" {{ $peralatan->kategori == 'Alat Tulis Kantor' ? 'selected' : '' }}>Alat Tulis Kantor</option>
                <option value="Elektronik" {{ $peralatan->kategori == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="Peralatan Dapur" {{ $peralatan->kategori == 'Peralatan Dapur' ? 'selected' : '' }}>Peralatan Dapur</option>
                <option value="Peralatan Olahraga" {{ $peralatan->kategori == 'Peralatan Olahraga' ? 'selected' : '' }}>Peralatan Olahraga</option>
                <option value="Peralatan Taman" {{ $peralatan->kategori == 'Peralatan Taman' ? 'selected' : '' }}>Peralatan Taman</option>
                <option value="Perkakas" {{ $peralatan->kategori == 'Perkakas' ? 'selected' : '' }}>Perkakas</option>
                <option value="Keamanan" {{ $peralatan->kategori == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                <option value="Lainnya" {{ $peralatan->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
            <select name="kondisi" class="form-select" required>
                <option value="baik" {{ $peralatan->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak ringan" {{ $peralatan->kondisi == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak berat" {{ $peralatan->kondisi == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
            <input type="number" name="stok_awal" class="form-control" 
                value="{{ $peralatan->stok_awal }}" min="0" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Satuan <span class="text-danger">*</span></label>
            <input type="text" name="satuan" class="form-control" 
                value="{{ $peralatan->satuan }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Stok Minimum</label>
            <input type="number" name="stok_minimum" class="form-control" 
                value="{{ $peralatan->stok_minimum }}" min="0">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Lokasi Penyimpanan</label>
            <input type="text" name="lokasi_penyimpanan" class="form-control" 
                value="{{ $peralatan->lokasi_penyimpanan }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Harga Satuan</label>
            <input type="number" name="harga_satuan" class="form-control" 
                value="{{ $peralatan->harga_satuan }}" min="0">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Pembelian</label>
            <input type="date" name="tanggal_pembelian" class="form-control" 
                value="{{ $peralatan->tanggal_pembelian ? $peralatan->tanggal_pembelian->format('Y-m-d') : '' }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" 
                value="{{ $peralatan->supplier }}">
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2">{{ $peralatan->deskripsi }}</textarea>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Foto Peralatan</label>
            @if($peralatan->foto)
                <div class="mb-2">
                    <img src="{{ Storage::url('peralatan/' . $peralatan->foto) }}" 
                         alt="{{ $peralatan->nama_peralatan }}" 
                         class="rounded" 
                         style="max-height: 150px;">
                </div>
            @endif
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control" rows="2">{{ $peralatan->catatan }}</textarea>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-warning">
            <i class="ti ti-device-floppy me-1"></i> Update
        </button>
    </div>
</form>
