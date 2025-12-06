@extends('layouts.app')
@section('titlepage', 'Data Peminjaman Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Peminjaman</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('peminjaman-inventaris.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i> Tambah Peminjaman
                        </a>
                        <a href="{{ route('peminjaman-inventaris.export-pdf') }}" class="btn btn-danger" target="_blank">
                            <i class="fa fa-file-pdf me-2"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('peminjaman-inventaris.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <x-input-with-icon label="Cari Kode / Peminjam" value="{{ Request('search') }}" 
                                        name="search" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="disetujui" {{ Request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="ditolak" {{ Request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="dipinjam" {{ Request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                            <option value="dikembalikan" {{ Request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="date" name="tanggal_dari" class="form-control" value="{{ Request('tanggal_dari') }}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ Request('tanggal_sampai') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fa fa-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('peminjaman-inventaris.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Inventaris</th>
                                <th>Peminjam</th>
                                <th>Jumlah</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $item)
                            <tr class="{{ $item->isTerlambat() ? 'table-danger' : '' }}">
                                <td>{{ $loop->iteration + ($peminjaman->currentPage() - 1) * $peminjaman->perPage() }}</td>
                                <td><strong>{{ $item->kode_peminjaman }}</strong></td>
                                <td>
                                    <strong>{{ $item->inventaris->nama_barang }}</strong><br>
                                    <small class="text-muted">{{ $item->inventaris->kode_inventaris }}</small>
                                </td>
                                <td>
                                    @if($item->karyawan)
                                        <strong>{{ $item->karyawan->nama_lengkap }}</strong><br>
                                    @else
                                        <strong>{{ $item->nama_peminjam }}</strong><br>
                                    @endif
                                    <small class="text-muted">{{ $item->karyawan->nik }}</small>
                                </td>
                                <td>{{ $item->jumlah_pinjam }} {{ $item->inventaris->satuan }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali_rencana)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($item->status == 'disetujui')
                                        <span class="badge bg-info">Disetujui</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="badge bg-primary">Dipinjam</span>
                                        @if($item->isTerlambat())
                                            <span class="badge bg-danger ms-1">TERLAMBAT!</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('peminjaman-inventaris.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if($item->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-success btn-approve" 
                                                data-id="{{ $item->id }}" title="Setujui">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger btn-reject" 
                                                data-id="{{ $item->id }}" title="Tolak">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <a href="{{ route('peminjaman-inventaris.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($item->status == 'dipinjam')
                                            <a href="{{ route('pengembalian-inventaris.create', $item->id) }}" class="btn btn-sm btn-success" title="Kembalikan">
                                                <i class="fa fa-undo"></i> Kembalikan
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data peminjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $peminjaman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    $(document).on('click', '.btn-approve', function() {
        let id = $(this).data('id');
        
        Swal.fire({
            title: 'Setujui Peminjaman?',
            text: "Peminjaman akan disetujui dan status berubah menjadi 'Dipinjam'",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/peminjaman-inventaris/${id}/setujui`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });

    $(document).on('click', '.btn-reject', function() {
        let id = $(this).data('id');
        
        Swal.fire({
            title: 'Tolak Peminjaman?',
            text: 'Masukkan alasan penolakan',
            input: 'textarea',
            inputPlaceholder: 'Alasan penolakan...',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan harus diisi!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/peminjaman-inventaris/${id}/tolak`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        alasan_penolakan: result.value
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 3000
        });
    @endif
</script>
@endpush
@endsection
