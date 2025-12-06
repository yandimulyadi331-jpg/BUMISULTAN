<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Kendaraan;
use Illuminate\Support\Facades\Crypt;

$kendaraan = Kendaraan::with(['peminjamanAktif', 'aktivitasAktif'])->first();

if (!$kendaraan) {
    die("Kendaraan tidak ditemukan\n");
}

echo "=== DEBUG KENDARAAN {$kendaraan->kode_kendaraan} ===\n\n";
echo "Status: {$kendaraan->status}\n";
echo "Ketersediaan: {$kendaraan->ketersediaan}\n\n";

echo "--- Peminjaman Aktif ---\n";
if ($kendaraan->peminjamanAktif) {
    echo "✅ ADA peminjaman aktif\n";
    echo "ID: {$kendaraan->peminjamanAktif->id}\n";
    echo "Status: {$kendaraan->peminjamanAktif->status}\n";
    echo "Peminjam: {$kendaraan->peminjamanAktif->nama_peminjam}\n";
} else {
    echo "❌ TIDAK ada peminjaman aktif\n";
}

echo "\n--- Aktivitas Aktif ---\n";
if ($kendaraan->aktivitasAktif) {
    echo "✅ ADA aktivitas aktif\n";
    echo "Status: {$kendaraan->aktivitasAktif->status}\n";
} else {
    echo "❌ TIDAK ada aktivitas aktif\n";
}

echo "\n--- Proses Aktif ---\n";
if (isset($kendaraan->prosesAktif)) {
    echo "✅ ADA proses aktif\n";
    var_dump($kendaraan->prosesAktif);
} else {
    echo "❌ TIDAK ada prosesAktif\n";
}

echo "\n\n✅ Debug selesai\n";
