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
        if (!Schema::hasTable('jamaah_devices')) {
            Schema::create('jamaah_devices', function (Blueprint $table) {
                $table->id();
                $table->string('kode_yayasan', 20);
                $table->string('device_id', 200)->unique();
                $table->string('device_name', 200)->nullable();
                $table->string('device_model', 100)->nullable();
                $table->string('os_name', 50)->nullable();
                $table->string('os_version', 50)->nullable();
                $table->string('browser', 100)->nullable();
                $table->timestamp('first_login_at')->useCurrent();
                $table->timestamp('last_login_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                // Foreign keys
                $table->foreign('kode_yayasan')->references('kode_yayasan')->on('yayasan_masar')->cascadeOnDelete();

                // Indexes
                $table->index('device_id');
                $table->index('kode_yayasan');
                $table->unique(['kode_yayasan', 'device_id'], 'unique_jamaah_device');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaah_devices');
    }
};
