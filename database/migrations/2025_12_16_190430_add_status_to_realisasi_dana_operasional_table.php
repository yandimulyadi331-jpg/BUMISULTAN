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
        Schema::table('realisasi_dana_operasional', function (Blueprint $table) {
            // Add status column if not exists
            if (!Schema::hasColumn('realisasi_dana_operasional', 'status')) {
                $table->enum('status', ['active', 'voided'])->default('active')->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisasi_dana_operasional', function (Blueprint $table) {
            if (Schema::hasColumn('realisasi_dana_operasional', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
