@extends('layouts.app')
@section('titlepage', 'Data Mesin Fingerprint - Al-Ikhlas')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> Data Mesin Fingerprint
@endsection

<!-- Navigation Tabs -->
@include('majlistaklim.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <!-- Info Mesin Card -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2"><i class="ti ti-device-desktop-analytics me-2"></i>Mesin Fingerprint Solution X601</h5>
                        <div class="alert alert-info mb-0">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>IP:</strong> {{ config('app.zkteco_ip', '192.168.1.201') }} | 
                            <strong>Port:</strong> {{ config('app.zkteco_port', 4370) }} | 
                            <strong>Model:</strong> Solution X601 (ZKTeco Platform)
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <button type="button" class="btn btn-info btn-sm me-2" id="btnTestConnection">
                            <i class="ti ti-plug-connected me-1"></i> Test Koneksi
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="btnFetchAll">
                            <i class="ti ti-download me-1"></i> Ambil Semua Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- List Jamaah dengan PIN -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-users me-2"></i>Daftar Jamaah dengan PIN Fingerprint</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    <strong>Cara Menggunakan:</strong>
                    <ol class="mb-0 mt-2">
                        <li><strong>Test Koneksi:</strong> Pastikan mesin terhubung ke jaringan lokal terlebih dahulu</li>
                        <li><strong>Ambil Semua Data:</strong> Klik tombol untuk mengambil semua data attendance dari mesin</li>
                        <li><strong>Review & Update:</strong> Data akan ditampilkan dalam tabel, klik Update untuk simpan ke database</li>
                    </ol>
                </div>

                <div id="loadingState" style="display:none;" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Mengambil data dari mesin Solution X601...</p>
                </div>

                <div id="dataTable" class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableMesinData">
                        <thead class="table-dark">
                            <tr>
                                <th width="3%">No</th>
                                <th width="10%">PIN</th>
                                <th>Nama Jamaah</th>
                                <th>Nomor Jamaah</th>
                                <th width="15%">Tanggal</th>
                                <th width="10%">Jam</th>
                                <th width="10%">Status</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="ti ti-database-off" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Belum ada data dari mesin</p>
                                    <p class="text-muted">Klik tombol "Ambil Semua Data" untuk mengambil data dari mesin</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
$(document).ready(function() {
    
    // Test Connection ke Mesin X601
    $('#btnTestConnection').click(function() {
        Swal.fire({
            title: 'Testing Connection...',
            html: 'Menghubungi mesin Solution X601...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/majlistaklim/test-connection',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const info = response.details.device_info || {};
                Swal.fire({
                    icon: 'success',
                    title: 'Koneksi Berhasil!',
                    html: `
                        <div class="text-start">
                            <strong>IP:</strong> ${response.details.ip}:${response.details.port}<br>
                            <strong>Status:</strong> <span class="badge bg-success">${response.details.status}</span><br>
                            <strong>Model:</strong> ${info.model || 'Solution X601'}<br>
                            <strong>Serial:</strong> ${info.serial || '-'}<br>
                            <strong>Users:</strong> ${info.users || '-'}
                        </div>
                    `
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Gagal!',
                    text: xhr.responseJSON?.message || 'Pastikan mesin terhubung ke jaringan lokal'
                });
            }
        });
    });

    // Ambil Semua Data dari Mesin
    $('#btnFetchAll').click(function() {
        $('#loadingState').show();
        $('#dataTable').hide();

        Swal.fire({
            title: 'Mengambil Data...',
            html: 'Mengambil data attendance dari mesin Solution X601...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/majlistaklim/fetch-from-machine',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.close();
                $('#loadingState').hide();
                $('#dataTable').show();
                
                if (response.success && response.data && response.data.length > 0) {
                    // Build table rows
                    let rows = '';
                    response.data.forEach(function(item, index) {
                        const statusText = item.type === 'Check In' ? 'Masuk' : 'Pulang';
                        const statusClass = item.type === 'Check In' ? 'success' : 'danger';
                        const jamaahName = item.nama_jamaah || 'PIN Tidak Terdaftar';
                        const nomorJamaah = item.nomor_jamaah || '-';
                        const isRegistered = item.status === 'Terdaftar';
                        
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td><span class="badge bg-primary">${item.pin}</span></td>
                                <td>${jamaahName}</td>
                                <td>${nomorJamaah}</td>
                                <td>${item.tanggal}</td>
                                <td>${item.jam}</td>
                                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                                <td>
                                    ${isRegistered ? `
                                        <button class="btn btn-success btn-sm btnUpdate" 
                                            data-jamaah-id="${item.jamaah_id}"
                                            data-pin="${item.pin}"
                                            data-tanggal="${item.tanggal}"
                                            data-jam="${item.jam}"
                                            data-timestamp="${item.timestamp}">
                                            <i class="ti ti-check me-1"></i> Update
                                        </button>
                                    ` : `
                                        <span class="badge bg-warning">PIN Belum Terdaftar</span>
                                    `}
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#dataTableBody').html(rows);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `Berhasil mengambil ${response.data.length} data dari mesin`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    $('#dataTableBody').html(`
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="ti ti-database-off" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Tidak ada data attendance di mesin</p>
                            </td>
                        </tr>
                    `);
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Data',
                        text: 'Tidak ada data attendance di mesin'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                $('#loadingState').hide();
                $('#dataTable').show();
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Gagal mengambil data dari mesin'
                });
            }
        });
    });

    // Update ke Database
    $(document).on('click', '.btnUpdate', function() {
        const btn = $(this);
        const jamaahId = btn.data('jamaah-id');
        const pin = btn.data('pin');
        const tanggal = btn.data('tanggal');
        const jam = btn.data('jam');
        const timestamp = btn.data('timestamp');

        Swal.fire({
            title: 'Konfirmasi',
            text: `Simpan data kehadiran (${tanggal} ${jam}) ke database?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX Update
                $.ajax({
                    url: '/majlistaklim/updatefrommachine',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: [{
                            jamaah_id: jamaahId,
                            pin: pin,
                            tanggal: tanggal,
                            jam: jam,
                            timestamp: timestamp
                        }]
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data kehadiran berhasil disimpan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Remove row dari tabel
                            btn.closest('tr').fadeOut();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Gagal menyimpan data'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
