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
            $table->foreignId('inventaris_detail_unit_id')->nullable()->after('inventaris_id')
                ->constrained('inventaris_detail_units')->onDelete('set null')
                ->comment('ID unit spesifik yang dipinjam (untuk tracking per unit)');
            
            $table->string('kode_unit_dipinjam', 50)->nullable()->after('inventaris_detail_unit_id')
                ->comment('Kode unit yang dipinjam untuk referensi cepat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_inventaris', function (Blueprint $table) {
            $table->dropForeign(['inventaris_detail_unit_id']);
            $table->dropColumn(['inventaris_detail_unit_id', 'kode_unit_dipinjam']);
        });
    }
};
