<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SaldoHarianOperasional;
use App\Models\RealisasiDanaOperasional;
use Carbon\Carbon;

echo "==============================================\n";
echo "CEK SALDO HARIAN & TRANSAKSI JANUARI 2025\n";
echo "==============================================\n\n";

// Cek saldo harian Januari 2025
$saldoJanuari = SaldoHarianOperasional::whereYear('tanggal', 2025)
    ->whereMonth('tanggal', 1)
    ->orderBy('tanggal')
    ->get();

echo "✓ Total saldo harian Januari 2025: " . $saldoJanuari->count() . "\n\n";

if ($saldoJanuari->count() > 0) {
    echo "DATA SALDO HARIAN JANUARI 2025:\n";
    echo "─────────────────────────────────────────────────\n";
    foreach ($saldoJanuari as $s) {
        echo sprintf(
            "ID: %d | %s | Saldo Awal: Rp %s\n",
            $s->id,
            $s->tanggal->format('Y-m-d'),
            number_format($s->saldo_awal, 0, ',', '.')
        );
    }
    echo "\n";
} else {
    echo "❌ TIDAK ADA SALDO HARIAN JANUARI 2025!\n";
    echo "Ini penyebab data tidak muncul!\n\n";
}

// Cek transaksi Januari 2025
$transaksiJanuari = RealisasiDanaOperasional::whereYear('tanggal_realisasi', 2025)
    ->whereMonth('tanggal_realisasi', 1)
    ->orderBy('tanggal_realisasi')
    ->get();

echo "✓ Total transaksi Januari 2025: " . $transaksiJanuari->count() . "\n\n";

if ($transaksiJanuari->count() > 0) {
    echo "DATA TRANSAKSI JANUARI 2025:\n";
    echo "─────────────────────────────────────────────────\n";
    foreach ($transaksiJanuari as $t) {
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
}

echo "==============================================\n";
echo "DIAGNOSIS:\n";
echo "==============================================\n";

if ($transaksiJanuari->count() > 0 && $saldoJanuari->count() == 0) {
    echo "❌ MASALAH DITEMUKAN!\n";
    echo "Ada transaksi tapi tidak ada saldo harian.\n";
    echo "View tidak bisa menampilkan data karena loop berdasarkan saldo_harian.\n\n";
    
    echo "SOLUSI: Generate saldo harian untuk Januari 2025\n";
    echo "Jalankan: php artisan tinker\n";
    echo "Lalu: RealisasiDanaOperasional::recalculateSaldoHarian('2025-01-01');\n";
} elseif ($transaksiJanuari->count() > 0 && $saldoJanuari->count() > 0) {
    echo "✅ DATA LENGKAP!\n";
    echo "Saldo harian dan transaksi ada.\n";
    echo "Cek query di controller atau filter.\n";
} else {
    echo "❌ TIDAK ADA DATA SAMA SEKALI!\n";
    echo "Import data terlebih dahulu.\n";
}

echo "==============================================\n";
