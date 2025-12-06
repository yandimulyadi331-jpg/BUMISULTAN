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
            $table->string('latitude_pinjam')->nullable()->after('ttd_pinjam');
            $table->string('longitude_pinjam')->nullable()->after('latitude_pinjam');
            $table->string('latitude_kembali')->nullable()->after('ttd_kembali');
            $table->string('longitude_kembali')->nullable()->after('latitude_kembali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['latitude_pinjam', 'longitude_pinjam', 'latitude_kembali', 'longitude_kembali']);
        });
    }
};
