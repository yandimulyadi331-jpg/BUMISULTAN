@extends('layouts.app')
@section('titlepage', 'History & Tracking Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / History</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><i class="fa fa-history me-2"></i>History Inventaris</h4>
                    </div>
                    <div>
                        <a href="{{ route('history-inventaris.export-pdf') }}" class="btn btn-danger" target="_blank">
                            <i class="fa fa-file-pdf me-2"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('history-inventaris.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <x-input-with-icon label="Cari Inventaris / Karyawan" value="{{ Request('search') }}" 
                                        name="search" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="jenis_aktivitas" class="form-select">
                                            <option value="">Semua Aktivitas</option>
                                            <option value="peminjaman" {{ Request('jenis_aktivitas') == 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                                            <option value="pengembalian" {{ Request('jenis_aktivitas') == 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="date" name="tanggal_dari" class="form-control" value="{{ Request('tanggal_dari') }}" placeholder="Dari Tanggal">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ Request('tanggal_sampai') }}" placeholder="Sampai Tanggal">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fa fa-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('history-inventaris.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>Inventaris</th>
                                <th>Jenis Aktivitas</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Nama Karyawan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $item)
                            @php
                                // Tentukan class untuk row berdasarkan jenis aktivitas
                                $rowClass = '';
                                if($item->jenis_aktivitas == 'peminjaman' || $item->jenis_aktivitas == 'pinjam') {
                                    $rowClass = 'table-warning'; // Kuning untuk peminjaman
                                } elseif($item->jenis_aktivitas == 'pengembalian' || $item->jenis_aktivitas == 'kembali') {
                                    $rowClass = 'table-success'; // Hijau untuk pengembalian
                                }
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>{{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}</td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <strong>{{ $item->inventaris->nama_barang }}</strong><br>
                                    <small class="text-muted">{{ $item->inventaris->kode_inventaris }}</small>
                                </td>
                                <td>
                                    @if($item->jenis_aktivitas == 'peminjaman' || $item->jenis_aktivitas == 'pinjam')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fa fa-hand-holding me-1"></i>Peminjaman
                                        </span>
                                    @elseif($item->jenis_aktivitas == 'pengembalian' || $item->jenis_aktivitas == 'kembali')
                                        <span class="badge bg-success">
                                            <i class="fa fa-undo me-1"></i>Pengembalian
                                        </span>
                                    @elseif($item->jenis_aktivitas == 'tambah')
                                        <span class="badge bg-primary">
                                            <i class="fa fa-plus me-1"></i>Tambah Stok
                                        </span>
                                    @elseif($item->jenis_aktivitas == 'update')
                                        <span class="badge bg-info">
                                            <i class="fa fa-edit me-1"></i>Update Data
                                        </span>
                                    @elseif($item->jenis_aktivitas == 'rusak')
                                        <span class="badge bg-danger">
                                            <i class="fa fa-exclamation-triangle me-1"></i>Rusak
                                        </span>
                                    @elseif($item->jenis_aktivitas == 'maintenance')
                                        <span class="badge bg-secondary">
                                            <i class="fa fa-tools me-1"></i>Maintenance
                                        </span>
                                    @else
                                        <span class="badge bg-dark">
                                            {{ ucfirst($item->jenis_aktivitas) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ Str::limit($item->keterangan, 50) }}
                                </td>
                                <td class="text-center">
                                    @if($item->jumlah_perubahan > 0)
                                        <span class="badge bg-success">+{{ $item->jumlah_perubahan }}</span>
                                    @elseif($item->jumlah_perubahan < 0)
                                        <span class="badge bg-danger">{{ $item->jumlah_perubahan }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->karyawan)
                                        <strong>{{ $item->karyawan->nama }}</strong><br>
                                        <small class="text-muted">NIK: {{ $item->karyawan->nik }}</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('history-inventaris.show', $item->id) }}" class="btn btn-sm btn-info me-1" title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <form action="{{ route('history-inventaris.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-confirm" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 3000
        });
    @endif

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus history ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection
