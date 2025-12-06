<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use Carbon\Carbon;

echo "==============================================\n";
echo "RECALCULATE SALDO HARIAN JANUARI 2025\n";
echo "==============================================\n\n";

try {
    echo "Mulai recalculate untuk tanggal: 2025-01-01\n";
    
    RealisasiDanaOperasional::recalculateSaldoHarian('2025-01-01');
    
    echo "✅ Recalculate BERHASIL!\n\n";
    
    // Cek hasil
    $saldo = \App\Models\SaldoHarianOperasional::whereDate('tanggal', '2025-01-01')->first();
    
    if ($saldo) {
        echo "Saldo Harian 2025-01-01:\n";
        echo "  Saldo Awal: Rp " . number_format($saldo->saldo_awal, 0, ',', '.') . "\n";
        echo "  Dana Masuk: Rp " . number_format($saldo->dana_masuk, 0, ',', '.') . "\n";
        echo "  Dana Keluar: Rp " . number_format($saldo->total_realisasi, 0, ',', '.') . "\n";
        echo "  Saldo Akhir: Rp " . number_format($saldo->saldo_akhir, 0, ',', '.') . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n==============================================\n";
