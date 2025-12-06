<form action="{{ route('barang.update', [
    'gedung_id' => Crypt::encrypt($gedung->id),
    'ruangan_id' => Crypt::encrypt($ruangan->id),
    'id' => Crypt::encrypt($barang->id)
]) }}" id="formeditBarang" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Barang" name="kode_barang" value="{{ $barang->kode_barang }}" readonly />
    <x-input-with-icon icon="ti ti-package" label="Nama Barang" name="nama_barang" value="{{ $barang->nama_barang }}" />
    <x-input-with-icon icon="ti ti-category" label="Kategori" name="kategori" value="{{ $barang->kategori }}" />
    <x-input-with-icon icon="ti ti-brand-tabler" label="Merk" name="merk" value="{{ $barang->merk }}" />
    
    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-number" label="Jumlah" name="jumlah" type="number" value="{{ $barang->jumlah }}" />
        </div>
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-box" label="Satuan" name="satuan" value="{{ $barang->satuan }}" />
        </div>
    </div>
    
    <div class="form-group mb-3">
        <label class="form-label">Kondisi</label>
        <select name="kondisi" id="kondisi" class="form-select">
            <option value="Baik" {{ $barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
            <option value="Rusak Ringan" {{ $barang->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
            <option value="Rusak Berat" {{ $barang->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
        </select>
    </div>
    
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Perolehan" name="tanggal_perolehan" type="date" 
        value="{{ $barang->tanggal_perolehan ? $barang->tanggal_perolehan->format('Y-m-d') : '' }}" />
    <x-input-with-icon icon="ti ti-currency-dollar" label="Harga Perolehan" name="harga_perolehan" type="number" step="0.01" 
        value="{{ $barang->harga_perolehan }}" />
    
    <div class="form-group mb-3">
        <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Barang</label>
        @if($barang->foto)
            <div class="mb-2">
                <img src="{{ asset('storage/barang/' . $barang->foto) }}" class="img-thumbnail" style="max-width: 200px;">
                <p class="text-muted small mb-0">Foto saat ini</p>
            </div>
        @endif
        <input type="file" name="foto" id="foto_barang_edit" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
        <div class="mt-2">
            <img id="preview_foto_barang_edit" src="" style="max-width: 200px; display: none;" class="img-thumbnail">
        </div>
    </div>
    
    <x-textarea label="Keterangan" name="keterangan" value="{{ $barang->keterangan }}" />
    
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Update
        </button>
    </div>
</form>

<script>
    // Preview foto barang saat dipilih
    $("#foto_barang_edit").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_foto_barang_edit').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#preview_foto_barang_edit').hide();
        }
    });

    $("#formeditBarang").submit(function(e) {
        var nama_barang = $("#formeditBarang").find("#nama_barang").val();
        var jumlah = $("#formeditBarang").find("#jumlah").val();
        var satuan = $("#formeditBarang").find("#satuan").val();

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
