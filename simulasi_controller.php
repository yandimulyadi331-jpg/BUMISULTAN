<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;
use Carbon\Carbon;

echo "=== SIMULASI QUERY CONTROLLER ===\n\n";

$bulan = date('Y-m');
$tanggalAwal = Carbon::parse($bulan . '-01')->startOfMonth();
$tanggalAkhir = Carbon::parse($bulan . '-01')->endOfMonth();

echo "Filter Bulan: $bulan\n";
echo "Range: {$tanggalAwal->format('Y-m-d')} s/d {$tanggalAkhir->format('Y-m-d')}\n\n";

// Get saldo harian
echo "--- SALDO HARIAN ---\n";
$riwayatSaldo = SaldoHarianOperasional::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
    ->orderBy('tanggal', 'asc')
    ->get();

echo "Jumlah: " . $riwayatSaldo->count() . " hari\n";
foreach ($riwayatSaldo as $s) {
    echo "- {$s->tanggal}: Saldo Akhir = Rp " . number_format($s->saldo_akhir, 0, ',', '.') . "\n";
}

// Get transaksi
echo "\n--- TRANSAKSI PER TANGGAL ---\n";
$realisasiPerTanggal = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
    ->orderBy('tanggal_realisasi', 'asc')
    ->orderBy('urutan_baris', 'asc')
    ->orderBy('id', 'asc')
    ->get()
    ->groupBy(function($item) {
        return $item->tanggal_realisasi->format('Y-m-d');
    });

echo "Jumlah tanggal: " . $realisasiPerTanggal->count() . "\n";
foreach ($realisasiPerTanggal as $tanggal => $transaksi) {
    echo "\n$tanggal ({$transaksi->count()} transaksi):\n";
    foreach ($transaksi as $t) {
        echo "  - {$t->nomor_transaksi}: {$t->keterangan} | {$t->tipe_transaksi} | Rp " . number_format($t->nominal, 0, ',', '.') . "\n";
    }
}

echo "\n✓ Jika ada data di sini, berarti controller sudah benar kirim data ke view.\n";
echo "✓ Pastikan view index-new.blade.php yang diakses, bukan index.blade.php lama.\n";
