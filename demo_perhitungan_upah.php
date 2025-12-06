<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KehadiranTukang;

echo "=== DEMO PERHITUNGAN UPAH ===\n\n";

$kehadiran = KehadiranTukang::first();

if (!$kehadiran) {
    echo "Belum ada data\n";
    exit;
}

echo "Tukang: {$kehadiran->tukang->nama_tukang}\n";
echo "Tarif Harian: Rp " . number_format($kehadiran->tukang->tarif_harian, 0, ',', '.') . "\n\n";

echo "SKENARIO 1: HADIR (tanpa lembur)\n";
$kehadiran->status = 'hadir';
$kehadiran->lembur = false;
$kehadiran->hitungUpah();
echo "- Status: Hadir\n";
echo "- Lembur: Tidak\n";
echo "- Upah Harian: Rp " . number_format($kehadiran->upah_harian, 0, ',', '.') . "\n";
echo "- Upah Lembur: Rp " . number_format($kehadiran->upah_lembur, 0, ',', '.') . "\n";
echo "- TOTAL: Rp " . number_format($kehadiran->total_upah, 0, ',', '.') . " ✓\n\n";

echo "SKENARIO 2: HADIR + LEMBUR\n";
$kehadiran->status = 'hadir';
$kehadiran->lembur = true;
$kehadiran->hitungUpah();
echo "- Status: Hadir\n";
echo "- Lembur: Ya\n";
echo "- Upah Harian: Rp " . number_format($kehadiran->upah_harian, 0, ',', '.') . "\n";
echo "- Upah Lembur: Rp " . number_format($kehadiran->upah_lembur, 0, ',', '.') . "\n";
echo "- TOTAL: Rp " . number_format($kehadiran->total_upah, 0, ',', '.') . "\n\n";

echo "SKENARIO 3: SETENGAH HARI (tanpa lembur)\n";
$kehadiran->status = 'setengah_hari';
$kehadiran->lembur = false;
$kehadiran->hitungUpah();
echo "- Status: Setengah Hari\n";
echo "- Lembur: Tidak\n";
echo "- Upah Harian: Rp " . number_format($kehadiran->upah_harian, 0, ',', '.') . " (50%)\n";
echo "- Upah Lembur: Rp " . number_format($kehadiran->upah_lembur, 0, ',', '.') . "\n";
echo "- TOTAL: Rp " . number_format($kehadiran->total_upah, 0, ',', '.') . "\n\n";

echo "SKENARIO 4: TIDAK HADIR\n";
$kehadiran->status = 'tidak_hadir';
$kehadiran->lembur = false;
$kehadiran->hitungUpah();
echo "- Status: Tidak Hadir\n";
echo "- Lembur: Tidak\n";
echo "- Upah Harian: Rp " . number_format($kehadiran->upah_harian, 0, ',', '.') . "\n";
echo "- Upah Lembur: Rp " . number_format($kehadiran->upah_lembur, 0, ',', '.') . "\n";
echo "- TOTAL: Rp " . number_format($kehadiran->total_upah, 0, ',', '.') . "\n\n";

echo "===================================\n";
echo "Untuk mendapat upah Rp 150.000 saja:\n";
echo "- Klik tombol status sampai HIJAU (Hadir)\n";
echo "- Pastikan toggle LEMBUR dalam posisi OFF\n";
