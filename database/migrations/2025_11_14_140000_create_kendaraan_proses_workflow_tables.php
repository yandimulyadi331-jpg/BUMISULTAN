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
        // Tabel untuk tracking proses workflow kendaraan (keluar, pinjam, service)
        Schema::create('kendaraan_proses', function (Blueprint $table) {
            $table->id();
            $table->string('kode_proses', 50)->unique();
            $table->unsignedBigInteger('kendaraan_id');
            $table->enum('jenis_proses', ['keluar', 'pinjam', 'service']);
            $table->enum('status_proses', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->string('tahap_saat_ini', 50)->nullable(); // Current stage
            $table->unsignedBigInteger('user_id'); // User yang menginisiasi
            $table->string('user_name');
            
            // Data specific untuk tiap jenis proses
            $table->json('data_proses')->nullable(); // Flexible data storage
            
            // Timestamps
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamp('locked_at')->nullable(); // For optimistic locking
            $table->unsignedBigInteger('locked_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('kendaraan_id')->references('id')->on('kendaraans')->onDelete('cascade');
            
            $table->index(['kendaraan_id', 'status_proses']);
            $table->index(['jenis_proses', 'status_proses']);
            $table->index('kode_proses');
        });

        // Tabel untuk tahapan/stages dalam workflow
        Schema::create('kendaraan_proses_tahap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proses_id');
            $table->string('kode_tahap', 50); // e.g., 'pengajuan', 'verifikasi', 'disetujui'
            $table->string('nama_tahap', 100);
            $table->integer('urutan')->default(0);
            $table->enum('status_tahap', ['pending', 'in_progress', 'completed', 'rejected', 'skipped'])->default('pending');
            
            // Data perubahan status
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->string('updated_by_user_name')->nullable();
            $table->text('catatan')->nullable();
            $table->json('metadata')->nullable(); // Additional data (photos, docs, etc.)
            
            // Timestamps
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
            
            $table->foreign('proses_id')->references('id')->on('kendaraan_proses')->onDelete('cascade');
            
            $table->index(['proses_id', 'urutan']);
            $table->index(['proses_id', 'status_tahap']);
        });

        // Tabel untuk history/audit log setiap perubahan
        Schema::create('kendaraan_proses_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proses_id');
            $table->unsignedBigInteger('tahap_id')->nullable();
            $table->string('event_type', 50); // 'created', 'stage_changed', 'status_updated', 'completed', 'cancelled'
            $table->string('old_value', 100)->nullable();
            $table->string('new_value', 100)->nullable();
            $table->text('description')->nullable();
            
            // User yang melakukan aksi
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            
            // Additional context
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            $table->foreign('proses_id')->references('id')->on('kendaraan_proses')->onDelete('cascade');
            $table->foreign('tahap_id')->references('id')->on('kendaraan_proses_tahap')->onDelete('cascade');
            
            $table->index(['proses_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        // Add columns to existing tables if needed
        Schema::table('kendaraans', function (Blueprint $table) {
            // Add process tracking columns
            $table->unsignedBigInteger('proses_aktif_id')->nullable()->after('status');
            $table->enum('status_workflow', ['idle', 'in_keluar', 'in_pinjam', 'in_service'])->default('idle')->after('proses_aktif_id');
            
            $table->foreign('proses_aktif_id')->references('id')->on('kendaraan_proses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropForeign(['proses_aktif_id']);
            $table->dropColumn(['proses_aktif_id', 'status_workflow']);
        });
        
        Schema::dropIfExists('kendaraan_proses_history');
        Schema::dropIfExists('kendaraan_proses_tahap');
        Schema::dropIfExists('kendaraan_proses');
    }
};
