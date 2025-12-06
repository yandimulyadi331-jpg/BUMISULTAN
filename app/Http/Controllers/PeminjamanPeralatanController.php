<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\PeminjamanPeralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanPeralatanController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanPeralatan::with(['peralatan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_peminjaman', 'like', '%' . $search . '%')
                  ->orWhere('nama_peminjam', 'like', '%' . $search . '%')
                  ->orWhereHas('peralatan', function($q) use ($search) {
                      $q->where('nama_peralatan', 'like', '%' . $search . '%');
                  });
            });
        }

        $peminjaman = $query->latest()->paginate(15);
        return view('peminjaman-peralatan.index', compact('peminjaman'));
    }

    public function create()
    {
        $peralatan = Peralatan::where('stok_tersedia', '>', 0)->get();
        
        // Check if AJAX request
        if (request()->ajax()) {
            return view('peminjaman-peralatan.create-modal', compact('peralatan'));
        }
        
        return view('peminjaman-peralatan.create', compact('peralatan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peralatan_id' => 'required|exists:peralatan,id',
            'nama_peminjam' => 'required|string|max:255',
            'jumlah_dipinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan' => 'required|string|max:255',
            'kondisi_saat_dipinjam' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $peralatan = Peralatan::findOrFail($validated['peralatan_id']);
        
        if ($peralatan->stok_tersedia < $validated['jumlah_dipinjam']) {
            return back()->with('error', 'Stok peralatan tidak mencukupi! Stok tersedia: ' . $peralatan->stok_tersedia);
        }

        DB::beginTransaction();
        try {
            $validated['nomor_peminjaman'] = PeminjamanPeralatan::generateNomorPeminjaman();
            $validated['status'] = 'dipinjam';
            PeminjamanPeralatan::create($validated);

            $peralatan->stok_tersedia -= $validated['jumlah_dipinjam'];
            $peralatan->stok_dipinjam += $validated['jumlah_dipinjam'];
            $peralatan->save();

            DB::commit();
            return redirect()->route('peminjaman-peralatan.index')
                ->with('success', 'Peminjaman peralatan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(PeminjamanPeralatan $peminjamanPeralatan)
    {
        $peminjamanPeralatan->load(['peralatan']);
        
        // Check if AJAX request
        if (request()->ajax()) {
            return view('peminjaman-peralatan.detail-modal', compact('peminjamanPeralatan'));
        }
        
        return view('peminjaman-peralatan.show', compact('peminjamanPeralatan'));
    }

    public function edit(PeminjamanPeralatan $peminjamanPeralatan)
    {
        if ($peminjamanPeralatan->status !== 'dipinjam') {
            if (request()->ajax()) {
                return '<div class="alert alert-danger">Peminjaman yang sudah dikembalikan tidak dapat diubah!</div>';
            }
            return redirect()->route('peminjaman-peralatan.index')
                ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diubah!');
        }

        // Check if AJAX request
        if (request()->ajax()) {
            return view('peminjaman-peralatan.edit-modal', compact('peminjamanPeralatan'));
        }
        
        $peralatan = Peralatan::all();
        return view('peminjaman-peralatan.edit', compact('peminjamanPeralatan', 'peralatan'));
    }

    public function update(Request $request, PeminjamanPeralatan $peminjamanPeralatan)
    {
        if ($peminjamanPeralatan->status !== 'dipinjam') {
            return redirect()->route('peminjaman-peralatan.index')
                ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diubah!');
        }

        $validated = $request->validate([
            'tanggal_kembali_rencana' => 'required|date',
            'keperluan' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $peminjamanPeralatan->update($validated);
        return redirect()->route('peminjaman-peralatan.index')
            ->with('success', 'Peminjaman peralatan berhasil diperbarui!');
    }

    public function destroy(PeminjamanPeralatan $peminjamanPeralatan)
    {
        DB::beginTransaction();
        try {
            $peralatan = $peminjamanPeralatan->peralatan;
            
            // Jika masih dipinjam, kembalikan stok
            if ($peminjamanPeralatan->status == 'dipinjam') {
                $peralatan->stok_tersedia += $peminjamanPeralatan->jumlah_dipinjam;
                $peralatan->stok_dipinjam -= $peminjamanPeralatan->jumlah_dipinjam;
                $peralatan->save();
            }
            
            $peminjamanPeralatan->delete();

            DB::commit();
            return redirect()->route('peminjaman-peralatan.index')
                ->with('success', 'Peminjaman peralatan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function pengembalian(Request $request, PeminjamanPeralatan $peminjamanPeralatan)
    {
        if ($peminjamanPeralatan->status !== 'dipinjam') {
            return back()->with('error', 'Peralatan sudah dikembalikan!');
        }

        $validated = $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_saat_dikembalikan' => 'required|string',
            'jumlah_rusak' => 'nullable|integer|min:0|max:' . $peminjamanPeralatan->jumlah_dipinjam,
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $peralatan = $peminjamanPeralatan->peralatan;
            $jumlahRusak = $validated['jumlah_rusak'] ?? 0;
            $jumlahBaik = $peminjamanPeralatan->jumlah_dipinjam - $jumlahRusak;

            $peminjamanPeralatan->tanggal_kembali_aktual = $validated['tanggal_kembali_aktual'];
            $peminjamanPeralatan->kondisi_saat_dikembalikan = $validated['kondisi_saat_dikembalikan'];
            $peminjamanPeralatan->catatan = $validated['catatan'] ?? $peminjamanPeralatan->catatan;
            
            if (strtotime($validated['tanggal_kembali_aktual']) > strtotime($peminjamanPeralatan->tanggal_kembali_rencana)) {
                $peminjamanPeralatan->status = 'terlambat';
            } else {
                $peminjamanPeralatan->status = 'dikembalikan';
            }
            
            $peminjamanPeralatan->save();

            $peralatan->stok_dipinjam -= $peminjamanPeralatan->jumlah_dipinjam;
            $peralatan->stok_tersedia += $jumlahBaik;
            $peralatan->stok_rusak += $jumlahRusak;
            $peralatan->save();

            DB::commit();
            return redirect()->route('peminjaman-peralatan.index')
                ->with('success', 'Pengembalian peralatan berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        $query = PeminjamanPeralatan::with(['peralatan']);
        
        // Apply filters if any
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_peminjaman', 'like', '%' . $search . '%')
                  ->orWhere('nama_peminjam', 'like', '%' . $search . '%')
                  ->orWhereHas('peralatan', function($q) use ($search) {
                      $q->where('nama_peralatan', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $peminjaman = $query->latest()->get();
        
        $pdf = PDF::loadView('peminjaman-peralatan.pdf', compact('peminjaman'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('Laporan_Peminjaman_Peralatan_' . date('YmdHis') . '.pdf');
    }

    public function formPengembalian(PeminjamanPeralatan $peminjamanPeralatan)
    {
        if ($peminjamanPeralatan->status !== 'dipinjam') {
            if (request()->ajax()) {
                return '<div class="alert alert-danger">Peralatan sudah dikembalikan!</div>';
            }
            return redirect()->route('peminjaman-peralatan.index')
                ->with('error', 'Peralatan sudah dikembalikan!');
        }
        
        // Check if AJAX request
        if (request()->ajax()) {
            return view('peminjaman-peralatan.pengembalian-modal', compact('peminjamanPeralatan'));
        }
        
        return view('peminjaman-peralatan.pengembalian', compact('peminjamanPeralatan'));
    }

    public function getStokTersedia($peralatanId)
    {
        $peralatan = Peralatan::findOrFail($peralatanId);
        return response()->json([
            'stok_tersedia' => $peralatan->stok_tersedia,
            'satuan' => $peralatan->satuan,
        ]);
    }
}
