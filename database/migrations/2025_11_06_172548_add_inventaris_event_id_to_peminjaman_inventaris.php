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
            if (!Schema::hasColumn('peminjaman_inventaris', 'inventaris_event_id')) {
                $table->unsignedBigInteger('inventaris_event_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_inventaris', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_inventaris', 'inventaris_event_id')) {
                $table->dropColumn('inventaris_event_id');
            }
        });
    }
};
