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
        Schema::create('temuan', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->comment('Judul temuan/masalah yang dilaporkan');
            $table->text('deskripsi')->comment('Deskripsi lengkap temuan');
            $table->string('lokasi')->comment('Lokasi tempat temuan ditemukan');
            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang')->comment('Tingkat urgensi perbaikan');
            $table->enum('status', ['baru', 'sedang_diproses', 'sudah_diperbaiki', 'tindaklanjuti', 'selesai'])->default('baru')->comment('Status temuan');
            $table->text('foto_path')->nullable()->comment('Path file foto sebagai bukti');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID user yang melaporkan');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null')->comment('ID admin yang menangani');
            $table->text('catatan_admin')->nullable()->comment('Catatan dari admin tentang perbaikan');
            $table->timestamp('tanggal_temuan')->useCurrent()->comment('Tanggal temuan ditemukan');
            $table->timestamp('tanggal_ditindaklanjuti')->nullable()->comment('Tanggal mulai ditindaklanjuti');
            $table->timestamp('tanggal_selesai')->nullable()->comment('Tanggal perbaikan selesai');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('admin_id');
            $table->index('status');
            $table->index('urgensi');
            $table->index('tanggal_temuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temuan');
    }
};
