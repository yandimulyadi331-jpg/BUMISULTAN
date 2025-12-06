<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 CEK DATA NOVEMBER 2025\n";
echo "========================================\n\n";

// Cek saldo harian November
$saldoNov = DB::table('saldo_harian_operasional')
    ->whereYear('tanggal', 2025)
    ->whereMonth('tanggal', 11)
    ->get();

echo "📊 Saldo Harian November: " . $saldoNov->count() . " baris\n";
foreach ($saldoNov as $s) {
    $cols = get_object_vars($s);
    echo "  • {$s->tanggal} | Saldo: " . ($s->saldo_akhir ?? 'N/A') . "\n";
}

// Cek pengajuan November
$pengajuanNov = DB::table('pengajuan_dana_operasional')
    ->whereYear('tanggal_pengajuan', 2025)
    ->whereMonth('tanggal_pengajuan', 11)
    ->get();

echo "\n📋 Pengajuan November: " . $pengajuanNov->count() . " baris\n";
foreach ($pengajuanNov as $p) {
    echo "  • {$p->tanggal_pengajuan} | Status: {$p->status} | Cair: {$p->nominal_cair}\n";
}

// Cek realisasi November
$realisasiNov = DB::table('realisasi_dana_operasional')
    ->whereYear('tanggal_realisasi', 2025)
    ->whereMonth('tanggal_realisasi', 11)
    ->get();

echo "\n💰 Realisasi November: " . $realisasiNov->count() . " baris\n";
foreach ($realisasiNov as $r) {
    echo "  • {$r->tanggal_realisasi} | {$r->uraian} | {$r->nominal}\n";
}

echo "\n🗑️  HAPUS SEMUA DATA NOVEMBER 2025\n";
echo "========================================\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

$deletedSaldo = DB::table('saldo_harian_operasional')
    ->whereYear('tanggal', 2025)
    ->whereMonth('tanggal', 11)
    ->delete();

$deletedPengajuan = DB::table('pengajuan_dana_operasional')
    ->whereYear('tanggal_pengajuan', 2025)
    ->whereMonth('tanggal_pengajuan', 11)
    ->delete();

$deletedRealisasi = DB::table('realisasi_dana_operasional')
    ->whereYear('tanggal_realisasi', 2025)
    ->whereMonth('tanggal_realisasi', 11)
    ->delete();

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "✅ Dihapus:\n";
echo "  • Saldo Harian: $deletedSaldo baris\n";
echo "  • Pengajuan: $deletedPengajuan baris\n";
echo "  • Realisasi: $deletedRealisasi baris\n";

echo "\n✅ Selesai! Data November sudah bersih.\n";
