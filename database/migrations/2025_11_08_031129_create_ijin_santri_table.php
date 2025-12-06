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
        Schema::create('ijin_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->date('tanggal_ijin');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->text('alasan_ijin');
            $table->string('nomor_surat')->unique();
            
            // Status: pending, ttd_ustadz, dipulangkan, kembali
            $table->enum('status', ['pending', 'ttd_ustadz', 'dipulangkan', 'kembali'])->default('pending');
            
            // Verifikasi tahapan
            $table->timestamp('ttd_ustadz_at')->nullable();
            $table->foreignId('ttd_ustadz_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verifikasi_pulang_at')->nullable();
            $table->foreignId('verifikasi_pulang_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verifikasi_kembali_at')->nullable();
            $table->foreignId('verifikasi_kembali_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Upload foto surat dengan TTD ortu
            $table->string('foto_surat_ttd_ortu')->nullable();
            
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ijin_santri');
    }
};
