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
        Schema::table('master_perawatan', function (Blueprint $table) {
            $table->unsignedBigInteger('ruangan_id')->nullable()->comment('Ruangan yang mempunyai perawatan ini')->after('kategori');
            $table->foreign('ruangan_id')->references('id')->on('ruangans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_perawatan', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
            $table->dropColumn('ruangan_id');
        });
    }
};
