@extends('layouts.app')

@section('title', 'Distribusi Hadiah - MASAR')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('masar.index') }}">MASAR</a>
            </li>
            <li class="breadcrumb-item active">Distribusi Hadiah</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-3">
        <div class="col-md-6">
            <h4 class="fw-bold">Distribusi Hadiah Yayasan Masar</h4>
            <p class="text-muted">Manajemen dan pencatatan distribusi hadiah kepada jamaah</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('masar.distribusi.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i> Tambah Distribusi
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-gift text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-0">Total Distribusi</p>
                            <h4 class="mb-0" id="total-distribusi">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-check text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-0">Diterima</p>
                            <h4 class="mb-0" id="total-diterima">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-clock text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-0">Pending</p>
                            <h4 class="mb-0" id="total-pending">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-x text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-0">Ditolak</p>
                            <h4 class="mb-0" id="total-ditolak">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" class="form-control" id="search" placeholder="Nama jamaah, nomor distribusi...">
                </div>
                <div class="col-md-2">
                    <label for="metode" class="form-label">Metode Distribusi</label>
                    <select class="form-control" id="metode_distribusi">
                        <option value="">Semua Metode</option>
                        <option value="langsung">Langsung</option>
                        <option value="undian">Undian</option>
                        <option value="prestasi">Prestasi</option>
                        <option value="kehadiran">Kehadiran</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status_distribusi">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="tanggal_dari" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_dari">
                </div>
                <div class="col-md-2">
                    <label for="tanggal_sampai" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_sampai">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-secondary w-100" id="reset-filter">
                        <i class="ti ti-refresh"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Distribusi Hadiah</h5>
            <a href="{{ route('masar.distribusi.exportPDF') }}" class="btn btn-sm btn-outline-secondary">
                <i class="ti ti-file-pdf me-1"></i> Export PDF
            </a>
        </div>
        <div class="table-responsive">
            <table id="distribusi-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Distribusi</th>
                        <th>Nama Jamaah</th>
                        <th>Hadiah</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus distribusi hadiah ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let deleteId = null;
    let table;

    $(document).ready(function () {
        // Initialize DataTable
        initializeTable();

        // Load statistik
        loadStatistik();

        // Filter events
        $('#search, #metode_distribusi, #status_distribusi, #tanggal_dari, #tanggal_sampai').on('change keyup', function () {
            table.draw();
        });

        // Reset filter
        $('#reset-filter').on('click', function () {
            $('#filter-form')[0].reset();
            table.draw();
        });

        // Delete button click
        $(document).on('click', '.delete-btn', function () {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#confirm-delete').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: '{{ route("masar.distribusi.destroy", ":id") }}'.replace(':id', deleteId),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#deleteModal').modal('hide');
                                table.draw();
                                loadStatistik();
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    function initializeTable() {
        table = $('#distribusi-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("masar.distribusi.index") }}',
                data: function (d) {
                    d.search = $('#search').val();
                    d.metode_distribusi = $('#metode_distribusi').val();
                    d.status_distribusi = $('#status_distribusi').val();
                    d.tanggal_dari = $('#tanggal_dari').val();
                    d.tanggal_sampai = $('#tanggal_sampai').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nomor_distribusi', name: 'nomor_distribusi'},
                {data: 'nama_jamaah', name: 'nama_jamaah'},
                {data: 'nama_hadiah', name: 'nama_hadiah'},
                {data: 'jumlah', name: 'jumlah'},
                {data: 'metode_badge', name: 'metode_distribusi', orderable: false},
                {data: 'status_badge', name: 'status_distribusi', orderable: false},
                {data: 'tanggal_distribusi', name: 'tanggal_distribusi'},
                {data: 'petugas_distribusi', name: 'petugas_distribusi'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            language: {
                url: '{{ asset("js/datatables.id.json") }}'
            }
        });
    }

    function loadStatistik() {
        $.ajax({
            url: '{{ route("masar.distribusi.statistik") }}',
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    $('#total-distribusi').text(response.data.total_distribusi);
                    $('#total-diterima').text(response.data.total_diterima);
                    $('#total-pending').text(response.data.total_pending);
                    $('#total-ditolak').text(response.data.total_ditolak);
                }
            }
        });
    }
</script>
@endsection
