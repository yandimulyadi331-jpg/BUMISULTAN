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
        Schema::create('keuangan_tukangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tukang_id')->constrained('tukangs')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['upah_harian', 'lembur_full', 'lembur_setengah', 'lembur_cash', 'pinjaman', 'pembayaran_pinjaman', 'potongan', 'bonus', 'lain_lain']);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->enum('tipe', ['debit', 'kredit']); // debit = masuk (upah), kredit = keluar (potongan)
            $table->unsignedBigInteger('kehadiran_tukang_id')->nullable();
            $table->unsignedBigInteger('pinjaman_tukang_id')->nullable();
            $table->unsignedBigInteger('potongan_tukang_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('dicatat_oleh')->nullable();
            $table->timestamps();
            
            // Index
            $table->index(['tukang_id', 'tanggal']);
            $table->index('jenis_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_tukangs');
    }
};
