@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('barang-keluar.index') }}">Barang Keluar</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('barang-keluar.show', $barangKeluar->id) }}">{{ $barangKeluar->kode_transaksi }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <h4 class="mb-0"><i class="ti ti-edit me-2"></i>Edit Barang Keluar</h4>
        </div>
    </div>

    <form action="{{ route('barang-keluar.update', $barangKeluar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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
                                    <option value="laundry" {{ old('jenis_barang', $barangKeluar->jenis_barang) == 'laundry' ? 'selected' : '' }}>Laundry</option>
                                    <option value="perbaikan_sepatu" {{ old('jenis_barang', $barangKeluar->jenis_barang) == 'perbaikan_sepatu' ? 'selected' : '' }}>Perbaikan Sepatu</option>
                                    <option value="perbaikan_elektronik" {{ old('jenis_barang', $barangKeluar->jenis_barang) == 'perbaikan_elektronik' ? 'selected' : '' }}>Perbaikan Elektronik</option>
                                    <option value="perbaikan_furniture" {{ old('jenis_barang', $barangKeluar->jenis_barang) == 'perbaikan_furniture' ? 'selected' : '' }}>Perbaikan Furniture</option>
                                    <option value="jahit_pakaian" {{ old('jenis_barang', $barangKeluar->jenis_barang) == 'jahit_pakaian' ? 'selected' : '' }}>Jahit Pakaian</option>
                                    <option value="reparasi_kendaraan" {{ old('jenis_barang', $barangKeluar->jenis_barang) == 'reparasi_kendaraan' ? 'selected' : '' }}>Reparasi Kendaraan</option>
                                    <option value="lainnya" {{ old('jenis_barang', $barangKeluar->jenis_barang) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                                       value="{{ old('nama_barang', $barangKeluar->nama_barang) }}" required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="3">{{ old('deskripsi', $barangKeluar->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                                       value="{{ old('jumlah', $barangKeluar->jumlah) }}" min="1" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                                       value="{{ old('satuan', $barangKeluar->satuan) }}">
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                                    <option value="rendah" {{ old('prioritas', $barangKeluar->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                    <option value="normal" {{ old('prioritas', $barangKeluar->prioritas) == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="tinggi" {{ old('prioritas', $barangKeluar->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="urgent" {{ old('prioritas', $barangKeluar->prioritas) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('prioritas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kondisi Keluar <span class="text-danger">*</span></label>
                                <select name="kondisi_keluar" class="form-select @error('kondisi_keluar') is-invalid @enderror" required>
                                    <option value="baik" {{ old('kondisi_keluar', $barangKeluar->kondisi_keluar) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi_keluar', $barangKeluar->kondisi_keluar) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi_keluar', $barangKeluar->kondisi_keluar) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kondisi Kembali</label>
                                <select name="kondisi_kembali" class="form-select @error('kondisi_kembali') is-invalid @enderror">
                                    <option value="">Belum Kembali</option>
                                    <option value="baik" {{ old('kondisi_kembali', $barangKeluar->kondisi_kembali) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi_kembali', $barangKeluar->kondisi_kembali) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi_kembali', $barangKeluar->kondisi_kembali) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    <option value="hilang" {{ old('kondisi_kembali', $barangKeluar->kondisi_kembali) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('kondisi_kembali')
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
                                       value="{{ old('pemilik_barang', $barangKeluar->pemilik_barang) }}" required>
                                @error('pemilik_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Departemen</label>
                                <select name="departemen" class="form-select @error('departemen') is-invalid @enderror">
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->nama_departemen }}" {{ old('departemen', $barangKeluar->departemen) == $dept->nama_departemen ? 'selected' : '' }}>
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
                                       value="{{ old('no_telp_pemilik', $barangKeluar->no_telp_pemilik) }}">
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
                                       value="{{ old('nama_vendor', $barangKeluar->nama_vendor) }}" required>
                                @error('nama_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">PIC Vendor</label>
                                <input type="text" name="pic_vendor" class="form-control @error('pic_vendor') is-invalid @enderror" 
                                       value="{{ old('pic_vendor', $barangKeluar->pic_vendor) }}">
                                @error('pic_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat Vendor</label>
                                <textarea name="alamat_vendor" class="form-control @error('alamat_vendor') is-invalid @enderror" 
                                          rows="2">{{ old('alamat_vendor', $barangKeluar->alamat_vendor) }}</textarea>
                                @error('alamat_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Telp Vendor</label>
                                <input type="text" name="no_telp_vendor" class="form-control @error('no_telp_vendor') is-invalid @enderror" 
                                       value="{{ old('no_telp_vendor', $barangKeluar->no_telp_vendor) }}">
                                @error('no_telp_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rating Vendor (1-5)</label>
                                <input type="number" name="rating_vendor" class="form-control @error('rating_vendor') is-invalid @enderror" 
                                       value="{{ old('rating_vendor', $barangKeluar->rating_vendor) }}" min="1" max="5">
                                @error('rating_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Review Vendor</label>
                                <textarea name="review_vendor" class="form-control @error('review_vendor') is-invalid @enderror" 
                                          rows="2" placeholder="Review pelayanan vendor...">{{ old('review_vendor', $barangKeluar->review_vendor) }}</textarea>
                                @error('review_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
                                   value="{{ old('tanggal_keluar', $barangKeluar->tanggal_keluar->format('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimasi Kembali</label>
                            <input type="date" name="estimasi_kembali" 
                                   class="form-control @error('estimasi_kembali') is-invalid @enderror" 
                                   value="{{ old('estimasi_kembali', $barangKeluar->estimasi_kembali?->format('Y-m-d')) }}">
                            @error('estimasi_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Biaya --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-cash me-2"></i>Biaya</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Estimasi Biaya (Rp)</label>
                            <input type="number" name="estimasi_biaya" 
                                   class="form-control @error('estimasi_biaya') is-invalid @enderror" 
                                   value="{{ old('estimasi_biaya', $barangKeluar->estimasi_biaya) }}" min="0" step="1000">
                            @error('estimasi_biaya')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biaya Aktual (Rp)</label>
                            <input type="number" name="biaya_aktual" 
                                   class="form-control @error('biaya_aktual') is-invalid @enderror" 
                                   value="{{ old('biaya_aktual', $barangKeluar->biaya_aktual) }}" min="0" step="1000">
                            @error('biaya_aktual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Nota</label>
                            <input type="file" name="foto_nota" class="form-control @error('foto_nota') is-invalid @enderror" accept="image/*,application/pdf">
                            @if($barangKeluar->foto_nota)
                                <small class="text-muted">File saat ini: <a href="{{ asset('storage/' . $barangKeluar->foto_nota) }}" target="_blank">Lihat Nota</a></small>
                            @endif
                            @error('foto_nota')
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
                        <div class="mb-3">
                            <label class="form-label">Catatan Keluar</label>
                            <textarea name="catatan_keluar" class="form-control @error('catatan_keluar') is-invalid @enderror" 
                                      rows="3">{{ old('catatan_keluar', $barangKeluar->catatan_keluar) }}</textarea>
                            @error('catatan_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Vendor</label>
                            <textarea name="catatan_vendor" class="form-control @error('catatan_vendor') is-invalid @enderror" 
                                      rows="3">{{ old('catatan_vendor', $barangKeluar->catatan_vendor) }}</textarea>
                            @error('catatan_vendor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Catatan Kembali</label>
                            <textarea name="catatan_kembali" class="form-control @error('catatan_kembali') is-invalid @enderror" 
                                      rows="3">{{ old('catatan_kembali', $barangKeluar->catatan_kembali) }}</textarea>
                            @error('catatan_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="ti ti-device-floppy me-1"></i> Update Data
                        </button>
                        <a href="{{ route('barang-keluar.show', $barangKeluar->id) }}" class="btn btn-secondary w-100">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>
@endsection
