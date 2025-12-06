<?php
// Fix Foreign Key constraint - ubah dari id ke kode_yayasan

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== FIX: Foreign Key Constraint ===\n\n";

try {
    // 1. Drop FK lama jika ada
    echo "1️⃣  Dropping FK lama (jika ada)...\n";
    try {
        DB::statement("ALTER TABLE `distribusi_hadiah` DROP FOREIGN KEY `distribusi_hadiah_jamaah_id_foreign`");
        echo "   ✅ FK lama dropped\n\n";
    } catch (\Exception $e) {
        echo "   ℹ️  FK tidak ditemukan (OK, lanjut)\n\n";
    }

    // 2. Cek kolom jamaah_id type
    echo "2️⃣  Checking jamaah_id column type...\n";
    $columns = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
                           WHERE TABLE_NAME='distribusi_hadiah' AND COLUMN_NAME='jamaah_id'");
    
    if ($columns) {
        echo "   Current: {$columns[0]->COLUMN_TYPE}\n";
        
        // Jika INT, ubah ke VARCHAR
        if (strpos($columns[0]->COLUMN_TYPE, 'int') !== false) {
            echo "   Changing to VARCHAR(20)...\n";
            DB::statement("ALTER TABLE `distribusi_hadiah` MODIFY `jamaah_id` VARCHAR(20) NULL");
            echo "   ✅ Column updated to VARCHAR(20)\n";
        }
    }
    echo "\n";

    // 3. Add FK baru yang referensi kode_yayasan
    echo "3️⃣  Adding FK constraint ke yayasan_masar(kode_yayasan)...\n";
    DB::statement("ALTER TABLE `distribusi_hadiah` 
                   ADD CONSTRAINT `distribusi_hadiah_jamaah_id_foreign` 
                   FOREIGN KEY (`jamaah_id`) REFERENCES `yayasan_masar`(`kode_yayasan`) 
                   ON DELETE CASCADE");
    echo "   ✅ FK constraint added successfully\n\n";

    // 4. Verify
    echo "4️⃣  Verifying FK constraint...\n";
    $constraints = DB::select("SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                               FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                               WHERE TABLE_NAME='distribusi_hadiah' AND COLUMN_NAME='jamaah_id'");
    
    if ($constraints) {
        $c = $constraints[0];
        echo "   ✅ FK Verified:\n";
        echo "      - Constraint: {$c->CONSTRAINT_NAME}\n";
        echo "      - Column: {$c->TABLE_NAME}.{$c->COLUMN_NAME}\n";
        echo "      - References: {$c->REFERENCED_TABLE_NAME}({$c->REFERENCED_COLUMN_NAME})\n";
    }

    echo "\n✅ FK CONSTRAINT FIX COMPLETE\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
