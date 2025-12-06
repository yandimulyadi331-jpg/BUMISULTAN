<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key constraints dari tabel yang sudah ada
        Schema::table('kendaraan_keluar_masuk', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
        });

        Schema::table('kendaraan_peminjaman', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
        });

        Schema::table('kendaraan_service', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
        });

        // Tambahkan foreign key yang benar mereferensi 'kendaraans'
        Schema::table('kendaraan_keluar_masuk', function (Blueprint $table) {
            $table->foreign('kendaraan_id')
                  ->references('id')
                  ->on('kendaraans')
                  ->onDelete('cascade');
        });

        Schema::table('kendaraan_peminjaman', function (Blueprint $table) {
            $table->foreign('kendaraan_id')
                  ->references('id')
                  ->on('kendaraans')
                  ->onDelete('cascade');
        });

        Schema::table('kendaraan_service', function (Blueprint $table) {
            $table->foreign('kendaraan_id')
                  ->references('id')
                  ->on('kendaraans')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        // Kembalikan ke constraint lama jika rollback
        Schema::table('kendaraan_keluar_masuk', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
        });

        Schema::table('kendaraan_peminjaman', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
        });

        Schema::table('kendaraan_service', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
        });

        Schema::table('kendaraan_keluar_masuk', function (Blueprint $table) {
            $table->foreign('kendaraan_id')
                  ->references('id')
                  ->on('kendaraan')
                  ->onDelete('cascade');
        });

        Schema::table('kendaraan_peminjaman', function (Blueprint $table) {
            $table->foreign('kendaraan_id')
                  ->references('id')
                  ->on('kendaraan')
                  ->onDelete('cascade');
        });

        Schema::table('kendaraan_service', function (Blueprint $table) {
            $table->foreign('kendaraan_id')
                  ->references('id')
                  ->on('kendaraan')
                  ->onDelete('cascade');
        });
    }
};
