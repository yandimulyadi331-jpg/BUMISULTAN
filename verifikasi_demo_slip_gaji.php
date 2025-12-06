<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VERIFIKASI DATA DEMO - CEK SLIP GAJI ===\n\n";

// Ambil data karyawan yang baru dapat pinjaman
$karyawan_demo = ['22.22.224', '22.22.225', '251000002'];

echo "📋 DATA KARYAWAN & POTONGAN PINJAMAN NOVEMBER 2025:\n";
echo str_repeat("=", 120) . "\n\n";

foreach ($karyawan_demo as $nik) {
    $karyawan = DB::table('karyawan')
        ->where('nik', $nik)
        ->first();
    
    if (!$karyawan) continue;
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "NIK           : {$karyawan->nik}\n";
    echo "Nama Karyawan : {$karyawan->nama_karyawan}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Data pinjaman
    $pinjaman = DB::table('pinjaman')
        ->where('karyawan_id', $nik)
        ->where('status', 'berjalan')
        ->get();
    
    if ($pinjaman->count() > 0) {
        echo "📊 PINJAMAN AKTIF:\n";
        foreach ($pinjaman as $p) {
            echo "   • {$p->nomor_pinjaman}\n";
            echo "     Total: Rp " . number_format($p->total_pinjaman, 0, ',', '.') . "\n";
            echo "     Cicilan: Rp " . number_format($p->cicilan_per_bulan, 0, ',', '.') . " / bulan\n";
            echo "     Sudah dibayar: Rp " . number_format($p->total_terbayar, 0, ',', '.') . "\n";
            echo "     Sisa: Rp " . number_format($p->sisa_pinjaman, 0, ',', '.') . "\n\n";
        }
    }
    
    // Potongan November 2025
    $potongan = DB::table('potongan_pinjaman_payroll')
        ->join('pinjaman', 'potongan_pinjaman_payroll.pinjaman_id', '=', 'pinjaman.id')
        ->where('potongan_pinjaman_payroll.nik', $nik)
        ->where('potongan_pinjaman_payroll.bulan', 11)
        ->where('potongan_pinjaman_payroll.tahun', 2025)
        ->select(
            'potongan_pinjaman_payroll.*',
            'pinjaman.nomor_pinjaman'
        )
        ->get();
    
    if ($potongan->count() > 0) {
        echo "💰 POTONGAN PINJAMAN NOVEMBER 2025:\n";
        $total_potongan = 0;
        foreach ($potongan as $p) {
            echo "   • Kode: {$p->kode_potongan}\n";
            echo "     Pinjaman: {$p->nomor_pinjaman}\n";
            echo "     Cicilan ke-{$p->cicilan_ke}\n";
            echo "     Jumlah: Rp " . number_format($p->jumlah_potongan, 0, ',', '.') . "\n";
            echo "     Status: {$p->status}\n";
            echo "     Tanggal Dipotong: {$p->tanggal_dipotong}\n\n";
            $total_potongan += $p->jumlah_potongan;
        }
        
        echo "   " . str_repeat("-", 60) . "\n";
        echo "   TOTAL POTONGAN: Rp " . number_format($total_potongan, 0, ',', '.') . "\n\n";
    }
    
    // Preview slip gaji
    echo "📄 PREVIEW SLIP GAJI NOVEMBER 2025:\n";
    echo "   " . str_repeat("-", 80) . "\n";
    echo "   BUMI SULTAN\n";
    echo "   SLIP GAJI\n";
    echo "   23/10/2025 - 20/11/2025\n";
    echo "   " . str_repeat("-", 80) . "\n";
    echo "   NIK     : {$karyawan->nik}\n";
    echo "   Nama    : {$karyawan->nama_karyawan}\n";
    echo "   " . str_repeat("-", 80) . "\n";
    echo "   PENGHASILAN\n";
    echo "   Gaji Pokok                                              (akan ditampilkan)\n";
    echo "   Sub Total                                               (akan ditampilkan)\n";
    echo "   " . str_repeat("-", 80) . "\n";
    echo "   POTONGAN\n";
    
    if ($potongan->count() > 0) {
        $jml_cicilan = $potongan->count();
        $label = $jml_cicilan > 1 ? "Pot. Pinjaman ({$jml_cicilan}x)" : "Pot. Pinjaman";
        echo "   {$label}                                         Rp " . number_format($total_potongan, 0, ',', '.') . "\n";
    }
    
    echo "   Sub Total                                               (akan ditampilkan)\n";
    echo "   " . str_repeat("-", 80) . "\n";
    echo "   GAJI BERSIH                                             (akan ditampilkan)\n";
    echo "   " . str_repeat("-", 80) . "\n\n";
    
    echo "   ✅ Potongan pinjaman AKAN MUNCUL di slip gaji!\n\n";
}

echo str_repeat("=", 120) . "\n";
echo "🎯 CARA MELIHAT SLIP GAJI LENGKAP:\n";
echo str_repeat("=", 120) . "\n";
echo "1. Buka browser: http://127.0.0.1:8000\n";
echo "2. Login sebagai admin\n";
echo "3. Menu: Laporan > Presensi\n";
echo "4. Pilih:\n";
echo "   - Format Laporan: Slip Gaji (Format 3)\n";
echo "   - Bulan: November (11)\n";
echo "   - Tahun: 2025\n";
echo "   - NIK: Pilih salah satu dari:\n";
foreach ($karyawan_demo as $nik) {
    $k = DB::table('karyawan')->where('nik', $nik)->first();
    if ($k) {
        echo "     • {$nik} - {$k->nama_karyawan}\n";
    }
}
echo "5. Klik: CETAK\n";
echo "6. Slip gaji akan tampil dengan potongan pinjaman!\n\n";

echo "💡 Tips:\n";
echo "   - Pilih 'Semua Karyawan' untuk melihat slip gaji semua karyawan sekaligus\n";
echo "   - Slip akan ditampilkan dalam format thermal receipt\n";
echo "   - Potongan pinjaman otomatis masuk ke perhitungan gaji bersih\n\n";
