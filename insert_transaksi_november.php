<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;

echo "Insert transaksi bulan November 2025...\n\n";

try {
    // Transaksi 1: Dana Masuk
    $transaksi1 = RealisasiDanaOperasional::create([
        'tanggal_realisasi' => '2025-11-13',
        'keterangan' => 'Dana Masuk Awal',
        'uraian' => 'Dana Masuk Awal',
        'tipe_transaksi' => 'masuk',
        'nominal' => 5000000,
        'created_by' => 1,
    ]);
    echo "✓ Transaksi 1: {$transaksi1->nomor_transaksi} - Rp 5.000.000 (Masuk)\n";
    
    // Transaksi 2: Dana Keluar
    $transaksi2 = RealisasiDanaOperasional::create([
        'tanggal_realisasi' => '2025-11-13',
        'keterangan' => 'Pembelian BBM',
        'uraian' => 'Pembelian BBM',
        'tipe_transaksi' => 'keluar',
        'nominal' => 500000,
        'created_by' => 1,
    ]);
    echo "✓ Transaksi 2: {$transaksi2->nomor_transaksi} - Rp 500.000 (Keluar)\n";
    
    // Transaksi 3: Dana Keluar
    $transaksi3 = RealisasiDanaOperasional::create([
        'tanggal_realisasi' => '2025-11-13',
        'keterangan' => 'Bayar Listrik',
        'uraian' => 'Bayar Listrik',
        'tipe_transaksi' => 'keluar',
        'nominal' => 300000,
        'created_by' => 1,
    ]);
    echo "✓ Transaksi 3: {$transaksi3->nomor_transaksi} - Rp 300.000 (Keluar)\n";
    
    echo "\n--- Saldo Hari Ini (2025-11-13) ---\n";
    $saldo = SaldoHarianOperasional::where('tanggal', '2025-11-13')->first();
    if ($saldo) {
        echo "Saldo Awal  : Rp " . number_format($saldo->saldo_awal, 0, ',', '.') . "\n";
        echo "Dana Masuk  : Rp " . number_format($saldo->dana_masuk, 0, ',', '.') . "\n";
        echo "Dana Keluar : Rp " . number_format($saldo->dana_keluar, 0, ',', '.') . "\n";
        echo "Saldo Akhir : Rp " . number_format($saldo->saldo_akhir, 0, ',', '.') . "\n";
    }
    
    echo "\n✓ Selesai! Silakan refresh halaman /dana-operasional\n";
    
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
}
