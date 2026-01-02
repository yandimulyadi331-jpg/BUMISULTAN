<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('presensi_yayasan')) {
            Schema::table('presensi_yayasan', function (Blueprint $table) {
                // Cek apakah kolom belum ada sebelum ditambahkan
                if (!Schema::hasColumn('presensi_yayasan', 'attendance_method')) {
                    $table->enum('attendance_method', ['fingerprint', 'qr_code', 'manual'])
                        ->default('fingerprint')
                        ->after('status');
                }
                
                if (!Schema::hasColumn('presensi_yayasan', 'qr_event_id')) {
                    $table->unsignedBigInteger('qr_event_id')->nullable()->after('attendance_method');
                }
                
                if (!Schema::hasColumn('presensi_yayasan', 'device_id')) {
                    $table->string('device_id', 200)->nullable()->after('qr_event_id');
                }
            });

            // Tambah index setelah kolom dibuat
            Schema::table('presensi_yayasan', function (Blueprint $table) {
                if (!Schema::hasColumn('presensi_yayasan', 'attendance_method')) {
                    $table->index('attendance_method');
                }
                
                if (!Schema::hasColumn('presensi_yayasan', 'qr_event_id')) {
                    $table->index('qr_event_id');
                }
            });

            // Tambah foreign key jika tabel qr_attendance_events sudah ada
            if (Schema::hasTable('qr_attendance_events')) {
                Schema::table('presensi_yayasan', function (Blueprint $table) {
                    // Cek apakah foreign key belum ada
                    $foreignKeys = Schema::getConnection()
                        ->getDoctrineSchemaManager()
                        ->listTableForeignKeys('presensi_yayasan');
                    
                    $hasForeignKey = false;
                    foreach ($foreignKeys as $foreignKey) {
                        if (in_array('qr_event_id', $foreignKey->getColumns())) {
                            $hasForeignKey = true;
                            break;
                        }
                    }

                    if (!$hasForeignKey && Schema::hasColumn('presensi_yayasan', 'qr_event_id')) {
                        $table->foreign('qr_event_id')
                            ->references('id')
                            ->on('qr_attendance_events')
                            ->nullOnDelete();
                    }
                });
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
                $table->dropForeign(['qr_event_id']);
                
                // Drop kolom
                $table->dropColumn(['attendance_method', 'qr_event_id', 'device_id']);
            });
        }
    }
};
