<form action="{{ route('ruangan.update', [
    'gedung_id' => Crypt::encrypt($gedung->id),
    'id' => Crypt::encrypt($ruangan->id)
]) }}" id="formeditRuangan" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Ruangan" name="kode_ruangan" value="{{ $ruangan->kode_ruangan }}" readonly />
    <x-input-with-icon icon="ti ti-door" label="Nama Ruangan" name="nama_ruangan" value="{{ $ruangan->nama_ruangan }}" />
    <x-input-with-icon icon="ti ti-stairs" label="Lantai" name="lantai" value="{{ $ruangan->lantai }}" />
    <x-input-with-icon icon="ti ti-dimensions" label="Luas (m²)" name="luas" type="number" step="0.01" value="{{ $ruangan->luas }}" />
    <x-input-with-icon icon="ti ti-users" label="Kapasitas" name="kapasitas" type="number" value="{{ $ruangan->kapasitas }}" />
    
    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Ruangan</label>
        @if($ruangan->foto)
            <div class="mb-2">
                <img src="{{ asset('storage/ruangan/' . $ruangan->foto) }}" class="img-thumbnail" style="max-width: 200px;">
                <p class="text-muted small mb-0">Foto saat ini</p>
            </div>
        @endif
        <input type="file" name="foto" id="foto_ruangan_edit" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
        <div class="mt-2">
            <img id="preview_foto_ruangan_edit" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
        </div>
    </div>
    
    <x-textarea label="Keterangan" name="keterangan" value="{{ $ruangan->keterangan }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Update
        </button>
    </div>
</form>

<script>
    $("#foto_ruangan_edit").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_ruangan_edit').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    $("#formeditRuangan").submit(function(e) {
        var nama_ruangan = $("#formeditRuangan").find("#nama_ruangan").val();

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
