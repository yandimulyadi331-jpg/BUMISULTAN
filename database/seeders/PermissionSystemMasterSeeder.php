<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * MASTER SEEDER FOR COMPREHENSIVE PERMISSION SYSTEM
 * Runs all permission seeders in correct order
 */
class PermissionSystemMasterSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🔐 MENJALANKAN COMPREHENSIVE PERMISSION SYSTEM SEEDERS\n";
        echo str_repeat("=", 60) . "\n\n";

        // Batch 1: Financial, Vehicle, Inventory (18 groups)
        echo "\n📦 BATCH 1: Sistem Keuangan, Kendaraan, Inventaris\n";
        echo str_repeat("-", 60) . "\n";
        $this->call(ComprehensivePermissionSeederBatch1::class);

        // Batch 2: Facilities, Students, Religious Events (21 groups)
        echo "\n\n📦 BATCH 2: Fasilitas, Santri, Event Keagamaan\n";
        echo str_repeat("-", 60) . "\n";
        $this->call(ComprehensivePermissionSeederBatch2::class);

        // Batch 3: Maintenance, Quality, Admin, Documents (20 groups)
        echo "\n\n📦 BATCH 3: Perawatan, Kualitas, Administrasi, Dokumen\n";
        echo str_repeat("-", 60) . "\n";
        $this->call(ComprehensivePermissionSeederBatch3::class);

        echo "\n\n" . str_repeat("=", 60) . "\n";
        echo "✅ SEMUA PERMISSION SEEDERS BERHASIL DIJALANKAN!\n";
        echo str_repeat("=", 60) . "\n";
        echo "\n📊 RINGKASAN:\n";
        echo "   • Batch 1: 18 permission groups (Keuangan, Kendaraan, Inventaris)\n";
        echo "   • Batch 2: 21 permission groups (Fasilitas, Santri, Religiusitas)\n";
        echo "   • Batch 3: 20 permission groups (Perawatan, Kualitas, Admin)\n";
        echo "   • TOTAL: 59 permission groups dengan 300+ permissions\n";
        echo "   • Semua permissions sudah di-assign ke role 'super admin'\n";
        echo "\n🎯 LANGKAH BERIKUTNYA:\n";
        echo "   1. Kunjungi halaman Edit Role di Admin Panel\n";
        echo "   2. Pilih role yang ingin diatur permission-nya\n";
        echo "   3. Lihat semua permission groups yang tersedia di bawah\n";
        echo "   4. Centang permissions yang diinginkan\n";
        echo "   5. Klik 'Simpan' untuk menyimpan\n";
        echo "\n";
    }
}
