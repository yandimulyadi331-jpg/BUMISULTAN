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
        Schema::create('pengunjung', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengunjung')->unique();
            $table->string('nama_lengkap');
            $table->string('instansi')->nullable();
            $table->string('no_identitas')->nullable(); // KTP/SIM
            $table->string('no_telepon');
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('keperluan');
            $table->string('bertemu_dengan')->nullable();
            $table->string('foto')->nullable(); // path foto pengunjung
            
            // Check-in & Check-out
            $table->dateTime('waktu_checkin');
            $table->dateTime('waktu_checkout')->nullable();
            $table->enum('status', ['checkin', 'checkout'])->default('checkin');
            
            // Relasi
            $table->char('kode_cabang', 3)->nullable();
            $table->unsignedBigInteger('jadwal_pengunjung_id')->nullable(); // jika dari jadwal
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('set null');
            $table->foreign('jadwal_pengunjung_id')->references('id')->on('jadwal_pengunjung')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengunjung');
    }
};
