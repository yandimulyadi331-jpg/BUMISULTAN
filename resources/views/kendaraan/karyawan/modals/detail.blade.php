<!-- Modal Detail Kendaraan -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetailLabel">
                    <ion-icon name="information-circle-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Detail Kendaraan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function loadDetailKendaraan(kendaraanId) {
    $('#detailContent').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat data...</p>
        </div>
    `);

    $.ajax({
        url: `/api/kendaraan/${kendaraanId}/detail`,
        method: 'GET',
        success: function(response) {
            const data = response.data;
            let html = `
                <!-- Foto Besar -->
                <div class="text-center mb-4">
                    <img src="${data.foto ? '/storage/kendaraan/' + data.foto : '/assets/img/no-image.png'}" 
                         alt="${data.nama_kendaraan}"
                         class="img-fluid rounded shadow"
                         style="max-height: 300px; object-fit: cover;">
                </div>

                <!-- Informasi Dasar -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Informasi Dasar</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <small class="text-muted">Kode Kendaraan</small>
                                <div class="fw-bold">${data.kode_kendaraan}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">No. Polisi</small>
                                <div class="fw-bold">${data.no_polisi}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Jenis</small>
                                <div>${data.jenis_kendaraan}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Merk / Model</small>
                                <div>${data.merk ?? '-'} / ${data.model ?? '-'}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Tahun</small>
                                <div>${data.tahun ?? '-'}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Warna</small>
                                <div>${data.warna ?? '-'}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Kapasitas</small>
                                <div>${data.kapasitas_penumpang ?? '-'} orang</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Jenis BBM</small>
                                <div>${data.jenis_bbm ?? '-'}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Terakhir -->
                ${data.riwayat_terakhir ? `
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Riwayat Terakhir</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <small class="text-muted">KM Terakhir</small>
                                <div class="fw-bold">${data.riwayat_terakhir.km_terakhir ?? '-'} km</div>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">BBM Terakhir</small>
                                <div>${data.riwayat_terakhir.bbm_terakhir ?? '-'}</div>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Pemakaian Terakhir</small>
                                <div>${data.riwayat_terakhir.pemakaian_terakhir ?? '-'}</div>
                            </div>
                        </div>
                    </div>
                </div>
                ` : ''}

                <!-- Catatan Service -->
                ${data.catatan_service ? `
                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark">
                        <strong>Catatan Service</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">${data.catatan_service}</p>
                    </div>
                </div>
                ` : ''}

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-3">
                    ${data.can_keluar ? `
                        <button class="btn btn-success flex-fill" onclick="$('#modalDetail').modal('hide'); openKeluarModal(${data.id});">
                            <ion-icon name="exit-outline"></ion-icon> Keluar
                        </button>
                    ` : ''}
                    ${data.can_pinjam ? `
                        <button class="btn btn-info flex-fill" onclick="$('#modalDetail').modal('hide'); openPinjamModal(${data.id});">
                            <ion-icon name="key-outline"></ion-icon> Pinjam
                        </button>
                    ` : ''}
                    ${data.can_service ? `
                        <button class="btn btn-warning flex-fill" onclick="$('#modalDetail').modal('hide'); openServiceModal(${data.id});">
                            <ion-icon name="construct-outline"></ion-icon> Service
                        </button>
                    ` : ''}
                </div>
            `;
            
            $('#detailContent').html(html);
        },
        error: function(xhr) {
            $('#detailContent').html(`
                <div class="alert alert-danger">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    Gagal memuat data: ${xhr.responseJSON?.message || 'Terjadi kesalahan'}
                </div>
            `);
        }
    });
}
</script>
