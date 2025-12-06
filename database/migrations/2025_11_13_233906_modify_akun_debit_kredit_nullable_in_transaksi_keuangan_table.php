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
        // Ubah kolom akun_debit_id dan akun_kredit_id menjadi nullable
        DB::statement('ALTER TABLE transaksi_keuangan MODIFY COLUMN akun_debit_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE transaksi_keuangan MODIFY COLUMN akun_kredit_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke NOT NULL
        DB::statement('ALTER TABLE transaksi_keuangan MODIFY COLUMN akun_debit_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE transaksi_keuangan MODIFY COLUMN akun_kredit_id BIGINT UNSIGNED NOT NULL');
    }
};
