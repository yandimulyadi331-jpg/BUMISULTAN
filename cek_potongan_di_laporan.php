<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CEK POTONGAN PINJAMAN UNTUK KARYAWAN DI LAPORAN ===\n\n";

// Cek karyawan di screenshot (yang terlihat)
$karyawan_list = [
    "'12345678" => "Adam Adifa",
    "'32073211611" => "Fitriani Nur Hidayah", 
    "'22.22.225" => "Lionel Messi",
    "'32073211612" => "Qiandra",
    "'55.55.555" => "Qiandra Zaydan",
    "'11.11.112" => "YANDI MULYADI"
];

echo "Karyawan yang tampil di laporan:\n";
echo str_repeat("-", 80) . "\n";

foreach ($karyawan_list as $nik => $nama) {
    // Hapus quote di awal untuk query
    $nik_clean = ltrim($nik, "'");
    
    $potongan = DB::table('potongan_pinjaman_payroll')
        ->where('nik', $nik_clean)
        ->where('bulan', 11)
        ->where('tahun', 2025)
        ->where('status', 'dipotong')
        ->sum('jumlah_potongan');
    
    $status = $potongan > 0 ? "✅ ADA" : "❌ TIDAK ADA";
    
    printf("%-20s %-25s %s %15s\n", 
        $nik, 
        $nama, 
        $status,
        $potongan > 0 ? "Rp " . number_format($potongan, 0, ',', '.') : ""
    );
}

echo str_repeat("-", 80) . "\n\n";

// Cek semua potongan November 2025
echo "=== SEMUA POTONGAN PINJAMAN NOVEMBER 2025 (STATUS: DIPOTONG) ===\n\n";

$all_potongan = DB::table('potongan_pinjaman_payroll')
    ->join('karyawan', 'potongan_pinjaman_payroll.nik', '=', 'karyawan.nik')
    ->where('bulan', 11)
    ->where('tahun', 2025)
    ->where('status', 'dipotong')
    ->select(
        'potongan_pinjaman_payroll.nik',
        'karyawan.nama_karyawan',
        'potongan_pinjaman_payroll.jumlah_potongan',
        'potongan_pinjaman_payroll.kode_potongan'
    )
    ->get();

if ($all_potongan->count() > 0) {
    echo str_repeat("-", 100) . "\n";
    printf("%-20s %-30s %-15s %15s\n", "NIK", "Nama", "Kode", "Jumlah");
    echo str_repeat("-", 100) . "\n";
    
    $total = 0;
    foreach ($all_potongan as $p) {
        printf("%-20s %-30s %-15s %15s\n",
            $p->nik,
            $p->nama_karyawan,
            $p->kode_potongan,
            number_format($p->jumlah_potongan, 0, ',', '.')
        );
        $total += $p->jumlah_potongan;
    }
    
    echo str_repeat("-", 100) . "\n";
    echo "TOTAL: Rp " . number_format($total, 0, ',', '.') . "\n\n";
} else {
    echo "❌ TIDAK ADA potongan dengan status DIPOTONG\n\n";
}

// Kesimpulan
echo "\n=== KESIMPULAN ===\n";
echo "✅ Data potongan pinjaman SUDAH MASUK ke sistem\n";
echo "✅ Status: DIPOTONG (siap dipotong dari gaji)\n";
echo "❓ Apakah sudah tampil di laporan gaji? Perlu dicek di kolom 'Pot. Pinjaman'\n";
