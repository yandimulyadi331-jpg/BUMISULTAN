<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CEK STRUKTUR TABEL kpi_crew ===\n";
$kpiColumns = DB::select("DESCRIBE kpi_crew");
foreach($kpiColumns as $col) {
    if ($col->Field == 'nik') {
        echo "Column: {$col->Field} | Type: {$col->Type} | Key: {$col->Key}\n";
    }
}

echo "\n=== CEK STRUKTUR TABEL karyawan ===\n";
$karyawanColumns = DB::select("DESCRIBE karyawan");
foreach($karyawanColumns as $col) {
    if ($col->Field == 'nik') {
        echo "Column: {$col->Field} | Type: {$col->Type} | Key: {$col->Key}\n";
    }
}

echo "\n=== CEK FOREIGN KEY CONSTRAINTS ===\n";
$constraints = DB::select("
    SELECT 
        CONSTRAINT_NAME,
        TABLE_NAME,
        COLUMN_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_NAME = 'kpi_crew' 
    AND REFERENCED_TABLE_NAME IS NOT NULL
");

foreach($constraints as $c) {
    echo "FK: {$c->CONSTRAINT_NAME}\n";
    echo "  From: {$c->TABLE_NAME}.{$c->COLUMN_NAME}\n";
    echo "  To: {$c->REFERENCED_TABLE_NAME}.{$c->REFERENCED_COLUMN_NAME}\n\n";
}

echo "=== TEST JOIN MANUAL ===\n";
$test = DB::select("
    SELECT 
        k.nik as kpi_nik,
        k.total_point,
        kar.nik as kar_nik,
        kar.nama_karyawan
    FROM kpi_crew k
    LEFT JOIN karyawan kar ON k.nik = kar.nik
    WHERE k.bulan = 11 AND k.tahun = 2025
    ORDER BY k.ranking
");

foreach($test as $t) {
    if ($t->kar_nik) {
        echo "✓ KPI NIK: {$t->kpi_nik} | Karyawan NIK: {$t->kar_nik} | Nama: {$t->nama_karyawan}\n";
    } else {
        echo "✗ KPI NIK: {$t->kpi_nik} | Karyawan: NOT FOUND\n";
    }
}

echo "\n=== CEK NIK COMPARISON ===\n";
$kpiNiks = DB::select("SELECT DISTINCT nik FROM kpi_crew WHERE bulan = 11 AND tahun = 2025");
$karNiks = DB::select("SELECT DISTINCT nik FROM karyawan");

echo "NIK di KPI:\n";
foreach($kpiNiks as $n) {
    echo "  '{$n->nik}' (length: " . strlen($n->nik) . ", type: " . gettype($n->nik) . ")\n";
}

echo "\nNIK di Karyawan:\n";
foreach($karNiks as $n) {
    echo "  '{$n->nik}' (length: " . strlen($n->nik) . ", type: " . gettype($n->nik) . ")\n";
}
