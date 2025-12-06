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
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            // Ubah dari char(9) menjadi varchar(20) untuk menampung kode yang lebih panjang
            $table->string('kode_penyesuaian_gaji', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            // Kembalikan ke char(9)
            $table->char('kode_penyesuaian_gaji', 9)->nullable()->change();
        });
    }
};
