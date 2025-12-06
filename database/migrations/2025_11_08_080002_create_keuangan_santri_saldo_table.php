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
        Schema::create('keuangan_santri_saldo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->unique()->constrained('santri')->onDelete('cascade');
            $table->decimal('saldo_awal', 15, 2)->default(0); // Saldo awal saat registrasi
            $table->decimal('total_pemasukan', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->decimal('saldo_akhir', 15, 2)->default(0); // Saldo terkini
            $table->date('last_transaction_date')->nullable(); // Tanggal transaksi terakhir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_santri_saldo');
    }
};
