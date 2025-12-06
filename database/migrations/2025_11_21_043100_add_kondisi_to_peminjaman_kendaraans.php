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
            $table->string('kondisi_kendaraan')->nullable()->after('keterangan_kembali');
            $table->text('catatan_kembali')->nullable()->after('kondisi_kendaraan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['kondisi_kendaraan', 'catatan_kembali']);
        });
    }
};
