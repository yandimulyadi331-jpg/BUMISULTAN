<form action="{{ route('inventaris.store') }}" method="POST" enctype="multipart/form-data" id="formInventarisCreate">
    @csrf
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Nama Inventaris <span class="text-danger">*</span></label>
                <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required placeholder="Nama barang inventaris">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ strtolower($kat) }}" {{ old('kategori') == strtolower($kat) ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Cabang</label>
                <select name="cabang_id" class="form-select">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($cabangs as $c)
                        <option value="{{ $c->id }}" {{ old('cabang_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Barang Master</label>
                <select name="barang_id" class="form-select">
                    <option value="">-- Pilih Barang (Opsional) --</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_barang }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Link ke master barang jika tersedia</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" required min="1">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                <select name="satuan" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>Unit</option>
                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                    <option value="set" {{ old('satuan') == 'set' ? 'selected' : '' }}>Set</option>
                    <option value="buah" {{ old('satuan') == 'buah' ? 'selected' : '' }}>Buah</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                <select name="kondisi" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Merk</label>
                <input type="text" name="merk" class="form-control" value="{{ old('merk') }}" placeholder="Merk barang">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tipe/Model</label>
                <input type="text" name="tipe_model" class="form-control" value="{{ old('tipe_model') }}" placeholder="Tipe atau model">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Nomor Seri</label>
                <input type="text" name="nomor_seri" class="form-control" value="{{ old('nomor_seri') }}" placeholder="Serial number">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi_penyimpanan" class="form-control" value="{{ old('lokasi_penyimpanan') }}" placeholder="Lokasi penyimpanan">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Harga Perolehan</label>
                <input type="number" name="harga_perolehan" class="form-control" value="{{ old('harga_perolehan') }}" placeholder="Harga perolehan" step="0.01">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tanggal Perolehan</label>
                <input type="date" name="tanggal_perolehan" class="form-control" value="{{ old('tanggal_perolehan') }}">
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi inventaris">{{ old('deskripsi') }}</textarea>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Foto</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>Simpan
        </button>
    </div>
</form>
