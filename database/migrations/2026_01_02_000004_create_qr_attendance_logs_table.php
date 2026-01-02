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
        if (!Schema::hasTable('qr_attendance_logs')) {
            Schema::create('qr_attendance_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('event_id');
                $table->unsignedBigInteger('qr_code_id');
                $table->string('kode_yayasan', 20)->nullable();
                $table->string('device_id', 200)->nullable();
                $table->decimal('scan_latitude', 10, 8)->nullable();
                $table->decimal('scan_longitude', 11, 8)->nullable();
                $table->decimal('distance_from_venue', 8, 2)->nullable();
                $table->string('ip_address', 50)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('scan_at')->useCurrent();
                $table->enum('status', [
                    'success', 
                    'failed_expired_qr', 
                    'failed_geofence', 
                    'failed_device', 
                    'failed_duplicate', 
                    'failed_time',
                    'failed_face_verification'
                ]);
                $table->text('failure_reason')->nullable();
                $table->string('photo_selfie', 255)->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('event_id')->references('id')->on('qr_attendance_events')->cascadeOnDelete();
                $table->foreign('qr_code_id')->references('id')->on('qr_attendance_codes')->cascadeOnDelete();
                $table->foreign('kode_yayasan')->references('kode_yayasan')->on('yayasan_masar')->nullOnDelete();

                // Indexes
                $table->index('event_id');
                $table->index('kode_yayasan');
                $table->index('status');
                $table->index('scan_at');
                $table->index(['event_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_attendance_logs');
    }
};
