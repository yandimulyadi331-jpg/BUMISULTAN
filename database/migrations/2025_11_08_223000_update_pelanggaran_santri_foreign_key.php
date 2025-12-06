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
        Schema::table('pelanggaran_santri', function (Blueprint $table) {
            // Drop foreign key constraint lama
            $table->dropForeign(['user_id']);
            
            // Tambah foreign key baru ke tabel santri
            $table->foreign('user_id')
                  ->references('id')
                  ->on('santri')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggaran_santri', function (Blueprint $table) {
            // Drop foreign key ke santri
            $table->dropForeign(['user_id']);
            
            // Kembalikan foreign key ke users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
