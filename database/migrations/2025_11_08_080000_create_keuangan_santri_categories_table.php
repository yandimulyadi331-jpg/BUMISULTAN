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
        Schema::create('keuangan_santri_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Kebersihan, Makanan, Pendidikan, dll
            $table->enum('jenis', ['pemasukan', 'pengeluaran']); // Jenis transaksi
            $table->text('keywords')->nullable(); // Kata kunci untuk auto-detect (JSON array)
            $table->string('icon')->nullable(); // Icon untuk UI
            $table->string('color')->default('#3B82F6'); // Warna untuk chart
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_santri_categories');
    }
};
