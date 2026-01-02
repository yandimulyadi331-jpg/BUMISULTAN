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
        if (!Schema::hasTable('qr_attendance_codes')) {
            Schema::create('qr_attendance_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('event_id');
                $table->string('qr_token', 100)->unique();
                $table->string('qr_hash', 255);
                $table->timestamp('generated_at')->useCurrent();
                $table->timestamp('expired_at');
                $table->boolean('is_active')->default(true);
                $table->integer('scan_count')->default(0);
                $table->timestamps();

                // Foreign keys
                $table->foreign('event_id')->references('id')->on('qr_attendance_events')->cascadeOnDelete();

                // Indexes
                $table->index('qr_token');
                $table->index('expired_at');
                $table->index('is_active');
                $table->index(['event_id', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_attendance_codes');
    }
};
