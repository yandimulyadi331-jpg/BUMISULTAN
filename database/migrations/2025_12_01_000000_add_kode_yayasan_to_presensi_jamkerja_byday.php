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
        Schema::table('presensi_jamkerja_byday', function (Blueprint $table) {
            // Add kode_yayasan column if it doesn't exist
            if (!Schema::hasColumn('presensi_jamkerja_byday', 'kode_yayasan')) {
                $table->char('kode_yayasan', 3)->nullable()->after('nik');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_jamkerja_byday', function (Blueprint $table) {
            if (Schema::hasColumn('presensi_jamkerja_byday', 'kode_yayasan')) {
                $table->dropForeign(['kode_yayasan']);
                $table->dropColumn('kode_yayasan');
            }
        });
    }
};
