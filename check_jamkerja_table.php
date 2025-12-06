<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Struktur Tabel presensi_jamkerja ===\n";
$cols = \Illuminate\Support\Facades\DB::select(
    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_NAME='presensi_jamkerja' 
     AND TABLE_SCHEMA=DATABASE()"
);

foreach ($cols as $c) {
    echo $c->COLUMN_NAME . "\n";
}

echo "\n=== Sample Data presensi_jamkerja ===\n";
$data = \Illuminate\Support\Facades\DB::table('presensi_jamkerja')->get();
foreach ($data as $d) {
    echo "kode_jam_kerja: {$d->kode_jam_kerja}, nama: {$d->nama_jam_kerja}\n";
}
?>
