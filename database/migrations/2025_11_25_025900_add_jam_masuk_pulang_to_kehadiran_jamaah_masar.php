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
        Schema::table('kehadiran_jamaah_masar', function (Blueprint $table) {
            // Tambah kolom jam_masuk dan jam_pulang untuk integrasi fingerspot
            // Mirip dengan tabel presensi karyawan
            $table->time('jam_masuk')->nullable()->after('tanggal_kehadiran')->comment('Jam masuk dari mesin fingerprint');
            $table->time('jam_pulang')->nullable()->after('jam_masuk')->comment('Jam pulang dari mesin fingerprint');
            
            // Rename jam_kehadiran menjadi jam_kehadiran_old (backup)
            // Atau bisa tetap digunakan sebagai fallback
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran_jamaah_masar', function (Blueprint $table) {
            $table->dropColumn(['jam_masuk', 'jam_pulang']);
        });
    }
};
