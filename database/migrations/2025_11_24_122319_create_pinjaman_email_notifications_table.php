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
        Schema::create('pinjaman_email_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->onDelete('cascade');
            $table->string('email_tujuan');
            $table->enum('tipe_notifikasi', ['jatuh_tempo_hari_ini', 'jatuh_tempo_besok', 'jatuh_tempo_3_hari', 'jatuh_tempo_7_hari', 'sudah_lewat_jatuh_tempo']);
            $table->integer('hari_sebelum_jatuh_tempo')->nullable()->comment('Jumlah hari sebelum jatuh tempo saat notif dikirim');
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamps();
            
            // Index untuk query yang sering digunakan (dengan nama custom yang lebih pendek)
            $table->index(['pinjaman_id', 'tipe_notifikasi', 'status'], 'idx_pinjaman_notif_status');
            $table->index(['tanggal_jatuh_tempo', 'status'], 'idx_tgl_jt_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_email_notifications');
    }
};
