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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_inventaris')->unique(); // Kode unik inventaris (INV-001)
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->string('kategori'); // Elektronik, Furniture, Alat Tulis, Olahraga, dll
            $table->unsignedBigInteger('barang_id')->nullable(); // Link ke tabel barang existing
            $table->string('merk')->nullable();
            $table->string('tipe_model')->nullable();
            $table->string('nomor_seri')->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan')->default('unit'); // unit, pcs, set, dll
            $table->decimal('harga_perolehan', 15, 2)->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->string('kondisi')->default('baik'); // baik, rusak ringan, rusak berat
            $table->enum('status', ['tersedia', 'dipinjam', 'maintenance', 'rusak', 'hilang'])->default('tersedia');
            $table->string('lokasi_penyimpanan')->nullable();
            $table->unsignedBigInteger('cabang_id')->nullable();
            $table->string('foto')->nullable();
            $table->text('spesifikasi')->nullable(); // JSON untuk spesifikasi detail
            $table->integer('masa_pakai_bulan')->nullable(); // Estimasi masa pakai
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
