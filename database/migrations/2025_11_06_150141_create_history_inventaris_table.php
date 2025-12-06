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
        Schema::create('history_inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->string('jenis_aktivitas'); // input, update, pinjam, kembali, pindah_lokasi, maintenance, perbaikan, hapus
            $table->text('deskripsi'); // Detail aktivitas
            $table->string('status_sebelum')->nullable();
            $table->string('status_sesudah')->nullable();
            $table->string('lokasi_sebelum')->nullable();
            $table->string('lokasi_sesudah')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('karyawan_id')->nullable(); // NIK karyawan tanpa foreign key constraint
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjaman_inventaris')->onDelete('set null');
            $table->foreignId('pengembalian_id')->nullable()->constrained('pengembalian_inventaris')->onDelete('set null');
            $table->text('data_perubahan')->nullable(); // JSON untuk detail perubahan
            $table->string('foto')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // User yang melakukan aktivitas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_inventaris');
    }
};
