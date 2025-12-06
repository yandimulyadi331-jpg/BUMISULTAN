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
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            $table->boolean('auto_potong_gaji')->default(true)->after('keterangan')->comment('Otomatis potong gaji via payroll');
            $table->char('kode_penyesuaian_gaji', 9)->nullable()->after('auto_potong_gaji')->comment('Kode penyesuaian gaji yang digunakan');
            $table->boolean('sudah_dipotong')->default(false)->after('kode_penyesuaian_gaji')->comment('Status sudah dipotong di payroll');
            $table->date('tanggal_dipotong')->nullable()->after('sudah_dipotong')->comment('Tanggal dipotong via payroll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            $table->dropColumn(['auto_potong_gaji', 'kode_penyesuaian_gaji', 'sudah_dipotong', 'tanggal_dipotong']);
        });
    }
};
