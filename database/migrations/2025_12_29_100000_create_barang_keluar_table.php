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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('jenis_barang'); // laundry, perbaikan_sepatu, perbaikan_elektronik, dll
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan')->nullable(); // pcs, pasang, buah, dll
            
            // Informasi pemilik barang
            $table->string('pemilik_barang'); // nama karyawan/departemen
            $table->string('departemen')->nullable();
            $table->string('no_telp_pemilik')->nullable();
            
            // Informasi vendor/tempat jasa
            $table->string('nama_vendor');
            $table->text('alamat_vendor')->nullable();
            $table->string('no_telp_vendor')->nullable();
            $table->string('pic_vendor')->nullable(); // person in charge di vendor
            
            // Tracking tanggal
            $table->dateTime('tanggal_keluar');
            $table->dateTime('estimasi_kembali')->nullable();
            $table->dateTime('tanggal_kembali')->nullable();
            
            // Biaya
            $table->decimal('estimasi_biaya', 15, 2)->default(0);
            $table->decimal('biaya_aktual', 15, 2)->default(0)->nullable();
            
            // Status tracking
            $table->enum('status', [
                'pending',           // Menunggu pengiriman
                'dikirim',          // Sudah dikirim ke vendor
                'proses',           // Sedang dikerjakan vendor
                'selesai_vendor',   // Selesai di vendor, siap diambil
                'diambil',          // Sudah diambil/kembali
                'batal'             // Dibatalkan
            ])->default('pending');
            
            // Kondisi barang
            $table->enum('kondisi_keluar', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->nullable();
            
            // Foto dokumentasi
            $table->json('foto_sebelum')->nullable(); // Array foto sebelum
            $table->json('foto_sesudah')->nullable(); // Array foto setelah selesai
            $table->string('foto_nota')->nullable(); // Foto nota/bukti pembayaran
            
            // Catatan tambahan
            $table->text('catatan_keluar')->nullable();
            $table->text('catatan_kembali')->nullable();
            $table->text('catatan_vendor')->nullable();
            
            // Priority & Rating
            $table->enum('prioritas', ['rendah', 'normal', 'tinggi', 'urgent'])->default('normal');
            $table->integer('rating_vendor')->nullable()->comment('1-5 stars');
            $table->text('review_vendor')->nullable();
            
            // Tracking user
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('diambil_by')->nullable(); // User yang mengambil
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('diambil_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('kode_transaksi');
            $table->index('jenis_barang');
            $table->index('status');
            $table->index('tanggal_keluar');
            $table->index('tanggal_kembali');
            $table->index('nama_vendor');
        });
        
        // Tabel untuk history/log perubahan status
        Schema::create('barang_keluar_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_keluar_id');
            $table->string('status_dari')->nullable();
            $table->string('status_ke');
            $table->text('catatan')->nullable();
            $table->json('foto')->nullable(); // Foto pendukung perubahan status
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            
            $table->foreign('barang_keluar_id')->references('id')->on('barang_keluar')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index('barang_keluar_id');
            $table->index('status_ke');
        });
        
        // Tabel untuk reminder/notifikasi
        Schema::create('barang_keluar_reminder', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_keluar_id');
            $table->dateTime('tanggal_reminder');
            $table->text('pesan_reminder');
            $table->enum('tipe_reminder', ['email', 'wa', 'notifikasi'])->default('notifikasi');
            $table->boolean('sudah_terkirim')->default(false);
            $table->dateTime('tanggal_terkirim')->nullable();
            $table->timestamps();
            
            $table->foreign('barang_keluar_id')->references('id')->on('barang_keluar')->onDelete('cascade');
            
            $table->index('barang_keluar_id');
            $table->index('tanggal_reminder');
            $table->index('sudah_terkirim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar_reminder');
        Schema::dropIfExists('barang_keluar_history');
        Schema::dropIfExists('barang_keluar');
    }
};
