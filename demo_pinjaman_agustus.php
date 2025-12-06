<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "=== DEMO: BUAT DATA PINJAMAN & POTONGAN DARI AGUSTUS 2025 ===\n\n";

DB::beginTransaction();

try {
    // 1. Cari karyawan yang belum punya pinjaman aktif
    $karyawan = DB::table('karyawan')
        ->leftJoin('pinjaman', function($join) {
            $join->on('karyawan.nik', '=', 'pinjaman.karyawan_id')
                 ->whereIn('pinjaman.status', ['berjalan', 'dicairkan']);
        })
        ->whereNull('pinjaman.id')
        ->select('karyawan.nik', 'karyawan.nama_karyawan', 'karyawan.kode_jabatan')
        ->limit(3)
        ->get();

    if ($karyawan->count() == 0) {
        echo "❌ Tidak ada karyawan yang tersedia untuk demo\n";
        echo "Menggunakan karyawan yang sudah ada...\n\n";
        
        $karyawan = DB::table('karyawan')
            ->select('nik', 'nama_karyawan', 'kode_jabatan')
            ->limit(3)
            ->get();
    }

    echo "📋 Karyawan yang akan dibuatkan demo pinjaman:\n";
    echo str_repeat("-", 80) . "\n";
    foreach ($karyawan as $k) {
        echo "- NIK: {$k->nik} | Nama: {$k->nama_karyawan}\n";
    }
    echo str_repeat("-", 80) . "\n\n";

    $admin_user = DB::table('users')->first();
    $pinjaman_created = [];

    // 2. Buat 3 pinjaman demo
    foreach ($karyawan as $index => $k) {
        $nomor_urut = str_pad(rand(100, 999), 4, '0', STR_PAD_LEFT);
        $nomor_pinjaman = "PNJ-202508-{$nomor_urut}";
        
        // Variasi jumlah pinjaman
        $jumlah_options = [3000000, 5000000, 8000000];
        $jumlah_pengajuan = $jumlah_options[$index];
        $tenor = 12; // 12 bulan
        $bunga = 0; // Tanpa bunga untuk demo
        
        $cicilan_per_bulan = $jumlah_pengajuan / $tenor;
        
        // Insert pinjaman
        $pinjaman_id = DB::table('pinjaman')->insertGetId([
            'nomor_pinjaman' => $nomor_pinjaman,
            'kategori_peminjam' => 'crew',
            'karyawan_id' => $k->nik,
            'tanggal_pengajuan' => '2025-08-01',
            'jumlah_pengajuan' => $jumlah_pengajuan,
            'jumlah_disetujui' => $jumlah_pengajuan,
            'tujuan_pinjaman' => 'Demo pinjaman untuk testing integrasi slip gaji',
            'tenor_bulan' => $tenor,
            'bunga_persen' => $bunga,
            'tipe_bunga' => 'flat',
            'total_pokok' => $jumlah_pengajuan,
            'total_bunga' => 0,
            'total_pinjaman' => $jumlah_pengajuan,
            'cicilan_per_bulan' => $cicilan_per_bulan,
            'status' => 'berjalan',
            'diajukan_oleh' => $admin_user->id,
            'direview_oleh' => $admin_user->id,
            'tanggal_review' => '2025-08-02',
            'disetujui_oleh' => $admin_user->id,
            'tanggal_persetujuan' => '2025-08-02',
            'tanggal_pencairan' => '2025-08-05',
            'dicairkan_oleh' => $admin_user->id,
            'metode_pencairan' => 'Transfer',
            'tanggal_jatuh_tempo_pertama' => '2025-08-25',
            'tanggal_jatuh_tempo_terakhir' => '2025-07-25', // Juli 2026
            'tanggal_jatuh_tempo_setiap_bulan' => 25,
            'total_terbayar' => $cicilan_per_bulan * 3, // 3 bulan sudah dibayar (Agustus, September, Oktober)
            'sisa_pinjaman' => $jumlah_pengajuan - ($cicilan_per_bulan * 3),
            'created_at' => '2025-08-01 10:00:00',
            'updated_at' => now(),
        ]);

        $pinjaman_created[] = [
            'id' => $pinjaman_id,
            'nomor' => $nomor_pinjaman,
            'nik' => $k->nik,
            'nama' => $k->nama_karyawan,
            'jumlah' => $jumlah_pengajuan,
            'cicilan' => $cicilan_per_bulan,
        ];

        echo "✅ Pinjaman {$nomor_pinjaman} dibuat untuk {$k->nama_karyawan}\n";
        echo "   Jumlah: Rp " . number_format($jumlah_pengajuan, 0, ',', '.') . "\n";
        echo "   Cicilan: Rp " . number_format($cicilan_per_bulan, 0, ',', '.') . " x {$tenor} bulan\n";

        // 3. Buat cicilan untuk 12 bulan (Agustus 2025 - Juli 2026)
        $bulan_mulai = Carbon::create(2025, 8, 25); // 25 Agustus 2025
        
        for ($i = 1; $i <= $tenor; $i++) {
            $tanggal_jatuh_tempo = $bulan_mulai->copy()->addMonths($i - 1);
            
            // Tentukan status cicilan
            if ($i <= 3) {
                // Cicilan 1-3 (Agustus, September, Oktober) = SUDAH DIBAYAR
                $status = 'lunas';
                $tanggal_bayar = $tanggal_jatuh_tempo->copy()->addDays(rand(0, 5));
            } else {
                // Cicilan 4+ = BELUM DIBAYAR
                $status = 'belum_bayar';
                $tanggal_bayar = null;
            }
            
            $cicilan_id = DB::table('pinjaman_cicilan')->insertGetId([
                'pinjaman_id' => $pinjaman_id,
                'cicilan_ke' => $i,
                'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo->format('Y-m-d'),
                'jumlah_cicilan' => $cicilan_per_bulan,
                'jumlah_pokok' => $cicilan_per_bulan,
                'jumlah_bunga' => 0,
                'status' => $status,
                'tanggal_bayar' => $tanggal_bayar ? $tanggal_bayar->format('Y-m-d') : null,
                'jumlah_dibayar' => $status == 'lunas' ? $cicilan_per_bulan : 0,
                'sisa_cicilan' => $status == 'lunas' ? 0 : $cicilan_per_bulan,
                'metode_pembayaran' => $status == 'lunas' ? 'Potong Gaji' : null,
                'dibayar_oleh' => $status == 'lunas' ? $admin_user->id : null,
                'denda' => 0,
                'hari_terlambat' => 0,
                'keterangan' => $status == 'lunas' ? 'Dipotong otomatis dari gaji' : null,
                'created_at' => $tanggal_jatuh_tempo->copy()->subDays(5),
                'updated_at' => $status == 'lunas' ? $tanggal_bayar : $tanggal_jatuh_tempo->copy()->subDays(5),
            ]);

            // 4. Buat history pembayaran untuk cicilan yang sudah lunas
            if ($status == 'lunas') {
                DB::table('pinjaman_history')->insert([
                    'pinjaman_id' => $pinjaman_id,
                    'aksi' => 'bayar_cicilan',
                    'status_lama' => 'belum_bayar',
                    'status_baru' => 'lunas',
                    'keterangan' => "Pembayaran cicilan ke-{$i} via Potong Gaji sejumlah Rp " . number_format($cicilan_per_bulan, 0, ',', '.'),
                    'data_perubahan' => json_encode([
                        'cicilan_ke' => $i,
                        'tanggal_bayar' => $tanggal_bayar->format('Y-m-d'),
                        'jumlah' => $cicilan_per_bulan,
                        'metode' => 'Potong Gaji',
                    ]),
                    'user_id' => $admin_user->id,
                    'user_name' => $admin_user->name,
                    'created_at' => $tanggal_bayar,
                    'updated_at' => $tanggal_bayar,
                ]);
            }
        }

        echo "   ✅ 12 cicilan dibuat (3 sudah lunas, 9 belum bayar)\n\n";
    }

    echo "\n📊 GENERATE POTONGAN PINJAMAN UNTUK NOVEMBER 2025\n";
    echo str_repeat("=", 80) . "\n\n";

    // 5. Generate potongan untuk November 2025
    $cicilan_november = DB::table('pinjaman_cicilan')
        ->join('pinjaman', 'pinjaman_cicilan.pinjaman_id', '=', 'pinjaman.id')
        ->where('pinjaman.status', 'berjalan')
        ->whereYear('pinjaman_cicilan.tanggal_jatuh_tempo', 2025)
        ->whereMonth('pinjaman_cicilan.tanggal_jatuh_tempo', 11)
        ->where('pinjaman_cicilan.status', 'belum_bayar')
        ->select(
            'pinjaman_cicilan.*',
            'pinjaman.karyawan_id as nik',
            'pinjaman.nomor_pinjaman'
        )
        ->get();

    echo "Cicilan jatuh tempo November 2025: {$cicilan_november->count()} cicilan\n\n";

    foreach ($cicilan_november as $cicilan) {
        // Format baru: PPP + MM + YY + counter 3 digit
        $counter = DB::table('potongan_pinjaman_payroll')
            ->where('bulan', 11)
            ->where('tahun', 2025)
            ->count() + 1;
        
        $kode_potongan = 'PPP1125' . str_pad($counter, 3, '0', STR_PAD_LEFT);
        
        DB::table('potongan_pinjaman_payroll')->insert([
            'kode_potongan' => $kode_potongan,
            'bulan' => 11,
            'tahun' => 2025,
            'nik' => $cicilan->nik,
            'pinjaman_id' => $cicilan->pinjaman_id,
            'cicilan_id' => $cicilan->id,
            'cicilan_ke' => $cicilan->cicilan_ke,
            'jumlah_potongan' => $cicilan->jumlah_cicilan,
            'tanggal_jatuh_tempo' => $cicilan->tanggal_jatuh_tempo,
            'status' => 'dipotong', // Langsung set DIPOTONG untuk demo
            'tanggal_dipotong' => now(),
            'diproses_oleh' => $admin_user->id,
            'keterangan' => 'Demo: Auto-generated untuk November 2025',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "✅ Potongan {$kode_potongan} dibuat dan diproses (DIPOTONG)\n";
        echo "   NIK: {$cicilan->nik} | Cicilan ke-{$cicilan->cicilan_ke}\n";
        echo "   Jumlah: Rp " . number_format($cicilan->jumlah_cicilan, 0, ',', '.') . "\n\n";
    }

    DB::commit();

    echo "\n" . str_repeat("=", 80) . "\n";
    echo "✅ DEMO DATA BERHASIL DIBUAT!\n";
    echo str_repeat("=", 80) . "\n\n";

    // Tampilkan ringkasan
    echo "📋 RINGKASAN DATA DEMO:\n\n";
    
    foreach ($pinjaman_created as $p) {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Karyawan: {$p['nama']} (NIK: {$p['nik']})\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Nomor Pinjaman : {$p['nomor']}\n";
        echo "Jumlah Pinjaman: Rp " . number_format($p['jumlah'], 0, ',', '.') . "\n";
        echo "Cicilan/Bulan  : Rp " . number_format($p['cicilan'], 0, ',', '.') . " x 12 bulan\n";
        echo "Periode        : Agustus 2025 - Juli 2026\n";
        echo "\nStatus Pembayaran:\n";
        echo "  ✅ Agustus 2025   : LUNAS (Rp " . number_format($p['cicilan'], 0, ',', '.') . ")\n";
        echo "  ✅ September 2025 : LUNAS (Rp " . number_format($p['cicilan'], 0, ',', '.') . ")\n";
        echo "  ✅ Oktober 2025   : LUNAS (Rp " . number_format($p['cicilan'], 0, ',', '.') . ")\n";
        echo "  🔸 November 2025  : DIPOTONG DI SLIP GAJI (Rp " . number_format($p['cicilan'], 0, ',', '.') . ")\n";
        echo "  ⏳ Desember 2025  : Belum bayar\n";
        echo "  ⏳ Jan-Jul 2026   : Belum bayar (7 cicilan)\n";
        echo "\nTotal Terbayar: Rp " . number_format($p['cicilan'] * 3, 0, ',', '.') . " (3 bulan)\n";
        echo "Sisa Pinjaman : Rp " . number_format($p['jumlah'] - ($p['cicilan'] * 3), 0, ',', '.') . " (9 cicilan)\n";
        echo "\n";
    }

    echo "\n" . str_repeat("=", 80) . "\n";
    echo "🎯 LANGKAH SELANJUTNYA:\n";
    echo str_repeat("=", 80) . "\n";
    echo "1. Buka: Laporan > Presensi\n";
    echo "2. Pilih Format Laporan: 'Slip Gaji (Format 3)'\n";
    echo "3. Pilih Bulan: November (11)\n";
    echo "4. Pilih Tahun: 2025\n";
    echo "5. Pilih salah satu karyawan di atas atau pilih semua\n";
    echo "6. Klik: CETAK\n\n";
    echo "💡 Potongan pinjaman akan muncul di bagian POTONGAN slip gaji!\n";
    echo str_repeat("=", 80) . "\n\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
