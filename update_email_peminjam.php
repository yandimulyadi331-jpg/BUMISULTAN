<?php

/**
 * SCRIPT: Update Email Peminjam
 * 
 * Script ini untuk mengupdate email peminjam (non-crew) yang belum punya email
 * supaya bisa menerima notifikasi pinjaman jatuh tempo.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pinjaman;

echo "\n";
echo "========================================\n";
echo "   UPDATE EMAIL PEMINJAM NON-CREW      \n";
echo "========================================\n\n";

// Cek pinjaman non-crew yang belum punya email
$pinjamanTanpaEmail = Pinjaman::where('kategori_peminjam', 'non_crew')
    ->where(function($q) {
        $q->whereNull('email_peminjam')
          ->orWhere('email_peminjam', '');
    })
    ->where('status', 'berjalan')
    ->get();

echo "Ditemukan {$pinjamanTanpaEmail->count()} pinjaman non-crew tanpa email\n\n";

if ($pinjamanTanpaEmail->count() > 0) {
    echo "CONTOH UPDATE EMAIL:\n";
    echo "--------------------\n\n";
    
    foreach ($pinjamanTanpaEmail->take(3) as $pinjaman) {
        echo "Pinjaman: {$pinjaman->nomor_pinjaman}\n";
        echo "Nama: {$pinjaman->nama_peminjam}\n";
        echo "No Telp: {$pinjaman->no_telp_peminjam}\n";
        echo "\n";
        echo "Cara update email:\n";
        echo "\$pinjaman = Pinjaman::find({$pinjaman->id});\n";
        echo "\$pinjaman->email_peminjam = 'email@example.com';\n";
        echo "\$pinjaman->save();\n";
        echo "\n---\n\n";
    }
    
    echo "ATAU via SQL langsung:\n";
    echo "UPDATE pinjaman SET email_peminjam = 'email@example.com' WHERE id = [ID_PINJAMAN];\n\n";
}

// Cek pinjaman crew yang karyawannya belum punya email
$pinjamanCrewTanpaEmail = Pinjaman::where('kategori_peminjam', 'crew')
    ->with('karyawan')
    ->where('status', 'berjalan')
    ->get()
    ->filter(function($pinjaman) {
        return !$pinjaman->karyawan || !$pinjaman->karyawan->email;
    });

echo "Ditemukan {$pinjamanCrewTanpaEmail->count()} pinjaman crew yang karyawannya belum punya email\n\n";

if ($pinjamanCrewTanpaEmail->count() > 0) {
    echo "CARA UPDATE EMAIL KARYAWAN:\n";
    echo "---------------------------\n\n";
    
    foreach ($pinjamanCrewTanpaEmail->take(3) as $pinjaman) {
        echo "Pinjaman: {$pinjaman->nomor_pinjaman}\n";
        echo "NIK: {$pinjaman->karyawan_id}\n";
        echo "Nama: " . ($pinjaman->karyawan->nama_lengkap ?? 'N/A') . "\n";
        echo "\n";
        echo "Cara update email di tabel karyawan:\n";
        echo "\$karyawan = Karyawan::where('nik', '{$pinjaman->karyawan_id}')->first();\n";
        echo "\$karyawan->email = 'email@example.com';\n";
        echo "\$karyawan->save();\n";
        echo "\n---\n\n";
    }
    
    echo "ATAU via SQL langsung:\n";
    echo "UPDATE karyawan SET email = 'email@example.com' WHERE nik = '[NIK]';\n\n";
}

echo "========================================\n";
echo "   CONTOH UPDATE MASSAL (HATI-HATI!)   \n";
echo "========================================\n\n";

echo "Jika ingin update email secara massal, bisa gunakan script berikut:\n\n";
echo "<?php\n";
echo "// Contoh: Update email untuk semua pinjaman non-crew dengan pola tertentu\n";
echo "\$pinjamanList = [\n";
echo "    ['id' => 1, 'email' => 'peminjam1@example.com'],\n";
echo "    ['id' => 2, 'email' => 'peminjam2@example.com'],\n";
echo "    // ... dst\n";
echo "];\n\n";
echo "foreach (\$pinjamanList as \$data) {\n";
echo "    \$pinjaman = Pinjaman::find(\$data['id']);\n";
echo "    if (\$pinjaman) {\n";
echo "        \$pinjaman->email_peminjam = \$data['email'];\n";
echo "        \$pinjaman->save();\n";
echo "        echo \"✓ Updated pinjaman ID {\$data['id']}\\n\";\n";
echo "    }\n";
echo "}\n";
echo "?>\n\n";

echo "========================================\n\n";

echo "💡 TIPS:\n";
echo "1. Untuk crew: Update email di tabel karyawan\n";
echo "2. Untuk non-crew: Update email_peminjam di tabel pinjaman\n";
echo "3. Gunakan email yang valid dan aktif\n";
echo "4. Test kirim notifikasi setelah update email\n\n";
