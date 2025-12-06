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
            $table->enum('tipe_transaksi', ['masuk', 'keluar'])->default('keluar')->after('nominal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisasi_dana_operasional', function (Blueprint $table) {
            $table->dropColumn('tipe_transaksi');
        });
    }
};
