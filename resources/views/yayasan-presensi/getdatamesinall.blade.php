<div class="row">
    <div class="col-12">
        <div class="alert alert-info" role="alert">
            <i class="ti ti-info-circle me-2"></i>
            <strong>Informasi:</strong> Data yang ditampilkan adalah semua data presensi dari mesin untuk tanggal <strong>{{ date('d-m-Y', strtotime($tanggal)) }}</strong>
        </div>

        @if(empty($filteredDataByPIN))
            <div class="alert alert-warning" role="alert">
                <i class="ti ti-alert-circle me-2"></i>
                <strong>Tidak ada data</strong> Tidak ada data presensi dari mesin untuk tanggal ini.
            </div>
        @else
            <!-- Tombol Aksi Utama -->
            <div class="row mb-4">
                <div class="col-6">
                    <button class="btn btn-success btn-lg w-100 btnSubmitAllMasuk" tanggal="{{ $tanggal }}">
                        <i class="ti ti-login me-2" style="font-size: 24px;"></i>
                        <span style="font-size: 18px;">MASUK</span>
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-danger btn-lg w-100 btnSubmitAllPulang" tanggal="{{ $tanggal }}">
                        <i class="ti ti-logout me-2" style="font-size: 24px;"></i>
                        <span style="font-size: 18px;">PULANG</span>
                    </button>
                </div>
            </div>

            <!-- Tabel Preview Data yang akan diproses -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">📋 Preview Data Presensi - {{ count($filteredDataByPIN) }} Jamaah</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 15%">Kode</th>
                                    <th style="width: 40%">Nama Jamaah</th>
                                    <th style="width: 15%">PIN</th>
                                    <th style="width: 15%">Status Scan</th>
                                    <th style="width: 10%">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($filteredDataByPIN as $kodeYayasan => $yayasanData)
                                    @foreach($yayasanData['data'] as $scanData)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td><code>{{ $kodeYayasan }}</code></td>
                                            <td><strong>{{ $yayasanData['nama_yayasan'] }}</strong></td>
                                            <td><code>{{ $scanData->pin }}</code></td>
                                            <td>
                                                @if($scanData->status_scan % 2 == 0)
                                                    <span class="badge bg-success"><i class="ti ti-login me-1"></i>IN</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="ti ti-logout me-1"></i>OUT</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ date('H:i:s', strtotime($scanData->scan_date)) }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alert untuk konfirmasi -->
            <div class="alert alert-warning mt-4" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>Perhatian:</strong> Klik tombol <strong>MASUK</strong> atau <strong>PULANG</strong> untuk merekam absensi SEMUA jamaah sekaligus. Proses tidak dapat dibatalkan!
            </div>
        @endif
    </div>
</div>

<script>
    $(function() {
        // Tombol MASUK untuk semua
        $(".btnSubmitAllMasuk").click(function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Absensi MASUK',
                html: '<p class="mb-0">Yakin ingin merekam <strong>MASUK</strong> untuk <strong>SEMUA jamaah</strong> sekaligus?</p><p class="text-muted small mt-2">Proses tidak dapat dibatalkan!</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-login me-2"></i> Ya, Proses Sekarang',
                cancelButtonText: '<i class="ti ti-x me-2"></i> Batal',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var tanggal = $(this).attr("tanggal");
                    var btn = $(this);
                    btn.prop('disabled', true);
                    btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
                    
                    $.ajax({
                        type: 'POST',
                        url: '/yayasan-presensi/submitallmasuk',
                        data: {
                            _token: "{{ csrf_token() }}",
                            tanggal: tanggal
                        },
                        cache: false,
                        success: function(respond) {
                            Swal.fire({
                                title: '✓ Berhasil!',
                                html: `<div class="text-start">
                                    <p><strong>Absensi MASUK berhasil diproses!</strong></p>
                                    <p class="mb-2">Statistik:</p>
                                    <ul class="mb-0">
                                        <li>Berhasil: <strong class="text-success">${respond.count_success} jamaah</strong></li>
                                        <li>Gagal: <strong class="text-danger">${respond.count_failed} jamaah</strong></li>
                                    </ul>
                                </div>`,
                                icon: 'success',
                                confirmButtonColor: '#28a745',
                                confirmButtonText: '<i class="ti ti-check me-2"></i> OK',
                                allowOutsideClick: false,
                                didClose: () => {
                                    location.reload();
                                }
                            });
                        },
                        error: function(err) {
                            Swal.fire({
                                title: '✗ Terjadi Kesalahan',
                                text: err.responseJSON?.message || 'Silakan coba lagi',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: '<i class="ti ti-x me-2"></i> OK',
                                allowOutsideClick: false
                            });
                            btn.prop('disabled', false);
                            btn.html('<i class="ti ti-login me-2" style="font-size: 24px;"></i><span style="font-size: 18px;">MASUK</span>');
                        }
                    });
                }
            });
        });

        // Tombol PULANG untuk semua
        $(".btnSubmitAllPulang").click(function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Absensi PULANG',
                html: '<p class="mb-0">Yakin ingin merekam <strong>PULANG</strong> untuk <strong>SEMUA jamaah</strong> sekaligus?</p><p class="text-muted small mt-2">Proses tidak dapat dibatalkan!</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-logout me-2"></i> Ya, Proses Sekarang',
                cancelButtonText: '<i class="ti ti-x me-2"></i> Batal',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var tanggal = $(this).attr("tanggal");
                    var btn = $(this);
                    btn.prop('disabled', true);
                    btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
                    
                    $.ajax({
                        type: 'POST',
                        url: '/yayasan-presensi/submitallpulang',
                        data: {
                            _token: "{{ csrf_token() }}",
                            tanggal: tanggal
                        },
                        cache: false,
                        success: function(respond) {
                            Swal.fire({
                                title: '✓ Berhasil!',
                                html: `<div class="text-start">
                                    <p><strong>Absensi PULANG berhasil diproses!</strong></p>
                                    <p class="mb-2">Statistik:</p>
                                    <ul class="mb-0">
                                        <li>Berhasil: <strong class="text-success">${respond.count_success} jamaah</strong></li>
                                        <li>Gagal: <strong class="text-danger">${respond.count_failed} jamaah</strong></li>
                                    </ul>
                                </div>`,
                                icon: 'success',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: '<i class="ti ti-check me-2"></i> OK',
                                allowOutsideClick: false,
                                didClose: () => {
                                    location.reload();
                                }
                            });
                        },
                        error: function(err) {
                            Swal.fire({
                                title: '✗ Terjadi Kesalahan',
                                text: err.responseJSON?.message || 'Silakan coba lagi',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: '<i class="ti ti-x me-2"></i> OK',
                                allowOutsideClick: false
                            });
                            btn.prop('disabled', false);
                            btn.html('<i class="ti ti-logout me-2" style="font-size: 24px;"></i><span style="font-size: 18px;">PULANG</span>');
                        }
                    });
                }
            });
        });
    });
</script>

