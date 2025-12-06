@extends('layouts.app')
@section('titlepage', 'Tambah Transaksi Keuangan')

@section('content')
@section('navigasi')
    <span><a href="{{ route('keuangan-santri.index') }}">Keuangan Santri</a> / Tambah Transaksi</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-sm-12 mx-auto">
        <div class="card">
            <div class="card-header bg-gradient-success">
                <h5 class="text-white mb-0">
                    <i class="ti ti-plus me-2"></i>Tambah Transaksi Keuangan
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('keuangan-santri.store') }}" enctype="multipart/form-data" id="formTransaksi">
                    @csrf

                    <div class="row">
                        <!-- Santri -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Santri <span class="text-danger">*</span></label>
                                <select name="santri_id" required class="form-select">
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($santriList as $santri)
                                        <option value="{{ $santri->id }}" {{ old('santri_id', $selectedSantri) == $santri->id ? 'selected' : '' }}>
                                            {{ $santri->nama_lengkap }} ({{ $santri->nis }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('santri_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" 
                                       required class="form-control">
                                @error('tanggal_transaksi')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Jenis Transaksi -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis" id="pemasukan" 
                                               value="pemasukan" {{ old('jenis', 'pengeluaran') == 'pemasukan' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pemasukan">
                                            <i class="ti ti-arrow-up text-success"></i> Pemasukan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis" id="pengeluaran" 
                                               value="pengeluaran" {{ old('jenis', 'pengeluaran') == 'pengeluaran' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pengeluaran">
                                            <i class="ti ti-arrow-down text-danger"></i> Pengeluaran
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" value="{{ old('jumlah') }}" 
                                       required min="0" step="0.01" class="form-control" placeholder="0">
                                @error('jumlah')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" required 
                               id="deskripsi" placeholder="Contoh: Beli sabun mandi"
                               class="form-control">
                        <small class="text-muted">
                            <i class="ti ti-info-circle"></i> Kategori akan terdeteksi otomatis berdasarkan deskripsi
                        </small>
                        @error('deskripsi')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Kategori (Auto-detected) -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" id="category_id" class="form-select" style="color: #000;">
                                    <option value="" style="color: #666;">-- Auto Detect --</option>
                                    @foreach($categories as $jenis => $cats)
                                        <optgroup label="{{ ucfirst($jenis) }}" style="color: #000; font-weight: bold;">
                                            @foreach($cats as $cat)
                                                <option value="{{ $cat->id }}" data-jenis="{{ $cat->jenis }}" style="color: #000;">
                                                    {{ $cat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div id="kategoriSuggestion" class="alert alert-info mt-2 d-none" role="alert">
                                    <i class="ti ti-bulb"></i> Kategori terdeteksi: <span id="detectedCategory"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-select">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="E-Wallet">E-Wallet (OVO, GoPay, Dana)</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="form-group mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="catatan" rows="3" class="form-control" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Bukti File -->
                    <div class="form-group mb-3">
                        <label class="form-label">Upload Bukti (Foto/PDF)</label>
                        <input type="file" name="bukti_file" accept="image/*,application/pdf" class="form-control">
                        <small class="text-muted">Maksimal 2MB. Format: JPG, PNG, PDF</small>
                    </div>

                    <!-- Buttons -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Transaksi
                        </button>
                        <a href="{{ route('keuangan-santri.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
// Auto-detect kategori saat mengetik deskripsi
let debounceTimer;
document.getElementById('deskripsi').addEventListener('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        detectCategory();
    }, 500);
});

// Detect saat jenis berubah
document.querySelectorAll('input[name="jenis"]').forEach(radio => {
    radio.addEventListener('change', detectCategory);
});

function detectCategory() {
    const deskripsi = document.getElementById('deskripsi').value;
    const jenis = document.querySelector('input[name="jenis"]:checked').value;
    
    if (deskripsi.length < 3) return;
    
    fetch(`{{ route('keuangan-santri.detect-category') }}?deskripsi=${encodeURIComponent(deskripsi)}&jenis=${jenis}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.category) {
                document.getElementById('category_id').value = data.category.id;
                document.getElementById('detectedCategory').textContent = data.category.nama_kategori;
                document.getElementById('kategoriSuggestion').classList.remove('d-none');
            } else {
                document.getElementById('kategoriSuggestion').classList.add('d-none');
            }
        });
}

// Filter kategori berdasarkan jenis
document.querySelectorAll('input[name="jenis"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const jenis = this.value;
        const categorySelect = document.getElementById('category_id');
        const options = categorySelect.querySelectorAll('option[data-jenis]');
        
        options.forEach(option => {
            if (option.dataset.jenis === jenis) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        categorySelect.value = '';
    });
});
</script>
@endpush
@endsection
