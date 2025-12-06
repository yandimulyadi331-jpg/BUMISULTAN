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
        Schema::create('tugas_luar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tugas')->unique();
            $table->text('karyawan_list'); // JSON array untuk menyimpan multiple karyawan
            $table->date('tanggal');
            $table->string('tujuan');
            $table->text('keterangan')->nullable();
            $table->time('waktu_keluar');
            $table->time('waktu_kembali')->nullable();
            $table->string('status')->default('keluar'); // pending, keluar, kembali
            $table->string('dibuat_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_luar');
    }
};
