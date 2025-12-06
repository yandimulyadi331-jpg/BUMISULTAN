<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;

echo "=== DEMO AI KATEGORI DETECTOR ===\n\n";
echo "AI System dapat mengenali kategori berdasarkan keterangan:\n\n";

$testCases = [
    "Pembelian BBM Pertamax untuk mobil operasional",
    "Bayar listrik PLN bulan November",
    "Beli pulsa dan paket data internet",
    "Konsumsi rapat bulanan",
    "Pembelian ATK untuk kantor",
    "Service motor dan ganti oli",
    "Pembersihan ruangan dan sabun cuci piring",
    "Perbaikan AC ruang tamu",
    "Beli obat P3K dan vitamin",
    "Telepon dan komunikasi",
    "Administrasi surat dan materai",
    "Khidmat santri bulan ini",
    "Transfer masuk dari donatur",
    "Parkir mobil dan tol",
    "Catering makan siang 50 porsi",
    "Fotocopy dokumen penting",
    "Renovasi kamar mandi",
    "Bensin motor antar jemput",
    "Token listrik",
    "Sabun mandi dan tisu",
];

echo str_pad("KETERANGAN", 50) . " | KATEGORI TERDETEKSI\n";
echo str_repeat("-", 90) . "\n";

foreach ($testCases as $keterangan) {
    $kategori = RealisasiDanaOperasional::detectKategoriAI($keterangan);
    echo str_pad($keterangan, 50) . " | " . $kategori . "\n";
}

echo "\n" . str_repeat("=", 90) . "\n";
echo "✓ AI System menggunakan Pattern Recognition dengan keyword matching\n";
echo "✓ Setiap kategori memiliki bobot (weight) untuk akurasi lebih baik\n";
echo "✓ Keyword di awal kalimat mendapat score lebih tinggi\n";
echo "✓ Jika tidak ada yang cocok, default = 'Operasional'\n";
