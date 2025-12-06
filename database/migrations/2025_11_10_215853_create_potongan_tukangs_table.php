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
        Schema::create('potongan_tukangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tukang_id')->constrained('tukangs')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_potongan', ['keterlambatan', 'tidak_hadir', 'kerusakan_alat', 'pinjaman', 'denda', 'lain_lain']);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('dicatat_oleh')->nullable();
            $table->timestamps();
            
            // Index
            $table->index(['tukang_id', 'tanggal']);
            $table->index('jenis_potongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_tukangs');
    }
};
