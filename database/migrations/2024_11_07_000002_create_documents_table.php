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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dokumen', 50)->unique()->comment('Format: [KATEGORI]-[NOMOR]-[LOKER], contoh: SK-001-L001');
            $table->string('nama_dokumen', 255)->comment('Nama/judul dokumen');
            $table->foreignId('document_category_id')->constrained('document_categories')->onDelete('cascade');
            $table->text('deskripsi')->nullable()->comment('Deskripsi detail dokumen');
            
            // Informasi Fisik & Loker
            $table->string('nomor_loker', 20)->nullable()->comment('Nomor loker fisik penyimpanan dokumen');
            $table->string('lokasi_loker', 100)->nullable()->comment('Lokasi fisik loker, contoh: Ruang Arsip Lt.2');
            $table->string('rak', 20)->nullable()->comment('Nomor rak di loker');
            $table->string('baris', 20)->nullable()->comment('Baris/posisi di rak');
            
            // Jenis dan Path/Link Dokumen
            $table->enum('jenis_dokumen', ['file', 'link'])->default('file')->comment('Apakah file upload atau link eksternal');
            $table->string('jenis_file', 50)->nullable()->comment('pdf, excel, word, image, dll');
            $table->string('file_path', 500)->nullable()->comment('Path file jika upload, atau URL jika link');
            $table->string('file_size', 20)->nullable()->comment('Ukuran file');
            $table->string('file_extension', 10)->nullable()->comment('Ekstensi file');
            
            // Akses dan Permission
            $table->enum('access_level', ['public', 'view_only', 'restricted'])->default('public')
                ->comment('public: bisa view & download, view_only: hanya view, restricted: hanya admin');
            
            // Metadata Dokumen
            $table->date('tanggal_dokumen')->nullable()->comment('Tanggal pembuatan/pengesahan dokumen');
            $table->date('tanggal_berlaku')->nullable()->comment('Tanggal mulai berlaku');
            $table->date('tanggal_berakhir')->nullable()->comment('Tanggal berakhir/kadaluarsa');
            $table->string('nomor_referensi', 100)->nullable()->comment('Nomor surat/referensi eksternal');
            $table->string('penerbit', 100)->nullable()->comment('Yang menerbitkan dokumen');
            $table->text('tags')->nullable()->comment('Tag untuk pencarian, dipisah koma');
            
            // Status dan Tracking
            $table->enum('status', ['aktif', 'arsip', 'kadaluarsa'])->default('aktif');
            $table->integer('jumlah_view')->default(0)->comment('Jumlah kali dokumen dilihat');
            $table->integer('jumlah_download')->default(0)->comment('Jumlah kali dokumen didownload');
            
            // User tracking
            $table->string('uploaded_by', 20)->nullable()->comment('NIK user yang upload');
            $table->string('updated_by', 20)->nullable()->comment('NIK user yang terakhir update');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes untuk performa pencarian
            $table->index('kode_dokumen');
            $table->index('nomor_loker');
            $table->index('document_category_id');
            $table->index('access_level');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
