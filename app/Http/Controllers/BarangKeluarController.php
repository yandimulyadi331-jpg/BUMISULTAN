<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarHistory;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BarangKeluar::with(['creator', 'updater', 'pengambil']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis barang
        if ($request->filled('jenis_barang')) {
            $query->where('jenis_barang', $request->jenis_barang);
        }

        // Filter berdasarkan vendor
        if ($request->filled('vendor')) {
            $query->where('nama_vendor', 'like', '%' . $request->vendor . '%');
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_keluar', [
                $request->tanggal_dari . ' 00:00:00',
                $request->tanggal_sampai . ' 23:59:59'
            ]);
        }

        // Filter prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'like', '%' . $search . '%')
                    ->orWhere('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('pemilik_barang', 'like', '%' . $search . '%')
                    ->orWhere('nama_vendor', 'like', '%' . $search . '%');
            });
        }

        $barangKeluar = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get unique jenis barang dan vendor untuk filter
        $jenisBarangList = BarangKeluar::distinct()->pluck('jenis_barang');
        $vendorList = BarangKeluar::distinct()->pluck('nama_vendor');

        // Statistics
        $stats = [
            'total' => BarangKeluar::count(),
            'pending' => BarangKeluar::where('status', 'pending')->count(),
            'proses' => BarangKeluar::whereIn('status', ['dikirim', 'proses'])->count(),
            'selesai' => BarangKeluar::where('status', 'selesai_vendor')->count(),
            'terlambat' => BarangKeluar::terlambat()->count(),
        ];

        return view('barang-keluar.index', compact('barangKeluar', 'jenisBarangList', 'vendorList', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departemens = Departemen::all();
        return view('barang-keluar.create', compact('departemens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_barang' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'nullable|string|max:50',
            'pemilik_barang' => 'required|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'no_telp_pemilik' => 'nullable|string|max:20',
            'nama_vendor' => 'required|string|max:255',
            'alamat_vendor' => 'nullable|string',
            'no_telp_vendor' => 'nullable|string|max:20',
            'pic_vendor' => 'nullable|string|max:255',
            'tanggal_keluar' => 'required|date',
            'estimasi_kembali' => 'nullable|date|after_or_equal:tanggal_keluar',
            'estimasi_biaya' => 'nullable|numeric|min:0',
            'kondisi_keluar' => 'required|in:baik,rusak_ringan,rusak_berat',
            'prioritas' => 'required|in:rendah,normal,tinggi,urgent',
            'catatan_keluar' => 'nullable|string',
            'foto_sebelum' => 'nullable|array',
            'foto_sebelum.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle foto sebelum
            $fotoSebelum = [];
            if ($request->hasFile('foto_sebelum')) {
                foreach ($request->file('foto_sebelum') as $foto) {
                    $filename = 'barang_keluar_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('documents', $filename, 'public');
                    $fotoSebelum[] = $path;
                }
            }

            $validated['foto_sebelum'] = $fotoSebelum;
            $validated['status'] = 'pending';

            $barangKeluar = BarangKeluar::create($validated);

            // Log history
            BarangKeluarHistory::create([
                'barang_keluar_id' => $barangKeluar->id,
                'status_dari' => null,
                'status_ke' => 'pending',
                'catatan' => 'Data barang keluar dibuat',
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('barang-keluar.index')
                ->with('success', 'Data barang keluar berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barangKeluar = BarangKeluar::with(['creator', 'updater', 'pengambil', 'histories.user'])
            ->findOrFail($id);

        return view('barang-keluar.show', compact('barangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $departemens = Departemen::all();
        
        return view('barang-keluar.edit', compact('barangKeluar', 'departemens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        $validated = $request->validate([
            'jenis_barang' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'nullable|string|max:50',
            'pemilik_barang' => 'required|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'no_telp_pemilik' => 'nullable|string|max:20',
            'nama_vendor' => 'required|string|max:255',
            'alamat_vendor' => 'nullable|string',
            'no_telp_vendor' => 'nullable|string|max:20',
            'pic_vendor' => 'nullable|string|max:255',
            'tanggal_keluar' => 'required|date',
            'estimasi_kembali' => 'nullable|date|after_or_equal:tanggal_keluar',
            'estimasi_biaya' => 'nullable|numeric|min:0',
            'biaya_aktual' => 'nullable|numeric|min:0',
            'kondisi_keluar' => 'required|in:baik,rusak_ringan,rusak_berat',
            'kondisi_kembali' => 'nullable|in:baik,rusak_ringan,rusak_berat,hilang',
            'prioritas' => 'required|in:rendah,normal,tinggi,urgent',
            'catatan_keluar' => 'nullable|string',
            'catatan_kembali' => 'nullable|string',
            'catatan_vendor' => 'nullable|string',
            'rating_vendor' => 'nullable|integer|min:1|max:5',
            'review_vendor' => 'nullable|string',
            'foto_sebelum' => 'nullable|array',
            'foto_sebelum.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_sesudah' => 'nullable|array',
            'foto_sesudah.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_nota' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle foto sebelum (append)
            if ($request->hasFile('foto_sebelum')) {
                $fotoSebelum = $barangKeluar->foto_sebelum ?? [];
                foreach ($request->file('foto_sebelum') as $foto) {
                    $filename = 'barang_keluar_before_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('documents', $filename, 'public');
                    $fotoSebelum[] = $path;
                }
                $validated['foto_sebelum'] = $fotoSebelum;
            }

            // Handle foto sesudah (append)
            if ($request->hasFile('foto_sesudah')) {
                $fotoSesudah = $barangKeluar->foto_sesudah ?? [];
                foreach ($request->file('foto_sesudah') as $foto) {
                    $filename = 'barang_keluar_after_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('documents', $filename, 'public');
                    $fotoSesudah[] = $path;
                }
                $validated['foto_sesudah'] = $fotoSesudah;
            }

            // Handle foto nota
            if ($request->hasFile('foto_nota')) {
                // Delete old foto nota
                if ($barangKeluar->foto_nota) {
                    Storage::disk('public')->delete($barangKeluar->foto_nota);
                }
                
                $filename = 'barang_keluar_nota_' . time() . '.' . $request->file('foto_nota')->getClientOriginalExtension();
                $validated['foto_nota'] = $request->file('foto_nota')->storeAs('documents', $filename, 'public');
            }

            $barangKeluar->update($validated);

            DB::commit();

            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('success', 'Data barang keluar berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status barang keluar
     */
    public function updateStatus(Request $request, $id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,dikirim,proses,selesai_vendor,diambil,batal',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $statusLama = $barangKeluar->status;

            // Handle foto
            $foto = [];
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $filename = 'barang_keluar_history_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('documents', $filename, 'public');
                    $foto[] = $path;
                }
            }

            // Update status
            $barangKeluar->updateStatus($validated['status'], $validated['catatan'] ?? null);

            // Jika ada foto, update history terakhir
            if (!empty($foto)) {
                $lastHistory = $barangKeluar->histories()->latest()->first();
                if ($lastHistory) {
                    $lastHistory->foto = $foto;
                    $lastHistory->save();
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status berhasil diupdate dari ' . $statusLama . ' ke ' . $validated['status']);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete foto sebelum
            if ($barangKeluar->foto_sebelum) {
                foreach ($barangKeluar->foto_sebelum as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }

            // Delete foto sesudah
            if ($barangKeluar->foto_sesudah) {
                foreach ($barangKeluar->foto_sesudah as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }

            // Delete foto nota
            if ($barangKeluar->foto_nota) {
                Storage::disk('public')->delete($barangKeluar->foto_nota);
            }

            // Delete history photos
            foreach ($barangKeluar->histories as $history) {
                if ($history->foto) {
                    foreach ($history->foto as $foto) {
                        Storage::disk('public')->delete($foto);
                    }
                }
            }

            $barangKeluar->delete();

            DB::commit();

            return redirect()->route('barang-keluar.index')
                ->with('success', 'Data barang keluar berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete foto
     */
    public function deleteFoto(Request $request, $id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        $validated = $request->validate([
            'tipe_foto' => 'required|in:foto_sebelum,foto_sesudah',
            'index' => 'required|integer',
        ]);

        try {
            if ($validated['tipe_foto'] === 'foto_sebelum') {
                $foto = $barangKeluar->foto_sebelum;
                if (isset($foto[$validated['index']])) {
                    Storage::disk('public')->delete($foto[$validated['index']]);
                    unset($foto[$validated['index']]);
                    $barangKeluar->foto_sebelum = array_values($foto);
                }
            } else {
                $foto = $barangKeluar->foto_sesudah;
                if (isset($foto[$validated['index']])) {
                    Storage::disk('public')->delete($foto[$validated['index']]);
                    unset($foto[$validated['index']]);
                    $barangKeluar->foto_sesudah = array_values($foto);
                }
            }

            $barangKeluar->save();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Export PDF Report
     */
    public function exportPdf(Request $request)
    {
        $query = BarangKeluar::with(['creator', 'updater']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_barang')) {
            $query->where('jenis_barang', $request->jenis_barang);
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_keluar', [
                $request->tanggal_dari . ' 00:00:00',
                $request->tanggal_sampai . ' 23:59:59'
            ]);
        }

        $barangKeluar = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('barang-keluar.pdf', compact('barangKeluar'));

        return $pdf->download('laporan-barang-keluar-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Dashboard/Statistics
     */
    public function dashboard()
    {
        $stats = [
            'total' => BarangKeluar::count(),
            'belum_kembali' => BarangKeluar::belumKembali()->count(),
            'terlambat' => BarangKeluar::terlambat()->count(),
            'bulan_ini' => BarangKeluar::whereMonth('tanggal_keluar', now()->month)
                ->whereYear('tanggal_keluar', now()->year)
                ->count(),
        ];

        // Top vendors
        $topVendors = BarangKeluar::select('nama_vendor', DB::raw('count(*) as total'))
            ->groupBy('nama_vendor')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Recent items
        $recentItems = BarangKeluar::with(['creator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Items by status
        $byStatus = BarangKeluar::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        return view('barang-keluar.dashboard', compact('stats', 'topVendors', 'recentItems', 'byStatus'));
    }
}
