<?php
/**
 * Script untuk membersihkan orphaned records di perawatan_log
 * Menghapus records dengan master_perawatan_id yang NULL atau tidak ada di master_perawatan
 * 
 * Jalankan: php fix_perawatan_orphaned_records.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\PerawatanLog;

echo "=== CLEANING ORPHANED PERAWATAN_LOG RECORDS ===\n\n";

// Cek records dengan NULL master_perawatan_id
$nullCount = PerawatanLog::whereNull('master_perawatan_id')->count();
echo "📊 Records dengan NULL master_perawatan_id: " . $nullCount . "\n";

if ($nullCount > 0) {
    echo "🗑️  Menghapus records dengan NULL master_perawatan_id...\n";
    PerawatanLog::whereNull('master_perawatan_id')->delete();
    echo "✅ Selesai menghapus " . $nullCount . " records\n\n";
}

// Cek records dengan master_perawatan_id yang tidak ada di master_perawatan
echo "🔍 Checking orphaned foreign keys...\n";
$orphanedCount = DB::table('perawatan_log as pl')
    ->whereNotNull('pl.master_perawatan_id')
    ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('master_perawatan as mp')
            ->whereColumn('mp.id', 'pl.master_perawatan_id')
            ->whereNull('mp.deleted_at'); // Jangan hitung soft-deleted
    })
    ->count();

echo "📊 Orphaned records (master_perawatan_id tidak ada): " . $orphanedCount . "\n";

if ($orphanedCount > 0) {
    echo "🗑️  Menghapus orphaned records...\n";
    DB::table('perawatan_log as pl')
        ->whereNotNull('pl.master_perawatan_id')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('master_perawatan as mp')
                ->whereColumn('mp.id', 'pl.master_perawatan_id')
                ->whereNull('mp.deleted_at');
        })
        ->delete();
    echo "✅ Selesai menghapus " . $orphanedCount . " orphaned records\n\n";
}

// Verifikasi
$finalCount = PerawatanLog::count();
$validCount = PerawatanLog::whereNotNull('master_perawatan_id')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('master_perawatan as mp')
            ->whereColumn('mp.id', 'perawatan_log.master_perawatan_id')
            ->whereNull('mp.deleted_at');
    })
    ->count();

echo "📈 SUMMARY:\n";
echo "Total records: " . $finalCount . "\n";
echo "Valid records: " . $validCount . "\n";
echo "===============================================\n";
echo "✅ CLEANUP COMPLETE! Database siap digunakan.\n";
echo "===============================================\n";
?>
