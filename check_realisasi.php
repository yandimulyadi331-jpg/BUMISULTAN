<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;

echo "Checking all realisasi data...\n\n";

$realisasis = RealisasiDanaOperasional::orderBy('id')->get();

foreach ($realisasis as $r) {
    echo sprintf(
        "ID: %d | %s | Rp %s | Type: %s\n",
        $r->id,
        $r->uraian,
        number_format($r->nominal, 0, ',', '.'),
        $r->tipe_transaksi ?? 'NULL'
    );
}

echo "\n--- Summary ---\n";
echo "Total Masuk: Rp " . number_format(RealisasiDanaOperasional::where('tipe_transaksi', 'masuk')->sum('nominal'), 0, ',', '.') . "\n";
echo "Total Keluar: Rp " . number_format(RealisasiDanaOperasional::where('tipe_transaksi', 'keluar')->sum('nominal'), 0, ',', '.') . "\n";
