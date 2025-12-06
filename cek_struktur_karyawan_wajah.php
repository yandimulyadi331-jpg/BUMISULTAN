<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== STRUKTUR TABEL karyawan_wajah ===" . PHP_EOL . PHP_EOL;
$columns = DB::select('DESCRIBE karyawan_wajah');
foreach($columns as $col) {
    echo "Field: " . $col->Field . PHP_EOL;
    echo "  Type: " . $col->Type . PHP_EOL;
    echo "  Null: " . $col->Null . PHP_EOL;
    echo "  Default: " . ($col->Default ?: 'NULL') . PHP_EOL;
    echo "  Key: " . $col->Key . PHP_EOL;
    echo PHP_EOL;
}
