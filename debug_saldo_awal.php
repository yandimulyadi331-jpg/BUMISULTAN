<?php

/**
 * DEBUG SCRIPT: Cek Saldo Awal Per Hari
 * 
 * Script ini untuk debug kenapa saldo awal salah
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SaldoHarianOperasional;
use App\Models\RealisasiDanaOperasional;

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  DEBUG: SALDO AWAL PER HARI\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Ambil semua saldo harian (urutkan tanggal lama ke baru)
$saldoHarian = SaldoHarianOperasional::orderBy('tanggal', 'asc')->get();

if ($saldoHarian->count() == 0) {
    echo "❌ Tidak ada data saldo harian!\n\n";
    exit;
}

echo "Total hari tercatat: " . $saldoHarian->count() . " hari\n\n";

foreach ($saldoHarian as $index => $saldo) {
    echo "┌────────────────────────────────────────────────────┐\n";
    echo "│ Hari ke-" . ($index + 1) . ": " . $saldo->tanggal->format('d M Y') . " (" . $saldo->tanggal->format('Y-m-d') . ")\n";
    echo "├────────────────────────────────────────────────────┤\n";
    echo "│ Saldo Awal:        Rp " . number_format($saldo->saldo_awal, 2, ',', '.') . "\n";
    echo "│ Dana Masuk:        Rp " . number_format($saldo->dana_masuk, 2, ',', '.') . "\n";
    echo "│ Total Realisasi:   Rp " . number_format($saldo->total_realisasi, 2, ',', '.') . "\n";
    echo "│ Saldo Akhir:       Rp " . number_format($saldo->saldo_akhir, 2, ',', '.') . "\n";
    echo "└────────────────────────────────────────────────────┘\n";
    
    // Cek transaksi hari ini
    $transaksi = RealisasiDanaOperasional::whereDate('tanggal_realisasi', $saldo->tanggal)
        ->orderBy('urutan_baris', 'asc')
        ->get();
    
    if ($transaksi->count() > 0) {
        echo "  📝 Transaksi hari ini: " . $transaksi->count() . " transaksi\n";
        
        $totalMasuk = 0;
        $totalKeluar = 0;
        
        foreach ($transaksi as $t) {
            if ($t->tipe_transaksi == 'masuk') {
                $totalMasuk += $t->nominal;
            } else {
                $totalKeluar += $t->nominal;
            }
        }
        
        echo "  ✅ Total Masuk:  Rp " . number_format($totalMasuk, 2, ',', '.') . "\n";
        echo "  ⚠️  Total Keluar: Rp " . number_format($totalKeluar, 2, ',', '.') . "\n";
        
        // Hitung saldo akhir yang benar
        $saldoAkhirSeharusnya = $saldo->saldo_awal + $totalMasuk - $totalKeluar;
        echo "  🎯 Saldo Akhir Seharusnya: Rp " . number_format($saldoAkhirSeharusnya, 2, ',', '.') . "\n";
        
        if (abs($saldoAkhirSeharusnya - $saldo->saldo_akhir) > 0.01) {
            echo "  ❌ SALDO AKHIR SALAH! Beda: Rp " . number_format(abs($saldoAkhirSeharusnya - $saldo->saldo_akhir), 2, ',', '.') . "\n";
        } else {
            echo "  ✅ Saldo akhir BENAR!\n";
        }
    } else {
        echo "  📝 Tidak ada transaksi\n";
    }
    
    echo "\n";
    
    // Cek apakah saldo akhir hari ini = saldo awal hari besok
    if ($index < $saldoHarian->count() - 1) {
        $besok = $saldoHarian[$index + 1];
        if (abs($saldo->saldo_akhir - $besok->saldo_awal) > 0.01) {
            echo "  ⚠️  PERINGATAN: Saldo akhir hari ini (" . number_format($saldo->saldo_akhir, 2, ',', '.') . ")\n";
            echo "               TIDAK SAMA dengan saldo awal besok (" . number_format($besok->saldo_awal, 2, ',', '.') . ")!\n";
            echo "               Selisih: Rp " . number_format(abs($saldo->saldo_akhir - $besok->saldo_awal), 2, ',', '.') . "\n\n";
        }
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  DEBUG SELESAI\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "💡 CARA PERBAIKI:\n";
echo "   Jika ada saldo yang salah, jalankan:\n";
echo "   php fix_saldo_harian.php\n\n";
