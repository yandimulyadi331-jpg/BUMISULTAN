@extends('layouts.app')
@section('titlepage', 'Tambah Peminjaman Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Peminjaman / Tambah</span>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-hand-holding me-2"></i>Form Peminjaman Inventaris</h4>
                    <a href="{{ route('peminjaman-inventaris.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('peminjaman-inventaris.store') }}" method="POST" enctype="multipart/form-data" id="formPeminjaman">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-box me-1"></i> Inventaris <span class="text-danger">*</span></label>
                                <select name="inventaris_id" id="inventaris_id" class="form-select @error('inventaris_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Inventaris --</option>
                                    @foreach($inventaris as $item)
                                        <option value="{{ $item->id }}" 
                                            data-jumlah="{{ $item->jumlah }}"
                                            data-tersedia="{{ $item->jumlahTersedia() }}"
                                            data-satuan="{{ $item->satuan }}"
                                            {{ old('inventaris_id', request('inventaris_id')) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_barang }} ({{ $item->kode_inventaris }}) - Tersedia: {{ $item->jumlahTersedia() }} {{ $item->satuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventaris_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-user me-1"></i> Peminjam (Karyawan) <span class="text-danger">*</span></label>
                                <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach($karyawan as $k)
                                        <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama }} ({{ $k->nik }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('karyawan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-package me-1"></i> Jumlah Pinjam <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_pinjam" id="jumlah_pinjam" 
                                    class="form-control @error('jumlah_pinjam') is-invalid @enderror" 
                                    value="{{ old('jumlah_pinjam', 1) }}" min="1" required>
                                <small class="text-muted" id="info-tersedia">Tersedia: -</small>
                                @error('jumlah_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-calendar me-1"></i> Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_pinjam" 
                                    class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                    value="{{ old('tanggal_pinjam', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-calendar-event me-1"></i> Tanggal Kembali Rencana <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_kembali_rencana" 
                                    class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                                    value="{{ old('tanggal_kembali_rencana') }}" required>
                                @error('tanggal_kembali_rencana')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-file-text me-1"></i> Keperluan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                            rows="3" placeholder="Jelaskan keperluan peminjaman" required>{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Barang Saat Dipinjam</label>
                                <input type="file" name="foto_barang_pinjam" class="form-control @error('foto_barang_pinjam') is-invalid @enderror" 
                                    accept="image/*" id="fotoBarangInput">
                                <small class="text-muted">Upload foto kondisi barang saat dipinjam (opsional)</small>
                                @error('foto_barang_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="previewFotoBarang" class="mt-2" style="display: none;">
                                <img id="previewImageBarang" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-writing me-1"></i> TTD Digital Peminjam <span class="text-danger">*</span></label>
                                <div class="border rounded p-2">
                                    <canvas id="signature-pad-peminjam" width="400" height="200" style="border: 1px solid #ddd; width: 100%; height: 200px;"></canvas>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-warning" id="clear-signature-peminjam">
                                        <i class="fa fa-eraser me-1"></i>Hapus TTD
                                    </button>
                                </div>
                                <input type="hidden" name="ttd_peminjam" id="ttd_peminjam">
                                @error('ttd_peminjam')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-notes me-1"></i> Catatan</label>
                        <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                            rows="2" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Peminjaman akan masuk dengan status <strong>"Pending"</strong> dan menunggu approval dari admin/petugas.
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('peminjaman-inventaris.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    // Preview foto barang
    document.getElementById('fotoBarangInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImageBarang').src = e.target.result;
                document.getElementById('previewFotoBarang').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Signature Pad untuk Peminjam
    const canvasPeminjam = document.getElementById('signature-pad-peminjam');
    const signaturePadPeminjam = new SignaturePad(canvasPeminjam, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
    });

    document.getElementById('clear-signature-peminjam').addEventListener('click', function() {
        signaturePadPeminjam.clear();
    });

    // Update info ketersediaan saat pilih inventaris
    document.getElementById('inventaris_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const tersedia = selected.getAttribute('data-tersedia');
        const satuan = selected.getAttribute('data-satuan');
        
        if (tersedia) {
            document.getElementById('info-tersedia').textContent = `Tersedia: ${tersedia} ${satuan}`;
            document.getElementById('jumlah_pinjam').setAttribute('max', tersedia);
        } else {
            document.getElementById('info-tersedia').textContent = 'Tersedia: -';
        }
    });

    // Trigger change jika sudah ada yang dipilih
    if (document.getElementById('inventaris_id').value) {
        document.getElementById('inventaris_id').dispatchEvent(new Event('change'));
    }

    // Form validation
    document.getElementById('formPeminjaman').addEventListener('submit', function(e) {
        // Validasi signature
        if (signaturePadPeminjam.isEmpty()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'TTD Digital Diperlukan',
                text: 'Silakan buat tanda tangan digital peminjam terlebih dahulu!'
            });
            return false;
        }

        // Save signature to hidden input
        document.getElementById('ttd_peminjam').value = signaturePadPeminjam.toDataURL();

        // Validasi jumlah
        const jumlahPinjam = parseInt(document.getElementById('jumlah_pinjam').value);
        const selected = document.getElementById('inventaris_id').options[document.getElementById('inventaris_id').selectedIndex];
        const tersedia = parseInt(selected.getAttribute('data-tersedia'));

        if (jumlahPinjam > tersedia) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Jumlah Tidak Valid',
                text: `Jumlah pinjam tidak boleh melebihi ketersediaan (${tersedia})`
            });
            return false;
        }

        // Validasi tanggal
        const tanggalPinjam = new Date(document.querySelector('[name="tanggal_pinjam"]').value);
        const tanggalKembali = new Date(document.querySelector('[name="tanggal_kembali_rencana"]').value);

        if (tanggalKembali <= tanggalPinjam) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Tidak Valid',
                text: 'Tanggal kembali harus lebih besar dari tanggal pinjam!'
            });
            return false;
        }
    });
</script>
@endpush
@endsection
