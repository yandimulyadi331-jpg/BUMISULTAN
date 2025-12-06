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
        Schema::create('service_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_service', 30)->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->string('driver_service', 100)->nullable(); // Yang bawa service
            $table->datetime('waktu_service'); // Waktu mulai service
            $table->string('jenis_service', 100); // Ganti Oli, Tune Up, dll
            $table->string('bengkel', 200)->nullable();
            $table->text('deskripsi_kerusakan')->nullable();
            $table->text('pekerjaan')->nullable(); // Pekerjaan yang akan dilakukan
            $table->decimal('km_service', 10, 2)->nullable();
            $table->decimal('estimasi_biaya', 12, 2)->nullable();
            $table->date('estimasi_selesai')->nullable();
            $table->string('pic', 100)->nullable(); // Person in charge
            $table->string('foto_before')->nullable();
            $table->decimal('latitude_service', 10, 7)->nullable();
            $table->decimal('longitude_service', 10, 7)->nullable();
            
            // Data setelah selesai
            $table->datetime('waktu_selesai')->nullable();
            $table->decimal('km_selesai', 10, 2)->nullable();
            $table->decimal('biaya_akhir', 12, 2)->nullable();
            $table->text('pekerjaan_selesai')->nullable();
            $table->text('catatan_mekanik')->nullable();
            $table->string('kondisi_kendaraan', 50)->nullable();
            $table->string('pic_selesai', 100)->nullable();
            $table->string('foto_after')->nullable();
            $table->decimal('latitude_selesai', 10, 7)->nullable();
            $table->decimal('longitude_selesai', 10, 7)->nullable();
            
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_kendaraans');
    }
};
