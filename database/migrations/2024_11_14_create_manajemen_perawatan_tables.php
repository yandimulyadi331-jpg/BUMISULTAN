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
        // Tabel Master Template Checklist
        Schema::create('master_perawatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_periode', ['harian', 'mingguan', 'bulanan', 'tahunan']);
            $table->integer('urutan')->default(0); // untuk sorting
            $table->enum('kategori', ['kebersihan', 'perawatan_rutin', 'pengecekan', 'lainnya'])->default('kebersihan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Log Eksekusi Checklist (History lengkap)
        Schema::create('perawatan_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_perawatan_id')->constrained('master_perawatan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // siapa yang execute
            $table->date('tanggal_eksekusi');
            $table->time('waktu_eksekusi');
            $table->enum('status', ['completed', 'skipped'])->default('completed');
            $table->text('catatan')->nullable();
            $table->string('foto_bukti')->nullable(); // opsional untuk dokumentasi
            $table->string('periode_key'); // format: harian_2024-11-14, mingguan_2024-W46, bulanan_2024-11, tahunan_2024
            $table->timestamps();
            
            $table->index(['tanggal_eksekusi', 'periode_key']);
        });

        // Tabel Laporan Perawatan (Generate setelah semua checklist selesai)
        Schema::create('perawatan_laporan', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_laporan', ['harian', 'mingguan', 'bulanan', 'tahunan']);
            $table->string('periode_key'); // harian_2024-11-14, dll
            $table->date('tanggal_laporan');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->integer('total_checklist');
            $table->integer('total_completed');
            $table->text('ringkasan')->nullable();
            $table->string('file_pdf')->nullable();
            $table->timestamps();
            
            $table->unique(['tipe_laporan', 'periode_key']);
        });

        // Tabel untuk track status checklist per periode (untuk validasi sebelum generate laporan)
        Schema::create('perawatan_status_periode', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_periode', ['harian', 'mingguan', 'bulanan', 'tahunan']);
            $table->string('periode_key');
            $table->date('periode_start');
            $table->date('periode_end');
            $table->integer('total_checklist');
            $table->integer('total_completed')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['tipe_periode', 'periode_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawatan_status_periode');
        Schema::dropIfExists('perawatan_laporan');
        Schema::dropIfExists('perawatan_log');
        Schema::dropIfExists('master_perawatan');
    }
};
