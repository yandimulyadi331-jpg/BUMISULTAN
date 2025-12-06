<?php

namespace App\Http\Controllers;

use App\Models\HistoryInventaris;
use App\Models\Inventaris;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryInventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = HistoryInventaris::with(['inventaris', 'karyawan', 'user', 'peminjaman', 'pengembalian']);

        // Filter hanya peminjaman dan pengembalian
        $query->whereIn('jenis_aktivitas', ['peminjaman', 'pengembalian']);

        // Filter by inventaris
        if ($request->filled('inventaris_id')) {
            $query->where('inventaris_id', $request->inventaris_id);
        }

        // Filter by jenis aktivitas
        if ($request->filled('jenis_aktivitas')) {
            $query->where('jenis_aktivitas', $request->jenis_aktivitas);
        }

        // Filter by karyawan
        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('inventaris', function ($q2) use ($search) {
                      $q2->where('nama_barang', 'like', "%{$search}%")
                         ->orWhere('kode_inventaris', 'like', "%{$search}%");
                  })
                  ->orWhereHas('karyawan', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        $histories = $query->latest()->paginate(20);

        // Dashboard statistics - hanya peminjaman & pengembalian
        $totalAktivitas = HistoryInventaris::whereIn('jenis_aktivitas', ['peminjaman', 'pengembalian'])->count();
        $countTambah = 0; // Tidak ada lagi input barang baru di history
        $countPinjam = HistoryInventaris::where('jenis_aktivitas', 'peminjaman')->count();
        $countKembali = HistoryInventaris::where('jenis_aktivitas', 'pengembalian')->count();

        $inventaris = Inventaris::all();
        $karyawans = Karyawan::all();
        $jenisAktivitas = [
            'input' => 'Input Barang Baru',
            'update' => 'Update Data',
            'pinjam' => 'Peminjaman',
            'kembali' => 'Pengembalian',
            'pindah_lokasi' => 'Pindah Lokasi',
            'maintenance' => 'Maintenance',
            'perbaikan' => 'Perbaikan',
            'hapus' => 'Hapus'
        ];

        return view('history-inventaris.index', compact('histories', 'inventaris', 'karyawans', 'jenisAktivitas', 'totalAktivitas', 'countTambah', 'countPinjam', 'countKembali'));
    }

    public function show(HistoryInventaris $historyInventaris)
    {
        $historyInventaris->load(['inventaris', 'karyawan', 'user', 'peminjaman', 'pengembalian']);

        return view('history-inventaris.show', compact('historyInventaris'));
    }

    // Get history by inventaris
    public function byInventaris(Inventaris $inventaris)
    {
        $histories = $inventaris->histories()
            ->with(['karyawan', 'user', 'peminjaman', 'pengembalian'])
            ->latest()
            ->paginate(20);

        return view('history-inventaris.by-inventaris', compact('inventaris', 'histories'));
    }

    // Get history by karyawan
    public function byKaryawan(Karyawan $karyawan)
    {
        $histories = HistoryInventaris::with(['inventaris', 'user', 'peminjaman', 'pengembalian'])
            ->where('karyawan_id', $karyawan->id)
            ->latest()
            ->paginate(20);

        return view('history-inventaris.by-karyawan', compact('karyawan', 'histories'));
    }

    // Dashboard/Analytics
    public function dashboard(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now();

        // Total aktivitas by jenis
        $aktivitasByJenis = HistoryInventaris::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('jenis_aktivitas, COUNT(*) as total')
            ->groupBy('jenis_aktivitas')
            ->get();

        // Top active inventaris
        $topInventaris = HistoryInventaris::whereBetween('created_at', [$startDate, $endDate])
            ->with('inventaris')
            ->selectRaw('inventaris_id, COUNT(*) as total_aktivitas')
            ->groupBy('inventaris_id')
            ->orderByDesc('total_aktivitas')
            ->limit(10)
            ->get();

        // Top active karyawan
        $topKaryawan = HistoryInventaris::whereBetween('created_at', [$startDate, $endDate])
            ->with('karyawan')
            ->whereNotNull('karyawan_id')
            ->selectRaw('karyawan_id, COUNT(*) as total_aktivitas')
            ->groupBy('karyawan_id')
            ->orderByDesc('total_aktivitas')
            ->limit(10)
            ->get();

        // Recent activities
        $recentActivities = HistoryInventaris::with(['inventaris', 'karyawan', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(20)
            ->get();

        return view('history-inventaris.dashboard', compact(
            'aktivitasByJenis',
            'topInventaris',
            'topKaryawan',
            'recentActivities',
            'startDate',
            'endDate'
        ));
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $query = HistoryInventaris::with(['inventaris', 'karyawan', 'user', 'peminjaman', 'pengembalian']);

        // Filter hanya peminjaman dan pengembalian
        $query->whereIn('jenis_aktivitas', ['peminjaman', 'pengembalian']);

        if ($request->filled('inventaris_id')) {
            $query->where('inventaris_id', $request->inventaris_id);
        }

        if ($request->filled('jenis_aktivitas')) {
            $query->where('jenis_aktivitas', $request->jenis_aktivitas);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $histories = $query->latest()->get();

        $pdf = Pdf::loadView('history-inventaris.pdf', compact('histories'));
        return $pdf->download('laporan-history-peminjaman-pengembalian-' . date('Y-m-d') . '.pdf');
    }

    public function edit($id)
    {
        $history = HistoryInventaris::with(['inventaris', 'karyawan', 'user'])->findOrFail($id);
        $inventaris = Inventaris::all();
        $karyawans = Karyawan::all();

        return view('history-inventaris.edit', compact('history', 'inventaris', 'karyawans'));
    }

    public function update(Request $request, $id)
    {
        $history = HistoryInventaris::findOrFail($id);

        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'jenis_aktivitas' => 'required|string',
            'status_sebelum' => 'nullable|string',
            'status_sesudah' => 'nullable|string',
            'lokasi_sebelum' => 'nullable|string',
            'lokasi_sesudah' => 'nullable|string',
            'jumlah' => 'nullable|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($history->foto) {
                \Storage::disk('public')->delete($history->foto);
            }
            $validated['foto'] = $request->file('foto')->store('history', 'public');
        }

        $history->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'History berhasil diupdate'
            ]);
        }

        return redirect()->route('history-inventaris.show', $history->id)
            ->with('success', 'History berhasil diupdate');
    }

    public function destroy($id)
    {
        $history = HistoryInventaris::findOrFail($id);
        
        // Delete foto if exists
        if ($history->foto) {
            \Storage::disk('public')->delete($history->foto);
        }

        $history->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'History berhasil dihapus'
            ]);
        }

        return redirect()->route('history-inventaris.index')
            ->with('success', 'History berhasil dihapus');
    }

    // Export Excel (optional)
    public function exportExcel(Request $request)
    {
        // Implement Excel export using Laravel Excel if needed
        return response()->json(['message' => 'Excel export coming soon']);
    }
}
