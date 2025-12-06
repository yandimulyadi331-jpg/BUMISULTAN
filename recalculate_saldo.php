<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PengajuanDanaOperasional;

echo "Recalculating saldo harian...\n";

$pengajuans = PengajuanDanaOperasional::all();

foreach ($pengajuans as $pengajuan) {
    echo "Processing: {$pengajuan->nomor_pengajuan}\n";
    $pengajuan->updateSaldoHarian();
}

echo "\n✅ All saldo harian updated!\n";
