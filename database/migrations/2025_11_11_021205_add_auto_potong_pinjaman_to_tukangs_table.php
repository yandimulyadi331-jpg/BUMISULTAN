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
        Schema::table('tukangs', function (Blueprint $table) {
            $table->boolean('auto_potong_pinjaman')->default(true)->after('status')
                  ->comment('Auto potong pinjaman dari gaji mingguan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tukangs', function (Blueprint $table) {
            $table->dropColumn('auto_potong_pinjaman');
        });
    }
};
