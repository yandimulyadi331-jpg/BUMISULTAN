@extends('layouts.app')
@section('titlepage', 'Manajemen Informasi')

@section('content')
@section('navigasi')
    <span>Informasi / Manajemen Pusat Informasi</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white mb-0">
                            <i class="ti ti-info-circle me-2"></i>Manajemen Pusat Informasi Karyawan
                        </h5>
                    </div>
                    <div>
                        <a href="{{ route('admin.informasi.create') }}" class="btn btn-light">
                            <i class="ti ti-plus me-2"></i> Tambah Informasi Baru
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($informasi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Judul</th>
                                    <th width="10%">Tipe</th>
                                    <th width="15%">Preview</th>
                                    <th width="8%">Prioritas</th>
                                    <th width="12%">Periode</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Dibaca</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($informasi as $index => $item)
                                    <tr>
                                        <td>{{ $informasi->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $item->judul }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($item->konten, 50) }}</small>
                                        </td>
                                        <td>
                                            @if($item->tipe == 'banner')
                                                <span class="badge bg-info">
                                                    <i class="ti ti-photo me-1"></i>Banner
                                                </span>
                                            @elseif($item->tipe == 'link')
                                                <span class="badge bg-primary">
                                                    <i class="ti ti-link me-1"></i>Link
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="ti ti-text me-1"></i>Text
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->banner_path)
                                                <img src="{{ getBannerInformasi($item->banner_path) }}" 
                                                     alt="Banner" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 100px; max-height: 60px; object-fit: cover;">
                                            @elseif($item->link_url)
                                                <a href="{{ $item->link_url }}" target="_blank" class="text-primary">
                                                    <i class="ti ti-external-link"></i> Lihat Link
                                                </a>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-pill bg-dark">{{ $item->priority }}</span>
                                        </td>
                                        <td>
                                            @if($item->tanggal_mulai || $item->tanggal_selesai)
                                                <small>
                                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }}
                                                    <br>s/d<br>
                                                    {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}
                                                </small>
                                            @else
                                                <small class="text-muted">Permanen</small>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.informasi.toggleActive', $item->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-success' : 'btn-secondary' }} btn-toggle-status">
                                                    <i class="ti ti-{{ $item->is_active ? 'eye' : 'eye-off' }}"></i>
                                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="ti ti-check-circle text-success"></i> {{ $item->reads->count() }} orang
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.informasi.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.informasi.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus informasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $informasi->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="ti ti-info-circle me-2"></i>
                        Belum ada informasi. Silakan tambah informasi baru.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    // Confirm toggle status
    $('.btn-toggle-status').on('click', function(e) {
        return confirm('Yakin ingin mengubah status informasi ini?');
    });
</script>
@endpush

@endsection
