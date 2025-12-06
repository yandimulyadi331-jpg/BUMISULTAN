<?php

namespace App\Http\Controllers;

use App\Models\KeuanganSantriTransaction;
use App\Models\KeuanganSantriCategory;
use App\Models\KeuanganSantriSaldo;
use App\Models\Santri;
use App\Services\KeuanganSantriService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KeuanganSantriExport;
use App\Imports\KeuanganSantriImport;

class KeuanganSantriController extends Controller
{
    protected $keuanganService;

    public function __construct(KeuanganSantriService $keuanganService)
    {
        $this->keuanganService = $keuanganService;
    }

    /**
     * Dashboard Keuangan Santri - Daftar Saldo per Santri
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'aktif');

        // Get list santri dengan saldo masing-masing
        $query = Santri::select('id', 'nis', 'nama_lengkap', 'status_santri')
            ->with(['keuanganSaldo' => function($q) {
                $q->select('santri_id', 'saldo_awal', 'total_pemasukan', 'total_pengeluaran', 'saldo_akhir', 'last_transaction_date');
            }])
            ->where('status_santri', $status);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        $santriList = $query->orderBy('nama_lengkap')->paginate(20);

        // Statistik keseluruhan
        $totalSaldoSemua = KeuanganSantriSaldo::sum('saldo_akhir');
        $totalPemasukan = KeuanganSantriSaldo::sum('total_pemasukan');
        $totalPengeluaran = KeuanganSantriSaldo::sum('total_pengeluaran');
        $jumlahSantri = Santri::where('status_santri', 'aktif')->count();

        $statistik = [
            'total_saldo_semua' => $totalSaldoSemua,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'jumlah_santri' => $jumlahSantri,
        ];

        return view('keuangan-santri.index', compact(
            'santriList',
            'statistik',
            'search',
            'status'
        ));
    }

    /**
     * Detail transaksi per santri dengan filter
     */
    public function show(Request $request, $id)
    {
        $santri = Santri::with(['keuanganSaldo'])->findOrFail($id);
        
        $query = KeuanganSantriTransaction::where('santri_id', $id)
            ->with(['category', 'creator', 'verifier']);

        // Filter berdasarkan periode (default: hari ini)
        $periode = $request->get('periode', 'hari_ini');
        
        switch ($periode) {
            case 'hari_ini':
                $query->whereDate('tanggal_transaksi', today());
                break;
            
            case 'minggu_ini':
                $query->whereBetween('tanggal_transaksi', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            
            case 'bulan_ini':
                $query->whereMonth('tanggal_transaksi', now()->month)
                      ->whereYear('tanggal_transaksi', now()->year);
                break;
            
            case 'tahun_ini':
                $query->whereYear('tanggal_transaksi', now()->year);
                break;
            
            case 'rentang':
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $query->whereBetween('tanggal_transaksi', [
                        $request->tanggal_mulai,
                        $request->tanggal_selesai
                    ]);
                }
                break;
            
            case 'bulan':
                if ($request->filled('bulan')) {
                    $bulan = explode('-', $request->bulan);
                    $query->whereMonth('tanggal_transaksi', $bulan[1])
                          ->whereYear('tanggal_transaksi', $bulan[0]);
                }
                break;
            
            case 'tahun':
                if ($request->filled('tahun')) {
                    $query->whereYear('tanggal_transaksi', $request->tahun);
                }
                break;
            
            case 'semua':
                // Tidak ada filter tanggal
                break;
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $transactions = $query->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->all());

        // Data untuk modal edit
        $santriList = Santri::select('id', 'nis', 'nama_lengkap')
            ->orderBy('nama_lengkap')
            ->get();

        $categories = KeuanganSantriCategory::active()
            ->orderBy('jenis')
            ->orderBy('nama_kategori')
            ->get()
            ->groupBy('jenis');

        return view('keuangan-santri.show', compact('santri', 'transactions', 'santriList', 'categories'));
    }

    /**
     * Form tambah transaksi
     */
    public function create(Request $request)
    {
        $santriList = Santri::select('id', 'nis', 'nama_lengkap')
            ->orderBy('nama_lengkap')
            ->get();

        $categories = KeuanganSantriCategory::active()
            ->orderBy('jenis')
            ->orderBy('nama_kategori')
            ->get()
            ->groupBy('jenis');

        $selectedSantri = $request->get('santri_id');

        return view('keuangan-santri.create', compact('santriList', 'categories', 'selectedSantri'));
    }

    /**
     * Simpan transaksi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'category_id' => 'nullable|exists:keuangan_santri_categories,id',
            'catatan' => 'nullable|string',
            'metode_pembayaran' => 'nullable|string',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Upload bukti jika ada
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('keuangan_santri/bukti', $filename, 'public');
            $validated['bukti_file'] = $path;
        }

        $validated['created_by'] = Auth::id();

        // Create transaksi dengan service (auto-detect kategori & update saldo)
        $transaction = $this->keuanganService->createTransaction($validated);

        return redirect()
            ->route('keuangan-santri.show', $transaction->santri_id)
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    /**
     * Form edit transaksi
     */
    public function edit($id)
    {
        $transaction = KeuanganSantriTransaction::with(['category', 'santri'])->findOrFail($id);

        $santriList = Santri::select('id', 'nis', 'nama_lengkap')
            ->orderBy('nama_lengkap')
            ->get();

        $categories = KeuanganSantriCategory::active()
            ->orderBy('jenis')
            ->orderBy('nama_kategori')
            ->get()
            ->groupBy('jenis');

        return view('keuangan-santri.edit', compact('transaction', 'santriList', 'categories'));
    }

    /**
     * Update transaksi
     */
    public function update(Request $request, $id)
    {
        $transaction = KeuanganSantriTransaction::findOrFail($id);

        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'category_id' => 'nullable|exists:keuangan_santri_categories,id',
            'catatan' => 'nullable|string',
            'metode_pembayaran' => 'nullable|string',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Upload bukti baru jika ada
        if ($request->hasFile('bukti_file')) {
            // Hapus file lama
            if ($transaction->bukti_file) {
                Storage::disk('public')->delete($transaction->bukti_file);
            }

            $file = $request->file('bukti_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('keuangan_santri/bukti', $filename, 'public');
            $validated['bukti_file'] = $path;
        }

        $validated['updated_by'] = Auth::id();

        // Update transaksi dengan service
        $updatedTransaction = $this->keuanganService->updateTransaction($transaction, $validated);

        return redirect()
            ->route('keuangan-santri.show', $updatedTransaction->id)
            ->with('success', 'Transaksi berhasil diupdate!');
    }

    /**
     * Hapus transaksi
     */
    public function destroy($id)
    {
        $transaction = KeuanganSantriTransaction::findOrFail($id);

        // Hapus file bukti jika ada
        if ($transaction->bukti_file) {
            Storage::disk('public')->delete($transaction->bukti_file);
        }

        // Delete dengan service (restore saldo)
        $this->keuanganService->deleteTransaction($transaction);

        return redirect()
            ->route('keuangan-santri.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }

    /**
     * Halaman laporan
     */
    public function laporan(Request $request)
    {
        $filters = [
            'santri_id' => $request->get('santri_id'),
            'jenis' => $request->get('jenis'),
            'category_id' => $request->get('category_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'periode' => $request->get('periode'),
            'search' => $request->get('search'),
        ];

        $transactions = $this->keuanganService->getTransactions($filters)
            ->paginate(20);

        // Get data untuk filter
        $santriList = Santri::select('id', 'nis', 'nama_lengkap')
            ->orderBy('nama_lengkap')
            ->get();

        $categories = KeuanganSantriCategory::active()
            ->orderBy('nama_kategori')
            ->get();

        // Hitung total
        $totalPemasukan = $this->keuanganService->getTransactions($filters)
            ->pemasukan()
            ->sum('jumlah');

        $totalPengeluaran = $this->keuanganService->getTransactions($filters)
            ->pengeluaran()
            ->sum('jumlah');

        $summary = [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'selisih' => $totalPemasukan - $totalPengeluaran,
        ];

        return view('keuangan-santri.laporan', compact(
            'transactions',
            'santriList',
            'categories',
            'filters',
            'summary'
        ));
    }

    /**
     * Export PDF laporan
     */
    public function exportPdf(Request $request)
    {
        $filters = [
            'santri_id' => $request->get('santri_id'),
            'jenis' => $request->get('jenis'),
            'category_id' => $request->get('category_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'periode' => $request->get('periode'),
        ];

        $transactions = $this->keuanganService->getTransactions($filters)->get();

        $totalPemasukan = $transactions->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transactions->where('jenis', 'pengeluaran')->sum('jumlah');

        // Get info santri jika ada filter
        $santri = null;
        if ($filters['santri_id']) {
            $santri = Santri::find($filters['santri_id']);
        }

        // Get saldo jika ada santri
        $saldo = null;
        if ($santri) {
            $saldo = KeuanganSantriSaldo::where('santri_id', $santri->nik)->first();
        }

        $pdf = Pdf::loadView('keuangan-santri.pdf', compact(
            'transactions',
            'totalPemasukan',
            'totalPengeluaran',
            'filters',
            'santri',
            'saldo'
        ));

        $filename = 'Laporan_Keuangan_' . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = [
            'santri_id' => $request->get('santri_id'),
            'jenis' => $request->get('jenis'),
            'category_id' => $request->get('category_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'periode' => $request->get('periode'),
        ];

        $filename = 'Laporan_Keuangan_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(new KeuanganSantriExport($filters), $filename);
    }

    /**
     * Halaman import
     */
    public function importForm()
    {
        return view('keuangan-santri.import');
    }

    /**
     * Proses import Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'santri_id' => 'required|exists:santri,id',
        ]);

        try {
            $import = new KeuanganSantriImport($request->santri_id);
            Excel::import($import, $request->file('file'));

            $imported = $import->getImportedCount();
            $skipped = $import->getSkippedCount();

            $message = "Import berhasil! {$imported} transaksi ditambahkan";
            if ($skipped > 0) {
                $message .= ", {$skipped} baris dilewati";
            }

            return redirect()
                ->route('keuangan-santri.index', ['santri_id' => $request->santri_id])
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new \App\Exports\KeuanganSantriTemplateExport, 
            'Template_Import_Keuangan_Santri.xlsx'
        );
    }

    /**
     * Verifikasi transaksi
     */
    public function verify($id)
    {
        $transaction = KeuanganSantriTransaction::findOrFail($id);

        $transaction->update([
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Transaksi berhasil diverifikasi!');
    }

    /**
     * API: Detect kategori otomatis
     */
    public function detectCategory(Request $request)
    {
        $deskripsi = $request->get('deskripsi');
        $jenis = $request->get('jenis', 'pengeluaran');

        $category = $this->keuanganService->detectCategory($deskripsi, $jenis);

        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }

    /**
     * Dashboard Keuangan Santri untuk Karyawan (READ ONLY)
     */
    public function indexKaryawan(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'aktif');

        // Get list santri dengan saldo masing-masing
        $query = Santri::select('id', 'nis', 'nama_lengkap', 'status_santri')
            ->with(['keuanganSaldo' => function($q) {
                $q->select('santri_id', 'saldo_awal', 'total_pemasukan', 'total_pengeluaran', 'saldo_akhir', 'last_transaction_date');
            }])
            ->where('status_santri', $status);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        $santriList = $query->orderBy('nama_lengkap')->paginate(20);

        // Statistik keseluruhan
        $totalSaldoSemua = KeuanganSantriSaldo::sum('saldo_akhir');
        $totalPemasukan = KeuanganSantriSaldo::sum('total_pemasukan');
        $totalPengeluaran = KeuanganSantriSaldo::sum('total_pengeluaran');
        $jumlahSantri = Santri::where('status_santri', 'aktif')->count();

        $statistik = [
            'total_saldo_semua' => $totalSaldoSemua,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'jumlah_santri' => $jumlahSantri,
        ];

        return view('keuangan-santri.karyawan.index', compact(
            'santriList',
            'statistik',
            'search',
            'status'
        ));
    }

    /**
     * Detail transaksi per santri untuk Karyawan (READ ONLY)
     */
    public function showKaryawan(Request $request, $id)
    {
        $santri = Santri::with(['keuanganSaldo'])->findOrFail($id);
        
        $query = KeuanganSantriTransaction::where('santri_id', $id)
            ->with(['category', 'creator', 'verifier']);

        // Filter berdasarkan periode (default: hari ini)
        $periode = $request->get('periode', 'hari_ini');
        
        switch ($periode) {
            case 'hari_ini':
                $query->whereDate('tanggal_transaksi', today());
                break;
            
            case 'minggu_ini':
                $query->whereBetween('tanggal_transaksi', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            
            case 'bulan_ini':
                $query->whereMonth('tanggal_transaksi', now()->month)
                      ->whereYear('tanggal_transaksi', now()->year);
                break;
            
            case 'tahun_ini':
                $query->whereYear('tanggal_transaksi', now()->year);
                break;
            
            case 'rentang':
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $query->whereBetween('tanggal_transaksi', [
                        $request->tanggal_mulai,
                        $request->tanggal_selesai
                    ]);
                }
                break;
            
            case 'bulan':
                if ($request->filled('bulan')) {
                    $bulan = explode('-', $request->bulan);
                    $query->whereMonth('tanggal_transaksi', $bulan[1])
                          ->whereYear('tanggal_transaksi', $bulan[0]);
                }
                break;
            
            case 'tahun':
                if ($request->filled('tahun')) {
                    $query->whereYear('tanggal_transaksi', $request->tahun);
                }
                break;
            
            case 'semua':
                // Tidak ada filter tanggal
                break;
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $transactions = $query->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->all());

        return view('keuangan-santri.karyawan.show', compact('santri', 'transactions'));
    }

    /**
     * Download Resi PDF Keuangan Santri untuk Karyawan
     */
    public function downloadResiKaryawan($id)
    {
        $santri = Santri::with(['keuanganSaldo'])->findOrFail($id);
        $saldo = $santri->keuanganSaldo;
        
        // Generate PDF
        $pdf = PDF::loadView('keuangan-santri.karyawan.resi-pdf', compact('santri', 'saldo'));
        
        // Set paper size dan orientasi
        $pdf->setPaper('a4', 'portrait');
        
        // Download dengan nama file
        $filename = 'Resi_Keuangan_' . $santri->nis . '_' . $santri->nama_lengkap . '_' . date('YmdHis') . '.pdf';
        
        return $pdf->download($filename);
    }
}
