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
        Schema::table('peminjaman_inventaris', function (Blueprint $table) {
            // Cek apakah kolom foto_barang_pinjam ada, rename ke foto_barang
            if (Schema::hasColumn('peminjaman_inventaris', 'foto_barang_pinjam')) {
                $table->renameColumn('foto_barang_pinjam', 'foto_barang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_inventaris', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_inventaris', 'foto_barang')) {
                $table->renameColumn('foto_barang', 'foto_barang_pinjam');
            }
        });
    }
};
