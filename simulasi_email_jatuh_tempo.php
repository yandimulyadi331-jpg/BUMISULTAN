<?php

/**
 * SIMULASI: Kirim Email Notifikasi Pinjaman Jatuh Tempo
 * Script untuk simulasi kirim email notifikasi seperti pinjaman betulan
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pinjaman;
use App\Mail\PinjamanJatuhTempoMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

echo "\n";
echo "========================================\n";
echo "   SIMULASI EMAIL JATUH TEMPO          \n";
echo "========================================\n\n";

// Buat data pinjaman dummy untuk simulasi
$pinjamanDummy = new Pinjaman();
$pinjamanDummy->id = 999;
$pinjamanDummy->nomor_pinjaman = 'PNJ-202511-DEMO';
$pinjamanDummy->kategori_peminjam = 'non_crew';
$pinjamanDummy->nama_peminjam = 'Budi Santoso (DEMO)';
$pinjamanDummy->email_peminjam = 'manajemenbumisultan@gmail.com';
$pinjamanDummy->cicilan_per_bulan = 1000000;
$pinjamanDummy->total_pinjaman = 12000000;
$pinjamanDummy->total_terbayar = 5000000;
$pinjamanDummy->sisa_pinjaman = 7000000;
$pinjamanDummy->tanggal_jatuh_tempo_setiap_bulan = 25; // Tanggal 25 setiap bulan
$pinjamanDummy->status = 'berjalan';
$pinjamanDummy->exists = false; // Tandai sebagai dummy, bukan dari database

$emailTujuan = 'manajemenbumisultan@gmail.com';

echo "📧 SIMULASI NOTIFIKASI PINJAMAN JATUH TEMPO\n\n";
echo "Detail Pinjaman (DEMO):\n";
echo "  Nomor: {$pinjamanDummy->nomor_pinjaman}\n";
echo "  Nama: {$pinjamanDummy->nama_peminjam}\n";
echo "  Cicilan: Rp " . number_format($pinjamanDummy->cicilan_per_bulan, 0, ',', '.') . "\n";
echo "  Total: Rp " . number_format($pinjamanDummy->total_pinjaman, 0, ',', '.') . "\n";
echo "  Terbayar: Rp " . number_format($pinjamanDummy->total_terbayar, 0, ',', '.') . "\n";
echo "  Sisa: Rp " . number_format($pinjamanDummy->sisa_pinjaman, 0, ',', '.') . "\n";
echo "  Jatuh Tempo: Tanggal {$pinjamanDummy->tanggal_jatuh_tempo_setiap_bulan} setiap bulan\n";
echo "  Email Tujuan: {$emailTujuan}\n\n";

echo "Pilih tipe notifikasi:\n";
echo "  1. Jatuh Tempo 7 Hari Lagi (H-7)\n";
echo "  2. Jatuh Tempo 3 Hari Lagi (H-3)\n";
echo "  3. Jatuh Tempo BESOK (H-1)\n";
echo "  4. Jatuh Tempo HARI INI (H-0)\n";
echo "  5. Sudah LEWAT Jatuh Tempo\n";
echo "  6. Kirim SEMUA Tipe (5 email sekaligus)\n\n";

// Auto pilih option 4 (hari ini) untuk demo
$pilihan = 4;
echo "🎯 Otomatis dipilih: Option {$pilihan} (Jatuh Tempo HARI INI)\n\n";

$tipeMap = [
    1 => ['tipe' => 'jatuh_tempo_7_hari', 'hari' => 7, 'label' => 'H-7 (7 hari lagi)'],
    2 => ['tipe' => 'jatuh_tempo_3_hari', 'hari' => 3, 'label' => 'H-3 (3 hari lagi)'],
    3 => ['tipe' => 'jatuh_tempo_besok', 'hari' => 1, 'label' => 'H-1 (BESOK)'],
    4 => ['tipe' => 'jatuh_tempo_hari_ini', 'hari' => 0, 'label' => 'H-0 (HARI INI)'],
    5 => ['tipe' => 'sudah_lewat_jatuh_tempo', 'hari' => null, 'label' => 'SUDAH LEWAT'],
];

$notifikasi = [];
if ($pilihan == 6) {
    // Kirim semua tipe
    $notifikasi = array_values($tipeMap);
} else {
    $notifikasi[] = $tipeMap[$pilihan];
}

echo "📤 Mengirim email...\n\n";

$berhasil = 0;
$gagal = 0;

foreach ($notifikasi as $notif) {
    try {
        echo "  → Mengirim: {$notif['label']}... ";
        
        Mail::to($emailTujuan)->send(
            new PinjamanJatuhTempoMail($pinjamanDummy, $notif['tipe'], $notif['hari'])
        );
        
        echo "✅ BERHASIL!\n";
        $berhasil++;
        
        // Delay 2 detik jika kirim multiple
        if (count($notifikasi) > 1) {
            sleep(2);
        }
        
    } catch (\Exception $e) {
        echo "❌ GAGAL!\n";
        echo "     Error: " . $e->getMessage() . "\n";
        $gagal++;
    }
}

echo "\n========================================\n";
echo "📊 RINGKASAN:\n";
echo "  ✅ Berhasil: {$berhasil} email\n";
if ($gagal > 0) {
    echo "  ❌ Gagal: {$gagal} email\n";
}
echo "========================================\n\n";

if ($berhasil > 0) {
    echo "🎉 EMAIL BERHASIL DIKIRIM!\n\n";
    echo "📬 Cek email Anda sekarang:\n";
    echo "   Email: {$emailTujuan}\n";
    echo "   Subject: Akan ada emoji 🔔⏰📅⚠️ sesuai tipe\n";
    echo "   Isi: Detail pinjaman + tanggal jatuh tempo\n\n";
    echo "💡 Tips:\n";
    echo "   - Cek Inbox terlebih dahulu\n";
    echo "   - Jika tidak ada, cek folder SPAM/Junk\n";
    echo "   - Email dari: " . config('mail.from.address') . "\n\n";
} else {
    echo "❌ Semua email gagal dikirim!\n\n";
    echo "Kemungkinan penyebab:\n";
    echo "1. Konfigurasi SMTP di .env salah\n";
    echo "2. App Password tidak valid\n";
    echo "3. Koneksi internet bermasalah\n\n";
}

echo "========================================\n\n";

// Info tambahan untuk kirim semua tipe
if ($pilihan == 6) {
    echo "ℹ️  CATATAN: Untuk kirim semua tipe, ganti \$pilihan = 6 di script\n\n";
}
