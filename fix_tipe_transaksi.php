<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use App\Models\PengajuanDanaOperasional;

echo "Fixing tipe_transaksi for 'Transfer dari kas pusat'...\n\n";

// Update ID 7 yang salah
$realisasi7 = RealisasiDanaOperasional::find(7);
if ($realisasi7) {
    echo "Before: ID {$realisasi7->id} - {$realisasi7->uraian} - Type: {$realisasi7->tipe_transaksi}\n";
    $realisasi7->tipe_transaksi = 'masuk';
    $realisasi7->save();
    echo "After:  ID {$realisasi7->id} - {$realisasi7->uraian} - Type: {$realisasi7->tipe_transaksi}\n\n";
}

// Recalculate saldo
echo "Recalculating saldo...\n";
$pengajuans = PengajuanDanaOperasional::all();
foreach ($pengajuans as $pengajuan) {
    $pengajuan->updateSaldoHarian();
}

echo "\n✅ Fixed and recalculated!\n\n";

// Show summary
echo "--- Summary ---\n";
echo "Total Masuk: Rp " . number_format(RealisasiDanaOperasional::where('tipe_transaksi', 'masuk')->sum('nominal'), 0, ',', '.') . "\n";
echo "Total Keluar: Rp " . number_format(RealisasiDanaOperasional::where('tipe_transaksi', 'keluar')->sum('nominal'), 0, ',', '.') . "\n";

$saldo = \App\Models\SaldoHarianOperasional::first();
echo "\n--- Saldo Harian ---\n";
echo "Saldo Awal: Rp " . number_format($saldo->saldo_awal, 0, ',', '.') . "\n";
echo "Dana Masuk: Rp " . number_format($saldo->dana_masuk, 0, ',', '.') . "\n";
echo "Dana Keluar: Rp " . number_format($saldo->total_realisasi, 0, ',', '.') . "\n";
echo "Saldo Akhir: Rp " . number_format($saldo->saldo_akhir, 0, ',', '.') . "\n";
