<?php

namespace App\Http\Controllers;

use App\Models\Temuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TemuanController extends Controller
{
    /**
     * ADMIN SECTION
     */

    /**
     * Display list temuan untuk admin
     */
    public function index(Request $request)
    {
        $query = Temuan::with(['pelapor', 'admin'])->latest();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan urgensi
        if ($request->filled('urgensi')) {
            $query->where('urgensi', $request->urgensi);
        }

        // Search berdasarkan judul atau lokasi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $temuan = $query->paginate(15);

        return view('temuan.index', compact('temuan'));
    }

    /**
     * Show detail temuan untuk admin
     */
    public function show($id)
    {
        $temuan = Temuan::with(['pelapor', 'admin'])->findOrFail($id);

        return view('temuan.show', compact('temuan'));
    }

    /**
     * Update status temuan
     */
    public function updateStatus(Request $request, $id)
    {
        $temuan = Temuan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:baru,sedang_diproses,sudah_diperbaiki,tindaklanjuti,selesai',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        // Update status
        $temuan->status = $validated['status'];
        $temuan->admin_id = Auth::id();

        // Set tanggal ketika status berubah
        if ($validated['status'] == 'sedang_diproses' && !$temuan->tanggal_ditindaklanjuti) {
            $temuan->tanggal_ditindaklanjuti = now();
        }

        if ($validated['status'] == 'selesai' && !$temuan->tanggal_selesai) {
            $temuan->tanggal_selesai = now();
        }

        // Update catatan jika ada
        if ($request->filled('catatan_admin')) {
            $temuan->catatan_admin = $validated['catatan_admin'];
        }

        $temuan->save();

        return redirect()->route('temuan.show', $id)
            ->with('success', 'Status temuan berhasil diperbarui!');
    }

    /**
     * Delete temuan
     */
    public function destroy($id)
    {
        $temuan = Temuan::findOrFail($id);

        // Delete foto jika ada
        if ($temuan->foto_path && Storage::disk('public')->exists($temuan->foto_path)) {
            Storage::disk('public')->delete($temuan->foto_path);
        }

        $temuan->delete();

        return redirect()->route('temuan.index')
            ->with('success', 'Temuan berhasil dihapus!');
    }

    /**
     * KARYAWAN SECTION
     */

    /**
     * Show form untuk membuat laporan temuan (karyawan)
     */
    public function create()
    {
        return view('temuan.karyawan.create');
    }

    /**
     * Store laporan temuan baru (karyawan)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:2000',
            'lokasi' => 'required|string|max:255',
            'urgensi' => 'required|in:rendah,sedang,tinggi,kritis',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('temuan', 'public');
        }

        Temuan::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'urgensi' => $validated['urgensi'],
            'foto_path' => $path,
            'user_id' => Auth::id(),
            'status' => 'baru',
        ]);

        return redirect()->route('temuan.karyawan.list')
            ->with('success', 'Laporan temuan berhasil dikirim! Admin akan segera memproses.');
    }

    /**
     * Display list temuan untuk karyawan
     */
    public function karyawanList(Request $request)
    {
        $query = Temuan::where('user_id', Auth::id())->with(['admin'])->latest();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $temuan = $query->paginate(10);

        return view('temuan.karyawan.list', compact('temuan'));
    }

    /**
     * Show detail temuan untuk karyawan
     */
    public function karyawanShow($id)
    {
        $temuan = Temuan::where('user_id', Auth::id())
            ->with(['admin'])
            ->findOrFail($id);

        return view('temuan.karyawan.show', compact('temuan'));
    }

    /**
     * Delete laporan temuan sendiri (karyawan)
     */
    public function karyawanDestroy($id)
    {
        $temuan = Temuan::where('user_id', Auth::id())->findOrFail($id);

        // Hanya bisa dihapus jika status masih baru
        if ($temuan->status != 'baru') {
            return redirect()->route('temuan.karyawan.show', $id)
                ->with('error', 'Laporan temuan tidak bisa dihapus setelah diproses!');
        }

        // Delete foto jika ada
        if ($temuan->foto_path && Storage::disk('public')->exists($temuan->foto_path)) {
            Storage::disk('public')->delete($temuan->foto_path);
        }

        $temuan->delete();

        return redirect()->route('temuan.karyawan.list')
            ->with('success', 'Laporan temuan berhasil dihapus!');
    }

    /**
     * API untuk dashboard - Get summary temuan
     */
    public function apiSummary()
    {
        return response()->json([
            'total' => Temuan::count(),
            'aktif' => Temuan::aktif()->count(),
            'baru' => Temuan::where('status', 'baru')->count(),
            'sedang_diproses' => Temuan::where('status', 'sedang_diproses')->count(),
            'selesai' => Temuan::where('status', 'selesai')->count(),
            'kritis' => Temuan::where('urgensi', 'kritis')->aktif()->count(),
        ]);
    }

    /**
     * Export to PDF untuk admin
     */
    public function exportPdf(Request $request)
    {
        $query = Temuan::with(['pelapor', 'admin'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgensi')) {
            $query->where('urgensi', $request->urgensi);
        }

        $temuan = $query->get();

        $pdf = app('dompdf.wrapper')
            ->loadView('temuan.pdf', compact('temuan'))
            ->setOption('defaultFont', 'sans-serif');

        return $pdf->download('laporan-temuan-' . now()->format('Y-m-d') . '.pdf');
    }
}
