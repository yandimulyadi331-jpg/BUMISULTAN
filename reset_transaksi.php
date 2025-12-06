<?php

/**
 * Script untuk menghapus SEMUA data transaksi
 * Jalankan: php reset_transaksi.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "⚠️  PERINGATAN: Script ini akan menghapus SEMUA data transaksi!\n";
echo "================================================\n\n";

// Hitung jumlah data
$jumlahRealisasi = DB::table('realisasi_dana_operasional')->count();
$jumlahSaldo = DB::table('saldo_harian_operasional')->count();
$jumlahPengajuan = DB::table('pengajuan_dana_operasional')->count();

echo "Data yang akan dihapus:\n";
echo "  - Realisasi Dana: {$jumlahRealisasi} baris\n";
echo "  - Saldo Harian: {$jumlahSaldo} baris\n";
echo "  - Pengajuan Dana: {$jumlahPengajuan} baris\n\n";

echo "🗑️  Menghapus data...\n\n";

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Hapus data realisasi
DB::table('realisasi_dana_operasional')->truncate();
echo "✅ Realisasi dana operasional dihapus\n";

// Hapus data saldo harian
DB::table('saldo_harian_operasional')->truncate();
echo "✅ Saldo harian operasional dihapus\n";

// Hapus data pengajuan
DB::table('pengajuan_dana_operasional')->truncate();
echo "✅ Pengajuan dana operasional dihapus\n";

// Enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "\n✅ SELESAI! Semua data transaksi berhasil dihapus.\n";
echo "🔄 Silakan refresh browser.\n";
echo "📝 Anda bisa mulai import data baru dari Excel.\n";
