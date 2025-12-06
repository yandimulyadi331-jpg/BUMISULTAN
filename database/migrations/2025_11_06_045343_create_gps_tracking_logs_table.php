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
        Schema::create('gps_tracking_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aktivitas_kendaraan_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed', 8, 2)->nullable(); // km/h
            $table->decimal('accuracy', 8, 2)->nullable(); // meters
            $table->string('status', 20)->default('moving'); // moving, stopped
            $table->timestamps();
            
            $table->foreign('aktivitas_kendaraan_id')
                  ->references('id')
                  ->on('aktivitas_kendaraans')
                  ->onDelete('cascade');
                  
            $table->index(['aktivitas_kendaraan_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gps_tracking_logs');
    }
};
