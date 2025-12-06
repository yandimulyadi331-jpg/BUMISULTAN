@extends('layouts.app')
@section('titlepage', 'Rekap Kehadiran Jamaah')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                @include('majlistaklim.partials.navigation')
                <h2 class="page-title">Rekap Kehadiran Jamaah</h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <a href="{{ route('majlistaklim.kehadiran.dashboard') }}" class="btn btn-info">
                        <i class="ti ti-chart-line me-1"></i> Dashboard Statistik
                    </a>
                    <a href="{{ route('majlistaklim.kehadiran.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Tambah Rekap Kehadiran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Rekap Kehadiran</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter" id="tableRekapKehadiran">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Hadir</th>
                                        <th>Persentase</th>
                                        <th>Keterangan</th>
                                        <th>Dicatat Oleh</th>
                                        <th>Aksi</th>
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
</div>
@endsection

@push('myscript')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#tableRekapKehadiran').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('majlistaklim.kehadiran.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'tanggal_formatted', name: 'tanggal'},
            {data: 'info_kehadiran', name: 'jumlah_hadir'},
            {data: 'persentase_badge', name: 'persentase'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'creator.name', name: 'creator.name', defaultContent: '-'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ],
        order: [[1, 'desc']]
    });

    // Delete handler
    $('#tableRekapKehadiran').on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data rekap kehadiran akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('majlistaklim.kehadiran.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Terjadi kesalahan', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
