<?php

/**
 * SCRIPT: Kosongkan Semua Data Transaksi Keuangan
 * Menghapus semua data dari tabel dana operasional untuk mulai dari 0
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🗑️  HAPUS SEMUA DATA TRANSAKSI KEUANGAN\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "⚠️  PERHATIAN: Semua data transaksi akan dihapus PERMANEN!\n";
echo "Ketik 'YA' untuk melanjutkan atau 'TIDAK' untuk batal: ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$confirmation = trim(strtoupper($line));
fclose($handle);

if ($confirmation !== 'YA') {
    echo "\n❌ Operasi dibatalkan!\n\n";
    exit(0);
}

echo "\n🔄 Memulai penghapusan data...\n\n";

try {
    // 1. Hapus Realisasi Dana Operasional
    $countRealisasi = DB::table('realisasi_dana_operasional')->count();
    DB::table('realisasi_dana_operasional')->delete();
    echo "✅ Tabel 'realisasi_dana_operasional': {$countRealisasi} data dihapus\n";
    
    // 2. Hapus Saldo Harian Operasional
    $countSaldo = DB::table('saldo_harian_operasional')->count();
    DB::table('saldo_harian_operasional')->delete();
    echo "✅ Tabel 'saldo_harian_operasional': {$countSaldo} data dihapus\n";
    
    // 3. Hapus Pengajuan Dana Operasional
    $countPengajuan = DB::table('pengajuan_dana_operasional')->count();
    DB::table('pengajuan_dana_operasional')->delete();
    echo "✅ Tabel 'pengajuan_dana_operasional': {$countPengajuan} data dihapus\n";
    
    // 4. Reset Auto Increment
    DB::statement('ALTER TABLE realisasi_dana_operasional AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE saldo_harian_operasional AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE pengajuan_dana_operasional AUTO_INCREMENT = 1');
    echo "✅ Auto increment direset ke 1\n";
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ SEMUA DATA BERHASIL DIHAPUS!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "📊 Ringkasan:\n";
    echo "   - Transaksi realisasi: {$countRealisasi} dihapus\n";
    echo "   - Saldo harian: {$countSaldo} dihapus\n";
    echo "   - Pengajuan dana: {$countPengajuan} dihapus\n";
    echo "   - Auto increment: DIRESET\n\n";
    
    echo "🎉 Sistem keuangan sekarang BERSIH dan siap untuk data baru!\n";
    echo "💡 Anda bisa mulai dari 0 dengan alur baru.\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}
