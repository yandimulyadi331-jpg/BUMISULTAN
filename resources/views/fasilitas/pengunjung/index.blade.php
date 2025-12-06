@extends('layouts.app')
@section('titlepage', 'Manajemen Pengunjung')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Pengunjung</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="#" class="btn btn-primary" id="btnCheckinPengunjung">
                            <i class="fa fa-sign-in me-2"></i> Check-In Pengunjung
                        </a>
                        <a href="{{ route('pengunjung.jadwal.index') }}" class="btn btn-info">
                            <i class="fa fa-calendar me-2"></i> Jadwal Pengunjung
                        </a>
                        <a href="{{ route('pengunjung.qrcode') }}" class="btn btn-success">
                            <i class="fa fa-qrcode me-2"></i> QR Code
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('pengunjung.exportPDF') }}" class="btn btn-danger" target="_blank">
                            <i class="ti ti-file-download me-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Pengunjung</h5>
                                <h2>{{ $pengunjung->count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Check-In</h5>
                                <h2>{{ $pengunjung->where('status', 'checkin')->count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body">
                                <h5>Check-Out</h5>
                                <h2>{{ $pengunjung->where('status', 'checkout')->count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5>Hari Ini</h5>
                                <h2>{{ $pengunjung->where('waktu_checkin', '>=', now()->startOfDay())->count() }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Foto</th>
                                        <th>Nama Lengkap</th>
                                        <th>Instansi</th>
                                        <th>No. Telepon</th>
                                        <th>Keperluan</th>
                                        <th>Bertemu Dengan</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengunjung as $p)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $p->kode_pengunjung }}</td>
                                            <td>
                                                @if($p->foto)
                                                    <img src="{{ Storage::url($p->foto) }}" 
                                                        alt="Foto Pengunjung" 
                                                        class="img-thumbnail foto-preview" 
                                                        style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                        data-foto="{{ Storage::url($p->foto) }}"
                                                        data-title="{{ $p->nama_lengkap }}">
                                                @else
                                                    <span class="badge bg-secondary">No Photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $p->nama_lengkap }}</td>
                                            <td>{{ $p->instansi ?? '-' }}</td>
                                            <td>{{ $p->no_telepon }}</td>
                                            <td>{{ $p->keperluan }}</td>
                                            <td>{{ $p->bertemu_dengan ?? '-' }}</td>
                                            <td>{{ $p->waktu_checkin->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($p->waktu_checkout)
                                                    {{ $p->waktu_checkout->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="badge bg-warning">Belum Check-Out</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($p->status == 'checkin')
                                                    <span class="badge bg-success">Check-In</span>
                                                @else
                                                    <span class="badge bg-secondary">Check-Out</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if($p->status == 'checkin')
                                                        <button type="button" class="btn btn-sm btn-warning me-1 btn-checkout" 
                                                            data-id="{{ $p->id }}" 
                                                            data-nama="{{ $p->nama_lengkap }}" 
                                                            title="Check-Out">
                                                            <i class="fa fa-sign-out"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('pengunjung.show', $p->id) }}" 
                                                        class="btn btn-sm btn-info me-1" title="Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pengunjung.edit', $p->id) }}" 
                                                        class="btn btn-sm btn-primary me-1" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                        data-id="{{ $p->id }}" 
                                                        data-nama="{{ $p->nama_lengkap }}" 
                                                        title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data pengunjung</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Check-In -->
<div class="modal fade" id="modalCheckin" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Check-In Pengunjung</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengunjung.checkin') }}" method="POST" enctype="multipart/form-data" id="formCheckin">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Instansi/Perusahaan</label>
                                <input type="text" name="instansi" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Identitas (KTP/SIM)</label>
                                <input type="text" name="no_identitas" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="no_telepon" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Cabang</label>
                                <select name="kode_cabang" class="form-select">
                                    <option value="">Pilih Cabang</option>
                                    @foreach($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Keperluan <span class="text-danger">*</span></label>
                                <input type="text" name="keperluan" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Bertemu Dengan</label>
                                <input type="text" name="bertemu_dengan" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Foto Pengunjung</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i> Check-In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoTitle">Foto Pengunjung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fotoPreview" src="" class="img-fluid" alt="Foto">
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Open Check-In Modal
        $('#btnCheckinPengunjung').click(function(e) {
            e.preventDefault();
            $('#modalCheckin').modal('show');
        });

        // Preview foto
        $('.foto-preview').click(function() {
            var foto = $(this).data('foto');
            var title = $(this).data('title');
            $('#fotoPreview').attr('src', foto);
            $('#fotoTitle').text('Foto - ' + title);
            $('#modalFoto').modal('show');
        });

        // Checkout confirmation
        $('.btn-checkout').click(function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            
            Swal.fire({
                title: 'Konfirmasi Check-Out',
                html: `Apakah Anda yakin ingin check-out pengunjung:<br><strong>${nama}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-sign-out me-1"></i> Ya, Check-Out',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': '/pengunjung/' + id + '/checkout'
                    });
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));
                    $('body').append(form);
                    form.submit();
                }
            });
        });

        // Delete confirmation
        $('.btn-delete').click(function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            
            Swal.fire({
                title: 'Hapus Data Pengunjung?',
                html: `Data pengunjung <strong>${nama}</strong> akan dihapus secara permanen!`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': '/pengunjung/' + id + '/delete'
                    });
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
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
