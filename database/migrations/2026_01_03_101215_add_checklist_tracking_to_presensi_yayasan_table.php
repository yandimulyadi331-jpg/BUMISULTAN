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
        Schema::table('presensi_yayasan', function (Blueprint $table) {
            $table->boolean('checklist_harian_completed')->default(false)->comment('Apakah checklist harian sudah diselesaikan')->after('status');
            $table->boolean('checklist_harian_skipped')->default(false)->comment('Apakah checklist harian di-skip (karena nonaktif)')->after('checklist_harian_completed');
            $table->string('checklist_harian_periode_key', 50)->nullable()->comment('Periode key checklist yang divalidasi')->after('checklist_harian_skipped');
            
            $table->index(['tanggal', 'checklist_harian_completed'], 'idx_checklist_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_yayasan', function (Blueprint $table) {
            $table->dropIndex('idx_checklist_status');
            $table->dropColumn(['checklist_harian_completed', 'checklist_harian_skipped', 'checklist_harian_periode_key']);
        });
    }
};
