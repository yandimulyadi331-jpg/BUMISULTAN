<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔄 REGENERATE NOMOR REALISASI DENGAN FORMAT BARU\n";
echo "========================================\n\n";

// Ambil semua realisasi
$realisasi = DB::table('realisasi_dana_operasional')
    ->orderBy('tanggal_realisasi', 'asc')
    ->orderBy('id', 'asc')
    ->get();

echo "📊 Total realisasi: " . $realisasi->count() . "\n\n";

$updated = 0;

foreach ($realisasi as $r) {
    // Generate nomor baru dengan microtime + ID untuk guarantee uniqueness
    $tanggal = date('Y/m/d', strtotime($r->tanggal_realisasi));
    $mikrodetik = str_replace('.', '', microtime(true));
    $uniqueId = substr($mikrodetik, -6) . str_pad($r->id, 4, '0', STR_PAD_LEFT); // microtime + ID
    
    $nomorBaru = "RLS/{$tanggal}/" . substr($uniqueId, 0, 10); // Max 10 digit
    
    // Update
    DB::table('realisasi_dana_operasional')
        ->where('id', $r->id)
        ->update(['nomor_realisasi' => $nomorBaru]);
    
    echo "✅ ID {$r->id}: {$r->nomor_realisasi} → {$nomorBaru}\n";
    $updated++;
    
    // Delay kecil untuk ensure microtime berbeda
    usleep(1000); // 1ms
}

echo "\n✅ Selesai! $updated nomor berhasil diregenerasi.\n";
echo "\n🎉 Sekarang bisa tambah transaksi baru tanpa error!\n";
