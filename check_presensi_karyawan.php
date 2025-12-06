<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Struktur Tabel Presensi (Karyawan) ===\n\n";

$columns = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE 
                       FROM INFORMATION_SCHEMA.COLUMNS 
                       WHERE TABLE_NAME='presensi'
                       ORDER BY ORDINAL_POSITION");

foreach ($columns as $col) {
    echo "- {$col->COLUMN_NAME}: {$col->COLUMN_TYPE} (NULL: {$col->IS_NULLABLE})\n";
}

echo "\n✅ SELESAI\n";
?>
