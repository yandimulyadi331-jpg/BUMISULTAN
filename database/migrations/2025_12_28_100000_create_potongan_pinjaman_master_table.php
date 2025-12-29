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
        Schema::create('potongan_pinjaman_master', function (Blueprint $table) {
            $table->id();
            $table->char('kode_potongan', 15)->unique()->comment('PPM250001, PPM250002, dst');
            $table->char('nik', 9);
            $table->unsignedBigInteger('pinjaman_id')->nullable()->comment('Referensi ke tabel pinjaman (opsional)');
            
            // Data Potongan
            $table->decimal('jumlah_pinjaman', 15, 2)->comment('Total pinjaman (misal 5jt)');
            $table->decimal('cicilan_per_bulan', 15, 2)->comment('Cicilan per bulan (misal 1jt)');
            $table->integer('jumlah_bulan')->comment('Jumlah bulan cicilan (misal 5 bulan)');
            
            // Periode Berlaku
            $table->smallInteger('bulan_mulai')->comment('Bulan mulai (1-12)');
            $table->integer('tahun_mulai')->comment('Tahun mulai');
            $table->smallInteger('bulan_selesai')->comment('Bulan selesai (auto-calculated)');
            $table->integer('tahun_selesai')->comment('Tahun selesai (auto-calculated)');
            
            // Tracking
            $table->decimal('jumlah_terbayar', 15, 2)->default(0)->comment('Total yang sudah dipotong');
            $table->decimal('sisa_pinjaman', 15, 2)->comment('Sisa yang belum dipotong');
            $table->smallInteger('bulan_terakhir_dipotong')->nullable()->comment('Tracking bulan terakhir dipotong');
            $table->integer('tahun_terakhir_dipotong')->nullable()->comment('Tracking tahun terakhir dipotong');
            $table->integer('cicilan_terbayar')->default(0)->comment('Jumlah cicilan yang sudah dipotong');
            
            // Status
            $table->enum('status', ['aktif', 'selesai', 'ditunda', 'dibatalkan'])->default('aktif');
            $table->date('tanggal_selesai')->nullable()->comment('Auto-set saat lunas');
            
            // Metadata
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->unsignedBigInteger('diupdate_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pinjaman_id')->references('id')->on('pinjaman')->onDelete('set null');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('diupdate_oleh')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('nik');
            $table->index('status');
            $table->index(['bulan_mulai', 'tahun_mulai', 'bulan_selesai', 'tahun_selesai'], 'idx_periode');
            $table->index(['status', 'bulan_mulai', 'tahun_mulai'], 'idx_status_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_pinjaman_master');
    }
};
