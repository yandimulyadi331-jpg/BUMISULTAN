<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanInventaris;
use App\Models\Inventaris;
use App\Models\Karyawan;
use App\Models\InventarisEvent;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanInventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanInventaris::with(['inventaris', 'karyawan', 'event', 'disetujuiOleh']);

        if ($request->filled('status')) {
            $query->where('status_peminjaman', $request->status);
        }

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        if ($request->filled('inventaris_id')) {
            $query->where('inventaris_id', $request->inventaris_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('karyawan', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%");
                  })
                  ->orWhereHas('inventaris', function ($q2) use ($search) {
                      $q2->where('nama_barang', 'like', "%{$search}%");
                  });
            });
        }

        $peminjaman = $query->latest()->paginate(15);
        $karyawans = Karyawan::all();

        return view('peminjaman-inventaris.index', compact('peminjaman', 'karyawans'));
    }

    public function create(Request $request)
    {
        $inventaris = Inventaris::where('status', 'tersedia')->get();
        $karyawans = Karyawan::all();
        $events = InventarisEvent::aktif()->get();

        // Untuk modal popup
        if ($request->ajax() || $request->wantsJson() || !$request->hasHeader('referer')) {
            return view('peminjaman-inventaris.create-modal', compact('inventaris', 'karyawans', 'events'));
        }

        return view('peminjaman-inventaris.create', compact('inventaris', 'karyawans', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'inventaris_detail_unit_id' => 'nullable|exists:inventaris_detail_units,id',
            'nama_peminjam' => 'required|string|max:255',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'required|string',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd_peminjam' => 'required|string',
            'ttd_petugas' => 'nullable|string',
            'inventaris_event_id' => 'nullable|exists:inventaris_events,id',
            'catatan_peminjaman' => 'nullable|string',
        ], [
            'nama_peminjam.required' => 'Nama peminjam wajib diisi',
            'jumlah_pinjam.required' => 'Jumlah pinjam wajib diisi',
            'tanggal_kembali_rencana.after' => 'Tanggal kembali harus setelah tanggal pinjam',
            'inventaris_detail_unit_id.exists' => 'Unit yang dipilih tidak valid',
            'ttd_peminjam.required' => 'Tanda tangan peminjam wajib diisi',
        ]);

        // Check ketersediaan
        $inventaris = Inventaris::find($validated['inventaris_id']);
        
        // Jika ada unit yang dipilih (dari detail unit)
        if ($request->filled('inventaris_detail_unit_id')) {
            $detailUnit = \App\Models\InventarisDetailUnit::find($validated['inventaris_detail_unit_id']);
            
            if (!$detailUnit || $detailUnit->inventaris_id != $inventaris->id) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unit yang dipilih tidak valid'
                    ], 422);
                }
                
                return redirect()->back()
                    ->with('error', 'Unit yang dipilih tidak valid')
                    ->withInput();
            }
            
            if ($detailUnit->status != 'tersedia') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unit ' . $detailUnit->kode_unit . ' tidak tersedia untuk dipinjam. Status: ' . $detailUnit->status
                    ], 422);
                }
                
                return redirect()->back()
                    ->with('error', 'Unit ' . $detailUnit->kode_unit . ' tidak tersedia untuk dipinjam. Status: ' . $detailUnit->status)
                    ->withInput();
            }
        } else {
            // Mode non-tracking atau tidak ada unit dipilih
            if ($inventaris->jumlahTersedia() < $validated['jumlah_pinjam']) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah inventaris tersedia tidak mencukupi. Tersedia: ' . $inventaris->jumlahTersedia()
                    ], 422);
                }
                
                return redirect()->back()
                    ->with('error', 'Jumlah inventaris tersedia tidak mencukupi. Tersedia: ' . $inventaris->jumlahTersedia())
                    ->withInput();
            }
        }

        if ($request->hasFile('foto_barang')) {
            $validated['foto_barang'] = $request->file('foto_barang')->store('peminjaman', 'public');
        }

        // Save signature images
        if (!empty($validated['ttd_peminjam'])) {
            $ttdPeminjam = $validated['ttd_peminjam'];
            if (preg_match('/^data:image\/(\w+);base64,/', $ttdPeminjam, $type)) {
                $ttdPeminjam = substr($ttdPeminjam, strpos($ttdPeminjam, ',') + 1);
                $type = strtolower($type[1]);
                $ttdPeminjam = base64_decode($ttdPeminjam);
                
                $filename = 'ttd_peminjam_' . time() . '_' . uniqid() . '.' . $type;
                \Storage::disk('public')->put('signatures/' . $filename, $ttdPeminjam);
                $validated['ttd_peminjam'] = 'signatures/' . $filename;
            }
        }

        if (!empty($validated['ttd_petugas'])) {
            $ttdPetugas = $validated['ttd_petugas'];
            if (preg_match('/^data:image\/(\w+);base64,/', $ttdPetugas, $type)) {
                $ttdPetugas = substr($ttdPetugas, strpos($ttdPetugas, ',') + 1);
                $type = strtolower($type[1]);
                $ttdPetugas = base64_decode($ttdPetugas);
                
                $filename = 'ttd_petugas_' . time() . '_' . uniqid() . '.' . $type;
                \Storage::disk('public')->put('signatures/' . $filename, $ttdPetugas);
                $validated['ttd_petugas'] = 'signatures/' . $filename;
            }
        }

        $validated['status_peminjaman'] = 'disetujui'; // Auto approve untuk workflow sederhana
        $validated['disetujui_oleh'] = auth()->id();
        $validated['created_by'] = auth()->id();

        // Debug: Pastikan nama_peminjam ada
        if (empty($validated['nama_peminjam'])) {
            return response()->json([
                'success' => false,
                'message' => 'Nama peminjam tidak boleh kosong'
            ], 422);
        }

        $peminjaman = PeminjamanInventaris::create($validated);
        
        // Reload untuk memastikan semua data terisi
        $peminjaman = $peminjaman->fresh();

        // Jika ada unit yang dipilih, update status unit
        if (isset($detailUnit)) {
            $detailUnit->setDipinjam($peminjaman);
        } else {
            // Update status inventaris berdasarkan stok tersisa (mode lama)
            $stokTersisa = $inventaris->jumlahTersedia();
            if ($stokTersisa <= 0) {
                // Semua stok sudah dipinjam
                $inventaris->update(['status' => 'dipinjam']);
            } else if ($stokTersisa < $inventaris->jumlah) {
                // Sebagian stok dipinjam, tetap bisa dipinjam lagi
                $inventaris->update(['status' => 'dipinjam']); // Status tetap dipinjam tapi masih bisa dipinjam sebagian
            }
            // Jika stok masih penuh, status tetap 'tersedia' (tidak perlu update)
        }

        // Return JSON untuk AJAX request dari modal
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diproses dengan kode: ' . $peminjaman->kode_peminjaman,
                'data' => $peminjaman
            ]);
        }

        return redirect()->route('peminjaman-inventaris.show', $peminjaman)
            ->with('success', 'Peminjaman berhasil diproses dengan kode: ' . $peminjaman->kode_peminjaman);
    }

    public function show(PeminjamanInventaris $peminjamanInventaris)
    {
        // Redirect ke index karena detail tidak diperlukan untuk sistem multi-unit
        return redirect()->route('peminjaman-inventaris.index')
            ->with('info', 'Gunakan halaman Detail Inventaris untuk melihat informasi peminjaman lengkap');
    }

    public function edit(PeminjamanInventaris $peminjamanInventaris)
    {
        if ($peminjamanInventaris->status !== 'menunggu_approval') {
            return redirect()->back()->with('error', 'Hanya peminjaman dengan status menunggu approval yang dapat diedit');
        }

        $inventaris = Inventaris::where('status', 'tersedia')->get();
        $karyawans = Karyawan::all();
        $events = InventarisEvent::aktif()->get();

        return view('peminjaman-inventaris.edit', compact('peminjamanInventaris', 'inventaris', 'karyawans', 'events'));
    }

    public function update(Request $request, PeminjamanInventaris $peminjamanInventaris)
    {
        if ($peminjamanInventaris->status !== 'menunggu_approval') {
            return redirect()->back()->with('error', 'Hanya peminjaman dengan status menunggu approval yang dapat diedit');
        }

        $validated = $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'karyawan_id' => 'required|exists:karyawans,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'required|string',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd_peminjam' => 'nullable|string',
            'catatan_peminjaman' => 'nullable|string',
        ]);

        if ($request->hasFile('foto_barang')) {
            $validated['foto_barang'] = $request->file('foto_barang')->store('peminjaman', 'public');
        }

        $peminjamanInventaris->update($validated);

        return redirect()->route('peminjaman-inventaris.show', $peminjamanInventaris)
            ->with('success', 'Peminjaman berhasil diupdate');
    }

    public function destroy(PeminjamanInventaris $peminjamanInventaris)
    {
        if (!in_array($peminjamanInventaris->status, ['menunggu_approval', 'ditolak'])) {
            return redirect()->back()->with('error', 'Peminjaman dengan status ini tidak dapat dihapus');
        }

        $kode = $peminjamanInventaris->kode_peminjaman;
        $peminjamanInventaris->delete();

        return redirect()->route('peminjaman-inventaris.index')
            ->with('success', 'Peminjaman ' . $kode . ' berhasil dihapus');
    }

    // Approval actions
    public function setujui(Request $request, PeminjamanInventaris $peminjamanInventaris)
    {
        $request->validate([
            'ttd_petugas' => 'nullable|string',
            'catatan_approval' => 'nullable|string',
        ]);

        $peminjamanInventaris->ttd_petugas = $request->ttd_petugas;
        $peminjamanInventaris->setujui(auth()->id(), $request->catatan_approval);
        $peminjamanInventaris->prosesPeminjaman();

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui');
    }

    public function tolak(Request $request, PeminjamanInventaris $peminjamanInventaris)
    {
        $request->validate([
            'catatan_approval' => 'required|string',
        ]);

        $peminjamanInventaris->tolak(auth()->id(), $request->catatan_approval);

        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak');
    }

    // Check inventaris ketersediaan
    public function checkKetersediaan($inventarisId)
    {
        $inventaris = Inventaris::findOrFail($inventarisId);
        
        return response()->json([
            'success' => true,
            'jumlah_total' => $inventaris->jumlah,
            'jumlah_tersedia' => $inventaris->jumlahTersedia(),
            'status' => $inventaris->status,
        ]);
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $query = PeminjamanInventaris::with(['inventaris', 'karyawan', 'disetujuiOleh']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
        }

        $peminjaman = $query->get();

        $pdf = Pdf::loadView('peminjaman-inventaris.pdf', compact('peminjaman'));
        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }
}
