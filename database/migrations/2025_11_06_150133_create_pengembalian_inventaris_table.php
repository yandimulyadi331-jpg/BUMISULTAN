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
        Schema::create('pengembalian_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengembalian')->unique(); // KMB-001
            $table->foreignId('peminjaman_inventaris_id')->constrained('peminjaman_inventaris')->onDelete('cascade');
            $table->dateTime('tanggal_pengembalian');
            $table->integer('jumlah_kembali')->default(1);
            $table->enum('kondisi_barang', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->boolean('terlambat')->default(false);
            $table->integer('hari_keterlambatan')->default(0);
            $table->decimal('denda', 15, 2)->default(0); // Jika ada denda keterlambatan
            $table->string('foto_pengembalian')->nullable(); // Foto kondisi barang saat dikembalikan
            $table->text('ttd_peminjam')->nullable(); // TTD peminjam saat mengembalikan
            $table->text('ttd_petugas')->nullable(); // TTD petugas penerima
            $table->text('keterangan')->nullable(); // Catatan kondisi atau masalah
            $table->text('catatan_kerusakan')->nullable(); // Detail jika ada kerusakan
            $table->foreignId('diterima_oleh')->nullable()->constrained('users')->onDelete('set null'); // Petugas penerima
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_inventaris');
    }
};
