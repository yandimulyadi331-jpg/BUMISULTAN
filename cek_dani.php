<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\JamaahMasar;

echo "=== CARI JAMAAH DANI ===\n\n";

// Cari by NIK dari screenshot
$jamaah = JamaahMasar::where('nik', '251200004')->first();

if ($jamaah) {
    echo "Nama: " . $jamaah->nama_jamaah . "\n";
    echo "NIK: " . $jamaah->nik . "\n";
    echo "Kehadiran: " . $jamaah->jumlah_kehadiran . "\n";
    echo "PIN: " . $jamaah->pin_fingerprint . "\n\n";
} else {
    echo "Tidak ditemukan dengan NIK 251200004\n";
    echo "Cek sample data:\n\n";
    
    $all = JamaahMasar::limit(5)->get(['id', 'nik', 'nama_jamaah', 'jumlah_kehadiran']);
    foreach ($all as $j) {
        echo $j->nik . ' - ' . $j->nama_jamaah . ' (Kehadiran: ' . $j->jumlah_kehadiran . ")\n";
    }
}
