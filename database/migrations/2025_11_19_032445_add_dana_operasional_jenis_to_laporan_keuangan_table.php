<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum jenis_laporan untuk tambah Dana Operasional types
        DB::statement("ALTER TABLE laporan_keuangan MODIFY COLUMN jenis_laporan ENUM(
            'NERACA',
            'LABA_RUGI',
            'PERUBAHAN_MODAL',
            'ARUS_KAS',
            'CATATAN_ATAS_LAPORAN',
            'NERACA_SALDO',
            'BUKU_BESAR',
            'LAPORAN_BUDGET',
            'LAPORAN_BULANAN',
            'LAPORAN_MINGGUAN',
            'LAPORAN_TAHUNAN',
            'LAPORAN_CUSTOM'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE laporan_keuangan MODIFY COLUMN jenis_laporan ENUM(
            'NERACA',
            'LABA_RUGI',
            'PERUBAHAN_MODAL',
            'ARUS_KAS',
            'CATATAN_ATAS_LAPORAN',
            'NERACA_SALDO',
            'BUKU_BESAR',
            'LAPORAN_BUDGET'
        ) NOT NULL");
    }
};
