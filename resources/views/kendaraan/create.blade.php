<form action="{{ route('kendaraan.store') }}" method="POST" id="formcreateKendaraan" enctype="multipart/form-data">
    @csrf
    
    <div class="alert alert-info mb-3">
        <i class="ti ti-info-circle me-2"></i>
        <strong>Kode Kendaraan akan digenerate otomatis</strong> berdasarkan jenis:
        <ul class="mb-0 mt-2">
            <li>Mobil: MB01, MB02, MB03, ...</li>
            <li>Motor: MT01, MT02, MT03, ...</li>
            <li>Truk: TK01, TK02, TK03, ...</li>
            <li>Bus: BS01, BS02, BS03, ...</li>
            <li>Lainnya: LN01, LN02, LN03, ...</li>
        </ul>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-car" label="Nama Kendaraan" name="nama_kendaraan" />
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                <select name="jenis_kendaraan" id="jenis_kendaraan" class="form-select">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Mobil">Mobil (MB)</option>
                    <option value="Motor">Motor (MT)</option>
                    <option value="Truk">Truk (TK)</option>
                    <option value="Bus">Bus (BS)</option>
                    <option value="Lainnya">Lainnya (LN)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-license" label="No. Polisi" name="no_polisi" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-brand-tabler" label="Merk" name="merk" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-car-suv" label="Model" name="model" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <x-input-with-icon icon="ti ti-calendar" label="Tahun" name="tahun" type="number" min="1900" max="2099" />
        </div>
        <div class="col-md-4">
            <x-input-with-icon icon="ti ti-palette" label="Warna" name="warna" />
        </div>
        <div class="col-md-4">
            <x-input-with-icon icon="ti ti-users" label="Kapasitas Penumpang" name="kapasitas_penumpang" type="number" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-id" label="No. Rangka" name="no_rangka" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-engine" label="No. Mesin" name="no_mesin" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label"><i class="ti ti-gas-station me-1"></i> Jenis BBM</label>
                <select name="jenis_bbm" class="form-select">
                    <option value="">-- Pilih BBM --</option>
                    <option value="Bensin">Bensin</option>
                    <option value="Solar">Solar</option>
                    <option value="Listrik">Listrik</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label"><i class="ti ti-building me-1"></i> Cabang</label>
                <select name="kode_cabang" class="form-select">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-calendar-event" label="STNK Berlaku s/d" name="stnk_berlaku" type="date" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-calendar-dollar" label="Pajak Berlaku s/d" name="pajak_berlaku" type="date" />
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Kendaraan</label>
        <input type="file" name="foto" id="foto_kendaraan" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB.</small>
        <div class="mt-2">
            <img id="preview_foto_kendaraan" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
        </div>
    </div>

    <x-textarea label="Keterangan" name="keterangan" />

    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <i class="ti ti-device-floppy me-1"></i> Simpan
        </button>
    </div>
</form>

<script>
    // Preview foto
    $("#foto_kendaraan").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_kendaraan').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#preview_foto_kendaraan').hide();
        }
    });

    // Hapus validasi JavaScript - biarkan Laravel yang handle
</script>
