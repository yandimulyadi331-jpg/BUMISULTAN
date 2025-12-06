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
        Schema::table('pinjaman', function (Blueprint $table) {
            // Menambahkan kolom tanggal jatuh tempo setiap bulan (1-31)
            $table->tinyInteger('tanggal_jatuh_tempo_setiap_bulan')->default(1)->after('tenor_bulan')->comment('Tanggal jatuh tempo cicilan setiap bulan (1-31)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropColumn('tanggal_jatuh_tempo_setiap_bulan');
        });
    }
};
