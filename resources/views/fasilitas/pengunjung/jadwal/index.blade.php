@extends('layouts.app')
@section('titlepage', 'Jadwal Pengunjung')

@section('content')
@section('navigasi')
    <span><a href="{{ route('pengunjung.index') }}">Manajemen Pengunjung</a> / Jadwal</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('pengunjung.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i> Kembali
                        </a>
                        <a href="#" class="btn btn-primary" id="btnTambahJadwal">
                            <i class="fa fa-plus me-2"></i> Tambah Jadwal
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode Jadwal</th>
                                <th>Nama Lengkap</th>
                                <th>Instansi</th>
                                <th>No. Telepon</th>
                                <th>Keperluan</th>
                                <th>Bertemu Dengan</th>
                                <th>Tanggal Kunjungan</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwal as $j)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $j->kode_jadwal }}</td>
                                    <td>{{ $j->nama_lengkap }}</td>
                                    <td>{{ $j->instansi ?? '-' }}</td>
                                    <td>{{ $j->no_telepon }}</td>
                                    <td>{{ $j->keperluan }}</td>
                                    <td>{{ $j->bertemu_dengan ?? '-' }}</td>
                                    <td>{{ $j->tanggal_kunjungan->format('d/m/Y') }}</td>
                                    <td>{{ date('H:i', strtotime($j->waktu_kunjungan)) }}</td>
                                    <td>
                                        @if($j->status == 'terjadwal')
                                            <span class="badge bg-info">Terjadwal</span>
                                        @elseif($j->status == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Batal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @if($j->status == 'terjadwal')
                                                <a href="{{ route('pengunjung.jadwal.checkin', $j->id) }}" 
                                                    class="btn btn-sm btn-success me-1" title="Check-In">
                                                    <i class="fa fa-sign-in"></i>
                                                </a>
                                            @endif
                                            <a href="#" class="btn btn-sm btn-primary me-1 btnEditJadwal" 
                                                data-id="{{ $j->id }}"
                                                data-kode="{{ $j->kode_jadwal }}"
                                                data-nama="{{ $j->nama_lengkap }}"
                                                data-instansi="{{ $j->instansi }}"
                                                data-telepon="{{ $j->no_telepon }}"
                                                data-email="{{ $j->email }}"
                                                data-keperluan="{{ $j->keperluan }}"
                                                data-bertemu="{{ $j->bertemu_dengan }}"
                                                data-tanggal="{{ $j->tanggal_kunjungan->format('Y-m-d') }}"
                                                data-waktu="{{ $j->waktu_kunjungan }}"
                                                data-cabang="{{ $j->cabang_id }}"
                                                data-catatan="{{ $j->catatan }}"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('pengunjung.jadwal.destroy', $j->id) }}" method="POST" class="form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete-jadwal" 
                                                    data-id="{{ $j->id }}" 
                                                    data-nama="{{ $j->nama_lengkap }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada jadwal pengunjung</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="modalJadwal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalJadwalTitle">Tambah Jadwal Pengunjung</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengunjung.jadwal.store') }}" method="POST" id="formJadwal">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="jadwal_id" id="jadwalId">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Instansi/Perusahaan</label>
                                <input type="text" name="instansi" id="instansi" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="no_telepon" id="no_telepon" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Keperluan <span class="text-danger">*</span></label>
                                <input type="text" name="keperluan" id="keperluan" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Bertemu Dengan</label>
                                <input type="text" name="bertemu_dengan" id="bertemu_dengan" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Tanggal Kunjungan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Waktu Kunjungan <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_kunjungan" id="waktu_kunjungan" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Cabang</label>
                                <select name="kode_cabang" id="cabang_id" class="form-select">
                                    <option value="">Pilih Cabang</option>
                                    @foreach($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Tambah Jadwal
        $('#btnTambahJadwal').click(function(e) {
            e.preventDefault();
            $('#modalJadwalTitle').text('Tambah Jadwal Pengunjung');
            $('#formJadwal')[0].reset();
            $('#formMethod').val('POST');
            $('#formJadwal').attr('action', '{{ route("pengunjung.jadwal.store") }}');
            $('#modalJadwal').modal('show');
        });

        // Edit Jadwal
        $('.btnEditJadwal').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            
            $('#modalJadwalTitle').text('Edit Jadwal Pengunjung');
            $('#jadwalId').val(id);
            $('#nama_lengkap').val($(this).data('nama'));
            $('#instansi').val($(this).data('instansi'));
            $('#no_telepon').val($(this).data('telepon'));
            $('#email').val($(this).data('email'));
            $('#keperluan').val($(this).data('keperluan'));
            $('#bertemu_dengan').val($(this).data('bertemu'));
            $('#tanggal_kunjungan').val($(this).data('tanggal'));
            $('#waktu_kunjungan').val($(this).data('waktu'));
            $('#cabang_id').val($(this).data('cabang'));
            $('#catatan').val($(this).data('catatan'));
            
            $('#formMethod').val('PUT');
            $('#formJadwal').attr('action', '/pengunjung/jadwal/' + id);
            $('#modalJadwal').modal('show');
        });

        // Delete confirmation
        $('.btn-delete-jadwal').click(function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            
            Swal.fire({
                title: 'Hapus Jadwal?',
                html: `Jadwal kunjungan <strong>${nama}</strong> akan dihapus!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });

        // Success notification
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session("success") }}'
            });
        @endif

        // Error notification
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Validation errors
        @if($errors->any())
            let errorList = '<ul class="text-start mb-0">';
            @foreach($errors->all() as $error)
                errorList += '<li>{{ $error }}</li>';
            @endforeach
            errorList += '</ul>';
            
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errorList,
                confirmButtonColor: '#3085d6'
            });
        @endif
    });
</script>
@endpush
