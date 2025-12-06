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
        if (!Schema::hasTable('distribusi_hadiah')) {
            Schema::create('distribusi_hadiah', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_distribusi', 50)->unique()->comment('Format: DH-TAHUN-BULAN-URUT');
                $table->foreignId('jamaah_id')->constrained('jamaah_majlis_taklim')->onDelete('cascade');
                $table->foreignId('hadiah_id')->constrained('hadiah_majlis_taklim')->onDelete('cascade');
                $table->date('tanggal_distribusi');
                $table->integer('jumlah')->default(1);
                $table->string('ukuran_diterima', 20)->nullable();
                $table->string('warna_diterima', 50)->nullable();
                $table->string('penerima', 100)->comment('Nama yang menerima jika bukan jamaah langsung');
                $table->text('foto_bukti')->nullable()->comment('Foto bukti penerimaan');
                $table->text('tanda_tangan')->nullable()->comment('Tanda tangan digital/scan');
                $table->enum('status_distribusi', ['pending', 'diterima', 'ditolak'])->default('diterima');
                $table->text('keterangan')->nullable();
                $table->string('petugas_distribusi', 100)->nullable()->comment('Nama petugas yang membagikan');
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa dan mencegah duplikasi
                $table->index(['jamaah_id', 'hadiah_id', 'tanggal_distribusi']);
                $table->index('tanggal_distribusi');
                $table->index('nomor_distribusi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('distribusi_hadiah');
    }
};
