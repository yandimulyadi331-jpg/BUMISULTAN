<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Karyawan;
use App\Models\KpiCrew;

echo "=== CEK DATA KARYAWAN ===\n";
echo "Total Karyawan: " . Karyawan::count() . "\n";
echo "Karyawan dengan NIK: " . Karyawan::whereNotNull('nik')->count() . "\n\n";

echo "Daftar Karyawan:\n";
$karyawans = Karyawan::select('nik', 'nama_karyawan')->get();
foreach($karyawans as $k) {
    echo $k->nik . " - " . $k->nama_karyawan . "\n";
}

echo "\n=== CEK DATA KPI NOVEMBER 2025 ===\n";
$kpiData = KpiCrew::where('bulan', 11)->where('tahun', 2025)->get();
echo "Total KPI Records: " . $kpiData->count() . "\n";

if ($kpiData->count() > 0) {
    echo "\nDetail KPI:\n";
    foreach($kpiData as $kpi) {
        echo "NIK: {$kpi->nik} | Kehadiran: {$kpi->kehadiran_count} | Aktivitas: {$kpi->aktivitas_count} | Perawatan: {$kpi->perawatan_count} | Total: {$kpi->total_point}\n";
    }
}

echo "\n=== CEK DATA KPI DENGAN RELASI KARYAWAN ===\n";
$kpiWithKaryawan = KpiCrew::with('karyawan')
    ->where('bulan', 11)
    ->where('tahun', 2025)
    ->get();

echo "Total KPI dengan relasi: " . $kpiWithKaryawan->count() . "\n";

$kpiHasKaryawan = KpiCrew::whereHas('karyawan')
    ->where('bulan', 11)
    ->where('tahun', 2025)
    ->get();

echo "Total KPI whereHas karyawan: " . $kpiHasKaryawan->count() . "\n";

echo "\nDetail:\n";
foreach($kpiWithKaryawan as $kpi) {
    if ($kpi->karyawan) {
        echo "✓ NIK: {$kpi->nik} | Nama: {$kpi->karyawan->nama_karyawan}\n";
    } else {
        echo "✗ NIK: {$kpi->nik} | Karyawan: NULL (data tidak ditemukan)\n";
    }
}
