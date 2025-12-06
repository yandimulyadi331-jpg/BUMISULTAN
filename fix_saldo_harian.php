<?php

/**
 * FIX SCRIPT: Recalculate Semua Saldo Harian
 * 
 * Script ini akan:
 * 1. Ambil semua transaksi dari realisasi_dana_operasional
 * 2. Hitung ulang saldo per hari berdasarkan transaksi RIIL
 * 3. Update tabel saldo_harian_operasional dengan data yang benar
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SaldoHarianOperasional;
use App\Models\RealisasiDanaOperasional;
use Carbon\Carbon;

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  🔧 FIX: RECALCULATE SALDO HARIAN\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Step 1: Ambil tanggal pertama transaksi
$transaksiPertama = RealisasiDanaOperasional::orderBy('tanggal_realisasi', 'asc')
    ->orderBy('urutan_baris', 'asc')
    ->first();

if (!$transaksiPertama) {
    echo "❌ Tidak ada transaksi! Import Excel dulu.\n\n";
    exit;
}

$tanggalMulai = $transaksiPertama->tanggal_realisasi->startOfDay();
$tanggalAkhir = RealisasiDanaOperasional::orderBy('tanggal_realisasi', 'desc')->first()
    ->tanggal_realisasi->endOfDay();

echo "📅 Periode: " . $tanggalMulai->format('d M Y') . " - " . $tanggalAkhir->format('d M Y') . "\n\n";

// Step 2: Tanya saldo awal
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 SALDO AWAL PERIODE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Cek transaksi pertama, apakah ada yang keterangannya "saldo awal" atau "sisa saldo"?
$transaksiSaldoAwal = RealisasiDanaOperasional::whereDate('tanggal_realisasi', $tanggalMulai)
    ->where('uraian', 'LIKE', '%saldo%awal%')
    ->orWhere('uraian', 'LIKE', '%sisa%saldo%')
    ->orderBy('urutan_baris', 'asc')
    ->first();

if ($transaksiSaldoAwal && $transaksiSaldoAwal->tipe_transaksi == 'masuk') {
    $saldoAwalPeriode = $transaksiSaldoAwal->nominal;
    echo "✅ Saldo awal terdeteksi dari transaksi pertama:\n";
    echo "   \"" . $transaksiSaldoAwal->uraian . "\"\n";
    echo "   Rp " . number_format($saldoAwalPeriode, 2, ',', '.') . "\n\n";
} else {
    echo "⚠️  Tidak ada transaksi 'Saldo Awal' terdeteksi.\n";
    echo "   Masukkan saldo awal periode (sebelum " . $tanggalMulai->format('d M Y') . "):\n";
    echo "   (Ketik angka saja, tanpa titik/koma. Contoh: 33446)\n";
    echo "   Saldo Awal: Rp ";
    
    $input = trim(fgets(STDIN));
    $saldoAwalPeriode = (float) str_replace(['.', ',', ' '], '', $input);
    
    echo "\n✅ Saldo awal di-set: Rp " . number_format($saldoAwalPeriode, 2, ',', '.') . "\n\n";
}

// Konfirmasi
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "⚠️  KONFIRMASI\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "Script ini akan:\n";
echo "1. Recalculate saldo untuk semua hari\n";
echo "2. Update tabel saldo_harian_operasional\n";
echo "3. OVERWRITE data yang ada sekarang\n\n";
echo "Lanjutkan? (y/n): ";

$confirm = trim(fgets(STDIN));
if (strtolower($confirm) !== 'y') {
    echo "\n❌ Dibatalkan.\n\n";
    exit;
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔄 PROCESSING...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Step 3: Loop per hari, hitung saldo
$currentDate = $tanggalMulai->copy();
$saldoKemarin = $saldoAwalPeriode;
$totalHari = 0;

while ($currentDate <= $tanggalAkhir) {
    $tanggalKey = $currentDate->format('Y-m-d');
    
    echo "📅 " . $currentDate->format('d M Y') . ":\n";
    
    // Ambil transaksi hari ini (urut berdasarkan urutan_baris)
    $transaksiHariIni = RealisasiDanaOperasional::whereDate('tanggal_realisasi', $currentDate)
        ->orderBy('urutan_baris', 'asc')
        ->orderBy('id', 'asc')
        ->get();
    
    if ($transaksiHariIni->count() == 0) {
        echo "   ⚪ Tidak ada transaksi\n";
        echo "   Saldo tetap: Rp " . number_format($saldoKemarin, 2, ',', '.') . "\n\n";
        
        // Tetap buat record saldo harian (saldo sama seperti kemarin)
        SaldoHarianOperasional::updateOrCreate(
            ['tanggal' => $currentDate->format('Y-m-d')],
            [
                'saldo_awal' => $saldoKemarin,
                'dana_masuk' => 0,
                'total_realisasi' => 0,
                'saldo_akhir' => $saldoKemarin,
            ]
        );
        
        $currentDate->addDay();
        continue;
    }
    
    echo "   📝 " . $transaksiHariIni->count() . " transaksi\n";
    
    // Hitung total masuk dan keluar (EXCLUDE transaksi saldo awal di hari pertama)
    $totalMasuk = 0;
    $totalKeluar = 0;
    $skipFirst = ($currentDate->isSameDay($tanggalMulai) && $transaksiSaldoAwal);
    
    foreach ($transaksiHariIni as $index => $transaksi) {
        // Skip transaksi saldo awal (sudah dihitung sebagai saldo_awal)
        if ($skipFirst && $index == 0 && $transaksi->id == $transaksiSaldoAwal->id) {
            echo "   ⏭️  Skip: " . $transaksi->uraian . " (sudah di saldo_awal)\n";
            continue;
        }
        
        if ($transaksi->tipe_transaksi == 'masuk') {
            $totalMasuk += $transaksi->nominal;
        } else {
            $totalKeluar += $transaksi->nominal;
        }
    }
    
    // Hitung saldo akhir hari ini
    $saldoAkhir = $saldoKemarin + $totalMasuk - $totalKeluar;
    
    echo "   ✅ Total Masuk:  Rp " . number_format($totalMasuk, 2, ',', '.') . "\n";
    echo "   ⚠️  Total Keluar: Rp " . number_format($totalKeluar, 2, ',', '.') . "\n";
    echo "   🎯 Saldo Akhir:  Rp " . number_format($saldoAkhir, 2, ',', '.') . "\n";
    
    // Update atau create saldo harian
    // PENTING: dana_masuk di-set 0 karena tidak ada pencairan pengajuan
    // Semua transaksi masuk/keluar sudah tercatat di realisasi_dana_operasional
    // dana_masuk di saldo_harian_operasional HANYA untuk pencairan pengajuan_dana_operasional
    SaldoHarianOperasional::updateOrCreate(
        ['tanggal' => $currentDate->format('Y-m-d')],
        [
            'saldo_awal' => $saldoKemarin,
            'dana_masuk' => 0, // Set 0 karena tidak ada pencairan
            'dana_keluar' => 0, // Set 0 karena tidak ada dana keluar dari pengajuan
            'total_realisasi' => $totalKeluar,
            'saldo_akhir' => $saldoAkhir,
        ]
    );
    
    echo "   💾 Disimpan!\n\n";
    
    // Saldo akhir hari ini = saldo awal hari besok
    $saldoKemarin = $saldoAkhir;
    $totalHari++;
    
    $currentDate->addDay();
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ SELESAI!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "📊 SUMMARY:\n";
echo "   Total hari diproses: $totalHari hari\n";
echo "   Saldo Awal Periode:  Rp " . number_format($saldoAwalPeriode, 2, ',', '.') . "\n";
echo "   Saldo Akhir Periode: Rp " . number_format($saldoKemarin, 2, ',', '.') . "\n\n";

if ($saldoKemarin < 0) {
    echo "⚠️  PERHATIAN: Saldo akhir NEGATIF (defisit)!\n";
    echo "   Butuh tambahan dana: Rp " . number_format(abs($saldoKemarin), 2, ',', '.') . "\n\n";
}

echo "💡 Refresh halaman sekarang - saldo sudah benar!\n\n";
