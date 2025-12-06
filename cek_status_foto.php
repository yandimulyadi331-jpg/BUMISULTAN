<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;

echo "=== CEK STATUS FOTO TRANSAKSI ===\n\n";

$transaksi = RealisasiDanaOperasional::where('tanggal_realisasi', '2025-11-13')
    ->orderBy('id')
    ->get();

echo "Total Transaksi: " . $transaksi->count() . "\n\n";

foreach ($transaksi as $t) {
    $status = $t->foto_bukti ? '✓ Ada Foto' : '✗ Belum Ada Foto';
    echo "ID {$t->id} | {$t->nomor_transaksi} | {$t->keterangan} | {$status}\n";
}

echo "\n=== SIMULASI TAMPILAN ===\n\n";
foreach ($transaksi as $t) {
    if ($t->foto_bukti) {
        echo "Transaksi {$t->nomor_transaksi}: [Tombol FOTO (Biru)] - Klik untuk lihat\n";
    } else {
        echo "Transaksi {$t->nomor_transaksi}: [Tombol UPLOAD (Biru)] - Klik untuk upload\n";
    }
}

echo "\n✓ Refresh halaman untuk melihat tombol upload/foto yang baru!\n";
