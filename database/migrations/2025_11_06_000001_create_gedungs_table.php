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
        Schema::create('gedungs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gedung', 20)->unique();
            $table->string('nama_gedung', 100);
            $table->text('alamat')->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->integer('jumlah_lantai')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index('kode_cabang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gedungs');
    }
};
