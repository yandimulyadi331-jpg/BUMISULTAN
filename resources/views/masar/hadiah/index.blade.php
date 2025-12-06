@extends('layouts.app')
@section('titlepage', 'Manajemen Hadiah Majlis Ta\'lim')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> Manajemen Hadiah
@endsection

<!-- Navigation Tabs -->
@include('masar.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-gift me-2"></i>Manajemen Hadiah Majlis Ta'lim</h5>
                <div>
                    <a href="{{ route('masar.hadiah.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i> Tambah Hadiah
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Filter Jenis Hadiah</label>
                        <select class="form-select" id="filter_jenis">
                            <option value="">Semua Jenis</option>
                            <option value="sarung">Sarung</option>
                            <option value="peci">Peci</option>
                            <option value="gamis">Gamis</option>
                            <option value="mukena">Mukena</option>
                            <option value="tasbih">Tasbih</option>
                            <option value="sajadah">Sajadah</option>
                            <option value="al_quran">Al-Qur'an</option>
                            <option value="buku">Buku</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Status</label>
                        <select class="form-select" id="filter_status">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="habis">Habis</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
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
                    <table class="table table-bordered table-hover" id="tableHadiah" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Hadiah</th>
                                <th>Nama Hadiah</th>
                                <th>Jenis</th>
                                <th>Ukuran</th>
                                <th>Stok</th>
                                <th>Nilai</th>
                                <th>Status</th>
                                <th width="10%">Aksi</th>
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
        let table = $('#tableHadiah').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masar.hadiah.index') }}",
                data: function(d) {
                    d.jenis_hadiah = $('#filter_jenis').val();
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
                {data: 'kode_hadiah', name: 'kode_hadiah'},
                {data: 'nama_hadiah', name: 'nama_hadiah'},
                {data: 'jenis_hadiah', name: 'jenis_hadiah'},
                {data: 'ukuran', name: 'ukuran'},
                {data: 'stok_info', name: 'stok_tersedia', orderable: true},
                {data: 'nilai_hadiah', name: 'nilai_hadiah', 
                    render: function(data) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {data: 'status_badge', name: 'status'},
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

        // Delete button handler
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data hadiah akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('masar/hadiah') }}/" + id,
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
