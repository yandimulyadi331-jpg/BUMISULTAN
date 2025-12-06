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
                // Make cabang, dept, jabatan nullable
                $table->char('kode_cabang', 3)->nullable()->change();
                $table->char('kode_dept', 3)->nullable()->change();
                $table->char('kode_jabatan', 3)->nullable()->change();
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
                $table->char('kode_cabang', 3)->change();
                $table->char('kode_dept', 3)->change();
                $table->char('kode_jabatan', 3)->change();
            });
        }
    }
};
