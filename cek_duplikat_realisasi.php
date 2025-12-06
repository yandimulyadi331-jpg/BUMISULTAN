<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 CEK NOMOR REALISASI DUPLICATE\n";
echo "========================================\n\n";

// Cari nomor realisasi yang duplicate
$duplicates = DB::select("
    SELECT nomor_realisasi, COUNT(*) as jumlah 
    FROM realisasi_dana_operasional 
    GROUP BY nomor_realisasi 
    HAVING COUNT(*) > 1
");

echo "📊 Nomor duplicate: " . count($duplicates) . "\n\n";

foreach ($duplicates as $dup) {
    echo "• {$dup->nomor_realisasi} - {$dup->jumlah} kali\n";
    
    // Tampilkan detailnya
    $records = DB::table('realisasi_dana_operasional')
        ->where('nomor_realisasi', $dup->nomor_realisasi)
        ->get();
    
    foreach ($records as $r) {
        echo "  - ID: {$r->id} | {$r->tanggal_realisasi} | {$r->uraian} | Rp " . number_format($r->nominal, 0, ',', '.') . "\n";
    }
}

// Cek khusus nomor yang error
$problemNumber = 'RLS/2025/01/336';
$existing = DB::table('realisasi_dana_operasional')
    ->where('nomor_realisasi', $problemNumber)
    ->get();

echo "\n🔍 CEK NOMOR YANG ERROR: $problemNumber\n";
echo "========================================\n";
echo "Jumlah: " . $existing->count() . " record\n\n";

foreach ($existing as $r) {
    echo "ID: {$r->id} | {$r->tanggal_realisasi} | {$r->uraian} | {$r->tipe_transaksi} | Rp " . number_format($r->nominal, 0, ',', '.') . "\n";
}

echo "\n💡 SOLUSI:\n";
echo "========================================\n";
echo "Jalankan: php hapus_duplikat_realisasi.php\n";
echo "Untuk membersihkan nomor duplicate dan regenerate nomor baru\n";
