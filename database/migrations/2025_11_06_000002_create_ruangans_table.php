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
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan', 20)->unique();
            $table->foreignId('gedung_id')->constrained('gedungs')->onDelete('cascade');
            $table->string('nama_ruangan', 100);
            $table->string('lantai', 10)->nullable();
            $table->decimal('luas', 10, 2)->nullable()->comment('Luas dalam m2');
            $table->integer('kapasitas')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index('gedung_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
