<form action="{{ route('barang.store', [
    'gedung_id' => Crypt::encrypt($gedung->id),
    'ruangan_id' => Crypt::encrypt($ruangan->id)
]) }}" id="formcreateBarang" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="alert alert-info mb-3">
        <i class="ti ti-info-circle me-2"></i>
        <strong>Kode Barang akan digenerate otomatis</strong> (Format: {{ $ruangan->kode_ruangan }}-BR01, {{ $ruangan->kode_ruangan }}-BR02, ...)
    </div>
    <x-input-with-icon icon="ti ti-package" label="Nama Barang" name="nama_barang" />
    <x-input-with-icon icon="ti ti-category" label="Kategori" name="kategori" />
    <x-input-with-icon icon="ti ti-brand-tabler" label="Merk" name="merk" />
    
    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-number" label="Jumlah" name="jumlah" type="number" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-box" label="Satuan" name="satuan" value="Unit" />
        </div>
    </div>
    
    <div class="form-group mb-3">
        <label class="form-label">Kondisi</label>
        <select name="kondisi" id="kondisi" class="form-select">
            <option value="Baik">Baik</option>
            <option value="Rusak Ringan">Rusak Ringan</option>
            <option value="Rusak Berat">Rusak Berat</option>
        </select>
    </div>
    
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Perolehan" name="tanggal_perolehan" type="date" />
    <x-input-with-icon icon="ti ti-currency-dollar" label="Harga Perolehan" name="harga_perolehan" type="number" step="0.01" />
    
    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Barang</label>
        <input type="file" name="foto" id="foto_barang" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
        <div class="mt-2">
            <img id="preview_foto_barang" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
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
    $("#foto_barang").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_barang').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    $("#formcreateBarang").submit(function(e) {
        var nama_barang = $("#formcreateBarang").find("#nama_barang").val();
        var jumlah = $("#formcreateBarang").find("#jumlah").val();
        var satuan = $("#formcreateBarang").find("#satuan").val();

        if (nama_barang == "") {
            Swal.fire({
                title: 'Oops!',
                text: 'Nama Barang Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#nama_barang").focus();
            });
            return false;
        } else if (jumlah == "" || jumlah <= 0) {
            Swal.fire({
                title: 'Oops!',
                text: 'Jumlah Harus Diisi dan Lebih dari 0 !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#jumlah").focus();
            });
            return false;
        } else if (satuan == "") {
            Swal.fire({
                title: 'Oops!',
                text: 'Satuan Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#satuan").focus();
            });
            return false;
        }
    });
</script>
