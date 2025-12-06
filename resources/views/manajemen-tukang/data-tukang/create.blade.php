@extends('layouts.app')
@section('titlepage', 'Tambah Data Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang / Data Tukang /</span> Tambah Data
@endsection

<div class="row">
   <div class="col-12">
      <form action="{{ route('tukang.store') }}" method="POST" id="formTukang" enctype="multipart/form-data">
         @csrf
         <div class="card">
            <div class="card-header">
               <h5 class="mb-0">Form Tambah Data Tukang</h5>
            </div>
            <div class="card-body">
               <div class="row">
                  <!-- Kode Tukang -->
                  <div class="col-md-6 mb-3">
                     <label for="kode_tukang" class="form-label">Kode Tukang <span class="text-danger">*</span></label>
                     <input type="text" class="form-control @error('kode_tukang') is-invalid @enderror" 
                        id="kode_tukang" name="kode_tukang" value="{{ old('kode_tukang', $kode_tukang) }}" 
                        placeholder="Contoh: TK001" readonly style="background-color: #f0f0f0;">
                     <small class="text-muted">
                        <i class="ti ti-info-circle"></i> Kode dibuat otomatis oleh sistem
                     </small>
                     @error('kode_tukang')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Nama Tukang -->
                  <div class="col-md-6 mb-3">
                     <label for="nama_tukang" class="form-label">Nama Tukang <span class="text-danger">*</span></label>
                     <input type="text" class="form-control @error('nama_tukang') is-invalid @enderror" 
                        id="nama_tukang" name="nama_tukang" value="{{ old('nama_tukang') }}" 
                        placeholder="Nama lengkap tukang" required>
                     @error('nama_tukang')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- NIK -->
                  <div class="col-md-6 mb-3">
                     <label for="nik" class="form-label">NIK</label>
                     <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                        id="nik" name="nik" value="{{ old('nik') }}" 
                        placeholder="Nomor Induk Kependudukan" maxlength="20">
                     @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- No HP -->
                  <div class="col-md-6 mb-3">
                     <label for="no_hp" class="form-label">No HP</label>
                     <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                        id="no_hp" name="no_hp" value="{{ old('no_hp') }}" 
                        placeholder="08xxxxxxxxxx" maxlength="20">
                     @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Email -->
                  <div class="col-md-6 mb-3">
                     <label for="email" class="form-label">Email</label>
                     <input type="email" class="form-control @error('email') is-invalid @enderror" 
                        id="email" name="email" value="{{ old('email') }}" 
                        placeholder="email@example.com">
                     @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Keahlian -->
                  <div class="col-md-6 mb-3">
                     <label for="keahlian" class="form-label">Keahlian</label>
                     <input type="text" class="form-control @error('keahlian') is-invalid @enderror" 
                        id="keahlian" name="keahlian" value="{{ old('keahlian') }}" 
                        placeholder="Contoh: Tukang Batu, Tukang Cat, dll">
                     @error('keahlian')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Tarif Harian -->
                  <div class="col-md-6 mb-3">
                     <label for="tarif_harian" class="form-label">Tarif Harian (Rp)</label>
                     <input type="number" class="form-control @error('tarif_harian') is-invalid @enderror" 
                        id="tarif_harian" name="tarif_harian" value="{{ old('tarif_harian') }}" 
                        placeholder="150000" step="0.01">
                     @error('tarif_harian')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Status -->
                  <div class="col-md-6 mb-3">
                     <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                     <select class="form-select @error('status') is-invalid @enderror" 
                        id="status" name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                     </select>
                     @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Alamat -->
                  <div class="col-md-12 mb-3">
                     <label for="alamat" class="form-label">Alamat</label>
                     <textarea class="form-control @error('alamat') is-invalid @enderror" 
                        id="alamat" name="alamat" rows="3" 
                        placeholder="Alamat lengkap tukang">{{ old('alamat') }}</textarea>
                     @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Keterangan -->
                  <div class="col-md-12 mb-3">
                     <label for="keterangan" class="form-label">Keterangan</label>
                     <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                        id="keterangan" name="keterangan" rows="3" 
                        placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
                     @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- Foto -->
                  <div class="col-md-12 mb-3">
                     <label for="foto" class="form-label">Foto</label>
                     <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                        id="foto" name="foto" accept="image/jpeg,image/jpg,image/png">
                     <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                     @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     <div id="preview-foto" class="mt-2"></div>
                  </div>
               </div>
            </div>
            <div class="card-footer">
               <div class="d-flex justify-content-between">
                  <a href="{{ route('tukang.index') }}" class="btn btn-secondary">
                     <i class="ti ti-arrow-left me-1"></i> Kembali
                  </a>
                  <button type="submit" class="btn btn-primary">
                     <i class="ti ti-device-floppy me-1"></i> Simpan
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
   $(function() {
      // Preview foto
      $('#foto').on('change', function() {
         const file = this.files[0];
         if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
               $('#preview-foto').html(`
                  <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">
               `);
            }
            reader.readAsDataURL(file);
         }
      });

      // Form validation
      $('#formTukang').submit(function(e) {
         const kode = $('#kode_tukang').val();
         const nama = $('#nama_tukang').val();
         const status = $('#status').val();

         if (!kode || !nama || !status) {
            e.preventDefault();
            Swal.fire({
               icon: 'error',
               title: 'Oops...',
               text: 'Harap isi semua field yang wajib diisi!',
            });
            return false;
         }
      });
   });
</script>
@endpush
