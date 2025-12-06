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
        Schema::create('peminjaman_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 30)->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->string('nama_peminjam', 100);
            $table->string('email_peminjam', 100)->nullable(); // Untuk tracking GPS
            $table->string('no_hp_peminjam', 20)->nullable();
            $table->string('driver', 100)->nullable();
            $table->string('tujuan')->nullable();
            $table->dateTime('waktu_pinjam');
            $table->dateTime('estimasi_kembali');
            $table->dateTime('waktu_kembali')->nullable();
            $table->enum('status', ['dipinjam', 'kembali', 'terlambat'])->default('dipinjam');
            $table->decimal('km_awal', 10, 2)->nullable();
            $table->decimal('km_akhir', 10, 2)->nullable();
            $table->string('foto_pinjam')->nullable(); // Foto bukti saat pinjam
            $table->text('ttd_pinjam')->nullable(); // Base64 tanda tangan digital
            $table->string('foto_kembali')->nullable(); // Foto bukti saat kembali
            $table->text('ttd_kembali')->nullable(); // Base64 tanda tangan digital
            $table->text('keterangan_pinjam')->nullable();
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
        Schema::dropIfExists('peminjaman_kendaraans');
    }
};
