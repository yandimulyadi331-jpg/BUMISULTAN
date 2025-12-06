@extends('layouts.app')

@section('header')
<h2 class="text-xl font-semibold leading-tight text-gray-800">
    {{ __('Import Data Keuangan') }}
</h2>
@endsection

@section('content')
<div class="py-4">
    <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        
        <!-- Instructions -->
        <div class="mb-4 overflow-hidden bg-blue-50 shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="mb-3 text-lg font-semibold text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>Petunjuk Import
                </h3>
                <ul class="list-inside list-disc space-y-2 text-sm text-blue-700">
                    <li>Download template Excel terlebih dahulu</li>
                    <li>Isi data sesuai format yang tersedia (Tanggal, Deskripsi, Jumlah, dll)</li>
                    <li>Kategori akan <strong>terdeteksi otomatis</strong> berdasarkan deskripsi</li>
                    <li>Untuk pengeluaran, bisa menggunakan angka negatif atau kolom "Jenis"</li>
                    <li>Format tanggal: DD/MM/YYYY atau YYYY-MM-DD</li>
                    <li>Upload file Excel yang sudah diisi</li>
                    <li>Sistem akan memproses dan menambahkan transaksi secara otomatis</li>
                </ul>
            </div>
        </div>

        <!-- Download Template -->
        <div class="mb-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="mb-3 text-lg font-semibold">1. Download Template</h3>
                <p class="mb-4 text-sm text-gray-600">
                    Download template Excel untuk memudahkan proses import data
                </p>
                <a href="{{ route('keuangan-santri.download-template') }}" 
                   class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>
                    Download Template Excel
                </a>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="mb-3 text-lg font-semibold">2. Upload File Excel</h3>
                
                <form method="POST" action="{{ route('keuangan-santri.import') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-4">
                        <!-- Pilih Santri -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Pilih Santri <span class="text-red-500">*</span>
                            </label>
                            <select name="santri_id" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Santri --</option>
                                @foreach(\App\Models\Santri::orderBy('nama_lengkap')->get() as $santri)
                                    <option value="{{ $santri->id }}">{{ $santri->nama_lengkap }} ({{ $santri->nis }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Semua transaksi dalam file akan ditambahkan untuk santri ini
                            </p>
                            @error('santri_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload File -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                File Excel <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">
                                Format: XLSX, XLS, atau CSV (Max 5MB)
                            </p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Auto-Detect -->
                        <div class="rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-magic text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">
                                        Fitur Auto-Kategorisasi
                                    </h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p class="mb-2">Sistem akan otomatis mendeteksi kategori berdasarkan deskripsi:</p>
                                        <ul class="list-inside list-disc space-y-1 text-xs">
                                            <li>"Beli sabun" → Kategori: <strong>Kebersihan & Kesehatan</strong></li>
                                            <li>"Jajan bakso" → Kategori: <strong>Makanan & Minuman</strong></li>
                                            <li>"Beli buku tulis" → Kategori: <strong>Pendidikan & Alat Tulis</strong></li>
                                            <li>"Pulsa 50ribu" → Kategori: <strong>Komunikasi & Pulsa</strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('keuangan-santri.index') }}" 
                               class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                <i class="fas fa-upload mr-2"></i>Upload & Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Example Table -->
        <div class="mt-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="mb-3 text-lg font-semibold">Contoh Format Excel</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Deskripsi</th>
                                <th class="px-4 py-2 text-left">Jumlah</th>
                                <th class="px-4 py-2 text-left">Jenis</th>
                                <th class="px-4 py-2 text-left">Kategori (Opsional)</th>
                                <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                                <th class="px-4 py-2 text-left">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2">01/11/2025</td>
                                <td class="px-4 py-2">Uang saku bulan November</td>
                                <td class="px-4 py-2">500000</td>
                                <td class="px-4 py-2">Pemasukan</td>
                                <td class="px-4 py-2">-</td>
                                <td class="px-4 py-2">Transfer Bank</td>
                                <td class="px-4 py-2">Dari orangtua</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">02/11/2025</td>
                                <td class="px-4 py-2">Beli sabun dan shampo</td>
                                <td class="px-4 py-2">25000</td>
                                <td class="px-4 py-2">Pengeluaran</td>
                                <td class="px-4 py-2">-</td>
                                <td class="px-4 py-2">Tunai</td>
                                <td class="px-4 py-2">-</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">03/11/2025</td>
                                <td class="px-4 py-2">Makan siang di kantin</td>
                                <td class="px-4 py-2">15000</td>
                                <td class="px-4 py-2">Pengeluaran</td>
                                <td class="px-4 py-2">-</td>
                                <td class="px-4 py-2">Tunai</td>
                                <td class="px-4 py-2">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
