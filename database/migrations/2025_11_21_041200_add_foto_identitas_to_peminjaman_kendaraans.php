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
        Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
            $table->string('foto_identitas')->nullable()->after('no_hp_peminjam');
            $table->text('keperluan')->nullable()->after('tujuan');
            $table->string('status_bbm_pinjam')->nullable()->after('km_awal');
            $table->string('status_bbm_kembali')->nullable()->after('km_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['foto_identitas', 'keperluan', 'status_bbm_pinjam', 'status_bbm_kembali']);
        });
    }
};
