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
        Schema::create('belanja_khidmat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_khidmat_id')->constrained('jadwal_khidmat')->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belanja_khidmat');
    }
};
