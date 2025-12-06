<form action="{{ route('inventaris.update', $inventaris->id) }}" method="POST" enctype="multipart/form-data" id="formInventarisEdit">
    @csrf
    @method('PUT')
    
    <div class="row mb-3">
        <div class="col-md-12 text-center">
            @if($inventaris->foto)
            <img src="{{ Storage::url($inventaris->foto) }}" alt="Current" class="img-thumbnail mb-2" style="max-height: 150px;">
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Kode Inventaris</label>
                <input type="text" class="form-control" value="{{ $inventaris->kode_inventaris }}" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Nama Inventaris <span class="text-danger">*</span></label>
                <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $inventaris->nama_barang) }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select" required>
                    @foreach($kategoris as $kat)
                        <option value="{{ strtolower($kat) }}" {{ old('kategori', $inventaris->kategori) == strtolower($kat) ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Cabang</label>
                <select name="cabang_id" class="form-select">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($cabangs as $c)
                        <option value="{{ $c->id }}" {{ old('cabang_id', $inventaris->cabang_id) == $c->id ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $inventaris->jumlah) }}" required min="1">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                <select name="satuan" class="form-select" required>
                    <option value="unit" {{ old('satuan', $inventaris->satuan) == 'unit' ? 'selected' : '' }}>Unit</option>
                    <option value="pcs" {{ old('satuan', $inventaris->satuan) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                    <option value="set" {{ old('satuan', $inventaris->satuan) == 'set' ? 'selected' : '' }}>Set</option>
                    <option value="buah" {{ old('satuan', $inventaris->satuan) == 'buah' ? 'selected' : '' }}>Buah</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi_penyimpanan" class="form-control" value="{{ old('lokasi_penyimpanan', $inventaris->lokasi_penyimpanan) }}" placeholder="Lokasi penyimpanan">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="tersedia" {{ old('status', $inventaris->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ old('status', $inventaris->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="rusak" {{ old('status', $inventaris->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="maintenance" {{ old('status', $inventaris->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="hilang" {{ old('status', $inventaris->status) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                <select name="kondisi" class="form-select" required>
                    <option value="baik" {{ old('kondisi', $inventaris->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ old('kondisi', $inventaris->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ old('kondisi', $inventaris->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $inventaris->deskripsi) }}</textarea>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Foto</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Catatan</label>
        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $inventaris->keterangan) }}</textarea>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>Update
        </button>
    </div>
</form>
