<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraint dengan raw SQL
        DB::statement('ALTER TABLE pinjaman DROP FOREIGN KEY pinjaman_karyawan_id_foreign');
        
        // Ubah tipe kolom
        DB::statement('ALTER TABLE pinjaman MODIFY karyawan_id VARCHAR(50) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            // Kembalikan ke char dan foreign key
            $table->char('karyawan_id', 9)->nullable()->change();
            $table->foreign('karyawan_id')->references('nik')->on('karyawan')->onDelete('set null');
        });
    }
};
