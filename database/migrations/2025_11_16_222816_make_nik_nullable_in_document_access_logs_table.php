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
        Schema::table('document_access_logs', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->change();
            $table->string('nama_user', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_access_logs', function (Blueprint $table) {
            $table->string('nik', 20)->nullable(false)->change();
            $table->string('nama_user', 100)->nullable(false)->change();
        });
    }
};
