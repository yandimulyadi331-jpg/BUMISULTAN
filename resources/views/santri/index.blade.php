@extends('layouts.app')
@section('titlepage', 'Data Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span>Manajemen Saung Santri / Data Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-users me-2"></i> Data Santri Saung Santri</h4>
                    <div>
                        <a href="{{ route('santri.download-formulir') }}" class="btn btn-success btn-sm" title="Download Formulir Pendaftaran Santri Baru">
                            <i class="ti ti-download me-1"></i> Download Formulir Pendaftaran
                        </a>
                        <a href="{{ route('santri.create') }}" class="btn btn-light btn-sm">
                            <i class="ti ti-plus me-1"></i> Tambah Santri
                        </a>
                        <a href="{{ route('santri.export-pdf', request()->all()) }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('santri.index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-lg-3">
                                    <input type="text" name="search" class="form-control" 
                                        placeholder="🔍 Cari NIS/Nama/NIK..." 
                                        value="{{ Request('search') }}">
                                </div>
                                <div class="col-lg-2">
                                    <select name="status_santri" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="aktif" {{ Request('status_santri') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="cuti" {{ Request('status_santri') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                        <option value="alumni" {{ Request('status_santri') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                        <option value="keluar" {{ Request('status_santri') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select name="jenis_kelamin" class="form-select">
                                        <option value="">Semua Gender</option>
                                        <option value="L" {{ Request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ Request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select name="tahun_masuk" class="form-select">
                                        <option value="">Semua Tahun</option>
                                        @foreach($tahunMasukList as $tahun)
                                            <option value="{{ $tahun }}" {{ Request('tahun_masuk') == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('santri.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">NIS</th>
                                <th>Foto</th>
                                <th>Nama Lengkap</th>
                                <th>JK</th>
                                <th>Tahun Masuk</th>
                                <th>Hafalan</th>
                                <th>Status</th>
                                <th>Status Ijin</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santri as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $santri->firstItem() + $index }}</td>
                                    <td><strong>{{ $item->nis }}</strong></td>
                                    <td class="text-center">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/santri/'.$item->foto) }}" 
                                                alt="{{ $item->nama_lengkap }}" 
                                                class="rounded-circle" 
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                                style="width: 40px; height: 40px;">
                                                <i class="ti ti-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama_lengkap }}</strong>
                                        @if($item->nama_panggilan)
                                            <br><small class="text-muted">({{ $item->nama_panggilan }})</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->jenis_kelamin == 'L')
                                            <span class="badge bg-info"><i class="ti ti-man"></i> L</span>
                                        @else
                                            <span class="badge bg-pink"><i class="ti ti-woman"></i> P</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->tahun_masuk }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" 
                                                        role="progressbar" 
                                                        style="width: {{ $item->persentase_hafalan }}%"
                                                        aria-valuenow="{{ $item->persentase_hafalan }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                        {{ $item->jumlah_juz_hafalan }} Juz
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="ms-2 text-muted">{{ number_format($item->persentase_hafalan, 0) }}%</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status_santri == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($item->status_santri == 'cuti')
                                            <span class="badge bg-warning">Cuti</span>
                                        @elseif($item->status_santri == 'alumni')
                                            <span class="badge bg-info">Alumni</span>
                                        @else
                                            <span class="badge bg-danger">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $ijinAktif = null;
                                            if (method_exists($item, 'ijinSantri') && $item->relationLoaded('ijinSantri')) {
                                                $ijinAktif = $item->ijinSantri->first();
                                            }
                                        @endphp
                                        @if($ijinAktif)
                                            <span class="badge bg-warning" 
                                                  data-bs-toggle="tooltip" 
                                                  data-bs-placement="top" 
                                                  title="Ijin: {{ $ijinAktif->alasan_ijin }} | Kembali: {{ $ijinAktif->tanggal_kembali_rencana->format('d/m/Y') }}">
                                                <i class="ti ti-home-off"></i> Pulang
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                s/d {{ $ijinAktif->tanggal_kembali_rencana->format('d/m') }}
                                            </small>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="ti ti-home-check"></i> Di Pesantren
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('santri.show', $item->id) }}" 
                                                class="btn btn-sm btn-info" 
                                                title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('santri.cetak-qr', $item->id) }}" 
                                                class="btn btn-sm btn-success" 
                                                title="Cetak QR Code"
                                                target="_blank">
                                                <i class="ti ti-qrcode"></i>
                                            </a>
                                            <a href="{{ route('santri.edit', $item->id) }}" 
                                                class="btn btn-sm btn-warning" 
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('santri.destroy', $item->id) }}" 
                                                method="POST" 
                                                class="d-inline form-delete"
                                                data-nama="{{ $item->nama_lengkap }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="ti ti-database-off" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p class="mt-2 text-muted">Belum ada data santri</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $santri->firstItem() ?? 0 }} - {{ $santri->lastItem() ?? 0 }} dari {{ $santri->total() }} data
                    </div>
                    <div>
                        {{ $santri->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // SweetAlert2 untuk konfirmasi hapus
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('.form-delete');
        const namaSantri = form.data('nama');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus data santri:<br><strong>${namaSantri}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="ti ti-x me-1"></i> Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form
                form.submit();
            }
        });
    });

    // Auto hide alert setelah 3 detik
    @if(session('success') || session('error'))
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    @endif
</script>
@endpush
@endsection
