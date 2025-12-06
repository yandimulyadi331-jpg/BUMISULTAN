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
        Schema::table('administrasi', function (Blueprint $table) {
            // Field khusus untuk undangan masuk/keluar
            $table->string('nama_acara')->nullable()->after('perihal'); // Nama acara undangan
            $table->date('tanggal_acara_mulai')->nullable()->after('nama_acara'); // Tanggal mulai acara
            $table->date('tanggal_acara_selesai')->nullable()->after('tanggal_acara_mulai'); // Tanggal selesai acara
            $table->time('waktu_acara_mulai')->nullable()->after('tanggal_acara_selesai'); // Waktu mulai (HH:MM)
            $table->time('waktu_acara_selesai')->nullable()->after('waktu_acara_mulai'); // Waktu selesai (HH:MM)
            $table->string('lokasi_acara')->nullable()->after('waktu_acara_selesai'); // Nama tempat/gedung
            $table->text('alamat_acara')->nullable()->after('lokasi_acara'); // Alamat lengkap
            $table->string('dress_code')->nullable()->after('alamat_acara'); // Dress code acara
            $table->text('catatan_acara')->nullable()->after('dress_code'); // Catatan khusus acara
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrasi', function (Blueprint $table) {
            $table->dropColumn([
                'nama_acara',
                'tanggal_acara_mulai',
                'tanggal_acara_selesai',
                'waktu_acara_mulai',
                'waktu_acara_selesai',
                'lokasi_acara',
                'alamat_acara',
                'dress_code',
                'catatan_acara'
            ]);
        });
    }
};
