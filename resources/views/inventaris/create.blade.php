@extends('layouts.app')
@section('titlepage', 'Tambah Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Tambah Data</span>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-plus me-2"></i>Tambah Inventaris Baru</h4>
                    <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('inventaris.store') }}" method="POST" enctype="multipart/form-data" id="formInventaris">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-barcode me-1"></i> Kode Inventaris <span class="text-danger">*</span></label>
                                <input type="text" name="kode_inventaris" class="form-control @error('kode_inventaris') is-invalid @enderror" 
                                    value="{{ old('kode_inventaris') }}" placeholder="Auto-generate jika kosong">
                                <small class="text-muted">Kosongkan untuk generate otomatis (INV-00001)</small>
                                @error('kode_inventaris')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-box me-1"></i> Nama Inventaris <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                                    value="{{ old('nama_barang') }}" placeholder="Nama inventaris" required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-category me-1"></i> Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="elektronik" {{ old('kategori') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="furnitur" {{ old('kategori') == 'furnitur' ? 'selected' : '' }}>Furnitur</option>
                                    <option value="kendaraan" {{ old('kategori') == 'kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                                    <option value="alat_kantor" {{ old('kategori') == 'alat_kantor' ? 'selected' : '' }}>Alat Kantor</option>
                                    <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-building me-1"></i> Cabang</label>
                                <select name="cabang_id" class="form-select @error('cabang_id') is-invalid @enderror">
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($cabangs as $c)
                                        <option value="{{ $c->id }}" {{ old('cabang_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cabang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-package me-1"></i> Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                                    value="{{ old('jumlah', 1) }}" min="1" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-ruler me-1"></i> Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Unit" {{ old('satuan') == 'Unit' ? 'selected' : '' }}>Unit</option>
                                    <option value="Pcs" {{ old('satuan') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="Set" {{ old('satuan') == 'Set' ? 'selected' : '' }}>Set</option>
                                    <option value="Box" {{ old('satuan') == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Lusin" {{ old('satuan') == 'Lusin' ? 'selected' : '' }}>Lusin</option>
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-map-pin me-1"></i> Lokasi</label>
                                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" 
                                    value="{{ old('lokasi') }}" placeholder="Lokasi penyimpanan">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-flag me-1"></i> Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="tersedia" {{ old('status', 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="rusak" {{ old('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="hilang" {{ old('status') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-check me-1"></i> Kondisi <span class="text-danger">*</span></label>
                                <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                    <option value="baik" {{ old('kondisi', 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-file-text me-1"></i> Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                            rows="3" placeholder="Deskripsi detail inventaris">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-notes me-1"></i> Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror" 
                            rows="3" placeholder="Spesifikasi teknis (opsional)">{{ old('spesifikasi') }}</textarea>
                        @error('spesifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Inventaris</label>
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" 
                                    accept="image/*" id="fotoInput">
                                <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="previewFoto" class="mt-2" style="display: none;">
                                <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-calendar me-1"></i> Tanggal Perolehan</label>
                                <input type="date" name="tanggal_perolehan" class="form-control @error('tanggal_perolehan') is-invalid @enderror" 
                                    value="{{ old('tanggal_perolehan', date('Y-m-d')) }}">
                                @error('tanggal_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-notes me-1"></i> Catatan Tambahan</label>
                        <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                            rows="2" placeholder="Catatan lain (opsional)">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Simpan Inventaris
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    // Preview foto sebelum upload
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewFoto').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    document.getElementById('formInventaris').addEventListener('submit', function(e) {
        const nama = document.querySelector('[name="nama"]').value;
        const kategori = document.querySelector('[name="kategori"]').value;
        const jumlah = document.querySelector('[name="jumlah"]').value;
        
        if (!nama || !kategori || !jumlah) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Nama, Kategori, dan Jumlah harus diisi!'
            });
            return false;
        }
    });
</script>
@endpush
@endsection
