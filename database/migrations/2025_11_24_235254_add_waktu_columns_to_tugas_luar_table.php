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
        Schema::table('tugas_luar', function (Blueprint $table) {
            $table->time('waktu_keluar')->nullable()->after('keterangan');
            $table->time('waktu_kembali')->nullable()->after('waktu_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_luar', function (Blueprint $table) {
            $table->dropColumn(['waktu_keluar', 'waktu_kembali']);
        });
    }
};
