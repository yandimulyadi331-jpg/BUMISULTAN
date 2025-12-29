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
        // Tambah kolom tanggal_potongan di master
        Schema::table('potongan_pinjaman_master', function (Blueprint $table) {
            $table->smallInteger('tanggal_potongan')->default(25)->after('tahun_selesai')
                  ->comment('Tanggal jatuh tempo per bulan (1-31)');
        });

        // Tambah kolom tanggal_jatuh_tempo di detail
        Schema::table('potongan_pinjaman_detail', function (Blueprint $table) {
            $table->date('tanggal_jatuh_tempo')->nullable()->after('tahun')
                  ->comment('Tanggal jatuh tempo potongan (auto-calculated)');
            $table->index('tanggal_jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potongan_pinjaman_master', function (Blueprint $table) {
            $table->dropColumn('tanggal_potongan');
        });

        Schema::table('potongan_pinjaman_detail', function (Blueprint $table) {
            $table->dropIndex(['tanggal_jatuh_tempo']);
            $table->dropColumn('tanggal_jatuh_tempo');
        });
    }
};
