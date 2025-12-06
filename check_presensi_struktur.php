<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$cols = DB::select(
    "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, EXTRA
     FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_NAME = 'presensi_yayasan' 
     AND TABLE_SCHEMA = DATABASE()
     ORDER BY ORDINAL_POSITION"
);

echo "=== Struktur Tabel presensi_yayasan ===\n";
foreach ($cols as $c) {
    echo sprintf("%-20s %-15s NULL=%-3s KEY=%-3s %s\n", 
        $c->COLUMN_NAME, $c->COLUMN_TYPE, $c->IS_NULLABLE, $c->COLUMN_KEY, $c->EXTRA);
}

// Check apakah ada FK constraint
echo "\n=== FK Constraints ===\n";
$fks = DB::select(
    "SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
     FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
     WHERE TABLE_NAME = 'presensi_yayasan' 
     AND TABLE_SCHEMA = DATABASE()
     AND REFERENCED_TABLE_NAME IS NOT NULL"
);

foreach ($fks as $fk) {
    echo "{$fk->CONSTRAINT_NAME}: {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}({$fk->REFERENCED_COLUMN_NAME})\n";
}

// Check yayasan punya kode_jam_kerja
echo "\n=== Sample YayasanMasar Data ===\n";
$yayasan = \App\Models\YayasanMasar::where('status_aktif', 1)->first(['kode_yayasan', 'nama', 'kode_dept', 'kode_jam_kerja']);
if ($yayasan) {
    echo "kode_yayasan: {$yayasan->kode_yayasan}\n";
    echo "nama: {$yayasan->nama}\n";
    echo "kode_jam_kerja: {$yayasan->kode_jam_kerja}\n";
}
?>
