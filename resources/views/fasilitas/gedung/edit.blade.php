<form action="{{ route('gedung.update', Crypt::encrypt($gedung->id)) }}" id="formeditGedung" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Gedung" name="kode_gedung" value="{{ $gedung->kode_gedung }}" readonly />
    <x-input-with-icon icon="ti ti-building" label="Nama Gedung" name="nama_gedung" value="{{ $gedung->nama_gedung }}" />
    <div class="form-group mb-3">
        <select name="kode_cabang" id="kode_cabang_edit" class="form-select">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $c)
                <option value="{{ $c->kode_cabang }}" {{ $gedung->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                    {{ $c->nama_cabang }}
                </option>
            @endforeach
        </select>
    </div>
    <x-textarea label="Alamat" name="alamat" value="{{ $gedung->alamat }}" />
    <x-input-with-icon icon="ti ti-stairs" label="Jumlah Lantai" name="jumlah_lantai" type="number" value="{{ $gedung->jumlah_lantai }}" />
    
    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Gedung</label>
        @if($gedung->foto)
            <div class="mb-2">
                <img src="{{ asset('storage/gedung/' . $gedung->foto) }}" class="img-thumbnail" style="max-width: 200px;">
                <p class="text-muted small mb-0">Foto saat ini</p>
            </div>
        @endif
        <input type="file" name="foto" id="foto_gedung_edit" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
        <div class="mt-2">
            <img id="preview_foto_gedung_edit" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
        </div>
    </div>
    
    <x-textarea label="Keterangan" name="keterangan" value="{{ $gedung->keterangan }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Update
        </button>
    </div>
</form>

<script>
    // Preview foto
    $("#foto_gedung_edit").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_gedung_edit').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    $("#formeditGedung").submit(function(e) {
        var nama_gedung = $("#formeditGedung").find("#nama_gedung").val();
        var jumlah_lantai = $("#formeditGedung").find("#jumlah_lantai").val();

        if (nama_gedung == "") {
            Swal.fire({
                title: 'Oops!',
                text: 'Nama Gedung Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#nama_gedung").focus();
            });
            return false;
        } else if (jumlah_lantai == "") {
            Swal.fire({
                title: 'Oops!',
                text: 'Jumlah Lantai Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#jumlah_lantai").focus();
            });
            return false;
        }
    });
</script>
