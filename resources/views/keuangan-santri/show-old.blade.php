@extends('layouts.app')

@section('header')
<h2 class="text-xl font-semibold leading-tight text-gray-800">
    {{ __('Detail Transaksi') }}
</h2>
@endsection

@section('content')
<div class="py-4">
    <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        
        <div class="mb-4 flex justify-between">
            <a href="{{ route('keuangan-santri.index') }}" class="rounded-md bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <div class="space-x-2">
                <a href="{{ route('keuangan-santri.edit', $transaction->id) }}" 
                   class="rounded-md bg-yellow-600 px-4 py-2 text-white hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                @if(!$transaction->is_verified)
                    <form method="POST" action="{{ route('keuangan-santri.verify', $transaction->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Verifikasi
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('keuangan-santri.destroy', $transaction->id) }}" class="inline" 
                      onsubmit="return confirm('Yakin hapus transaksi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6 border-b pb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $transaction->kode_transaksi }}</h3>
                            <p class="text-gray-600">{{ $transaction->tanggal_transaksi->format('d F Y') }}</p>
                        </div>
                        <div class="text-right">
                            @if($transaction->jenis == 'pemasukan')
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                                    <i class="fas fa-arrow-up mr-1"></i> PEMASUKAN
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                                    <i class="fas fa-arrow-down mr-1"></i> PENGELUARAN
                                </span>
                            @endif
                            @if($transaction->is_verified)
                                <span class="ml-2 inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">
                                    <i class="fas fa-check-circle mr-1"></i> TERVERIFIKASI
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Amount -->
                <div class="mb-6 rounded-lg bg-gray-50 p-6 text-center">
                    <p class="mb-2 text-sm text-gray-600">Nominal Transaksi</p>
                    <p class="text-4xl font-bold {{ $transaction->jenis == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->jenis == 'pemasukan' ? '+' : '-' }} 
                        Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Details -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Santri</label>
                            <p class="mt-1 text-lg">{{ $transaction->santri->nama_karyawan ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kategori</label>
                            <p class="mt-1">
                                @if($transaction->category)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium" 
                                          style="background-color: {{ $transaction->category->color }}20; color: {{ $transaction->category->color }}">
                                        <i class="{{ $transaction->category->icon }} mr-1"></i>
                                        {{ $transaction->category->nama_kategori }}
                                    </span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="mt-1 text-lg">{{ $transaction->deskripsi }}</p>
                    </div>

                    @if($transaction->catatan)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Catatan</label>
                        <p class="mt-1">{{ $transaction->catatan }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Metode Pembayaran</label>
                            <p class="mt-1">{{ $transaction->metode_pembayaran ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Saldo Sebelum</label>
                            <p class="mt-1">Rp {{ number_format($transaction->saldo_sebelum, 0, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Saldo Sesudah</label>
                            <p class="mt-1 font-bold">Rp {{ number_format($transaction->saldo_sesudah, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($transaction->bukti_file)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Bukti Transaksi</label>
                        <div class="mt-2">
                            @if(Str::endsWith($transaction->bukti_file, '.pdf'))
                                <a href="{{ Storage::url($transaction->bukti_file) }}" target="_blank" 
                                   class="inline-flex items-center rounded-md bg-red-50 px-3 py-2 text-sm text-red-700 hover:bg-red-100">
                                    <i class="fas fa-file-pdf mr-2"></i>Lihat PDF
                                </a>
                            @else
                                <a href="{{ Storage::url($transaction->bukti_file) }}" target="_blank">
                                    <img src="{{ Storage::url($transaction->bukti_file) }}" 
                                         alt="Bukti" class="h-auto max-w-sm rounded-lg shadow-lg">
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Info -->
                    <div class="rounded-lg bg-gray-50 p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Dibuat oleh</p>
                                <p class="font-medium">{{ $transaction->creator->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            @if($transaction->updater)
                            <div>
                                <p class="text-gray-600">Diupdate oleh</p>
                                <p class="font-medium">{{ $transaction->updater->name }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @endif
                            
                            @if($transaction->is_verified)
                            <div>
                                <p class="text-gray-600">Diverifikasi oleh</p>
                                <p class="font-medium">{{ $transaction->verifier->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->verified_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
