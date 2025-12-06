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
        Schema::create('pinjaman_tukangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tukang_id')->constrained('tukangs')->onDelete('cascade');
            $table->date('tanggal_pinjaman');
            $table->decimal('jumlah_pinjaman', 15, 2)->default(0);
            $table->decimal('jumlah_terbayar', 15, 2)->default(0);
            $table->decimal('sisa_pinjaman', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'lunas', 'dibatalkan'])->default('aktif');
            $table->decimal('cicilan_per_minggu', 15, 2)->default(0)->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal_lunas')->nullable();
            $table->string('dicatat_oleh')->nullable();
            $table->timestamps();
            
            // Index
            $table->index(['tukang_id', 'status']);
            $table->index('tanggal_pinjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_tukangs');
    }
};
