<?php

namespace App\Http\Controllers;

use App\Models\PengembalianInventaris;
use App\Models\PeminjamanInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PengembalianInventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = PengembalianInventaris::with(['peminjaman.inventaris', 'peminjaman.karyawan', 'diterimаOleh']);

        if ($request->filled('terlambat')) {
            $query->where('terlambat', $request->terlambat);
        }

        if ($request->filled('kondisi_barang')) {
            $query->where('kondisi_barang', $request->kondisi_barang);
        }

        $pengembalian = $query->latest()->paginate(15);

        return view('pengembalian-inventaris.index', compact('pengembalian'));
    }

    public function create(Request $request)
    {
        // Untuk sistem multi-unit, pengembalian dilakukan dari halaman detail inventaris
        // Redirect ke index dengan pesan
        return redirect()->route('pengembalian-inventaris.index')
            ->with('info', 'Gunakan halaman Detail Inventaris untuk melakukan pengembalian barang');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_inventaris_id' => 'required|exists:peminjaman_inventaris,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat',
            'foto_pengembalian' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd_peminjam' => 'nullable|string',
            'ttd_petugas' => 'nullable|string',
            'catatan' => 'nullable|string',
            'denda' => 'nullable|numeric|min:0',
            'ada_kerusakan' => 'nullable|boolean',
            'deskripsi_kerusakan' => 'nullable|string',
        ], [
            'kondisi_kembali.required' => 'Kondisi barang wajib dipilih',
            'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi',
        ]);

        $peminjaman = PeminjamanInventaris::findOrFail($validated['peminjaman_inventaris_id']);
        $inventaris = $peminjaman->inventaris;

        // Check if already returned
        if ($peminjaman->pengembalian) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman ini sudah dikembalikan'
                ], 422);
            }
            return redirect()->back()->with('error', 'Peminjaman ini sudah dikembalikan');
        }

        // Handle file upload
        if ($request->hasFile('foto_pengembalian')) {
            $validated['foto_pengembalian'] = $request->file('foto_pengembalian')->store('pengembalian', 'public');
        }

        // Save signature images
        if (!empty($validated['ttd_peminjam'])) {
            $ttdPeminjam = $validated['ttd_peminjam'];
            if (preg_match('/^data:image\/(\w+);base64,/', $ttdPeminjam, $type)) {
                $ttdPeminjam = substr($ttdPeminjam, strpos($ttdPeminjam, ',') + 1);
                $type = strtolower($type[1]);
                $ttdPeminjam = base64_decode($ttdPeminjam);
                
                $filename = 'ttd_peminjam_' . time() . '_' . uniqid() . '.' . $type;
                Storage::disk('public')->put('signatures/' . $filename, $ttdPeminjam);
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
                Storage::disk('public')->put('signatures/' . $filename, $ttdPetugas);
                $validated['ttd_petugas'] = 'signatures/' . $filename;
            }
        }

        // Map kondisi_kembali to kondisi_barang (sesuai struktur database)
        $validated['kondisi_barang'] = $validated['kondisi_kembali'];
        unset($validated['kondisi_kembali']);

        // Map catatan to keterangan (sesuai struktur database)
        if (isset($validated['catatan'])) {
            $validated['keterangan'] = $validated['catatan'];
            unset($validated['catatan']);
        }

        // Set default values
        $validated['denda'] = $validated['denda'] ?? 0;
        $validated['diterima_oleh'] = auth()->id();
        $validated['created_by'] = auth()->id();
        $validated['jumlah_kembali'] = $peminjaman->jumlah_pinjam;

        // Create pengembalian record
        $pengembalian = PengembalianInventaris::create($validated);

        // Jika tracking per unit aktif dan unit terkait dengan peminjaman
        if ($inventaris->tracking_per_unit && $peminjaman->inventaris_detail_unit_id) {
            $detailUnit = \App\Models\InventarisDetailUnit::find($peminjaman->inventaris_detail_unit_id);
            
            if ($detailUnit) {
                // Update kondisi unit berdasarkan kondisi pengembalian
                $detailUnit->kondisi = $validated['kondisi_barang'];
                $detailUnit->save();
                
                // Update status unit ke tersedia atau maintenance jika rusak
                if (in_array($validated['kondisi_barang'], ['rusak_ringan', 'rusak_berat'])) {
                    $detailUnit->setMaintenance('Perlu perbaikan: ' . ($validated['deskripsi_kerusakan'] ?? 'Rusak saat pengembalian'));
                } else {
                    $detailUnit->setDikembalikan($pengembalian);
                }
            }
        } else {
            // Update inventaris status to 'tersedia' (mode lama)
            $inventaris->update(['status' => 'tersedia']);
        }

        // Update peminjaman status
        $peminjaman->update(['status_peminjaman' => 'dikembalikan']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengembalian berhasil diproses dengan kode: ' . $pengembalian->kode_pengembalian,
                'data' => $pengembalian
            ]);
        }

        return redirect()->route('pengembalian-inventaris.show', $pengembalian)
            ->with('success', 'Pengembalian berhasil diproses dengan kode: ' . $pengembalian->kode_pengembalian);
    }

    public function show(PengembalianInventaris $pengembalianInventaris)
    {
        $pengembalianInventaris->load(['peminjaman.inventaris', 'peminjaman.karyawan', 'diterimаOleh']);

        return view('pengembalian-inventaris.show', compact('pengembalianInventaris'));
    }

    public function exportPdf(Request $request)
    {
        $query = PengembalianInventaris::with(['peminjaman.inventaris', 'peminjaman.karyawan']);

        if ($request->filled('terlambat')) {
            $query->where('terlambat', $request->terlambat);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pengembalian', [$request->start_date, $request->end_date]);
        }

        $pengembalian = $query->get();
        $totalDenda = $pengembalian->sum('denda');

        $pdf = Pdf::loadView('pengembalian-inventaris.pdf', compact('pengembalian', 'totalDenda'));
        return $pdf->download('laporan-pengembalian-' . date('Y-m-d') . '.pdf');
    }

    // Get peminjaman yang bisa dikembalikan
    public function getPeminjamanAktif()
    {
        // Redirect ke create dengan list peminjaman aktif
        return redirect()->route('pengembalian-inventaris.create');
    }
}
