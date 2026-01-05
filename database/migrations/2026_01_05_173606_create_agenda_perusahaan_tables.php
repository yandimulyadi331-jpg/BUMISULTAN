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
        // Tabel utama agenda perusahaan
        Schema::create('agenda_perusahaan', function (Blueprint $table) {
            $table->id();
            
            // Informasi Dasar
            $table->string('nomor_agenda', 50)->unique()->comment('AGD-YYYYMM-0001');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_agenda', ['undangan', 'rapat', 'kunjungan', 'event', 'deadline', 'lainnya']);
            $table->enum('kategori_agenda', ['internal', 'eksternal', 'pemerintah', 'vendor', 'client', 'umum'])->default('internal');
            
            // Waktu & Tempat
            $table->date('tanggal_mulai');
            $table->time('waktu_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->integer('durasi_menit')->nullable()->comment('Estimasi durasi dalam menit');
            $table->string('lokasi')->nullable();
            $table->text('lokasi_detail')->nullable()->comment('Alamat lengkap, link maps, dll');
            $table->boolean('is_online')->default(false);
            $table->text('link_meeting')->nullable()->comment('Zoom, GMeet, Teams, dll');
            
            // Detail Acara
            $table->string('penyelenggara')->nullable()->comment('Nama instansi/orang yang mengadakan');
            $table->string('contact_person')->nullable();
            $table->string('no_telp_cp', 20)->nullable();
            $table->string('email_cp')->nullable();
            
            // Dress Code & Requirements
            $table->enum('dress_code', ['formal', 'semi_formal', 'casual', 'bebas_rapi', 'batik', 'khusus'])->default('bebas_rapi');
            $table->text('dress_code_keterangan')->nullable()->comment('Detail dress code jika khusus');
            $table->text('perlengkapan_dibawa')->nullable()->comment('Barang/dokumen yang perlu dibawa');
            
            // Peserta
            $table->text('peserta_internal')->nullable()->comment('JSON array user_id yang hadir');
            $table->text('peserta_eksternal')->nullable()->comment('Nama-nama tamu eksternal');
            $table->integer('jumlah_peserta_estimasi')->nullable();
            
            // Anggaran & Dokumen
            $table->boolean('ada_anggaran')->default(false);
            $table->decimal('nominal_anggaran', 15, 2)->nullable();
            $table->string('sumber_anggaran', 100)->nullable();
            $table->string('dokumen_undangan')->nullable()->comment('File undangan/surat');
            $table->string('dokumen_rundown')->nullable()->comment('File rundown acara');
            $table->string('dokumen_materi')->nullable()->comment('File presentasi/materi');
            $table->text('dokumen_lainnya')->nullable()->comment('JSON array file lainnya');
            
            // Status & Priority
            $table->enum('status', ['draft', 'terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('draft');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang');
            $table->boolean('is_wajib_hadir')->default(false)->comment('Wajib dihadiri pimpinan');
            
            // Reminder Settings
            $table->boolean('reminder_aktif')->default(true);
            $table->boolean('reminder_1_hari')->default(true);
            $table->boolean('reminder_3_jam')->default(true);
            $table->boolean('reminder_30_menit')->default(true);
            $table->integer('reminder_custom_menit')->nullable()->comment('Reminder custom (menit sebelum acara)');
            
            // Hasil & Tindak Lanjut
            $table->enum('kehadiran_konfirmasi', ['belum', 'hadir', 'tidak_hadir', 'diwakilkan'])->default('belum');
            $table->string('nama_perwakilan')->nullable()->comment('Jika diwakilkan');
            $table->text('catatan_kehadiran')->nullable();
            $table->text('hasil_agenda')->nullable()->comment('Kesimpulan/hasil setelah acara');
            $table->text('tindak_lanjut')->nullable()->comment('Action items setelah acara');
            $table->text('foto_dokumentasi')->nullable()->comment('JSON array foto');
            
            // Tracking
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->unsignedBigInteger('diupdate_oleh')->nullable();
            $table->unsignedBigInteger('dibatalkan_oleh')->nullable();
            $table->datetime('tanggal_dibatalkan')->nullable();
            $table->text('alasan_dibatalkan')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('set null');
            $table->foreign('diupdate_oleh')->references('id')->on('users')->onDelete('set null');
            $table->foreign('dibatalkan_oleh')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('tanggal_mulai');
            $table->index('status');
            $table->index('tipe_agenda');
            $table->index('prioritas');
        });

        // Tabel log reminder
        Schema::create('agenda_reminder_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agenda_id');
            
            $table->enum('tipe_reminder', ['1_hari', '3_jam', '30_menit', 'custom']);
            $table->integer('menit_sebelum');
            
            $table->enum('metode_reminder', ['whatsapp', 'email', 'notification', 'sms']);
            $table->string('tujuan')->comment('Nomor/email penerima');
            
            $table->enum('status', ['pending', 'terkirim', 'gagal'])->default('pending');
            $table->datetime('tanggal_kirim')->nullable();
            $table->text('error_message')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('agenda_id')->references('id')->on('agenda_perusahaan')->onDelete('cascade');
        });

        // Tabel history/audit trail
        Schema::create('agenda_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agenda_id');
            
            $table->string('aksi', 100)->comment('created, updated, status_changed, dll');
            $table->text('perubahan')->nullable()->comment('JSON data perubahan');
            $table->text('catatan')->nullable();
            
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('agenda_id')->references('id')->on('agenda_perusahaan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_history');
        Schema::dropIfExists('agenda_reminder_log');
        Schema::dropIfExists('agenda_perusahaan');
    }
};
