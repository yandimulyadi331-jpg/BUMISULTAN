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
        Schema::create('peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peralatan')->unique();
            $table->string('nama_peralatan');
            $table->string('kategori'); // Kebersihan, Alat Tulis, Elektronik, dll
            $table->text('deskripsi')->nullable();
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->integer('stok_dipinjam')->default(0);
            $table->integer('stok_rusak')->default(0);
            $table->string('satuan'); // pcs, unit, set, dll
            $table->string('lokasi_penyimpanan')->nullable();
            $table->string('kondisi')->default('baik'); // baik, rusak ringan, rusak berat
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->string('supplier')->nullable();
            $table->integer('stok_minimum')->default(0); // untuk alert stok menipis
            $table->string('foto')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peralatan');
    }
};
