@extends('layouts.app')

@section('header')
<h2 class="text-xl font-semibold leading-tight text-gray-800">
    {{ __('Edit Transaksi Keuangan') }}
</h2>
@endsection

@section('content')
<div class="py-4">
    <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('keuangan-santri.update', $transaction->id) }}" enctype="multipart/form-data" id="formTransaksi">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Santri -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Santri <span class="text-red-500">*</span></label>
                            <select name="santri_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Santri --</option>
                                @foreach($santriList as $santri)
                                    <option value="{{ $santri->id }}" {{ old('santri_id', $transaction->santri_id) == $santri->id ? 'selected' : '' }}>
                                        {{ $santri->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            @error('santri_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Transaksi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Transaksi <span class="text-red-500">*</span></label>
                            <div class="mt-2 space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis" value="pemasukan" {{ old('jenis', $transaction->jenis) == 'pemasukan' ? 'checked' : '' }} 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                    <span class="ml-2">Pemasukan</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis" value="pengeluaran" {{ old('jenis', $transaction->jenis) == 'pengeluaran' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                    <span class="ml-2">Pengeluaran</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', $transaction->tanggal_transaksi->format('Y-m-d')) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('tanggal_transaksi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                            <input type="text" name="deskripsi" value="{{ old('deskripsi', $transaction->deskripsi) }}" required 
                                   id="deskripsi" placeholder="Contoh: Beli sabun mandi"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Kategori akan terdeteksi otomatis berdasarkan deskripsi</p>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" style="color: #000;">
                                <option value="" style="color: #666;">-- Auto Detect --</option>
                                @foreach($categories as $jenis => $cats)
                                    <optgroup label="{{ ucfirst($jenis) }}" style="color: #000; font-weight: bold;">
                                        @foreach($cats as $cat)
                                            <option value="{{ $cat->id }}" data-jenis="{{ $cat->jenis }}" 
                                                    {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}
                                                    style="color: #000;">
                                                {{ $cat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div id="kategoriSuggestion" class="mt-1 hidden rounded-md bg-blue-50 p-2 text-sm text-blue-800">
                                <i class="fas fa-info-circle"></i> Kategori terdeteksi: <span id="detectedCategory"></span>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah <span class="text-red-500">*</span></label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                                <input type="number" name="jumlah" value="{{ old('jumlah', $transaction->jumlah) }}" required min="0" step="0.01"
                                       class="block w-full rounded-md border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @error('jumlah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Tunai" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="Transfer Bank" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="E-Wallet" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet (OVO, GoPay, Dana, dll)</option>
                                <option value="Lainnya" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="catatan" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan', $transaction->catatan) }}</textarea>
                        </div>

                        <!-- Upload Bukti -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload Bukti (Opsional)</label>
                            @if($transaction->bukti_file)
                                <div class="mb-2 text-sm text-gray-600">
                                    File saat ini: 
                                    <a href="{{ Storage::url($transaction->bukti_file) }}" target="_blank" class="text-blue-600 hover:underline">
                                        Lihat file
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="bukti_file" accept="image/*,.pdf"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max 2MB). Kosongkan jika tidak ingin mengubah.</p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('keuangan-santri.show', $transaction->id) }}" 
                               class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Update Transaksi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
                document.getElementById('kategoriSuggestion').classList.remove('hidden');
            } else {
                document.getElementById('kategoriSuggestion').classList.add('hidden');
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
    });
});
</script>
@endpush
@endsection
