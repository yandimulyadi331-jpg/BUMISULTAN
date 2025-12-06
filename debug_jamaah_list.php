<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JamaahMasar;

echo "=== CEK DATA JAMAAH_MASAR YANG AKAN DITAMPILKAN ===\n\n";

$jamaah_list = JamaahMasar::where('status_aktif', 'aktif')
    ->orderBy('nama_jamaah')
    ->get();

echo "Total jamaah aktif: " . $jamaah_list->count() . "\n\n";

foreach ($jamaah_list as $jamaah) {
    echo "ID: {$jamaah->id}\n";
    echo "Nama: {$jamaah->nama_jamaah}\n";
    echo "Status: {$jamaah->status_aktif}\n";
    echo "---\n";
}

echo "\n=== CEK TIPE DATA ===\n";
if ($jamaah_list->count() > 0) {
    $first = $jamaah_list->first();
    echo "Tipe object: " . get_class($first) . "\n";
    echo "Has id?: " . (property_exists($first, 'id') ? 'YES' : 'NO') . "\n";
    echo "Has nama_jamaah?: " . (property_exists($first, 'nama_jamaah') ? 'YES' : 'NO') . "\n";
}
