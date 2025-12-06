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
        Schema::create('pinjaman_cicilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->onDelete('cascade');
            
            // Informasi Cicilan
            $table->integer('cicilan_ke'); // Cicilan ke-1, ke-2, dst
            $table->date('tanggal_jatuh_tempo');
            $table->decimal('jumlah_pokok', 15, 2); // Pokok yang harus dibayar
            $table->decimal('jumlah_bunga', 15, 2); // Bunga yang harus dibayar
            $table->decimal('jumlah_cicilan', 15, 2); // Total cicilan (pokok + bunga)
            
            // Status Pembayaran
            $table->enum('status', ['belum_bayar', 'sebagian', 'lunas', 'terlambat'])->default('belum_bayar');
            
            // Pembayaran
            $table->date('tanggal_bayar')->nullable();
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_cicilan', 15, 2)->default(0);
            
            // Denda
            $table->integer('hari_terlambat')->default(0);
            $table->decimal('denda', 15, 2)->default(0);
            
            // Metode Pembayaran
            $table->string('metode_pembayaran')->nullable(); // Transfer/Tunai/Potong Gaji
            $table->string('no_referensi')->nullable(); // No. transfer atau referensi
            $table->text('bukti_pembayaran')->nullable(); // Path file bukti
            
            // Dicatat oleh
            $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['pinjaman_id', 'cicilan_ke']);
            $table->index('tanggal_jatuh_tempo');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_cicilan');
    }
};
