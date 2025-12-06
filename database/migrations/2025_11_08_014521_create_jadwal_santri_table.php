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
        Schema::create('jadwal_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jadwal'); // Contoh: Ngaji, Tahfidz, Kajian, dll
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_jadwal', ['harian', 'mingguan', 'bulanan'])->default('harian');
            $table->string('hari')->nullable(); // Senin, Selasa, dst (untuk jadwal mingguan)
            $table->date('tanggal')->nullable(); // untuk jadwal bulanan atau sekali waktu
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi_menit')->nullable(); // durasi dalam menit
            $table->string('tempat')->nullable();
            $table->string('pembimbing')->nullable(); // Ustadz/Ustadzah
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_santri');
    }
};
