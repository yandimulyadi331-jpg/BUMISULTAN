<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CEK DATA PINJAMAN & POTONGAN ===\n\n";

// 1. Cek data pinjaman aktif
echo "1. DATA PINJAMAN AKTIF (BERJALAN):\n";
$pinjaman_aktif = DB::table('pinjaman')
    ->leftJoin('karyawan', 'pinjaman.karyawan_id', '=', 'karyawan.nik')
    ->select(
        'pinjaman.id',
        'pinjaman.nomor_pinjaman',
        'pinjaman.kategori_peminjam',
        'pinjaman.karyawan_id as nik',
        'karyawan.nama_karyawan',
        'pinjaman.nama_peminjam',
        'pinjaman.status',
        'pinjaman.jumlah_disetujui',
        'pinjaman.total_pinjaman',
        'pinjaman.total_terbayar',
        'pinjaman.sisa_pinjaman',
        'pinjaman.cicilan_per_bulan'
    )
    ->where('pinjaman.status', 'berjalan')
    ->get();

if ($pinjaman_aktif->count() > 0) {
    echo "Total pinjaman aktif: " . $pinjaman_aktif->count() . "\n";
    echo str_repeat("-", 140) . "\n";
    printf("%-5s %-20s %-10s %-15s %-25s %15s %15s %15s\n", 
        "ID", "Nomor", "Kategori", "NIK", "Nama", "Total", "Terbayar", "Cicil/Bln");
    echo str_repeat("-", 140) . "\n";
    
    foreach ($pinjaman_aktif as $p) {
        $nama = $p->kategori_peminjam == 'crew' ? $p->nama_karyawan : $p->nama_peminjam;
        printf("%-5s %-20s %-10s %-15s %-25s %15s %15s %15s\n",
            $p->id,
            $p->nomor_pinjaman,
            $p->kategori_peminjam,
            $p->nik ?? '-',
            substr($nama, 0, 25),
            number_format($p->total_pinjaman, 0, ',', '.'),
            number_format($p->total_terbayar, 0, ',', '.'),
            number_format($p->cicilan_per_bulan, 0, ',', '.')
        );
    }
    echo str_repeat("-", 140) . "\n\n";
} else {
    echo "❌ TIDAK ADA pinjaman dengan status BERJALAN!\n\n";
}

// 2. Cek data potongan pinjaman PENDING
echo "2. DATA POTONGAN PINJAMAN PENDING:\n";
$potongan_pending = DB::table('potongan_pinjaman_payroll')
    ->leftJoin('karyawan', 'potongan_pinjaman_payroll.nik', '=', 'karyawan.nik')
    ->leftJoin('pinjaman', 'potongan_pinjaman_payroll.pinjaman_id', '=', 'pinjaman.id')
    ->select(
        'potongan_pinjaman_payroll.*',
        'karyawan.nama_karyawan',
        'pinjaman.nomor_pinjaman'
    )
    ->where('potongan_pinjaman_payroll.status', 'pending')
    ->orderBy('potongan_pinjaman_payroll.bulan', 'desc')
    ->orderBy('potongan_pinjaman_payroll.tahun', 'desc')
    ->get();

if ($potongan_pending->count() > 0) {
    echo "Total potongan pending: " . $potongan_pending->count() . "\n";
    echo str_repeat("-", 140) . "\n";
    printf("%-5s %-15s %-25s %-20s %-10s %-12s %15s\n", 
        "ID", "NIK", "Nama", "Nomor Pinjaman", "Periode", "Status", "Jumlah");
    echo str_repeat("-", 140) . "\n";
    
    foreach ($potongan_pending as $p) {
        printf("%-5s %-15s %-25s %-20s %02d/%04d     %-12s %15s\n",
            $p->id,
            $p->nik,
            substr($p->nama_karyawan, 0, 25),
            $p->nomor_pinjaman,
            $p->bulan,
            $p->tahun,
            $p->status,
            number_format($p->jumlah_potongan, 0, ',', '.')
        );
    }
    echo str_repeat("-", 140) . "\n";
    
    $total_pending = $potongan_pending->sum('jumlah_potongan');
    echo "\n💡 TOTAL POTONGAN PENDING: Rp " . number_format($total_pending, 0, ',', '.') . "\n";
    echo "⚠️  Silakan PROSES potongan ini terlebih dahulu di menu:\n";
    echo "   Payroll > Potongan Pinjaman > Klik tombol 'Proses Potongan'\n\n";
} else {
    echo "❌ TIDAK ADA potongan dengan status PENDING\n\n";
}

// 3. Cek cicilan yang jatuh tempo bulan ini
echo "3. CICILAN JATUH TEMPO NOVEMBER 2025:\n";
$cicilan_november = DB::table('pinjaman_cicilan')
    ->join('pinjaman', 'pinjaman_cicilan.pinjaman_id', '=', 'pinjaman.id')
    ->leftJoin('karyawan', 'pinjaman.karyawan_id', '=', 'karyawan.nik')
    ->select(
        'pinjaman_cicilan.*',
        'pinjaman.nomor_pinjaman',
        'pinjaman.kategori_peminjam',
        'pinjaman.karyawan_id as nik',
        'karyawan.nama_karyawan',
        'pinjaman.nama_peminjam'
    )
    ->whereYear('pinjaman_cicilan.tanggal_jatuh_tempo', 2025)
    ->whereMonth('pinjaman_cicilan.tanggal_jatuh_tempo', 11)
    ->where('pinjaman_cicilan.status', 'belum_dibayar')
    ->orderBy('pinjaman_cicilan.tanggal_jatuh_tempo')
    ->get();

if ($cicilan_november->count() > 0) {
    echo "Total cicilan jatuh tempo: " . $cicilan_november->count() . "\n";
    echo str_repeat("-", 140) . "\n";
    printf("%-5s %-20s %-15s %-25s %-5s %-12s %15s\n", 
        "ID", "Nomor Pinjaman", "NIK", "Nama", "Ke-", "Jatuh Tempo", "Jumlah");
    echo str_repeat("-", 140) . "\n";
    
    foreach ($cicilan_november as $c) {
        $nama = $c->kategori_peminjam == 'crew' ? $c->nama_karyawan : $c->nama_peminjam;
        printf("%-5s %-20s %-15s %-25s %-5s %-12s %15s\n",
            $c->id,
            $c->nomor_pinjaman,
            $c->nik ?? '-',
            substr($nama, 0, 25),
            $c->cicilan_ke,
            date('d-m-Y', strtotime($c->tanggal_jatuh_tempo)),
            number_format($c->jumlah_cicilan, 0, ',', '.')
        );
    }
    echo str_repeat("-", 140) . "\n";
    
    $total_cicilan = $cicilan_november->sum('jumlah_cicilan');
    echo "\n💡 TOTAL CICILAN NOVEMBER: Rp " . number_format($total_cicilan, 0, ',', '.') . "\n";
    echo "ℹ️  Generate potongan pinjaman untuk periode ini dengan:\n";
    echo "   Payroll > Potongan Pinjaman > Generate Potongan\n\n";
} else {
    echo "❌ TIDAK ADA cicilan jatuh tempo November 2025\n\n";
}

echo "\n=== LANGKAH-LANGKAH ===\n";
echo "1. Pastikan ada pinjaman dengan status 'berjalan'\n";
echo "2. Generate potongan pinjaman untuk periode November 2025\n";
echo "3. Proses potongan pinjaman (ubah status dari PENDING ke DIPOTONG)\n";
echo "4. Cetak slip gaji - potongan pinjaman akan muncul otomatis\n";
echo "\n=== SELESAI ===\n";
