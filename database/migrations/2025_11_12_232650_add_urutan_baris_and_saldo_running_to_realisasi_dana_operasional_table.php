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
        Schema::table('realisasi_dana_operasional', function (Blueprint $table) {
            // Kolom untuk menyimpan urutan baris dari Excel (baris 1, 2, 3, dst)
            $table->integer('urutan_baris')->nullable()->after('tanggal_realisasi')
                ->comment('Urutan baris dari Excel untuk menjaga sequence yang benar');
            
            // Kolom untuk menyimpan saldo berjalan (running balance) seperti di Excel
            // Rumus: Saldo Running = Saldo Sebelumnya + Dana Masuk - Dana Keluar
            $table->decimal('saldo_running', 15, 2)->nullable()->after('nominal')
                ->comment('Saldo berjalan setelah transaksi ini (seperti kolom Saldo di Excel)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisasi_dana_operasional', function (Blueprint $table) {
            $table->dropColumn(['urutan_baris', 'saldo_running']);
        });
    }
};
