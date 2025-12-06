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
        Schema::create('pelanggaran_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('santri')->onDelete('cascade');
            $table->string('nama_santri');
            $table->string('nik_santri')->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan');
            $table->date('tanggal_pelanggaran');
            $table->integer('point')->default(1);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('tanggal_pelanggaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_santri');
    }
};
