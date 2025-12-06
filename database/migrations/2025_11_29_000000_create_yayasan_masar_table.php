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
        if (!Schema::hasTable('yayasan_masar')) {
            Schema::create('yayasan_masar', function (Blueprint $table) {
                $table->string('kode_yayasan', 20)->primary();
                $table->string('no_identitas', 16);
                $table->string('nama', 100);
                $table->string('tempat_lahir', 20)->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->string('alamat')->nullable();
                $table->string('no_hp', 15)->nullable();
                $table->char('jenis_kelamin', 1);
                $table->char('kode_status_kawin', 2)->nullable();
                $table->string('pendidikan_terakhir', 4)->nullable();
                $table->char('kode_cabang', 3);
                $table->char('kode_dept', 3);
                $table->char('kode_jabatan', 3);
                $table->date('tanggal_masuk');
                $table->char('status', 1);
                $table->string('email')->nullable();
                $table->string('foto')->nullable();
                $table->char('kode_jadwal', 5)->nullable();
                $table->smallInteger('pin')->nullable();
                $table->date('tanggal_nonaktif')->nullable();
                $table->date('tanggal_off_gaji')->nullable();
                $table->char('lock_location', 1);
                $table->char('lock_jam_kerja', 1)->default('0');
                $table->char('status_aktif', 1);
                $table->string('password');
                $table->json('kode_cabang_array')->nullable();
                $table->char('kode_group', 3)->nullable();
                $table->timestamps();
            });
        }

        // Create users_yayasan_masar linking table
        if (!Schema::hasTable('users_yayasan_masar')) {
            Schema::create('users_yayasan_masar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_yayasan', 20);
            $table->unsignedBigInteger('id_user');
            $table->timestamps();
            $table->unique(['kode_yayasan', 'id_user']);
            $table->foreign('kode_yayasan')->references('kode_yayasan')->on('yayasan_masar')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Create yayasan_masar_wajah table for face recognition
        if (!Schema::hasTable('yayasan_masar_wajah')) {
            Schema::create('yayasan_masar_wajah', function (Blueprint $table) {
                $table->id();
                $table->string('kode_yayasan', 20);
                $table->string('wajah')->nullable();
                $table->timestamps();
                $table->foreign('kode_yayasan')->references('kode_yayasan')->on('yayasan_masar')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yayasan_masar_wajah');
        Schema::dropIfExists('users_yayasan_masar');
        Schema::dropIfExists('yayasan_masar');
    }
};
