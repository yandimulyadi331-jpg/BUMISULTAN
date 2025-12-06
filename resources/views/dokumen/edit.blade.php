@extends('layouts.app')

@section('title', 'Edit Dokumen')

@section('page-pretitle', 'Manajemen Dokumen')
@section('page-title', 'Edit Dokumen: ' . $document->nama_dokumen)

@section('content')
<div class="container-xl">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('dokumen.update', $document->id) }}" method="POST" enctype="multipart/form-data" id="formDokumen">
                @csrf
                @method('PUT')
                
                <!-- Info Current Document -->
                <div class="alert alert-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <strong><i class="ti ti-info-circle me-2"></i>Dokumen saat ini:</strong>
                            <br>Kode: <span class="badge bg-primary fs-5">{{ $document->kode_dokumen }}</span>
                            @if($document->jenis_dokumen === 'file' && $document->file_path)
                            <br>File: {{ basename($document->file_path) }} ({{ $document->file_size }})
                            @elseif($document->jenis_dokumen === 'link')
                            <br>Link: <a href="{{ $document->file_path }}" target="_blank">{{ $document->file_path }}</a>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('dokumen.show', $document->id) }}" class="btn btn-info" target="_blank">
                                <i class="ti ti-eye me-1"></i>
                                Lihat Dokumen
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-edit me-2"></i>
                            Informasi Dokumen
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Nama Dokumen -->
                            <div class="col-md-12">
                                <label class="form-label required">Nama Dokumen</label>
                                <input type="text" name="nama_dokumen" 
                                       class="form-control @error('nama_dokumen') is-invalid @enderror" 
                                       value="{{ old('nama_dokumen', $document->nama_dokumen) }}" 
                                       placeholder="Masukkan nama dokumen" required>
                                @error('nama_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori Dokumen -->
                            <div class="col-md-6">
                                <label class="form-label required">Kategori Dokumen</label>
                                <select name="document_category_id" 
                                        class="form-select @error('document_category_id') is-invalid @enderror" 
                                        required id="categorySelect">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            data-kode="{{ $category->kode_kategori }}"
                                            {{ old('document_category_id', $document->document_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->kode_kategori }} - {{ $category->nama_kategori }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('document_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label required">Status Dokumen</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $document->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="arsip" {{ old('status', $document->status) == 'arsip' ? 'selected' : '' }}>Arsip</option>
                                    <option value="kadaluarsa" {{ old('status', $document->status) == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-md-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" rows="3" 
                                          class="form-control @error('deskripsi') is-invalid @enderror" 
                                          placeholder="Deskripsi detail dokumen">{{ old('deskripsi', $document->deskripsi) }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File/Link Section -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-upload me-2"></i>
                            File Dokumen
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Jenis Dokumen -->
                            <div class="col-md-12">
                                <label class="form-label required">Jenis Dokumen</label>
                                <div class="form-selectgroup">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="jenis_dokumen" value="file" 
                                               class="form-selectgroup-input" 
                                               {{ old('jenis_dokumen', $document->jenis_dokumen) == 'file' ? 'checked' : '' }}
                                               onchange="toggleJenisDokumen()">
                                        <span class="form-selectgroup-label">
                                            <i class="ti ti-file-upload me-2"></i>
                                            Upload File
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="jenis_dokumen" value="link" 
                                               class="form-selectgroup-input"
                                               {{ old('jenis_dokumen', $document->jenis_dokumen) == 'link' ? 'checked' : '' }}
                                               onchange="toggleJenisDokumen()">
                                        <span class="form-selectgroup-label">
                                            <i class="ti ti-link me-2"></i>
                                            Link Eksternal
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Upload File -->
                            <div class="col-md-12" id="fileUploadSection">
                                <label class="form-label">File Dokumen Baru (Kosongkan jika tidak ingin mengganti)</label>
                                <input type="file" name="file_dokumen" 
                                       class="form-control @error('file_dokumen') is-invalid @enderror" 
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar"
                                       onchange="previewFile(this)">
                                @error('file_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Format yang didukung: PDF, Word, Excel, Gambar (JPG, PNG), ZIP, RAR. Maksimal 10MB
                                </small>
                                <div id="filePreview" class="mt-2"></div>
                            </div>

                            <!-- Link Eksternal -->
                            <div class="col-md-12" id="linkSection" style="display: none;">
                                <label class="form-label required">URL Link Dokumen</label>
                                <input type="url" name="link_dokumen" 
                                       class="form-control @error('link_dokumen') is-invalid @enderror" 
                                       value="{{ old('link_dokumen', $document->jenis_dokumen == 'link' ? $document->file_path : '') }}" 
                                       placeholder="https://example.com/dokumen">
                                @error('link_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi Fisik Section -->
                <div class="card mt-3">
                    <div class="card-header bg-primary-lt">
                        <h3 class="card-title">
                            <i class="ti ti-archive me-2"></i>
                            Lokasi Penyimpanan Fisik
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Nomor Loker -->
                            <div class="col-md-3">
                                <label class="form-label">Nomor Loker</label>
                                <input type="text" name="nomor_loker" 
                                       class="form-control @error('nomor_loker') is-invalid @enderror" 
                                       value="{{ old('nomor_loker', $document->nomor_loker) }}" 
                                       placeholder="L001" id="nomorLoker"
                                       oninput="updateKodePreview()">
                                @error('nomor_loker')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lokasi Loker -->
                            <div class="col-md-3">
                                <label class="form-label">Lokasi Loker</label>
                                <input type="text" name="lokasi_loker" 
                                       class="form-control @error('lokasi_loker') is-invalid @enderror" 
                                       value="{{ old('lokasi_loker', $document->lokasi_loker) }}" 
                                       placeholder="Ruang Arsip Lt.2">
                                @error('lokasi_loker')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Rak -->
                            <div class="col-md-3">
                                <label class="form-label">Nomor Rak</label>
                                <input type="text" name="rak" 
                                       class="form-control @error('rak') is-invalid @enderror" 
                                       value="{{ old('rak', $document->rak) }}" 
                                       placeholder="R1">
                                @error('rak')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Baris -->
                            <div class="col-md-3">
                                <label class="form-label">Baris/Posisi</label>
                                <input type="text" name="baris" 
                                       class="form-control @error('baris') is-invalid @enderror" 
                                       value="{{ old('baris', $document->baris) }}" 
                                       placeholder="B1">
                                @error('baris')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hak Akses Section -->
                <div class="card mt-3">
                    <div class="card-header bg-warning-lt">
                        <h3 class="card-title">
                            <i class="ti ti-lock me-2"></i>
                            Pengaturan Hak Akses
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Access Level -->
                            <div class="col-md-12">
                                <label class="form-label required">Level Akses Dokumen</label>
                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="access_level" value="public" 
                                               class="form-selectgroup-input"
                                               {{ old('access_level', $document->access_level) == 'public' ? 'checked' : '' }}>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">
                                                    <i class="ti ti-world text-success me-2"></i>
                                                    <strong>Publik</strong>
                                                </div>
                                                <div class="text-muted">Semua user dapat melihat dan mengunduh dokumen</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="access_level" value="view_only" 
                                               class="form-selectgroup-input"
                                               {{ old('access_level', $document->access_level) == 'view_only' ? 'checked' : '' }}>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">
                                                    <i class="ti ti-eye text-warning me-2"></i>
                                                    <strong>Hanya Lihat</strong>
                                                </div>
                                                <div class="text-muted">User dapat melihat tetapi tidak dapat mengunduh</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="access_level" value="restricted" 
                                               class="form-selectgroup-input"
                                               {{ old('access_level', $document->access_level) == 'restricted' ? 'checked' : '' }}>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">
                                                    <i class="ti ti-lock text-danger me-2"></i>
                                                    <strong>Terbatas (Admin Only)</strong>
                                                </div>
                                                <div class="text-muted">Hanya admin yang dapat melihat dan mengunduh</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('access_level')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata Section -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-info-circle me-2"></i>
                            Metadata & Informasi Tambahan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Tanggal Dokumen -->
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Dokumen</label>
                                <input type="date" name="tanggal_dokumen" 
                                       class="form-control @error('tanggal_dokumen') is-invalid @enderror" 
                                       value="{{ old('tanggal_dokumen', $document->tanggal_dokumen?->format('Y-m-d')) }}">
                                @error('tanggal_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Berlaku -->
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Berlaku</label>
                                <input type="date" name="tanggal_berlaku" 
                                       class="form-control @error('tanggal_berlaku') is-invalid @enderror" 
                                       value="{{ old('tanggal_berlaku', $document->tanggal_berlaku?->format('Y-m-d')) }}">
                                @error('tanggal_berlaku')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Berakhir -->
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Berakhir</label>
                                <input type="date" name="tanggal_berakhir" 
                                       class="form-control @error('tanggal_berakhir') is-invalid @enderror" 
                                       value="{{ old('tanggal_berakhir', $document->tanggal_berakhir?->format('Y-m-d')) }}">
                                @error('tanggal_berakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nomor Referensi -->
                            <div class="col-md-6">
                                <label class="form-label">Nomor Referensi/Surat</label>
                                <input type="text" name="nomor_referensi" 
                                       class="form-control @error('nomor_referensi') is-invalid @enderror" 
                                       value="{{ old('nomor_referensi', $document->nomor_referensi) }}" 
                                       placeholder="001/SK/DIR/2024">
                                @error('nomor_referensi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Penerbit -->
                            <div class="col-md-6">
                                <label class="form-label">Penerbit/Yang Mengesahkan</label>
                                <input type="text" name="penerbit" 
                                       class="form-control @error('penerbit') is-invalid @enderror" 
                                       value="{{ old('penerbit', $document->penerbit) }}" 
                                       placeholder="Direktur Utama">
                                @error('penerbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12">
                                <label class="form-label">Tags (Kata Kunci)</label>
                                <input type="text" name="tags" 
                                       class="form-control @error('tags') is-invalid @enderror" 
                                       value="{{ old('tags', $document->tags) }}" 
                                       placeholder="kontrak, karyawan, 2024">
                                @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Pisahkan dengan koma untuk memudahkan pencarian</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card mt-3">
                    <div class="card-footer text-end">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dokumen.index') }}" class="btn btn-link">
                                <i class="ti ti-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    Update Dokumen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle between file upload and link
    function toggleJenisDokumen() {
        const jenisDokumen = document.querySelector('input[name="jenis_dokumen"]:checked').value;
        const fileSection = document.getElementById('fileUploadSection');
        const linkSection = document.getElementById('linkSection');
        
        if (jenisDokumen === 'file') {
            fileSection.style.display = 'block';
            linkSection.style.display = 'none';
            document.querySelector('input[name="link_dokumen"]').required = false;
        } else {
            fileSection.style.display = 'none';
            linkSection.style.display = 'block';
            document.querySelector('input[name="link_dokumen"]').required = true;
        }
    }

    // Preview file
    function previewFile(input) {
        const preview = document.getElementById('filePreview');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
            
            preview.innerHTML = `
                <div class="alert alert-success mb-0">
                    <i class="ti ti-check me-2"></i>
                    <strong>File baru dipilih:</strong> ${file.name} (${fileSize} MB)
                </div>
            `;
        }
    }

    // Init on load
    document.addEventListener('DOMContentLoaded', function() {
        toggleJenisDokumen();
    });
</script>
@endpush
