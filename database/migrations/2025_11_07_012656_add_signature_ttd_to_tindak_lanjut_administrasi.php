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
        Schema::table('tindak_lanjut_administrasi', function (Blueprint $table) {
            $table->string('signature_ttd')->nullable()->after('file_dokumen_ttd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tindak_lanjut_administrasi', function (Blueprint $table) {
            $table->dropColumn('signature_ttd');
        });
    }
};
