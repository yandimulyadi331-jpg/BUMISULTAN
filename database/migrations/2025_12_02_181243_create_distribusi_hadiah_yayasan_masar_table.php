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
        if (!Schema::hasTable('distribusi_hadiah_yayasan_masar')) {
            Schema::create('distribusi_hadiah_yayasan_masar', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_distribusi', 50)->unique()->comment('Format: DSY-TANGGAL-URUT');
                $table->foreignId('jamaah_id')->nullable()->constrained('yayasan_masar')->onDelete('cascade');
                $table->foreignId('hadiah_id')->constrained('hadiah_yayasan_masar')->onDelete('cascade');
                $table->date('tanggal_distribusi');
                $table->integer('jumlah')->default(1);
                $table->string('ukuran', 20)->nullable();
                $table->json('ukuran_breakdown')->nullable()->comment('Detail breakdown ukuran jika multiple');
                $table->enum('metode_distribusi', ['langsung', 'undian', 'prestasi', 'kehadiran'])->default('langsung');
                $table->string('penerima', 100)->comment('Nama penerima');
                $table->string('petugas_distribusi', 100)->nullable()->comment('Nama petugas yang membagikan');
                $table->enum('status_distribusi', ['pending', 'diterima', 'ditolak'])->default('diterima');
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa dengan nama lebih pendek
                $table->index(['jamaah_id', 'hadiah_id'], 'idx_dist_jamaah_hadiah');
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
        // TIDAK AKAN DROP TABLE
        // Schema::dropIfExists('distribusi_hadiah_yayasan_masar');
    }
};
