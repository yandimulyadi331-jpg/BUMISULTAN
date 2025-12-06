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
            // Status transaksi: active / voided
            $table->enum('status', ['active', 'voided'])->default('active')->after('created_by');
            
            // Audit trail untuk void
            $table->unsignedBigInteger('void_by')->nullable()->after('status');
            $table->timestamp('void_at')->nullable()->after('void_by');
            $table->text('void_reason')->nullable()->after('void_at');
            
            // Audit trail untuk update
            $table->unsignedBigInteger('updated_by')->nullable()->after('void_reason');
            
            // Foreign keys
            $table->foreign('void_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisasi_dana_operasional', function (Blueprint $table) {
            // Drop foreign keys dulu
            $table->dropForeign(['void_by']);
            $table->dropForeign(['updated_by']);
            
            // Drop columns
            $table->dropColumn(['status', 'void_by', 'void_at', 'void_reason', 'updated_by']);
        });
    }
};
