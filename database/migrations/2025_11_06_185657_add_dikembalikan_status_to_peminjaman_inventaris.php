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
        // Alter enum to add 'dikembalikan' value
        DB::statement("ALTER TABLE peminjaman_inventaris MODIFY COLUMN status_peminjaman ENUM('pending', 'disetujui', 'ditolak', 'dikembalikan') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE peminjaman_inventaris MODIFY COLUMN status_peminjaman ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending'");
    }
};
