<form action="{{ route('gedung.store') }}" id="formcreateGedung" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="alert alert-info mb-3">
        <i class="ti ti-info-circle me-2"></i>
        <strong>Kode Gedung akan digenerate otomatis</strong> (Format: GD01, GD02, ...)
    </div>
    <x-input-with-icon icon="ti ti-building" label="Nama Gedung" name="nama_gedung" />
    <div class="form-group mb-3">
        <select name="kode_cabang" id="kode_cabang_create" class="form-select">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $c)
                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
            @endforeach
        </select>
    </div>
    <x-textarea label="Alamat" name="alamat" />
    <x-input-with-icon icon="ti ti-stairs" label="Jumlah Lantai" name="jumlah_lantai" type="number" />
    
    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Gedung</label>
        <input type="file" name="foto" id="foto_gedung" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
        <div class="mt-2">
            <img id="preview_foto_gedung" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
        </div>
    </div>
    
    <x-textarea label="Keterangan" name="keterangan" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script>
    // Preview foto
    $("#foto_gedung").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_gedung').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    $("#formcreateGedung").submit(function(e) {
        var nama_gedung = $("#formcreateGedung").find("#nama_gedung").val();
        var jumlah_lantai = $("#formcreateGedung").find("#jumlah_lantai").val();

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
