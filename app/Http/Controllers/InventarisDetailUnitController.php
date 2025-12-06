<?php

namespace App\Http\Controllers;

use App\Models\InventarisDetailUnit;
use App\Models\Inventaris;
use App\Models\InventarisUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InventarisDetailUnitController extends Controller
{
    /**
     * Display a listing of units for specific inventaris
     */
    public function index($inventarisId)
    {
        $inventaris = Inventaris::findOrFail($inventarisId);
        $units = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->with(['inventarisUnit', 'peminjamanAktif'])
            ->latest()
            ->paginate(20);

        return view('inventaris.units.index', compact('inventaris', 'units'));
    }

    /**
     * Show the form for creating a new unit
     */
    public function create($inventarisId)
    {
        $inventaris = Inventaris::findOrFail($inventarisId);
        
        if (request()->ajax()) {
            return view('inventaris.units.create-modal', compact('inventaris'));
        }
        
        return view('inventaris.units.create', compact('inventaris'));
    }

    /**
     * Store newly created units
     */
    public function store(Request $request, $inventarisId)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1|max:100',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi_saat_ini' => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'catatan_kondisi' => 'nullable|string',
            'nomor_seri_unit' => 'nullable|string|max:100',
            'foto_unit' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Optional batch info
            'create_batch' => 'nullable|boolean',
            'batch_code' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
        ]);

        $inventaris = Inventaris::findOrFail($inventarisId);
        $jumlah = $validated['jumlah'];

        DB::beginTransaction();
        try {
            // Create batch if requested
            $inventarisUnitId = null;
            if ($request->create_batch) {
                $batch = InventarisUnit::create([
                    'inventaris_id' => $inventaris->id,
                    'batch_code' => $validated['batch_code'] ?? null,
                    'tanggal_perolehan' => $validated['tanggal_perolehan'] ?? now(),
                    'supplier' => $validated['supplier'] ?? null,
                    'harga_perolehan_per_unit' => $validated['harga_perolehan'] ?? null,
                    'jumlah_unit_dalam_batch' => $jumlah,
                    'lokasi_penyimpanan' => $validated['lokasi_saat_ini'] ?? $inventaris->lokasi_penyimpanan,
                    'created_by' => auth()->id(),
                ]);
                $inventarisUnitId = $batch->id;
            }

            // Handle foto upload
            $fotoPath = null;
            if ($request->hasFile('foto_unit')) {
                $fotoPath = $request->file('foto_unit')->store('inventaris/units', 'public');
            }

            // Create multiple units
            $units = [];
            for ($i = 0; $i < $jumlah; $i++) {
                $unit = InventarisDetailUnit::create([
                    'inventaris_id' => $inventaris->id,
                    'inventaris_unit_id' => $inventarisUnitId,
                    'kondisi' => $validated['kondisi'],
                    'status' => 'tersedia',
                    'lokasi_saat_ini' => $validated['lokasi_saat_ini'] ?? $inventaris->lokasi_penyimpanan,
                    'tanggal_perolehan' => $validated['tanggal_perolehan'] ?? now(),
                    'harga_perolehan' => $validated['harga_perolehan'] ?? $inventaris->harga_perolehan,
                    'catatan_kondisi' => $validated['catatan_kondisi'] ?? null,
                    'nomor_seri_unit' => $validated['nomor_seri_unit'] ?? null,
                    'foto_unit' => $fotoPath,
                    'created_by' => auth()->id(),
                ]);
                
                $units[] = $unit;
            }

            // Update master inventaris
            $inventaris->update([
                'tracking_per_unit' => true,
                'jumlah_unit' => $inventaris->detailUnits()->count(),
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "$jumlah unit berhasil ditambahkan",
                    'units' => $units,
                    'total_units' => $inventaris->detailUnits()->count(),
                ]);
            }

            return redirect()->route('inventaris.detail', $inventaris->id)
                ->with('success', "$jumlah unit berhasil ditambahkan ke {$inventaris->nama_barang}");

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan unit: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Gagal menambahkan unit: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing a unit
     */
    public function edit($inventarisId, $id)
    {
        $unit = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->findOrFail($id);
        $inventaris = $unit->inventaris;

        if (request()->ajax()) {
            return view('inventaris.units.edit-modal', compact('unit', 'inventaris'));
        }

        return view('inventaris.units.edit', compact('unit', 'inventaris'));
    }

    /**
     * Update a unit
     */
    public function update(Request $request, $inventarisId, $id)
    {
        $unit = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->findOrFail($id);

        $validated = $request->validate([
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:tersedia,dipinjam,maintenance,rusak,hilang',
            'lokasi_saat_ini' => 'nullable|string|max:255',
            'catatan_kondisi' => 'nullable|string',
            'nomor_seri_unit' => 'nullable|string|max:100',
            'foto_unit' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'terakhir_maintenance' => 'nullable|date',
        ]);

        // Check if unit is dipinjam and status being changed
        if ($unit->status === 'dipinjam' && $validated['status'] !== 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Unit sedang dipinjam. Gunakan fitur pengembalian untuk mengubah status.'
            ], 422);
        }

        // Handle foto upload
        if ($request->hasFile('foto_unit')) {
            if ($unit->foto_unit) {
                Storage::disk('public')->delete($unit->foto_unit);
            }
            $validated['foto_unit'] = $request->file('foto_unit')->store('inventaris/units', 'public');
        }

        $validated['updated_by'] = auth()->id();
        $unit->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit berhasil diupdate',
                'unit' => $unit->fresh()
            ]);
        }

        return redirect()->route('inventaris.detail', $inventarisId)
            ->with('success', "Unit {$unit->kode_unit} berhasil diupdate");
    }

    /**
     * Remove a unit
     */
    public function destroy($inventarisId, $id)
    {
        $unit = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->findOrFail($id);

        // Check if unit is dipinjam
        if ($unit->status === 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Unit sedang dipinjam, tidak dapat dihapus'
            ], 422);
        }

        $kodeUnit = $unit->kode_unit;
        
        DB::beginTransaction();
        try {
            // Log history sebelum hapus
            $unit->logHistory('hapus', 'Unit dihapus dari sistem oleh ' . auth()->user()->name);
            
            // Hapus foto jika ada
            if ($unit->foto_unit) {
                Storage::disk('public')->delete($unit->foto_unit);
            }
            
            $unit->delete();

            // Update jumlah_unit di master
            $inventaris = Inventaris::find($inventarisId);
            $inventaris->update([
                'jumlah_unit' => $inventaris->detailUnits()->count()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Unit $kodeUnit berhasil dihapus"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus unit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show history for a specific unit
     */
    public function showHistory(Request $request, $inventarisId, $id)
    {
        $unit = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->with(['inventaris', 'histories.user', 'histories.peminjaman', 'histories.pengembalian'])
            ->findOrFail($id);

        // Filter hanya peminjaman dan pengembalian
        $query = $unit->histories()
            ->with(['user', 'peminjaman', 'pengembalian'])
            ->whereIn('jenis_aktivitas', ['pinjam', 'kembali']);
        
        // Filter by week
        if ($request->filled('week')) {
            $startOfWeek = \Carbon\Carbon::parse($request->week)->startOfWeek();
            $endOfWeek = \Carbon\Carbon::parse($request->week)->endOfWeek();
            $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        }
        
        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('peminjaman', function($q2) use ($search) {
                      $q2->where('nama_peminjam', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by jenis aktivitas
        if ($request->filled('jenis')) {
            $query->where('jenis_aktivitas', $request->jenis);
        }
        
        $histories = $query->latest('created_at')->get();

        // Return view langsung tanpa popup
        return view('inventaris.units.history', compact('unit', 'histories'));
    }

    /**
     * Generate PDF history unit
     */
    public function historyPdf(Request $request, $inventarisId, $id)
    {
        $unit = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->with(['inventaris', 'histories.user', 'histories.peminjaman', 'histories.pengembalian'])
            ->findOrFail($id);

        // Filter hanya peminjaman dan pengembalian
        $query = $unit->histories()
            ->with(['user', 'peminjaman', 'pengembalian'])
            ->whereIn('jenis_aktivitas', ['pinjam', 'kembali']);
        
        // Filter by week
        $periodText = 'Semua Periode';
        if ($request->filled('week')) {
            $startOfWeek = \Carbon\Carbon::parse($request->week)->startOfWeek();
            $endOfWeek = \Carbon\Carbon::parse($request->week)->endOfWeek();
            $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            $periodText = $startOfWeek->format('d M Y') . ' - ' . $endOfWeek->format('d M Y');
        }
        
        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('peminjaman', function($q2) use ($search) {
                      $q2->where('nama_peminjam', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by jenis aktivitas
        if ($request->filled('jenis')) {
            $query->where('jenis_aktivitas', $request->jenis);
        }
        
        $histories = $query->latest('created_at')->get();

        $pdf = \PDF::loadView('inventaris.units.history-pdf', compact('unit', 'histories', 'periodText'));
        
        $filename = 'History_' . $unit->kode_unit . '_' . now()->format('Ymd_His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Show form peminjaman for specific unit
     */
    public function showPeminjamanForm($inventarisId, $id)
    {
        $unit = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->with('inventaris')
            ->findOrFail($id);

        return view('inventaris.units.peminjaman-modal', compact('unit'));
    }

    /**
     * Process pengembalian unit langsung
     */
    public function kembalikanUnit(Request $request, $inventarisId, $id)
    {
        try {
            // Validasi input dari form
            $validated = $request->validate([
                'tanggal_kembali' => 'required|date',
                'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
                'catatan' => 'nullable|string',
                'denda' => 'nullable|numeric|min:0',
            ]);
            
            $unit = InventarisDetailUnit::where('inventaris_id', $inventarisId)
                ->with(['peminjamanAktif'])
                ->findOrFail($id);

            if ($unit->status !== 'dipinjam') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit ini tidak sedang dipinjam'
                ], 400);
            }

            // Get peminjaman aktif dengan semua data
            $peminjaman = $unit->peminjamanAktif;
            
            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peminjaman tidak ditemukan'
                ], 400);
            }
            
            // Reload peminjaman untuk memastikan semua field terisi
            $peminjaman = \App\Models\PeminjamanInventaris::find($peminjaman->id);
            
            if (!$peminjaman || empty($peminjaman->nama_peminjam)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peminjaman tidak lengkap. Nama peminjam tidak ditemukan.'
                ], 400);
            }

            DB::beginTransaction();

            // Update status unit dan kondisi
            $unit->update([
                'status' => 'tersedia',
                'kondisi' => $validated['kondisi'],
                'dipinjam_oleh' => null,
                'tanggal_pinjam' => null,
                'peminjaman_inventaris_id' => null,
                'catatan_kondisi' => $validated['catatan'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            // Buat record pengembalian dengan data lengkap
            $pengembalian = \App\Models\PengembalianInventaris::create([
                'peminjaman_inventaris_id' => $peminjaman->id,
                'inventaris_id' => $unit->inventaris_id,
                'inventaris_detail_unit_id' => $unit->id,
                'tanggal_pengembalian' => $validated['tanggal_kembali'],
                'kondisi_barang' => $validated['kondisi'],
                'jumlah_kembali' => 1,
                'catatan_pengembalian' => $validated['catatan'] ?? 'Pengembalian unit dari detail unit',
                'denda' => $validated['denda'] ?? 0,
                'status_peminjaman' => 'dikembalikan',
                'diterima_oleh' => auth()->id(),
            ]);

            // Update status peminjaman
            $peminjaman->update([
                'status_peminjaman' => 'dikembalikan',
                'tanggal_kembali' => $validated['tanggal_kembali'],
            ]);
            
            // Log history pengembalian dengan fallback nama
            $namaPeminjam = 'Unknown';
            if ($peminjaman && !empty($peminjaman->nama_peminjam)) {
                $namaPeminjam = $peminjaman->nama_peminjam;
            }
            
            $unit->logHistory(
                'kembali', 
                "Unit dikembalikan oleh " . $namaPeminjam . " - Kondisi: " . ucfirst($validated['kondisi']), 
                $pengembalian->id, 
                'pengembalian'
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Unit berhasil dikembalikan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available units for peminjaman dropdown
     */
    public function getUnitsTersedia($inventarisId)
    {
        $units = InventarisDetailUnit::where('inventaris_id', $inventarisId)
            ->where('status', 'tersedia')
            ->where('kondisi', '!=', 'rusak_berat')
            ->select('id', 'kode_unit', 'kondisi', 'lokasi_saat_ini')
            ->get();

        return response()->json([
            'success' => true,
            'units' => $units
        ]);
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request, $inventarisId)
    {
        $validated = $request->validate([
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:inventaris_detail_units,id',
            'status' => 'required|in:tersedia,maintenance,rusak,hilang',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $units = InventarisDetailUnit::where('inventaris_id', $inventarisId)
                ->whereIn('id', $validated['unit_ids'])
                ->where('status', '!=', 'dipinjam')
                ->get();

            foreach ($units as $unit) {
                $unit->update([
                    'status' => $validated['status'],
                    'updated_by' => auth()->id(),
                ]);
                
                $unit->logHistory(
                    'update_kondisi', 
                    ($validated['keterangan'] ?? 'Bulk update status ke: ' . $validated['status'])
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($units) . ' unit berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export units to Excel
     */
    public function export($inventarisId)
    {
        $inventaris = Inventaris::findOrFail($inventarisId);
        $units = $inventaris->detailUnits()
            ->with(['inventarisUnit', 'peminjamanAktif'])
            ->get();

        // TODO: Implement Excel export
        // return Excel::download(new UnitsExport($units), "units-{$inventaris->kode_inventaris}.xlsx");
        
        return response()->json([
            'message' => 'Export feature coming soon'
        ]);
    }
}
