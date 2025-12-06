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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pinjaman')->unique(); // Nomor otomatis: PNJ-202511-0001
            
            // Kategori Peminjam
            $table->enum('kategori_peminjam', ['crew', 'non_crew']);
            
            // Data Peminjam (Crew)
            $table->char('karyawan_id', 9)->nullable(); // NIK karyawan
            $table->foreign('karyawan_id')->references('nik')->on('karyawan')->onDelete('cascade');
            
            // Data Peminjam (Non-Crew)
            $table->string('nama_peminjam')->nullable(); // Untuk non-crew
            $table->string('nik_peminjam')->nullable(); // NIK KTP
            $table->text('alamat_peminjam')->nullable();
            $table->string('no_telp_peminjam')->nullable();
            $table->string('pekerjaan_peminjam')->nullable();
            
            // Data Pinjaman
            $table->date('tanggal_pengajuan');
            $table->decimal('jumlah_pengajuan', 15, 2); // Jumlah yang diajukan
            $table->decimal('jumlah_disetujui', 15, 2)->nullable(); // Bisa berbeda dari pengajuan
            $table->text('tujuan_pinjaman'); // Keperluan apa
            $table->integer('tenor_bulan'); // Jangka waktu cicilan (bulan)
            $table->decimal('bunga_persen', 5, 2)->default(0); // Bunga per tahun (%)
            $table->enum('tipe_bunga', ['flat', 'efektif'])->default('flat'); // Metode perhitungan bunga
            
            // Perhitungan Cicilan
            $table->decimal('total_pokok', 15, 2)->default(0); // Total pokok yang harus dibayar
            $table->decimal('total_bunga', 15, 2)->default(0); // Total bunga
            $table->decimal('total_pinjaman', 15, 2)->default(0); // Pokok + Bunga
            $table->decimal('cicilan_per_bulan', 15, 2)->default(0); // Angsuran per bulan
            
            // Status Approval
            $table->enum('status', [
                'pengajuan',      // Baru diajukan
                'review',         // Sedang ditinjau
                'disetujui',      // Disetujui, belum dicairkan
                'ditolak',        // Ditolak
                'dicairkan',      // Sudah dicairkan
                'berjalan',       // Sedang dicicil
                'lunas',          // Sudah lunas
                'dibatalkan'      // Dibatalkan
            ])->default('pengajuan');
            
            // Approval Workflow
            $table->foreignId('diajukan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('direview_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_review')->nullable();
            $table->text('catatan_review')->nullable();
            
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->text('catatan_persetujuan')->nullable();
            
            // Pencairan
            $table->date('tanggal_pencairan')->nullable();
            $table->foreignId('dicairkan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->string('metode_pencairan')->nullable(); // Transfer/Tunai
            $table->string('no_rekening_tujuan')->nullable();
            $table->string('nama_bank')->nullable();
            $table->text('bukti_pencairan')->nullable(); // Path file bukti transfer
            
            // Tracking Pembayaran
            $table->decimal('total_terbayar', 15, 2)->default(0);
            $table->decimal('sisa_pinjaman', 15, 2)->default(0);
            $table->date('tanggal_jatuh_tempo_pertama')->nullable();
            $table->date('tanggal_jatuh_tempo_terakhir')->nullable();
            $table->date('tanggal_lunas')->nullable();
            
            // Denda & Keterlambatan
            $table->decimal('denda_keterlambatan', 15, 2)->default(0);
            $table->integer('hari_telat')->default(0);
            
            // Dokumen Pendukung
            $table->text('dokumen_ktp')->nullable(); // Upload KTP
            $table->text('dokumen_slip_gaji')->nullable(); // Slip gaji (untuk crew)
            $table->text('dokumen_pendukung_lain')->nullable(); // Dokumen lain
            
            // Data Jaminan / Agunan (optional)
            $table->string('jenis_jaminan')->nullable(); // bpkb, sertifikat, elektronik, lainnya
            $table->string('nomor_jaminan')->nullable(); // Nomor BPKB, Sertifikat, dll
            $table->text('deskripsi_jaminan')->nullable(); // Deskripsi detail jaminan
            $table->decimal('nilai_jaminan', 15, 2)->nullable(); // Nilai taksiran
            $table->string('atas_nama_jaminan')->nullable(); // Kepemilikan jaminan
            $table->string('kondisi_jaminan')->nullable(); // baru, bekas_baik, bekas_cukup
            $table->text('keterangan_jaminan')->nullable(); // Info tambahan jaminan
            
            // Penjamin (optional)
            $table->string('nama_penjamin')->nullable();
            $table->string('hubungan_penjamin')->nullable();
            $table->string('no_telp_penjamin')->nullable();
            $table->text('alamat_penjamin')->nullable();
            
            // Keterangan
            $table->text('keterangan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            
            // Soft Deletes
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['kategori_peminjam', 'status']);
            $table->index('tanggal_pengajuan');
            $table->index('nomor_pinjaman');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
