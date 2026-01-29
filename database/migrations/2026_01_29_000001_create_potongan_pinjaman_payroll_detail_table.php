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
        Schema::create('potongan_pinjaman_payroll_detail', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('tukang_id')
                  ->constrained('tukangs')
                  ->onDelete('cascade');
            
            $table->foreignId('pinjaman_tukang_id')
                  ->nullable()
                  ->constrained('pinjaman_tukangs')
                  ->onDelete('set null');
            
            // Minggu & Tahun (ISO 8601)
            $table->integer('tahun')->comment('Tahun (2026, 2025, etc)');
            $table->integer('minggu')->comment('Minggu ke- (1-52)');
            
            // Range Tanggal Minggu
            $table->date('tanggal_mulai')->comment('Hari Senin minggu itu');
            $table->date('tanggal_selesai')->comment('Hari Minggu minggu itu');
            
            // Status Potongan
            $table->enum('status_potong', ['DIPOTONG', 'TIDAK_DIPOTONG'])
                  ->default('DIPOTONG')
                  ->comment('Status potongan minggu ini');
            
            // Nominal & Alasan
            $table->decimal('nominal_cicilan', 12, 2)
                  ->default(0)
                  ->comment('Cicilan per minggu');
            
            $table->string('alasan_tidak_potong', 255)
                  ->nullable()
                  ->comment('Alasan jika tidak dipotong (sakit, kebutuhan mendadak, dll)');
            
            // Audit Trail
            $table->string('toggle_by', 100)
                  ->nullable()
                  ->comment('Nama user/admin yang mengubah toggle');
            
            $table->timestamp('toggle_at')
                  ->nullable()
                  ->comment('Waktu toggle diubah');
            
            $table->text('catatan')
                  ->nullable()
                  ->comment('Catatan tambahan');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['tukang_id', 'tahun', 'minggu'], 'idx_tukang_tahun_minggu');
            $table->unique(['tukang_id', 'tahun', 'minggu'], 'uk_tukang_minggu');
            $table->index(['tahun', 'minggu'], 'idx_tahun_minggu');
            $table->index(['status_potong'], 'idx_status_potong');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_pinjaman_payroll_detail');
    }
};
