<?php

namespace App\Services;

use App\Models\KeuanganSantriCategory;
use App\Models\KeuanganSantriTransaction;
use App\Models\KeuanganSantriSaldo;
use Illuminate\Support\Facades\DB;

class KeuanganSantriService
{
    /**
     * Auto-detect kategori berdasarkan deskripsi transaksi
     */
    public function detectCategory(string $deskripsi, string $jenis = 'pengeluaran')
    {
        return KeuanganSantriCategory::detectCategory($deskripsi, $jenis);
    }

    /**
     * Buat transaksi baru dengan update saldo otomatis
     */
    public function createTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Get atau create saldo santri
            $saldo = KeuanganSantriSaldo::getOrCreateSaldo($data['santri_id']);

            // Set saldo sebelum transaksi
            $data['saldo_sebelum'] = $saldo->saldo_akhir;

            // Hitung saldo sesudah transaksi
            if ($data['jenis'] === 'pemasukan') {
                $data['saldo_sesudah'] = $data['saldo_sebelum'] + $data['jumlah'];
            } else {
                $data['saldo_sesudah'] = $data['saldo_sebelum'] - $data['jumlah'];
            }

            // Auto-detect kategori jika tidak ada
            if (!isset($data['category_id']) || !$data['category_id']) {
                $category = $this->detectCategory($data['deskripsi'], $data['jenis']);
                $data['category_id'] = $category ? $category->id : null;
            }

            // Hapus kode_transaksi dari data jika ada (akan di-generate otomatis)
            unset($data['kode_transaksi']);

            // Create transaksi
            $transaction = KeuanganSantriTransaction::create($data);

            // Update saldo
            $saldo->updateSaldo($transaction);

            return $transaction->load(['category', 'santri', 'creator']);
        });
    }

    /**
     * Update transaksi dengan recalculate saldo
     */
    public function updateTransaction(KeuanganSantriTransaction $transaction, array $data)
    {
        return DB::transaction(function () use ($transaction, $data) {
            // Restore saldo lama
            $saldo = KeuanganSantriSaldo::where('santri_id', $transaction->santri_id)->first();
            
            if ($transaction->jenis === 'pemasukan') {
                $saldo->total_pemasukan -= $transaction->jumlah;
                $saldo->saldo_akhir -= $transaction->jumlah;
            } else {
                $saldo->total_pengeluaran -= $transaction->jumlah;
                $saldo->saldo_akhir += $transaction->jumlah;
            }
            $saldo->save();

            // Update transaksi
            $data['saldo_sebelum'] = $saldo->saldo_akhir;
            
            if ($data['jenis'] === 'pemasukan') {
                $data['saldo_sesudah'] = $data['saldo_sebelum'] + $data['jumlah'];
            } else {
                $data['saldo_sesudah'] = $data['saldo_sebelum'] - $data['jumlah'];
            }

            // Auto-detect kategori jika deskripsi berubah
            if (isset($data['deskripsi']) && $data['deskripsi'] !== $transaction->deskripsi) {
                if (!isset($data['category_id']) || !$data['category_id']) {
                    $category = $this->detectCategory($data['deskripsi'], $data['jenis']);
                    $data['category_id'] = $category ? $category->id : null;
                }
            }

            // Jangan update kode_transaksi karena sudah unique
            unset($data['kode_transaksi']);

            $transaction->update($data);

            // Update saldo baru
            $saldo->updateSaldo($transaction);

            return $transaction->load(['category', 'santri', 'creator']);
        });
    }

    /**
     * Delete transaksi dengan restore saldo
     */
    public function deleteTransaction(KeuanganSantriTransaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {
            $saldo = KeuanganSantriSaldo::where('santri_id', $transaction->santri_id)->first();
            
            // Restore saldo
            if ($transaction->jenis === 'pemasukan') {
                $saldo->total_pemasukan -= $transaction->jumlah;
                $saldo->saldo_akhir -= $transaction->jumlah;
            } else {
                $saldo->total_pengeluaran -= $transaction->jumlah;
                $saldo->saldo_akhir += $transaction->jumlah;
            }
            
            $saldo->save();
            
            return $transaction->delete();
        });
    }

    /**
     * Get statistik keuangan santri
     */
    public function getStatistik($santriId = null, $startDate = null, $endDate = null)
    {
        $query = KeuanganSantriTransaction::query();

        if ($santriId) {
            $query->where('santri_id', $santriId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        }

        $totalPemasukan = $query->clone()->pemasukan()->sum('jumlah');
        $totalPengeluaran = $query->clone()->pengeluaran()->sum('jumlah');
        $selisih = $totalPemasukan - $totalPengeluaran;
        $totalTransaksi = $query->clone()->count();

        // Statistik per kategori
        $pengeluaranPerKategori = $query->clone()
            ->pengeluaran()
            ->select('category_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $pemasukanPerKategori = $query->clone()
            ->pemasukan()
            ->select('category_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'selisih' => $selisih,
            'total_transaksi' => $totalTransaksi,
            'pengeluaran_per_kategori' => $pengeluaranPerKategori,
            'pemasukan_per_kategori' => $pemasukanPerKategori,
        ];
    }

    /**
     * Get transaksi untuk periode tertentu
     */
    public function getTransactions($filters = [])
    {
        $query = KeuanganSantriTransaction::with(['category', 'santri', 'creator'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc');

        if (isset($filters['santri_id'])) {
            $query->where('santri_id', $filters['santri_id']);
        }

        if (isset($filters['jenis'])) {
            $query->where('jenis', $filters['jenis']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('tanggal_transaksi', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }

        if (isset($filters['periode'])) {
            switch ($filters['periode']) {
                case 'hari':
                    $query->today();
                    break;
                case 'minggu':
                    $query->thisWeek();
                    break;
                case 'bulan':
                    $query->thisMonth();
                    break;
                case 'tahun':
                    $query->thisYear();
                    break;
            }
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
