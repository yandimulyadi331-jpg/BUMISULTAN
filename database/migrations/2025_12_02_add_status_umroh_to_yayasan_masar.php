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
        if (Schema::hasTable('yayasan_masar')) {
            Schema::table('yayasan_masar', function (Blueprint $table) {
                // Add status_umroh column if it doesn't exist
                if (!Schema::hasColumn('yayasan_masar', 'status_umroh')) {
                    $table->char('status_umroh', 1)->nullable()->default('0')->after('status_aktif');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('yayasan_masar')) {
            Schema::table('yayasan_masar', function (Blueprint $table) {
                if (Schema::hasColumn('yayasan_masar', 'status_umroh')) {
                    $table->dropColumn('status_umroh');
                }
            });
        }
    }
};
