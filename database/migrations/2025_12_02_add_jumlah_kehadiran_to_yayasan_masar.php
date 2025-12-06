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
        if (Schema::hasTable('yayasan_masar') && !Schema::hasColumn('yayasan_masar', 'jumlah_kehadiran')) {
            Schema::table('yayasan_masar', function (Blueprint $table) {
                $table->integer('jumlah_kehadiran')->default(0)->after('status_aktif');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('yayasan_masar') && Schema::hasColumn('yayasan_masar', 'jumlah_kehadiran')) {
            Schema::table('yayasan_masar', function (Blueprint $table) {
                $table->dropColumn('jumlah_kehadiran');
            });
        }
    }
};
