<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update master_perawatan dan perawatan_log untuk mendukung jadwal piket
     */
    public function up(): void
    {
        // Add kode_jam_kerja ke master_perawatan (nullable, for optional shift filtering)
        Schema::table('master_perawatan', function (Blueprint $table) {
            $table->char('kode_jam_kerja', 4)->nullable()->after('kategori');
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('presensi_jamkerja')->restrictOnDelete()->cascadeOnUpdate();
        });

        // Add columns ke perawatan_log untuk riwayat dan user info
        Schema::table('perawatan_log', function (Blueprint $table) {
            // Tambahan field untuk riwayat
            $table->string('nama_karyawan')->nullable()->after('user_id'); // snapshot nama karyawan saat ceklis
            $table->time('jam_ceklis')->nullable()->after('waktu_eksekusi'); // jam detail saat ceklis
            $table->char('kode_jam_kerja', 4)->nullable()->after('jam_ceklis'); // jam kerja mana yang berlaku
            
            // Field untuk validasi
            $table->enum('status_validity', ['valid', 'expired', 'outside_shift'])->default('valid')->after('status');
            $table->timestamp('last_reset_at')->nullable()->after('status_validity');
            
            // Foreign key
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('presensi_jamkerja')->restrictOnDelete()->cascadeOnUpdate();
            
            // Indexes untuk performa query riwayat
            $table->index(['user_id', 'tanggal_eksekusi']);
            $table->index(['kode_jam_kerja', 'tanggal_eksekusi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perawatan_log', function (Blueprint $table) {
            $table->dropForeign(['kode_jam_kerja']);
            $table->dropIndex(['user_id', 'tanggal_eksekusi']);
            $table->dropIndex(['kode_jam_kerja', 'tanggal_eksekusi']);
            $table->dropColumn([
                'nama_karyawan',
                'jam_ceklis',
                'kode_jam_kerja',
                'status_validity',
                'last_reset_at'
            ]);
        });

        Schema::table('master_perawatan', function (Blueprint $table) {
            $table->dropForeign(['kode_jam_kerja']);
            $table->dropColumn('kode_jam_kerja');
        });
    }
};
