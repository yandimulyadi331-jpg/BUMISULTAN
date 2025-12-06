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
        Schema::create('absensi_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_santri_id')->constrained('jadwal_santri')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->date('tanggal_absensi');
            $table->time('waktu_absensi')->nullable();
            $table->enum('status_kehadiran', ['hadir', 'ijin', 'sakit', 'khidmat', 'absen'])->default('absen');
            $table->text('keterangan')->nullable();
            $table->string('dibuat_oleh')->nullable(); // user yang input absensi
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk performa
            $table->index(['jadwal_santri_id', 'tanggal_absensi']);
            $table->index(['santri_id', 'tanggal_absensi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_santri');
    }
};
