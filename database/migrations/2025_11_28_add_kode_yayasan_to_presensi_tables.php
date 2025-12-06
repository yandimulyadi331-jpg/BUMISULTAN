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
        // Add kode_yayasan to presensi_jamkerja_bydate if not exists
        if (Schema::hasTable('presensi_jamkerja_bydate') && !Schema::hasColumn('presensi_jamkerja_bydate', 'kode_yayasan')) {
            Schema::table('presensi_jamkerja_bydate', function (Blueprint $table) {
                $table->string('kode_yayasan', 20)->nullable()->after('kode_jam_kerja');
            });
        }

        // Add kode_yayasan to presensi_jamkerja if not exists
        if (Schema::hasTable('presensi_jamkerja') && !Schema::hasColumn('presensi_jamkerja', 'kode_yayasan')) {
            Schema::table('presensi_jamkerja', function (Blueprint $table) {
                $table->string('kode_yayasan', 20)->nullable()->after('kode_jam_kerja');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns if they were added
        if (Schema::hasTable('presensi_jamkerja_bydate')) {
            Schema::table('presensi_jamkerja_bydate', function (Blueprint $table) {
                if (Schema::hasColumn('presensi_jamkerja_bydate', 'kode_yayasan')) {
                    $table->dropColumn('kode_yayasan');
                }
            });
        }

        if (Schema::hasTable('presensi_jamkerja')) {
            Schema::table('presensi_jamkerja', function (Blueprint $table) {
                if (Schema::hasColumn('presensi_jamkerja', 'kode_yayasan')) {
                    $table->dropColumn('kode_yayasan');
                }
            });
        }
    }
};
