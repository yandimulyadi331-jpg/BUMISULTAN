<?php
// Script untuk fix status_aktif yang NULL menjadi 1 di yayasan_masar

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\YayasanMasar;
use Illuminate\Support\Facades\DB;

echo "=== FIX: Update status_aktif NULL → 1 ===\n\n";

try {
    // Update semua status_aktif yang NULL atau 0 menjadi 1
    $updated = YayasanMasar::where('status_aktif', '=', DB::raw('NULL'))
        ->orWhere('status_aktif', 0)
        ->update(['status_aktif' => 1]);
    
    echo "✅ Updated: $updated records\n\n";
    
    // Verify hasil
    echo "=== Verifikasi Hasil ===\n";
    $all = YayasanMasar::orderBy('id')->get(['id', 'kode_yayasan', 'nama', 'status_aktif']);
    
    foreach ($all as $row) {
        $status = $row->status_aktif ? "✅" : "⚠️";
        echo "$status ID: {$row->id} | Kode: {$row->kode_yayasan} | Nama: {$row->nama} | Status: {$row->status_aktif}\n";
    }
    
    echo "\n✅ FIX COMPLETE\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
