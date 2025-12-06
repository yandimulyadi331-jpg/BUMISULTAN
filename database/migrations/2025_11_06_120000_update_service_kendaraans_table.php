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
        Schema::table('service_kendaraans', function (Blueprint $table) {
            // Make driver_service nullable
            $table->string('driver_service', 100)->nullable()->change();
            
            // Rename and change columns
            $table->dropColumn(['tanggal_service', 'tanggal_selesai', 'keterangan_service', 'foto_sebelum', 'foto_sesudah', 'hasil_service', 'biaya']);
            
            // Add new columns
            $table->datetime('waktu_service')->after('driver_service');
            $table->text('deskripsi_kerusakan')->nullable()->after('bengkel');
            $table->text('pekerjaan')->nullable()->after('deskripsi_kerusakan');
            $table->decimal('estimasi_biaya', 12, 2)->nullable()->after('km_service');
            $table->date('estimasi_selesai')->nullable()->after('estimasi_biaya');
            $table->string('pic', 100)->nullable()->after('estimasi_selesai');
            $table->string('foto_before')->nullable()->after('pic');
            $table->decimal('latitude_service', 10, 7)->nullable()->after('foto_before');
            $table->decimal('longitude_service', 10, 7)->nullable()->after('latitude_service');
            
            // Data setelah selesai
            $table->datetime('waktu_selesai')->nullable()->after('longitude_service');
            $table->decimal('km_selesai', 10, 2)->nullable()->after('waktu_selesai');
            $table->decimal('biaya_akhir', 12, 2)->nullable()->after('km_selesai');
            $table->text('pekerjaan_selesai')->nullable()->after('biaya_akhir');
            $table->text('catatan_mekanik')->nullable()->after('pekerjaan_selesai');
            $table->string('kondisi_kendaraan', 50)->nullable()->after('catatan_mekanik');
            $table->string('pic_selesai', 100)->nullable()->after('kondisi_kendaraan');
            $table->string('foto_after')->nullable()->after('pic_selesai');
            $table->decimal('latitude_selesai', 10, 7)->nullable()->after('foto_after');
            $table->decimal('longitude_selesai', 10, 7)->nullable()->after('latitude_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_kendaraans', function (Blueprint $table) {
            $table->string('driver_service', 100)->nullable(false)->change();
            
            $table->dropColumn([
                'waktu_service', 'deskripsi_kerusakan', 'pekerjaan', 'estimasi_biaya',
                'estimasi_selesai', 'pic', 'foto_before', 'latitude_service', 'longitude_service',
                'waktu_selesai', 'km_selesai', 'biaya_akhir', 'pekerjaan_selesai', 
                'catatan_mekanik', 'kondisi_kendaraan', 'pic_selesai', 'foto_after',
                'latitude_selesai', 'longitude_selesai'
            ]);
            
            $table->date('tanggal_service')->after('driver_service');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_service');
            $table->text('keterangan_service')->nullable()->after('bengkel');
            $table->string('foto_sebelum')->nullable()->after('km_service');
            $table->string('foto_sesudah')->nullable()->after('foto_sebelum');
            $table->text('hasil_service')->nullable()->after('foto_sesudah');
            $table->decimal('biaya', 12, 2)->nullable()->after('hasil_service');
        });
    }
};
