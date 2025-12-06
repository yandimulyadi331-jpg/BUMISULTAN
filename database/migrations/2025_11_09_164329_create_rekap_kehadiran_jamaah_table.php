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
        Schema::create('rekap_kehadiran_jamaah', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index();
            $table->integer('jumlah_hadir')->unsigned();
            $table->integer('total_jamaah')->unsigned()->nullable();
            $table->decimal('persentase', 5, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign key ke users
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kehadiran_jamaah');
    }
};
