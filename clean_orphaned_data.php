<?php
// Clean orphaned data sebelum add FK

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CLEAN: Orphaned Data ===\n\n";

try {
    // 1. Check data yang tidak ada di yayasan_masar
    echo "1️⃣  Checking orphaned data...\n";
    $orphaned = DB::select("SELECT DISTINCT jamaah_id FROM distribusi_hadiah 
                            WHERE jamaah_id IS NOT NULL 
                            AND jamaah_id NOT IN (SELECT kode_yayasan FROM yayasan_masar)");
    
    if ($orphaned) {
        echo "   Found " . count($orphaned) . " orphaned jamaah_id:\n";
        foreach ($orphaned as $row) {
            echo "      - {$row->jamaah_id}\n";
        }
        
        // 2. Set ke NULL
        echo "\n2️⃣  Setting orphaned data to NULL...\n";
        $orphanedIds = array_map(function($row) { return $row->jamaah_id; }, $orphaned);
        
        $cleaned = DB::table('distribusi_hadiah')
            ->whereIn('jamaah_id', $orphanedIds)
            ->update(['jamaah_id' => null]);
        
        echo "   ✅ Cleaned: $cleaned records\n";
    } else {
        echo "   ✅ No orphaned data found\n";
    }
    
    // 3. Show current data
    echo "\n3️⃣  Current distribusi_hadiah data:\n";
    $data = DB::select("SELECT id, jamaah_id, hadiah_id, penerima FROM distribusi_hadiah LIMIT 10");
    foreach ($data as $row) {
        echo "   - ID: {$row->id} | jamaah_id: {$row->jamaah_id} | hadiah_id: {$row->hadiah_id} | penerima: {$row->penerima}\n";
    }

    echo "\n✅ CLEANUP COMPLETE\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
