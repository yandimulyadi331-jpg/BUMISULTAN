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
        if (!Schema::hasTable('qr_attendance_events')) {
            Schema::create('qr_attendance_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_code', 50)->unique();
                $table->string('event_name', 200);
                $table->date('event_date');
                $table->time('event_start_time');
                $table->time('event_end_time');
                $table->string('venue_name', 200)->nullable();
                $table->decimal('venue_latitude', 10, 8);
                $table->decimal('venue_longitude', 11, 8);
                $table->integer('venue_radius_meter')->default(100);
                $table->char('kode_cabang', 3)->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->nullOnDelete()->cascadeOnUpdate();
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

                // Indexes
                $table->index('event_date');
                $table->index('is_active');
                $table->index(['event_date', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_attendance_events');
    }
};
