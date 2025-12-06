<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tukang;
use App\Models\PinjamanTukang;

echo "============================================\n";
echo "TAMBAH DATA PINJAMAN UNTUK DEMO\n";
echo "============================================\n\n";

$tukang = Tukang::where('kode_tukang', 'TK001')->first();

if (!$tukang) {
    echo "❌ Tukang TK001 tidak ditemukan!\n";
    exit;
}

// Hapus pinjaman lama jika ada
PinjamanTukang::where('tukang_id', $tukang->id)->delete();

// Buat pinjaman baru
$pinjaman = PinjamanTukang::create([
    'tukang_id' => $tukang->id,
    'tanggal_pinjaman' => '2025-11-01',
    'jumlah_pinjaman' => 500000,
    'cicilan_per_minggu' => 100000,
    'sisa_pinjaman' => 500000,
    'status' => 'aktif',
    'keterangan' => 'Pinjaman untuk kebutuhan keluarga'
]);

echo "✅ Pinjaman berhasil dibuat!\n\n";
echo "📋 Detail Pinjaman:\n";
echo "   Nama: {$tukang->nama_tukang} ({$tukang->kode_tukang})\n";
echo "   Jumlah: Rp " . number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') . "\n";
echo "   Cicilan/Minggu: Rp " . number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') . "\n";
echo "   Sisa: Rp " . number_format($pinjaman->sisa_pinjaman, 0, ',', '.') . "\n";
echo "   Status: {$pinjaman->status}\n";
echo "   Auto Potong: " . ($tukang->auto_potong_pinjaman ? 'YA ✅' : 'TIDAK ❌') . "\n\n";

echo "💡 Perhitungan Gaji Minggu Ini:\n";
echo "   Upah (6 hari x Rp 150.000): Rp 900.000\n";
echo "   Cicilan dipotong: Rp 100.000\n";
echo "   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "   Gaji Bersih: Rp 800.000\n\n";

echo "🌐 Refresh dashboard untuk melihat perubahan!\n";
