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
        Schema::create('informasi_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('informasi_id')->constrained('informasi')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('read_at')->useCurrent();
            $table->timestamps();
            
            // Unique constraint: satu user hanya bisa baca satu informasi sekali
            $table->unique(['informasi_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_reads');
    }
};
