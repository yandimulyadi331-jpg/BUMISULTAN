<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK STRUKTUR KOLOM jenis_laporan ===\n\n";

$columns = DB::select("SHOW COLUMNS FROM laporan_keuangan WHERE Field = 'jenis_laporan'");

foreach ($columns as $col) {
    echo "Field: {$col->Field}\n";
    echo "Type: {$col->Type}\n";
    echo "Null: {$col->Null}\n";
    echo "Default: {$col->Default}\n";
}

echo "\n=== ENUM VALUES ===\n";
// Extract enum values
$type = $columns[0]->Type;
preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
if (isset($matches[1])) {
    $enum = explode("','", $matches[1]);
    echo "Allowed values:\n";
    foreach ($enum as $val) {
        echo "  - {$val} (" . strlen($val) . " chars)\n";
    }
}

echo "\n=== TEST VALUES ===\n";
echo "LAPORAN_BULANAN: " . strlen('LAPORAN_BULANAN') . " chars\n";
echo "LAPORAN_MINGGUAN: " . strlen('LAPORAN_MINGGUAN') . " chars\n";
echo "LAPORAN_TAHUNAN: " . strlen('LAPORAN_TAHUNAN') . " chars\n";
echo "LAPORAN_CUSTOM: " . strlen('LAPORAN_CUSTOM') . " chars\n";
