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
        Schema::create('inventaris_events', function (Blueprint $table) {
            $table->id();
            $table->string('kode_event')->unique(); // EVT-001
            $table->string('nama_event');
            $table->text('deskripsi_event')->nullable();
            $table->string('jenis_event'); // Outing, Training, Gathering, Naik Gunung, dll
            $table->date('tanggal_event');
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi_event')->nullable();
            $table->foreignId('pic_id')->nullable()->constrained('users')->onDelete('set null'); // Person in Charge
            $table->enum('status', ['persiapan', 'berlangsung', 'selesai', 'dibatalkan'])->default('persiapan');
            $table->integer('jumlah_peserta')->nullable();
            $table->text('daftar_inventaris')->nullable(); // JSON array inventaris yang dibutuhkan
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel pivot untuk relasi many-to-many inventaris dan event
        Schema::create('inventaris_event_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_event_id')->constrained('inventaris_events')->onDelete('cascade');
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->integer('jumlah_dibutuhkan')->default(1);
            $table->integer('jumlah_tersedia')->default(0);
            $table->enum('status', ['menunggu', 'tersedia', 'terdistribusi', 'dikembalikan'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_event_items');
        Schema::dropIfExists('inventaris_events');
    }
};
