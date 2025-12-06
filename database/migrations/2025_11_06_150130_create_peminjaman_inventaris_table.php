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
        Schema::create('peminjaman_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->string('karyawan_id')->nullable(); // NIK karyawan (opsional)
            $table->string('nama_peminjam'); // Nama peminjam manual input
            $table->integer('jumlah_pinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->text('keperluan')->nullable();
            $table->string('foto_barang')->nullable();
            $table->text('ttd_peminjam')->nullable(); // Base64 signature
            $table->text('ttd_petugas')->nullable(); // Base64 signature petugas yang menyetujui
            $table->enum('status_peminjaman', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_peminjaman')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->foreignId('inventaris_event_id')->nullable()->constrained('inventaris_events')->onDelete('set null');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_approval')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_inventaris');
    }
};
