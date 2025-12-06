<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Memperbaiki data transaksi keuangan yang salah...\n\n";

// Fix keuangan_tukangs - bagi 10 untuk transaksi yang nilainya terlalu besar
$transaksis = DB::table('keuangan_tukangs')
    ->where('jumlah', '>=', 500000) // Transaksi >= 500rb kemungkinan salah
    ->where('tipe', 'kredit')
    ->whereIn('jenis_transaksi', ['pinjaman', 'pembayaran_pinjaman'])
    ->get();

echo "Ditemukan " . count($transaksis) . " transaksi yang perlu diperbaiki\n\n";

foreach ($transaksis as $t) {
    $jumlahBaru = $t->jumlah / 10;
    
    DB::table('keuangan_tukangs')
        ->where('id', $t->id)
        ->update([
            'jumlah' => $jumlahBaru
        ]);
    
    echo "✓ ID {$t->id} ({$t->jenis_transaksi}): Rp " . number_format($t->jumlah, 0, ',', '.') . 
         " → Rp " . number_format($jumlahBaru, 0, ',', '.') . "\n";
}

echo "\n✅ Selesai! Total diperbaiki: " . count($transaksis) . " record\n";
echo "\nSilakan refresh halaman browser Anda!\n";
