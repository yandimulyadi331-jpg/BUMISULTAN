<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KehadiranTukang;
use App\Models\Tukang;

echo "=== CEK DATA KEHADIRAN TUKANG ===\n\n";

$kehadiran = KehadiranTukang::with('tukang')->get();

if ($kehadiran->isEmpty()) {
    echo "Belum ada data kehadiran\n";
} else {
    foreach ($kehadiran as $k) {
        echo "ID: {$k->id}\n";
        echo "Tukang: {$k->tukang->nama_tukang}\n";
        echo "Tanggal: {$k->tanggal}\n";
        echo "Status: {$k->status}\n";
        echo "Lembur: " . ($k->lembur ? 'Ya' : 'Tidak') . "\n";
        echo "Tarif Harian Tukang: Rp " . number_format($k->tukang->tarif_harian, 0, ',', '.') . "\n";
        echo "Upah Harian: Rp " . number_format($k->upah_harian, 0, ',', '.') . "\n";
        echo "Upah Lembur: Rp " . number_format($k->upah_lembur, 0, ',', '.') . "\n";
        echo "TOTAL UPAH: Rp " . number_format($k->total_upah, 0, ',', '.') . "\n";
        echo "-----------------------------------\n";
    }
}

echo "\n=== HITUNG ULANG ===\n";
echo "Menghitung ulang upah untuk semua data...\n";

foreach ($kehadiran as $k) {
    $k->hitungUpah();
    $k->save();
    echo "Updated {$k->tukang->nama_tukang} - Total: Rp " . number_format($k->total_upah, 0, ',', '.') . "\n";
}

echo "\nSelesai!\n";
