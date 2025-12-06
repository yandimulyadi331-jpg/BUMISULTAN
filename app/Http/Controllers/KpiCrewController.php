<?php

namespace App\Http\Controllers;

use App\Models\KpiCrew;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\AktivitasKaryawan;
use App\Models\PerawatanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KpiCrewController extends Controller
{
    /**
     * Display KPI Crew Dashboard
     */
    public function index(Request $request)
    {
        // Default ke bulan dan tahun saat ini
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        // Hitung atau update KPI untuk periode yang dipilih
        $this->calculateKpi($bulan, $tahun);

        // Ambil data KPI dengan relasi karyawan, filter yang karyawannya masih ada
        $kpiData = KpiCrew::with(['karyawan' => function($query) {
                $query->select('nik', 'nama_karyawan', 'kode_dept', 'kode_cabang');
            }])
            ->whereHas('karyawan') // Hanya ambil KPI yang karyawannya masih ada
            ->periode($bulan, $tahun)
            ->orderedByRanking()
            ->get();

        // Statistik ringkasan
        $totalKaryawan = $kpiData->count();
        $avgTotalPoint = $kpiData->avg('total_point');
        $topPerformer = $kpiData->first();
        
        // Data untuk filter
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tahunList = range(date('Y'), date('Y') - 2); // 3 tahun terakhir

        return view('kpicrew.index', compact(
            'kpiData',
            'bulan',
            'tahun',
            'bulanList',
            'tahunList',
            'totalKaryawan',
            'avgTotalPoint',
            'topPerformer'
        ));
    }

    /**
     * Hitung KPI untuk semua karyawan pada periode tertentu
     */
    protected function calculateKpi($bulan, $tahun)
    {
        // Ambil semua karyawan yang ada di database
        $karyawans = Karyawan::select('nik', 'nama_karyawan')
            ->whereNotNull('nik')
            ->get();

        // Tanggal awal dan akhir bulan
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        foreach ($karyawans as $karyawan) {
            // 1. Hitung Kehadiran dari tabel presensi
            $kehadiranCount = Presensi::where('nik', $karyawan->nik)
                ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->whereNotNull('jam_in') // Hanya yang ada jam masuk
                ->count();

            // 2. Hitung Aktivitas dari tabel aktivitas_karyawan
            $aktivitasCount = AktivitasKaryawan::where('nik', $karyawan->nik)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // 3. Hitung Perawatan dari tabel perawatan_log
            // Ambil user_id dari karyawan
            $user = User::whereHas('userkaryawan', function($query) use ($karyawan) {
                $query->where('nik', $karyawan->nik);
            })->first();

            $perawatanCount = 0;
            if ($user) {
                $perawatanCount = PerawatanLog::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->whereBetween('tanggal_eksekusi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->count();
            }

            // Hitung Point berdasarkan bobot
            // Kehadiran: 1 point per kehadiran (maks 25 hari kerja)
            $kehadiranPoint = $kehadiranCount * 4; // 4 point per kehadiran (total max 100 point untuk 25 hari)
            
            // Aktivitas: 3 point per aktivitas
            $aktivitasPoint = $aktivitasCount * 3;
            
            // Perawatan: 2 point per perawatan diselesaikan
            $perawatanPoint = $perawatanCount * 2;

            // Total Point
            $totalPoint = $kehadiranPoint + $aktivitasPoint + $perawatanPoint;

            // Update atau create KPI record
            KpiCrew::updateOrCreate(
                [
                    'nik' => $karyawan->nik,
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ],
                [
                    'kehadiran_count' => $kehadiranCount,
                    'aktivitas_count' => $aktivitasCount,
                    'perawatan_count' => $perawatanCount,
                    'kehadiran_point' => $kehadiranPoint,
                    'aktivitas_point' => $aktivitasPoint,
                    'perawatan_point' => $perawatanPoint,
                    'total_point' => $totalPoint
                ]
            );
        }

        // Update ranking berdasarkan total point
        $this->updateRanking($bulan, $tahun);
    }

    /**
     * Update ranking karyawan berdasarkan total point
     */
    protected function updateRanking($bulan, $tahun)
    {
        // Ambil semua KPI untuk periode ini, diurutkan berdasarkan total point
        $kpiRecords = KpiCrew::periode($bulan, $tahun)
            ->orderedByPoint()
            ->get();

        $ranking = 1;
        foreach ($kpiRecords as $kpi) {
            $kpi->update(['ranking' => $ranking]);
            $ranking++;
        }
    }

    /**
     * Show detail KPI untuk karyawan tertentu
     */
    public function show(Request $request, $nik)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        $kpi = KpiCrew::with('karyawan')
            ->where('nik', $nik)
            ->periode($bulan, $tahun)
            ->firstOrFail();

        // Ambil detail data untuk periode ini
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Detail kehadiran
        $kehadiranDetail = Presensi::where('nik', $nik)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->whereNotNull('jam_in')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Detail aktivitas
        $aktivitasDetail = AktivitasKaryawan::where('nik', $nik)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Detail perawatan
        $user = User::whereHas('userkaryawan', function($query) use ($nik) {
            $query->where('nik', $nik);
        })->first();

        $perawatanDetail = collect();
        if ($user) {
            $perawatanDetail = PerawatanLog::with('masterPerawatan')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('tanggal_eksekusi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->orderBy('tanggal_eksekusi', 'desc')
                ->get();
        }

        return view('kpicrew.show', compact(
            'kpi',
            'kehadiranDetail',
            'aktivitasDetail',
            'perawatanDetail',
            'bulan',
            'tahun'
        ));
    }

    /**
     * Force recalculate KPI
     */
    public function recalculate(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        $this->calculateKpi($bulan, $tahun);

        return redirect()->route('kpicrew.index', ['bulan' => $bulan, 'tahun' => $tahun])
            ->with('success', 'KPI berhasil dihitung ulang untuk periode ' . $bulan . '/' . $tahun);
    }
}
