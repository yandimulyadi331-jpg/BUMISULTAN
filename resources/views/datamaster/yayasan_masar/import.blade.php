<form action="{{ route('yayasan_masar.importExcel') }}" id="formImportYayasanMasar" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="alert alert-info" role="alert">
        <i class="ti ti-info-circle me-2"></i>
        <strong>Petunjuk:</strong> Pilih file Excel (.xlsx atau .xls) yang sudah diisi sesuai template. Download template terlebih dahulu jika belum memiliki format yang tepat.
    </div>
    
    <div class="form-group mb-3">
        <label for="file" class="form-label"><strong>Pilih File Excel</strong> <span class="text-danger">*</span></label>
        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls" required>
        <small class="text-muted">Format: .xlsx atau .xls (max 5MB)</small>
    </div>

    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="cloud-upload-outline" class="me-1"></ion-icon>
            Import Data
        </button>
    </div>
</form>

<script>
    $(function() {
        $("#formImportYayasanMasar").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            
            $.ajax({
                type: "POST",
                url: "{{ route('yayasan_masar.importExcel') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.message) {
                        alert(response.message);
                    }
                    location.reload();
                },
                error: function(error) {
                    alert('Terjadi kesalahan saat import data');
                }
            });
        });
    });
</script>
