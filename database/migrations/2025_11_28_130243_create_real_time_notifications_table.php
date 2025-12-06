<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('real_time_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul notifikasi
            $table->text('message'); // Isi pesan notifikasi
            $table->string('type'); // Tipe aktivitas: presensi, kendaraan, pinjaman, undangan, dll
            $table->string('icon')->nullable(); // Icon untuk notifikasi
            $table->string('color')->default('primary'); // Warna badge/indicator
            $table->json('data')->nullable(); // Data tambahan dalam format JSON
            $table->string('user_id')->nullable(); // ID user terkait (opsional)
            $table->string('reference_id')->nullable(); // ID referensi ke tabel lain
            $table->string('reference_table')->nullable(); // Nama tabel referensi
            $table->boolean('is_read')->default(false); // Status dibaca
            $table->date('tanggal'); // Tanggal untuk reset harian
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['tanggal', 'created_at']);
            $table->index(['type', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_time_notifications');
    }
};
