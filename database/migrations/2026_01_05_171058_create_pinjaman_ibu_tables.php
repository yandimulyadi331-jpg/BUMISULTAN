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
        // Tabel pinjaman_ibu (Pinjaman via Ibu - Terpisah dari keuangan perusahaan)
        Schema::create('pinjaman_ibu', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pinjaman')->unique(); // Nomor otomatis: PNJI-202601-0001
            
            // Kategori Peminjam
            $table->enum('kategori_peminjam', ['crew', 'non_crew']);
            
            // Data Peminjam (Crew)
            $table->char('karyawan_id', 9)->nullable();
            $table->foreign('karyawan_id')->references('nik')->on('karyawan')->onDelete('cascade');
            
            // Data Peminjam (Non-Crew)
            $table->string('nama_peminjam')->nullable();
            $table->string('nama_peminjam_lengkap')->nullable();
            $table->string('nik_peminjam')->nullable();
            $table->text('alamat_peminjam')->nullable();
            $table->string('no_telp_peminjam')->nullable();
            $table->string('email_peminjam')->nullable();
            $table->string('pekerjaan_peminjam')->nullable();
            
            // Data Pinjaman
            $table->date('tanggal_pengajuan');
            $table->decimal('jumlah_pengajuan', 15, 2);
            $table->decimal('jumlah_disetujui', 15, 2)->nullable();
            $table->text('tujuan_pinjaman');
            $table->integer('tenor_bulan');
            $table->integer('tenor')->nullable();
            $table->integer('tanggal_jatuh_tempo_setiap_bulan')->nullable();
            $table->decimal('bunga_persen', 5, 2)->default(0);
            $table->enum('tipe_bunga', ['flat', 'efektif'])->default('flat');
            
            // Perhitungan Cicilan
            $table->decimal('total_pokok', 15, 2)->default(0);
            $table->decimal('total_bunga', 15, 2)->default(0);
            $table->decimal('total_pinjaman', 15, 2)->default(0);
            $table->decimal('cicilan_per_bulan', 15, 2)->default(0);
            
            // Status Approval
            $table->enum('status', [
                'pengajuan', 'review', 'disetujui', 'ditolak', 
                'dicairkan', 'berjalan', 'lunas', 'dibatalkan'
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
            $table->string('metode_pencairan')->nullable();
            $table->string('no_rekening_tujuan')->nullable();
            $table->string('nama_bank')->nullable();
            $table->text('bukti_pencairan')->nullable();
            
            // Tracking Pembayaran
            $table->decimal('total_terbayar', 15, 2)->default(0);
            $table->decimal('sisa_pinjaman', 15, 2)->default(0);
            $table->decimal('persentase_pembayaran', 5, 2)->default(0);
            $table->date('tanggal_jatuh_tempo_pertama')->nullable();
            $table->date('tanggal_jatuh_tempo_terakhir')->nullable();
            $table->date('tanggal_lunas')->nullable();
            $table->integer('hari_telat')->default(0);
            
            // Dokumen Pendukung
            $table->text('dokumen_ktp')->nullable();
            $table->text('dokumen_slip_gaji')->nullable();
            $table->text('dokumen_pendukung_lain')->nullable();
            
            // Data Jaminan
            $table->string('jenis_jaminan')->nullable();
            $table->string('nomor_jaminan')->nullable();
            $table->text('deskripsi_jaminan')->nullable();
            $table->decimal('nilai_jaminan', 15, 2)->nullable();
            $table->string('atas_nama_jaminan')->nullable();
            $table->string('kondisi_jaminan')->nullable();
            $table->text('keterangan_jaminan')->nullable();
            
            // Penjamin
            $table->string('nama_penjamin')->nullable();
            $table->string('hubungan_penjamin')->nullable();
            $table->string('no_telp_penjamin')->nullable();
            $table->text('alamat_penjamin')->nullable();
            
            $table->text('keterangan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['kategori_peminjam', 'status']);
            $table->index('tanggal_pengajuan');
            $table->index('nomor_pinjaman');
            $table->index('status');
        });

        // Tabel pinjaman_ibu_cicilan
        Schema::create('pinjaman_ibu_cicilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_ibu_id')->constrained('pinjaman_ibu')->onDelete('cascade');
            
            $table->integer('cicilan_ke');
            $table->date('tanggal_jatuh_tempo');
            $table->decimal('jumlah_pokok', 15, 2);
            $table->decimal('jumlah_bunga', 15, 2);
            $table->decimal('jumlah_cicilan', 15, 2);
            
            $table->enum('status', ['belum_bayar', 'sebagian', 'lunas', 'terlambat'])->default('belum_bayar');
            
            $table->boolean('is_ditunda')->default(false);
            $table->date('tanggal_ditunda')->nullable();
            $table->foreignId('ditunda_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('alasan_ditunda')->nullable();
            $table->boolean('is_hasil_tunda')->default(false);
            $table->unsignedBigInteger('cicilan_ditunda_id')->nullable();
            
            $table->date('tanggal_bayar')->nullable();
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_cicilan', 15, 2)->default(0);
            $table->integer('hari_terlambat')->default(0);
            
            $table->string('metode_pembayaran')->nullable();
            $table->string('no_referensi')->nullable();
            $table->text('bukti_pembayaran')->nullable();
            
            $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('keterangan')->nullable();
            
            $table->boolean('auto_potong_gaji')->default(false);
            $table->string('kode_penyesuaian_gaji')->nullable();
            $table->boolean('sudah_dipotong')->default(false);
            $table->date('tanggal_dipotong')->nullable();
            
            $table->timestamps();
            
            $table->index(['pinjaman_ibu_id', 'cicilan_ke']);
            $table->index('tanggal_jatuh_tempo');
            $table->index('status');
        });

        // Tabel pinjaman_ibu_history
        Schema::create('pinjaman_ibu_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_ibu_id')->constrained('pinjaman_ibu')->onDelete('cascade');
            
            $table->string('aksi');
            $table->string('status_lama')->nullable();
            $table->string('status_baru')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('data_perubahan')->nullable();
            
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            
            $table->timestamps();
            
            $table->index('pinjaman_ibu_id');
            $table->index('aksi');
        });

        // Tabel pinjaman_ibu_email_notifications
        Schema::create('pinjaman_ibu_email_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_ibu_id')->constrained('pinjaman_ibu')->onDelete('cascade');
            
            $table->string('tipe_notifikasi');
            $table->string('email_tujuan');
            $table->text('subject');
            $table->boolean('terkirim')->default(false);
            $table->timestamp('tanggal_kirim')->nullable();
            $table->text('error_message')->nullable();
            
            $table->timestamps();
            
            $table->index('pinjaman_ibu_id');
            $table->index('tipe_notifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_ibu_email_notifications');
        Schema::dropIfExists('pinjaman_ibu_history');
        Schema::dropIfExists('pinjaman_ibu_cicilan');
        Schema::dropIfExists('pinjaman_ibu');
    }
};
