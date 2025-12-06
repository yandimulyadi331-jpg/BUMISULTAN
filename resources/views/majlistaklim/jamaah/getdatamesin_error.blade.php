<div class="row">
    <div class="col-12">
        <div class="alert alert-danger">
            <div class="d-flex align-items-center mb-3">
                <i class="ti ti-alert-circle me-3" style="font-size: 48px;"></i>
                <div>
                    <h5 class="mb-1">
                        <i class="ti ti-x me-2"></i>Gagal Mengambil Data dari Mesin Fingerspot
                    </h5>
                    <p class="mb-0">Terjadi kesalahan saat mencoba mengambil data absensi dari mesin.</p>
                </div>
            </div>
            
            <hr>
            
            <div class="mt-3">
                <strong><i class="ti ti-info-circle me-2"></i>Detail Error:</strong>
                <div class="bg-white p-3 rounded mt-2 text-dark">
                    <code>{{ $error }}</code>
                </div>
            </div>

            @if(isset($response))
                <div class="mt-3">
                    <strong><i class="ti ti-code me-2"></i>Response dari Server:</strong>
                    <div class="bg-white p-3 rounded mt-2 text-dark">
                        <pre style="margin: 0; max-height: 200px; overflow-y: auto;"><code>{{ $response }}</code></pre>
                    </div>
                </div>
            @endif
        </div>

        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="ti ti-help me-2"></i>Troubleshooting
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Kemungkinan penyebab masalah:</strong></p>
                <ol>
                    <li>
                        <strong>Cloud ID atau API Key belum diatur</strong>
                        <ul>
                            <li>Pastikan Cloud ID dan API Key sudah di-input di menu <strong>Pengaturan → Pengaturan Umum</strong></li>
                            <li>Dapatkan Cloud ID & API Key dari dashboard <a href="https://developer.fingerspot.io" target="_blank">https://developer.fingerspot.io</a></li>
                        </ul>
                    </li>
                    <li>
                        <strong>Mesin fingerprint belum sync ke cloud</strong>
                        <ul>
                            <li>Cek setting mesin, pastikan <strong>Cloud Sync</strong> aktif</li>
                            <li>Pastikan mesin terhubung ke internet</li>
                            <li>Lakukan manual sync jika diperlukan</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Tidak ada data absensi pada tanggal yang dipilih</strong>
                        <ul>
                            <li>Pastikan jamaah sudah melakukan absensi di mesin</li>
                            <li>Coba pilih tanggal yang berbeda</li>
                            <li>Tunggu beberapa menit untuk proses sync otomatis</li>
                        </ul>
                    </li>
                    <li>
                        <strong>API Key expired atau tidak valid</strong>
                        <ul>
                            <li>Login ke dashboard Fingerspot</li>
                            <li>Generate API Key baru</li>
                            <li>Update di menu Pengaturan Umum</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Server Fingerspot down atau maintenance</strong>
                        <ul>
                            <li>Coba beberapa saat lagi</li>
                            <li>Hubungi support Fingerspot: <a href="mailto:support@fingerspot.com">support@fingerspot.com</a></li>
                        </ul>
                    </li>
                </ol>

                <div class="alert alert-info mt-3">
                    <i class="ti ti-bulb me-2"></i>
                    <strong>Tips:</strong>
                    <ul class="mb-0">
                        <li>Gunakan menu <strong>Monitoring Presensi</strong> untuk melihat contoh yang sudah berfungsi</li>
                        <li>Pastikan PIN jamaah di database sesuai dengan PIN di mesin fingerprint</li>
                        <li>Test koneksi dengan tombol "Get Data Mesin" di halaman Karyawan terlebih dahulu</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-3 text-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="ti ti-x me-2"></i>Tutup
            </button>
            <button type="button" class="btn btn-primary" onclick="location.href='{{ route('generalsettings.index') }}'">
                <i class="ti ti-settings me-2"></i>Buka Pengaturan Umum
            </button>
        </div>
    </div>
</div>

<style>
    .alert-danger {
        border-left: 4px solid #dc3545;
    }
    
    code {
        font-size: 0.9rem;
        color: #d63384;
    }
    
    pre code {
        color: #333;
    }
</style>
