@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Pelanggaran Santri
                </div>
                <h2 class="page-title">
                    Edit Data Pelanggaran
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('pelanggaran-santri.update', $pelanggaranSantri->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Pelanggaran Santri</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Pilih Santri</label>
                                        <select name="user_id" id="user_id"
                                            class="form-select @error('user_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Santri --</option>
                                            @foreach($santriList as $santri)
                                            <option value="{{ $santri->id }}" data-name="{{ $santri->nama_lengkap }}"
                                                data-nik="{{ $santri->nik }}" {{ old('user_id',
                                                $pelanggaranSantri->user_id)==$santri->id ? 'selected' : '' }}>
                                                {{ $santri->nama_lengkap }} ({{ $santri->nik ?? 'No NIK' }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Nama Santri</label>
                                        <input type="text" name="nama_santri" id="nama_santri"
                                            class="form-control @error('nama_santri') is-invalid @enderror"
                                            value="{{ old('nama_santri', $pelanggaranSantri->nama_santri) }}" required
                                            readonly>
                                        @error('nama_santri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">NIK Santri</label>
                                        <input type="text" name="nik_santri" id="nik_santri"
                                            class="form-control @error('nik_santri') is-invalid @enderror"
                                            value="{{ old('nik_santri', $pelanggaranSantri->nik_santri) }}" readonly>
                                        @error('nik_santri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Tanggal Pelanggaran</label>
                                        <input type="date" name="tanggal_pelanggaran"
                                            class="form-control @error('tanggal_pelanggaran') is-invalid @enderror"
                                            value="{{ old('tanggal_pelanggaran', $pelanggaranSantri->tanggal_pelanggaran->format('Y-m-d')) }}"
                                            required>
                                        @error('tanggal_pelanggaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Point Pelanggaran</label>
                                        <input type="number" name="point"
                                            class="form-control @error('point') is-invalid @enderror"
                                            value="{{ old('point', $pelanggaranSantri->point) }}" min="1" max="10" required>
                                        @error('point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Range 1-10 (1-4: Ringan, 5-7: Sedang, 8-10: Berat)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Foto Pelanggaran</label>
                                        <input type="file" name="foto" id="foto"
                                            class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                        @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Format: JPG, JPEG, PNG. Maksimal 5MB. Kosongkan jika
                                            tidak ingin mengubah foto</small>
                                    </div>

                                    <!-- Preview Foto Lama -->
                                    @if($pelanggaranSantri->foto)
                                    <div class="mb-3">
                                        <label class="form-label">Foto Saat Ini:</label><br>
                                        <img src="{{ asset('storage/' . $pelanggaranSantri->foto) }}" alt="Foto"
                                            class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    @endif

                                    <!-- Preview Foto Baru -->
                                    <div id="preview-container" class="mb-3" style="display: none;">
                                        <label class="form-label">Preview Foto Baru:</label><br>
                                        <img id="preview-image" src="" alt="Preview" class="img-thumbnail"
                                            style="max-width: 200px;">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label required">Keterangan Pelanggaran</label>
                                        <textarea name="keterangan" rows="5"
                                            class="form-control @error('keterangan') is-invalid @enderror"
                                            placeholder="Jelaskan detail pelanggaran yang dilakukan..."
                                            required>{{ old('keterangan', $pelanggaranSantri->keterangan) }}</textarea>
                                        @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('pelanggaran-santri.index') }}" class="btn btn-secondary me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <line x1="5" y1="12" x2="9" y2="16"></line>
                                    <line x1="5" y1="12" x2="9" y2="8"></line>
                                </svg>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                    </path>
                                    <path d="M16 5l3 3"></path>
                                </svg>
                                Update Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    // Auto fill nama dan nik saat santri dipilih
    document.getElementById('user_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const nama = selectedOption.getAttribute('data-name');
        const nik = selectedOption.getAttribute('data-nik');
        
        document.getElementById('nama_santri').value = nama || '';
        document.getElementById('nik_santri').value = nik || '';
    });

    // Preview foto
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
