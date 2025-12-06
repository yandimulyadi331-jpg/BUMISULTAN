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
        Schema::create('administrasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_administrasi')->unique(); // ADM-001
            $table->enum('jenis_administrasi', [
                'surat_masuk',
                'surat_keluar', 
                'undangan_masuk',
                'undangan_keluar',
                'proposal_masuk',
                'proposal_keluar',
                'paket_masuk',
                'paket_keluar',
                'memo_internal',
                'sk_internal',
                'surat_tugas',
                'surat_keputusan',
                'nota_dinas',
                'berita_acara',
                'kontrak',
                'mou',
                'dokumen_lainnya'
            ]);
            $table->string('nomor_surat')->nullable(); // Nomor surat/dokumen
            $table->string('pengirim')->nullable(); // Untuk surat/paket masuk
            $table->string('penerima')->nullable(); // Untuk surat/paket keluar
            $table->string('perihal');
            $table->text('ringkasan')->nullable();
            $table->date('tanggal_surat')->nullable(); // Tanggal pada surat
            $table->dateTime('tanggal_terima')->nullable(); // Tanggal diterima (untuk masuk)
            $table->dateTime('tanggal_kirim')->nullable(); // Tanggal dikirim (untuk keluar)
            $table->enum('prioritas', ['rendah', 'normal', 'tinggi', 'urgent'])->default('normal');
            $table->enum('status', ['pending', 'proses', 'selesai', 'ditolak', 'expired'])->default('pending');
            $table->string('file_dokumen')->nullable(); // PDF, Word, dll
            $table->string('foto')->nullable(); // Foto dokumen
            $table->text('lampiran')->nullable(); // JSON array untuk multiple files
            $table->unsignedBigInteger('divisi_id')->nullable(); // Divisi terkait
            $table->unsignedBigInteger('cabang_id')->nullable(); // Cabang terkait
            $table->string('disposisi_ke')->nullable(); // Nama/bagian yang didisposisikan
            $table->text('catatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrasi');
    }
};
