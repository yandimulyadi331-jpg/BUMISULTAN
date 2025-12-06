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
        Schema::create('keuangan_santri_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('keuangan_santri_categories')->onDelete('restrict');
            $table->string('kode_transaksi')->unique(); // TRX-20250108-001
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2); // Nominal transaksi
            $table->decimal('saldo_sebelum', 15, 2)->default(0); // Saldo sebelum transaksi
            $table->decimal('saldo_sesudah', 15, 2)->default(0); // Saldo setelah transaksi
            $table->date('tanggal_transaksi');
            $table->string('deskripsi'); // Deskripsi transaksi
            $table->text('catatan')->nullable(); // Catatan tambahan
            $table->string('bukti_file')->nullable(); // Upload bukti (foto/pdf)
            $table->boolean('is_verified')->default(false); // Status verifikasi
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->string('metode_pembayaran')->nullable(); // Tunai, Transfer, dll
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes untuk performa
            $table->index('tanggal_transaksi');
            $table->index('jenis');
            $table->index('kode_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_santri_transactions');
    }
};
