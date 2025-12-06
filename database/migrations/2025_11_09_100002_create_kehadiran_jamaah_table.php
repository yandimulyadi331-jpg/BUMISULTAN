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
        if (!Schema::hasTable('kehadiran_jamaah')) {
            Schema::create('kehadiran_jamaah', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jamaah_id')->constrained('jamaah_majlis_taklim')->onDelete('cascade');
                $table->date('tanggal_kehadiran');
                $table->time('jam_masuk')->nullable();
                $table->time('jam_pulang')->nullable();
                $table->string('lokasi_masuk', 255)->nullable();
                $table->string('lokasi_pulang', 255)->nullable();
                $table->text('foto_masuk')->nullable();
                $table->text('foto_pulang')->nullable();
                $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
                $table->text('keterangan')->nullable();
                $table->string('device_id', 100)->nullable()->comment('ID mesin fingerprint');
                $table->enum('sumber_absen', ['fingerprint', 'manual', 'gps'])->default('fingerprint');
                $table->timestamps();
                
                // Index untuk performa
                $table->index(['jamaah_id', 'tanggal_kehadiran']);
                $table->index('tanggal_kehadiran');
                $table->unique(['jamaah_id', 'tanggal_kehadiran'], 'unique_jamaah_tanggal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('kehadiran_jamaah');
    }
};
