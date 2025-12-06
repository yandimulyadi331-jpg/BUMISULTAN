@extends('layouts.app')
@section('titlepage', 'Undian Umroh')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> Undian Umroh
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-trophy me-2"></i>Undian Umroh Majlis Ta'lim</h5>
                <div>
                    <a href="{{ route('masar.undian.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i> Buat Undian Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Filter Tahun</label>
                        <select class="form-select" id="filter_tahun">
                            <option value="">Semua Tahun</option>
                            @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Status</label>
                        <select class="form-select" id="filter_status">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
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
                    <table class="table table-bordered table-hover" id="tableUndian" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Undian</th>
                                <th>Tanggal</th>
                                <th>Min. Kehadiran</th>
                                <th>Jumlah Pemenang</th>
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

@endsection

@push('myscript')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        let table = $('#tableUndian').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masar.undian.index') }}",
                data: function(d) {
                    d.tahun = $('#filter_tahun').val();
                    d.status = $('#filter_status').val();
                },
                error: function(xhr, error, code) {
                    console.log('Ajax error:', xhr.responseText);
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    }
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_undian', name: 'nama_undian'},
                {data: 'tanggal_undian', name: 'tanggal_undian'},
                {data: 'min_kehadiran', name: 'min_kehadiran'},
                {data: 'jumlah_pemenang', name: 'jumlah_pemenang'},
                {data: 'status_badge', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[2, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });

        // Filter button
        $('#btnFilter').click(function() {
            table.draw();
        });

        // Undi button handler
        $(document).on('click', '.undi-btn', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            
            Swal.fire({
                title: 'Mulai Undian?',
                text: "Undian " + nama + " akan segera dimulai!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Mulai Undian!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('masar/undian') }}/" + id + "/undi";
                }
            });
        });

        // Delete button handler
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data undian dan pemenang akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('masar/undian') }}/" + id,
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
    });
</script>
@endpush
