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
        if (!Schema::hasTable('undian_umroh')) {
            Schema::create('undian_umroh', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_undian', 50)->unique()->comment('Format: UU-TAHUN-URUT');
                $table->string('nama_program', 100);
                $table->text('deskripsi')->nullable();
                $table->date('tanggal_undian');
                $table->date('periode_keberangkatan_dari')->nullable();
                $table->date('periode_keberangkatan_sampai')->nullable();
                $table->integer('jumlah_pemenang')->default(1);
                $table->integer('minimal_kehadiran')->default(0)->comment('Syarat minimal kehadiran untuk ikut undian');
                $table->enum('status_undian', ['draft', 'aktif', 'selesai', 'batal'])->default('draft');
                $table->text('syarat_ketentuan')->nullable();
                $table->decimal('biaya_program', 15, 2)->default(0);
                $table->string('sponsor', 100)->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index('nomor_undian');
                $table->index('tanggal_undian');
                $table->index('status_undian');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TIDAK AKAN DROP TABLE - Sesuai instruksi untuk tidak menghapus data
        // Schema::dropIfExists('undian_umroh');
    }
};
