@extends('layouts.app')
@section('titlepage', 'Tambah Administrasi')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Administrasi / Tambah Data</span>
@endsection

<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('administrasi.store') }}" method="POST" enctype="multipart/form-data" id="formAdministrasi">
            @csrf
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ti ti-file-plus me-2"></i>Form Tambah Administrasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Jenis Administrasi -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Administrasi <span class="text-danger">*</span></label>
                                <select name="jenis_administrasi" id="jenis_administrasi" class="form-select @error('jenis_administrasi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis Administrasi --</option>
                                    @foreach($jenisAdministrasi as $key => $value)
                                        <option value="{{ $key }}" {{ old('jenis_administrasi', $administrasi->jenis_administrasi) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_administrasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Nomor Surat -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nomor Surat / Dokumen</label>
                                <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" 
                                       value="{{ old('nomor_surat', $administrasi->nomor_surat) }}" placeholder="Contoh: 001/ADM/2024">
                                @error('nomor_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">* Opsional - Isi jika ada nomor surat/dokumen</small>
                            </div>
                        </div>

                        <!-- Perihal -->
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Perihal / Judul <span class="text-danger">*</span></label>
                                <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" 
                                       value="{{ old('perihal', $administrasi->perihal) }}" placeholder="Masukkan perihal atau judul dokumen" required>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pengirim (for masuk) -->
                        <div class="col-lg-6" id="field-pengirim" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Pengirim</label>
                                <input type="text" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror" 
                                       value="{{ old('pengirim', $administrasi->pengirim) }}" placeholder="Nama pengirim">
                                @error('pengirim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Penerima (for keluar) -->
                        <div class="col-lg-6" id="field-penerima" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Penerima</label>
                                <input type="text" name="penerima" class="form-control @error('penerima') is-invalid @enderror" 
                                       value="{{ old('penerima', $administrasi->penerima) }}" placeholder="Nama penerima">
                                @error('penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Surat -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Surat / Dokumen</label>
                                <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" 
                                       value="{{ old('tanggal_surat', date('Y-m-d')) }}">
                                @error('tanggal_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Terima/Kirim -->
                        <div class="col-lg-6" id="field-tanggal-terima" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Terima</label>
                                <input type="datetime-local" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" 
                                       value="{{ old('tanggal_terima', $administrasi->tanggal_terima) }}">
                                @error('tanggal_terima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-tanggal-kirim" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Kirim</label>
                                <input type="datetime-local" name="tanggal_kirim" class="form-control @error('tanggal_kirim') is-invalid @enderror" 
                                       value="{{ old('tanggal_kirim', $administrasi->tanggal_kirim) }}">
                                @error('tanggal_kirim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Prioritas -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                                    <option value="normal" {{ old('prioritas', $administrasi->prioritas) == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="rendah" {{ old('prioritas', $administrasi->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                    <option value="tinggi" {{ old('prioritas', $administrasi->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="urgent" {{ old('prioritas', $administrasi->prioritas) == 'urgent' ? 'selected' : '' }}>URGENT</option>
                                </select>
                                @error('prioritas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $administrasi->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="proses" {{ old('status', $administrasi->status) == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ old('status', $administrasi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak" {{ old('status', $administrasi->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="expired" {{ old('status', $administrasi->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ringkasan -->
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Ringkasan / Isi Singkat</label>
                                <textarea name="ringkasan" class="form-control @error('ringkasan') is-invalid @enderror" 
                                          rows="3" placeholder="Ringkasan singkat isi dokumen">{{ old('ringkasan', $administrasi->ringkasan) }}</textarea>
                                @error('ringkasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Disposisi Ke -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Disposisi Ke</label>
                                <input type="text" name="disposisi_ke" class="form-control @error('disposisi_ke') is-invalid @enderror" 
                                       value="{{ old('disposisi_ke', $administrasi->disposisi_ke) }}" placeholder="Nama bagian/orang yang didisposisikan">
                                @error('disposisi_ke')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">* Opsional - Isi jika dokumen perlu didisposisikan</small>
                            </div>
                        </div>

                        <!-- Cabang -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Cabang</label>
                                <select name="cabang_id" class="form-select @error('cabang_id') is-invalid @enderror">
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($cabangs as $cabang)
                                        <option value="{{ $cabang->id }}" {{ old('cabang_id', $administrasi->cabang_id) == $cabang->id ? 'selected' : '' }}>
                                            {{ $cabang->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cabang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Upload File Dokumen -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Upload Dokumen (PDF, Word, Excel)</label>
                                <input type="file" name="file_dokumen" class="form-control @error('file_dokumen') is-invalid @enderror" 
                                       accept=".pdf,.doc,.docx,.xls,.xlsx">
                                @error('file_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Max: 10MB - Format: PDF, DOC, DOCX, XLS, XLSX</small>
                            </div>
                        </div>

                        <!-- Upload Foto -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Upload Foto Dokumen (JPG, PNG)</label>
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" 
                                       accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Max: 2MB - Format: JPG, PNG</small>
                                <div id="preview-container" class="mt-2" style="display: none;">
                                    <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                          rows="2" placeholder="Catatan tambahan">{{ old('catatan', $administrasi->catatan) }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                                          rows="2" placeholder="Keterangan tambahan">{{ old('keterangan', $administrasi->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('administrasi.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
<script>
    // Dynamic field visibility based on jenis_administrasi
    document.getElementById('jenis_administrasi').addEventListener('change', function() {
        const value = this.value;
        const isMasuk = ['surat_masuk', 'undangan_masuk', 'proposal_masuk', 'paket_masuk'].includes(value);
        const isKeluar = ['surat_keluar', 'undangan_keluar', 'proposal_keluar', 'paket_keluar'].includes(value);
        
        // Show/hide pengirim field
        document.getElementById('field-pengirim').style.display = isMasuk ? 'block' : 'none';
        
        // Show/hide penerima field
        document.getElementById('field-penerima').style.display = isKeluar ? 'block' : 'none';
        
        // Show/hide tanggal terima/kirim
        document.getElementById('field-tanggal-terima').style.display = isMasuk ? 'block' : 'none';
        document.getElementById('field-tanggal-kirim').style.display = isKeluar ? 'block' : 'none';
    });

    // Preview image
    function previewImage(input) {
        const preview = document.getElementById('preview-image');
        const container = document.getElementById('preview-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation
    document.getElementById('formAdministrasi').addEventListener('submit', function(e) {
        const jenisAdministrasi = document.getElementById('jenis_administrasi').value;
        
        if (!jenisAdministrasi) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Silakan pilih jenis administrasi terlebih dahulu',
            });
            return false;
        }
    });

    // Trigger change on page load to show appropriate fields
    document.getElementById('jenis_administrasi').dispatchEvent(new Event('change'));
</script>
@endpush
