<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KehadiranTukang;

echo "=== UPDATE DATA LEMBUR KE SISTEM BARU ===\n\n";

$kehadiran = KehadiranTukang::all();

foreach ($kehadiran as $k) {
    // Jika data lama memiliki lembur = 1 atau true, ubah ke 'tidak' 
    // (karena sekarang default 'tidak', user harus klik lagi untuk pilih)
    if ($k->lembur == '1' || $k->lembur === true) {
        $k->lembur = 'tidak';
        $k->hitungUpah();
        $k->save();
        echo "✓ Updated {$k->tukang->nama_tukang} ({$k->tanggal}) - Lembur direset ke 'tidak'\n";
    }
}

echo "\n=== DEMO FITUR BARU ===\n\n";
echo "Sekarang ada 3 pilihan lembur:\n";
echo "1. TIDAK LEMBUR (abu-abu) - Bonus: Rp 0\n";
echo "2. LEMBUR FULL 8 JAM (merah) - Bonus: 100% tarif harian\n";
echo "3. LEMBUR SETENGAH HARI 4 JAM (orange) - Bonus: 50% tarif harian\n\n";

$tukang = \App\Models\Tukang::first();
if ($tukang) {
    $tarif = number_format($tukang->tarif_harian, 0, ',', '.');
    echo "Contoh untuk {$tukang->nama_tukang} (Tarif: Rp {$tarif}):\n";
    echo "- Hadir + Tidak Lembur = Rp {$tarif}\n";
    echo "- Hadir + Lembur Full = Rp " . number_format($tukang->tarif_harian * 2, 0, ',', '.') . "\n";
    echo "- Hadir + Lembur Setengah = Rp " . number_format($tukang->tarif_harian * 1.5, 0, ',', '.') . "\n";
}

echo "\nSelesai! Silakan refresh halaman kehadiran tukang.\n";
