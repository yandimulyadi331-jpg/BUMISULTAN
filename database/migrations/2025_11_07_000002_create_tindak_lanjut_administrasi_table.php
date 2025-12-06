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
        Schema::create('tindak_lanjut_administrasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('administrasi_id');
            $table->string('kode_tindak_lanjut')->unique(); // TLJ-001
            $table->enum('jenis_tindak_lanjut', [
                'pencairan_dana', // Untuk proposal
                'disposisi', // Untuk surat masuk
                'konfirmasi_terima', // Untuk paket masuk
                'konfirmasi_kirim', // Untuk paket keluar
                'rapat_pembahasan', // Untuk undangan
                'penerbitan_sk', // Untuk surat keputusan
                'tandatangan', // Untuk dokumen yang perlu TTD
                'verifikasi', // Untuk verifikasi dokumen
                'approval', // Untuk persetujuan
                'revisi', // Untuk revisi dokumen
                'arsip', // Untuk pengarsipan
                'lainnya'
            ]);
            $table->string('judul_tindak_lanjut');
            $table->text('deskripsi_tindak_lanjut')->nullable();
            $table->enum('status_tindak_lanjut', ['pending', 'proses', 'selesai', 'ditolak'])->default('pending');
            
            // Fields untuk Pencairan Dana (Proposal)
            $table->decimal('nominal_pencairan', 15, 2)->nullable();
            $table->string('metode_pencairan')->nullable(); // Transfer, Tunai, Cek
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_penerima_dana')->nullable();
            $table->date('tanggal_pencairan')->nullable();
            $table->string('bukti_pencairan')->nullable(); // Upload bukti transfer
            $table->string('tandatangan_pencairan')->nullable(); // Upload TTD
            
            // Fields untuk Disposisi
            $table->string('disposisi_dari')->nullable();
            $table->string('disposisi_kepada')->nullable();
            $table->text('instruksi_disposisi')->nullable();
            $table->date('deadline_disposisi')->nullable();
            
            // Fields untuk Konfirmasi Paket
            $table->string('nama_penerima_paket')->nullable();
            $table->string('foto_paket')->nullable();
            $table->dateTime('waktu_terima_paket')->nullable();
            $table->string('kondisi_paket')->nullable(); // Baik, Rusak, Tidak Lengkap
            $table->string('resi_pengiriman')->nullable();
            
            // Fields untuk Rapat
            $table->dateTime('waktu_rapat')->nullable();
            $table->string('tempat_rapat')->nullable();
            $table->text('peserta_rapat')->nullable(); // JSON array
            $table->text('hasil_rapat')->nullable();
            $table->string('notulen_rapat')->nullable(); // Upload file
            
            // Fields untuk Penandatanganan
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->date('tanggal_tandatangan')->nullable();
            $table->string('file_dokumen_ttd')->nullable(); // Upload dokumen yang sudah TTD
            
            // Fields untuk Verifikasi/Approval
            $table->string('verifikator')->nullable();
            $table->date('tanggal_verifikasi')->nullable();
            $table->enum('hasil_verifikasi', ['disetujui', 'ditolak', 'revisi'])->nullable();
            $table->text('catatan_verifikasi')->nullable();
            
            // Generic fields
            $table->text('catatan')->nullable();
            $table->text('lampiran')->nullable(); // JSON array untuk multiple files
            $table->unsignedBigInteger('pic_id')->nullable(); // Person In Charge (karyawan)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key
            $table->foreign('administrasi_id')->references('id')->on('administrasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut_administrasi');
    }
};
