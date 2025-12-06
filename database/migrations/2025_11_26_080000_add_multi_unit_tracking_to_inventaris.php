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
        // Update tabel inventaris - tambah kolom untuk tracking multi-unit
        Schema::table('inventaris', function (Blueprint $table) {
            $table->integer('jumlah_unit')->default(0)->after('jumlah')->comment('Total unit yang di-track secara individual');
            $table->boolean('tracking_per_unit')->default(false)->after('jumlah_unit')->comment('TRUE jika barang ini di-track per unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn(['jumlah_unit', 'tracking_per_unit']);
        });
    }
};
