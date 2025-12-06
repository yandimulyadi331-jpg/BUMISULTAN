<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;
use App\Models\TransaksiKeuangan;
use App\Models\LaporanKeuangan;
use Carbon\Carbon;
use PDF;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class LaporanKeuanganController extends Controller
{
    /**
     * Tampilkan halaman Laporan Keuangan
     */
    public function index()
    {
        return view('laporan-keuangan.index');
    }

    /**
     * Download PDF Laporan Keuangan Profesional
     * Style: Annual Report seperti perusahaan besar (Astra Agro, Bank, dll)
     */
    public function downloadAnnualReport(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'periode_type' => 'required|in:bulanan,triwulan,semester,tahunan',
                'tahun' => 'required|integer|min:2020|max:2030',
                'bulan' => 'required_if:periode_type,bulanan|nullable|integer|min:1|max:12',
                'triwulan' => 'required_if:periode_type,triwulan|nullable|integer|min:1|max:4',
                'semester' => 'required_if:periode_type,semester|nullable|integer|min:1|max:2',
            ]);

            // Tentukan periode berdasarkan tipe
            $periodeData = $this->hitungPeriode($request);
            
            // Ambil data keuangan
            $dataKeuangan = $this->getDataKeuangan($periodeData);
            
            // Generate PDF Annual Report
            $pdf = PDF::loadView('laporan-keuangan.pdf-annual-report', [
                'periode' => $periodeData,
                'data' => $dataKeuangan,
                'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
            ]);
            
            $pdf->setPaper('a4', 'portrait');
            
            // Nama file
            $filename = $this->generateFilename($periodeData);
            
            // Simpan PDF ke storage untuk karyawan
            $pdfContent = $pdf->output();
            Storage::put('public/' . $filename, $pdfContent);
            
            // Simpan ke database untuk publish ke karyawan
            $this->saveLaporanToDatabase($periodeData, $filename, $dataKeuangan);
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Simpan laporan ke database untuk fitur publish
     */
    private function saveLaporanToDatabase($periodeData, $filename, $dataKeuangan)
    {
        try {
            // Map periode type ke jenis_laporan enum
            $jenisLaporan = 'LAPORAN_BUDGET'; // Default, karena ini annual report
            
            // Generate nomor laporan
            $lastNumber = DB::table('laporan_keuangan')
                ->where('nomor_laporan', 'like', 'LAP-' . date('Ym') . '%')
                ->count();
            $nomor = 'LAP-' . date('Ym') . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            
            // Cek apakah sudah ada laporan dengan periode yang sama
            $existing = DB::table('laporan_keuangan')
                ->where('periode', $periodeData['type'])
                ->where('nama_laporan', $periodeData['nama_periode'])
                ->first();

            if ($existing) {
                // Update existing
                DB::table('laporan_keuangan')
                    ->where('id', $existing->id)
                    ->update([
                        'tanggal_mulai' => $periodeData['tanggal_awal']->format('Y-m-d'),
                        'tanggal_selesai' => $periodeData['tanggal_akhir']->format('Y-m-d'),
                        'file_pdf' => $filename,
                        'data_laporan' => json_encode($dataKeuangan),
                        'user_id' => auth()->id(),
                        'generated_at' => now(),
                        'updated_at' => now(),
                    ]);
            } else {
                // Create new
                DB::table('laporan_keuangan')->insert([
                    'nomor_laporan' => $nomor,
                    'jenis_laporan' => $jenisLaporan,
                    'nama_laporan' => $periodeData['nama_periode'],
                    'tanggal_mulai' => $periodeData['tanggal_awal']->format('Y-m-d'),
                    'tanggal_selesai' => $periodeData['tanggal_akhir']->format('Y-m-d'),
                    'periode' => $periodeData['type'], // bulanan, tahunan, dll
                    'parameter' => json_encode([
                        'type' => $periodeData['type'],
                        'tahun' => $periodeData['tahun'],
                    ]),
                    'data_laporan' => json_encode($dataKeuangan),
                    'file_pdf' => $filename,
                    'status' => 'DRAFT',
                    'is_published' => false,
                    'user_id' => auth()->id(),
                    'generated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan break download
            \Log::error('Failed to save laporan to database: ' . $e->getMessage());
        }
    }

    /**
     * Hitung periode berdasarkan input
     */
    private function hitungPeriode($request)
    {
        $tahun = $request->tahun;
        $type = $request->periode_type;

        switch ($type) {
            case 'bulanan':
                $bulan = $request->bulan;
                $tanggalAwal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $tanggalAkhir = Carbon::create($tahun, $bulan, 1)->endOfMonth();
                $namaPeriode = Carbon::create($tahun, $bulan, 1)->format('F Y');
                break;

            case 'triwulan':
                $triwulan = $request->triwulan;
                $bulanAwal = (($triwulan - 1) * 3) + 1;
                $bulanAkhir = $bulanAwal + 2;
                $tanggalAwal = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
                $tanggalAkhir = Carbon::create($tahun, $bulanAkhir, 1)->endOfMonth();
                $namaPeriode = "Triwulan {$triwulan} Tahun {$tahun}";
                break;

            case 'semester':
                $semester = $request->semester;
                $bulanAwal = $semester == 1 ? 1 : 7;
                $bulanAkhir = $semester == 1 ? 6 : 12;
                $tanggalAwal = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
                $tanggalAkhir = Carbon::create($tahun, $bulanAkhir, 1)->endOfMonth();
                $namaPeriode = "Semester {$semester} Tahun {$tahun}";
                break;

            case 'tahunan':
                $tanggalAwal = Carbon::create($tahun, 1, 1)->startOfYear();
                $tanggalAkhir = Carbon::create($tahun, 12, 31)->endOfYear();
                $namaPeriode = "Tahun {$tahun}";
                break;

            default:
                throw new \Exception('Tipe periode tidak valid');
        }

        return [
            'type' => $type,
            'tahun' => $tahun,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir,
            'nama_periode' => $namaPeriode,
        ];
    }

    /**
     * Ambil dan olah data keuangan untuk periode tertentu
     */
    private function getDataKeuangan($periode)
    {
        $tanggalAwal = $periode['tanggal_awal'];
        $tanggalAkhir = $periode['tanggal_akhir'];

        // 1. INCOME STATEMENT (Laporan Laba Rugi)
        $pendapatan = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->where('tipe_transaksi', 'Dana Masuk')
            ->sum('nominal');

        $pengeluaran = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->where('tipe_transaksi', 'Dana Keluar')
            ->sum('nominal');

        $labaRugi = $pendapatan - $pengeluaran;

        // Detail Pendapatan per Kategori
        $pendapatanPerKategori = RealisasiDanaOperasional::select(
                'kategori', 
                DB::raw('SUM(nominal) as total'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->where('tipe_transaksi', 'Dana Masuk')
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->get();

        // Detail Pengeluaran per Kategori
        $pengeluaranPerKategori = RealisasiDanaOperasional::select(
                'kategori', 
                DB::raw('SUM(nominal) as total'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->where('tipe_transaksi', 'Dana Keluar')
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->get();

        // 2. BALANCE SHEET (Neraca)
        // Saldo Awal Periode
        $saldoAwalPeriode = SaldoHarianOperasional::whereDate('tanggal', $tanggalAwal)
            ->value('saldo_awal') ?? 0;

        // Saldo Akhir Periode
        $saldoAkhirPeriode = SaldoHarianOperasional::whereDate('tanggal', $tanggalAkhir)
            ->value('saldo_akhir') ?? ($saldoAwalPeriode + $labaRugi);

        // 3. CASH FLOW (Arus Kas)
        $arusKasMasuk = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->where('tipe_transaksi', 'Dana Masuk')
            ->count();

        $arusKasKeluar = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->where('tipe_transaksi', 'Dana Keluar')
            ->count();

        // 4. FINANCIAL HIGHLIGHTS (Ikhtisar Keuangan)
        $totalTransaksi = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->count();

        $rataRataTransaksiHarian = $totalTransaksi / max(1, $tanggalAwal->diffInDays($tanggalAkhir) + 1);

        // 5. PERBANDINGAN PERIODE SEBELUMNYA
        $periodeDiff = $tanggalAwal->diffInDays($tanggalAkhir) + 1;
        $tanggalAwalSebelumnya = $tanggalAwal->copy()->subDays($periodeDiff);
        $tanggalAkhirSebelumnya = $tanggalAkhir->copy()->subDays($periodeDiff);

        $pendapatanSebelumnya = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwalSebelumnya, $tanggalAkhirSebelumnya])
            ->where('tipe_transaksi', 'Dana Masuk')
            ->sum('nominal');

        $pengeluaranSebelumnya = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwalSebelumnya, $tanggalAkhirSebelumnya])
            ->where('tipe_transaksi', 'Dana Keluar')
            ->sum('nominal');

        $labaRugiSebelumnya = $pendapatanSebelumnya - $pengeluaranSebelumnya;

        // Hitung persentase perubahan
        $perubahanPendapatan = $pendapatanSebelumnya > 0 ? 
            (($pendapatan - $pendapatanSebelumnya) / $pendapatanSebelumnya) * 100 : 0;

        $perubahanPengeluaran = $pengeluaranSebelumnya > 0 ? 
            (($pengeluaran - $pengeluaranSebelumnya) / $pengeluaranSebelumnya) * 100 : 0;

        $perubahanLabaRugi = $labaRugiSebelumnya != 0 ? 
            (($labaRugi - $labaRugiSebelumnya) / abs($labaRugiSebelumnya)) * 100 : 0;

        // 6. TRANSAKSI TERBESAR
        $transaksiTerbesar = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('nominal', 'desc')
            ->limit(10)
            ->get();

        // 7. DATA BULANAN (untuk grafik)
        $dataBulanan = [];
        if ($periode['type'] == 'tahunan') {
            for ($i = 1; $i <= 12; $i++) {
                $bulanStart = Carbon::create($periode['tahun'], $i, 1)->startOfMonth();
                $bulanEnd = Carbon::create($periode['tahun'], $i, 1)->endOfMonth();

                $pendapatanBulan = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$bulanStart, $bulanEnd])
                    ->where('tipe_transaksi', 'Dana Masuk')
                    ->sum('nominal');

                $pengeluaranBulan = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$bulanStart, $bulanEnd])
                    ->where('tipe_transaksi', 'Dana Keluar')
                    ->sum('nominal');

                $dataBulanan[] = [
                    'bulan' => $bulanStart->format('M'),
                    'pendapatan' => $pendapatanBulan,
                    'pengeluaran' => $pengeluaranBulan,
                    'laba_rugi' => $pendapatanBulan - $pengeluaranBulan,
                ];
            }
        }

        return [
            // Income Statement
            'pendapatan' => $pendapatan,
            'pengeluaran' => $pengeluaran,
            'laba_rugi' => $labaRugi,
            'pendapatan_per_kategori' => $pendapatanPerKategori,
            'pengeluaran_per_kategori' => $pengeluaranPerKategori,

            // Balance Sheet
            'saldo_awal' => $saldoAwalPeriode,
            'saldo_akhir' => $saldoAkhirPeriode,

            // Cash Flow
            'arus_kas_masuk_count' => $arusKasMasuk,
            'arus_kas_keluar_count' => $arusKasKeluar,
            'arus_kas_masuk' => $pendapatan,
            'arus_kas_keluar' => $pengeluaran,
            'arus_kas_bersih' => $pendapatan - $pengeluaran,

            // Financial Highlights
            'total_transaksi' => $totalTransaksi,
            'rata_rata_transaksi_harian' => $rataRataTransaksiHarian,

            // Perbandingan Periode
            'pendapatan_sebelumnya' => $pendapatanSebelumnya,
            'pengeluaran_sebelumnya' => $pengeluaranSebelumnya,
            'laba_rugi_sebelumnya' => $labaRugiSebelumnya,
            'perubahan_pendapatan' => $perubahanPendapatan,
            'perubahan_pengeluaran' => $perubahanPengeluaran,
            'perubahan_laba_rugi' => $perubahanLabaRugi,

            // Transaksi Terbesar
            'transaksi_terbesar' => $transaksiTerbesar,

            // Data Bulanan (untuk grafik)
            'data_bulanan' => $dataBulanan,
        ];
    }

    /**
     * Generate nama file (support PDF & Excel)
     */
    private function generateFilename($periode, $extension = 'pdf')
    {
        $type = ucfirst($periode['type']);
        $nama = str_replace(' ', '_', $periode['nama_periode']);
        $timestamp = Carbon::now()->format('YmdHis');
        
        return "Laporan_Keuangan_{$type}_{$nama}_{$timestamp}.{$extension}";
    }

    /**
     * Preview Laporan (untuk testing)
     */
    public function preview(Request $request)
    {
        $periodeData = $this->hitungPeriode($request);
        $dataKeuangan = $this->getDataKeuangan($periodeData);
        
        return view('laporan-keuangan.pdf-annual-report', [
            'periode' => $periodeData,
            'data' => $dataKeuangan,
            'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
        ]);
    }

    /**
     * Download Excel Format (Data Raw)
     */
    public function downloadExcel(Request $request)
    {
        try {
            $request->validate([
                'periode_type' => 'required|in:bulanan,triwulan,semester,tahunan',
                'tahun' => 'required|integer|min:2020|max:2030',
                'bulan' => 'required_if:periode_type,bulanan|nullable|integer|min:1|max:12',
                'triwulan' => 'required_if:periode_type,triwulan|nullable|integer|min:1|max:4',
                'semester' => 'required_if:periode_type,semester|nullable|integer|min:1|max:2',
            ]);

            $periodeData = $this->hitungPeriode($request);
            $filename = $this->generateFilename($periodeData, 'xlsx');
            
            // Update database dengan file excel
            $laporan = DB::table('laporan_keuangan')
                ->where('periode', $periodeData['type'])
                ->where('nama_laporan', $periodeData['nama_periode'])
                ->first();
            
            if ($laporan) {
                DB::table('laporan_keuangan')
                    ->where('id', $laporan->id)
                    ->update([
                        'file_excel' => $filename,
                        'updated_at' => now(),
                    ]);
            }
            
            return Excel::download(
                new \App\Exports\LaporanKeuanganExport($periodeData),
                $filename,
                \Maatwebsite\Excel\Excel::XLSX
            );

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal generate Excel: ' . $e->getMessage());
        }
    }

    /**
     * Toggle publish status laporan keuangan untuk karyawan
     */
    public function togglePublish($id)
    {
        try {
            $laporan = DB::table('laporan_keuangan')->where('id', $id)->first();
            
            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan.'
                ], 404);
            }
            
            // Check permission
            if (!auth()->user()->can('laporan-keuangan.publish')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk publish laporan.'
                ], 403);
            }

            // Toggle status
            $newStatus = !$laporan->is_published;
            
            DB::table('laporan_keuangan')
                ->where('id', $id)
                ->update([
                    'is_published' => $newStatus,
                    'published_at' => $newStatus ? now() : null,
                    'published_by' => $newStatus ? auth()->id() : null,
                    'status' => $newStatus ? 'PUBLISHED' : 'DRAFT',
                    'updated_at' => now(),
                ]);

            $message = $newStatus 
                ? 'Laporan berhasil dipublish. Sekarang karyawan dapat melihatnya.' 
                : 'Laporan berhasil di-unpublish. Karyawan tidak dapat melihatnya lagi.';

            $publishedBy = $newStatus ? auth()->user()->name : null;
            $publishedAt = $newStatus ? now()->format('d M Y H:i') : null;

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_published' => $newStatus,
                'published_at' => $publishedAt,
                'published_by' => $publishedBy,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal toggle publish: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish laporan keuangan (make it visible to karyawan)
     */
    public function publishLaporan($id)
    {
        try {
            $laporan = LaporanKeuangan::findOrFail($id);
            
            if (!auth()->user()->can('laporan-keuangan.publish')) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk publish laporan.');
            }

            if ($laporan->is_published) {
                return redirect()->back()
                    ->with('info', 'Laporan sudah dipublish sebelumnya.');
            }

            $laporan->is_published = true;
            $laporan->published_at = now();
            $laporan->published_by = auth()->id();
            $laporan->save();

            return redirect()->back()
                ->with('success', 'Laporan berhasil dipublish. Karyawan sekarang dapat melihatnya.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal publish laporan: ' . $e->getMessage());
        }
    }

    /**
     * Unpublish laporan keuangan (hide from karyawan)
     */
    public function unpublishLaporan($id)
    {
        try {
            $laporan = LaporanKeuangan::findOrFail($id);
            
            if (!auth()->user()->can('laporan-keuangan.publish')) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk unpublish laporan.');
            }

            if (!$laporan->is_published) {
                return redirect()->back()
                    ->with('info', 'Laporan belum dipublish.');
            }

            $laporan->is_published = false;
            $laporan->published_at = null;
            $laporan->published_by = null;
            $laporan->save();

            return redirect()->back()
                ->with('success', 'Laporan berhasil di-unpublish. Karyawan tidak dapat melihatnya lagi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal unpublish laporan: ' . $e->getMessage());
        }
    }
}
