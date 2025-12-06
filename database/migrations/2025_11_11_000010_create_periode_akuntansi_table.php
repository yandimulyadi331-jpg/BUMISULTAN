<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Periode Akuntansi / Fiscal Period
     */
    public function up(): void
    {
        Schema::create('periode_akuntansi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode', 100)->comment('Nama periode misal: Januari 2025');
            $table->string('kode_periode', 7)->unique()->comment('YYYY-MM');
            $table->integer('tahun');
            $table->integer('bulan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', [
                'OPEN',       // Periode terbuka, bisa input transaksi
                'CLOSED',     // Periode ditutup, tidak bisa input
                'LOCKED'      // Periode dikunci permanen
            ])->default('OPEN');
            $table->boolean('is_closing_done')->default(false)->comment('Apakah sudah dilakukan closing');
            $table->date('tanggal_closing')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users');
            $table->text('catatan_closing')->nullable();
            $table->timestamps();
            
            // Index
            $table->index(['tahun', 'bulan']);
            $table->index(['kode_periode', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_akuntansi');
    }
};
