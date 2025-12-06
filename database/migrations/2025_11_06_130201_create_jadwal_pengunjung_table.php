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
        Schema::create('jadwal_pengunjung', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jadwal')->unique();
            $table->string('nama_lengkap');
            $table->string('instansi')->nullable();
            $table->string('no_telepon');
            $table->string('email')->nullable();
            $table->string('keperluan');
            $table->string('bertemu_dengan')->nullable();
            
            // Jadwal
            $table->date('tanggal_kunjungan');
            $table->time('waktu_kunjungan');
            $table->enum('status', ['terjadwal', 'selesai', 'batal'])->default('terjadwal');
            
            // Relasi
            $table->char('kode_cabang', 3)->nullable();
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengunjung');
    }
};
