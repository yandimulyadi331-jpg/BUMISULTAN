<div class="row">
    <div class="col-12">
        <!-- Informasi Yayasan Masar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th class="text-muted">NIK</th>
                            <td class="fw-semibold">{{ $yayasan_masar->nik }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nama Yayasan Masar</th>
                            <td class="fw-semibold">{{ $yayasan_masar->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Departemen</th>
                            <td class="fw-semibold">{{ $yayasan_masar->nama_dept }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Cabang Utama</th>
                            <td class="fw-semibold">{{ $yayasan_masar->nama_cabang }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Set Cabang -->
        <form action="{{ route('yayasan_masar.storecabang', Crypt::encrypt($yayasan_masar->nik)) }}" id="formSetCabang" method="POST">
            @csrf
            <div class="row">
                <div class="col-12">
                    <h6 class="mb-3 text-primary">
                        <i class="ti ti-location-plus me-2"></i>Pilih Cabang Tambahan yang Bisa Diakses Yayasan Masar untuk Absen
                    </h6>
                    <div class="alert alert-info mb-3">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Cabang Utama:</strong> {{ $yayasan_masar->nama_cabang }} sudah otomatis terchecklist dan bisa diakses untuk absen.
                    </div>
                    <div class="row g-3">
                        @foreach ($cabang as $c)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="kode_cabang_array[]" value="{{ $c->kode_cabang }}"
                                                id="cabang_{{ $c->kode_cabang }}"
                                                {{ in_array($c->kode_cabang, $kode_cabang_array) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium w-100" for="cabang_{{ $c->kode_cabang }}">
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-building me-2 text-primary"></i>
                                                    <span>{{ $c->nama_cabang }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="row mt-4">
                <div class="col-6">
                    <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Batal
                    </button>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        $("#formSetCabang").submit(function() {
            $("#btnSubmit").prop('disabled', true);
            $("#btnSubmit").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading...
            `);
        });
    });
</script>
