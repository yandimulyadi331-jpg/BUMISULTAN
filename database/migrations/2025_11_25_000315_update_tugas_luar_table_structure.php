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
            // Drop kolom-kolom yang tidak diperlukan
            $table->dropForeign(['nik']);
            $table->dropColumn(['nik', 'waktu_mulai', 'waktu_selesai', 'waktu_kembali_actual', 'disetujui_oleh', 'catatan']);
            
            // Tambah kolom baru
            $table->text('karyawan_list')->after('kode_tugas');
            $table->string('dibuat_oleh')->nullable()->after('status');
            
            // Update default status
            $table->string('status')->default('keluar')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_luar', function (Blueprint $table) {
            // Kembalikan ke struktur lama
            $table->dropColumn(['karyawan_list', 'dibuat_oleh']);
            $table->string('nik');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->time('waktu_kembali_actual')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->text('catatan')->nullable();
            $table->string('status')->default('sedang_tugas')->change();
        });
    }
};
