<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // First drop jadwal_piket_karyawans to release the foreign key constraint
            DB::statement('DROP TABLE IF EXISTS `jadwal_piket_karyawans`');
            
            // Drop foreign key from master_perawatan if exists
            $fkeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME='master_perawatan' AND COLUMN_NAME='jadwal_piket_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
            if (!empty($fkeys)) {
                foreach ($fkeys as $fk) {
                    DB::statement("ALTER TABLE `master_perawatan` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                }
            }
            
            // Drop foreign key from perawatan_log if exists
            $fkeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME='perawatan_log' AND COLUMN_NAME='jadwal_piket_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
            if (!empty($fkeys)) {
                foreach ($fkeys as $fk) {
                    DB::statement("ALTER TABLE `perawatan_log` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                }
            }
            
            // Drop the old jadwal_pikets table
            DB::statement('DROP TABLE IF EXISTS `jadwal_pikets`');
            
            // Remove jadwal_piket_id from master_perawatan
            $masterColumns = DB::select("SHOW COLUMNS FROM `master_perawatan` WHERE Field = 'jadwal_piket_id'");
            if (!empty($masterColumns)) {
                DB::statement('ALTER TABLE `master_perawatan` DROP COLUMN `jadwal_piket_id`');
            }
            
            // Remove jadwal_piket_id from perawatan_log
            $logColumns = DB::select("SHOW COLUMNS FROM `perawatan_log` WHERE Field = 'jadwal_piket_id'");
            if (!empty($logColumns)) {
                DB::statement('ALTER TABLE `perawatan_log` DROP COLUMN `jadwal_piket_id`');
            }
            
            // Add kode_jam_kerja to master_perawatan if not exists
            $columns = DB::select("SHOW COLUMNS FROM `master_perawatan` WHERE Field = 'kode_jam_kerja'");
            if (empty($columns)) {
                DB::statement('ALTER TABLE `master_perawatan` ADD COLUMN `kode_jam_kerja` CHAR(4) NULL AFTER `kategori`');
                DB::statement('ALTER TABLE `master_perawatan` ADD FOREIGN KEY (`kode_jam_kerja`) REFERENCES `presensi_jamkerja`(`kode_jam_kerja`) ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            
            // Add kode_jam_kerja to perawatan_log if not exists
            $columns = DB::select("SHOW COLUMNS FROM `perawatan_log` WHERE Field = 'kode_jam_kerja'");
            if (empty($columns)) {
                DB::statement('ALTER TABLE `perawatan_log` ADD COLUMN `kode_jam_kerja` CHAR(4) NULL AFTER `jam_ceklis`');
                DB::statement('ALTER TABLE `perawatan_log` ADD FOREIGN KEY (`kode_jam_kerja`) REFERENCES `presensi_jamkerja`(`kode_jam_kerja`) ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            
            // Create the correct jadwal_piket_karyawans table
            DB::statement('CREATE TABLE IF NOT EXISTS `jadwal_piket_karyawans` (
                `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `nik` VARCHAR(255) NOT NULL,
                `kode_jam_kerja` CHAR(4) NOT NULL,
                `mulai_berlaku` DATE NOT NULL,
                `berakhir_berlaku` DATE NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL,
                UNIQUE KEY `unique_nik_jam_berlaku` (`nik`, `kode_jam_kerja`, `mulai_berlaku`),
                KEY `idx_nik_berlaku` (`nik`, `mulai_berlaku`),
                FOREIGN KEY (`nik`) REFERENCES `karyawan`(`nik`) ON DELETE CASCADE,
                FOREIGN KEY (`kode_jam_kerja`) REFERENCES `presensi_jamkerja`(`kode_jam_kerja`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way correction, no need to reverse
    }
};
