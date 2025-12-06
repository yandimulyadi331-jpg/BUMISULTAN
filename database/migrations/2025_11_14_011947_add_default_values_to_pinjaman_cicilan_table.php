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
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            // Ubah kolom jumlah_pokok dan jumlah_bunga agar punya default value
            $table->decimal('jumlah_pokok', 15, 2)->default(0)->change();
            $table->decimal('jumlah_bunga', 15, 2)->default(0)->change();
            $table->decimal('jumlah_dibayar', 15, 2)->default(0)->change();
            $table->decimal('sisa_cicilan', 15, 2)->default(0)->change();
            
            // Tambah kolom jumlah_bayar untuk kompatibilitas
            if (!Schema::hasColumn('pinjaman_cicilan', 'jumlah_bayar')) {
                $table->decimal('jumlah_bayar', 15, 2)->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            // Rollback tidak perlu dilakukan karena hanya menambah default value
        });
    }
};
