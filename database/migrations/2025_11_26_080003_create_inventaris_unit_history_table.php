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
        Schema::create('inventaris_unit_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_detail_unit_id')->constrained('inventaris_detail_units')->onDelete('cascade');
            $table->enum('jenis_aktivitas', [
                'input', 
                'pinjam', 
                'kembali', 
                'maintenance', 
                'perbaikan', 
                'update_kondisi', 
                'pindah_lokasi', 
                'rusak', 
                'hilang',
                'hapus'
            ])->comment('Jenis aktivitas yang dilakukan pada unit');
            
            $table->string('kondisi_sebelum', 50)->nullable();
            $table->string('kondisi_sesudah', 50)->nullable();
            $table->string('status_sebelum', 50)->nullable();
            $table->string('status_sesudah', 50)->nullable();
            $table->string('lokasi_sebelum')->nullable();
            $table->string('lokasi_sesudah')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->unsignedBigInteger('referensi_id')->nullable()->comment('ID dari tabel terkait (peminjaman/pengembalian/dll)');
            $table->string('referensi_type', 100)->nullable()->comment('Tipe referensi: peminjaman, pengembalian, dll');
            
            $table->foreignId('dilakukan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('inventaris_detail_unit_id');
            $table->index('jenis_aktivitas');
            $table->index(['referensi_id', 'referensi_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_unit_history');
    }
};
