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
        Schema::table('master_perawatan', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('tipe_periode')->comment('Jam mulai untuk checklist harian');
            $table->time('jam_selesai')->nullable()->after('jam_mulai')->comment('Jam selesai untuk checklist harian');
            $table->date('tanggal_target')->nullable()->after('jam_selesai')->comment('Tanggal target untuk mingguan/tahunan');
            $table->string('bulan_target', 7)->nullable()->after('tanggal_target')->comment('Bulan target (YYYY-MM) untuk bulanan');
            $table->tinyInteger('hari_minggu')->nullable()->after('bulan_target')->comment('Hari dalam minggu (1=Senin, 7=Minggu) untuk mingguan');
            $table->tinyInteger('tanggal_bulan')->nullable()->after('hari_minggu')->comment('Tanggal dalam bulan (1-31) untuk bulanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_perawatan', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai', 'tanggal_target', 'bulan_target', 'hari_minggu', 'tanggal_bulan']);
        });
    }
};
