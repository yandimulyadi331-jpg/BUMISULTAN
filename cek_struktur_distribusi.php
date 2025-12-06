<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Kolom di tabel distribusi_hadiah ===\n";
$columns = Schema::getColumnListing('distribusi_hadiah');
print_r($columns);

echo "\n=== Detail Kolom ===\n";
$columnDetails = DB::select("DESCRIBE distribusi_hadiah");
foreach ($columnDetails as $column) {
    echo "{$column->Field} - {$column->Type} - Null: {$column->Null}\n";
}
