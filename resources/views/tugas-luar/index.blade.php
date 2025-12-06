@extends('layouts.app')
@section('titlepage', 'Tugas Luar Karyawan')

@section('content')
@section('navigasi')
    <span>Tugas Luar Karyawan</span>
@endsection

<div class="row mt-3">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-briefcase me-2"></i>
                    Manajemen Tugas Luar
                </h5>
                <a href="{{ route('tugas-luar.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Tambah Tugas Luar
                </a>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form action="{{ route('tugas-luar.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <x-input-with-icon label="Tanggal" icon="ti ti-calendar" name="tanggal" 
                                datepicker="flatpickr-date" value="{{ request('tanggal') }}" />
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Sedang Keluar</option>
                                    <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Sudah Kembali</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="ti ti-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Tugas</th>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Tujuan</th>
                                <th>Waktu Keluar</th>
                                <th>Waktu Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tugasLuar as $index => $tugas)
                                @php
                                    // karyawan_list sudah berupa array karena cast di model
                                    $karyawanIds = is_array($tugas->karyawan_list) ? $tugas->karyawan_list : json_decode($tugas->karyawan_list, true) ?? [];
                                    $karyawanList = \App\Models\Karyawan::whereIn('nik', $karyawanIds)->get();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $tugas->kode_tugas }}</strong></td>
                                    <td>{{ $tugas->tanggal->format('d/m/Y') }}</td>
                                    <td>
                                        @foreach($karyawanList as $k)
                                            <span class="badge bg-label-primary mb-1">{{ $k->nama_karyawan }}</span>
                                        @endforeach
                                        <br><small class="text-muted">{{ count($karyawanList) }} karyawan</small>
                                    </td>
                                    <td>
                                        {{ $tugas->tujuan }}<br>
                                        @if($tugas->keterangan)
                                            <small class="text-muted">{{ Str::limit($tugas->keterangan, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-danger">
                                            <i class="ti ti-clock"></i> {{ $tugas->waktu_keluar }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($tugas->waktu_kembali)
                                            <span class="badge bg-label-success">
                                                <i class="ti ti-clock"></i> {{ $tugas->waktu_kembali }}
                                            </span>
                                        @else
                                            <span class="badge bg-label-secondary">Belum Kembali</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tugas->status == 'pending')
                                            <span class="badge bg-warning">
                                                <i class="ti ti-clock"></i> Menunggu Persetujuan
                                            </span>
                                        @elseif($tugas->status == 'keluar')
                                            <span class="badge bg-danger">
                                                <i class="ti ti-briefcase"></i> Sedang Keluar
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="ti ti-check"></i> Sudah Kembali
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tugas->status == 'pending')
                                            <form action="{{ route('tugas-luar.approve', $tugas->id) }}" method="POST" class="d-inline form-approve">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-primary btn-approve" 
                                                    data-id="{{ $tugas->id }}">
                                                    <i class="ti ti-check"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('tugas-luar.reject', $tugas->id) }}" method="POST" class="d-inline form-reject">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-warning btn-reject" 
                                                    data-id="{{ $tugas->id }}">
                                                    <i class="ti ti-x"></i> Tolak
                                                </button>
                                            </form>
                                        @elseif($tugas->status == 'keluar')
                                            <form action="{{ route('tugas-luar.kembali', $tugas->id) }}" method="POST" class="d-inline form-kembali">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-success btn-kembali" 
                                                    data-id="{{ $tugas->id }}">
                                                    <i class="ti ti-check"></i> Kembali
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('tugas-luar.destroy', $tugas->id) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                data-id="{{ $tugas->id }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="ti ti-briefcase-off" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Tidak ada data tugas luar</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}'
        });
    @endif

    // Handler tombol Approve
    $(document).on('click', '.btn-approve', function(e) {
        e.preventDefault();
        const form = $(this).closest('.form-approve');
        
        Swal.fire({
            title: 'Setujui Pengajuan?',
            text: "Pengajuan tugas luar ini akan disetujui",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Handler tombol Reject
    $(document).on('click', '.btn-reject', function(e) {
        e.preventDefault();
        const form = $(this).closest('.form-reject');
        
        Swal.fire({
            title: 'Tolak Pengajuan?',
            text: "Pengajuan tugas luar ini akan ditolak dan dihapus",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Handler tombol Kembali
    $(document).on('click', '.btn-kembali', function(e) {
        e.preventDefault();
        const form = $(this).closest('.form-kembali');
        
        Swal.fire({
            title: 'Tandai Kembali?',
            text: "Karyawan sudah kembali dari tugas luar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Sudah Kembali',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Handler tombol Delete
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('.form-delete');
        
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data tugas luar ini akan dihapus permanen!",
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
