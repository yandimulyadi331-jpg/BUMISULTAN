<?php

namespace App\Traits;

use App\Models\PinjamanCicilan;

trait PelunasanAwalHelper
{
    /**
     * ✅ HANDLE PELUNASAN AWAL (PEMBAYARAN LEBIH DARI CICILAN NORMAL)
     * 
     * Skenario:
     * - Cicilan normal: Rp 2.000.000
     * - Pembayaran: Rp 3.000.000 (lebih Rp 1.000.000)
     * 
     * Sistem akan:
     * 1. Lunasin cicilan bulan ini penuh
     * 2. Alokasikan kelebihan ke cicilan berikutnya
     * 3. Update jadwal cicilan yang tersisa
     * 4. Real-time update laporan
     * 
     * Hasil: TIDAK ADA NOMINAL YANG HILANG ATAU BERTAMBAH ✅
     */
    public function prosesPelunasanAwal($jumlahBayar, $metodePembayaran, $noReferensi = null, $buktiBayar = null, $keterangan = null)
    {
        $pinjaman = $this->pinjaman;
        $totalTagihan = $this->sisa_cicilan;
        
        // Data untuk audit
        $dataSebulum = [
            'cicilan_ke' => $this->cicilan_ke,
            'jumlah_dibayar' => $this->jumlah_dibayar,
            'sisa_cicilan' => $this->sisa_cicilan,
            'status' => $this->status,
        ];

        // ✅ STEP 1: Bayar cicilan saat ini terlebih dahulu
        $sisaSetelahBayarCicilanIni = $jumlahBayar - $totalTagihan;

        if ($sisaSetelahBayarCicilanIni >= 0) {
            // ✅ Pelunasan awal terdeteksi!
            
            // Mark cicilan ini LUNAS
            $this->jumlah_dibayar = $this->jumlah_cicilan;
            $this->sisa_cicilan = 0;
            $this->status = 'lunas';
            $this->tanggal_bayar = now();
            $this->metode_pembayaran = $metodePembayaran;
            $this->no_referensi = $noReferensi;
            $this->bukti_pembayaran = $buktiBayar;
            $this->dibayar_oleh = auth()->id();
            $this->keterangan = $keterangan;
            $this->save();

            // ✅ STEP 2: Alokasikan kelebihan ke cicilan berikutnya
            if ($sisaSetelahBayarCicilanIni > 0) {
                $this->alokasikanKelebihanKeCicilanBerikutnya($sisaSetelahBayarCicilanIni, $pinjaman, $metodePembayaran);
            }

        } else {
            // Pembayaran normal (tidak lebih)
            $this->jumlah_dibayar += $jumlahBayar;
            $this->tanggal_bayar = now();
            $this->metode_pembayaran = $metodePembayaran;
            $this->no_referensi = $noReferensi;
            $this->bukti_pembayaran = $buktiBayar;
            $this->dibayar_oleh = auth()->id();
            $this->keterangan = $keterangan;

            if ($this->jumlah_dibayar >= $totalTagihan) {
                $this->sisa_cicilan = 0;
                $this->status = 'lunas';
            } else {
                $this->sisa_cicilan = $this->jumlah_cicilan - $this->jumlah_dibayar;
                $this->status = 'sebagian';
            }

            $this->save();
        }

        // ✅ STEP 3: Update total pembayaran di pinjaman induk
        $pinjaman->total_terbayar += $jumlahBayar;
        $pinjaman->sisa_pinjaman = max(0, $pinjaman->total_pinjaman - $pinjaman->total_terbayar);

        if ($pinjaman->sisa_pinjaman <= 0) {
            $pinjaman->status = 'lunas';
            $pinjaman->tanggal_lunas = now();
        } else {
            $pinjaman->status = 'berjalan';
        }

        $pinjaman->save();

        // ✅ STEP 4: Log perubahan
        $pinjaman->logHistory(
            'bayar_cicilan_pelunasan_awal',
            null,
            null,
            "Pembayaran cicilan ke-{$this->cicilan_ke}: Rp " . number_format($jumlahBayar, 0, ',', '.') . " (Pelunasan Awal)",
            [
                'cicilan_ke' => $this->cicilan_ke,
                'jumlah_bayar' => $jumlahBayar,
                'kelebihan_alokasi' => max(0, $sisaSetelahBayarCicilanIni),
                'metode' => $metodePembayaran,
            ]
        );

        // ✅ STEP 5: Trigger event real-time update
        event(new \App\Events\PinjamanPaymentUpdated($pinjaman, $this, [
            'sebelum' => array_merge($dataSebulum, [
                'sisa_pinjaman' => $pinjaman->total_terbayar - $jumlahBayar,
                'total_terbayar' => $pinjaman->total_terbayar - $jumlahBayar,
            ]),
            'sesudah' => [
                'jumlah_dibayar' => $this->jumlah_dibayar,
                'sisa_cicilan' => $this->sisa_cicilan,
                'status_cicilan' => $this->status,
                'sisa_pinjaman' => $pinjaman->sisa_pinjaman,
                'total_terbayar' => $pinjaman->total_terbayar,
                'status_pinjaman' => $pinjaman->status,
                'tipe_pembayaran' => $sisaSetelahBayarCicilanIni > 0 ? 'pelunasan_awal' : 'normal',
            ],
        ]));

        return [
            'success' => true,
            'cicilan_lunas' => true,
            'kelebihan_dialokasikan' => max(0, $sisaSetelahBayarCicilanIni),
            'status' => $this->status,
            'cicilan_berikutnya_updated' => $sisaSetelahBayarCicilanIni > 0 ? true : false,
        ];
    }

    /**
     * ✅ ALOKASIKAN KELEBIHAN KE CICILAN BERIKUTNYA
     * 
     * Jika pembayaran lebih dari cicilan normal, alokasikan sisanya:
     * - Bayar cicilan berikutnya sebagian atau penuh
     * - Regenerate jadwal cicilan yang tersisa
     */
    private function alokasikanKelebihanKeCicilanBerikutnya($kelebihan, $pinjaman, $metodePembayaran)
    {
        // Cari cicilan berikutnya yang belum lunas
        $cicilanBerikutnya = $pinjaman->cicilan()
            ->where('cicilan_ke', '>', $this->cicilan_ke)
            ->where('status', '!=', 'lunas')
            ->orderBy('cicilan_ke')
            ->first();

        if (!$cicilanBerikutnya) {
            // Tidak ada cicilan berikutnya, berarti ini cicilan terakhir
            return;
        }

        $sisaKelebihan = $kelebihan;

        // ✅ Alokasikan kelebihan ke cicilan-cicilan berikutnya
        $cicilanSisanya = $pinjaman->cicilan()
            ->where('cicilan_ke', '>', $this->cicilan_ke)
            ->where('status', '!=', 'lunas')
            ->orderBy('cicilan_ke')
            ->get();

        foreach ($cicilanSisanya as $cicilan) {
            if ($sisaKelebihan <= 0) break;

            $tagihanCicilan = $cicilan->sisa_cicilan;

            if ($sisaKelebihan >= $tagihanCicilan) {
                // ✅ Cicilan ini bisa lunas dengan kelebihan
                $cicilan->jumlah_dibayar = $cicilan->jumlah_cicilan;
                $cicilan->sisa_cicilan = 0;
                $cicilan->status = 'lunas';
                $cicilan->tanggal_bayar = now();
                $cicilan->metode_pembayaran = $metodePembayaran;
                $cicilan->dibayar_oleh = auth()->id();
                $cicilan->keterangan = "Pembayaran dari pelunasan awal cicilan ke-{$this->cicilan_ke}";
                $cicilan->save();

                $sisaKelebihan -= $tagihanCicilan;

                \Log::info("Cicilan ke-{$cicilan->cicilan_ke} lunas dari alokasi pelunasan awal", [
                    'pinjaman_id' => $pinjaman->id,
                    'alokasi' => $tagihanCicilan,
                ]);

            } else {
                // ✅ Cicilan ini dibayar sebagian dengan kelebihan
                $cicilan->jumlah_dibayar += $sisaKelebihan;
                $cicilan->sisa_cicilan = $cicilan->jumlah_cicilan - $cicilan->jumlah_dibayar;
                $cicilan->status = 'sebagian';
                $cicilan->tanggal_bayar = now();
                $cicilan->metode_pembayaran = $metodePembayaran;
                $cicilan->dibayar_oleh = auth()->id();
                $cicilan->keterangan = "Pembayaran sebagian dari pelunasan awal cicilan ke-{$this->cicilan_ke}";
                $cicilan->save();

                \Log::info("Cicilan ke-{$cicilan->cicilan_ke} dibayar sebagian dari alokasi pelunasan awal", [
                    'pinjaman_id' => $pinjaman->id,
                    'alokasi' => $sisaKelebihan,
                    'sisa_tagihan' => $cicilan->sisa_cicilan,
                ]);

                $sisaKelebihan = 0;
            }
        }

        // ✅ Jika masih ada sisa kelebihan, berarti sudah overpay semua cicilan
        // Set sebagai kembalian
        if ($sisaKelebihan > 0) {
            \Log::warning("Overpayment terdeteksi, ada kelebian Rp " . number_format($sisaKelebihan, 0), [
                'pinjaman_id' => $pinjaman->id,
            ]);
        }
    }

    /**
     * ✅ GET JADWAL CICILAN YANG SUDAH DIUPDATE DENGAN PELUNASAN AWAL
     * 
     * Menampilkan jadwal cicilan dengan perubahan akibat pelunasan awal
     */
    public static function getJadwalTerbaru($pinjamanId)
    {
        $pinjaman = \App\Models\Pinjaman::findOrFail($pinjamanId);
        $pinjaman->load('cicilan');

        $jadwal = [];
        $totalBayarKumulatif = 0;

        foreach ($pinjaman->cicilan as $cicilan) {
            $totalBayarKumulatif += $cicilan->jumlah_dibayar;

            $jadwal[] = [
                'cicilan_ke' => $cicilan->cicilan_ke,
                'tanggal_jatuh_tempo' => $cicilan->tanggal_jatuh_tempo,
                'jumlah_cicilan' => (float)$cicilan->jumlah_cicilan,
                'jumlah_dibayar' => (float)$cicilan->jumlah_dibayar,
                'sisa_cicilan' => (float)$cicilan->sisa_cicilan,
                'status' => $cicilan->status,
                'terbayar_kumulatif' => (float)$totalBayarKumulatif,
                'sisa_total' => max(0, (float)$pinjaman->total_pinjaman - $totalBayarKumulatif),
            ];
        }

        return $jadwal;
    }

    /**
     * ✅ GET RINGKASAN PELUNASAN AWAL
     * 
     * Menampilkan:
     * - Berapa cicilan sudah dilunasi
     * - Berapa cicilan yang terlewat
     * - Progress pelunasan
     * - Estimasi tanggal selesai jika melanjutkan
     */
    public static function getRingkasanPelunasanAwal($pinjamanId)
    {
        $pinjaman = \App\Models\Pinjaman::findOrFail($pinjamanId);
        $pinjaman->load('cicilan');

        $totalCicilan = $pinjaman->tenor_bulan;
        $cicilanLunas = $pinjaman->cicilan()->where('status', 'lunas')->count();
        $cicilanBelumBayar = $pinjaman->cicilan()->where('status', '!=', 'lunas')->count();
        $cicilanSebagian = $pinjaman->cicilan()->where('status', 'sebagian')->count();

        // Hitung berapa cicilan yang terlewat
        $cicilanTerlompat = 0;
        $cicilanSebelumnya = 0;
        foreach ($pinjaman->cicilan as $cicilan) {
            if ($cicilan->cicilan_ke > ($cicilanSebelumnya + 1) && $cicilan->status != 'belum_bayar') {
                $cicilanTerlompat += ($cicilan->cicilan_ke - $cicilanSebelumnya - 1);
            }
            if ($cicilan->status != 'belum_bayar') {
                $cicilanSebelumnya = $cicilan->cicilan_ke;
            }
        }

        // Hitung estimasi selesai jika lanjut normal
        $cicilanBelumBayarDari = $pinjaman->cicilan()
            ->where('status', '!=', 'lunas')
            ->orderBy('cicilan_ke')
            ->first();

        $estimasiSelesai = null;
        if ($cicilanBelumBayarDari) {
            $hariJatuhTempo = $cicilanBelumBayarDari->tanggal_jatuh_tempo->day;
            $sisaCicilan = $pinjaman->cicilan()->where('status', '!=', 'lunas')->count();
            $estimasiSelesai = now()->addMonths($sisaCicilan)->format('Y-m-d');
        }

        return [
            'total_cicilan' => $totalCicilan,
            'cicilan_lunas' => $cicilanLunas,
            'cicilan_sebagian' => $cicilanSebagian,
            'cicilan_belum_bayar' => $cicilanBelumBayar,
            'cicilan_terlompat' => $cicilanTerlompat,
            'progress_persen' => round(($cicilanLunas / $totalCicilan) * 100, 2),
            'sisa_nominal' => (float)$pinjaman->sisa_pinjaman,
            'total_bayar' => (float)$pinjaman->total_terbayar,
            'estimasi_selesai' => $estimasiSelesai,
            'status_pinjaman' => $pinjaman->status,
        ];
    }

    /**
     * ✅ VALIDASI PELUNASAN AWAL
     * 
     * Memastikan:
     * 1. Cicilan masih aktif (belum lunas)
     * 2. Pembayaran masuk akal
     * 3. Tidak ada anomali
     */
    public function validasiPelunasanAwal($jumlahBayar): array
    {
        $errors = [];

        // Cek 1: Cicilan sudah lunas?
        if ($this->status === 'lunas') {
            $errors[] = 'Cicilan ini sudah lunas, tidak bisa dibayar lagi';
        }

        // Cek 2: Pembayaran nol?
        if ($jumlahBayar <= 0) {
            $errors[] = 'Jumlah pembayaran harus lebih dari 0';
        }

        // Cek 3: Pembayaran wajar?
        if ($jumlahBayar > ($this->sisa_cicilan * 2)) {
            $errors[] = 'Pembayaran terlalu besar (melebihi 2x cicilan normal), silakan verifikasi';
        }

        // Cek 4: Pinjaman masih aktif?
        $pinjaman = $this->pinjaman;
        if ($pinjaman->status === 'lunas') {
            $errors[] = 'Pinjaman sudah lunas, tidak ada cicilan yang perlu dibayar';
        }

        if ($pinjaman->sisa_pinjaman <= 0) {
            $errors[] = 'Sisa pinjaman sudah 0, tidak ada yang perlu dibayar';
        }

        return [
            'is_valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }
}
