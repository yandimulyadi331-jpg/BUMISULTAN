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
        Schema::create('checklist_periode_config', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_periode', ['harian', 'mingguan', 'bulanan', 'tahunan'])->unique();
            $table->boolean('is_enabled')->default(true)->comment('Toggle ON/OFF untuk periode ini');
            $table->boolean('is_mandatory')->default(false)->comment('Apakah wajib diselesaikan sebelum absen pulang');
            $table->text('keterangan')->nullable()->comment('Catatan untuk karyawan');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('diubah_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['tipe_periode', 'is_enabled'], 'idx_tipe_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_periode_config');
    }
};
