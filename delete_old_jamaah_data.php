<?php
/**
 * Script untuk menghapus data Jamaah lama yang tidak digunakan
 * Khusus untuk: TESTYasdfg
 */

require 'vendor/autoload.php';

use App\Models\JamaahMajlisTaklim;
use App\Models\KehadiranJamaah;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Cari jamaah dengan nama TESTYasdfg
    $jamaah = JamaahMajlisTaklim::where('nama_jamaah', 'TESTYasdfg')->first();
    
    if ($jamaah) {
        echo "Ditemukan data: " . $jamaah->nama_jamaah . " (ID: " . $jamaah->id . ")\n";
        
        DB::beginTransaction();
        
        // Hapus kehadiran terkait
        KehadiranJamaah::where('jamaah_id', $jamaah->id)->delete();
        echo "✓ Kehadiran dihapus\n";
        
        // Hapus distribusi hadiah terkait (cek tabel existence)
        try {
            DB::table('hadiah_majlis_taklim_jamaah')
                ->where('jamaah_id', $jamaah->id)
                ->delete();
            echo "✓ Distribusi hadiah dihapus\n";
        } catch (\Exception $e) {
            echo "ℹ️ Tabel distribusi tidak perlu dihapus\n";
        }
        
        // Hapus jamaah
        $jamaah->delete();
        echo "✓ Jamaah dihapus\n";
        
        DB::commit();
        
        echo "\n✅ Data lama TESTYasdfg berhasil dihapus!\n";
    } else {
        echo "⚠️ Data TESTYasdfg tidak ditemukan di database\n";
    }
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
