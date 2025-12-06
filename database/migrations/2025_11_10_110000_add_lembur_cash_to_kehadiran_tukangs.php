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
        Schema::table('kehadiran_tukangs', function (Blueprint $table) {
            // Tambah kolom untuk flag lembur dibayar langsung
            $table->boolean('lembur_dibayar_cash')->default(false)->after('lembur');
            
            // Tambah kolom tanggal pembayaran lembur
            $table->date('tanggal_bayar_lembur')->nullable()->after('lembur_dibayar_cash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran_tukangs', function (Blueprint $table) {
            $table->dropColumn(['lembur_dibayar_cash', 'tanggal_bayar_lembur']);
        });
    }
};
