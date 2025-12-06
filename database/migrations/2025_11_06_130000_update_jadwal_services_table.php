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
        Schema::table('jadwal_services', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['tanggal_jadwal', 'status']);
            
            // Add new columns
            $table->enum('tipe_interval', ['kilometer', 'waktu'])->default('kilometer')->after('jenis_service');
            $table->integer('km_terakhir')->nullable()->after('interval_km');
            $table->integer('interval_hari')->nullable()->after('km_terakhir');
            $table->date('tanggal_terakhir')->nullable()->after('interval_hari');
            $table->date('jadwal_berikutnya')->nullable()->after('tanggal_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_services', function (Blueprint $table) {
            $table->dropColumn(['tipe_interval', 'km_terakhir', 'interval_hari', 'tanggal_terakhir', 'jadwal_berikutnya']);
            $table->date('tanggal_jadwal')->after('jenis_service');
            $table->enum('status', ['pending', 'done', 'overdue'])->default('pending')->after('keterangan');
        });
    }
};
