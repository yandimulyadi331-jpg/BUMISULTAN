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
        Schema::table('pinjaman', function (Blueprint $table) {
            // Tambah kolom jaminan setelah dokumen_pendukung_lain
            $table->string('jenis_jaminan')->nullable()->after('dokumen_pendukung_lain');
            $table->string('nomor_jaminan')->nullable()->after('jenis_jaminan');
            $table->text('deskripsi_jaminan')->nullable()->after('nomor_jaminan');
            $table->decimal('nilai_jaminan', 15, 2)->nullable()->after('deskripsi_jaminan');
            $table->string('atas_nama_jaminan')->nullable()->after('nilai_jaminan');
            $table->string('kondisi_jaminan')->nullable()->after('atas_nama_jaminan');
            $table->text('keterangan_jaminan')->nullable()->after('kondisi_jaminan');
            
            // Tambah kolom nama_peminjam_lengkap
            $table->string('nama_peminjam_lengkap')->nullable()->after('karyawan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_jaminan',
                'nomor_jaminan', 
                'deskripsi_jaminan',
                'nilai_jaminan',
                'atas_nama_jaminan',
                'kondisi_jaminan',
                'keterangan_jaminan',
                'nama_peminjam_lengkap'
            ]);
        });
    }
};
