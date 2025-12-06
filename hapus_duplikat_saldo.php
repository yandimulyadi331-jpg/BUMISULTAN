<?php

/**
 * Script untuk menghapus duplikat data saldo harian
 * Jalankan: php hapus_duplikat_saldo.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Mencari duplikat data saldo harian...\n\n";

// Cari tanggal yang duplikat
$duplikats = DB::select("
    SELECT tanggal, COUNT(*) as jumlah 
    FROM saldo_harian_operasional 
    GROUP BY tanggal 
    HAVING COUNT(*) > 1
");

if (empty($duplikats)) {
    echo "✅ Tidak ada duplikat! Data sudah bersih.\n";
    exit;
}

echo "❌ Ditemukan " . count($duplikats) . " tanggal yang duplikat:\n";
foreach ($duplikats as $dup) {
    echo "   - {$dup->tanggal} ({$dup->jumlah} baris)\n";
}

echo "\n🧹 Membersihkan duplikat...\n";

$totalHapus = 0;
foreach ($duplikats as $dup) {
    // Ambil semua record untuk tanggal ini
    $records = DB::table('saldo_harian_operasional')
        ->where('tanggal', $dup->tanggal)
        ->orderBy('id', 'asc')
        ->get();
    
    // Simpan yang pertama, hapus sisanya
    $keep = $records->first();
    $deleteIds = $records->skip(1)->pluck('id')->toArray();
    
    if (!empty($deleteIds)) {
        DB::table('saldo_harian_operasional')
            ->whereIn('id', $deleteIds)
            ->delete();
        
        $jumlahHapus = count($deleteIds);
        $totalHapus += $jumlahHapus;
        echo "   ✅ {$dup->tanggal}: Simpan ID {$keep->id}, hapus {$jumlahHapus} duplikat\n";
    }
}

echo "\n✅ Selesai! Total {$totalHapus} baris duplikat berhasil dihapus.\n";
echo "🔄 Silakan refresh browser.\n";
