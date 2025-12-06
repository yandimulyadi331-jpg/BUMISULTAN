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
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori', 10)->unique()->comment('Kode kategori untuk pembentukan kode dokumen, contoh: SK, PKS, SOP');
            $table->string('nama_kategori', 100)->comment('Nama kategori dokumen');
            $table->text('deskripsi')->nullable()->comment('Deskripsi kategori');
            $table->string('warna', 20)->default('#007bff')->comment('Warna badge untuk UI');
            $table->integer('last_number')->default(0)->comment('Nomor urut terakhir untuk auto-generate kode');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};
