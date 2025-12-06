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
        Schema::table('hadiah_majlis_taklim', function (Blueprint $table) {
            $table->json('stok_ukuran')->nullable()->after('stok_terbagikan')->comment('Stok per ukuran dalam format JSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hadiah_majlis_taklim', function (Blueprint $table) {
            // $table->dropColumn('stok_ukuran'); // Commented for safety
        });
    }
};
