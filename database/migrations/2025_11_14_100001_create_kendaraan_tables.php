<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table Log Keluar/Masuk Kendaraan
        Schema::create('kendaraan_keluar_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_log', 50)->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->enum('tipe', ['Keluar', 'Masuk']);
            $table->string('nik')->nullable();
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('set null');
            $table->string('pengemudi', 100);
            $table->string('tujuan', 200)->nullable();
            $table->dateTime('waktu_keluar')->nullable();
            $table->dateTime('waktu_masuk')->nullable();
            $table->integer('km_keluar')->nullable();
            $table->integer('km_masuk')->nullable();
            $table->integer('km_tempuh')->nullable();
            $table->enum('kondisi_keluar', ['Baik', 'Cukup', 'Perlu Service'])->nullable();
            $table->enum('kondisi_masuk', ['Baik', 'Cukup', 'Rusak'])->nullable();
            $table->text('keperluan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('petugas', 100)->nullable();
            $table->timestamps();
        });

        // Table Peminjaman Kendaraan
        Schema::create('kendaraan_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 50)->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->string('nik')->nullable();
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('set null');
            $table->string('nama_peminjam', 100);
            $table->string('jabatan', 50)->nullable();
            $table->string('departemen', 50)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_kembali');
            $table->string('tujuan_penggunaan', 200);
            $table->text('keperluan');
            $table->integer('jumlah_penumpang')->nullable();
            $table->enum('status_pengajuan', ['Pending', 'Disetujui', 'Ditolak', 'Selesai', 'Batal'])->default('Pending');
            $table->string('disetujui_oleh', 100)->nullable();
            $table->dateTime('tanggal_persetujuan')->nullable();
            $table->text('catatan_persetujuan')->nullable();
            $table->dateTime('waktu_ambil')->nullable();
            $table->dateTime('waktu_kembali_actual')->nullable();
            $table->integer('km_awal')->nullable();
            $table->integer('km_akhir')->nullable();
            $table->enum('kondisi_ambil', ['Baik', 'Cukup', 'Perlu Service'])->nullable();
            $table->enum('kondisi_kembali', ['Baik', 'Cukup', 'Rusak'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Table Service Kendaraan
        Schema::create('kendaraan_service', function (Blueprint $table) {
            $table->id();
            $table->string('kode_service', 50)->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->date('tanggal_service');
            $table->enum('jenis_service', ['Service Rutin', 'Perbaikan', 'Ganti Oli', 'Ganti Ban', 'Body Repair', 'Cuci', 'Lainnya']);
            $table->string('bengkel', 100)->nullable();
            $table->text('deskripsi_pekerjaan');
            $table->integer('km_service')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->string('mekanik', 100)->nullable();
            $table->text('sparepart_diganti')->nullable();
            $table->date('service_selanjutnya')->nullable();
            $table->integer('km_service_selanjutnya')->nullable();
            $table->string('pelapor', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kendaraan_service');
        Schema::dropIfExists('kendaraan_peminjaman');
        Schema::dropIfExists('kendaraan_keluar_masuk');
    }
};
