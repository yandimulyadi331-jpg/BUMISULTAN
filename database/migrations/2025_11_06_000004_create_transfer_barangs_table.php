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
        Schema::create('transfer_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transfer', 30)->unique();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('ruangan_asal_id')->constrained('ruangans')->onDelete('cascade');
            $table->foreignId('ruangan_tujuan_id')->constrained('ruangans')->onDelete('cascade');
            $table->integer('jumlah_transfer');
            $table->date('tanggal_transfer');
            $table->string('petugas', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index('barang_id');
            $table->index('tanggal_transfer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_barangs');
    }
};
