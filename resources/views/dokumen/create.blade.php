@extends('layouts.app')

@section('title', 'Tambah Dokumen')

@section('page-pretitle', 'Manajemen Dokumen')
@section('page-title', 'Tambah Dokumen Baru')

@section('content')
<div class="container-xl">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" id="formDokumen">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-file-plus me-2"></i>
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
                                       value="{{ old('nama_dokumen') }}" 
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
                                            {{ old('document_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->kode_kategori }} - {{ $category->nama_kategori }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('document_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kode akan otomatis di-generate berdasarkan kategori</small>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label required">Status Dokumen</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="arsip" {{ old('status') == 'arsip' ? 'selected' : '' }}>Arsip</option>
                                    <option value="kadaluarsa" {{ old('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
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
                                          placeholder="Deskripsi detail dokumen">{{ old('deskripsi') }}</textarea>
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
                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-row gap-3">
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="jenis_dokumen" value="file" 
                                               class="form-selectgroup-input" 
                                               {{ old('jenis_dokumen', 'file') == 'file' ? 'checked' : '' }}
                                               onchange="toggleJenisDokumen()">
                                        <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-4">
                                            <div class="text-center">
                                                <i class="ti ti-file-upload fs-1 text-primary mb-2"></i>
                                                <div class="fw-bold">Upload File</div>
                                                <small class="text-muted">Upload dari komputer</small>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="jenis_dokumen" value="link" 
                                               class="form-selectgroup-input"
                                               {{ old('jenis_dokumen') == 'link' ? 'checked' : '' }}
                                               onchange="toggleJenisDokumen()">
                                        <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-4">
                                            <div class="text-center">
                                                <i class="ti ti-link fs-1 text-success mb-2"></i>
                                                <div class="fw-bold">Link Eksternal</div>
                                                <small class="text-muted">Google Drive, Dropbox, dll</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Upload File Section -->
                            <div class="col-md-12" id="fileUploadSection">
                                <div class="card border-primary shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="card-title mb-0">
                                            <i class="ti ti-upload me-2"></i>
                                            Upload File dari Komputer
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="border-3 border-primary border-dashed rounded p-4 text-center bg-primary-lt" 
                                             style="cursor: pointer;"
                                             onclick="document.querySelector('input[name=file_dokumen]').click()">
                                            <i class="ti ti-cloud-upload" style="font-size: 4rem; color: #0054a6;"></i>
                                            <h3 class="mt-3 mb-2">Klik untuk Pilih File</h3>
                                            <p class="text-muted mb-0">atau drag & drop file di sini</p>
                                        </div>
                                        <input type="file" name="file_dokumen" 
                                               class="form-control form-control-lg mt-3 @error('file_dokumen') is-invalid @enderror" 
                                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar"
                                               onchange="previewFile(this)">
                                        @error('file_dokumen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="alert alert-info mt-3 mb-0">
                                            <i class="ti ti-info-circle me-2"></i>
                                            <strong>Format yang didukung:</strong> PDF, Word, Excel, Gambar (JPG, PNG), ZIP, RAR
                                            <br><strong>Ukuran maksimal:</strong> 10MB
                                        </div>
                                        <div id="filePreview" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Link Eksternal Section -->
                            <div class="col-md-12" id="linkSection" style="display: none;">
                                <div class="card border-success shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h4 class="card-title mb-0">
                                            <i class="ti ti-link me-2"></i>
                                            Copy & Paste Link Dokumen
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="border-3 border-success border-dashed rounded p-4 bg-success-lt">
                                            <div class="text-center mb-3">
                                                <i class="ti ti-copy" style="font-size: 4rem; color: #2fb344;"></i>
                                                <h3 class="mt-3 mb-2">Paste URL Link di Bawah</h3>
                                                <p class="text-muted mb-0">Salin link dari Google Drive, Dropbox, atau website lainnya</p>
                                            </div>
                                            
                                            <label class="form-label required fs-4 fw-bold text-success">
                                                <i class="ti ti-world me-1"></i>
                                                Paste Link URL di Sini:
                                            </label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-success text-white">
                                                    <i class="ti ti-link"></i>
                                                </span>
                                                <input type="url" name="link_dokumen" 
                                                       class="form-control form-control-lg @error('link_dokumen') is-invalid @enderror" 
                                                       value="{{ old('link_dokumen') }}" 
                                                       placeholder="Ctrl+V untuk paste link di sini..."
                                                       id="linkInput"
                                                       style="font-size: 1.1rem; padding: 1rem;">
                                                <button class="btn btn-success" type="button" onclick="document.getElementById('linkInput').value = ''">
                                                    <i class="ti ti-x"></i> Clear
                                                </button>
                                            </div>
                                            @error('link_dokumen')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="alert alert-success mt-3">
                                            <i class="ti ti-info-circle me-2"></i>
                                            <strong>Contoh link yang didukung:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><strong>Google Drive:</strong> https://drive.google.com/file/d/xxxxx/view</li>
                                                <li><strong>Dropbox:</strong> https://www.dropbox.com/s/xxxxx/file.pdf</li>
                                                <li><strong>OneDrive:</strong> https://onedrive.live.com/xxxxx</li>
                                                <li><strong>Website:</strong> https://example.com/document.pdf</li>
                                            </ul>
                                        </div>
                                        <div id="linkPreview" class="mt-3"></div>
                                    </div>
                                </div>
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
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Integrasi Loker Fisik:</strong> Isi informasi loker untuk memudahkan pencarian dokumen fisik
                        </div>
                        <div class="row g-3">
                            <!-- Nomor Loker -->
                            <div class="col-md-3">
                                <label class="form-label">Nomor Loker</label>
                                <input type="text" name="nomor_loker" 
                                       class="form-control @error('nomor_loker') is-invalid @enderror" 
                                       value="{{ old('nomor_loker') }}" 
                                       placeholder="L001" id="nomorLoker"
                                       oninput="updateKodePreview()">
                                @error('nomor_loker')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: L001, L002, dll</small>
                            </div>

                            <!-- Lokasi Loker -->
                            <div class="col-md-3">
                                <label class="form-label">Lokasi Loker</label>
                                <input type="text" name="lokasi_loker" 
                                       class="form-control @error('lokasi_loker') is-invalid @enderror" 
                                       value="{{ old('lokasi_loker') }}" 
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
                                       value="{{ old('rak') }}" 
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
                                       value="{{ old('baris') }}" 
                                       placeholder="B1">
                                @error('baris')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview Kode Dokumen -->
                            <div class="col-md-12">
                                <div class="alert alert-success mb-0">
                                    <strong>Preview Kode Dokumen:</strong>
                                    <h3 class="mb-0 mt-2" id="kodePreview">
                                        <span class="text-muted">[Pilih kategori dan isi nomor loker]</span>
                                    </h3>
                                </div>
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
                                               {{ old('access_level', 'public') == 'public' ? 'checked' : '' }}>
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
                                               {{ old('access_level') == 'view_only' ? 'checked' : '' }}>
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
                                               {{ old('access_level') == 'restricted' ? 'checked' : '' }}>
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
                                       value="{{ old('tanggal_dokumen') }}">
                                @error('tanggal_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Berlaku -->
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Berlaku</label>
                                <input type="date" name="tanggal_berlaku" 
                                       class="form-control @error('tanggal_berlaku') is-invalid @enderror" 
                                       value="{{ old('tanggal_berlaku') }}">
                                @error('tanggal_berlaku')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Berakhir -->
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Berakhir</label>
                                <input type="date" name="tanggal_berakhir" 
                                       class="form-control @error('tanggal_berakhir') is-invalid @enderror" 
                                       value="{{ old('tanggal_berakhir') }}">
                                @error('tanggal_berakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nomor Referensi -->
                            <div class="col-md-6">
                                <label class="form-label">Nomor Referensi/Surat</label>
                                <input type="text" name="nomor_referensi" 
                                       class="form-control @error('nomor_referensi') is-invalid @enderror" 
                                       value="{{ old('nomor_referensi') }}" 
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
                                       value="{{ old('penerbit') }}" 
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
                                       value="{{ old('tags') }}" 
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
                                <button type="reset" class="btn btn-secondary me-2">
                                    <i class="ti ti-refresh me-1"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    Simpan Dokumen
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
    // Toggle between file upload and link with smooth animation
    function toggleJenisDokumen(animate = true) {
        const jenisDokumen = document.querySelector('input[name="jenis_dokumen"]:checked').value;
        const fileSection = document.getElementById('fileUploadSection');
        const linkSection = document.getElementById('linkSection');
        
        if (animate) {
            // Add fade out effect
            fileSection.style.opacity = '0';
            linkSection.style.opacity = '0';
        }
        
        const delay = animate ? 200 : 0;
        
        setTimeout(() => {
            if (jenisDokumen === 'file') {
                fileSection.style.display = 'block';
                linkSection.style.display = 'none';
                document.querySelector('input[name="file_dokumen"]').required = true;
                document.querySelector('input[name="link_dokumen"]').required = false;
                
                // Fade in
                if (animate) {
                    setTimeout(() => { fileSection.style.opacity = '1'; }, 50);
                } else {
                    fileSection.style.opacity = '1';
                }
            } else {
                fileSection.style.display = 'none';
                linkSection.style.display = 'block';
                document.querySelector('input[name="file_dokumen"]').required = false;
                document.querySelector('input[name="link_dokumen"]').required = true;
                
                // Fade in and focus on link input
                if (animate) {
                    setTimeout(() => { 
                        linkSection.style.opacity = '1';
                        document.querySelector('input[name="link_dokumen"]').focus();
                    }, 50);
                } else {
                    linkSection.style.opacity = '1';
                }
            }
        }, delay);
    }
    
    // Add CSS transition and initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const fileSection = document.getElementById('fileUploadSection');
        const linkSection = document.getElementById('linkSection');
        fileSection.style.transition = 'opacity 0.2s ease-in-out';
        linkSection.style.transition = 'opacity 0.2s ease-in-out';
        
        // Initialize display based on selected option (without animation)
        toggleJenisDokumen(false);
    });

    // Preview file
    function previewFile(input) {
        const preview = document.getElementById('filePreview');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
            
            preview.innerHTML = `
                <div class="alert alert-success mb-0">
                    <i class="ti ti-check me-2"></i>
                    <strong>File dipilih:</strong> ${file.name} (${fileSize} MB)
                </div>
            `;
        }
    }

    // Update kode preview
    function updateKodePreview() {
        const category = document.getElementById('categorySelect');
        const nomorLoker = document.getElementById('nomorLoker').value || 'L000';
        const kodePreview = document.getElementById('kodePreview');
        
        if (category.value) {
            const kodeKategori = category.options[category.selectedIndex].getAttribute('data-kode');
            kodePreview.innerHTML = `<span class="text-primary">${kodeKategori}-XXX-${nomorLoker}</span>`;
            kodePreview.innerHTML += '<br><small class="text-muted">XXX = Nomor otomatis dari sistem</small>';
        } else {
            kodePreview.innerHTML = '<span class="text-muted">[Pilih kategori dan isi nomor loker]</span>';
        }
    }

    // Init on load
    document.addEventListener('DOMContentLoaded', function() {
        toggleJenisDokumen();
        updateKodePreview();
        
        // Add event listener to category select
        document.getElementById('categorySelect').addEventListener('change', updateKodePreview);
    });
</script>
@endpush
