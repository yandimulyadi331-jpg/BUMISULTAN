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
        if (!Schema::hasTable('pemenang_undian_umroh')) {
            Schema::create('pemenang_undian_umroh', function (Blueprint $table) {
                $table->id();
                $table->foreignId('undian_id')->constrained('undian_umroh')->onDelete('cascade');
                $table->foreignId('jamaah_id')->constrained('jamaah_majlis_taklim')->onDelete('cascade');
                $table->integer('urutan_pemenang')->comment('Urutan ke berapa sebagai pemenang');
                $table->date('tanggal_pengumuman');
                $table->date('tanggal_keberangkatan')->nullable();
                $table->date('tanggal_kepulangan')->nullable();
                $table->enum('status_keberangkatan', ['belum_berangkat', 'sudah_berangkat', 'selesai', 'batal'])->default('belum_berangkat');
                $table->text('dokumentasi')->nullable()->comment('Link foto/video dokumentasi');
                $table->text('testimoni')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index(['undian_id', 'jamaah_id']);
                $table->index('status_keberangkatan');
                $table->unique(['undian_id', 'jamaah_id'], 'unique_undian_jamaah');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('pemenang_undian_umroh');
    }
};
