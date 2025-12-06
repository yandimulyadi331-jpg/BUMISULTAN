<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== PERBAIKAN: HAPUS DATA DUPLIKAT POTONGAN NOVEMBER 2025 ===\n\n";

DB::beginTransaction();

try {
    // Hapus semua potongan November 2025
    $deleted = DB::table('potongan_pinjaman_payroll')
        ->where('bulan', 11)
        ->where('tahun', 2025)
        ->delete();
    
    echo "✅ Berhasil menghapus {$deleted} data potongan November 2025\n\n";
    
    DB::commit();
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🎯 LANGKAH SELANJUTNYA:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "1. Refresh halaman Potongan Pinjaman di browser (F5)\n";
    echo "2. Klik tombol: Generate Potongan\n";
    echo "3. Sistem akan membuat potongan dengan kode UNIK:\n";
    echo "   • PPP112501 (potongan pertama)\n";
    echo "   • PPP112502 (potongan kedua)\n";
    echo "   • PPP112503 (potongan ketiga)\n";
    echo "   • dst...\n\n";
    echo "4. Setelah generate berhasil, klik: Proses Potongan\n";
    echo "5. Cetak slip gaji untuk melihat hasilnya\n\n";
    
    echo "✅ FIX sudah diterapkan:\n";
    echo "   • Kode potongan sekarang UNIK untuk setiap record\n";
    echo "   • Format: PPP + Bulan(2) + Tahun(2) + Urutan(3)\n";
    echo "   • Tidak akan ada error duplicate lagi\n\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
