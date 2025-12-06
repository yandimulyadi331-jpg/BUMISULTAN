<form action="{{ route('ruangan.store', Crypt::encrypt($gedung->id)) }}" id="formcreateRuangan" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="alert alert-info mb-3">
        <i class="ti ti-info-circle me-2"></i>
        <strong>Kode Ruangan akan digenerate otomatis</strong> (Format: {{ $gedung->kode_gedung }}-RU01, {{ $gedung->kode_gedung }}-RU02, ...)
    </div>
    <x-input-with-icon icon="ti ti-door" label="Nama Ruangan" name="nama_ruangan" />
    <x-input-with-icon icon="ti ti-stairs" label="Lantai" name="lantai" />
    <x-input-with-icon icon="ti ti-dimensions" label="Luas (m²)" name="luas" type="number" step="0.01" />
    <x-input-with-icon icon="ti ti-users" label="Kapasitas" name="kapasitas" type="number" />
    
    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Ruangan</label>
        <input type="file" name="foto" id="foto_ruangan" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
        <div class="mt-2">
            <img id="preview_foto_ruangan" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
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
    $("#foto_ruangan").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_ruangan').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    $("#formcreateRuangan").submit(function(e) {
        var nama_ruangan = $("#formcreateRuangan").find("#nama_ruangan").val();

        if (nama_ruangan == "") {
            Swal.fire({
                title: 'Oops!',
                text: 'Nama Ruangan Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#nama_ruangan").focus();
            });
            return false;
        }
    });
</script>
