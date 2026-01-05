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
        // Hapus kolom denda_keterlambatan dari tabel pinjaman
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropColumn('denda_keterlambatan');
        });

        // Hapus kolom denda dari tabel pinjaman_cicilan
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            $table->dropColumn('denda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom denda_keterlambatan ke tabel pinjaman
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->decimal('denda_keterlambatan', 15, 2)->default(0)->after('tanggal_lunas');
        });

        // Kembalikan kolom denda ke tabel pinjaman_cicilan
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            $table->decimal('denda', 15, 2)->default(0)->after('hari_terlambat');
        });
    }
};
