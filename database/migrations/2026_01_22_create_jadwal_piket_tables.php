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
        // Relasi: Karyawan dengan Jam Kerja (Jadwal Piket)
        Schema::create('jadwal_piket_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nik'); // Foreign key ke karyawan
            $table->char('kode_jam_kerja', 4); // Foreign key ke presensi_jamkerja
            $table->date('mulai_berlaku');
            $table->date('berakhir_berlaku')->nullable(); // NULL = berlaku selamanya
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('presensi_jamkerja')->restrictOnDelete()->cascadeOnUpdate();
            
            // Indexes
            $table->unique(['nik', 'kode_jam_kerja', 'mulai_berlaku']);
            $table->index(['nik', 'mulai_berlaku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_piket_karyawans');
    }
};

