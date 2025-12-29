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
        Schema::create('potongan_pinjaman_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id')->comment('FK ke potongan_pinjaman_master');
            
            // Periode
            $table->smallInteger('bulan')->comment('Bulan potongan (1-12)');
            $table->integer('tahun')->comment('Tahun potongan');
            
            // Jumlah
            $table->decimal('jumlah_potongan', 15, 2)->comment('Jumlah yang dipotong');
            $table->integer('cicilan_ke')->comment('Cicilan ke berapa');
            
            // Status
            $table->enum('status', ['pending', 'dipotong', 'batal'])->default('pending');
            $table->date('tanggal_dipotong')->nullable()->comment('Kapan dipotong');
            $table->unsignedBigInteger('diproses_oleh')->nullable()->comment('FK ke users');
            
            // Metadata
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('master_id')->references('id')->on('potongan_pinjaman_master')->onDelete('cascade');
            $table->foreign('diproses_oleh')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('master_id');
            $table->index(['bulan', 'tahun'], 'idx_periode');
            $table->index('status');
            
            // Unique constraint - satu master hanya bisa punya 1 detail per periode
            $table->unique(['master_id', 'bulan', 'tahun'], 'unique_master_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_pinjaman_detail');
    }
};
