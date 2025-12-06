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
        Schema::create('pembayaran_gaji_tukangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tukang_id')->constrained('tukangs')->onDelete('cascade');
            
            // Periode pembayaran
            $table->date('periode_mulai'); // Sabtu minggu lalu
            $table->date('periode_akhir'); // Jumat minggu ini
            $table->dateTime('tanggal_bayar'); // Kapan dibayar (Kamis)
            
            // Rincian Upah
            $table->decimal('total_upah_harian', 15, 2)->default(0);
            $table->decimal('total_upah_lembur', 15, 2)->default(0);
            $table->decimal('lembur_cash_terbayar', 15, 2)->default(0); // Yang sudah dibayar cash
            $table->decimal('total_kotor', 15, 2)->default(0);
            
            // Potongan
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->json('rincian_potongan')->nullable(); // Detail semua potongan
            
            // Nett
            $table->decimal('total_nett', 15, 2)->default(0); // Yang benar-benar diterima
            
            // TTD Digital
            $table->longText('tanda_tangan_base64')->nullable(); // Image TTD base64
            $table->string('tanda_tangan_path')->nullable(); // Path file TTD jika disimpan
            $table->string('ip_address', 50)->nullable(); // IP device yang TTD
            $table->text('device_info')->nullable(); // Info browser/device
            
            // Status & Audit
            $table->enum('status', ['pending', 'lunas', 'dibatalkan'])->default('pending');
            $table->string('dibayar_oleh', 100)->nullable(); // Admin yang bayar
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['tukang_id', 'periode_mulai', 'periode_akhir'], 'idx_tukang_periode');
            $table->index('tanggal_bayar');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_gaji_tukangs');
    }
};
