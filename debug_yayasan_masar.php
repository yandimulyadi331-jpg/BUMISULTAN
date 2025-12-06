<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\YayasanMasar;
use Illuminate\Support\Facades\DB;

echo "=== CEK KOLOM YAYASAN_MASAR ===\n";
$columns = DB::select("DESCRIBE yayasan_masar");
foreach ($columns as $col) {
    echo "{$col->Field} ({$col->Type}) - Key: {$col->Key}\n";
}

echo "\n=== DATA YAYASAN MASAR ===\n";
$yayasan = YayasanMasar::all();
echo "Total: " . $yayasan->count() . "\n\n";

foreach ($yayasan->take(10) as $y) {
    echo "ID: {$y->id}\n";
    echo "Kode: {$y->kode_yayasan}\n";
    echo "Nama Jamaah: {$y->nama_jamaah}\n";
    echo "Status Aktif: {$y->status_aktif}\n";
    echo "---\n";
}

echo "\n=== YANG AKTIF ===\n";
$active = YayasanMasar::where('status_aktif', 1)->get();
echo "Jumlah aktif: " . $active->count() . "\n\n";
foreach ($active->take(5) as $y) {
    echo "ID: {$y->id}, Nama: {$y->nama_jamaah}\n";
}
