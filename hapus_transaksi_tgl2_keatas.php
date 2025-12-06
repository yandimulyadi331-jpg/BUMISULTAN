<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🗑️  HAPUS TRANSAKSI DARI TANGGAL 2 JAN SAMPAI AKHIR\n";
echo "========================================\n\n";

// Cek data sebelum dihapus
$realisasiTgl1 = DB::table('realisasi_dana_operasional')
    ->whereDate('tanggal_realisasi', '2025-01-01')
    ->count();

$realisasiTgl2Plus = DB::table('realisasi_dana_operasional')
    ->where('tanggal_realisasi', '>=', '2025-01-02')
    ->count();

$saldoTgl1 = DB::table('saldo_harian_operasional')
    ->whereDate('tanggal', '2025-01-01')
    ->count();

$saldoTgl2Plus = DB::table('saldo_harian_operasional')
    ->where('tanggal', '>=', '2025-01-02')
    ->count();

echo "📊 DATA SEBELUM DIHAPUS:\n";
echo "• Realisasi tanggal 1 Jan: $realisasiTgl1 (AKAN DIPERTAHANKAN)\n";
echo "• Realisasi tanggal 2 Jan - akhir: $realisasiTgl2Plus (AKAN DIHAPUS)\n";
echo "• Saldo harian tanggal 1 Jan: $saldoTgl1 (AKAN DIPERTAHANKAN)\n";
echo "• Saldo harian tanggal 2 Jan - akhir: $saldoTgl2Plus (AKAN DIHAPUS)\n\n";

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Hapus realisasi dari tanggal 2 keatas
$deletedRealisasi = DB::table('realisasi_dana_operasional')
    ->where('tanggal_realisasi', '>=', '2025-01-02')
    ->delete();

// Hapus saldo harian dari tanggal 2 keatas
$deletedSaldo = DB::table('saldo_harian_operasional')
    ->where('tanggal', '>=', '2025-01-02')
    ->delete();

// Enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "✅ HASIL PENGHAPUSAN:\n";
echo "• Realisasi dihapus: $deletedRealisasi baris\n";
echo "• Saldo harian dihapus: $deletedSaldo baris\n\n";

// Cek data setelah dihapus
$sisaRealisasi = DB::table('realisasi_dana_operasional')->count();
$sisaSaldo = DB::table('saldo_harian_operasional')->count();

echo "📊 DATA SETELAH DIHAPUS:\n";
echo "• Realisasi tersisa: $sisaRealisasi baris (hanya tanggal 1 Jan)\n";
echo "• Saldo harian tersisa: $sisaSaldo baris (hanya tanggal 1 Jan)\n\n";

echo "✅ Selesai! Hanya data tanggal 1 Januari yang tersisa.\n";
