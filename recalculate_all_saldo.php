<?php

/**
 * SCRIPT: Recalculate Semua Saldo Harian
 * 
 * Fungsi: Menghitung ulang semua saldo harian dari awal sampai akhir
 * dengan logika baru yang benar:
 * - Saldo positif masuk ke Dana Masuk
 * - Saldo negatif masuk ke Dana Keluar
 * - Cascade akumulasi dari hari ke hari
 * 
 * CARA PAKAI:
 * php recalculate_all_saldo.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SaldoHarianOperasional;
use App\Models\RealisasiDanaOperasional;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   RECALCULATE SEMUA SALDO HARIAN - DANA OPERASIONAL           ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

try {
    DB::beginTransaction();
    
    echo "📊 Mengambil semua data saldo harian...\n";
    
    // Ambil semua saldo harian, urutkan dari yang terlama
    $allSaldo = SaldoHarianOperasional::orderBy('tanggal', 'asc')->get();
    
    if ($allSaldo->isEmpty()) {
        echo "❌ Tidak ada data saldo harian yang ditemukan.\n";
        echo "   Silakan import data Excel terlebih dahulu.\n";
        exit(1);
    }
    
    echo "✅ Ditemukan " . $allSaldo->count() . " hari data saldo\n";
    echo "   Periode: " . $allSaldo->first()->tanggal->format('d M Y') . " s/d " . $allSaldo->last()->tanggal->format('d M Y') . "\n\n";
    
    echo "🔄 Mulai recalculate...\n";
    echo str_repeat("─", 70) . "\n";
    
    $saldoSebelumnya = 0; // Mulai dari 0 (hari pertama saldo awal = 0)
    $processed = 0;
    
    foreach ($allSaldo as $saldo) {
        $tanggalStr = $saldo->tanggal->format('Y-m-d');
        
        echo "📅 {$tanggalStr}\n";
        
        // Set saldo awal dari hari sebelumnya
        $saldo->saldo_awal = $saldoSebelumnya;
        
        // Hitung transaksi hari ini (HANYA ACTIVE)
        $transaksi = RealisasiDanaOperasional::whereDate('tanggal_realisasi', $tanggalStr)
            ->where('status', 'active')
            ->get();
        
        $totalMasuk = $transaksi->where('tipe_transaksi', 'masuk')->sum('nominal');
        $totalKeluar = $transaksi->where('tipe_transaksi', 'keluar')->sum('nominal');
        
        echo "   Transaksi: " . $transaksi->count() . " items\n";
        echo "   Masuk: Rp " . number_format($totalMasuk, 0, ',', '.') . "\n";
        echo "   Keluar: Rp " . number_format($totalKeluar, 0, ',', '.') . "\n";
        
        // LOGIKA BARU: Include saldo_awal dalam perhitungan dana_masuk/dana_keluar
        if ($saldo->saldo_awal >= 0) {
            // Saldo positif = Dana Masuk
            $saldo->dana_masuk = $saldo->saldo_awal + $totalMasuk;
            $saldo->total_realisasi = $totalKeluar;
        } else {
            // Saldo negatif = Dana Keluar (kekurangan)
            $saldo->dana_masuk = $totalMasuk;
            $saldo->total_realisasi = abs($saldo->saldo_awal) + $totalKeluar;
        }
        
        // Hitung saldo akhir
        $saldo->saldo_akhir = $saldo->saldo_awal + $totalMasuk - $totalKeluar;
        $saldo->save();
        
        echo "   Saldo Awal: Rp " . number_format($saldo->saldo_awal, 0, ',', '.') . "\n";
        echo "   Saldo Akhir: Rp " . number_format($saldo->saldo_akhir, 0, ',', '.') . "\n";
        
        // Update saldo untuk hari berikutnya
        $saldoSebelumnya = $saldo->saldo_akhir;
        
        $processed++;
        echo "   ✅ Selesai\n";
        echo str_repeat("─", 70) . "\n";
    }
    
    DB::commit();
    
    echo "\n";
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║   ✅ RECALCULATE SELESAI!                                     ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "📊 Total hari diproses: {$processed}\n";
    echo "💰 Saldo Akhir Terakhir: Rp " . number_format($saldoSebelumnya, 0, ',', '.') . "\n";
    echo "\n";
    echo "✅ Semua saldo telah dihitung ulang dengan logika baru!\n";
    echo "   - Saldo positif → Dana Masuk\n";
    echo "   - Saldo negatif → Dana Keluar\n";
    echo "   - Cascade akumulasi sudah benar\n";
    echo "\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    
    echo "\n";
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
    echo "\n";
    exit(1);
}
