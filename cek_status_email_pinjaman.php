<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pinjaman;
use App\Models\PinjamanEmailNotification;

echo "\n";
echo "========================================\n";
echo "   CEK STATUS EMAIL DI PINJAMAN\n";
echo "========================================\n\n";

// Ambil pinjaman dengan emailNotifications
$pinjamanList = Pinjaman::with(['karyawan', 'emailNotifications'])
    ->whereIn('status', ['dicairkan', 'berjalan'])
    ->limit(10)
    ->get();

echo "📊 DAFTAR PINJAMAN & STATUS EMAIL:\n\n";

foreach ($pinjamanList as $pinjaman) {
    echo "┌─────────────────────────────────────────────────────────────┐\n";
    echo "│ No. Pinjaman  : {$pinjaman->nomor_pinjaman}\n";
    echo "│ Nama          : {$pinjaman->nama_peminjam}\n";
    echo "│ Kategori      : " . strtoupper($pinjaman->kategori_peminjam) . "\n";
    
    // Cek email tersedia
    $emailTersedia = false;
    $emailTujuan = null;
    
    if ($pinjaman->kategori_peminjam === 'crew' && $pinjaman->karyawan && $pinjaman->karyawan->email) {
        $emailTersedia = true;
        $emailTujuan = $pinjaman->karyawan->email;
    } elseif ($pinjaman->kategori_peminjam === 'non_crew' && $pinjaman->email_peminjam) {
        $emailTersedia = true;
        $emailTujuan = $pinjaman->email_peminjam;
    }
    
    if ($emailTersedia) {
        echo "│ 📧 Email       : {$emailTujuan}\n";
        
        // Cek email terakhir
        $lastEmail = $pinjaman->emailNotifications()
            ->where('status', 'sent')
            ->latest('sent_at')
            ->first();
        
        if ($lastEmail) {
            echo "│ ✅ Status      : TERKIRIM\n";
            echo "│ 📅 Terakhir    : {$lastEmail->sent_at->format('d M Y H:i')}\n";
            echo "│ 🕐 Sejak       : {$lastEmail->sent_at->diffForHumans()}\n";
            echo "│ 📋 Tipe        : {$lastEmail->tipe_notifikasi}\n";
        } else {
            echo "│ ⏰ Status      : BELUM PERNAH DIKIRIM\n";
        }
    } else {
        echo "│ ❌ Email       : TIDAK ADA\n";
    }
    
    echo "└─────────────────────────────────────────────────────────────┘\n\n";
}

// Statistik email
echo "\n========================================\n";
echo "📊 STATISTIK EMAIL NOTIFIKASI\n";
echo "========================================\n\n";

$stats = [
    'total_sent' => PinjamanEmailNotification::where('status', 'sent')->count(),
    'total_failed' => PinjamanEmailNotification::where('status', 'failed')->count(),
    'total_pending' => PinjamanEmailNotification::where('status', 'pending')->count(),
];

echo "✅ Email Terkirim  : {$stats['total_sent']}\n";
echo "❌ Email Gagal     : {$stats['total_failed']}\n";
echo "⏳ Email Pending   : {$stats['total_pending']}\n";

// Email terakhir 5
echo "\n📧 5 EMAIL TERAKHIR DIKIRIM:\n\n";

$recentEmails = PinjamanEmailNotification::with('pinjaman')
    ->where('status', 'sent')
    ->latest('sent_at')
    ->limit(5)
    ->get();

foreach ($recentEmails as $email) {
    echo "  → {$email->sent_at->format('d M Y H:i')} | ";
    echo "{$email->pinjaman->nomor_pinjaman} | ";
    echo "{$email->email_tujuan} | ";
    echo "({$email->tipe_notifikasi})\n";
}

echo "\n========================================\n";
echo "🎉 FITUR STATUS EMAIL SIAP DIGUNAKAN!\n";
echo "========================================\n\n";

echo "💡 Cara Pakai:\n";
echo "1. Buka: http://localhost:8000/pinjaman\n";
echo "2. Lihat kolom '📧 Email' di tabel\n";
echo "3. Klik tombol '📤 Kirim' untuk kirim email manual\n";
echo "4. Status akan update otomatis setelah kirim\n\n";
