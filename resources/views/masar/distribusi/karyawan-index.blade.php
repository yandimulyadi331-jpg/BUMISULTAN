@extends('layouts.app')

@section('title', 'Distribusi Hadiah - MASAR Karyawan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('masar.karyawan.index') }}">MASAR</a>
            </li>
            <li class="breadcrumb-item active">Distribusi Hadiah</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-3">
        <div class="col-md-6">
            <h4 class="fw-bold">Distribusi Hadiah Yayasan Masar</h4>
            <p class="text-muted">Daftar distribusi hadiah yang telah diterima</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('masar.karyawan.distribusi.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i> Catat Distribusi
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" class="form-control" id="search" placeholder="Nama jamaah, nomor distribusi, nama hadiah...">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="button" class="btn btn-secondary w-100" id="reset-filter">
                        <i class="ti ti-refresh"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Distribusi Hadiah</h5>
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
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let table;

    $(document).ready(function () {
        // Initialize DataTable
        initializeTable();

        // Filter events
        $('#search').on('keyup', function () {
            table.draw();
        });

        // Reset filter
        $('#reset-filter').on('click', function () {
            $('#filter-form')[0].reset();
            table.draw();
        });
    });

    function initializeTable() {
        table = $('#distribusi-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("masar.karyawan.distribusi.index") }}',
                data: function (d) {
                    d.search = $('#search').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nomor_distribusi', name: 'nomor_distribusi'},
                {data: 'nama_jamaah', name: 'nama_jamaah'},
                {data: 'nama_hadiah', name: 'nama_hadiah'},
                {data: 'jumlah', name: 'jumlah'},
                {data: 'metode_badge', name: 'metode_distribusi', orderable: false},
                {data: 'tanggal_distribusi', name: 'tanggal_distribusi'},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<a href="' + '{{ route("masar.karyawan.distribusi.show", ":id") }}'.replace(':id', row.id) + '" class="btn btn-sm btn-info"><i class="ti ti-eye"></i></a>';
                    }
                }
            ],
            language: {
                url: '{{ asset("js/datatables.id.json") }}'
            }
        });
    }
</script>
@endsection
