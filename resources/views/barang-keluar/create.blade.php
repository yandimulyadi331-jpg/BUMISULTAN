@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('barang-keluar.index') }}">Barang Keluar</a></li>
                    <li class="breadcrumb-item active">Tambah Baru</li>
                </ol>
            </nav>
            <h4 class="mb-0"><i class="ti ti-package-export me-2"></i>Tambah Barang Keluar</h4>
        </div>
    </div>

    <form action="{{ route('barang-keluar.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            {{-- Form Utama --}}
            <div class="col-lg-8">
                
                {{-- Informasi Barang --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-box me-2"></i>Informasi Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Barang <span class="text-danger">*</span></label>
                                <select name="jenis_barang" class="form-select @error('jenis_barang') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="laundry" {{ old('jenis_barang') == 'laundry' ? 'selected' : '' }}>Laundry</option>
                                    <option value="perbaikan_sepatu" {{ old('jenis_barang') == 'perbaikan_sepatu' ? 'selected' : '' }}>Perbaikan Sepatu</option>
                                    <option value="perbaikan_elektronik" {{ old('jenis_barang') == 'perbaikan_elektronik' ? 'selected' : '' }}>Perbaikan Elektronik</option>
                                    <option value="perbaikan_furniture" {{ old('jenis_barang') == 'perbaikan_furniture' ? 'selected' : '' }}>Perbaikan Furniture</option>
                                    <option value="jahit_pakaian" {{ old('jenis_barang') == 'jahit_pakaian' ? 'selected' : '' }}>Jahit Pakaian</option>
                                    <option value="reparasi_kendaraan" {{ old('jenis_barang') == 'reparasi_kendaraan' ? 'selected' : '' }}>Reparasi Kendaraan</option>
                                    <option value="lainnya" {{ old('jenis_barang') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                                       value="{{ old('nama_barang') }}" placeholder="Contoh: Seragam Karyawan" required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="3" placeholder="Deskripsi detail barang...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                                       value="{{ old('jumlah', 1) }}" min="1" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                                       value="{{ old('satuan', 'pcs') }}" placeholder="pcs, pasang, buah, dll">
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kondisi Keluar <span class="text-danger">*</span></label>
                                <select name="kondisi_keluar" class="form-select @error('kondisi_keluar') is-invalid @enderror" required>
                                    <option value="baik" {{ old('kondisi_keluar') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi_keluar') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi_keluar') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                                    <option value="normal" {{ old('prioritas', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                    <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="urgent" {{ old('prioritas') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('prioritas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pemilik --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-user me-2"></i>Informasi Pemilik Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" name="pemilik_barang" class="form-control @error('pemilik_barang') is-invalid @enderror" 
                                       value="{{ old('pemilik_barang') }}" placeholder="Nama karyawan/departemen" required>
                                @error('pemilik_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Departemen</label>
                                <select name="departemen" class="form-select @error('departemen') is-invalid @enderror">
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->nama_departemen }}" {{ old('departemen') == $dept->nama_departemen ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departemen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Telp Pemilik</label>
                                <input type="text" name="no_telp_pemilik" class="form-control @error('no_telp_pemilik') is-invalid @enderror" 
                                       value="{{ old('no_telp_pemilik') }}" placeholder="08xxxxxxxxxx">
                                @error('no_telp_pemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Vendor --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-building-store me-2"></i>Informasi Vendor/Tempat Jasa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Vendor <span class="text-danger">*</span></label>
                                <input type="text" name="nama_vendor" class="form-control @error('nama_vendor') is-invalid @enderror" 
                                       value="{{ old('nama_vendor') }}" placeholder="Nama toko/tempat jasa" required>
                                @error('nama_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">PIC Vendor</label>
                                <input type="text" name="pic_vendor" class="form-control @error('pic_vendor') is-invalid @enderror" 
                                       value="{{ old('pic_vendor') }}" placeholder="Nama kontak person">
                                @error('pic_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat Vendor</label>
                                <textarea name="alamat_vendor" class="form-control @error('alamat_vendor') is-invalid @enderror" 
                                          rows="2" placeholder="Alamat lengkap vendor...">{{ old('alamat_vendor') }}</textarea>
                                @error('alamat_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Telp Vendor</label>
                                <input type="text" name="no_telp_vendor" class="form-control @error('no_telp_vendor') is-invalid @enderror" 
                                       value="{{ old('no_telp_vendor') }}" placeholder="08xxxxxxxxxx">
                                @error('no_telp_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Foto Dokumentasi --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-camera me-2"></i>Foto Dokumentasi Sebelum</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Foto (Maksimal 5 foto)</label>
                            <input type="file" name="foto_sebelum[]" class="form-control @error('foto_sebelum') is-invalid @enderror" 
                                   accept="image/*" multiple id="fotoSebelumInput">
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB per foto</small>
                            @error('foto_sebelum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="fotoSebelumPreview" class="row g-2"></div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                
                {{-- Tanggal & Waktu --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Tanggal & Waktu</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tanggal_keluar" 
                                   class="form-control @error('tanggal_keluar') is-invalid @enderror" 
                                   value="{{ old('tanggal_keluar', date('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimasi Kembali</label>
                            <input type="date" name="estimasi_kembali" 
                                   class="form-control @error('estimasi_kembali') is-invalid @enderror" 
                                   value="{{ old('estimasi_kembali') }}">
                            <small class="text-muted">Perkiraan kapan barang akan kembali</small>
                            @error('estimasi_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Biaya --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-cash me-2"></i>Estimasi Biaya</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Estimasi Biaya (Rp)</label>
                            <input type="number" name="estimasi_biaya" 
                                   class="form-control @error('estimasi_biaya') is-invalid @enderror" 
                                   value="{{ old('estimasi_biaya', 0) }}" min="0" step="1000">
                            <small class="text-muted">Perkiraan biaya yang akan dikeluarkan</small>
                            @error('estimasi_biaya')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-note me-2"></i>Catatan</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="catatan_keluar" class="form-control @error('catatan_keluar') is-invalid @enderror" 
                                  rows="4" placeholder="Catatan tambahan...">{{ old('catatan_keluar') }}</textarea>
                        @error('catatan_keluar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Data
                        </button>
                        <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary w-100">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>

@push('scripts')
<script>
// Preview foto sebelum upload
document.getElementById('fotoSebelumInput').addEventListener('change', function(e) {
    const preview = document.getElementById('fotoSebelumPreview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files).slice(0, 5); // Limit 5 files
    
    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                        <div class="card-body p-2 text-center">
                            <small class="text-muted">Foto ${index + 1}</small>
                        </div>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
@endsection
