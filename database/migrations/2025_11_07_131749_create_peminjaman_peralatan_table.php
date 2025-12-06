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
        Schema::create('peminjaman_peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_peminjaman')->unique();
            $table->foreignId('peralatan_id')->constrained('peralatan')->onDelete('cascade');
            $table->string('nama_peminjam');
            $table->integer('jumlah_dipinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->string('keperluan');
            $table->string('status')->default('dipinjam'); // dipinjam, dikembalikan, terlambat
            $table->text('kondisi_saat_dipinjam')->nullable();
            $table->text('kondisi_saat_dikembalikan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_peralatan');
    }
};
