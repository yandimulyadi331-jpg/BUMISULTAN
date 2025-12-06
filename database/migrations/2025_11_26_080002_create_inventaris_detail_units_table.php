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
        Schema::create('inventaris_detail_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->foreignId('inventaris_unit_id')->nullable()->constrained('inventaris_units')->onDelete('set null')->comment('Link ke batch (optional)');
            $table->string('kode_unit', 50)->unique()->comment('Kode unik per unit, contoh: INV-00001-U001');
            $table->string('nomor_seri_unit', 100)->nullable()->comment('Serial number fisik pada barang');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('status', ['tersedia', 'dipinjam', 'maintenance', 'rusak', 'hilang'])->default('tersedia');
            $table->string('lokasi_saat_ini')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->decimal('harga_perolehan', 15, 2)->nullable();
            
            // Tracking peminjaman aktif
            $table->string('dipinjam_oleh')->nullable()->comment('Nama peminjam saat ini');
            $table->date('tanggal_pinjam')->nullable();
            $table->foreignId('peminjaman_inventaris_id')->nullable()->constrained('peminjaman_inventaris')->onDelete('set null');
            
            // Additional info
            $table->string('foto_unit')->nullable();
            $table->text('catatan_kondisi')->nullable();
            $table->date('terakhir_maintenance')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('inventaris_id');
            $table->index('inventaris_unit_id');
            $table->index('kode_unit');
            $table->index('status');
            $table->index('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_detail_units');
    }
};
