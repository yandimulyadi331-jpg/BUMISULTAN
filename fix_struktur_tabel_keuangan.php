<?php

/**
 * Script untuk memperbaiki dan melengkapi struktur tabel yang sudah ada
 * untuk Manajemen Keuangan
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "PERBAIKAN STRUKTUR TABEL MANAJEMEN KEUANGAN\n";
echo "==============================================\n\n";

try {
    // 1. Cek dan perbaiki tabel jurnal_umum
    echo "Memperbaiki tabel jurnal_umum...\n";
    if (Schema::hasTable('jurnal_umum')) {
        Schema::table('jurnal_umum', function (Blueprint $table) {
            if (!Schema::hasColumn('jurnal_umum', 'nomor_jurnal')) {
                $table->string('nomor_jurnal', 50)->unique()->after('id');
            }
            if (!Schema::hasColumn('jurnal_umum', 'tanggal')) {
                $table->date('tanggal')->after('nomor_jurnal');
            }
            if (!Schema::hasColumn('jurnal_umum', 'transaksi_keuangan_id')) {
                $table->foreignId('transaksi_keuangan_id')->nullable()->after('tanggal')->constrained('transaksi_keuangan')->onDelete('cascade');
            }
            if (!Schema::hasColumn('jurnal_umum', 'akun_keuangan_id')) {
                $table->foreignId('akun_keuangan_id')->after('transaksi_keuangan_id')->constrained('akun_keuangan')->onDelete('cascade');
            }
            if (!Schema::hasColumn('jurnal_umum', 'debit')) {
                $table->decimal('debit', 20, 2)->default(0)->after('akun_keuangan_id');
            }
            if (!Schema::hasColumn('jurnal_umum', 'kredit')) {
                $table->decimal('kredit', 20, 2)->default(0)->after('debit');
            }
            if (!Schema::hasColumn('jurnal_umum', 'keterangan')) {
                $table->text('keterangan')->after('kredit');
            }
            if (!Schema::hasColumn('jurnal_umum', 'status')) {
                $table->enum('status', ['draft', 'posted'])->default('draft')->after('keterangan');
            }
            if (!Schema::hasColumn('jurnal_umum', 'created_by')) {
                $table->foreignId('created_by')->after('status')->constrained('users')->onDelete('cascade');
            }
        });
        echo "✓ Tabel jurnal_umum berhasil diperbaiki\n";
    } else {
        echo "⚠ Tabel jurnal_umum tidak ditemukan\n";
    }

    // 2. Cek dan buat tabel anggaran jika belum ada
    echo "\nMemeriksa tabel anggaran...\n";
    if (!Schema::hasTable('anggaran')) {
        Schema::create('anggaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_anggaran', 50)->unique();
            $table->string('nama_anggaran', 150);
            $table->year('tahun');
            $table->enum('periode', ['bulanan', 'triwulan', 'tahunan'])->default('bulanan');
            $table->integer('bulan')->nullable()->comment('Bulan 1-12 untuk periode bulanan');
            $table->integer('triwulan')->nullable()->comment('Q1-Q4 untuk periode triwulan');
            
            $table->foreignId('akun_keuangan_id')->constrained('akun_keuangan')->onDelete('cascade');
            $table->foreignId('departemen_id')->nullable()->constrained('departemens')->onDelete('set null');
            
            $table->decimal('jumlah_anggaran', 20, 2);
            $table->decimal('realisasi', 20, 2)->default(0);
            $table->decimal('sisa_anggaran', 20, 2)->default(0);
            
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        echo "✓ Tabel anggaran berhasil dibuat\n";
    } else {
        echo "✓ Tabel anggaran sudah ada\n";
    }

    // 3. Cek dan buat tabel rekonsiliasi_bank jika belum ada
    echo "\nMemeriksa tabel rekonsiliasi_bank...\n";
    if (!Schema::hasTable('rekonsiliasi_bank')) {
        Schema::create('rekonsiliasi_bank', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rekonsiliasi', 50)->unique();
            $table->foreignId('kas_bank_id')->constrained('kas_bank')->onDelete('cascade');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            
            $table->decimal('saldo_buku_awal', 20, 2)->comment('Saldo menurut pembukuan');
            $table->decimal('saldo_bank_awal', 20, 2)->comment('Saldo menurut bank');
            
            $table->decimal('saldo_buku_akhir', 20, 2);
            $table->decimal('saldo_bank_akhir', 20, 2);
            
            $table->decimal('selisih', 20, 2)->default(0);
            $table->text('catatan_selisih')->nullable();
            
            $table->enum('status', ['draft', 'in_progress', 'completed', 'approved'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
        echo "✓ Tabel rekonsiliasi_bank berhasil dibuat\n";
    } else {
        echo "✓ Tabel rekonsiliasi_bank sudah ada\n";
    }

    // 4. Cek dan buat tabel rekonsiliasi_detail jika belum ada
    echo "\nMemeriksa tabel rekonsiliasi_detail...\n";
    if (!Schema::hasTable('rekonsiliasi_detail')) {
        Schema::create('rekonsiliasi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekonsiliasi_bank_id')->constrained('rekonsiliasi_bank')->onDelete('cascade');
            $table->foreignId('transaksi_keuangan_id')->nullable()->constrained('transaksi_keuangan')->onDelete('cascade');
            
            $table->date('tanggal');
            $table->string('nomor_referensi', 100)->nullable();
            $table->text('keterangan');
            $table->decimal('jumlah', 20, 2);
            $table->enum('tipe', ['debit', 'kredit']);
            $table->enum('status', ['matched', 'unmatched', 'pending'])->default('pending');
            
            $table->boolean('ada_di_buku')->default(false);
            $table->boolean('ada_di_bank')->default(false);
            
            $table->timestamps();
        });
        echo "✓ Tabel rekonsiliasi_detail berhasil dibuat\n";
    } else {
        echo "✓ Tabel rekonsiliasi_detail sudah ada\n";
    }

    echo "\n==============================================\n";
    echo "✓ PERBAIKAN STRUKTUR TABEL SELESAI!\n";
    echo "==============================================\n\n";

    echo "Status Tabel:\n";
    echo "✓ kategori_akun_keuangan: " . (Schema::hasTable('kategori_akun_keuangan') ? 'Ada' : 'Tidak Ada') . "\n";
    echo "✓ akun_keuangan: " . (Schema::hasTable('akun_keuangan') ? 'Ada' : 'Tidak Ada') . "\n";
    echo "✓ kas_bank: " . (Schema::hasTable('kas_bank') ? 'Ada' : 'Tidak Ada') . "\n";
    echo "✓ transaksi_keuangan: " . (Schema::hasTable('transaksi_keuangan') ? 'Ada' : 'Tidak Ada') . "\n";
    echo "✓ jurnal_umum: " . (Schema::hasTable('jurnal_umum') ? 'Ada' : 'Tidak Ada') . "\n";
    echo "✓ anggaran: " . (Schema::hasTable('anggaran') ? 'Ada' : 'Tidak Ada') . "\n";
    echo "✓ rekonsiliasi_bank: " . (Schema::hasTable('rekonsiliasi_bank') ? 'Ada' : 'Tidak Ada') . "\n";
    echo "✓ rekonsiliasi_detail: " . (Schema::hasTable('rekonsiliasi_detail') ? 'Ada' : 'Tidak Ada') . "\n";

    echo "\n==============================================\n";
    echo "Silakan refresh halaman browser Anda!\n";
    echo "==============================================\n";

} catch (\Exception $e) {
    echo "\n";
    echo "==============================================\n";
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "==============================================\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
    echo "\n";
}
