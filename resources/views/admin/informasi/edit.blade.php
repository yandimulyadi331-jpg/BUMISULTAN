@extends('layouts.app')
@section('titlepage', 'Edit Informasi')

@section('content')
@section('navigasi')
    <span><a href="{{ route('admin.informasi.index') }}">Informasi</a> / Edit Informasi</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-sm-12">
        <div class="card">
            <div class="card-header bg-gradient-warning">
                <h5 class="text-white mb-0">
                    <i class="ti ti-edit me-2"></i>Edit Informasi
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.informasi.update', $informasi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Judul -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Judul Informasi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="judul" 
                                   class="form-control @error('judul') is-invalid @enderror" 
                                   value="{{ old('judul', $informasi->judul) }}"
                                   required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipe Informasi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Informasi <span class="text-danger">*</span></label>
                            <select name="tipe" 
                                    id="tipe_informasi" 
                                    class="form-select @error('tipe') is-invalid @enderror" 
                                    required>
                                <option value="banner" {{ old('tipe', $informasi->tipe) == 'banner' ? 'selected' : '' }}>Banner / Gambar</option>
                                <option value="text" {{ old('tipe', $informasi->tipe) == 'text' ? 'selected' : '' }}>Text / Pengumuman</option>
                                <option value="link" {{ old('tipe', $informasi->tipe) == 'link' ? 'selected' : '' }}>Link / URL</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prioritas -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioritas</label>
                            <input type="number" 
                                   name="priority" 
                                   class="form-control @error('priority') is-invalid @enderror" 
                                   value="{{ old('priority', $informasi->priority) }}">
                            <small class="text-muted">0 = rendah, semakin tinggi semakin prioritas</small>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konten -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Konten / Deskripsi</label>
                            <textarea name="konten" 
                                      class="form-control @error('konten') is-invalid @enderror" 
                                      rows="4">{{ old('konten', $informasi->konten) }}</textarea>
                            @error('konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Banner (conditional) -->
                        <div class="col-12 mb-3" id="banner_upload_section">
                            <label class="form-label">Upload Banner / Gambar</label>
                            
                            @if($informasi->banner_path)
                                <div class="mb-2">
                                    <label class="d-block small text-muted">Banner saat ini:</label>
                                    <img src="{{ getBannerInformasi($informasi->banner_path) }}" 
                                         alt="Current Banner" 
                                         class="img-thumbnail" 
                                         style="max-width: 300px;">
                                </div>
                            @endif

                            <input type="file" 
                                   name="banner" 
                                   class="form-control @error('banner') is-invalid @enderror"
                                   accept="image/*"
                                   id="banner_input">
                            <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB. Kosongkan jika tidak ingin mengubah banner.</small>
                            @error('banner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview -->
                            <div class="mt-2" id="banner_preview" style="display:none;">
                                <label class="d-block small text-muted">Preview baru:</label>
                                <img src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>

                        <!-- Link URL (conditional) -->
                        <div class="col-12 mb-3" id="link_url_section" style="display:none;">
                            <label class="form-label">URL Link</label>
                            <input type="url" 
                                   name="link_url" 
                                   class="form-control @error('link_url') is-invalid @enderror" 
                                   value="{{ old('link_url', $informasi->link_url) }}"
                                   placeholder="https://example.com">
                            @error('link_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Periode Tampil -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai Tampil</label>
                            <input type="date" 
                                   name="tanggal_mulai" 
                                   class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                   value="{{ old('tanggal_mulai', $informasi->tanggal_mulai ? $informasi->tanggal_mulai->format('Y-m-d') : '') }}">
                            <small class="text-muted">Kosongkan jika langsung aktif</small>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai Tampil</label>
                            <input type="date" 
                                   name="tanggal_selesai" 
                                   class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                   value="{{ old('tanggal_selesai', $informasi->tanggal_selesai ? $informasi->tanggal_selesai->format('Y-m-d') : '') }}">
                            <small class="text-muted">Kosongkan jika permanen</small>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active"
                                       {{ old('is_active', $informasi->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktifkan Informasi
                                </label>
                            </div>
                            <small class="text-muted">Jika dinonaktifkan, informasi tidak akan tampil di karyawan</small>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Statistik:</strong> Informasi ini sudah dibaca oleh <strong>{{ $informasi->reads->count() }}</strong> karyawan.
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="ti ti-device-floppy me-2"></i>Update Informasi
                        </button>
                        <a href="{{ route('admin.informasi.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-lg-4 col-sm-12">
        <div class="card">
            <div class="card-header bg-info">
                <h6 class="text-white mb-0">
                    <i class="ti ti-chart-bar me-2"></i>Detail Informasi
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $informasi->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $informasi->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Terakhir Update:</strong></td>
                        <td>{{ $informasi->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Dibaca:</strong></td>
                        <td>
                            <span class="badge bg-success">{{ $informasi->reads->count() }} karyawan</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    // Toggle section based on tipe informasi
    $(document).ready(function() {
        toggleSections();
        
        $('#tipe_informasi').on('change', function() {
            toggleSections();
        });

        function toggleSections() {
            const tipe = $('#tipe_informasi').val();
            
            if (tipe === 'banner') {
                $('#banner_upload_section').show();
                $('#link_url_section').hide();
            } else if (tipe === 'link') {
                $('#banner_upload_section').hide();
                $('#link_url_section').show();
            } else {
                $('#banner_upload_section').hide();
                $('#link_url_section').hide();
            }
        }

        // Preview banner
        $('#banner_input').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#banner_preview img').attr('src', e.target.result);
                    $('#banner_preview').show();
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush

@endsection
