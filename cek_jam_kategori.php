<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;

echo "=== CEK JAM DAN KATEGORI TRANSAKSI ===\n\n";

$transaksi = RealisasiDanaOperasional::where('tanggal_realisasi', '2025-11-13')
    ->orderBy('id')
    ->get();

echo "Total Transaksi: " . $transaksi->count() . "\n\n";

echo str_pad("NOMOR", 18) . " | " . str_pad("JAM", 8) . " | " . str_pad("KATEGORI", 20) . " | KETERANGAN\n";
echo str_repeat("-", 80) . "\n";

foreach ($transaksi as $t) {
    $jam = $t->created_at->format('H:i');
    $kategori = $t->kategori ?? 'BELUM ADA';
    
    echo str_pad($t->nomor_transaksi, 18) . " | ";
    echo str_pad($jam, 8) . " | ";
    echo str_pad($kategori, 20) . " | ";
    echo $t->keterangan . "\n";
}

echo "\n✓ Kolom JAM dan KATEGORI sudah ditambahkan ke tabel!\n";
echo "✓ Refresh halaman untuk melihat tampilan baru.\n";
