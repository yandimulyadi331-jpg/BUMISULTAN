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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20)->unique();
            $table->foreignId('ruangan_id')->constrained('ruangans')->onDelete('cascade');
            $table->string('nama_barang', 100);
            $table->string('kategori', 50)->nullable();
            $table->string('merk', 50)->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 20)->default('Unit');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->date('tanggal_perolehan')->nullable();
            $table->decimal('harga_perolehan', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index('ruangan_id');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
