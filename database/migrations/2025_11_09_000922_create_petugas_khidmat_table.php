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
        Schema::create('petugas_khidmat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_khidmat_id')->constrained('jadwal_khidmat')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_khidmat');
    }
};
