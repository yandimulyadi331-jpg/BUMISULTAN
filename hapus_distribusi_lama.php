<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DistribusiHadiahMasar;
use Illuminate\Support\Facades\DB;

echo "=== SEBELUM DELETE ===\n";
echo "Total Distribusi: " . DistribusiHadiahMasar::count() . "\n";
$distribusi = DistribusiHadiahMasar::first();
if ($distribusi) {
    echo "Data pertama: ID = " . $distribusi->id . ", jamaah_id = " . $distribusi->jamaah_id . "\n";
}

echo "\n=== HAPUS SEMUA DATA LAMA ===\n";
DB::table('distribusi_hadiah_masar')->truncate();
echo "✓ Semua data distribusi telah dihapus\n";

echo "\n=== SETELAH DELETE ===\n";
echo "Total Distribusi: " . DistribusiHadiahMasar::count() . "\n";
