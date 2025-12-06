<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use Carbon\Carbon;

echo "==============================================\n";
echo "CEK DATA TRANSAKSI JANUARI 2025\n";
echo "==============================================\n\n";

// Total semua transaksi
$totalAll = RealisasiDanaOperasional::count();
echo "✓ Total semua transaksi: {$totalAll}\n\n";

// Transaksi Januari 2025
$totalJanuari = RealisasiDanaOperasional::whereYear('tanggal_realisasi', 2025)
    ->whereMonth('tanggal_realisasi', 1)
    ->count();
echo "✓ Total transaksi Januari 2025: {$totalJanuari}\n\n";

if ($totalJanuari > 0) {
    echo "DATA TRANSAKSI JANUARI 2025:\n";
    echo "─────────────────────────────────────────────────\n";
    
    $transaksi = RealisasiDanaOperasional::whereYear('tanggal_realisasi', 2025)
        ->whereMonth('tanggal_realisasi', 1)
        ->orderBy('tanggal_realisasi')
        ->orderBy('urutan_baris')
        ->get();
    
    foreach ($transaksi as $t) {
        echo sprintf(
            "ID: %d | %s | %s | Rp %s | %s\n",
            $t->id,
            $t->tanggal_realisasi->format('Y-m-d'),
            $t->tipe_transaksi,
            number_format($t->nominal, 0, ',', '.'),
            $t->keterangan
        );
    }
    
    echo "\n";
} else {
    echo "❌ TIDAK ADA DATA JANUARI 2025!\n";
    echo "Kemungkinan:\n";
    echo "1. Data belum diimport\n";
    echo "2. Import gagal\n";
    echo "3. Data ada tapi tanggal salah format\n\n";
}

// Cek 10 transaksi terakhir (apapun tanggalnya)
echo "10 TRANSAKSI TERAKHIR (Semua Periode):\n";
echo "─────────────────────────────────────────────────\n";

$recent = RealisasiDanaOperasional::orderBy('id', 'desc')->limit(10)->get();

if ($recent->count() > 0) {
    foreach ($recent as $t) {
        echo sprintf(
            "ID: %d | %s | %s | Rp %s | %s\n",
            $t->id,
            $t->tanggal_realisasi->format('Y-m-d'),
            $t->tipe_transaksi,
            number_format($t->nominal, 0, ',', '.'),
            substr($t->keterangan, 0, 50)
        );
    }
} else {
    echo "❌ TIDAK ADA TRANSAKSI SAMA SEKALI!\n";
}

echo "\n==============================================\n";
