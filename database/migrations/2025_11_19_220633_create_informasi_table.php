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
        Schema::create('informasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konten')->nullable();
            $table->string('tipe')->default('banner'); // banner, link, text
            $table->string('banner_path')->nullable(); // path untuk banner/image
            $table->string('link_url')->nullable(); // URL jika tipe link
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // untuk urutan tampil
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi');
    }
};
