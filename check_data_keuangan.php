<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;

echo "=== CEK DATA KEUANGAN ===\n\n";

// Total data
$total = RealisasiDanaOperasional::count();
echo "📊 Total Realisasi: {$total} data\n\n";

// Sample data
echo "📝 Sample Data (3 terakhir):\n";
$sample = RealisasiDanaOperasional::orderBy('tanggal_realisasi', 'desc')->take(3)->get();
foreach ($sample as $item) {
    $nominal = number_format($item->nominal, 0, ',', '.');
    echo "  • {$item->tanggal_realisasi} | {$item->tipe_transaksi} | Rp {$nominal} | {$item->keterangan}\n";
}

echo "\n";

// Summary per tahun
$years = RealisasiDanaOperasional::selectRaw('YEAR(tanggal_realisasi) as year')
    ->groupBy('year')
    ->pluck('year');

foreach ($years as $year) {
    $masuk = RealisasiDanaOperasional::where('tipe_transaksi', 'Dana Masuk')
        ->whereYear('tanggal_realisasi', $year)
        ->sum('nominal');
    
    $keluar = RealisasiDanaOperasional::where('tipe_transaksi', 'Dana Keluar')
        ->whereYear('tanggal_realisasi', $year)
        ->sum('nominal');
    
    $count = RealisasiDanaOperasional::whereYear('tanggal_realisasi', $year)->count();
    
    echo "📅 Tahun {$year}:\n";
    echo "   Dana Masuk  : Rp " . number_format($masuk, 0, ',', '.') . "\n";
    echo "   Dana Keluar : Rp " . number_format($keluar, 0, ',', '.') . "\n";
    echo "   Selisih     : Rp " . number_format($masuk - $keluar, 0, ',', '.') . "\n";
    echo "   Transaksi   : {$count} data\n\n";
}

// Saldo Harian
$saldoCount = SaldoHarianOperasional::count();
echo "💰 Total Saldo Harian: {$saldoCount} data\n";

if ($saldoCount > 0) {
    $latestSaldo = SaldoHarianOperasional::orderBy('tanggal', 'desc')->first();
    echo "   Saldo Terakhir: Rp " . number_format($latestSaldo->saldo_akhir, 0, ',', '.') . " ({$latestSaldo->tanggal})\n";
}
