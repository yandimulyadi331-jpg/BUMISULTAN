<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📊 TRANSAKSI DI TANGGAL 1 JANUARI 2025\n";
echo "========================================\n\n";

$transaksi = DB::table('realisasi_dana_operasional')
    ->whereDate('tanggal_realisasi', '2025-01-01')
    ->get();

echo "Total: " . $transaksi->count() . " transaksi\n\n";

foreach ($transaksi as $t) {
    echo "ID {$t->id}: {$t->uraian} - Rp " . number_format($t->nominal, 0, ',', '.') . " ({$t->tipe_transaksi})\n";
}
