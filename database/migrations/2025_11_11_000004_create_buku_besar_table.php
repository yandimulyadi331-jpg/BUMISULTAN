<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Buku Besar - General Ledger (Posted Transactions)
     */
    public function up(): void
    {
        Schema::create('buku_besar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->foreignId('jurnal_umum_id')->constrained('jurnal_umum');
            $table->foreignId('jurnal_detail_id')->constrained('jurnal_detail');
            $table->date('tanggal')->comment('Tanggal transaksi');
            $table->string('nomor_jurnal', 50)->comment('Nomor jurnal referensi');
            $table->text('keterangan');
            $table->decimal('debit', 20, 2)->default(0);
            $table->decimal('kredit', 20, 2)->default(0);
            $table->decimal('saldo', 20, 2)->default(0)->comment('Running balance');
            $table->string('periode', 7)->comment('Periode YYYY-MM');
            $table->integer('tahun_buku')->comment('Tahun buku');
            $table->integer('bulan_buku')->comment('Bulan buku 1-12');
            $table->boolean('is_closing')->default(false)->comment('Apakah transaksi penutupan');
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['chart_of_account_id', 'tanggal']);
            $table->index(['periode', 'chart_of_account_id']);
            $table->index(['tahun_buku', 'bulan_buku', 'chart_of_account_id']);
            $table->index('jurnal_umum_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_besar');
    }
};
