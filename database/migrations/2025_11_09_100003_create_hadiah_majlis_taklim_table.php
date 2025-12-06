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
        if (!Schema::hasTable('hadiah_majlis_taklim')) {
            Schema::create('hadiah_majlis_taklim', function (Blueprint $table) {
                $table->id();
                $table->string('kode_hadiah', 50)->unique();
                $table->string('nama_hadiah', 100);
                $table->enum('jenis_hadiah', ['sarung', 'peci', 'gamis', 'mukena', 'tasbih', 'sajadah', 'al_quran', 'buku', 'lainnya'])->default('lainnya');
                $table->string('ukuran', 20)->nullable()->comment('S, M, L, XL, XXL, atau nomor');
                $table->string('warna', 50)->nullable();
                $table->text('deskripsi')->nullable();
                $table->integer('stok_awal')->default(0);
                $table->integer('stok_tersedia')->default(0);
                $table->integer('stok_terbagikan')->default(0);
                $table->decimal('nilai_hadiah', 15, 2)->default(0)->comment('Nilai/harga hadiah');
                $table->date('tanggal_pengadaan')->nullable();
                $table->string('supplier', 100)->nullable();
                $table->string('foto', 255)->nullable();
                $table->enum('status', ['tersedia', 'habis', 'tidak_aktif'])->default('tersedia');
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index('kode_hadiah');
                $table->index('jenis_hadiah');
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('hadiah_majlis_taklim');
    }
};
