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
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('status');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->unsignedBigInteger('published_by')->nullable()->after('published_at');
            
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_keuangan', function (Blueprint $table) {
            $table->dropForeign(['published_by']);
            $table->dropColumn(['is_published', 'published_at', 'published_by']);
        });
    }
};
