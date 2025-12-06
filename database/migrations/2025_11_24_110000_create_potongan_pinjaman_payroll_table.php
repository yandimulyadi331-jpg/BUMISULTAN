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
        Schema::create('potongan_pinjaman_payroll', function (Blueprint $table) {
            $table->id();
            $table->char('kode_potongan', 12)->unique(); // PPP112025 (Potongan Pinjaman Payroll + Bulan + Tahun)
            $table->smallInteger('bulan');
            $table->integer('tahun');
            $table->char('nik', 9);
            $table->unsignedBigInteger('pinjaman_id');
            $table->unsignedBigInteger('cicilan_id');
            $table->integer('cicilan_ke');
            $table->decimal('jumlah_potongan', 15, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['pending', 'dipotong', 'batal'])->default('pending');
            $table->date('tanggal_dipotong')->nullable();
            $table->unsignedBigInteger('diproses_oleh')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pinjaman_id')->references('id')->on('pinjaman')->onDelete('cascade');
            $table->foreign('cicilan_id')->references('id')->on('pinjaman_cicilan')->onDelete('cascade');
            $table->foreign('diproses_oleh')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['bulan', 'tahun']);
            $table->index('status');
            $table->index('nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_pinjaman_payroll');
    }
};
