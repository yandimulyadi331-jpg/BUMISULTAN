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
                                        <option value="{{ $key }}" {{ old('jenis_administrasi') == $key ? 'selected' : '' }}>
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
                                       value="{{ old('nomor_surat') }}" placeholder="Contoh: 001/ADM/2024">
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
                                       value="{{ old('perihal') }}" placeholder="Masukkan perihal atau judul dokumen" required>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- DETAIL ACARA UNDANGAN (hanya untuk undangan_masuk & undangan_keluar) -->
                        <div class="col-lg-12" id="section-undangan" style="display: none;">
                            <div class="alert alert-info">
                                <strong><i class="ti ti-calendar-event me-2"></i>Detail Acara Undangan</strong>
                            </div>
                        </div>

                        <div class="col-lg-12" id="field-nama-acara" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Acara <span class="text-danger">*</span></label>
                                <input type="text" name="nama_acara" class="form-control @error('nama_acara') is-invalid @enderror" 
                                       value="{{ old('nama_acara') }}" placeholder="Contoh: Rapat Tahunan 2025">
                                @error('nama_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-tanggal-acara-mulai" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Mulai Acara <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_acara_mulai" class="form-control @error('tanggal_acara_mulai') is-invalid @enderror" 
                                       value="{{ old('tanggal_acara_mulai') }}">
                                @error('tanggal_acara_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-tanggal-acara-selesai" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Selesai Acara</label>
                                <input type="date" name="tanggal_acara_selesai" class="form-control @error('tanggal_acara_selesai') is-invalid @enderror" 
                                       value="{{ old('tanggal_acara_selesai') }}">
                                @error('tanggal_acara_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">* Kosongkan jika acara hanya 1 hari</small>
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-waktu-acara-mulai" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Waktu Mulai</label>
                                <input type="time" name="waktu_acara_mulai" class="form-control @error('waktu_acara_mulai') is-invalid @enderror" 
                                       value="{{ old('waktu_acara_mulai') }}">
                                @error('waktu_acara_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-waktu-acara-selesai" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Waktu Selesai</label>
                                <input type="time" name="waktu_acara_selesai" class="form-control @error('waktu_acara_selesai') is-invalid @enderror" 
                                       value="{{ old('waktu_acara_selesai') }}">
                                @error('waktu_acara_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-lokasi-acara" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Lokasi / Tempat Acara <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi_acara" class="form-control @error('lokasi_acara') is-invalid @enderror" 
                                       value="{{ old('lokasi_acara') }}" placeholder="Contoh: Gedung Graha Bhakti">
                                @error('lokasi_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-dress-code" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Dress Code</label>
                                <input type="text" name="dress_code" class="form-control @error('dress_code') is-invalid @enderror" 
                                       value="{{ old('dress_code') }}" placeholder="Contoh: Batik, Formal, Casual">
                                @error('dress_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12" id="field-alamat-acara" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Alamat Lengkap Acara</label>
                                <textarea name="alamat_acara" class="form-control @error('alamat_acara') is-invalid @enderror" 
                                          rows="2" placeholder="Alamat lengkap lokasi acara">{{ old('alamat_acara') }}</textarea>
                                @error('alamat_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12" id="field-catatan-acara" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Catatan Acara</label>
                                <textarea name="catatan_acara" class="form-control @error('catatan_acara') is-invalid @enderror" 
                                          rows="2" placeholder="Catatan khusus atau informasi tambahan acara">{{ old('catatan_acara') }}</textarea>
                                @error('catatan_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- END DETAIL ACARA UNDANGAN -->

                        <!-- Pengirim (for masuk) -->
                        <div class="col-lg-6" id="field-pengirim" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Pengirim</label>
                                <input type="text" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror" 
                                       value="{{ old('pengirim') }}" placeholder="Nama pengirim">
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
                                       value="{{ old('penerima') }}" placeholder="Nama penerima">
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
                                       value="{{ old('tanggal_terima') }}">
                                @error('tanggal_terima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6" id="field-tanggal-kirim" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Kirim</label>
                                <input type="datetime-local" name="tanggal_kirim" class="form-control @error('tanggal_kirim') is-invalid @enderror" 
                                       value="{{ old('tanggal_kirim') }}">
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
                                    <option value="normal" {{ old('prioritas') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                    <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="urgent" {{ old('prioritas') == 'urgent' ? 'selected' : '' }}>URGENT</option>
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
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
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
                                          rows="3" placeholder="Ringkasan singkat isi dokumen">{{ old('ringkasan') }}</textarea>
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
                                       value="{{ old('disposisi_ke') }}" placeholder="Nama bagian/orang yang didisposisikan">
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
                                        <option value="{{ $cabang->id }}" {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>
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
                                          rows="2" placeholder="Catatan tambahan">{{ old('catatan') }}</textarea>
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
                                          rows="2" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
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
        const isUndangan = ['undangan_masuk', 'undangan_keluar'].includes(value);
        
        // Show/hide pengirim field
        document.getElementById('field-pengirim').style.display = isMasuk ? 'block' : 'none';
        
        // Show/hide penerima field
        document.getElementById('field-penerima').style.display = isKeluar ? 'block' : 'none';
        
        // Show/hide tanggal terima/kirim
        document.getElementById('field-tanggal-terima').style.display = isMasuk ? 'block' : 'none';
        document.getElementById('field-tanggal-kirim').style.display = isKeluar ? 'block' : 'none';

        // Show/hide UNDANGAN FIELDS
        document.getElementById('section-undangan').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-nama-acara').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-tanggal-acara-mulai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-tanggal-acara-selesai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-waktu-acara-mulai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-waktu-acara-selesai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-lokasi-acara').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-alamat-acara').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-dress-code').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-catatan-acara').style.display = isUndangan ? 'block' : 'none';

        // Set required attribute for undangan fields
        const namaAcara = document.querySelector('[name="nama_acara"]');
        const tanggalMulai = document.querySelector('[name="tanggal_acara_mulai"]');
        const lokasiAcara = document.querySelector('[name="lokasi_acara"]');
        
        if (isUndangan) {
            namaAcara.setAttribute('required', 'required');
            tanggalMulai.setAttribute('required', 'required');
            lokasiAcara.setAttribute('required', 'required');
        } else {
            namaAcara.removeAttribute('required');
            tanggalMulai.removeAttribute('required');
            lokasiAcara.removeAttribute('required');
        }
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

        // Validate undangan fields
        const isUndangan = ['undangan_masuk', 'undangan_keluar'].includes(jenisAdministrasi);
        if (isUndangan) {
            const namaAcara = document.querySelector('[name="nama_acara"]').value;
            const tanggalMulai = document.querySelector('[name="tanggal_acara_mulai"]').value;
            const lokasiAcara = document.querySelector('[name="lokasi_acara"]').value;

            if (!namaAcara || !tanggalMulai || !lokasiAcara) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Undangan Tidak Lengkap!',
                    text: 'Harap isi Nama Acara, Tanggal Mulai, dan Lokasi Acara',
                });
                return false;
            }
        }
    });

    // Trigger change on page load to show appropriate fields
    document.getElementById('jenis_administrasi').dispatchEvent(new Event('change'));
</script>
@endpush
