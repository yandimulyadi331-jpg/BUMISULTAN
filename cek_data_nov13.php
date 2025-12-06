<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;

echo "=== CEK DATA 2025-11-13 ===\n\n";

// Cek transaksi
echo "TRANSAKSI:\n";
$transaksi = RealisasiDanaOperasional::where('tanggal_realisasi', '2025-11-13')->get();
foreach ($transaksi as $t) {
    echo "- {$t->nomor_transaksi}: {$t->keterangan} | {$t->tipe_transaksi} | Rp " . number_format($t->nominal, 0, ',', '.') . "\n";
}

echo "\n";
$masuk = $transaksi->where('tipe_transaksi', 'masuk')->sum('nominal');
$keluar = $transaksi->where('tipe_transaksi', 'keluar')->sum('nominal');
echo "Total Masuk : Rp " . number_format($masuk, 0, ',', '.') . "\n";
echo "Total Keluar: Rp " . number_format($keluar, 0, ',', '.') . "\n";

// Cek saldo harian
echo "\n SALDO HARIAN:\n";
$saldo = SaldoHarianOperasional::where('tanggal', '2025-11-13')->first();
if ($saldo) {
    echo "Saldo Awal      : Rp " . number_format($saldo->saldo_awal, 0, ',', '.') . "\n";
    echo "Dana Masuk      : Rp " . number_format($saldo->dana_masuk, 0, ',', '.') . "\n";
    echo "Total Realisasi : Rp " . number_format($saldo->total_realisasi, 0, ',', '.') . " (dana keluar)\n";
    echo "Saldo Akhir     : Rp " . number_format($saldo->saldo_akhir, 0, ',', '.') . "\n";
    
    $seharusnya = $saldo->saldo_awal + $saldo->dana_masuk - $saldo->total_realisasi;
    echo "\nHitungan Manual : Rp " . number_format($seharusnya, 0, ',', '.') . "\n";
    
    if ($saldo->saldo_akhir != $seharusnya) {
        echo "⚠ ADA KESALAHAN KALKULASI!\n";
    }
}
