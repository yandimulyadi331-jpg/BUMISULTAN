<?php

namespace App\Http\Controllers;

use App\Models\IjinSantri;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class IjinSantriController extends Controller
{
    public function index(Request $request)
    {
        $query = IjinSantri::with(['santri', 'creator']);

        // Filter berdasarkan tanggal hari ini sebagai default
        if (!$request->filled('tanggal_dari') && !$request->filled('tanggal_sampai') && !$request->filled('search')) {
            // Tampilkan ijin hari ini saja
            $query->whereDate('tanggal_ijin', today());
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('alasan_ijin', 'like', "%{$search}%")
                  ->orWhereHas('santri', function($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_ijin', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_ijin', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ijinSantri = $query->orderBy('tanggal_ijin', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ijin_santri.index', compact('ijinSantri'));
    }

    public function create()
    {
        $santri = Santri::orderBy('nama_lengkap')->get();
        return view('ijin_santri.create', compact('santri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'tanggal_ijin' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_ijin',
            'alasan_ijin' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $nomorSurat = IjinSantri::generateNomorSurat();

        $ijinSantri = IjinSantri::create([
            'santri_id' => $request->santri_id,
            'tanggal_ijin' => $request->tanggal_ijin,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'alasan_ijin' => $request->alasan_ijin,
            'catatan' => $request->catatan,
            'nomor_surat' => $nomorSurat,
            'status' => 'pending',
            'created_by' => Auth::id()
        ]);

        return redirect()->route('ijin-santri.index')
            ->with('success', 'Data ijin santri berhasil dibuat. Silakan download surat untuk TTD Ustadz.');
    }

    public function show($id)
    {
        $ijinSantri = IjinSantri::with(['santri', 'creator', 'ttdUstadzBy', 'verifikasiPulangBy', 'verifikasiKembaliBy'])
            ->findOrFail($id);

        return view('ijin_santri.show', compact('ijinSantri'));
    }

    public function downloadPdf($id)
    {
        $ijinSantri = IjinSantri::with(['santri', 'creator'])->findOrFail($id);

        $pdf = Pdf::loadView('ijin_santri.pdf', compact('ijinSantri'));
        
        $filename = 'Surat_Ijin_' . $ijinSantri->santri->nama_lengkap . '_' . $ijinSantri->nomor_surat . '.pdf';
        $filename = str_replace(['/', ' '], ['_', '_'], $filename);

        return $pdf->download($filename);
    }

    public function verifikasiTtdUstadz(Request $request, $id)
    {
        $ijinSantri = IjinSantri::findOrFail($id);

        if ($ijinSantri->status !== 'pending') {
            return redirect()->back()->with('error', 'Status ijin tidak valid untuk verifikasi TTD Ustadz.');
        }

        $ijinSantri->update([
            'status' => 'ttd_ustadz',
            'ttd_ustadz_at' => now(),
            'ttd_ustadz_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Verifikasi TTD Ustadz berhasil. Santri siap untuk dipulangkan.');
    }

    public function verifikasiKepulangan(Request $request, $id)
    {
        $ijinSantri = IjinSantri::findOrFail($id);

        if ($ijinSantri->status !== 'ttd_ustadz') {
            return redirect()->back()->with('error', 'Status ijin tidak valid untuk verifikasi kepulangan.');
        }

        $ijinSantri->update([
            'status' => 'dipulangkan',
            'verifikasi_pulang_at' => now(),
            'verifikasi_pulang_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Verifikasi kepulangan berhasil. Status santri: PULANG');
    }

    public function verifikasiKembali(Request $request, $id)
    {
        $request->validate([
            'foto_surat_ttd_ortu' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_kembali_aktual' => 'required|date'
        ]);

        $ijinSantri = IjinSantri::findOrFail($id);

        if ($ijinSantri->status !== 'dipulangkan') {
            return redirect()->back()->with('error', 'Status ijin tidak valid untuk verifikasi kembali.');
        }

        // Upload foto surat TTD ortu
        $file = $request->file('foto_surat_ttd_ortu');
        $filename = 'ijin_santri_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/ijin_santri', $filename);

        $ijinSantri->update([
            'status' => 'kembali',
            'foto_surat_ttd_ortu' => $filename,
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
            'verifikasi_kembali_at' => now(),
            'verifikasi_kembali_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Verifikasi kembali berhasil. Santri sudah di pesantren.');
    }

    public function destroy($id)
    {
        $ijinSantri = IjinSantri::findOrFail($id);

        // Hapus foto jika ada
        if ($ijinSantri->foto_surat_ttd_ortu) {
            Storage::delete('public/ijin_santri/' . $ijinSantri->foto_surat_ttd_ortu);
        }

        $ijinSantri->delete();

        return redirect()->route('ijin-santri.index')->with('success', 'Data ijin santri berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = IjinSantri::with(['santri', 'creator', 'ttdUstadzBy', 'verifikasiPulangBy', 'verifikasiKembaliBy']);

        // Filter sesuai dengan yang ditampilkan di index
        if (!$request->filled('tanggal_dari') && !$request->filled('tanggal_sampai') && !$request->filled('search')) {
            $query->whereDate('tanggal_ijin', today());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('alasan_ijin', 'like', "%{$search}%")
                  ->orWhereHas('santri', function($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_ijin', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_ijin', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ijinSantri = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('ijin_santri.laporan_pdf', compact('ijinSantri'))
            ->setPaper('a4', 'landscape');
        
        $filename = 'Laporan_Ijin_Santri_' . date('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Halaman Ijin Santri untuk Karyawan (READ ONLY)
     */
    public function indexKaryawan(Request $request)
    {
        $query = IjinSantri::with(['santri', 'creator']);

        // Filter berdasarkan tanggal hari ini sebagai default
        if (!$request->filled('tanggal_dari') && !$request->filled('tanggal_sampai') && !$request->filled('search')) {
            // Tampilkan ijin hari ini saja
            $query->whereDate('tanggal_ijin', today());
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('alasan_ijin', 'like', "%{$search}%")
                  ->orWhereHas('santri', function($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_ijin', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_ijin', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ijinSantri = $query->orderBy('tanggal_ijin', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ijin_santri.karyawan.index', compact('ijinSantri'));
    }

    /**
     * Detail Ijin Santri untuk Karyawan (READ ONLY)
     */
    public function showKaryawan($id)
    {
        $ijinSantri = IjinSantri::with(['santri', 'creator', 'ttdUstadzBy', 'verifikasiPulangBy', 'verifikasiKembaliBy'])
            ->findOrFail($id);

        return view('ijin_santri.karyawan.show', compact('ijinSantri'));
    }

    /**
     * Download PDF untuk Karyawan (READ ONLY)
     */
    public function downloadPdfKaryawan($id)
    {
        $ijinSantri = IjinSantri::with(['santri', 'creator'])->findOrFail($id);

        $pdf = Pdf::loadView('ijin_santri.pdf', compact('ijinSantri'));
        
        $filename = 'Surat_Ijin_' . $ijinSantri->santri->nama_lengkap . '_' . $ijinSantri->nomor_surat . '.pdf';
        $filename = str_replace(['/', ' '], ['_', '_'], $filename);

        return $pdf->download($filename);
    }
}

