<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_crew', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->index();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('kehadiran_count')->default(0)->comment('Jumlah kehadiran dalam periode');
            $table->integer('aktivitas_count')->default(0)->comment('Jumlah aktivitas yang diupload');
            $table->integer('perawatan_count')->default(0)->comment('Jumlah checklist perawatan yang diselesaikan');
            $table->decimal('kehadiran_point', 10, 2)->default(0)->comment('Point dari kehadiran');
            $table->decimal('aktivitas_point', 10, 2)->default(0)->comment('Point dari aktivitas');
            $table->decimal('perawatan_point', 10, 2)->default(0)->comment('Point dari perawatan');
            $table->decimal('total_point', 10, 2)->default(0)->comment('Total akumulasi point');
            $table->integer('ranking')->nullable()->comment('Peringkat karyawan');
            $table->timestamps();

            // Constraint untuk memastikan kombinasi nik + bulan + tahun unik
            $table->unique(['nik', 'bulan', 'tahun']);
            
            // Foreign key ke tabel karyawan
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_crew');
    }
};
