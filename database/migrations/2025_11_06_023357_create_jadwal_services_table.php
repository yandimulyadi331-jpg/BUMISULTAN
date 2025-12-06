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
        Schema::create('jadwal_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->string('jenis_service', 100);
            $table->enum('tipe_interval', ['kilometer', 'waktu'])->default('kilometer');
            
            // Untuk interval berdasarkan kilometer
            $table->integer('interval_km')->nullable(); // Service setiap berapa KM
            $table->integer('km_terakhir')->nullable(); // KM terakhir service
            
            // Untuk interval berdasarkan waktu
            $table->integer('interval_hari')->nullable(); // Service setiap berapa hari
            $table->date('tanggal_terakhir')->nullable(); // Tanggal service terakhir
            $table->date('jadwal_berikutnya')->nullable(); // Tanggal jadwal service berikutnya
            
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_services');
    }
};
