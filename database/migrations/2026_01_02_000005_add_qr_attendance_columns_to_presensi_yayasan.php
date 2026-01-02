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
        if (Schema::hasTable('presensi_yayasan')) {
            Schema::table('presensi_yayasan', function (Blueprint $table) {
                // Tambah kolom jika belum ada
                if (!Schema::hasColumn('presensi_yayasan', 'attendance_method')) {
                    $table->enum('attendance_method', ['fingerprint', 'qr_code', 'manual'])
                        ->default('fingerprint')
                        ->after('status');
                }
                
                if (!Schema::hasColumn('presensi_yayasan', 'qr_event_id')) {
                    $table->unsignedBigInteger('qr_event_id')->nullable()->after('attendance_method');
                    $table->index('qr_event_id');
                }
                
                if (!Schema::hasColumn('presensi_yayasan', 'device_id')) {
                    $table->string('device_id', 200)->nullable()->after('qr_event_id');
                }
            });

            // Tambah foreign key menggunakan raw SQL untuk avoid doctrine
            if (Schema::hasTable('qr_attendance_events')) {
                try {
                    DB::statement('
                        ALTER TABLE presensi_yayasan 
                        ADD CONSTRAINT fk_presensi_qr_event 
                        FOREIGN KEY (qr_event_id) 
                        REFERENCES qr_attendance_events(id) 
                        ON DELETE SET NULL
                    ');
                } catch (\Exception $e) {
                    // Foreign key mungkin sudah ada, skip error
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('presensi_yayasan')) {
            Schema::table('presensi_yayasan', function (Blueprint $table) {
                // Drop foreign key terlebih dahulu
                try {
                    DB::statement('ALTER TABLE presensi_yayasan DROP FOREIGN KEY fk_presensi_qr_event');
                } catch (\Exception $e) {
                    // Skip jika tidak ada
                }
                
                // Drop kolom
                if (Schema::hasColumn('presensi_yayasan', 'device_id')) {
                    $table->dropColumn('device_id');
                }
                
                if (Schema::hasColumn('presensi_yayasan', 'qr_event_id')) {
                    $table->dropColumn('qr_event_id');
                }
                
                if (Schema::hasColumn('presensi_yayasan', 'attendance_method')) {
                    $table->dropColumn('attendance_method');
                }
            });
        }
    }
};
