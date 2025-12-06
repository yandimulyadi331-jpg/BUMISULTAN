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
        if (!Schema::hasTable('distribusi_hadiah_masar')) {
            Schema::create('distribusi_hadiah_masar', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_distribusi', 50)->unique();
                $table->foreignId('jamaah_id')->constrained('jamaah_masar')->onDelete('restrict');
                $table->foreignId('hadiah_id')->constrained('hadiah_masar')->onDelete('restrict');
                $table->date('tanggal_distribusi');
                $table->integer('jumlah')->default(1);
                $table->string('ukuran', 20)->nullable();
                $table->string('ukuran_diterima', 20)->nullable();
                $table->string('warna_diterima', 50)->nullable();
                $table->string('penerima', 100)->nullable()->comment('Nama penerima jika bukan jamaah langsung');
                $table->string('foto_bukti', 255)->nullable();
                $table->string('tanda_tangan', 255)->nullable();
                $table->enum('status_distribusi', ['diterima', 'pending', 'dikembalikan'])->default('diterima');
                $table->text('keterangan')->nullable();
                $table->string('petugas_distribusi', 100)->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index('nomor_distribusi');
                $table->index('jamaah_id');
                $table->index('hadiah_id');
                $table->index('tanggal_distribusi');
                $table->index('status_distribusi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('distribusi_hadiah_masar');
    }
};
