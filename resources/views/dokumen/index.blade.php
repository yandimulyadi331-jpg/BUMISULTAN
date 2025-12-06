@extends('layouts.app')

@section('title', 'Manajemen Dokumen')

@section('page-pretitle', 'Fasilitas & Asset')
@section('page-title', 'Manajemen Dokumen')

@section('content')
<div class="container-xl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <h3 class="card-title">
                                <i class="ti ti-file-text me-2"></i>
                                Daftar Dokumen Perusahaan
                            </h3>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('dokumen.export-pdf', request()->all()) }}" class="btn btn-danger me-2" target="_blank">
                                <i class="ti ti-file-type-pdf me-1"></i>
                                Export PDF
                            </a>
                            @role('super admin')
                            <a href="{{ route('dokumen.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                Tambah Dokumen
                            </a>
                            @endrole
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filter & Search Section -->
                    <form action="{{ route('dokumen.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <!-- Search -->
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari kode, nama, nomor loker..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Filter Kategori -->
                            <div class="col-md-2">
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Status -->
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="arsip" {{ request('status') == 'arsip' ? 'selected' : '' }}>Arsip</option>
                                    <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                                </select>
                            </div>

                            <!-- Filter Access Level -->
                            <div class="col-md-2">
                                <select name="access_level" class="form-select">
                                    <option value="">Semua Akses</option>
                                    <option value="public" {{ request('access_level') == 'public' ? 'selected' : '' }}>Publik</option>
                                    <option value="view_only" {{ request('access_level') == 'view_only' ? 'selected' : '' }}>View Only</option>
                                    <option value="restricted" {{ request('access_level') == 'restricted' ? 'selected' : '' }}>Restricted</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-filter me-1"></i>
                                    Filter
                                </button>
                            </div>
                        </div>

                        <!-- Quick Search by Loker -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ti ti-archive"></i>
                                    </span>
                                    <input type="text" name="nomor_loker" class="form-control" 
                                           placeholder="Cari berdasarkan Nomor Loker (L001, L002...)" 
                                           value="{{ request('nomor_loker') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                @if(request()->hasAny(['search', 'category_id', 'status', 'access_level', 'nomor_loker']))
                                <a href="{{ route('dokumen.index') }}" class="btn btn-secondary w-100">
                                    <i class="ti ti-x me-1"></i>
                                    Reset
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ti ti-info-circle me-2"></i>
                                        <strong>Total: {{ $documents->total() }} dokumen</strong>
                                    </div>
                                    <div class="text-muted">
                                        <small>
                                            <i class="ti ti-file-text me-1"></i>Kode format: [KATEGORI]-[NOMOR]-[LOKER]
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="12%">Kode Dokumen</th>
                                    <th width="20%">Nama Dokumen</th>
                                    <th width="12%">Kategori</th>
                                    <th width="10%">Nomor Loker</th>
                                    <th width="10%">Tipe/Akses</th>
                                    <th width="8%">Status</th>
                                    <th width="8%" class="text-center">Stats</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $index => $doc)
                                <tr>
                                    <td class="text-center">{{ $documents->firstItem() + $index }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $doc->kode_dokumen }}</strong>
                                        @if($doc->isExpired())
                                        <br><small class="text-danger"><i class="ti ti-alert-circle"></i> Kadaluarsa</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $doc->file_icon }} fs-2 me-2"></i>
                                            <div>
                                                <strong>{{ Str::limit($doc->nama_dokumen, 40) }}</strong>
                                                @if($doc->nomor_referensi)
                                                <br><small class="text-muted">Ref: {{ $doc->nomor_referensi }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $doc->category->warna }};">
                                            {{ $doc->category->kode_kategori }}
                                        </span>
                                        <br><small class="text-muted">{{ $doc->category->nama_kategori }}</small>
                                    </td>
                                    <td>
                                        @if($doc->nomor_loker)
                                        <strong class="text-info">{{ $doc->nomor_loker }}</strong>
                                        @if($doc->lokasi_loker)
                                        <br><small class="text-muted">{{ $doc->lokasi_loker }}</small>
                                        @endif
                                        @if($doc->rak)
                                        <br><small class="text-muted">Rak: {{ $doc->rak }}{{ $doc->baris ? ' / Baris: '.$doc->baris : '' }}</small>
                                        @endif
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($doc->jenis_dokumen == 'link')
                                        <span class="badge bg-info">
                                            <i class="ti ti-link"></i> Link
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">
                                            <i class="ti ti-file"></i> {{ strtoupper($doc->file_extension) }}
                                        </span>
                                        @endif
                                        <br>
                                        <span class="{{ $doc->access_badge }}">
                                            {{ $doc->access_level_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $doc->status_badge }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                        @if($doc->tanggal_berakhir)
                                        <br><small class="text-muted">
                                            {{ $doc->tanggal_berakhir->format('d/m/Y') }}
                                        </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <small>
                                            <i class="ti ti-eye text-info"></i> {{ $doc->jumlah_view }}
                                            <br>
                                            <i class="ti ti-download text-success"></i> {{ $doc->jumlah_download }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View/Preview Button -->
                                            @if($doc->canView())
                                            <button type="button" class="btn btn-info btn-sm" 
                                                    onclick="previewDocument({{ $doc->id }})"
                                                    title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            @endif

                                            <!-- Download Button -->
                                            @if($doc->canDownload())
                                            <a href="{{ route('dokumen.download', $doc->id) }}" 
                                               class="btn btn-success btn-sm"
                                               title="Download">
                                                <i class="ti ti-download"></i>
                                            </a>
                                            @endif

                                            <!-- Edit Button (Admin Only) -->
                                            @role('super admin')
                                            <a href="{{ route('dokumen.edit', $doc->id) }}" 
                                               class="btn btn-warning btn-sm"
                                               title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            <!-- Delete Button (Admin Only) -->
                                            <form action="{{ route('dokumen.destroy', $doc->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ti ti-folder-off fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">Tidak ada dokumen ditemukan</p>
                                        @role('super admin')
                                        <a href="{{ route('dokumen.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ti ti-plus me-1"></i>
                                            Tambah Dokumen Pertama
                                        </a>
                                        @endrole
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($documents->hasPages())
                    <div class="mt-4">
                        {{ $documents->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Document -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-file-text me-2"></i>
                    <span id="modalTitle">Detail Dokumen</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    function previewDocument(documentId) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const modalBody = document.getElementById('modalBody');
        const modalTitle = document.getElementById('modalTitle');
        const modalFooter = document.getElementById('modalFooter');
        
        // Show loading
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        modal.show();
        
        // Fetch document data
        fetch(`/dokumen/${documentId}/preview`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const doc = data.document;
                    
                    modalTitle.textContent = doc.nama_dokumen;
                    
                    let filePreview = '';
                    if (doc.jenis_dokumen === 'file') {
                        const fileUrl = `/storage/${doc.file_path}`;
                        
                        // Preview berdasarkan tipe file
                        if (doc.file_extension === 'pdf') {
                            filePreview = `
                                <div class="ratio ratio-16x9">
                                    <iframe src="${fileUrl}" frameborder="0"></iframe>
                                </div>
                            `;
                        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(doc.file_extension)) {
                            filePreview = `
                                <div class="text-center">
                                    <img src="${fileUrl}" class="img-fluid" alt="${doc.nama_dokumen}">
                                </div>
                            `;
                        } else {
                            filePreview = `
                                <div class="alert alert-info text-center">
                                    <i class="ti ti-file fs-1"></i>
                                    <p class="mb-0">File ${doc.file_extension.toUpperCase()} tidak dapat di-preview. Silakan download untuk melihat.</p>
                                </div>
                            `;
                        }
                    } else {
                        filePreview = `
                            <div class="alert alert-info text-center">
                                <i class="ti ti-link fs-1"></i>
                                <p class="mb-2">Dokumen berupa link eksternal</p>
                                <a href="${doc.file_path}" target="_blank" class="btn btn-primary">
                                    <i class="ti ti-external-link me-1"></i>
                                    Buka Link
                                </a>
                            </div>
                        `;
                    }
                    
                    modalBody.innerHTML = `
                        ${filePreview}
                        
                        <hr class="my-4">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong>Kode Dokumen:</strong><br>
                                <span class="text-primary fs-4">${doc.kode_dokumen}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Kategori:</strong><br>
                                <span class="badge" style="background-color: ${doc.category.warna}; font-size: 1rem;">
                                    ${doc.category.nama_kategori}
                                </span>
                            </div>
                            <div class="col-md-12">
                                <strong>Deskripsi:</strong><br>
                                ${doc.deskripsi || '<span class="text-muted">-</span>'}
                            </div>
                            <div class="col-md-6">
                                <strong>Nomor Loker:</strong> ${doc.nomor_loker || '-'}<br>
                                <strong>Lokasi Loker:</strong> ${doc.lokasi_loker || '-'}<br>
                                ${doc.rak ? `<strong>Rak:</strong> ${doc.rak}<br>` : ''}
                                ${doc.baris ? `<strong>Baris:</strong> ${doc.baris}` : ''}
                            </div>
                            <div class="col-md-6">
                                <strong>Level Akses:</strong> <span class="${doc.access_badge}">${doc.access_level_text}</span><br>
                                <strong>Status:</strong> <span class="${doc.status_badge}">${doc.status.charAt(0).toUpperCase() + doc.status.slice(1)}</span><br>
                                ${doc.tanggal_dokumen ? `<strong>Tanggal Dokumen:</strong> ${new Date(doc.tanggal_dokumen).toLocaleDateString('id-ID')}<br>` : ''}
                            </div>
                            <div class="col-md-12">
                                <strong>Statistik:</strong><br>
                                <i class="ti ti-eye text-info"></i> Dilihat: ${doc.jumlah_view} kali | 
                                <i class="ti ti-download text-success"></i> Diunduh: ${doc.jumlah_download} kali
                            </div>
                        </div>
                    `;
                    
                    // Update footer dengan download button jika bisa download
                    if (data.canDownload) {
                        modalFooter.innerHTML = `
                            <a href="/dokumen/${doc.id}/download" class="btn btn-success">
                                <i class="ti ti-download me-1"></i>
                                Download
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        `;
                    }
                } else {
                    modalBody.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>
                            ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        Terjadi kesalahan saat memuat data dokumen.
                    </div>
                `;
                console.error('Error:', error);
            });
    }
</script>
@endpush
