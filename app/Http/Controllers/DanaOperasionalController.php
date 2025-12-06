<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDanaOperasional;
use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;
use App\Exports\TransaksiOperasionalExport;
use App\Imports\TransaksiOperasionalImport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DanaOperasionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super admin');
    }

    // Dashboard
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'bulan');
        
        // Tentukan range tanggal berdasarkan tipe filter
        switch ($filterType) {
            case 'tahun':
                $tahun = $request->get('tahun', date('Y'));
                $tanggalAwal = Carbon::create($tahun, 1, 1)->startOfYear();
                $tanggalAkhir = Carbon::create($tahun, 12, 31)->endOfYear();
                $periodeLabel = "Tahun $tahun";
                break;
                
            case 'minggu':
                if ($request->has('minggu')) {
                    // Format minggu: 2025-W46
                    list($tahun, $minggu) = explode('-W', $request->minggu);
                    $tanggalAwal = Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
                    $tanggalAkhir = Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();
                } else {
                    $tanggalAwal = Carbon::now()->startOfWeek();
                    $tanggalAkhir = Carbon::now()->endOfWeek();
                }
                $periodeLabel = "Minggu " . $tanggalAwal->format('d M') . " - " . $tanggalAkhir->format('d M Y');
                break;
                
            case 'range':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $tanggalAwal = Carbon::parse($request->start_date)->startOfDay();
                    $tanggalAkhir = Carbon::parse($request->end_date)->endOfDay();
                } else {
                    $tanggalAwal = Carbon::now()->startOfMonth();
                    $tanggalAkhir = Carbon::now()->endOfMonth();
                }
                $periodeLabel = $tanggalAwal->format('d M Y') . " - " . $tanggalAkhir->format('d M Y');
                break;
                
            default: // 'bulan'
                $bulan = $request->get('bulan', date('Y-m'));
                $tanggalAwal = Carbon::parse($bulan . '-01')->startOfMonth();
                $tanggalAkhir = Carbon::parse($bulan . '-01')->endOfMonth();
                $periodeLabel = $tanggalAwal->locale('id')->isoFormat('MMMM YYYY');
                break;
        }
        
        // Get saldo harian dalam range
        $riwayatSaldo = SaldoHarianOperasional::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Get show voided option (default: true = tampilkan semua termasuk voided)
        $showVoided = $request->get('show_voided', true);
        
        // Get transaksi per tanggal
        $realisasiQuery = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir]);
        
        // Filter by status (jika show_voided = false, hanya tampilkan active)
        if (!$showVoided) {
            $realisasiQuery->where('status', 'active');
        }
        
        $realisasiPerTanggal = $realisasiQuery
            ->orderBy('tanggal_realisasi', 'asc')
            ->orderBy('urutan_baris', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy(function($item) {
                return $item->tanggal_realisasi->format('Y-m-d');
            });

        return view('dana-operasional.index', compact(
            'riwayatSaldo',
            'realisasiPerTanggal',
            'tanggalAwal',
            'tanggalAkhir',
            'filterType',
            'periodeLabel',
            'showVoided'
        ));
    }
    
    // Tambah Transaksi Manual
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_realisasi' => 'required|date',
            'keterangan' => 'required|string',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'nominal' => 'required|numeric|min:0',
            'foto_bukti' => 'nullable|image|max:2048',
        ]);
        
        try {
            $data = [
                'tanggal_realisasi' => $request->tanggal_realisasi,
                'keterangan' => $request->keterangan,
                'uraian' => $request->keterangan, // Sama dengan keterangan
                'tipe_transaksi' => $request->tipe_transaksi,
                'nominal' => $request->nominal,
                'created_by' => auth()->id(),
            ];
            
            // Upload foto jika ada
            if ($request->hasFile('foto_bukti')) {
                $path = $request->file('foto_bukti')->store('bukti-transaksi', 'public');
                $data['foto_bukti'] = $path;
            }
            
            $transaksi = RealisasiDanaOperasional::create($data);
            
            // Auto redirect ke filter yang sesuai berdasarkan tanggal transaksi
            $tanggalTransaksi = Carbon::parse($data['tanggal_realisasi']);
            $bulanTransaksi = $tanggalTransaksi->format('Y-m');
            
            return redirect()->route('dana-operasional.index', [
                'filter_type' => 'bulan',
                'bulan' => $bulanTransaksi
            ])->with('success', '✅ Transaksi berhasil ditambahkan! Nomor: ' . $transaksi->nomor_transaksi);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan transaksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Form Pengajuan
    public function createPengajuan()
    {
        $saldoKemarin = SaldoHarianOperasional::getSaldoKemarin();
        return view('dana-operasional.create-pengajuan', compact('saldoKemarin'));
    }

    // Simpan Pengajuan
    public function storePengajuan(Request $request)
    {
        $request->validate([
            'tanggal_pengajuan' => 'required|date',
            'rincian.*.uraian' => 'required|string',
            'rincian.*.nominal' => 'required|numeric|min:0',
        ]);

        $saldoKemarin = SaldoHarianOperasional::getSaldoKemarin();
        $rincian = $request->rincian;
        $totalPengajuan = collect($rincian)->sum('nominal');

        $pengajuan = PengajuanDanaOperasional::create([
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'user_id' => auth()->id(),
            'saldo_sebelumnya' => $saldoKemarin,
            'rincian_kebutuhan' => $rincian,
            'total_pengajuan' => $totalPengajuan,
            'status' => 'draft',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('dana-operasional.show-pengajuan', $pengajuan->id)
            ->with('success', 'Pengajuan berhasil dibuat!');
    }

    // Detail Pengajuan
    public function showPengajuan($id)
    {
        $pengajuan = PengajuanDanaOperasional::with(['user', 'pencair', 'realisasi.creator'])
            ->findOrFail($id);
        
        return view('dana-operasional.show-pengajuan', compact('pengajuan'));
    }

    // Ajukan
    public function ajukanPengajuan($id)
    {
        $pengajuan = PengajuanDanaOperasional::findOrFail($id);
        $pengajuan->update(['status' => 'diajukan']);
        
        return redirect()->back()->with('success', 'Pengajuan berhasil diajukan!');
    }

    // Form Pencairan
    public function formPencairan($id)
    {
        $pengajuan = PengajuanDanaOperasional::with('user')->findOrFail($id);
        return view('dana-operasional.form-pencairan', compact('pengajuan'));
    }

    // Proses Pencairan
    public function prosesPencairan(Request $request, $id)
    {
        $request->validate([
            'nominal_cair' => 'required|numeric|min:0',
        ]);

        $pengajuan = PengajuanDanaOperasional::findOrFail($id);
        $pengajuan->cairkan($request->nominal_cair, auth()->id(), $request->catatan_pencairan);

        return redirect()->route('dana-operasional.show-pengajuan', $id)
            ->with('success', 'Dana berhasil dicairkan!');
    }

    // Form Realisasi
    public function createRealisasi($pengajuanId)
    {
        $pengajuan = PengajuanDanaOperasional::with('realisasi')->findOrFail($pengajuanId);
        
        if ($pengajuan->status != 'dicairkan' && $pengajuan->status != 'selesai') {
            return redirect()->back()->with('error', 'Pengajuan belum dicairkan!');
        }
        
        return view('dana-operasional.create-realisasi', compact('pengajuan'));
    }

    // Simpan Realisasi
    public function storeRealisasi(Request $request, $pengajuanId)
    {
        $request->validate([
            'tanggal_realisasi' => 'required|date',
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:0',
        ]);

        RealisasiDanaOperasional::create([
            'pengajuan_id' => $pengajuanId,
            'tanggal_realisasi' => $request->tanggal_realisasi,
            'uraian' => $request->uraian,
            'nominal' => $request->nominal,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('dana-operasional.show-pengajuan', $pengajuanId)
            ->with('success', 'Realisasi berhasil ditambahkan!');
    }

    // Laporan Harian
    public function laporanHarian($tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::today();
        $saldo = SaldoHarianOperasional::with('pengajuan.realisasi')
            ->whereDate('tanggal', $tanggal)
            ->first();
        
        return view('dana-operasional.laporan-harian', compact('saldo', 'tanggal'));
    }

    // Export ke Excel
    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        $filename = 'Transaksi_Operasional_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.xlsx';
        
        return Excel::download(
            new TransaksiOperasionalExport($startDate, $endDate),
            $filename
        );
    }

    // Template Excel untuk Import
    public function downloadTemplate()
    {
        // Generate template untuk import dengan kolom tanggal
        return $this->generateTemplateWithDate();
    }

    // Generate Template Excel dengan Kolom Tanggal
    private function generateTemplateWithDate()
    {
        $headings = [
            ['TEMPLATE IMPORT TRANSAKSI DANA OPERASIONAL BUMI SULTAN'],
            ['PANDUAN PENGISIAN:'],
            ['1. Kolom TANGGAL (wajib): Format YYYY-MM-DD atau DD/MM/YYYY'],
            ['2. Kolom KETERANGAN (wajib): Deskripsi transaksi, contoh: Bayar listrik, Bensin motor, ATK kantor'],
            ['3. Kolom DANA MASUK: Isi nominal jika transaksi MASUK (uang bertambah), kosongkan jika keluar'],
            ['4. Kolom DANA KELUAR: Isi nominal jika transaksi KELUAR (uang berkurang), kosongkan jika masuk'],
            ['5. Nominal tanpa titik/koma, contoh: 150000 bukan 150.000'],
            ['6. Sistem otomatis deteksi kategori dari keterangan (Transport, ATK, Konsumsi, dll)'],
            ['7. Urutan baris = urutan tampil di sistem, jadi atur sesuai kronologi waktu'],
            [''],
            ['CONTOH DATA - HAPUS BARIS INI DAN ISI DATA ANDA DI BAWAH'],
            [''],
            ['Tanggal', 'Keterangan', 'Dana Masuk', 'Dana Keluar'],
            ['2025-01-01', 'Saldo awal kas Januari 2025', '10000000', ''],
            ['2025-01-02', 'Pembelian ATK (pulpen, buku, map)', '', '150000'],
            ['2025-01-02', 'Bensin motor operasional', '', '50000'],
            ['2025-01-03', 'Transfer dari kas pusat', '5000000', ''],
            ['2025-01-03', 'Bayar listrik bulan Desember', '', '250000'],
            ['2025-01-04', 'Konsumsi rapat mingguan', '', '75000'],
            ['2025-01-05', 'Servis kendaraan operasional', '', '350000'],
        ];
        
        return Excel::download(new class($headings) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithStyles {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function array(): array { return $this->data; }
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
                // Merge cells untuk header
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
                $sheet->mergeCells('A4:D4');
                $sheet->mergeCells('A5:D5');
                $sheet->mergeCells('A6:D6');
                $sheet->mergeCells('A7:D7');
                $sheet->mergeCells('A8:D8');
                $sheet->mergeCells('A9:D9');
                $sheet->mergeCells('A11:D11');
                
                // Set column width
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(60);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(18);
                
                // Styling
                return [
                    // Header utama - Biru bold
                    1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E5090']], 'alignment' => ['horizontal' => 'center']],
                    
                    // Panduan - Orange
                    2 => ['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FF6B00']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']]],
                    
                    // Petunjuk 1-7
                    3 => ['font' => ['size' => 10, 'color' => ['rgb' => '0070C0']]],
                    4 => ['font' => ['size' => 10, 'color' => ['rgb' => '0070C0']]],
                    5 => ['font' => ['size' => 10, 'color' => ['rgb' => '0070C0']]],
                    6 => ['font' => ['size' => 10, 'color' => ['rgb' => '0070C0']]],
                    7 => ['font' => ['size' => 10, 'color' => ['rgb' => 'C00000']], 'font' => ['bold' => true]],
                    8 => ['font' => ['size' => 10, 'color' => ['rgb' => '0070C0']]],
                    9 => ['font' => ['size' => 10, 'color' => ['rgb' => '00B050']], 'font' => ['bold' => true]],
                    
                    // Warning - Merah bold
                    11 => ['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C00000']], 'alignment' => ['horizontal' => 'center']],
                    
                    // Header kolom - Kuning bold
                    13 => ['font' => ['bold' => true, 'size' => 11], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC000']], 'alignment' => ['horizontal' => 'center']],
                    
                    // Data contoh - Abu-abu muda
                    14 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]],
                    15 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]],
                    16 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]],
                    17 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]],
                    18 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]],
                    19 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]],
                    20 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]],
                ];
            }
        }, 'Template_Import_Transaksi_Operasional.xlsx');
    }

    // Import dari Excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // Max 5MB
            'pengajuan_id' => 'nullable|exists:pengajuan_dana_operasional,id',
        ]);

        try {
            $file = $request->file('file');
            $pengajuanId = $request->pengajuan_id;
            
            \Log::info('Import Excel dimulai', [
                'filename' => $file->getClientOriginalName(),
                'pengajuan_id' => $pengajuanId,
                'user_id' => auth()->id(),
            ]);
            
            // Hitung jumlah data sebelum import
            $countBefore = RealisasiDanaOperasional::count();
            
            // Import data
            Excel::import(new TransaksiOperasionalImport($pengajuanId), $file);
            
            // Hitung jumlah data setelah import
            $countAfter = RealisasiDanaOperasional::count();
            $jumlahImport = $countAfter - $countBefore;
            
            \Log::info('Import Excel berhasil', [
                'jumlah_data' => $jumlahImport
            ]);
            
            // Ambil data yang baru diimpor untuk detect range tanggal
            $dataImport = RealisasiDanaOperasional::orderBy('id', 'desc')
                ->limit($jumlahImport)
                ->get();
            
            if ($dataImport->isNotEmpty()) {
                // Ambil tanggal terkecil dan terbesar dari data yang diimpor
                $tanggalMin = $dataImport->min('tanggal_realisasi');
                $tanggalMax = $dataImport->max('tanggal_realisasi');
                
                // Jika semua data dalam 1 bulan yang sama, redirect ke filter bulan
                $bulanMin = Carbon::parse($tanggalMin)->format('Y-m');
                $bulanMax = Carbon::parse($tanggalMax)->format('Y-m');
                
                if ($bulanMin === $bulanMax) {
                    // Semua data di bulan yang sama, redirect ke filter bulan
                    return redirect()->route('dana-operasional.index', [
                        'filter_type' => 'bulan',
                        'bulan' => $bulanMin
                    ])->with('success', "✅ Berhasil import {$jumlahImport} transaksi untuk bulan " . Carbon::parse($bulanMin)->locale('id')->isoFormat('MMMM YYYY'));
                } else {
                    // Data di berbagai bulan, redirect ke filter range
                    return redirect()->route('dana-operasional.index', [
                        'filter_type' => 'range',
                        'start_date' => Carbon::parse($tanggalMin)->format('Y-m-d'),
                        'end_date' => Carbon::parse($tanggalMax)->format('Y-m-d')
                    ])->with('success', "✅ Berhasil import {$jumlahImport} transaksi dari " . 
                        Carbon::parse($tanggalMin)->format('d M Y') . " sampai " . 
                        Carbon::parse($tanggalMax)->format('d M Y'));
                }
            }
            
            return redirect()->route('dana-operasional.index')
                ->with('success', "✅ Berhasil import {$jumlahImport} transaksi dari Excel!");
                
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()->back()
                ->with('error', 'Import gagal: ' . implode(' | ', $errors));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Import Excel dengan Preview (Bank-Grade System)
     * Menerima data yang sudah divalidasi dari JavaScript
     */
    public function importExcelPreview(Request $request)
    {
        try {
            $data = $request->input('data');
            
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data untuk diimport'
                ], 400);
            }
            
            \DB::beginTransaction();
            
            $tanggalMin = null;
            $tanggalMax = null;
            $inserted = 0;
            $failed = 0;
            $errors = [];
            
            foreach ($data as $index => $row) {
                try {
                    // Insert transaksi dengan retry mechanism untuk handle duplicate
                    $maxRetries = 3;
                    $retry = 0;
                    $created = false;
                    
                    while (!$created && $retry < $maxRetries) {
                        try {
                            $transaksi = RealisasiDanaOperasional::create([
                                'tanggal_realisasi' => $row['tanggal'],
                                'keterangan' => $row['keterangan'],
                                'uraian' => $row['keterangan'], // Same as keterangan for compatibility
                                'tipe_transaksi' => $row['tipe'],
                                'nominal' => $row['nominal'],
                                'kategori' => $row['kategori'] ?? 'Lain-lain',
                                'created_by' => auth()->id(),
                                'status' => 'active',
                            ]);
                            
                            $created = true;
                            $inserted++;
                            
                            // Track date range
                            $tanggal = Carbon::parse($row['tanggal']);
                            if (!$tanggalMin || $tanggal->lt($tanggalMin)) {
                                $tanggalMin = $tanggal;
                            }
                            if (!$tanggalMax || $tanggal->gt($tanggalMax)) {
                                $tanggalMax = $tanggal;
                            }
                            
                        } catch (\Illuminate\Database\QueryException $e) {
                            // Check if it's duplicate key error
                            if (str_contains($e->getMessage(), 'Duplicate entry') && $retry < $maxRetries - 1) {
                                // Retry with small delay
                                $retry++;
                                usleep(100000); // 100ms delay
                                continue;
                            } else {
                                throw $e; // Re-throw jika bukan duplicate atau sudah max retry
                            }
                        }
                    }
                    
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
                    \Log::error("Import Excel failed for row " . ($index + 1), [
                        'row' => $row,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Jika error bukan duplicate, rollback dan stop
                    if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                        throw $e;
                    }
                }
            }
            
            // Recalculate saldo harian dari tanggal paling awal
            if ($tanggalMin) {
                RealisasiDanaOperasional::recalculateSaldoHarian($tanggalMin->format('Y-m-d'));
            }
            
            // Log activity
            \App\Models\ActivityLog::log(
                'dana_operasional',
                'import_excel',
                "Import {$inserted} transaksi dari Excel dengan preview" . ($failed > 0 ? " ({$failed} gagal)" : ''),
                [
                    'jumlah_sukses' => $inserted,
                    'jumlah_gagal' => $failed,
                    'periode' => $tanggalMin ? ($tanggalMin->format('Y-m-d') . ' s/d ' . $tanggalMax->format('Y-m-d')) : '-',
                    'errors' => $errors
                ]
            );
            
            \DB::commit();
            
            // Determine redirect URL based on periode
            $bulanMin = $tanggalMin->format('Y-m');
            $bulanMax = $tanggalMax->format('Y-m');
            
            if ($bulanMin === $bulanMax) {
                // Same month
                $redirectUrl = route('dana-operasional.index', [
                    'filter_type' => 'bulan',
                    'bulan' => $bulanMin
                ]);
                $periode = $tanggalMin->locale('id')->isoFormat('MMMM YYYY');
            } else {
                // Different months
                $redirectUrl = route('dana-operasional.index', [
                    'filter_type' => 'range',
                    'start_date' => $tanggalMin->format('Y-m-d'),
                    'end_date' => $tanggalMax->format('Y-m-d')
                ]);
                $periode = $tanggalMin->format('d M Y') . ' - ' . $tanggalMax->format('d M Y');
            }
            
            return response()->json([
                'success' => true,
                'count' => $inserted,
                'failed' => $failed,
                'errors' => $errors,
                'periode' => $periode,
                'redirect_url' => $redirectUrl,
                'message' => $failed > 0 
                    ? "Import selesai: {$inserted} berhasil, {$failed} gagal" 
                    : "Berhasil import {$inserted} transaksi"
            ]);
            
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Import Excel Preview Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Detail Transaksi
    public function detail($id)
    {
        $realisasi = RealisasiDanaOperasional::with(['pengajuan', 'creator'])->findOrFail($id);
        
        $html = '
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="/dana-operasional/' . $id . '/download-resi" class="btn btn-sm btn-primary" target="_blank">
                    <i class="bx bx-download"></i> Download Resi (PDF)
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <td width="40%"><strong>Nomor Transaksi</strong></td>
                        <td>: <span class="badge bg-primary">' . ($realisasi->nomor_transaksi ?? $realisasi->nomor_realisasi ?? 'N/A') . '</span></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>: ' . $realisasi->tanggal_realisasi->format('d M Y') . '</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Input</strong></td>
                        <td>: ' . $realisasi->created_at->format('H:i:s') . ' WIB</td>
                    </tr>
                    <tr>
                        <td><strong>Tipe</strong></td>
                        <td>: <span class="badge bg-' . ($realisasi->tipe_transaksi == 'masuk' ? 'success' : 'danger') . '">' . strtoupper($realisasi->tipe_transaksi) . '</span></td>
                    </tr>
                    <tr>
                        <td><strong>Kategori</strong></td>
                        <td>: <span class="badge bg-info">' . ($realisasi->kategori ?? '-') . '</span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <td width="40%"><strong>Nominal</strong></td>
                        <td>: <span class="text-' . ($realisasi->tipe_transaksi == 'masuk' ? 'success' : 'danger') . '"><strong>Rp ' . number_format($realisasi->nominal, 2, ',', '.') . '</strong></span></td>
                    </tr>
                    <tr>
                        <td><strong>Diinput Oleh</strong></td>
                        <td>: ' . ($realisasi->creator ? $realisasi->creator->name : '-') . '</td>
                    </tr>
                    <tr>
                        <td><strong>Pengajuan</strong></td>
                        <td>: <span class="badge bg-success">' . ($realisasi->pengajuan ? $realisasi->pengajuan->nomor_pengajuan : '-') . '</span></td>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h6>Keterangan Transaksi:</h6>
                <div class="alert alert-secondary">
                    ' . $realisasi->uraian . '
                </div>
                ' . ($realisasi->keterangan ? '<small class="text-muted">Catatan: ' . $realisasi->keterangan . '</small>' : '') . '
            </div>
        </div>';
        
        // Tambahkan foto jika ada
        if ($realisasi->foto_bukti) {
            $html .= '
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Foto Bukti:</h6>
                        <a href="' . \Storage::url($realisasi->foto_bukti) . '" download class="btn btn-sm btn-success">
                            <i class="bx bx-download"></i> Download Foto
                        </a>
                    </div>
                    <div class="text-center">
                        <img src="' . \Storage::url($realisasi->foto_bukti) . '" alt="Foto Bukti" class="img-fluid rounded border" style="max-height: 400px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" onclick="window.open(this.src, \'_blank\')">
                        <p class="text-muted mt-2"><small><i class="bx bx-info-circle"></i> Klik foto untuk memperbesar</small></p>
                    </div>
                </div>
            </div>';
        } else {
            $html .= '
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6>Foto Bukti:</h6>
                    <div class="alert alert-warning">
                        <i class="bx bx-image-alt"></i> Belum ada foto bukti yang diupload
                    </div>
                </div>
            </div>';
        }
        
        return response($html);
    }

    // Form Edit Transaksi
    public function edit($id)
    {
        $realisasi = RealisasiDanaOperasional::findOrFail($id);
        
        $html = '
        <input type="hidden" id="edit_id" value="' . $realisasi->id . '">
        <input type="hidden" name="_method" value="POST">
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_realisasi" value="' . $realisasi->tanggal_realisasi->format('Y-m-d') . '" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                    <select class="form-select" name="tipe_transaksi" required>
                        <option value="keluar" ' . ($realisasi->tipe_transaksi == 'keluar' ? 'selected' : '') . '>Dana Keluar</option>
                        <option value="masuk" ' . ($realisasi->tipe_transaksi == 'masuk' ? 'selected' : '') . '>Dana Masuk</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Kategori <span class="text-danger">*</span></label>
            <select class="form-select" name="kategori" required>
                <option value="Transport & Kendaraan" ' . ($realisasi->kategori == 'Transport & Kendaraan' ? 'selected' : '') . '>Transport & Kendaraan</option>
                <option value="ATK & Perlengkapan" ' . ($realisasi->kategori == 'ATK & Perlengkapan' ? 'selected' : '') . '>ATK & Perlengkapan</option>
                <option value="Konsumsi" ' . ($realisasi->kategori == 'Konsumsi' ? 'selected' : '') . '>Konsumsi</option>
                <option value="Utilitas" ' . ($realisasi->kategori == 'Utilitas' ? 'selected' : '') . '>Utilitas</option>
                <option value="Maintenance" ' . ($realisasi->kategori == 'Maintenance' ? 'selected' : '') . '>Maintenance</option>
                <option value="Kebersihan" ' . ($realisasi->kategori == 'Kebersihan' ? 'selected' : '') . '>Kebersihan</option>
                <option value="Kesehatan" ' . ($realisasi->kategori == 'Kesehatan' ? 'selected' : '') . '>Kesehatan</option>
                <option value="Komunikasi" ' . ($realisasi->kategori == 'Komunikasi' ? 'selected' : '') . '>Komunikasi</option>
                <option value="Administrasi" ' . ($realisasi->kategori == 'Administrasi' ? 'selected' : '') . '>Administrasi</option>
                <option value="Operasional" ' . ($realisasi->kategori == 'Operasional' ? 'selected' : '') . '>Operasional</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Keterangan/Uraian <span class="text-danger">*</span></label>
            <textarea class="form-control" name="uraian" rows="3" required>' . $realisasi->uraian . '</textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="nominal" value="' . $realisasi->nominal . '" step="0.01" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Catatan Tambahan</label>
            <textarea class="form-control" name="keterangan" rows="2">' . $realisasi->keterangan . '</textarea>
        </div>';
        
        return response($html);
    }

    // Update Transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_realisasi' => 'required|date',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'kategori' => 'required|string',
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:0',
        ]);

        try {
            $realisasi = RealisasiDanaOperasional::findOrFail($id);
            
            $realisasi->update([
                'tanggal_realisasi' => $request->tanggal_realisasi,
                'tipe_transaksi' => $request->tipe_transaksi,
                'kategori' => $request->kategori,
                'uraian' => $request->uraian,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
            ]);

            // Update saldo harian
            if ($realisasi->pengajuan) {
                $realisasi->pengajuan->updateSaldoHarian();
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update kategori transaksi saja (Quick Update dari Dropdown)
     */
    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
        ]);

        try {
            $realisasi = RealisasiDanaOperasional::findOrFail($id);
            
            // Cek jika transaksi sudah di-void
            if ($realisasi->status === 'voided') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah kategori transaksi yang sudah di-void'
                ], 400);
            }
            
            $oldKategori = $realisasi->kategori;
            
            // Update kategori
            $realisasi->update([
                'kategori' => $request->kategori,
                'updated_by' => auth()->id(),
            ]);

            // Log activity
            \App\Models\ActivityLog::log(
                'dana_operasional',
                'update_kategori',
                "Update kategori transaksi {$realisasi->nomor_realisasi}",
                [
                    'transaksi_id' => $id,
                    'nomor_transaksi' => $realisasi->nomor_realisasi,
                    'old_kategori' => $oldKategori,
                    'new_kategori' => $request->kategori,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diupdate',
                'data' => [
                    'id' => $realisasi->id,
                    'kategori' => $realisasi->kategori,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Update Kategori Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal update kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    // Hapus Transaksi
    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $realisasi = RealisasiDanaOperasional::findOrFail($id);
            $tanggalRealisasi = $realisasi->tanggal_realisasi;
            $nomorTransaksi = $realisasi->nomor_transaksi;
            $nominal = $realisasi->nominal;
            $tipe = $realisasi->tipe_transaksi;
            
            // Log activity SEBELUM hapus (karena data akan hilang)
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'module' => 'Dana Operasional',
                'action' => 'delete',
                'description' => "Menghapus transaksi {$nomorTransaksi} - {$realisasi->keterangan} (Rp " . number_format($nominal, 0, ',', '.') . ")",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Hapus foto jika ada
            if ($realisasi->foto_bukti && Storage::disk('public')->exists($realisasi->foto_bukti)) {
                Storage::disk('public')->delete($realisasi->foto_bukti);
            }

            // Hapus transaksi
            $realisasi->delete();

            // Recalculate saldo harian setelah delete
            RealisasiDanaOperasional::recalculateSaldoHarian($tanggalRealisasi);

            DB::commit();

            // Auto redirect ke filter yang sesuai berdasarkan tanggal transaksi yang dihapus
            $bulanTransaksi = Carbon::parse($tanggalRealisasi)->format('Y-m');

            // Cek apakah request AJAX atau HTML form
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ Transaksi berhasil dihapus'
                ]);
            }

            return redirect()->route('dana-operasional.index', [
                'filter_type' => 'bulan',
                'bulan' => $bulanTransaksi
            ])->with('success', "✅ Transaksi {$nomorTransaksi} berhasil dihapus");

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Gagal hapus transaksi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', '❌ Gagal hapus transaksi: ' . $e->getMessage());
        }
    }

    // Upload Foto
    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        try {
            $realisasi = RealisasiDanaOperasional::findOrFail($id);
            
            // Hapus foto lama jika ada
            if ($realisasi->foto_bukti) {
                \Storage::disk('public')->delete($realisasi->foto_bukti);
            }
            
            // Upload foto baru
            $file = $request->file('foto');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('dana-operasional/' . date('Y/m'), $filename, 'public');
            
            $realisasi->update(['foto_bukti' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload',
                'path' => \Storage::url($path)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    // Download Resi PDF
    public function downloadResi($id)
    {
        $realisasi = RealisasiDanaOperasional::with(['pengajuan', 'creator'])->findOrFail($id);
        
        $data = [
            'realisasi' => $realisasi,
            'tanggal_cetak' => Carbon::now()
        ];
        
        $pdf = Pdf::loadView('dana-operasional.resi-pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Clean filename - remove special characters
        $filename = 'Resi_' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $realisasi->nomor_realisasi) . '.pdf';
        
        return $pdf->download($filename);
    }

    // Create Manual Transaction
    public function create(Request $request)
    {
        $request->validate([
            'tanggal_realisasi' => 'required|date',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'kategori' => 'required|string',
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor realisasi
            $tanggal = Carbon::parse($request->tanggal_realisasi);
            $prefix = 'RLS/' . $tanggal->format('Y/m');
            
            // Find the last number used in this month (including soft deleted)
            $lastRealisasi = RealisasiDanaOperasional::withTrashed()
                ->whereYear('tanggal_realisasi', $tanggal->year)
                ->whereMonth('tanggal_realisasi', $tanggal->month)
                ->where('nomor_realisasi', 'like', $prefix . '%')
                ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_realisasi, "/", -1) AS UNSIGNED) DESC')
                ->first();

            if ($lastRealisasi) {
                $lastNumber = (int) substr($lastRealisasi->nomor_realisasi, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $nomorRealisasi = $prefix . '/' . $newNumber;

            // Create realisasi
            $realisasi = RealisasiDanaOperasional::create([
                'pengajuan_id' => $request->pengajuan_id,
                'nomor_realisasi' => $nomorRealisasi,
                'tanggal_realisasi' => $request->tanggal_realisasi,
                'tipe_transaksi' => $request->tipe_transaksi,
                'kategori' => $request->kategori,
                'uraian' => $request->uraian,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->id(),
            ]);

            // Update saldo harian jika ada pengajuan
            if ($request->pengajuan_id) {
                $pengajuan = PengajuanDanaOperasional::find($request->pengajuan_id);
                if ($pengajuan) {
                    $pengajuan->updateSaldoHarian();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditambahkan',
                'data' => $realisasi
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export transaksi dana operasional ke PDF bergaya bank internasional
     * Menampilkan SEMUA transaksi detail dengan kode, jam, kategori lengkap
     */
    public function exportPdf(Request $request)
    {
        $filterType = $request->get('filter_type', 'bulan');
        
        // Tentukan range tanggal berdasarkan tipe filter (sama seperti index)
        switch ($filterType) {
            case 'tahun':
                $tahun = $request->get('tahun', date('Y'));
                $tanggalDari = Carbon::create($tahun, 1, 1)->startOfYear();
                $tanggalSampai = Carbon::create($tahun, 12, 31)->endOfYear();
                $periodeLabel = "Tahun $tahun";
                break;
                
            case 'minggu':
                if ($request->has('minggu')) {
                    list($tahun, $minggu) = explode('-W', $request->minggu);
                    $tanggalDari = Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
                    $tanggalSampai = Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();
                } else {
                    $tanggalDari = Carbon::now()->startOfWeek();
                    $tanggalSampai = Carbon::now()->endOfWeek();
                }
                $periodeLabel = "Minggu " . $tanggalDari->format('d M') . " - " . $tanggalSampai->format('d M Y');
                break;
                
            case 'range':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $tanggalDari = Carbon::parse($request->start_date)->startOfDay();
                    $tanggalSampai = Carbon::parse($request->end_date)->endOfDay();
                } else {
                    $tanggalDari = Carbon::now()->startOfMonth();
                    $tanggalSampai = Carbon::now()->endOfMonth();
                }
                $periodeLabel = $tanggalDari->format('d M Y') . " - " . $tanggalSampai->format('d M Y');
                break;
                
            default: // 'bulan'
                $bulan = $request->get('bulan', date('Y-m'));
                $tanggalDari = Carbon::parse($bulan . '-01')->startOfMonth();
                $tanggalSampai = Carbon::parse($bulan . '-01')->endOfMonth();
                $periodeLabel = $tanggalDari->locale('id')->isoFormat('MMMM YYYY');
                break;
        }

        // Ambil SEMUA transaksi detail (realisasi) berdasarkan filter tanggal
        $transaksiDetail = RealisasiDanaOperasional::with(['pengajuan', 'creator'])
            ->whereBetween('tanggal_realisasi', [$tanggalDari->startOfDay(), $tanggalSampai->endOfDay()])
            ->orderBy('tanggal_realisasi', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil ringkasan saldo harian
        $saldoHarian = SaldoHarianOperasional::whereBetween('tanggal', [$tanggalDari->startOfDay(), $tanggalSampai->endOfDay()])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Hitung total pemasukan dan pengeluaran dari transaksi detail
        // Support berbagai format tipe transaksi: 'pemasukan'/'masuk' dan 'pengeluaran'/'keluar'
        $totalPemasukan = $transaksiDetail->whereIn('tipe_transaksi', ['pemasukan', 'masuk'])->sum('nominal');
        $totalPengeluaran = $transaksiDetail->whereIn('tipe_transaksi', ['pengeluaran', 'keluar'])->sum('nominal');
        
        // Hitung saldo awal dan akhir
        $saldoAwal = $saldoHarian->first()->saldo_awal ?? 0;
        $saldoAkhir = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        // Data untuk PDF
        $data = [
            'transaksi_detail' => $transaksiDetail,
            'saldo_harian' => $saldoHarian,
            'tanggal_dari' => $tanggalDari->format('d F Y'),
            'tanggal_sampai' => $tanggalSampai->format('d F Y'),
            'periode_label' => $periodeLabel,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo_awal' => $saldoAwal,
            'saldo_akhir' => $saldoAkhir,
            'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
            'total_transaksi' => $transaksiDetail->count(),
        ];

        // Generate PDF dengan landscape untuk space lebih luas
        $pdf = PDF::loadView('dana-operasional.pdf-simple', $data);
        $pdf->setPaper('a4', 'landscape');
        
        // Nama file dengan tanggal
        $filename = 'Laporan_Keuangan_' . $tanggalDari->format('Ymd') . '_' . $tanggalSampai->format('Ymd') . '.pdf';
        
        // Save PDF to storage dan database untuk publish ke karyawan
        $this->saveDanaOperasionalToDatabase($filterType, $filename, $periodeLabel, $tanggalDari, $tanggalSampai, $pdf);
        
        return $pdf->download($filename);
    }

    /**
     * Save Dana Operasional PDF to database for publish system
     */
    private function saveDanaOperasionalToDatabase($filterType, $filename, $periodeLabel, $tanggalDari, $tanggalSampai, $pdf)
    {
        try {
            // Map filter type ke periode enum
            $periode = match($filterType) {
                'minggu' => 'MINGGUAN',
                'bulan' => 'BULANAN',
                'tahun' => 'TAHUNAN',
                'range' => 'CUSTOM',
                default => 'BULANAN'
            };

            // Map ke jenis_laporan enum
            $jenisLaporan = match($filterType) {
                'minggu' => 'LAPORAN_MINGGUAN',
                'bulan' => 'LAPORAN_BULANAN',
                'tahun' => 'LAPORAN_TAHUNAN',
                'range' => 'LAPORAN_CUSTOM',
                default => 'LAPORAN_BULANAN'
            };

            // Generate nomor laporan
            $lastNumber = DB::table('laporan_keuangan')
                ->where('nomor_laporan', 'like', 'LAP-' . date('Ym') . '%')
                ->count();
            $nomor = 'LAP-' . date('Ym') . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            // Save PDF file to storage
            $pdfContent = $pdf->output();
            $storagePath = 'laporan-keuangan/' . $filename;
            \Storage::disk('public')->put($storagePath, $pdfContent);

            // Cek apakah sudah ada laporan dengan periode yang sama
            $existing = DB::table('laporan_keuangan')
                ->where('periode', $periode)
                ->where('nama_laporan', $periodeLabel)
                ->where('jenis_laporan', $jenisLaporan)
                ->first();

            if ($existing) {
                // Update existing
                DB::table('laporan_keuangan')
                    ->where('id', $existing->id)
                    ->update([
                        'tanggal_mulai' => $tanggalDari->format('Y-m-d'),
                        'tanggal_selesai' => $tanggalSampai->format('Y-m-d'),
                        'file_pdf' => $storagePath,
                        'user_id' => auth()->id(),
                        'generated_at' => now(),
                        'updated_at' => now(),
                    ]);
            } else {
                // Create new
                DB::table('laporan_keuangan')->insert([
                    'nomor_laporan' => $nomor,
                    'jenis_laporan' => $jenisLaporan,
                    'nama_laporan' => $periodeLabel,
                    'tanggal_mulai' => $tanggalDari->format('Y-m-d'),
                    'tanggal_selesai' => $tanggalSampai->format('Y-m-d'),
                    'periode' => $periode,
                    'file_pdf' => $storagePath,
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
            \Log::error('Failed to save Dana Operasional to database: ' . $e->getMessage());
        }
    }

    // Update Saldo Awal
    public function updateSaldoAwal(Request $request, $id)
    {
        $request->validate([
            'saldo_awal' => 'required|numeric'
        ]);

        try {
            $saldo = SaldoHarianOperasional::findOrFail($id);
            $tanggal = $saldo->tanggal;
            
            // Update saldo awal
            $saldo->saldo_awal = $request->saldo_awal;
            $saldo->save();

            // Recalculate saldo untuk tanggal ini dan selanjutnya
            RealisasiDanaOperasional::recalculateSaldoHarian($tanggal);

            return redirect()->route('dana-operasional.index')
                ->with('success', 'Saldo awal berhasil diupdate');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update saldo awal: ' . $e->getMessage());
        }
    }

    // Hapus Saldo Awal
    public function destroySaldoAwal($id)
    {
        try {
            $saldo = SaldoHarianOperasional::findOrFail($id);
            $tanggal = $saldo->tanggal->format('d-M-Y');
            
            // Cek apakah ada transaksi di tanggal ini
            $adaTransaksi = RealisasiDanaOperasional::whereDate('tanggal_realisasi', $saldo->tanggal)->count();
            
            if ($adaTransaksi > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus saldo awal karena masih ada transaksi di tanggal ini');
            }

            $saldo->delete();

            return redirect()->route('dana-operasional.index')
                ->with('success', "Saldo awal tanggal {$tanggal} berhasil dihapus");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal hapus saldo awal: ' . $e->getMessage());
        }
    }

    /**
     * Kirim Email Laporan Keuangan
     */
    public function sendEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'filter_type' => 'required|in:bulan,tahun,minggu,range',
            ]);

            // Parse multiple emails
            $emails = array_map('trim', explode(',', $request->email));
            
            // Validate each email
            foreach ($emails as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Email tidak valid: {$email}"
                    ], 422);
                }
            }

            $filterType = $request->filter_type;
            
            // Tentukan range tanggal berdasarkan tipe filter
            switch ($filterType) {
                case 'tahun':
                    $tahun = $request->get('tahun', date('Y'));
                    $tanggalDari = Carbon::create($tahun, 1, 1)->startOfYear();
                    $tanggalSampai = Carbon::create($tahun, 12, 31)->endOfYear();
                    $periodeLabel = "Tahun $tahun";
                    break;
                    
                case 'minggu':
                    if ($request->has('minggu')) {
                        list($tahun, $minggu) = explode('-W', $request->minggu);
                        $tanggalDari = Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
                        $tanggalSampai = Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();
                    } else {
                        $tanggalDari = Carbon::now()->startOfWeek();
                        $tanggalSampai = Carbon::now()->endOfWeek();
                    }
                    $periodeLabel = "Minggu " . $tanggalDari->format('d M') . " - " . $tanggalSampai->format('d M Y');
                    break;
                    
                case 'range':
                    if ($request->has('tanggal_awal') && $request->has('tanggal_akhir')) {
                        $tanggalDari = Carbon::parse($request->tanggal_awal)->startOfDay();
                        $tanggalSampai = Carbon::parse($request->tanggal_akhir)->endOfDay();
                    } else {
                        $tanggalDari = Carbon::now()->startOfMonth();
                        $tanggalSampai = Carbon::now()->endOfMonth();
                    }
                    $periodeLabel = $tanggalDari->format('d M Y') . " - " . $tanggalSampai->format('d M Y');
                    break;
                    
                default: // 'bulan'
                    $bulan = $request->get('bulan', date('Y-m'));
                    $tanggalDari = Carbon::parse($bulan . '-01')->startOfMonth();
                    $tanggalSampai = Carbon::parse($bulan . '-01')->endOfMonth();
                    $periodeLabel = $tanggalDari->locale('id')->isoFormat('MMMM YYYY');
                    break;
            }

            // Clone untuk query agar tidak memodifikasi original
            $queryTanggalDari = clone $tanggalDari;
            $queryTanggalSampai = clone $tanggalSampai;

            // Ambil transaksi detail
            $transaksiDetail = RealisasiDanaOperasional::with(['pengajuan', 'creator'])
                ->whereBetween('tanggal_realisasi', [$queryTanggalDari->startOfDay(), $queryTanggalSampai->endOfDay()])
                ->orderBy('tanggal_realisasi', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            // Ambil ringkasan saldo harian
            $querySaldoDari = clone $tanggalDari;
            $querySaldoSampai = clone $tanggalSampai;
            $saldoHarian = SaldoHarianOperasional::whereBetween('tanggal', [$querySaldoDari->startOfDay(), $querySaldoSampai->endOfDay()])
                ->orderBy('tanggal', 'asc')
                ->get();

            // Hitung total
            $totalMasuk = $transaksiDetail->whereIn('tipe_transaksi', ['pemasukan', 'masuk'])->sum('nominal');
            $totalKeluar = $transaksiDetail->whereIn('tipe_transaksi', ['pengeluaran', 'keluar'])->sum('nominal');
            $saldoAwal = $saldoHarian->first()->saldo_awal ?? 0;
            $saldoAkhir = $saldoAwal + $totalMasuk - $totalKeluar;

            // Data untuk PDF
            $data = [
                'transaksi_detail' => $transaksiDetail,
                'saldo_harian' => $saldoHarian,
                'tanggal_dari' => $tanggalDari->format('d F Y'),
                'tanggal_sampai' => $tanggalSampai->format('d F Y'),
                'periode_label' => $periodeLabel,
                'total_pemasukan' => $totalMasuk,
                'total_pengeluaran' => $totalKeluar,
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAkhir,
                'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
                'total_transaksi' => $transaksiDetail->count(),
            ];

            // Generate PDF
            $pdf = PDF::loadView('dana-operasional.pdf-simple', $data);
            $pdf->setPaper('a4', 'landscape');
            
            // Save temporary PDF
            $filename = 'Laporan_Keuangan_' . $tanggalDari->format('Ymd') . '_' . $tanggalSampai->format('Ymd') . '.pdf';
            $tempPath = storage_path('app/temp/' . $filename);
            
            // Create temp directory if not exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            file_put_contents($tempPath, $pdf->output());

            // Kirim email ke setiap penerima
            $successCount = 0;
            $failedEmails = [];
            
            // Log untuk debugging
            \Log::info('Sending email with dates:', [
                'tanggalAwal' => $tanggalDari->format('d F Y'),
                'tanggalAkhir' => $tanggalSampai->format('d F Y'),
                'periode' => $periodeLabel
            ]);
            
            foreach ($emails as $email) {
                try {
                    \Mail::to($email)->send(new \App\Mail\LaporanKeuanganMail(
                        $periodeLabel,
                        $tanggalDari->format('d F Y'),  // Kirim format sudah jadi
                        $tanggalSampai->format('d F Y'), // Kirim format sudah jadi
                        $tempPath,
                        $totalMasuk,
                        $totalKeluar,
                        $saldoAkhir
                    ));
                    $successCount++;
                } catch (\Exception $e) {
                    $failedEmails[] = $email;
                    \Log::error("Failed to send email to {$email}: " . $e->getMessage());
                }
            }

            // Cleanup temporary PDF
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            if ($successCount > 0) {
                $message = "✅ Email berhasil dikirim ke {$successCount} penerima";
                if (count($failedEmails) > 0) {
                    $message .= "<br>⚠️ Gagal mengirim ke: " . implode(', ', $failedEmails);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Gagal mengirim email ke semua penerima'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Error sending laporan keuangan email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
