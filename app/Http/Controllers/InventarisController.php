<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisController extends Controller
{
    public function dashboard()
    {
        // Statistik untuk dashboard
        $totalInventaris = Inventaris::count();
        $totalPeminjaman = \App\Models\PeminjamanInventaris::count();
        $peminjamanAktif = \App\Models\PeminjamanInventaris::where('status_peminjaman', 'disetujui')
            ->whereDoesntHave('pengembalian')
            ->count();
        $totalPengembalian = \App\Models\PengembalianInventaris::count();
        $totalEvent = \App\Models\InventarisEvent::count();
        
        return view('inventaris.dashboard', compact(
            'totalInventaris',
            'totalPeminjaman',
            'peminjamanAktif',
            'totalPengembalian',
            'totalEvent'
        ));
    }

    public function index(Request $request)
    {
        $query = Inventaris::with(['cabang', 'barang', 'createdBy']);

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_inventaris', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('nomor_seri', 'like', "%{$search}%");
            });
        }

        $inventaris = $query->latest()->paginate(15);
        $cabangs = Cabang::all();
        $kategoris = Inventaris::select('kategori')->distinct()->pluck('kategori');

        return view('inventaris.index', compact('inventaris', 'cabangs', 'kategoris'));
    }

    public function create()
    {
        $barangs = Barang::all();
        $cabangs = Cabang::all();
        
        $kategoris = ['Elektronik', 'Furniture', 'Alat Tulis', 'Olahraga', 'Camping & Outdoor', 'Kendaraan', 'Peralatan Kantor', 'Peralatan Kebersihan', 'Lainnya'];

        // Untuk modal popup
        if (request()->ajax() || request()->wantsJson() || !request()->hasHeader('referer')) {
            return view('inventaris.create-modal', compact('barangs', 'cabangs', 'kategoris'));
        }

        return view('inventaris.create', compact('barangs', 'cabangs', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string',
            'barang_id' => 'nullable|exists:barangs,id',
            'deskripsi' => 'nullable|string',
            'merk' => 'nullable|string',
            'tipe_model' => 'nullable|string',
            'nomor_seri' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'harga_perolehan' => 'nullable|numeric',
            'tanggal_perolehan' => 'nullable|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi_penyimpanan' => 'nullable|string',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'spesifikasi' => 'nullable|string',
            'masa_pakai_bulan' => 'nullable|integer',
            'tanggal_kadaluarsa' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('inventaris', 'public');
        }

        if ($request->filled('spesifikasi')) {
            $validated['spesifikasi'] = json_decode($request->spesifikasi, true);
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'tersedia';

        $inventaris = Inventaris::create($validated);

        // Return JSON untuk AJAX request dari modal
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Inventaris berhasil ditambahkan dengan kode: ' . $inventaris->kode_inventaris
            ]);
        }

        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil ditambahkan dengan kode: ' . $inventaris->kode_inventaris);
    }

    public function show($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->load(['cabang', 'barang', 'peminjaman', 'histories.user']);
        $peminjamanAktif = $inventaris->peminjamanAktif()->get();
        $recentHistories = $inventaris->histories()->latest()->limit(10)->get();

        // Untuk modal popup (dipanggil via jQuery .load())
        if (request()->ajax() || request()->wantsJson() || !request()->hasHeader('referer')) {
            return view('inventaris.show-modal', compact('inventaris', 'peminjamanAktif', 'recentHistories'));
        }

        return view('inventaris.show', compact('inventaris', 'peminjamanAktif', 'recentHistories'));
    }

    /**
     * Show detailed view with tabs for units, peminjaman, pengembalian, history
     */
    public function showDetail($id)
    {
        $inventaris = Inventaris::with([
            'detailUnits.peminjamanAktif',
            'cabang',
            'barang',
            'createdBy'
        ])->findOrFail($id);

        // Stats untuk dashboard detail
        $stats = [
            'total_units' => $inventaris->getTotalUnits(),
            'tersedia' => $inventaris->jumlahTersedia(),
            'dipinjam' => $inventaris->getJumlahDipinjam(),
            'rusak' => $inventaris->getJumlahRusak(),
            'maintenance' => $inventaris->getJumlahMaintenance(),
        ];

        // Detail Units with pagination
        $detailUnits = $inventaris->detailUnits()
            ->with(['inventarisUnit', 'peminjamanAktif'])
            ->latest()
            ->paginate(20, ['*'], 'units_page');

        // Peminjaman Aktif
        $peminjamanAktif = $inventaris->peminjamanAktif()
            ->with(['detailUnit', 'disetujuiOleh', 'createdBy'])
            ->latest()
            ->get();

        // Units tersedia untuk dropdown peminjaman
        $unitsTersedia = $inventaris->detailUnitsTersedia()
            ->where('kondisi', '!=', 'rusak_berat')
            ->get();

        return view('inventaris.show-detail', compact(
            'inventaris',
            'stats',
            'detailUnits',
            'peminjamanAktif',
            'unitsTersedia'
        ));
    }

    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $barangs = Barang::all();
        $cabangs = Cabang::all();
        $kategoris = ['Elektronik', 'Furniture', 'Alat Tulis', 'Olahraga', 'Camping & Outdoor', 'Kendaraan', 'Peralatan Kantor', 'Peralatan Kebersihan', 'Lainnya'];

        // Untuk modal popup (dipanggil via jQuery .load())
        if (request()->ajax() || request()->wantsJson() || !request()->hasHeader('referer')) {
            return view('inventaris.edit-modal', compact('inventaris', 'barangs', 'cabangs', 'kategoris'));
        }

        return view('inventaris.edit', compact('inventaris', 'barangs', 'cabangs', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);
        
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string',
            'barang_id' => 'nullable|exists:barangs,id',
            'deskripsi' => 'nullable|string',
            'merk' => 'nullable|string',
            'tipe_model' => 'nullable|string',
            'nomor_seri' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'harga_perolehan' => 'nullable|numeric',
            'tanggal_perolehan' => 'nullable|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:tersedia,dipinjam,maintenance,rusak,hilang',
            'lokasi_penyimpanan' => 'nullable|string',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'spesifikasi' => 'nullable|string',
            'masa_pakai_bulan' => 'nullable|integer',
            'tanggal_kadaluarsa' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            if ($inventaris->foto) {
                Storage::disk('public')->delete($inventaris->foto);
            }
            $validated['foto'] = $request->file('foto')->store('inventaris', 'public');
        }

        if ($request->filled('spesifikasi')) {
            $validated['spesifikasi'] = json_decode($request->spesifikasi, true);
        }

        $validated['updated_by'] = auth()->id();
        $inventaris->update($validated);

        // Return JSON untuk AJAX request dari modal
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Inventaris berhasil diupdate'
            ]);
        }

        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil diupdate');
    }

    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        
        if ($inventaris->peminjamanAktif()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus inventaris yang sedang dipinjam');
        }

        if ($inventaris->foto) {
            Storage::disk('public')->delete($inventaris->foto);
        }

        $kode = $inventaris->kode_inventaris;
        $inventaris->delete();

        return redirect()->route('inventaris.index')->with('success', 'Inventaris ' . $kode . ' berhasil dihapus');
    }

    public function importFromBarang(Request $request)
    {
        $barangIds = $request->barang_ids;
        
        if (empty($barangIds)) {
            return redirect()->back()->with('error', 'Pilih barang yang ingin diimport');
        }

        $barangs = Barang::whereIn('id', $barangIds)->get();
        $imported = 0;

        foreach ($barangs as $barang) {
            Inventaris::create([
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori ?? 'Lainnya',
                'barang_id' => $barang->id,
                'deskripsi' => $barang->keterangan,
                'jumlah' => $barang->jumlah ?? 1,
                'satuan' => 'unit',
                'kondisi' => 'baik',
                'status' => 'tersedia',
                'lokasi_penyimpanan' => $barang->lokasi ?? null,
                'cabang_id' => $barang->cabang_id ?? auth()->user()->cabang_id,
                'foto' => $barang->foto ?? null,
                'created_by' => auth()->id(),
            ]);
            $imported++;
        }

        return redirect()->route('inventaris.index')->with('success', $imported . ' barang berhasil diimport ke inventaris');
    }

    public function exportPdf(Request $request)
    {
        $query = Inventaris::with(['cabang', 'barang']);

        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('cabang_id')) $query->where('cabang_id', $request->cabang_id);

        $inventaris = $query->get();
        $totalNilai = $inventaris->sum('harga_perolehan');

        $pdf = Pdf::loadView('inventaris.pdf', compact('inventaris', 'totalNilai'));
        return $pdf->download('laporan-inventaris-' . date('Y-m-d') . '.pdf');
    }

    public function exportAktivitasPdf(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now();

        $inventaris = Inventaris::with([
            'histories' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            },
            'histories.user',
            'histories.karyawan'
        ])->get();

        $pdf = Pdf::loadView('inventaris.aktivitas-pdf', compact('inventaris', 'startDate', 'endDate'));
        return $pdf->download('laporan-aktivitas-inventaris-' . date('Y-m-d') . '.pdf');
    }

    public function history(Request $request)
    {
        $inventarisId = $request->inventaris_id;
        
        $query = \App\Models\HistoryInventaris::with(['inventaris.barang', 'user', 'karyawan', 'peminjaman', 'pengembalian'])
            ->orderBy('created_at', 'desc');

        // Filter by inventaris if specified
        if ($inventarisId) {
            $query->where('inventaris_id', $inventarisId);
            $inventaris = Inventaris::find($inventarisId);
        } else {
            $inventaris = null;
        }

        // Filter by jenis aktivitas
        if ($request->filled('jenis_aktivitas')) {
            $query->where('jenis_aktivitas', $request->jenis_aktivitas);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deskripsi', 'like', '%' . $search . '%')
                  ->orWhereHas('inventaris', function($q2) use ($search) {
                      $q2->where('nama_barang', 'like', '%' . $search . '%')
                         ->orWhere('kode_inventaris', 'like', '%' . $search . '%');
                  });
            });
        }

        $histories = $query->paginate(20);

        // For AJAX modal requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('inventaris.history-modal', compact('histories', 'inventaris'));
        }

        return view('inventaris.history', compact('histories', 'inventaris'));
    }

    public function historyDetail($id)
    {
        $history = \App\Models\HistoryInventaris::with(['inventaris.barang', 'user', 'karyawan', 'peminjaman', 'pengembalian'])
            ->findOrFail($id);

        return view('inventaris.history-detail-modal', compact('history'));
    }

    public function getBarangsForImport()
    {
        $barangs = Barang::whereNotIn('id', function ($query) {
            $query->select('barang_id')->from('inventaris')->whereNotNull('barang_id');
        })->get();

        return view('inventaris.import-barang', compact('barangs'));
    }
}
