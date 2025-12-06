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
        Schema::table('peminjaman_peralatan', function (Blueprint $table) {
            // Cek apakah kolom karyawan_id masih ada
            if (Schema::hasColumn('peminjaman_peralatan', 'karyawan_id')) {
                $table->dropForeign(['karyawan_id']);
                $table->dropColumn('karyawan_id');
            }
            
            // Tambah kolom nama_peminjam jika belum ada
            if (!Schema::hasColumn('peminjaman_peralatan', 'nama_peminjam')) {
                $table->string('nama_peminjam')->after('peralatan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_peralatan', function (Blueprint $table) {
            $table->dropColumn('nama_peminjam');
            $table->foreignId('karyawan_id')->after('peralatan_id')->constrained('karyawan')->onDelete('cascade');
        });
    }
};
