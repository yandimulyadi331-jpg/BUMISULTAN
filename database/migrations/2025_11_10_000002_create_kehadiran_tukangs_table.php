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
        Schema::create('kehadiran_tukangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tukang_id')->constrained('tukangs')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'tidak_hadir', 'setengah_hari'])->default('tidak_hadir');
            $table->boolean('lembur')->default(false);
            $table->decimal('jam_kerja', 5, 2)->default(8.00); // 8 jam full, 4 jam setengah hari
            $table->decimal('upah_harian', 12, 2)->default(0); // Upah hari ini
            $table->decimal('upah_lembur', 12, 2)->default(0); // Upah lembur
            $table->decimal('total_upah', 12, 2)->default(0); // Total upah hari ini
            $table->text('keterangan')->nullable();
            $table->string('dicatat_oleh')->nullable(); // User yang absen
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['tukang_id', 'tanggal']);
            $table->index('tanggal');
            
            // Unique constraint: satu tukang hanya bisa absen sekali per hari
            $table->unique(['tukang_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_tukangs');
    }
};
