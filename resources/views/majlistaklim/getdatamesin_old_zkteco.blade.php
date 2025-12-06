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
                        <div class="d-flex flex-wrap gap-3">
                            <div>
                                <small class="text-muted">IP Address:</small>
                                <div class="fw-bold">{{ config('app.zkteco_ip', '192.168.1.201') }}</div>
                            </div>
                            <div>
                                <small class="text-muted">Port:</small>
                                <div class="fw-bold">{{ config('app.zkteco_port', '4370') }}</div>
                            </div>
                            <div>
                                <small class="text-muted">Serial Number:</small>
                                <div class="fw-bold">TES3243500221</div>
                            </div>
                            <div>
                                <small class="text-muted">Platform:</small>
                                <div class="fw-bold">ZKTeco ZMM220_TFT</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <button type="button" class="btn btn-info btn-sm me-2" id="btnTestConnection">
                            <i class="ti ti-plug-connected me-1"></i> Test Koneksi
                        </button>
                        <button type="button" class="btn btn-primary" id="btnFetchData">
                            <i class="ti ti-refresh me-1"></i> Ambil Data Dari Mesin
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Preview Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-table me-2"></i>Data Kehadiran Dari Mesin</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm" id="btnSyncAll" disabled>
                        <i class="ti ti-database-import me-1"></i> Sinkronkan Semua Data
                    </button>
                    <button type="button" class="btn btn-info btn-sm" id="btnSyncSelected" disabled>
                        <i class="ti ti-check me-1"></i> Sinkronkan Data Terpilih
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Loading State -->
                <div id="loadingState" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Mengambil data dari mesin fingerprint...</p>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-5">
                    <i class="ti ti-database-off" style="font-size: 4rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Data</h5>
                    <p class="text-muted">Klik tombol "Ambil Data Dari Mesin" untuk mengambil data kehadiran</p>
                </div>

                <!-- Data Table -->
                <div id="tableContainer" style="display: none;">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Informasi:</strong> Data yang sudah ada di database dengan tanggal yang sama akan di-skip otomatis saat sinkronisasi.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tableAttendance" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="checkAll" class="form-check-input">
                                    </th>
                                    <th width="5%">No</th>
                                    <th>PIN</th>
                                    <th>Nama Jamaah</th>
                                    <th>Nomor Jamaah</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    // Debug: Check if libraries loaded
    console.log('jQuery loaded:', typeof jQuery !== 'undefined');
    console.log('DataTables loaded:', typeof $.fn.DataTable !== 'undefined');
    console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');

    let attendanceData = [];
    let dataTable = null;

    $(document).ready(function() {
        console.log('Document ready - initializing...');
        
        // Initialize DataTable
        try {
            dataTable = $('#tableAttendance').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                order: [[5, 'desc'], [6, 'desc']], // Sort by tanggal, jam DESC
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                columnDefs: [
                    { 
                        orderable: false, 
                        targets: [0] // Checkbox column tidak bisa di-sort
                    }
                ]
            });
            console.log('DataTable initialized successfully');
        } catch (error) {
            console.error('Error initializing DataTable:', error);
        }

        // Button Fetch Data
        $('#btnFetchData').click(function() {
            console.log('Fetch button clicked');
            fetchDataFromMachine();
        });

        // Button Test Connection
        $('#btnTestConnection').click(function() {
            console.log('Test connection button clicked');
            testConnection();
        });

        // Button Test Connection
        $('#btnTestConnection').click(function() {
            testConnection();
        });

        // Check All Checkbox
        $('#checkAll').change(function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
            updateSyncButtons();
        });

        // Individual Checkbox
        $(document).on('change', '.row-checkbox', function() {
            updateSyncButtons();
            
            // Update Check All state
            const totalCheckbox = $('.row-checkbox').length;
            const checkedCheckbox = $('.row-checkbox:checked').length;
            $('#checkAll').prop('checked', totalCheckbox === checkedCheckbox);
        });

        // Sync All Button
        $('#btnSyncAll').click(function() {
            const confirmed = confirm('Apakah Anda yakin ingin menyinkronkan SEMUA data ke database?');
            if (confirmed) {
                syncToDatabase(attendanceData);
            }
        });

        // Sync Selected Button
        $('#btnSyncSelected').click(function() {
            const selectedData = [];
            $('.row-checkbox:checked').each(function() {
                const index = $(this).data('index');
                selectedData.push(attendanceData[index]);
            });

            if (selectedData.length === 0) {
                alert('Pilih minimal 1 data untuk disinkronkan');
                return;
            }

            const confirmed = confirm(`Apakah Anda yakin ingin menyinkronkan ${selectedData.length} data terpilih ke database?`);
            if (confirmed) {
                syncToDatabase(selectedData);
            }
        });
    });

    function testConnection() {
        console.log('testConnection function called');
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
        console.log('Route URL:', '{{ route("majlistaklim.testConnection") }}');
        
        // Disable button
        $('#btnTestConnection').prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Testing...');

        $.ajax({
            url: '{{ route("majlistaklim.testConnection") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                console.log('Sending test connection request...');
            },
            success: function(response) {
                console.log('Test connection response:', response);
                if (response.success) {
                    let deviceInfo = '';
                    if (response.details.device_info) {
                        const info = response.details.device_info;
                        deviceInfo = `
                            <div class="mt-3 text-start">
                                <h6>Device Information:</h6>
                                <ul class="list-unstyled">
                                    ${info.serial_number ? `<li><strong>Serial:</strong> ${info.serial_number}</li>` : ''}
                                    ${info.platform ? `<li><strong>Platform:</strong> ${info.platform}</li>` : ''}
                                    ${info.firmware_version ? `<li><strong>Firmware:</strong> ${info.firmware_version}</li>` : ''}
                                    ${info.device_name ? `<li><strong>Device Name:</strong> ${info.device_name}</li>` : ''}
                                </ul>
                            </div>
                        `;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Koneksi Berhasil!',
                        html: `
                            <p>Mesin fingerprint <strong>${response.details.ip}:${response.details.port}</strong> dapat diakses.</p>
                            ${deviceInfo}
                        `,
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Gagal!',
                        html: `
                            <p>${response.message}</p>
                            <div class="mt-3 text-start">
                                <p><strong>Troubleshooting:</strong></p>
                                <ul class="text-start">
                                    <li>Pastikan mesin dalam kondisi ON</li>
                                    <li>Cek koneksi jaringan ke ${response.details?.ip || '192.168.1.201'}</li>
                                    <li>Pastikan port ${response.details?.port || '4370'} tidak di-block firewall</li>
                                    <li>Restart mesin jika diperlukan</li>
                                </ul>
                            </div>
                        `,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Test connection error:', xhr, status, error);
                console.error('Response Text:', xhr.responseText);
                
                let errorMessage = 'Terjadi kesalahan saat test koneksi';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                $('#btnTestConnection').prop('disabled', false).html('<i class="ti ti-plug-connected me-1"></i> Test Koneksi');
            }
        });
    }

    function fetchDataFromMachine() {
        console.log('fetchDataFromMachine function called');
        console.log('Route URL:', '{{ route("majlistaklim.fetchDataFromMachine") }}');
        
        // Show loading
        $('#loadingState').show();
        $('#emptyState').hide();
        $('#tableContainer').hide();
        $('#btnFetchData').prop('disabled', true);

        $.ajax({
            url: '{{ route("majlistaklim.fetchDataFromMachine") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                console.log('Sending fetch data request...');
            },
            success: function(response) {
                console.log('Fetch data response:', response);
                if (response.success) {
                    attendanceData = response.data;
                    
                    if (attendanceData.length === 0) {
                        $('#emptyState').show();
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak Ada Data',
                            text: 'Tidak ada data attendance di mesin fingerprint',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        // Populate DataTable
                        populateTable(attendanceData);
                        $('#tableContainer').show();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `Berhasil mengambil ${attendanceData.length} data dari mesin`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    $('#emptyState').show();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr) {
                $('#emptyState').show();
                let errorMessage = 'Terjadi kesalahan saat mengambil data dari mesin';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                $('#loadingState').hide();
                $('#btnFetchData').prop('disabled', false);
            }
        });
    }

    function populateTable(data) {
        // Clear existing data
        dataTable.clear();

        // Add new data
        data.forEach(function(item, index) {
            const statusBadge = item.status === 'Terdaftar' 
                ? '<span class="badge bg-success">Terdaftar</span>'
                : '<span class="badge bg-danger">Tidak Terdaftar</span>';

            // Only add checkbox for registered jamaah
            const checkbox = item.jamaah_id 
                ? `<input type="checkbox" class="form-check-input row-checkbox" data-index="${index}">`
                : '<i class="ti ti-ban text-muted"></i>';

            dataTable.row.add([
                checkbox,
                index + 1,
                item.pin,
                item.nama_jamaah,
                item.nomor_jamaah,
                formatDate(item.tanggal),
                item.jam,
                statusBadge
            ]);
        });

        dataTable.draw();
        updateSyncButtons();
    }

    function updateSyncButtons() {
        const totalRows = attendanceData.filter(item => item.jamaah_id).length;
        const checkedRows = $('.row-checkbox:checked').length;

        $('#btnSyncAll').prop('disabled', totalRows === 0);
        $('#btnSyncSelected').prop('disabled', checkedRows === 0);
    }

    function syncToDatabase(dataToSync) {
        // Filter only registered jamaah
        const validData = dataToSync.filter(item => item.jamaah_id);

        if (validData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Tidak ada data yang valid untuk disinkronkan (semua PIN tidak terdaftar)',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Menyinkronkan Data...',
            text: 'Mohon tunggu, sedang menyimpan data ke database',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ route("majlistaklim.updatefrommachine") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                data: validData
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <p>${response.message}</p>
                            <ul class="list-unstyled mt-3">
                                <li><strong>Berhasil:</strong> ${response.details.success}</li>
                                <li><strong>Di-skip (sudah ada):</strong> ${response.details.skipped}</li>
                                <li><strong>Error:</strong> ${response.details.errors}</li>
                            </ul>
                        `,
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        // Reset checkboxes
                        $('#checkAll').prop('checked', false);
                        $('.row-checkbox').prop('checked', false);
                        updateSyncButtons();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyinkronkan data';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#d33'
                });
            }
        });
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        
        const date = new Date(dateString);
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            timeZone: 'Asia/Jakarta'
        };
        
        return date.toLocaleDateString('id-ID', options);
    }
</script>
@endpush
