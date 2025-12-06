@extends('layouts.app')
@section('titlepage', 'Manajemen Administrasi')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Administrasi</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white mb-0">
                            <i class="ti ti-file-text me-2"></i>Data Administrasi Perusahaan
                        </h5>
                    </div>
                    <div>
                        <a href="{{ route('administrasi.exportAllPdf') }}" class="btn btn-light me-2" target="_blank">
                            <i class="ti ti-download me-2"></i> Export PDF
                        </a>
                        <a href="{{ route('administrasi.create') }}" class="btn btn-light">
                            <i class="ti ti-plus me-2"></i> Tambah Administrasi
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <form action="{{ route('administrasi.index') }}" method="GET">
                            <div class="row g-2">
                                <div class="col-lg-3 col-sm-12">
                                    <x-input-with-icon label="Cari Kode / Perihal / Nomor" value="{{ Request('search') }}" 
                                        name="search" icon="ti ti-search" placeholder="Ketik untuk mencari..." />
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">Jenis Administrasi</label>
                                        <select name="jenis_administrasi" class="form-select">
                                            <option value="">Semua Jenis</option>
                                            @foreach($jenisAdministrasi as $key => $value)
                                                <option value="{{ $key }}" {{ Request('jenis_administrasi') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="proses" {{ Request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                            <option value="selesai" {{ Request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="ditolak" {{ Request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="expired" {{ Request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">Prioritas</label>
                                        <select name="prioritas" class="form-select">
                                            <option value="">Semua Prioritas</option>
                                            <option value="rendah" {{ Request('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                            <option value="normal" {{ Request('prioritas') == 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="tinggi" {{ Request('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                            <option value="urgent" {{ Request('prioritas') == 'urgent' ? 'selected' : '' }}>URGENT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <label class="form-label small">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fa fa-search me-1"></i> Cari
                                        </button>
                                        <a href="{{ route('administrasi.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-refresh me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 120px;">Kode</th>
                                <th>Jenis</th>
                                <th>Nomor Surat</th>
                                <th>Perihal</th>
                                <th>Tanggal</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($administrasi as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($administrasi->currentPage() - 1) * $administrasi->perPage() }}</td>
                                <td>
                                    <strong class="text-primary">{{ $item->kode_administrasi }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->getJenisAdministrasiColor() }}">
                                        <i class="{{ $item->getJenisAdministrasiIcon() }} me-1"></i>
                                        {{ $item->getJenisAdministrasiLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $item->nomor_surat ?? '-' }}</span>
                                </td>
                                <td>
                                    <strong>{{ Str::limit($item->perihal, 50) }}</strong>
                                    @if($item->isMasuk())
                                        <br><small class="text-muted">
                                            <i class="ti ti-user me-1"></i>Dari: {{ $item->pengirim ?? '-' }}
                                        </small>
                                    @elseif($item->isKeluar())
                                        <br><small class="text-muted">
                                            <i class="ti ti-send me-1"></i>Ke: {{ $item->penerima ?? '-' }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($item->tanggal_surat)
                                        <i class="ti ti-calendar me-1"></i>{{ $item->tanggal_surat->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {!! $item->getPrioritasBadge() !!}
                                </td>
                                <td class="text-center">
                                    {!! $item->getStatusBadge() !!}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('administrasi.show', $item->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('administrasi.edit', $item->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="{{ route('administrasi.tindak-lanjut.create', $item->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           title="Tindak Lanjut">
                                            <i class="ti ti-arrow-forward"></i>
                                        </a>
                                        @if($item->file_dokumen)
                                        <a href="{{ route('administrasi.download', $item->id) }}" 
                                           class="btn btn-sm btn-secondary" 
                                           title="Download Dokumen">
                                            <i class="ti ti-download"></i>
                                        </a>
                                        @endif
                                        <form action="{{ route('administrasi.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ti ti-folder-off" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Belum ada data administrasi</p>
                                        <a href="{{ route('administrasi.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ti ti-plus me-1"></i> Tambah Data
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $administrasi->firstItem() ?? 0 }} sampai {{ $administrasi->lastItem() ?? 0 }} 
                        dari {{ $administrasi->total() }} data
                    </div>
                    <div>
                        {{ $administrasi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .blink {
        animation: blink 1.5s infinite;
    }
</style>
@endsection

@push('myscript')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end',
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            confirmButtonColor: '#d33'
        });
    @endif

    // Modern delete confirmation with SweetAlert2
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
