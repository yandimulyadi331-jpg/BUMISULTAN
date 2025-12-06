<?php
// Script untuk debug error "No query results for model [AppModelsYayasanMasar] 1"

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\YayasanMasar;

echo "=== DEBUG: YayasanMasar Data ===\n\n";

// 1. Cek total data di database
$total = YayasanMasar::count();
echo "Total YayasanMasar: $total\n\n";

// 2. Cek apakah ID 1 ada
$id1 = YayasanMasar::find(1);
if ($id1) {
    echo "✅ ID 1 DITEMUKAN:\n";
    echo "   - ID: {$id1->id}\n";
    echo "   - Kode: {$id1->kode_yayasan}\n";
    echo "   - Nama: {$id1->nama}\n";
    echo "   - Status: {$id1->status_aktif}\n\n";
} else {
    echo "❌ ID 1 TIDAK DITEMUKAN\n\n";
}

// 3. List semua data dengan ID > 0
echo "Semua YayasanMasar dengan ID:\n";
$all = YayasanMasar::where('status_aktif', 1)
    ->orderBy('id')
    ->get(['id', 'kode_yayasan', 'nama', 'status_aktif']);

foreach ($all as $row) {
    echo "   - ID: {$row->id} | Kode: {$row->kode_yayasan} | Nama: {$row->nama} | Status: {$row->status_aktif}\n";
}

echo "\n";

// 4. Cek jika ada ID spesifik yang tidak ada
$testIds = [1, 2, 3, 4, 5, 251200001, 251200009];
echo "\n=== Test Specific IDs ===\n";
foreach ($testIds as $testId) {
    $data = YayasanMasar::find($testId);
    $status = $data ? "✅" : "❌";
    echo "$status ID $testId: ";
    if ($data) {
        echo "{$data->nama}\n";
    } else {
        echo "TIDAK ADA\n";
    }
}

// 5. Cek distribusi_hadiah untuk melihat jamaah_id apa yang direferensikan
echo "\n=== Check distribusi_hadiah table ===\n";
$distribusi = \App\Models\DistribusiHadiah::select('id', 'jamaah_id', 'penerima')
    ->get();

echo "Total distribusi_hadiah: " . $distribusi->count() . "\n";
if ($distribusi->count() > 0) {
    echo "\nSample data:\n";
    foreach ($distribusi->take(5) as $row) {
        echo "   - ID: {$row->id} | jamaah_id: {$row->jamaah_id} | Penerima: {$row->penerima}\n";
    }
}

echo "\n✅ DEBUG COMPLETE\n";
?>
