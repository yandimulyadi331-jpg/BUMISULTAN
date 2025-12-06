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
        Schema::table('saldo_harian_operasional', function (Blueprint $table) {
            $table->date('tanggal')->unique()->after('id');
            $table->unsignedBigInteger('pengajuan_id')->nullable()->after('tanggal');
            $table->decimal('saldo_awal', 15, 2)->default(0)->after('pengajuan_id');
            $table->decimal('dana_masuk', 15, 2)->default(0)->after('saldo_awal');
            $table->decimal('total_realisasi', 15, 2)->default(0)->after('dana_masuk');
            $table->decimal('saldo_akhir', 15, 2)->default(0)->after('total_realisasi');
            $table->enum('status', ['open', 'closed'])->default('open')->after('saldo_akhir');
            $table->unsignedBigInteger('ditutup_oleh')->nullable()->after('status');
            $table->timestamp('ditutup_pada')->nullable()->after('ditutup_oleh');
            
            $table->foreign('pengajuan_id')->references('id')->on('pengajuan_dana_operasional')->onDelete('set null');
            $table->foreign('ditutup_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saldo_harian_operasional', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_id']);
            $table->dropForeign(['ditutup_oleh']);
            $table->dropColumn(['tanggal', 'pengajuan_id', 'saldo_awal', 'dana_masuk', 'total_realisasi', 'saldo_akhir', 'status', 'ditutup_oleh', 'ditutup_pada']);
        });
    }
};
