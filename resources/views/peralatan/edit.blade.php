@extends('layouts.app')
@section('titlepage', 'Edit Peralatan BS')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peralatan.index') }}">PERALATAN BS</a> / Edit</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="ti ti-edit me-2"></i> Edit Peralatan</h4>
            </div>
            <form action="{{ route('peralatan.update', $peralatan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Peralatan <span class="text-danger">*</span></label>
                            <input type="text" name="kode_peralatan" class="form-control @error('kode_peralatan') is-invalid @enderror" 
                                value="{{ old('kode_peralatan', $peralatan->kode_peralatan) }}" required>
                            @error('kode_peralatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Peralatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_peralatan" class="form-control @error('nama_peralatan') is-invalid @enderror" 
                                value="{{ old('nama_peralatan', $peralatan->nama_peralatan) }}" required>
                            @error('nama_peralatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori', $peralatan->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                <option value="baik" {{ old('kondisi', $peralatan->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak ringan" {{ old('kondisi', $peralatan->kondisi) == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak berat" {{ old('kondisi', $peralatan->kondisi) == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stok_awal" class="form-control @error('stok_awal') is-invalid @enderror" 
                                value="{{ old('stok_awal', $peralatan->stok_awal) }}" min="0" required>
                            @error('stok_awal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                                value="{{ old('satuan', $peralatan->satuan) }}" required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control @error('stok_minimum') is-invalid @enderror" 
                                value="{{ old('stok_minimum', $peralatan->stok_minimum) }}" min="0">
                            @error('stok_minimum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <input type="text" name="lokasi_penyimpanan" class="form-control @error('lokasi_penyimpanan') is-invalid @enderror" 
                                value="{{ old('lokasi_penyimpanan', $peralatan->lokasi_penyimpanan) }}">
                            @error('lokasi_penyimpanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <input type="number" name="harga_satuan" class="form-control @error('harga_satuan') is-invalid @enderror" 
                                value="{{ old('harga_satuan', $peralatan->harga_satuan) }}" min="0" step="0.01">
                            @error('harga_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pembelian</label>
                            <input type="date" name="tanggal_pembelian" class="form-control @error('tanggal_pembelian') is-invalid @enderror" 
                                value="{{ old('tanggal_pembelian', $peralatan->tanggal_pembelian ? $peralatan->tanggal_pembelian->format('Y-m-d') : '') }}">
                            @error('tanggal_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier</label>
                            <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror" 
                                value="{{ old('supplier', $peralatan->supplier) }}">
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                rows="3">{{ old('deskripsi', $peralatan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Foto Peralatan</label>
                            @if($peralatan->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/peralatan/' . $peralatan->foto) }}" 
                                        alt="{{ $peralatan->nama_peralatan }}" 
                                        class="img-thumbnail" 
                                        style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" 
                                accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG. Maks: 2MB</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                rows="2">{{ old('catatan', $peralatan->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('peralatan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Perbarui
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
