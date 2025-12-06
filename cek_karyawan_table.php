<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK STRUKTUR TABEL KARYAWAN ===\n\n";

$columns = DB::select('SHOW COLUMNS FROM karyawan');

foreach ($columns as $col) {
    echo "{$col->Field} - {$col->Type}\n";
}

echo "\n=== SAMPLE DATA KARYAWAN ===\n\n";

$sample = DB::table('karyawan')->first();
if ($sample) {
    foreach ($sample as $key => $value) {
        echo "{$key}: " . (is_string($value) ? substr($value, 0, 50) : $value) . "\n";
    }
}
