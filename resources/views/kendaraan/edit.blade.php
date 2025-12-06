<form action="{{ route('kendaraan.update', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formeditKendaraan" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-barcode" label="Kode Kendaraan" name="kode_kendaraan" value="{{ $kendaraan->kode_kendaraan }}" required />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-car" label="Nama Kendaraan" name="nama_kendaraan" value="{{ $kendaraan->nama_kendaraan }}" required />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                <select name="jenis_kendaraan" id="jenis_kendaraan_edit" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Mobil" {{ $kendaraan->jenis_kendaraan == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                    <option value="Motor" {{ $kendaraan->jenis_kendaraan == 'Motor' ? 'selected' : '' }}>Motor</option>
                    <option value="Truk" {{ $kendaraan->jenis_kendaraan == 'Truk' ? 'selected' : '' }}>Truk</option>
                    <option value="Bus" {{ $kendaraan->jenis_kendaraan == 'Bus' ? 'selected' : '' }}>Bus</option>
                    <option value="Lainnya" {{ $kendaraan->jenis_kendaraan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-license" label="No. Polisi" name="no_polisi" value="{{ $kendaraan->no_polisi }}" required />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-brand-tabler" label="Merk" name="merk" value="{{ $kendaraan->merk }}" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-car-suv" label="Model" name="model" value="{{ $kendaraan->model }}" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <x-input-with-icon icon="ti ti-calendar" label="Tahun" name="tahun" type="number" value="{{ $kendaraan->tahun }}" />
        </div>
        <div class="col-md-4">
            <x-input-with-icon icon="ti ti-palette" label="Warna" name="warna" value="{{ $kendaraan->warna }}" />
        </div>
        <div class="col-md-4">
            <x-input-with-icon icon="ti ti-users" label="Kapasitas" name="kapasitas_penumpang" type="number" value="{{ $kendaraan->kapasitas_penumpang }}" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-id" label="No. Rangka" name="no_rangka" value="{{ $kendaraan->no_rangka }}" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-engine" label="No. Mesin" name="no_mesin" value="{{ $kendaraan->no_mesin }}" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label"><i class="ti ti-gas-station me-1"></i> Jenis BBM</label>
                <select name="jenis_bbm" class="form-select">
                    <option value="">-- Pilih BBM --</option>
                    <option value="Bensin" {{ $kendaraan->jenis_bbm == 'Bensin' ? 'selected' : '' }}>Bensin</option>
                    <option value="Solar" {{ $kendaraan->jenis_bbm == 'Solar' ? 'selected' : '' }}>Solar</option>
                    <option value="Listrik" {{ $kendaraan->jenis_bbm == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                    <option value="Hybrid" {{ $kendaraan->jenis_bbm == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label"><i class="ti ti-building me-1"></i> Cabang</label>
                <select name="kode_cabang" class="form-select">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ $kendaraan->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-calendar-event" label="STNK Berlaku s/d" name="stnk_berlaku" type="date" 
                value="{{ $kendaraan->stnk_berlaku ? $kendaraan->stnk_berlaku->format('Y-m-d') : '' }}" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-calendar-dollar" label="Pajak Berlaku s/d" name="pajak_berlaku" type="date"
                value="{{ $kendaraan->pajak_berlaku ? $kendaraan->pajak_berlaku->format('Y-m-d') : '' }}" />
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Kendaraan</label>
        @if($kendaraan->foto)
            <div class="mb-2">
                <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="img-thumbnail" style="max-width: 200px;">
                <p class="text-muted small mb-0">Foto saat ini</p>
            </div>
        @endif
        <input type="file" name="foto" id="foto_kendaraan_edit" class="form-control" accept="image/*">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
        <div class="mt-2">
            <img id="preview_foto_kendaraan_edit" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
        </div>
    </div>

    <x-textarea label="Keterangan" name="keterangan" value="{{ $kendaraan->keterangan }}" />

    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <i class="ti ti-device-floppy me-1"></i> Update
        </button>
    </div>
</form>

<script>
    $("#foto_kendaraan_edit").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_kendaraan_edit').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#preview_foto_kendaraan_edit').hide();
        }
    });

    $("#formeditKendaraan").submit(function(e) {
        var kode_kendaraan = $(this).find("input[name='kode_kendaraan']").val();
        var nama_kendaraan = $(this).find("input[name='nama_kendaraan']").val();
        var jenis_kendaraan = $("#jenis_kendaraan_edit").val();
        var no_polisi = $(this).find("input[name='no_polisi']").val();

        if (kode_kendaraan == "") {
            Swal.fire('Oops!', 'Kode Kendaraan Harus Diisi!', 'warning');
            return false;
        } else if (nama_kendaraan == "" || nama_kendaraan == undefined) {
            Swal.fire('Oops!', 'Nama Kendaraan Harus Diisi!', 'warning');
            return false;
        } else if (jenis_kendaraan == "") {
            Swal.fire('Oops!', 'Jenis Kendaraan Harus Dipilih!', 'warning');
            return false;
        } else if (no_polisi == "") {
            Swal.fire('Oops!', 'No. Polisi Harus Diisi!', 'warning');
            return false;
        }
    });
</script>
