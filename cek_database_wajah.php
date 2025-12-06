<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Facerecognition;

echo "\n=== CEK DATABASE KARYAWAN_WAJAH ===\n\n";

$data = Facerecognition::where('nik', '251100001')->get();

echo "Total data NIK 251100001: " . $data->count() . "\n\n";

foreach ($data as $d) {
    echo "ID: {$d->id}\n";
    echo "NIK: {$d->nik}\n";
    echo "Wajah (value di DB): {$d->wajah}\n";
    
    // Cek file di berbagai lokasi
    $locations = [
        'public/facerecognition/' . $d->wajah,
        'public/facerecognition/' . $d->nik . '_' . $d->wajah,
        'public/storage/facerecognition/' . $d->wajah,
        'public/storage/facerecognition/' . $d->nik . '_' . $d->wajah,
        'storage/app/public/facerecognition/' . $d->wajah,
        'storage/app/public/facerecognition/' . $d->nik . '_' . $d->wajah,
    ];
    
    echo "Cek keberadaan file:\n";
    foreach ($locations as $loc) {
        $exists = file_exists($loc);
        $status = $exists ? "✅ ADA" : "❌ TIDAK ADA";
        echo "  $status: $loc\n";
    }
    echo "\n";
}

echo "\n=== CEK FILE DI FOLDER facerecognition ===\n\n";
$files = glob('public/facerecognition/*.{jpg,png}', GLOB_BRACE);
echo "Total file: " . count($files) . "\n";
foreach ($files as $file) {
    echo "  - " . basename($file) . "\n";
}
