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
        if (!Schema::hasTable('kehadiran_jamaah_masar')) {
            Schema::create('kehadiran_jamaah_masar', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jamaah_id')->constrained('jamaah_masar')->onDelete('cascade');
                $table->date('tanggal_kehadiran');
                $table->time('jam_kehadiran')->nullable();
                $table->string('lokasi', 100)->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index('jamaah_id');
                $table->index('tanggal_kehadiran');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('kehadiran_jamaah_masar');
    }
};
