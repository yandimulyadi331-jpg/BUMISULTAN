@extends('layouts.app')
@section('titlepage', 'Data Jamaah MASAR')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> Data Jamaah MASAR
@endsection

<!-- Navigation Tabs -->
@include('masar.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @php
                    $titleJamaah = 'Data Jamaah MASAR';
                    if (request('jenis_kelamin') === 'laki-laki') {
                        $titleJamaah = 'Data Jamaah MASAR - Laki-laki';
                    } elseif (request('jenis_kelamin') === 'perempuan') {
                        $titleJamaah = 'Data Jamaah MASAR - Perempuan';
                    }
                @endphp
                <h5 class="mb-0"><i class="ti ti-users me-2"></i>{{ $titleJamaah }}</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('masar.jamaah.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i> Tambah Jamaah
                    </a>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                        <i class="ti ti-file-import me-1"></i> Import Excel
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalImportKehadiran">
                        <i class="ti ti-calendar-import me-1"></i> Import Kehadiran
                    </button>
                    <a href="{{ route('masar.jamaah.export') }}" class="btn btn-info btn-sm">
                        <i class="ti ti-file-export me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('masar.jamaah.downloadTemplate') }}" class="btn btn-secondary btn-sm">
                        <i class="ti ti-download me-1"></i> Download Template
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Filter Tahun Masuk</label>
                        <select class="form-select" id="filter_tahun_masuk">
                            <option value="">Semua Tahun</option>
                            @for($i = date('Y'); $i >= 2015; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Status Aktif</label>
                        <select class="form-select" id="filter_status">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="non_aktif">Non Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Status Umroh</label>
                        <select class="form-select" id="filter_umroh">
                            <option value="">Semua</option>
                            <option value="1">Sudah Umroh</option>
                            <option value="0">Belum Umroh</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary d-block w-100" id="btnFilter">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>

                <!-- DataTable -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableJamaah" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nomor Jamaah</th>
                                <th>Nama Jamaah</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                                <th>PIN</th>
                                <th>Tahun Masuk</th>
                                <th width="10%">Jumlah Kehadiran</th>
                                <th>Status Umroh</th>
                                <th>Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan data dari mesin -->
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('masar.jamaah.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Jamaah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">File Excel</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            Format: .xlsx, .xls, atau .csv (Max: 5MB)<br>
                            <a href="{{ route('masar.jamaah.downloadTemplate') }}" class="text-primary">
                                <i class="ti ti-download me-1"></i>Download Template Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-upload me-1"></i> Upload & Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Kehadiran -->
<div class="modal fade" id="modalImportKehadiran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formImportKehadiran" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Import Kehadiran Jamaah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Format Excel:</strong> 1 kolom saja dengan header <code>jumlah_kehadiran</code><br>
                        Urutan baris harus sesuai dengan urutan jamaah di database.
                    </div>
                    <div class="mb-3">
                        <label for="file_kehadiran" class="form-label">File Excel</label>
                        <input type="file" class="form-control" id="file_kehadiran" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            Format: .xlsx, .xls, atau .csv (Max: 2MB)<br>
                            <a href="{{ route('masar.jamaah.kehadiran.template') }}" class="text-warning">
                                <i class="ti ti-download me-1"></i>Download Template (sudah ada data jamaah)
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-upload me-1"></i> Upload & Import Kehadiran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('myscript')
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        let table = $('#tableJamaah').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masar.jamaah.index') }}",
                data: function(d) {
                    d.tahun_masuk = $('#filter_tahun_masuk').val();
                    d.status_aktif = $('#filter_status').val();
                    d.status_umroh = $('#filter_umroh').val();
                },
                error: function(xhr, error, code) {
                    console.log('AJAX Error:', xhr, error, code);
                    if (xhr.status === 401) {
                        Swal.fire('Error!', 'Anda harus login terlebih dahulu!', 'error');
                    } else {
                        Swal.fire('Error!', 'Gagal memuat data: ' + xhr.responseText, 'error');
                    }
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nomor_jamaah', name: 'nomor_jamaah'},
                {data: 'nama_jamaah', name: 'nama_jamaah'},
                {data: 'nik', name: 'nik'},
                {data: 'alamat', name: 'alamat'},
                {data: 'pin_fingerprint', name: 'pin_fingerprint', render: function(data) {
                    if (data) {
                        return '<span class="badge bg-info">' + data + '</span>';
                    }
                    return '<span class="text-muted">-</span>';
                }},
                {data: 'tahun_masuk', name: 'tahun_masuk'},
                {data: 'badge_kehadiran', name: 'jumlah_kehadiran', orderable: true, className: 'text-center fw-bold'},
                {data: 'status_umroh_badge', name: 'status_umroh'},
                {data: 'status_aktif_badge', name: 'status_aktif'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[1, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });

        // Filter button
        $('#btnFilter').click(function() {
            table.draw();
        });

        // Toggle Umroh handler
        $(document).on('change', '.toggle-umroh', function() {
            let checkbox = $(this);
            let id = checkbox.data('id');
            let isChecked = checkbox.is(':checked');
            
            $.ajax({
                url: "{{ url('masar/jamaah') }}/" + id + "/toggle-umroh",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update badge
                        let badge = checkbox.closest('div').find('.badge');
                        if (response.status_umroh) {
                            badge.removeClass('bg-secondary').addClass('bg-success');
                            badge.html('<i class="ti ti-plane"></i> Sudah');
                        } else {
                            badge.removeClass('bg-success').addClass('bg-secondary');
                            badge.html('Belum');
                        }
                        
                        // Show toast notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function(xhr) {
                    // Revert checkbox
                    checkbox.prop('checked', !isChecked);
                    Swal.fire('Error!', 'Gagal update status umroh', 'error');
                }
            });
        });

        // Delete button handler
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data jamaah akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('masar/jamaah') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            table.draw();
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

        // Import Kehadiran Form Handler
        $('#formImportKehadiran').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('masar.jamaah.kehadiran.import') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Sedang mengimport data kehadiran',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    $('#modalImportKehadiran').modal('hide');
                    $('#formImportKehadiran')[0].reset();
                    
                    let message = response.message;
                    if (response.errors && response.errors.length > 0) {
                        message += '<br><br><strong>Errors:</strong><br>' + response.errors.join('<br>');
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Berhasil!',
                        html: message,
                        confirmButtonText: 'OK'
                    });
                    
                    table.draw(); // Reload DataTable
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat import';
                    Swal.fire('Error!', errorMsg, 'error');
                }
            });
        });

        // ========================================
        // HANDLER TOMBOL "GET DATA MESIN FINGERSPOT"
        // Mirip dengan sistem Presensi Karyawan
        // ========================================
        $(document).on('click', '.btngetDatamesin', function(e) {
            e.preventDefault();
            
            var pin = $(this).attr("pin");
            var tanggal = $(this).attr("tanggal");
            
            // Show loading animation
            $("#loadmodal").html(`
                <div class="sk-wave sk-primary" style="margin:auto">
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                </div>
            `);
            
            // Open modal
            $("#modal").modal("show");
            $(".modal-title").text("Get Data Mesin Fingerspot - PIN: " + pin);
            
            // AJAX Request ke Fingerspot Cloud API
            $.ajax({
                type: 'POST',
                url: '{{ route("masar.jamaah.getdatamesin") }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    pin_fingerprint: pin,
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    console.log('Response received:', respond);
                    $("#loadmodal").html(respond);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr, status, error);
                    $("#loadmodal").html(`
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>
                            <strong>Error!</strong> Gagal mengambil data dari mesin Fingerspot.
                            <br><br>
                            <strong>Detail Error:</strong> ${error}
                            <br>
                            <strong>Status:</strong> ${xhr.status}
                            <br><br>
                            <small class="text-muted">
                                Pastikan:
                                <ul>
                                    <li>Cloud ID & API Key sudah diatur di Pengaturan Umum</li>
                                    <li>Mesin fingerprint sudah sync ke cloud</li>
                                    <li>Koneksi internet stabil</li>
                                    <li>Jamaah sudah absen di mesin pada tanggal ini</li>
                                </ul>
                            </small>
                        </div>
                    `);
                }
            });
        });
    });
</script>
@endpush
