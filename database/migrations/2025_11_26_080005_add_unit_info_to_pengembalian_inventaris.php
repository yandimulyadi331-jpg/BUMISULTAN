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
        Schema::table('pengembalian_inventaris', function (Blueprint $table) {
            $table->foreignId('inventaris_detail_unit_id')->nullable()->after('peminjaman_inventaris_id')
                ->constrained('inventaris_detail_units')->onDelete('set null')
                ->comment('ID unit yang dikembalikan');
            
            $table->enum('kondisi_saat_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik')->after('inventaris_detail_unit_id')
                ->comment('Kondisi unit saat dikembalikan');
            
            $table->boolean('ada_kerusakan')->default(false)->after('kondisi_saat_kembali')
                ->comment('Flag apakah ada kerusakan saat pengembalian');
            
            $table->text('deskripsi_kerusakan')->nullable()->after('ada_kerusakan')
                ->comment('Deskripsi detail kerusakan jika ada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengembalian_inventaris', function (Blueprint $table) {
            $table->dropForeign(['inventaris_detail_unit_id']);
            $table->dropColumn([
                'inventaris_detail_unit_id', 
                'kondisi_saat_kembali', 
                'ada_kerusakan', 
                'deskripsi_kerusakan'
            ]);
        });
    }
};
