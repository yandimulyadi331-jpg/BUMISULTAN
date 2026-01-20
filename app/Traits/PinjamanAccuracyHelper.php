<?php

namespace App\Traits;

use App\Models\Pinjaman;
use Illuminate\Support\Facades\DB;

trait PinjamanAccuracyHelper
{
    /**
     * ✅ VERIFIKASI AKURASI PINJAMAN REAL-TIME
     * 
     * Memastikan:
     * 1. total_pinjaman = total_terbayar + sisa_pinjaman (selalu 100% akurat)
     * 2. Tidak ada nominal yang hilang atau kesalip
     * 3. Laporan selalu menampilkan data terkini
     */
    public static function verifikasiAkurasi(Pinjaman $pinjaman): array
    {
        $result = [
            'is_akurat' => true,
            'selisih' => 0,
            'pesan' => 'Data akurat ✅',
            'detail' => [],
        ];

        // Hitung dari sumber kebenaran tunggal: tabel cicilan
        $cicilanData = $pinjaman->cicilan()
            ->selectRaw('
                COUNT(*) as total_cicilan,
                SUM(jumlah_cicilan) as total_nominal_cicilan,
                SUM(jumlah_dibayar) as total_dibayar_aktual,
                SUM(sisa_cicilan) as total_sisa_aktual,
                SUM(CASE WHEN status = "lunas" THEN 1 ELSE 0 END) as cicilan_lunas,
                SUM(CASE WHEN status = "sebagian" THEN 1 ELSE 0 END) as cicilan_sebagian,
                SUM(CASE WHEN status = "belum_bayar" THEN 1 ELSE 0 END) as cicilan_belum_bayar
            ')
            ->first();

        $result['detail']['cicilan_data'] = [
            'total_cicilan' => $cicilanData->total_cicilan ?? 0,
            'cicilan_lunas' => $cicilanData->cicilan_lunas ?? 0,
            'cicilan_sebagian' => $cicilanData->cicilan_sebagian ?? 0,
            'cicilan_belum_bayar' => $cicilanData->cicilan_belum_bayar ?? 0,
        ];

        // Sumber kebenaran untuk nominal
        $totalBayarAktual = $cicilanData->total_dibayar_aktual ?? 0;
        $totalSisaAktual = $cicilanData->total_sisa_aktual ?? 0;
        $totalNominalCicilan = $cicilanData->total_nominal_cicilan ?? 0;

        // Perbandingan dengan field pinjaman
        $result['detail']['perbandingan'] = [
            'total_pinjaman_field' => (float)$pinjaman->total_pinjaman,
            'total_nominal_cicilan' => (float)$totalNominalCicilan,
            'total_terbayar_field' => (float)$pinjaman->total_terbayar,
            'total_dibayar_aktual' => (float)$totalBayarAktual,
            'sisa_pinjaman_field' => (float)$pinjaman->sisa_pinjaman,
            'total_sisa_aktual' => (float)$totalSisaAktual,
        ];

        // Verifikasi 1: total_pinjaman vs total nominal cicilan
        $selisih1 = abs((float)$pinjaman->total_pinjaman - (float)$totalNominalCicilan);
        if ($selisih1 > 1) {
            $result['is_akurat'] = false;
            $result['pesan'] = "⚠️ Anomali: Total Pinjaman ≠ Total Nominal Cicilan";
            $result['selisih'] = $selisih1;
        }

        // Verifikasi 2: total_terbayar + sisa_pinjaman vs total_pinjaman
        $harusBayar = $totalBayarAktual + $totalSisaAktual;
        $selisih2 = abs((float)$pinjaman->total_pinjaman - $harusBayar);
        if ($selisih2 > 1) {
            $result['is_akurat'] = false;
            $result['pesan'] = "⚠️ Anomali: Total Bayar + Sisa ≠ Total Pinjaman";
            $result['selisih'] = $selisih2;
        }

        // Verifikasi 3: total_terbayar field vs aktual
        $selisih3 = abs((float)$pinjaman->total_terbayar - (float)$totalBayarAktual);
        if ($selisih3 > 1) {
            $result['is_akurat'] = false;
            $result['pesan'] = "⚠️ Anomali: Total Terbayar tidak sesuai dengan cicilan";
            $result['selisih'] = $selisih3;
        }

        // Verifikasi 4: sisa_pinjaman field vs aktual
        $selisih4 = abs((float)$pinjaman->sisa_pinjaman - (float)$totalSisaAktual);
        if ($selisih4 > 1) {
            $result['is_akurat'] = false;
            $result['pesan'] = "⚠️ Anomali: Sisa Pinjaman tidak sesuai dengan cicilan";
            $result['selisih'] = $selisih4;
        }

        return $result;
    }

    /**
     * ✅ PERBAIKI AKURASI OTOMATIS
     * 
     * Menjalankan rekonsiliasi penuh berdasarkan data cicilan yang sebenarnya
     */
    public static function perbaikiAkurasi(Pinjaman $pinjaman): array
    {
        $pinjaman->load('cicilan');

        $cicilanData = $pinjaman->cicilan()
            ->selectRaw('
                SUM(jumlah_cicilan) as total_nominal_cicilan,
                SUM(jumlah_dibayar) as total_dibayar_aktual,
                SUM(sisa_cicilan) as total_sisa_aktual
            ')
            ->first();

        $totalBayarAktual = $cicilanData->total_dibayar_aktual ?? 0;
        $totalSisaAktual = $cicilanData->total_sisa_aktual ?? 0;
        $totalNominalCicilan = $cicilanData->total_nominal_cicilan ?? 0;

        // Update berdasarkan sumber kebenaran: cicilan
        $pinjaman->total_pinjaman = $totalNominalCicilan;
        $pinjaman->total_terbayar = $totalBayarAktual;
        $pinjaman->sisa_pinjaman = $totalSisaAktual;

        // Jika sisa <= 0, tandai lunas
        if ($pinjaman->sisa_pinjaman <= 0) {
            $pinjaman->sisa_pinjaman = 0;
            $pinjaman->status = 'lunas';
            $pinjaman->tanggal_lunas = now();
        } else {
            if (!in_array($pinjaman->status, ['pengajuan', 'review', 'disetujui'])) {
                $pinjaman->status = 'berjalan';
            }
        }

        $pinjaman->save();

        // Log perbaikan
        \Log::warning('Akurasi pinjaman diperbaiki otomatis', [
            'pinjaman_id' => $pinjaman->id,
            'nomor_pinjaman' => $pinjaman->nomor_pinjaman,
            'total_pinjaman_final' => $pinjaman->total_pinjaman,
            'total_terbayar_final' => $pinjaman->total_terbayar,
            'sisa_pinjaman_final' => $pinjaman->sisa_pinjaman,
        ]);

        return [
            'success' => true,
            'total_pinjaman' => (float)$pinjaman->total_pinjaman,
            'total_terbayar' => (float)$pinjaman->total_terbayar,
            'sisa_pinjaman' => (float)$pinjaman->sisa_pinjaman,
            'status' => $pinjaman->status,
        ];
    }

    /**
     * ✅ GENERATE LAPORAN AKURAT REAL-TIME
     * 
     * Mengambil data langsung dari cicilan, bukan dari field pinjaman yang mungkin ketinggalan update
     */
    public static function generateLaporanAkurat(array $filter = []): array
    {
        $query = Pinjaman::query();

        // Apply filters
        if (!empty($filter['kategori'])) {
            $query->where('kategori_peminjam', $filter['kategori']);
        }
        if (!empty($filter['bulan'])) {
            $query->whereMonth('tanggal_pengajuan', $filter['bulan']);
        }
        if (!empty($filter['tahun'])) {
            $query->whereYear('tanggal_pengajuan', $filter['tahun']);
        }

        $pinjamanList = $query->with('cicilan')->get();

        $laporan = [
            'total_pinjaman_transaksi' => 0,
            'total_nominal_dicairkan' => 0,
            'total_nominal_terbayar' => 0,
            'total_nominal_sisa' => 0,
            'persentase_pembayaran' => 0,
            'detail_per_status' => [
                'pengajuan' => 0,
                'review' => 0,
                'disetujui' => 0,
                'dicairkan' => 0,
                'berjalan' => 0,
                'lunas' => 0,
            ],
            'detail_per_kategori' => [
                'crew' => ['nominal' => 0, 'transaksi' => 0],
                'non_crew' => ['nominal' => 0, 'transaksi' => 0],
            ],
            'data_detail' => [],
            'generated_at' => now(),
        ];

        foreach ($pinjamanList as $pinjaman) {
            // Hitung dari cicilan (sumber kebenaran)
            $cicilanStats = $pinjaman->cicilan()
                ->selectRaw('
                    SUM(jumlah_cicilan) as total_nominal,
                    SUM(jumlah_dibayar) as total_dibayar,
                    SUM(sisa_cicilan) as total_sisa
                ')
                ->first();

            $totalNominal = $cicilanStats->total_nominal ?? 0;
            $totalBayar = $cicilanStats->total_dibayar ?? 0;
            $totalSisa = $cicilanStats->total_sisa ?? 0;

            // Accumulate
            $laporan['total_pinjaman_transaksi']++;
            $laporan['total_nominal_dicairkan'] += $totalNominal;
            $laporan['total_nominal_terbayar'] += $totalBayar;
            $laporan['total_nominal_sisa'] += $totalSisa;

            // Per status
            $laporan['detail_per_status'][$pinjaman->status]++;

            // Per kategori
            $kategori = $pinjaman->kategori_peminjam;
            $laporan['detail_per_kategori'][$kategori]['nominal'] += $totalNominal;
            $laporan['detail_per_kategori'][$kategori]['transaksi']++;

            // Detail per pinjaman
            $laporan['data_detail'][] = [
                'id' => $pinjaman->id,
                'nomor_pinjaman' => $pinjaman->nomor_pinjaman,
                'nama_peminjam' => $pinjaman->nama_peminjam_lengkap,
                'kategori' => $pinjaman->kategori_peminjam,
                'total_nominal' => (float)$totalNominal,
                'total_bayar' => (float)$totalBayar,
                'total_sisa' => (float)$totalSisa,
                'persentase' => $totalNominal > 0 ? round(($totalBayar / $totalNominal) * 100, 2) : 0,
                'status' => $pinjaman->status,
                'created_at' => $pinjaman->created_at,
            ];
        }

        // Hitung persentase pembayaran total
        if ($laporan['total_nominal_dicairkan'] > 0) {
            $laporan['persentase_pembayaran'] = round(
                ($laporan['total_nominal_terbayar'] / $laporan['total_nominal_dicairkan']) * 100,
                2
            );
        }

        return $laporan;
    }
}
