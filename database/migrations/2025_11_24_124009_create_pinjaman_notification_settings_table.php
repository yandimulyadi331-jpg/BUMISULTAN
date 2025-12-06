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
        Schema::create('pinjaman_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('notifikasi_7_hari_aktif')->default(true)->comment('Aktifkan notifikasi H-7');
            $table->boolean('notifikasi_3_hari_aktif')->default(true)->comment('Aktifkan notifikasi H-3');
            $table->boolean('notifikasi_1_hari_aktif')->default(true)->comment('Aktifkan notifikasi H-1 (besok)');
            $table->boolean('notifikasi_hari_ini_aktif')->default(true)->comment('Aktifkan notifikasi hari ini');
            $table->boolean('notifikasi_lewat_tempo_aktif')->default(true)->comment('Aktifkan notifikasi lewat tempo');
            $table->string('jam_kirim')->default('08:00')->comment('Jam pengiriman email (HH:MM)');
            $table->string('email_cc')->nullable()->comment('Email CC untuk semua notifikasi (optional)');
            $table->text('template_tambahan')->nullable()->comment('Pesan tambahan untuk email');
            $table->timestamps();
        });

        // Insert default settings
        DB::table('pinjaman_notification_settings')->insert([
            'notifikasi_7_hari_aktif' => true,
            'notifikasi_3_hari_aktif' => true,
            'notifikasi_1_hari_aktif' => true,
            'notifikasi_hari_ini_aktif' => true,
            'notifikasi_lewat_tempo_aktif' => true,
            'jam_kirim' => '08:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_notification_settings');
    }
};
