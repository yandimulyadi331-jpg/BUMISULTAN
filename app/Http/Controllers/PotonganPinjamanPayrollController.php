<?php

namespace App\Http\Controllers;

use App\Models\PotonganPinjamanPayroll;
use App\Models\PinjamanCicilan;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class PotonganPinjamanPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahun = !empty($request->tahun) ? $request->tahun : date('Y');
        $bulan = !empty($request->bulan) ? $request->bulan : date('n');

        $data['start_year'] = config('global.start_year');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        // Get summary by periode
        $data['summary'] = PotonganPinjamanPayroll::periode($bulan, $tahun)
            ->selectRaw('
                status,
                COUNT(*) as total_cicilan,
                SUM(jumlah_potongan) as total_potongan,
                COUNT(DISTINCT nik) as total_karyawan
            ')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Get detail potongan by karyawan
        $data['potongan'] = PotonganPinjamanPayroll::with(['karyawan', 'pinjaman', 'cicilan'])
            ->periode($bulan, $tahun)
            ->orderBy('status')
            ->orderBy('nik')
            ->orderBy('cicilan_ke')
            ->get()
            ->groupBy('nik');

        return view('payroll.potongan_pinjaman.index', $data);
    }

    /**
     * Generate potongan pinjaman untuk periode tertentu
     */
    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
        ]);

        try {
            DB::beginTransaction();

            $bulan = $request->bulan;
            $tahun = $request->tahun;

            // Ambil cicilan yang jatuh tempo di periode ini untuk karyawan
            $cicilans = PinjamanCicilan::with(['pinjaman.karyawan'])
                ->whereHas('pinjaman', function($query) {
                    $query->where('kategori_peminjam', 'crew')
                          ->where('status', 'berjalan');
                })
                ->where('status', '!=', 'lunas')
                ->where('is_ditunda', false)
                ->whereYear('tanggal_jatuh_tempo', $tahun)
                ->whereMonth('tanggal_jatuh_tempo', $bulan)
                ->get();

            if ($cicilans->count() == 0) {
                return Redirect::back()->with('warning', 'Tidak ada cicilan yang jatuh tempo untuk periode ini.');
            }

            $totalGenerated = 0;
            $totalAmount = 0;

            foreach ($cicilans as $cicilan) {
                $pinjaman = $cicilan->pinjaman;

                if (!$pinjaman->karyawan_id) {
                    continue;
                }

                // Generate kode potongan yang unik untuk setiap record
                $kodePotongan = PotonganPinjamanPayroll::generateKode($bulan, $tahun);

                // Create potongan
                PotonganPinjamanPayroll::create([
                    'kode_potongan' => $kodePotongan,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nik' => $pinjaman->karyawan_id,
                    'pinjaman_id' => $pinjaman->id,
                    'cicilan_id' => $cicilan->id,
                    'cicilan_ke' => $cicilan->cicilan_ke,
                    'jumlah_potongan' => $cicilan->sisa_cicilan,
                    'tanggal_jatuh_tempo' => $cicilan->tanggal_jatuh_tempo,
                    'status' => 'pending',
                    'keterangan' => "Cicilan ke-{$cicilan->cicilan_ke} pinjaman {$pinjaman->nomor_pinjaman}",
                ]);

                // Update cicilan - mark as scheduled for payroll
                $cicilan->update([
                    'kode_penyesuaian_gaji' => $kodePotongan,
                ]);

                // Log history
                $pinjaman->logHistory(
                    'generate_potongan',
                    null,
                    null,
                    "Cicilan ke-{$cicilan->cicilan_ke} dijadwalkan untuk potongan gaji periode {$bulan}/{$tahun}",
                    [
                        'kode_potongan' => $kodePotongan,
                        'cicilan_ke' => $cicilan->cicilan_ke,
                        'jumlah' => $cicilan->sisa_cicilan,
                    ]
                );

                $totalGenerated++;
                $totalAmount += $cicilan->sisa_cicilan;
            }

            DB::commit();

            $message = "Berhasil generate {$totalGenerated} potongan pinjaman dengan total Rp " . number_format($totalAmount, 0, ',', '.');
            return Redirect::back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Gagal generate potongan: ' . $e->getMessage());
        }
    }

    /**
     * Proses potongan (mark as dipotong)
     */
    public function proses(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $updated = PotonganPinjamanPayroll::periode($request->bulan, $request->tahun)
                ->where('status', 'pending')
                ->update([
                    'status' => 'dipotong',
                    'tanggal_dipotong' => now(),
                    'diproses_oleh' => auth()->id(),
                ]);

            // Update cicilan juga
            $potongan = PotonganPinjamanPayroll::periode($request->bulan, $request->tahun)
                ->where('status', 'dipotong')
                ->get();

            foreach ($potongan as $p) {
                $p->cicilan->update([
                    'sudah_dipotong' => true,
                    'tanggal_dipotong' => now(),
                ]);
            }

            DB::commit();

            return Redirect::back()->with('success', "Berhasil memproses {$updated} potongan pinjaman. Status diubah menjadi 'Dipotong'.");

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua potongan untuk periode tertentu
     */
    public function deleteByPeriode(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            // Get potongan
            $potongan = PotonganPinjamanPayroll::periode($request->bulan, $request->tahun)->get();

            // Reset cicilan
            foreach ($potongan as $p) {
                $p->cicilan->update([
                    'kode_penyesuaian_gaji' => null,
                    'sudah_dipotong' => false,
                    'tanggal_dipotong' => null,
                ]);
            }

            // Delete potongan
            $deleted = PotonganPinjamanPayroll::periode($request->bulan, $request->tahun)->delete();

            DB::commit();

            return Redirect::back()->with('success', "Berhasil menghapus {$deleted} data potongan pinjaman.");

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Get potongan by NIK untuk integrasi slip gaji
     */
    public function getPotonganByNik($nik, $bulan, $tahun)
    {
        return PotonganPinjamanPayroll::with(['pinjaman', 'cicilan'])
            ->where('nik', $nik)
            ->periode($bulan, $tahun)
            ->where('status', 'dipotong')
            ->get();
    }
}
