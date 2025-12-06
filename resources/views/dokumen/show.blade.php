@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('page-pretitle', 'Manajemen Dokumen')
@section('page-title', $document->nama_dokumen)

@section('content')
<div class="container-xl">
    <div class="row">
        <!-- Document Info -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-file-text me-2"></i>
                        Detail Dokumen
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <h2 class="mb-3">{{ $document->nama_dokumen }}</h2>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Kode Dokumen:</strong><br>
                            <span class="badge bg-primary fs-3">{{ $document->kode_dokumen }}</span>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Kategori:</strong><br>
                            <span class="badge fs-4" style="background-color: {{ $document->category->warna }};">
                                {{ $document->category->nama_kategori }}
                            </span>
                        </div>

                        @if($document->deskripsi)
                        <div class="col-12">
                            <strong>Deskripsi:</strong><br>
                            <p class="text-muted">{{ $document->deskripsi }}</p>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <span class="{{ $document->status_badge }}">{{ ucfirst($document->status) }}</span>
                        </div>

                        <div class="col-md-6">
                            <strong>Level Akses:</strong><br>
                            <span class="{{ $document->access_badge }}">{{ $document->access_level_text }}</span>
                        </div>

                        @if($document->tanggal_dokumen)
                        <div class="col-md-4">
                            <strong>Tanggal Dokumen:</strong><br>
                            {{ $document->tanggal_dokumen->format('d F Y') }}
                        </div>
                        @endif

                        @if($document->tanggal_berlaku)
                        <div class="col-md-4">
                            <strong>Tanggal Berlaku:</strong><br>
                            {{ $document->tanggal_berlaku->format('d F Y') }}
                        </div>
                        @endif

                        @if($document->tanggal_berakhir)
                        <div class="col-md-4">
                            <strong>Tanggal Berakhir:</strong><br>
                            {{ $document->tanggal_berakhir->format('d F Y') }}
                            @if($document->isExpired())
                            <span class="badge bg-danger ms-1">Kadaluarsa</span>
                            @endif
                        </div>
                        @endif

                        @if($document->nomor_referensi)
                        <div class="col-md-6">
                            <strong>Nomor Referensi:</strong><br>
                            {{ $document->nomor_referensi }}
                        </div>
                        @endif

                        @if($document->penerbit)
                        <div class="col-md-6">
                            <strong>Penerbit:</strong><br>
                            {{ $document->penerbit }}
                        </div>
                        @endif

                        @if($document->tags)
                        <div class="col-12">
                            <strong>Tags:</strong><br>
                            @foreach(explode(',', $document->tags) as $tag)
                            <span class="badge bg-azure-lt">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- File Preview -->
            @if($document->jenis_dokumen === 'file')
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-eye me-2"></i>
                        Preview Dokumen
                    </h3>
                </div>
                <div class="card-body">
                    @if($document->file_extension === 'pdf')
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ asset('storage/' . $document->file_path) }}" frameborder="0"></iframe>
                    </div>
                    @elseif(in_array($document->file_extension, ['jpg', 'jpeg', 'png', 'gif']))
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $document->file_path) }}" class="img-fluid" alt="{{ $document->nama_dokumen }}">
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="{{ $document->file_icon }} fs-1"></i>
                        <p class="mb-0 mt-2">File {{ strtoupper($document->file_extension) }} tidak dapat di-preview.</p>
                        @if($document->canDownload())
                        <p class="mb-0">Silakan download untuk melihat.</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @elseif($document->jenis_dokumen === 'link')
            <div class="card mt-3">
                <div class="card-body text-center">
                    <i class="ti ti-link fs-1 text-info"></i>
                    <h3 class="mt-3">Dokumen Eksternal</h3>
                    <p class="text-muted">Dokumen ini berupa link eksternal</p>
                    <a href="{{ $document->file_path }}" target="_blank" class="btn btn-primary">
                        <i class="ti ti-external-link me-1"></i>
                        Buka Link
                    </a>
                </div>
            </div>
            @endif

            <!-- Access Logs -->
            @role('super admin')
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-history me-2"></i>
                        Riwayat Akses Terakhir
                    </h3>
                </div>
                <div class="card-body">
                    @if($accessLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accessLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $log->nama_user }}</td>
                                    <td><span class="{{ $log->action_badge }}">{{ $log->action_text }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">Belum ada riwayat akses</p>
                    @endif
                </div>
            </div>
            @endrole
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($document->canDownload())
                        <a href="{{ route('dokumen.download', $document->id) }}" class="btn btn-success">
                            <i class="ti ti-download me-1"></i>
                            Download Dokumen
                        </a>
                        @endif

                        @role('super admin')
                        <a href="{{ route('dokumen.edit', $document->id) }}" class="btn btn-warning">
                            <i class="ti ti-edit me-1"></i>
                            Edit Dokumen
                        </a>

                        <form action="{{ route('dokumen.destroy', $document->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="ti ti-trash me-1"></i>
                                Hapus Dokumen
                            </button>
                        </form>
                        @endrole

                        <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lokasi Fisik -->
            @if($document->nomor_loker)
            <div class="card mt-3">
                <div class="card-header bg-primary-lt">
                    <h3 class="card-title">
                        <i class="ti ti-archive me-2"></i>
                        Lokasi Penyimpanan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Nomor Loker:</strong><br>
                        <span class="fs-2 text-info">{{ $document->nomor_loker }}</span>
                    </div>
                    @if($document->lokasi_loker)
                    <div class="mb-2">
                        <strong>Lokasi:</strong><br>
                        {{ $document->lokasi_loker }}
                    </div>
                    @endif
                    @if($document->rak)
                    <div class="mb-2">
                        <strong>Rak:</strong> {{ $document->rak }}
                    </div>
                    @endif
                    @if($document->baris)
                    <div class="mb-2">
                        <strong>Baris:</strong> {{ $document->baris }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- File Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-info-circle me-2"></i>
                        Informasi File
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Jenis:</strong><br>
                        @if($document->jenis_dokumen == 'link')
                        <span class="badge bg-info">Link Eksternal</span>
                        @else
                        <span class="badge bg-secondary">{{ strtoupper($document->file_extension) }}</span>
                        @endif
                    </div>
                    @if($document->file_size)
                    <div class="mb-2">
                        <strong>Ukuran:</strong> {{ $document->file_size }}
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Dilihat:</strong> {{ $document->jumlah_view }} kali
                    </div>
                    <div class="mb-2">
                        <strong>Diunduh:</strong> {{ $document->jumlah_download }} kali
                    </div>
                    @if($document->uploader)
                    <div class="mb-2">
                        <strong>Diupload oleh:</strong><br>
                        {{ $document->uploader->nama }}
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Tanggal Upload:</strong><br>
                        {{ $document->created_at->format('d F Y H:i') }}
                    </div>
                    @if($document->updated_at != $document->created_at)
                    <div class="mb-2">
                        <strong>Terakhir Diupdate:</strong><br>
                        {{ $document->updated_at->format('d F Y H:i') }}
                        @if($document->updater)
                        <br>oleh {{ $document->updater->nama }}
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
