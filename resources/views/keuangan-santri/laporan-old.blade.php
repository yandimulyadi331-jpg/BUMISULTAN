@extends('layouts.app')

@section('header')
<h2 class="text-xl font-semibold leading-tight text-gray-800">
    {{ __('Laporan Keuangan Santri') }}
</h2>
@endsection

@section('content')
<div class="py-4">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        
        <!-- Filter Section -->
        <div class="mb-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('keuangan-santri.laporan') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Santri</label>
                            <select name="santri_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Semua --</option>
                                @foreach($santriList as $santri)
                                    <option value="{{ $santri->id }}" {{ $filters['santri_id'] == $santri->id ? 'selected' : '' }}>
                                        {{ $santri->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis</label>
                            <select name="jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Semua --</option>
                                <option value="pemasukan" {{ $filters['jenis'] == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ $filters['jenis'] == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Semua --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $filters['category_id'] == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }} ({{ ucfirst($cat->jenis) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Periode</label>
                            <select name="periode" id="periodeSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Custom --</option>
                                <option value="hari" {{ $filters['periode'] == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="minggu" {{ $filters['periode'] == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="bulan" {{ $filters['periode'] == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="tahun" {{ $filters['periode'] == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3" id="customDateRange">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $filters['start_date'] }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $filters['end_date'] }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cari</label>
                            <input type="text" name="search" value="{{ $filters['search'] }}" 
                                   placeholder="Kode/Deskripsi..." 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <div class="space-x-2">
                            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <a href="{{ route('keuangan-santri.laporan') }}" class="rounded-md bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </a>
                        </div>
                        
                        <div class="space-x-2">
                            <button type="submit" formaction="{{ route('keuangan-santri.export.pdf') }}" 
                                    class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                            <button type="submit" formaction="{{ route('keuangan-santri.export.excel') }}" 
                                    class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                <i class="fas fa-file-excel mr-2"></i>Export Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary -->
        <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-600">Total Pemasukan</div>
                    <div class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-600">Total Pengeluaran</div>
                    <div class="text-2xl font-bold text-red-600">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-600">Selisih</div>
                    <div class="text-2xl font-bold {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="mb-4 text-lg font-semibold">Detail Transaksi ({{ $transactions->total() }})</h3>
                
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kode</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Santri</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Deskripsi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kategori</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            {{ $transaction->tanggal_transaksi->format('d/m/Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            {{ $transaction->kode_transaksi }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $transaction->santri->nama_karyawan ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $transaction->deskripsi }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            {{ $transaction->category->nama_kategori ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium {{ $transaction->jenis == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->jenis == 'pemasukan' ? '+' : '-' }} 
                                            Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="py-8 text-center text-gray-500">
                        <i class="fas fa-inbox mb-2 text-4xl"></i>
                        <p>Tidak ada data sesuai filter</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Toggle custom date range
document.getElementById('periodeSelect').addEventListener('change', function() {
    const customDateRange = document.getElementById('customDateRange');
    if (this.value === '') {
        customDateRange.style.display = 'grid';
    } else {
        customDateRange.style.display = 'none';
    }
});

// Initialize
window.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periodeSelect');
    const customDateRange = document.getElementById('customDateRange');
    if (periodeSelect.value !== '') {
        customDateRange.style.display = 'none';
    }
});
</script>
@endpush
@endsection
