<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\PeminjamanPeralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PeralatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peralatan::query();

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter berdasarkan lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi_penyimpanan', 'like', '%' . $request->lokasi . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_peralatan', 'like', '%' . $search . '%')
                  ->orWhere('kode_peralatan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $peralatan = $query->latest()->paginate(15);
        
        // Data untuk filter
        $kategoris = Peralatan::select('kategori')->distinct()->pluck('kategori');

        return view('peralatan.index', compact('peralatan', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if AJAX request
        if (request()->ajax()) {
            return view('peralatan.create-modal');
        }
        
        $kategoris = [
            'Alat Kebersihan',
            'Alat Tulis Kantor',
            'Elektronik',
            'Peralatan Dapur',
            'Peralatan Olahraga',
            'Peralatan Taman',
            'Perkakas',
            'Keamanan',
            'Lainnya'
        ];

        return view('peralatan.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peralatan' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'harga_satuan' => 'nullable|numeric|min:0',
            'tanggal_pembelian' => 'nullable|date',
            'supplier' => 'nullable|string|max:255',
            'stok_minimum' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string',
        ]);

        // Generate kode peralatan otomatis
        $validated['kode_peralatan'] = Peralatan::generateKodePeralatan();

        // Set stok tersedia sama dengan stok awal
        $validated['stok_tersedia'] = $validated['stok_awal'];
        $validated['stok_dipinjam'] = 0;
        $validated['stok_rusak'] = 0;

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('storage/peralatan'), $filename);
            $validated['foto'] = $filename;
        }

        Peralatan::create($validated);

        return redirect()->route('peralatan.index')
            ->with('success', 'Peralatan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peralatan $peralatan)
    {
        $peralatan->load(['peminjaman']);
        
        // Check if AJAX request
        if (request()->ajax()) {
            return view('peralatan.detail-modal', compact('peralatan'));
        }
        
        $riwayatPeminjaman = $peralatan->peminjaman()->latest()->paginate(10);
        return view('peralatan.show', compact('peralatan', 'riwayatPeminjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peralatan $peralatan)
    {
        // Check if AJAX request
        if (request()->ajax()) {
            return view('peralatan.edit-modal', compact('peralatan'));
        }
        
        $kategoris = [
            'Alat Kebersihan',
            'Alat Tulis Kantor',
            'Elektronik',
            'Peralatan Dapur',
            'Peralatan Olahraga',
            'Peralatan Taman',
            'Perkakas',
            'Keamanan',
            'Lainnya'
        ];

        return view('peralatan.edit', compact('peralatan', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peralatan $peralatan)
    {
        $validated = $request->validate([
            'nama_peralatan' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'harga_satuan' => 'nullable|numeric|min:0',
            'tanggal_pembelian' => 'nullable|date',
            'supplier' => 'nullable|string|max:255',
            'stok_minimum' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string',
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($peralatan->foto && file_exists(public_path('storage/peralatan/' . $peralatan->foto))) {
                unlink(public_path('storage/peralatan/' . $peralatan->foto));
            }

            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('storage/peralatan'), $filename);
            $validated['foto'] = $filename;
        }

        $peralatan->update($validated);

        return redirect()->route('peralatan.index')
            ->with('success', 'Peralatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peralatan $peralatan)
    {
        // Cek apakah ada peminjaman aktif
        $peminjamanAktif = $peralatan->peminjaman()->where('status', 'dipinjam')->count();

        if ($peminjamanAktif > 0) {
            return redirect()->route('peralatan.index')
                ->with('error', 'Tidak dapat menghapus peralatan yang sedang dipinjam!');
        }

        // Hapus foto jika ada
        if ($peralatan->foto && file_exists(public_path('storage/peralatan/' . $peralatan->foto))) {
            unlink(public_path('storage/peralatan/' . $peralatan->foto));
        }

        $peralatan->delete();

        return redirect()->route('peralatan.index')
            ->with('success', 'Peralatan berhasil dihapus!');
    }

    /**
     * Export PDF Semua Peralatan
     */
    public function exportPdf(Request $request)
    {
        $peralatan = Peralatan::orderBy('kategori')->orderBy('nama_peralatan')->get();
        
        $pdf = PDF::loadView('peralatan.pdf', compact('peralatan'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('Data_Peralatan_BS_' . date('YmdHis') . '.pdf');
    }

    /**
     * Laporan Stok Peralatan
     */
    public function laporanStok(Request $request)
    {
        $query = Peralatan::query();

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter stok menipis
        if ($request->filled('stok_menipis') && $request->stok_menipis == '1') {
            $query->whereColumn('stok_tersedia', '<=', 'stok_minimum');
        }

        $peralatan = $query->get();
        $kategoris = Peralatan::select('kategori')->distinct()->pluck('kategori');

        // Statistik
        $totalPeralatan = $peralatan->count();
        $totalStokTersedia = $peralatan->sum('stok_tersedia');
        $totalStokDipinjam = $peralatan->sum('stok_dipinjam');
        $totalStokRusak = $peralatan->sum('stok_rusak');
        $stokMenipis = $peralatan->filter(function($p) {
            return $p->isStokMenipis();
        })->count();

        return view('peralatan.laporan-stok', compact(
            'peralatan', 
            'kategoris',
            'totalPeralatan',
            'totalStokTersedia',
            'totalStokDipinjam',
            'totalStokRusak',
            'stokMenipis'
        ));
    }

    /**
     * Export Laporan Stok PDF
     */
    public function exportLaporanStok(Request $request)
    {
        $query = Peralatan::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('stok_menipis') && $request->stok_menipis == '1') {
            $query->whereColumn('stok_tersedia', '<=', 'stok_minimum');
        }

        $peralatan = $query->get();

        $pdf = Pdf::loadView('peralatan.pdf.laporan-stok', compact('peralatan'));
        return $pdf->download('laporan-stok-peralatan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Laporan Peminjaman
     */
    public function laporanPeminjaman(Request $request)
    {
        $query = PeminjamanPeralatan::with(['peralatan']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }

        $peminjaman = $query->latest()->paginate(15);

        // Statistik
        $totalPeminjaman = PeminjamanPeralatan::count();
        $peminjamanAktif = PeminjamanPeralatan::where('status', 'dipinjam')->count();
        $peminjamanSelesai = PeminjamanPeralatan::where('status', 'dikembalikan')->count();
        $peminjamanTerlambat = PeminjamanPeralatan::where('status', 'terlambat')->count();

        return view('peralatan.laporan-peminjaman', compact(
            'peminjaman',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanSelesai',
            'peminjamanTerlambat'
        ));
    }

    /**
     * Export Laporan Peminjaman PDF
     */
    public function exportLaporanPeminjaman(Request $request)
    {
        $query = PeminjamanPeralatan::with(['peralatan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }

        $peminjaman = $query->latest()->get();

        $pdf = Pdf::loadView('peralatan.pdf.laporan-peminjaman', compact('peminjaman'));
        return $pdf->download('laporan-peminjaman-peralatan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Update Stok Peralatan
     */
    public function updateStok(Request $request, Peralatan $peralatan)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:tambah,kurang,rusak',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        switch ($validated['tipe']) {
            case 'tambah':
                $peralatan->stok_tersedia += $validated['jumlah'];
                $peralatan->stok_awal += $validated['jumlah'];
                break;
            case 'kurang':
                if ($peralatan->stok_tersedia < $validated['jumlah']) {
                    return back()->with('error', 'Stok tidak mencukupi!');
                }
                $peralatan->stok_tersedia -= $validated['jumlah'];
                break;
            case 'rusak':
                if ($peralatan->stok_tersedia < $validated['jumlah']) {
                    return back()->with('error', 'Stok tidak mencukupi!');
                }
                $peralatan->stok_tersedia -= $validated['jumlah'];
                $peralatan->stok_rusak += $validated['jumlah'];
                break;
        }

        $peralatan->save();

        return back()->with('success', 'Stok peralatan berhasil diperbarui!');
    }
}
