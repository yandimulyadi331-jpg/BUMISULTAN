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
        if (!Schema::hasTable('jamaah_majlis_taklim')) {
            Schema::create('jamaah_majlis_taklim', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_jamaah', 50)->unique()->comment('Format: JA-URUT-NIK2DIGIT-ID-TAHUN2DIGIT');
                $table->string('nama_jamaah', 100);
                $table->string('nik', 16)->unique();
                $table->text('alamat');
                $table->date('tanggal_lahir');
                $table->year('tahun_masuk');
                $table->string('no_telepon', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
                $table->string('pin_fingerprint', 10)->nullable()->comment('PIN untuk mesin fingerprint');
                $table->integer('jumlah_kehadiran')->default(0)->comment('Auto increment dari fingerprint');
                $table->boolean('status_umroh')->default(false)->comment('Badge sudah umroh atau belum');
                $table->date('tanggal_umroh')->nullable()->comment('Tanggal berangkat umroh');
                $table->string('foto', 255)->nullable();
                $table->enum('status_aktif', ['aktif', 'non_aktif'])->default('aktif');
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index('nomor_jamaah');
                $table->index('nama_jamaah');
                $table->index('status_aktif');
                $table->index('tahun_masuk');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('jamaah_majlis_taklim');
    }
};
