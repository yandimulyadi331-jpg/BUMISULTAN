<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\YayasanMasar;

echo "=== DATA YAYASAN MASAR DENGAN KOLOM NAMA ===\n";
$yayasan = YayasanMasar::where('status_aktif', 1)->get();
echo "Total aktif: " . $yayasan->count() . "\n\n";

foreach ($yayasan as $y) {
    echo "ID: {$y->id}\n";
    echo "Kode: {$y->kode_yayasan}\n";
    echo "Nama: {$y->nama}\n";
    echo "---\n";
}
