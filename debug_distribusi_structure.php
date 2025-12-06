<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== STRUKTUR TABEL distribusi_hadiah_yayasan_masar ===\n";
$columns = DB::select("DESCRIBE distribusi_hadiah_yayasan_masar");
foreach ($columns as $col) {
    echo "{$col->Field} ({$col->Type}) - Null: {$col->Null} - Key: {$col->Key} - Default: {$col->Default}\n";
}

echo "\n=== COBA CEK DATA ===\n";
$data = DB::table('distribusi_hadiah_yayasan_masar')->limit(3)->get();
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

echo "\n=== TABEL JAMAAH_MASAR PRIMARY KEY ===\n";
$jamaah_cols = DB::select("DESCRIBE jamaah_masar");
foreach ($jamaah_cols as $col) {
    if ($col->Key === 'PRI') {
        echo "Primary Key: {$col->Field} ({$col->Type})\n";
    }
}

echo "\n=== TABEL YAYASAN_MASAR PRIMARY KEY ===\n";
$yayasan_cols = DB::select("DESCRIBE yayasan_masar");
foreach ($yayasan_cols as $col) {
    if ($col->Key === 'PRI') {
        echo "Primary Key: {$col->Field} ({$col->Type})\n";
    }
}
