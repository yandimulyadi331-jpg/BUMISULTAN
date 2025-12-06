<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create table jumlah_kehadiran_mingguan if not exists
if (!Schema::hasTable('jumlah_kehadiran_mingguan')) {
    Schema::create('jumlah_kehadiran_mingguan', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jamaah_id')->constrained('jamaah_masar')->onDelete('cascade');
        $table->unsignedSmallInteger('tahun')->comment('Tahun kehadiran (contoh: 2025)');
        $table->unsignedSmallInteger('minggu_ke')->comment('Minggu ke berapa dalam tahun (1-52)');
        $table->unsignedSmallInteger('jumlah_kehadiran')->default(1)->comment('Jumlah kehadiran di minggu tersebut (biasanya 1 atau 0)');
        $table->date('tanggal_kehadiran')->nullable()->comment('Tanggal hari Jumat saat kehadiran tercatat');
        $table->timestamp('last_updated')->nullable()->comment('Terakhir diupdate kapan');
        $table->timestamps();
        $table->softDeletes();
        
        // Unique constraint agar satu jamaah tidak bisa punya 2 record untuk minggu yang sama di tahun yang sama
        $table->unique(['jamaah_id', 'tahun', 'minggu_ke'], 'unique_jamaah_year_week');
        
        // Index untuk performa query
        $table->index('jamaah_id');
        $table->index(['tahun', 'minggu_ke']);
        $table->index('tanggal_kehadiran');
    });
    
    echo "✓ Table jumlah_kehadiran_mingguan berhasil dibuat!\n";
} else {
    echo "✓ Table jumlah_kehadiran_mingguan sudah ada.\n";
}
