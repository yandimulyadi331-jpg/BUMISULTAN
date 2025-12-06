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
        Schema::create('tukangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tukang', 20)->unique();
            $table->string('nama_tukang', 100);
            $table->string('nik', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('keahlian', 100)->nullable(); // Misal: tukang batu, tukang cat, dll
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->decimal('tarif_harian', 12, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tukangs');
    }
};
