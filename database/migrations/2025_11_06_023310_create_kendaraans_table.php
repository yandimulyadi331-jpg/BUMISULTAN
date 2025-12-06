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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kendaraan', 20)->unique();
            $table->string('nama_kendaraan', 100);
            $table->enum('jenis_kendaraan', ['Mobil', 'Motor', 'Truk', 'Bus', 'Lainnya']);
            $table->string('merk', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('tahun', 4)->nullable();
            $table->string('no_polisi', 20)->unique();
            $table->string('no_rangka', 50)->nullable();
            $table->string('no_mesin', 50)->nullable();
            $table->string('warna', 30)->nullable();
            $table->integer('kapasitas_penumpang')->nullable();
            $table->enum('jenis_bbm', ['Bensin', 'Solar', 'Listrik', 'Hybrid'])->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['tersedia', 'keluar', 'dipinjam', 'service'])->default('tersedia');
            $table->date('stnk_berlaku')->nullable();
            $table->date('pajak_berlaku')->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
