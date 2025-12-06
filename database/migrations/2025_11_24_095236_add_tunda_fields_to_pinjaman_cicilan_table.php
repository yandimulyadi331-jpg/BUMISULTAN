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
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            // Field untuk tracking penundaan
            $table->boolean('is_ditunda')->default(false)->after('status')->comment('Apakah cicilan ini ditunda');
            $table->date('tanggal_ditunda')->nullable()->after('is_ditunda')->comment('Tanggal saat cicilan ditunda');
            $table->foreignId('ditunda_oleh')->nullable()->after('tanggal_ditunda')->constrained('users')->onDelete('set null')->comment('User yang menunda cicilan');
            $table->text('alasan_ditunda')->nullable()->after('ditunda_oleh')->comment('Alasan penundaan');
            $table->boolean('is_hasil_tunda')->default(false)->after('alasan_ditunda')->comment('Apakah cicilan ini hasil dari penundaan');
            $table->foreignId('cicilan_ditunda_id')->nullable()->after('is_hasil_tunda')->constrained('pinjaman_cicilan')->onDelete('set null')->comment('ID cicilan yang ditunda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman_cicilan', function (Blueprint $table) {
            $table->dropForeign(['ditunda_oleh']);
            $table->dropForeign(['cicilan_ditunda_id']);
            $table->dropColumn([
                'is_ditunda',
                'tanggal_ditunda',
                'ditunda_oleh',
                'alasan_ditunda',
                'is_hasil_tunda',
                'cicilan_ditunda_id'
            ]);
        });
    }
};
