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
        Schema::create('aktivitas_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aktivitas', 30)->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->string('driver', 100);
            $table->string('email_driver', 100)->nullable(); // Untuk tracking GPS
            $table->text('penumpang')->nullable(); // JSON array nama penumpang
            $table->string('tujuan')->nullable();
            $table->dateTime('waktu_keluar');
            $table->dateTime('waktu_kembali')->nullable();
            $table->decimal('km_awal', 10, 2)->nullable();
            $table->decimal('km_akhir', 10, 2)->nullable();
            $table->enum('status', ['keluar', 'kembali'])->default('keluar');
            $table->text('keterangan_keluar')->nullable();
            $table->text('keterangan_kembali')->nullable();
            $table->text('tracking_data')->nullable(); // JSON untuk GPS tracking history
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_kendaraans');
    }
};
