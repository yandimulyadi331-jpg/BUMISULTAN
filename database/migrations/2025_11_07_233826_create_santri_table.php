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
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            
            // Data Pribadi Santri
            $table->string('nis')->unique()->comment('Nomor Induk Santri');
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->string('nik', 16)->unique()->nullable()->comment('NIK KTP');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat_lengkap');
            $table->string('provinsi')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('foto')->nullable()->comment('Path foto santri');
            
            // Data Keluarga
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('no_hp_ayah', 15)->nullable();
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_hp_ibu', 15)->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('hubungan_wali')->nullable();
            $table->string('no_hp_wali', 15)->nullable();
            
            // Data Pendidikan
            $table->string('asal_sekolah')->nullable();
            $table->string('tingkat_pendidikan')->nullable()->comment('SD/SMP/SMA/dll');
            $table->year('tahun_masuk');
            $table->date('tanggal_masuk');
            $table->enum('status_santri', ['aktif', 'cuti', 'alumni', 'keluar'])->default('aktif');
            
            // Data Hafalan Al-Qur'an (Fokus Tahfidz)
            $table->integer('jumlah_juz_hafalan')->default(0)->comment('Jumlah juz yang sudah dihafal');
            $table->integer('jumlah_halaman_hafalan')->default(0)->comment('Total halaman hafalan');
            $table->string('target_hafalan')->nullable()->comment('Target hafalan (misal: 30 Juz)');
            $table->date('tanggal_mulai_tahfidz')->nullable();
            $table->date('tanggal_khatam_terakhir')->nullable();
            $table->text('catatan_hafalan')->nullable();
            
            // Data Asrama/Kamar
            $table->string('nama_asrama')->nullable();
            $table->string('nomor_kamar')->nullable();
            $table->string('nama_pembina')->nullable();
            
            // Persiapan untuk Integrasi Masa Depan
            // Field ini akan digunakan untuk relasi ke tabel lain
            // - Absensi: akan menggunakan santri_id
            // - Izin: akan menggunakan santri_id  
            // - Keuangan: akan menggunakan santri_id
            // - Pelanggaran: akan menggunakan santri_id
            
            // Status dan Metadata
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('nis');
            $table->index('status_santri');
            $table->index('status_aktif');
            $table->index('tahun_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};
