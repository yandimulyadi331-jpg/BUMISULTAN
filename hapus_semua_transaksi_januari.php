<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🗑️  HAPUS SEMUA TRANSAKSI TANGGAL 1 JANUARI 2025\n";
echo "========================================\n\n";

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Hapus realisasi tanggal 1 Januari
$deletedRealisasi = DB::table('realisasi_dana_operasional')
    ->whereDate('tanggal_realisasi', '2025-01-01')
    ->delete();

// Hapus saldo harian tanggal 1 Januari
$deletedSaldo = DB::table('saldo_harian_operasional')
    ->whereDate('tanggal', '2025-01-01')
    ->delete();

// Enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "✅ HASIL:\n";
echo "• Realisasi dihapus: $deletedRealisasi transaksi\n";
echo "• Saldo harian dihapus: $deletedSaldo baris\n\n";

// Cek sisa data
$sisaRealisasi = DB::table('realisasi_dana_operasional')->count();
$sisaSaldo = DB::table('saldo_harian_operasional')->count();

echo "📊 SISA DATA:\n";
echo "• Realisasi: $sisaRealisasi transaksi\n";
echo "• Saldo harian: $sisaSaldo baris\n\n";

echo "✅ Selesai! Semua data Januari 2025 sudah bersih.\n";
echo "Refresh browser, baris 'Saldo Akhir' akan hilang.\n";
