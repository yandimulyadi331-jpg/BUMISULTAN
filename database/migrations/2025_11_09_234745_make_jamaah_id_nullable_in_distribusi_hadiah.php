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
        Schema::table('distribusi_hadiah', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['jamaah_id']);
            
            // Make jamaah_id nullable
            $table->unsignedBigInteger('jamaah_id')->nullable()->change();
            
            // Re-add foreign key constraint
            $table->foreign('jamaah_id')->references('id')->on('jamaah_majlis_taklim')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribusi_hadiah', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['jamaah_id']);
            
            // Make jamaah_id NOT nullable again
            $table->unsignedBigInteger('jamaah_id')->nullable(false)->change();
            
            // Re-add foreign key
            $table->foreign('jamaah_id')->references('id')->on('jamaah_majlis_taklim')->onDelete('cascade');
        });
    }
};
