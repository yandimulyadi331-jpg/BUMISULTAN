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
            $table->integer('points')->default(1)->comment('Poin untuk pekerjaan, 1=ringan, 5=sedang, 10+=berat');
            $table->text('point_description')->nullable()->comment('Deskripsi alasan pemberian point ini');
        });

        Schema::table('perawatan_log', function (Blueprint $table) {
            $table->integer('points_earned')->default(0)->comment('Points yang didapat saat melakukan checklist ini');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perawatan_log', function (Blueprint $table) {
            $table->dropColumn('points_earned');
        });

        Schema::table('master_perawatan', function (Blueprint $table) {
            $table->dropColumn(['points', 'point_description']);
        });
    }
};
