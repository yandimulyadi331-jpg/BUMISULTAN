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
        if (Schema::hasTable('presensi_yayasan')) {
            Schema::table('presensi_yayasan', function (Blueprint $table) {
                // Drop foreign key constraint first
                $table->dropForeign('presensi_yayasan_kode_yayasan_foreign');
            });

            Schema::table('presensi_yayasan', function (Blueprint $table) {
                // Change kode_yayasan from char(8) to string(20) to match yayasan_masar
                $table->string('kode_yayasan', 20)->change();
            });

            Schema::table('presensi_yayasan', function (Blueprint $table) {
                // Re-create the foreign key
                $table->foreign('kode_yayasan')
                    ->references('kode_yayasan')
                    ->on('yayasan_masar')
                    ->restrictOnDelete()
                    ->cascadeOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('presensi_yayasan')) {
            Schema::table('presensi_yayasan', function (Blueprint $table) {
                $table->char('kode_yayasan', 8)->change();
            });
        }
    }
};
