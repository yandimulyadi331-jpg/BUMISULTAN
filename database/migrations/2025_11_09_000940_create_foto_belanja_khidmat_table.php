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
        Schema::create('foto_belanja_khidmat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_khidmat_id')->constrained('jadwal_khidmat')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path_file');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_belanja_khidmat');
    }
};
