<?php

namespace App\Services;

use App\Models\Pinjaman;
use App\Models\PinjamanCicilan;
use App\Models\Penyesuaiangaji;
use App\Models\Detailpenyesuaiangaji;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PinjamanPayrollService
{
    /**
     * Generate potongan pinjaman untuk periode payroll tertentu
     * 
     * @param int $bulan Bulan payroll (1-12)
     * @param int $tahun Tahun payroll  
     * @return array Hasil proses
     */
    public function generatePotonganPinjaman($bulan, $tahun)
    {
        try {
            DB::beginTransaction();

            // Cek atau buat penyesuaian gaji untuk periode ini
            $kodePenyesuaian = $this->getOrCreatePenyesuaianGaji($bulan, $tahun);

            // Ambil cicilan yang jatuh tempo di bulan ini untuk karyawan (crew)
            $cicilans = PinjamanCicilan::with(['pinjaman.karyawan'])
                ->whereHas('pinjaman', function($query) {
                    $query->where('kategori_peminjam', 'crew')
                          ->where('status', 'berjalan');
                })
                ->where('status', '!=', 'lunas')
                ->where('is_ditunda', false)
                ->where('auto_potong_gaji', true)
                ->where('sudah_dipotong', false)
                ->whereYear('tanggal_jatuh_tempo', $tahun)
                ->whereMonth('tanggal_jatuh_tempo', $bulan)
                ->get();

            $totalProcessed = 0;
            $totalAmount = 0;
            $errors = [];

            foreach ($cicilans as $cicilan) {
                try {
                    // Pastikan ada NIK karyawan
                    $pinjaman = $cicilan->pinjaman;
                    
                    if (!$pinjaman->karyawan_id) {
                        $errors[] = "Pinjaman {$pinjaman->nomor_pinjaman} tidak memiliki NIK karyawan";
                        continue;
                    }

                    // Cek apakah sudah ada potongan untuk karyawan ini
                    $existingDetail = Detailpenyesuaiangaji::where('kode_penyesuaian_gaji', $kodePenyesuaian)
                        ->where('nik', $pinjaman->karyawan_id)
                        ->first();

                    if ($existingDetail) {
                        // Update pengurang (tambahkan cicilan ini)
                        $existingDetail->pengurang += $cicilan->jumlah_cicilan;
                        $existingDetail->keterangan = $this->updateKeterangan(
                            $existingDetail->keterangan, 
                            $pinjaman->nomor_pinjaman,
                            $cicilan->cicilan_ke,
                            $cicilan->jumlah_cicilan
                        );
                        $existingDetail->save();
                    } else {
                        // Buat potongan baru
                        Detailpenyesuaiangaji::create([
                            'kode_penyesuaian_gaji' => $kodePenyesuaian,
                            'nik' => $pinjaman->karyawan_id,
                            'penambah' => 0,
                            'pengurang' => $cicilan->jumlah_cicilan,
                            'keterangan' => "Cicilan Pinjaman {$pinjaman->nomor_pinjaman} ke-{$cicilan->cicilan_ke}: Rp " . number_format($cicilan->jumlah_cicilan, 0, ',', '.')
                        ]);
                    }

                    // Update status cicilan
                    $cicilan->update([
                        'kode_penyesuaian_gaji' => $kodePenyesuaian,
                        'sudah_dipotong' => true,
                        'tanggal_dipotong' => now()
                    ]);

                    // Log history di pinjaman
                    $pinjaman->logHistory(
                        'potong_gaji',
                        null,
                        null,
                        "Cicilan ke-{$cicilan->cicilan_ke} dipotong via payroll periode {$bulan}/{$tahun}",
                        [
                            'cicilan_ke' => $cicilan->cicilan_ke,
                            'jumlah' => $cicilan->jumlah_cicilan,
                            'kode_penyesuaian' => $kodePenyesuaian,
                        ]
                    );

                    $totalProcessed++;
                    $totalAmount += $cicilan->jumlah_cicilan;

                } catch (\Exception $e) {
                    $errors[] = "Error processing cicilan {$cicilan->id}: " . $e->getMessage();
                    Log::error('Error processing cicilan for payroll', [
                        'cicilan_id' => $cicilan->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            return [
                'success' => true,
                'kode_penyesuaian' => $kodePenyesuaian,
                'total_cicilan' => $totalProcessed,
                'total_amount' => $totalAmount,
                'errors' => $errors,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating potongan pinjaman for payroll', [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get or create penyesuaian gaji untuk periode tertentu
     */
    protected function getOrCreatePenyesuaianGaji($bulan, $tahun)
    {
        $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $kodePenyesuaian = 'PYG' . $bulanPadded . $tahun;

        $penyesuaian = Penyesuaiangaji::firstOrCreate(
            ['kode_penyesuaian_gaji' => $kodePenyesuaian],
            [
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]
        );

        return $kodePenyesuaian;
    }

    /**
     * Update keterangan dengan info pinjaman baru
     */
    protected function updateKeterangan($keteranganLama, $nomorPinjaman, $cicilanKe, $jumlah)
    {
        $newLine = "Cicilan Pinjaman {$nomorPinjaman} ke-{$cicilanKe}: Rp " . number_format($jumlah, 0, ',', '.');
        
        if (empty($keteranganLama)) {
            return $newLine;
        }

        return $keteranganLama . "\n" . $newLine;
    }

    /**
     * Batalkan potongan pinjaman dari payroll
     * 
     * @param int $cicilanId ID cicilan yang akan dibatalkan
     * @return array Hasil proses
     */
    public function batalkanPotonganPayroll($cicilanId)
    {
        try {
            DB::beginTransaction();

            $cicilan = PinjamanCicilan::with('pinjaman')->findOrFail($cicilanId);

            if (!$cicilan->sudah_dipotong || !$cicilan->kode_penyesuaian_gaji) {
                throw new \Exception('Cicilan ini belum dipotong via payroll');
            }

            // Cari detail penyesuaian gaji
            $detail = Detailpenyesuaiangaji::where('kode_penyesuaian_gaji', $cicilan->kode_penyesuaian_gaji)
                ->where('nik', $cicilan->pinjaman->karyawan_id)
                ->first();

            if ($detail) {
                // Kurangi pengurang
                $detail->pengurang -= $cicilan->jumlah_cicilan;
                
                if ($detail->pengurang <= 0 && $detail->penambah <= 0) {
                    // Hapus jika tidak ada penambah/pengurang lagi
                    $detail->delete();
                } else {
                    $detail->save();
                }
            }

            // Reset status cicilan
            $cicilan->update([
                'kode_penyesuaian_gaji' => null,
                'sudah_dipotong' => false,
                'tanggal_dipotong' => null
            ]);

            // Log history
            $cicilan->pinjaman->logHistory(
                'batal_potong_gaji',
                null,
                null,
                "Pembatalan potongan gaji cicilan ke-{$cicilan->cicilan_ke}",
                ['cicilan_ke' => $cicilan->cicilan_ke]
            );

            DB::commit();

            return [
                'success' => true,
                'message' => 'Potongan payroll berhasil dibatalkan'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error batalkan potongan payroll', [
                'cicilan_id' => $cicilanId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get summary potongan pinjaman untuk periode tertentu
     */
    public function getSummaryPotongan($bulan, $tahun)
    {
        $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $kodePenyesuaian = 'PYG' . $bulanPadded . $tahun;

        $cicilans = PinjamanCicilan::with(['pinjaman.karyawan'])
            ->where('kode_penyesuaian_gaji', $kodePenyesuaian)
            ->where('sudah_dipotong', true)
            ->get();

        $summary = [
            'total_karyawan' => $cicilans->unique('pinjaman.karyawan_id')->count(),
            'total_cicilan' => $cicilans->count(),
            'total_amount' => $cicilans->sum('jumlah_cicilan'),
            'details' => []
        ];

        foreach ($cicilans->groupBy('pinjaman.karyawan_id') as $nik => $items) {
            $karyawan = $items->first()->pinjaman->karyawan;
            $summary['details'][] = [
                'nik' => $nik,
                'nama' => $karyawan ? $karyawan->nama_karyawan : 'N/A',
                'jumlah_cicilan' => $items->count(),
                'total_potongan' => $items->sum('jumlah_cicilan'),
                'items' => $items
            ];
        }

        return $summary;
    }
}
