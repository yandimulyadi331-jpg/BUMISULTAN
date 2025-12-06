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
        Schema::create('pinjaman_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->onDelete('cascade');
            
            // History Log
            $table->string('aksi'); // pengajuan, review, setujui, tolak, cairkan, bayar_cicilan, lunas, dll
            $table->string('status_lama')->nullable();
            $table->string('status_baru')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('data_perubahan')->nullable(); // Log detail perubahan
            
            // User yang melakukan aksi
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable(); // Backup nama user
            
            $table->timestamps();
            
            // Indexes
            $table->index('pinjaman_id');
            $table->index('aksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_history');
    }
};
