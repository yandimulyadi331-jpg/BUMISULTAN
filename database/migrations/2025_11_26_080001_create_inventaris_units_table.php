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
        Schema::create('inventaris_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->string('batch_code', 50)->nullable()->comment('Kode batch untuk grouping, contoh: BATCH-001');
            $table->date('tanggal_perolehan')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('harga_perolehan_per_unit', 15, 2)->nullable();
            $table->integer('jumlah_unit_dalam_batch')->default(1)->comment('Jumlah unit dalam batch ini');
            $table->string('lokasi_penyimpanan')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('inventaris_id');
            $table->index('batch_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_units');
    }
};
