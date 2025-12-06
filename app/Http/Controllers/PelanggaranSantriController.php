<?php

namespace App\Http\Controllers;

use App\Models\PelanggaranSantri;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelanggaranSantriExport;

class PelanggaranSantriController extends Controller
{
    /**
     * Display a listing of the resource (tampilkan semua santri)
     */
    public function index(Request $request)
    {
        // Query semua santri
        $query = Santri::query();

        // Filter berdasarkan santri
        if ($request->filled('santri_id')) {
            $query->where('id', $request->santri_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }

        $santriList = $query->orderBy('nama_lengkap', 'asc')->paginate(20);

        // Tambahkan informasi pelanggaran untuk setiap santri
        foreach ($santriList as $santri) {
            $totalPelanggaran = PelanggaranSantri::totalPelanggaranSantri($santri->id);
            $totalPoint = PelanggaranSantri::totalPointSantri($santri->id);
            $statusInfo = PelanggaranSantri::getStatusPelanggaran($totalPelanggaran);
            
            $santri->total_pelanggaran = $totalPelanggaran;
            $santri->total_point = $totalPoint;
            $santri->status_info = $statusInfo;
        }

        // Get list santri untuk filter
        $allSantri = Santri::orderBy('nama_lengkap', 'asc')->get();

        return view('pelanggaran-santri.index', compact('santriList', 'allSantri'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $santriList = Santri::where('status_santri', 'aktif')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('pelanggaran-santri.create', compact('santriList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:santri,id',
            'nama_santri' => 'required|string|max:255',
            'nik_santri' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'keterangan' => 'required|string',
            'tanggal_pelanggaran' => 'required|date',
            'point' => 'required|integer|min:1|max:10',
        ], [
            'user_id.required' => 'Santri harus dipilih',
            'user_id.exists' => 'Santri yang dipilih tidak valid',
            'nama_santri.required' => 'Nama santri harus diisi',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 5MB',
            'keterangan.required' => 'Keterangan pelanggaran harus diisi',
            'tanggal_pelanggaran.required' => 'Tanggal pelanggaran harus diisi',
            'point.required' => 'Point pelanggaran harus diisi',
            'point.integer' => 'Point harus berupa angka',
            'point.min' => 'Point minimal 1',
            'point.max' => 'Point maksimal 10',
        ]);

        $data = $request->except('foto');
        $data['created_by'] = auth()->id();

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pelanggaran-santri', $filename, 'public');
            $data['foto'] = $path;
        }

        PelanggaranSantri::create($data);

        // Jika request via AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pelanggaran berhasil ditambahkan'
            ]);
        }

        return redirect()
            ->route('pelanggaran-santri.index')
            ->with('success', 'Data pelanggaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource (show detail riwayat pelanggaran per santri)
     */
    public function show($santriId)
    {
        // Ambil data santri
        $santri = Santri::findOrFail($santriId);
        
        // Ambil semua pelanggaran santri ini
        $riwayatPelanggaran = PelanggaranSantri::where('user_id', $santriId)
            ->with('pencatat')
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->get();
        
        // Hitung total pelanggaran santri ini
        $totalPelanggaran = PelanggaranSantri::totalPelanggaranSantri($santriId);
        $totalPoint = PelanggaranSantri::totalPointSantri($santriId);
        $statusInfo = PelanggaranSantri::getStatusPelanggaran($totalPelanggaran);

        return view('pelanggaran-santri.show', compact('santri', 'riwayatPelanggaran', 'totalPelanggaran', 'totalPoint', 'statusInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PelanggaranSantri $pelanggaranSantri)
    {
        $santriList = Santri::where('status_santri', 'aktif')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('pelanggaran-santri.edit', compact('pelanggaranSantri', 'santriList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PelanggaranSantri $pelanggaranSantri)
    {
        $request->validate([
            'user_id' => 'required|exists:santri,id',
            'nama_santri' => 'required|string|max:255',
            'nik_santri' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'keterangan' => 'required|string',
            'tanggal_pelanggaran' => 'required|date',
        ]);

        $data = $request->except('foto');
        $data['point'] = 1; // Point tetap 1, diatur otomatis oleh sistem

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($pelanggaranSantri->foto && Storage::disk('public')->exists($pelanggaranSantri->foto)) {
                Storage::disk('public')->delete($pelanggaranSantri->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pelanggaran-santri', $filename, 'public');
            $data['foto'] = $path;
        }

        $pelanggaranSantri->update($data);

        return redirect()
            ->route('pelanggaran-santri.index')
            ->with('success', 'Data pelanggaran berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PelanggaranSantri $pelanggaranSantri)
    {
        // Hapus foto jika ada
        if ($pelanggaranSantri->foto && Storage::disk('public')->exists($pelanggaranSantri->foto)) {
            Storage::disk('public')->delete($pelanggaranSantri->foto);
        }

        $pelanggaranSantri->delete();

        return redirect()
            ->route('pelanggaran-santri.index')
            ->with('success', 'Data pelanggaran berhasil dihapus');
    }

    /**
     * Tampilkan halaman laporan
     */
    public function laporan(Request $request)
    {
        // Ambil rekap per santri
        $rekapSantri = DB::table('pelanggaran_santri')
            ->join('santri', 'pelanggaran_santri.user_id', '=', 'santri.id')
            ->select(
                'santri.id',
                'santri.nama_lengkap as name',
                'santri.nik',
                DB::raw('COUNT(pelanggaran_santri.id) as total_pelanggaran'),
                DB::raw('SUM(pelanggaran_santri.point) as total_point'),
                DB::raw('MAX(pelanggaran_santri.tanggal_pelanggaran) as pelanggaran_terakhir')
            )
            ->groupBy('santri.id', 'santri.nama_lengkap', 'santri.nik')
            ->orderBy('total_pelanggaran', 'desc');

        // Filter berdasarkan tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rekapSantri->whereBetween('pelanggaran_santri.tanggal_pelanggaran', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $rekapSantri = $rekapSantri->get();

        // Tambahkan status untuk setiap santri
        foreach ($rekapSantri as $santri) {
            $statusInfo = PelanggaranSantri::getStatusPelanggaran($santri->total_point);
            $santri->status = $statusInfo['status'];
            $santri->badge_class = $statusInfo['badge'];
            $santri->text_class = $statusInfo['text'];
            $santri->bg_class = $statusInfo['bg'];
        }

        return view('pelanggaran-santri.laporan', compact('rekapSantri'));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request)
    {
        // Ambil rekap per santri
        $rekapSantri = DB::table('pelanggaran_santri')
            ->join('santri', 'pelanggaran_santri.user_id', '=', 'santri.id')
            ->select(
                'santri.id',
                'santri.nama_lengkap as name',
                'santri.nik',
                DB::raw('COUNT(pelanggaran_santri.id) as total_pelanggaran'),
                DB::raw('SUM(pelanggaran_santri.point) as total_point'),
                DB::raw('MAX(pelanggaran_santri.tanggal_pelanggaran) as pelanggaran_terakhir')
            )
            ->groupBy('santri.id', 'santri.nama_lengkap', 'santri.nik')
            ->orderBy('total_pelanggaran', 'desc');

        // Filter berdasarkan tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rekapSantri->whereBetween('pelanggaran_santri.tanggal_pelanggaran', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $rekapSantri = $rekapSantri->get();

        // Tambahkan status untuk setiap santri
        foreach ($rekapSantri as $santri) {
            $statusInfo = PelanggaranSantri::getStatusPelanggaran($santri->total_point);
            $santri->status = $statusInfo['status'];
        }

        $pdf = Pdf::loadView('pelanggaran-santri.pdf', [
            'rekapSantri' => $rekapSantri,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pelanggaran-santri-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PelanggaranSantriExport($request->start_date, $request->end_date),
            'laporan-pelanggaran-santri-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Get total pelanggaran untuk santri tertentu (untuk AJAX)
     */
    public function getTotalPelanggaran($userId)
    {
        $total = PelanggaranSantri::totalPelanggaranSantri($userId);
        $totalPoint = PelanggaranSantri::totalPointSantri($userId);
        $statusInfo = PelanggaranSantri::getStatusPelanggaran($totalPoint);

        return response()->json([
            'total' => $total,
            'total_point' => $totalPoint,
            'status' => $statusInfo
        ]);
    }

    /**
     * Generate Surat Peringatan PDF untuk santri dengan total point >= 8 (Berat)
     */
    public function suratPeringatan($userId)
    {
        $santri = Santri::findOrFail($userId);
        $totalPelanggaran = PelanggaranSantri::totalPelanggaranSantri($userId);
        $totalPoint = PelanggaranSantri::totalPointSantri($userId);
        
        // Cek apakah sudah memenuhi syarat surat peringatan (point >= 8)
        if ($totalPoint < 8) {
            return redirect()
                ->back()
                ->with('error', 'Surat peringatan hanya untuk santri dengan total point >= 8 (kategori Berat). Total point saat ini: ' . $totalPoint);
        }

        // Ambil data pelanggaran santri
        $riwayatPelanggaran = PelanggaranSantri::where('user_id', $userId)
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->limit(10) // 10 pelanggaran terakhir
            ->get();

        $statusInfo = PelanggaranSantri::getStatusPelanggaran($totalPoint);

        // Generate nomor surat
        $nomorSurat = 'SP/' . date('Y') . '/' . str_pad($userId, 4, '0', STR_PAD_LEFT) . '/' . date('m');

        $pdf = Pdf::loadView('pelanggaran-santri.surat-peringatan', [
            'santri' => $santri,
            'totalPelanggaran' => $totalPelanggaran,
            'totalPoint' => $totalPoint,
            'statusInfo' => $statusInfo,
            'riwayatPelanggaran' => $riwayatPelanggaran,
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => now()->locale('id')->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        $filename = 'Surat-Peringatan-' . str_replace(' ', '-', $santri->name) . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Index untuk Karyawan (Read & Input)
     */
    public function indexKaryawan(Request $request)
    {
        // Query semua santri
        $query = Santri::query();

        // Filter berdasarkan santri
        if ($request->filled('santri_id')) {
            $query->where('id', $request->santri_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$search}%");
            });
        }

        $santriList = $query->orderBy('nama_lengkap', 'asc')->paginate(20);

        // Tambahkan informasi pelanggaran untuk setiap santri
        foreach ($santriList as $santri) {
            $totalPelanggaran = PelanggaranSantri::totalPelanggaranSantri($santri->id);
            $totalPoint = PelanggaranSantri::totalPointSantri($santri->id);
            $statusInfo = PelanggaranSantri::getStatusPelanggaran($totalPelanggaran);
            
            $santri->total_pelanggaran = $totalPelanggaran;
            $santri->total_point = $totalPoint;
            $santri->status_info = $statusInfo;
        }

        // Get list santri untuk filter
        $allSantri = Santri::orderBy('nama_lengkap', 'asc')->get();

        return view('pelanggaran-santri.karyawan.index', compact('santriList', 'allSantri'));
    }

    /**
     * Store pelanggaran dari karyawan (via AJAX)
     */
    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:santri,id',
            'nama_santri' => 'required|string|max:255',
            'nik_santri' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'keterangan' => 'required|string',
            'tanggal_pelanggaran' => 'required|date',
            'point' => 'required|integer|min:1|max:10',
        ], [
            'user_id.required' => 'Santri harus dipilih',
            'user_id.exists' => 'Santri yang dipilih tidak valid',
            'nama_santri.required' => 'Nama santri harus diisi',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 5MB',
            'keterangan.required' => 'Keterangan pelanggaran harus diisi',
            'tanggal_pelanggaran.required' => 'Tanggal pelanggaran harus diisi',
            'point.required' => 'Point pelanggaran harus diisi',
            'point.integer' => 'Point harus berupa angka',
            'point.min' => 'Point minimal 1',
            'point.max' => 'Point maksimal 10',
        ]);

        $data = $request->except('foto');
        $data['created_by'] = auth()->id();

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pelanggaran-santri', $filename, 'public');
            $data['foto'] = $path;
        }

        $pelanggaran = PelanggaranSantri::create($data);

        // Hitung total terbaru
        $totalPelanggaran = PelanggaranSantri::totalPelanggaranSantri($request->user_id);
        $totalPoint = PelanggaranSantri::totalPointSantri($request->user_id);
        $statusInfo = PelanggaranSantri::getStatusPelanggaran($totalPelanggaran);

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggaran berhasil ditambahkan',
            'data' => [
                'total_pelanggaran' => $totalPelanggaran,
                'total_point' => $totalPoint,
                'status_info' => $statusInfo
            ]
        ]);
    }

    /**
     * Show detail untuk karyawan
     */
    public function showKaryawan($santriId)
    {
        // Ambil data santri
        $santri = Santri::findOrFail($santriId);
        
        // Ambil semua pelanggaran santri ini
        $riwayatPelanggaran = PelanggaranSantri::where('user_id', $santriId)
            ->with('pencatat')
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->get();
        
        // Hitung total pelanggaran santri ini
        $totalPelanggaran = PelanggaranSantri::totalPelanggaranSantri($santriId);
        $totalPoint = PelanggaranSantri::totalPointSantri($santriId);
        $statusInfo = PelanggaranSantri::getStatusPelanggaran($totalPelanggaran);

        return view('pelanggaran-santri.karyawan.show', compact('santri', 'riwayatPelanggaran', 'totalPelanggaran', 'totalPoint', 'statusInfo'));
    }
}

