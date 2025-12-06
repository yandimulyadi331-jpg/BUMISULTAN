<?php

/**
 * DEMO: Notifikasi Email Pinjaman Jatuh Tempo
 * 
 * Script ini untuk demonstrasi dan testing fitur notifikasi email
 * yang otomatis mengirim pengingat ke peminjam saat cicilan mereka
 * akan jatuh tempo atau sudah lewat jatuh tempo.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pinjaman;
use App\Models\PinjamanEmailNotification;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "========================================\n";
echo "   DEMO: NOTIFIKASI EMAIL PINJAMAN     \n";
echo "========================================\n\n";

// 1. Cek konfigurasi email
echo "1. CEK KONFIGURASI EMAIL:\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_FROM: " . config('mail.from.address') . "\n\n";

// 2. Cek pinjaman yang sedang berjalan
echo "2. CEK PINJAMAN BERJALAN:\n";
$pinjamanBerjalan = Pinjaman::where('status', 'berjalan')
    ->with('karyawan')
    ->get();

echo "   Total pinjaman berjalan: " . $pinjamanBerjalan->count() . "\n\n";

if ($pinjamanBerjalan->count() > 0) {
    echo "   Detail pinjaman:\n";
    foreach ($pinjamanBerjalan->take(5) as $pinjaman) {
        echo "   - {$pinjaman->nomor_pinjaman}\n";
        echo "     Peminjam: " . ($pinjaman->karyawan->nama_lengkap ?? $pinjaman->nama_peminjam) . "\n";
        echo "     Email: " . ($pinjaman->karyawan->email ?? $pinjaman->email_peminjam ?? 'TIDAK ADA') . "\n";
        echo "     Tanggal JT: Setiap tanggal {$pinjaman->tanggal_jatuh_tempo_setiap_bulan}\n";
        echo "     Cicilan: Rp " . number_format($pinjaman->cicilan_per_bulan, 0, ',', '.') . "\n";
        echo "     Sisa: Rp " . number_format($pinjaman->sisa_pinjaman, 0, ',', '.') . "\n\n";
    }
    
    if ($pinjamanBerjalan->count() > 5) {
        echo "   ... dan " . ($pinjamanBerjalan->count() - 5) . " pinjaman lainnya\n\n";
    }
}

// 3. Cek log notifikasi email
echo "3. CEK LOG NOTIFIKASI EMAIL:\n";
$totalNotifikasi = DB::table('pinjaman_email_notifications')->count();
$notifikasiSent = DB::table('pinjaman_email_notifications')->where('status', 'sent')->count();
$notifikasiFailed = DB::table('pinjaman_email_notifications')->where('status', 'failed')->count();
$notifikasiPending = DB::table('pinjaman_email_notifications')->where('status', 'pending')->count();

echo "   Total notifikasi: {$totalNotifikasi}\n";
echo "   - Berhasil dikirim: {$notifikasiSent}\n";
echo "   - Gagal dikirim: {$notifikasiFailed}\n";
echo "   - Pending: {$notifikasiPending}\n\n";

// 4. Notifikasi terakhir dikirim
echo "4. NOTIFIKASI TERAKHIR DIKIRIM:\n";
$notifikasiTerakhir = DB::table('pinjaman_email_notifications')
    ->join('pinjaman', 'pinjaman_email_notifications.pinjaman_id', '=', 'pinjaman.id')
    ->where('pinjaman_email_notifications.status', 'sent')
    ->orderBy('pinjaman_email_notifications.sent_at', 'desc')
    ->limit(5)
    ->select([
        'pinjaman.nomor_pinjaman',
        'pinjaman_email_notifications.email_tujuan',
        'pinjaman_email_notifications.tipe_notifikasi',
        'pinjaman_email_notifications.sent_at',
    ])
    ->get();

if ($notifikasiTerakhir->count() > 0) {
    foreach ($notifikasiTerakhir as $notif) {
        echo "   - {$notif->nomor_pinjaman}\n";
        echo "     Email: {$notif->email_tujuan}\n";
        echo "     Tipe: {$notif->tipe_notifikasi}\n";
        echo "     Dikirim: " . \Carbon\Carbon::parse($notif->sent_at)->format('d M Y H:i') . "\n\n";
    }
} else {
    echo "   Belum ada notifikasi yang dikirim\n\n";
}

// 5. Simulasi pengecekan pinjaman jatuh tempo
echo "5. SIMULASI PENGECEKAN JATUH TEMPO HARI INI:\n";
$hariIni = now()->day;
echo "   Hari ini tanggal: {$hariIni}\n\n";

$pinjamanJatuhTempo = Pinjaman::where('status', 'berjalan')
    ->with('karyawan')
    ->get()
    ->filter(function($pinjaman) use ($hariIni) {
        $tanggalJT = (int) $pinjaman->tanggal_jatuh_tempo_setiap_bulan;
        $selisih = $tanggalJT - $hariIni;
        
        // Cek apakah hari ini adalah salah satu trigger notifikasi
        return in_array($selisih, [0, 1, 3, 7]) || $selisih < 0;
    });

echo "   Pinjaman yang perlu notifikasi: " . $pinjamanJatuhTempo->count() . "\n";

if ($pinjamanJatuhTempo->count() > 0) {
    foreach ($pinjamanJatuhTempo as $pinjaman) {
        $tanggalJT = (int) $pinjaman->tanggal_jatuh_tempo_setiap_bulan;
        $selisih = $tanggalJT - $hariIni;
        
        $tipeNotif = match(true) {
            $selisih == 0 => 'HARI INI',
            $selisih == 1 => 'BESOK (H-1)',
            $selisih == 3 => 'H-3',
            $selisih == 7 => 'H-7',
            $selisih < 0 => 'SUDAH LEWAT',
            default => 'TIDAK PERLU',
        };
        
        echo "   - {$pinjaman->nomor_pinjaman}\n";
        echo "     Peminjam: " . ($pinjaman->karyawan->nama_lengkap ?? $pinjaman->nama_peminjam) . "\n";
        echo "     JT Tanggal: {$tanggalJT}\n";
        echo "     Tipe Notif: {$tipeNotif}\n";
        echo "     Email: " . ($pinjaman->karyawan->email ?? $pinjaman->email_peminjam ?? 'TIDAK ADA') . "\n\n";
    }
}

// 6. Statistik notifikasi per tipe
echo "6. STATISTIK NOTIFIKASI PER TIPE:\n";
$stats = DB::table('pinjaman_email_notifications')
    ->select('tipe_notifikasi', DB::raw('COUNT(*) as total'))
    ->groupBy('tipe_notifikasi')
    ->get();

if ($stats->count() > 0) {
    foreach ($stats as $stat) {
        echo "   - {$stat->tipe_notifikasi}: {$stat->total} notifikasi\n";
    }
} else {
    echo "   Belum ada data\n";
}

echo "\n";

// 7. Cara menjalankan command
echo "7. CARA MENJALANKAN NOTIFIKASI:\n\n";
echo "   MODE TESTING (tidak kirim email betulan):\n";
echo "   php artisan pinjaman:send-jatuh-tempo-notifications --test\n\n";
echo "   MODE PRODUCTION (kirim email betulan):\n";
echo "   php artisan pinjaman:send-jatuh-tempo-notifications\n\n";

// 8. Cek scheduler
echo "8. CEK SCHEDULER:\n";
echo "   Notifikasi dijadwalkan otomatis setiap hari jam 08:00 WIB\n";
echo "   \n";
echo "   Jalankan scheduler untuk development:\n";
echo "   php artisan schedule:work\n\n";
echo "   Atau test manual:\n";
echo "   php artisan schedule:run\n\n";

echo "========================================\n";
echo "         DEMO SELESAI                   \n";
echo "========================================\n\n";

echo "💡 TIPS:\n";
echo "1. Pastikan email sudah dikonfigurasi di .env\n";
echo "2. Untuk crew, email diambil dari tabel karyawan\n";
echo "3. Untuk non-crew, pastikan isi email_peminjam saat input pinjaman\n";
echo "4. Test dengan mode --test terlebih dahulu\n";
echo "5. Setup scheduler untuk berjalan otomatis\n\n";

echo "📖 Dokumentasi lengkap: DOKUMENTASI_NOTIFIKASI_EMAIL_PINJAMAN.md\n";
echo "⚡ Quick start: QUICK_START_NOTIFIKASI_PINJAMAN.md\n\n";
