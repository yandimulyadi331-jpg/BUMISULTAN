<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SaldoHarianOperasional;

echo "🔍 CEK DANA MASUK DI SALDO HARIAN\n";
echo str_repeat("=", 60) . "\n\n";

$saldo = SaldoHarianOperasional::whereDate('tanggal', '2025-01-01')->first();

if ($saldo) {
    echo "📅 Tanggal: " . $saldo->tanggal->format('d M Y') . "\n";
    echo "💰 Saldo Awal: Rp " . number_format($saldo->saldo_awal, 2, ',', '.') . "\n";
    echo "📥 Dana Masuk: Rp " . number_format($saldo->dana_masuk, 2, ',', '.') . "\n";
    echo "📤 Dana Keluar: Rp " . number_format($saldo->dana_keluar, 2, ',', '.') . "\n";
    echo "💵 Saldo Akhir: Rp " . number_format($saldo->saldo_akhir, 2, ',', '.') . "\n\n";
    
    echo "❌ MASALAH DITEMUKAN!\n";
    echo "Dana Masuk Rp " . number_format($saldo->dana_masuk, 2, ',', '.') . " dari mana?\n";
    echo "Di Excel tidak ada pencairan dana sebesar itu!\n\n";
    
    echo "💡 SOLUSI:\n";
    echo "Dana Masuk seharusnya = 0 karena tidak ada pencairan.\n";
    echo "Semua dana masuk sudah tercatat di tabel realisasi_dana_operasional.\n";
} else {
    echo "❌ Data tidak ditemukan!\n";
}
