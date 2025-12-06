<div class="row">
    <div class="col-12">
        @if(count($filtered_array) > 0)
            <div class="alert alert-info mb-3">
                <i class="ti ti-info-circle me-2"></i>
                <strong>Info:</strong> Ditemukan <strong>{{ count($filtered_array) }}</strong> data absensi dari mesin Fingerspot.
                <br>
                <small>Klik tombol <strong>"Masuk"</strong> atau <strong>"Pulang"</strong> untuk menyimpan data ke database.</small>
            </div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th colspan="4" class="text-center">
                        <i class="ti ti-device-desktop me-2"></i>
                        Data Absensi dari Mesin Fingerspot Cloud
                    </th>
                </tr>
                <tr>
                    <th width="15%">PIN</th>
                    <th width="20%">Status Scan</th>
                    <th width="30%">Waktu Scan</th>
                    <th width="35%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filtered_array as $d)
                    <tr>
                        <!-- PIN -->
                        <td class="text-center">
                            <span class="badge bg-primary" style="font-size: 14px;">
                                {{ $d->pin }}
                            </span>
                        </td>
                        
                        <!-- STATUS SCAN: IN/OUT -->
                        <td class="text-center">
                            @if($d->status_scan % 2 == 0)
                                <span class="badge bg-success">
                                    <i class="ti ti-login me-1"></i> MASUK
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="ti ti-logout me-1"></i> PULANG
                                </span>
                            @endif
                            <br>
                            <small class="text-muted">(Status Code: {{ $d->status_scan }})</small>
                        </td>
                        
                        <!-- WAKTU SCAN -->
                        <td>
                            <i class="ti ti-calendar me-1"></i>
                            <strong>{{ date('d-m-Y', strtotime($d->scan_date)) }}</strong>
                            <br>
                            <i class="ti ti-clock me-1"></i>
                            <strong>{{ date('H:i:s', strtotime($d->scan_date)) }}</strong>
                        </td>
                        
                        <!-- AKSI: SIMPAN MASUK / PULANG -->
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <!-- Button MASUK -->
                                <form method="POST" class="updatemasuk"
                                      action="{{ route('masar.jamaah.updatefrommachine', [Crypt::encrypt($d->pin), 0]) }}">
                                    @csrf
                                    <input type="hidden" name="scan_date" 
                                           value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="ti ti-login me-1"></i> Simpan MASUK
                                    </button>
                                </form>
                                
                                <!-- Button PULANG -->
                                <form method="POST" class="updatepulang"
                                      action="{{ route('masar.jamaah.updatefrommachine', [Crypt::encrypt($d->pin), 1]) }}">
                                    @csrf
                                    <input type="hidden" name="scan_date" 
                                           value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="ti ti-logout me-1"></i> Simpan PULANG
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="ti ti-mood-sad text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2 mb-0">
                                <strong>Tidak ada data absensi</strong>
                            </p>
                            <small class="text-muted">
                                Tidak ditemukan data absensi untuk PIN ini pada tanggal yang dipilih.
                                <br>
                                Pastikan jamaah sudah melakukan absensi di mesin fingerprint.
                            </small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(count($filtered_array) > 0)
            <div class="alert alert-warning mt-3">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>Perhatian:</strong>
                <ul class="mb-0 mt-2">
                    <li>Data akan disimpan ke tabel <code>kehadiran_jamaah_masar</code></li>
                    <li>Jika sudah ada data di tanggal yang sama, jam masuk/pulang akan di-update</li>
                    <li>Jumlah kehadiran jamaah akan otomatis bertambah (hanya untuk record baru)</li>
                    <li>Status Scan genap (0,2,4,6,8) = <strong>MASUK</strong></li>
                    <li>Status Scan ganjil (1,3,5,7,9) = <strong>PULANG</strong></li>
                </ul>
            </div>
        @endif
    </div>
</div>

<style>
    .table thead th {
        background-color: #1a1d29;
        color: white;
        font-weight: 600;
    }
    
    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
</style>
