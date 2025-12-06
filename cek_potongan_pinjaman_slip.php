<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CEK DATA POTONGAN PINJAMAN UNTUK SLIP GAJI ===\n\n";

// Cek data potongan pinjaman yang status dipotong
$potongan = DB::table('potongan_pinjaman_payroll')
    ->join('karyawan', 'potongan_pinjaman_payroll.nik', '=', 'karyawan.nik')
    ->join('pinjaman', 'potongan_pinjaman_payroll.pinjaman_id', '=', 'pinjaman.id')
    ->select(
        'potongan_pinjaman_payroll.*',
        'karyawan.nama_karyawan',
        'pinjaman.nomor_pinjaman',
        'pinjaman.kategori_peminjam'
    )
    ->where('potongan_pinjaman_payroll.status', 'dipotong')
    ->orderBy('potongan_pinjaman_payroll.bulan', 'desc')
    ->orderBy('potongan_pinjaman_payroll.tahun', 'desc')
    ->limit(10)
    ->get();

echo "Total potongan pinjaman dengan status DIPOTONG: " . $potongan->count() . "\n\n";

if ($potongan->count() > 0) {
    echo "Sample data (10 terakhir):\n";
    echo str_repeat("-", 120) . "\n";
    printf("%-15s %-20s %-10s %-20s %-15s %-15s\n", 
        "NIK", "Nama", "Periode", "Nomor Pinjaman", "Jumlah", "Status");
    echo str_repeat("-", 120) . "\n";
    
    foreach ($potongan as $p) {
        printf("%-15s %-20s %02d/%04d     %-20s %15s %-15s\n", 
            $p->nik,
            substr($p->nama_karyawan, 0, 20),
            $p->bulan,
            $p->tahun,
            substr($p->nomor_pinjaman, 0, 20),
            number_format($p->jumlah_potongan, 0, ',', '.'),
            $p->status
        );
    }
    echo str_repeat("-", 120) . "\n\n";
} else {
    echo "❌ TIDAK ADA data potongan pinjaman dengan status DIPOTONG!\n\n";
}

// Cek total per periode
echo "=== TOTAL POTONGAN PER PERIODE ===\n";
$total_per_periode = DB::table('potongan_pinjaman_payroll')
    ->select(
        'bulan',
        'tahun',
        'status',
        DB::raw('COUNT(*) as jumlah'),
        DB::raw('SUM(jumlah_potongan) as total')
    )
    ->groupBy('bulan', 'tahun', 'status')
    ->orderBy('tahun', 'desc')
    ->orderBy('bulan', 'desc')
    ->get();

if ($total_per_periode->count() > 0) {
    echo str_repeat("-", 80) . "\n";
    printf("%-10s %-10s %-15s %15s %15s\n", "Bulan", "Tahun", "Status", "Jumlah Data", "Total Potongan");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($total_per_periode as $t) {
        printf("%02d         %04d       %-15s %15d %15s\n", 
            $t->bulan,
            $t->tahun,
            $t->status,
            $t->jumlah,
            number_format($t->total, 0, ',', '.')
        );
    }
    echo str_repeat("-", 80) . "\n\n";
}

// Cek karyawan yang punya potongan pinjaman bulan November 2025
echo "=== KARYAWAN DENGAN POTONGAN PINJAMAN NOVEMBER 2025 ===\n";
$karyawan_november = DB::table('potongan_pinjaman_payroll')
    ->join('karyawan', 'potongan_pinjaman_payroll.nik', '=', 'karyawan.nik')
    ->select(
        'potongan_pinjaman_payroll.nik',
        'karyawan.nama_karyawan',
        'potongan_pinjaman_payroll.status',
        DB::raw('COUNT(*) as jumlah_cicilan'),
        DB::raw('SUM(jumlah_potongan) as total_potongan')
    )
    ->where('potongan_pinjaman_payroll.bulan', 11)
    ->where('potongan_pinjaman_payroll.tahun', 2025)
    ->groupBy('potongan_pinjaman_payroll.nik', 'karyawan.nama_karyawan', 'potongan_pinjaman_payroll.status')
    ->get();

if ($karyawan_november->count() > 0) {
    echo str_repeat("-", 100) . "\n";
    printf("%-15s %-30s %-15s %15s %15s\n", "NIK", "Nama Karyawan", "Status", "Jml Cicilan", "Total Potongan");
    echo str_repeat("-", 100) . "\n";
    
    foreach ($karyawan_november as $k) {
        printf("%-15s %-30s %-15s %15d %15s\n", 
            $k->nik,
            substr($k->nama_karyawan, 0, 30),
            $k->status,
            $k->jumlah_cicilan,
            number_format($k->total_potongan, 0, ',', '.')
        );
    }
    echo str_repeat("-", 100) . "\n\n";
} else {
    echo "❌ Tidak ada karyawan dengan potongan pinjaman untuk November 2025\n\n";
}

// Cek apakah ada data PENDING yang belum diproses
$pending = DB::table('potongan_pinjaman_payroll')
    ->where('status', 'pending')
    ->count();

if ($pending > 0) {
    echo "⚠️  PERHATIAN: Ada {$pending} data potongan dengan status PENDING!\n";
    echo "   Silakan proses potongan pinjaman terlebih dahulu di menu Potongan Pinjaman.\n\n";
}

echo "\n=== SELESAI ===\n";
