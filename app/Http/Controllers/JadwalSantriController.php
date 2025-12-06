<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSantri;
use Carbon\Carbon;

class JadwalSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwalList = JadwalSantri::orderBy('created_at', 'desc')->get();
        
        // Tambahkan informasi apakah jadwal sedang berlangsung
        foreach ($jadwalList as $jadwal) {
            $jadwal->is_berlangsung = $jadwal->isJadwalBerlangsung();
        }
        
        return view('jadwal-santri.index', compact('jadwalList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jadwal-santri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jadwal' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_jadwal' => 'required|in:harian,mingguan,bulanan',
            'hari' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'durasi_menit' => 'nullable|integer',
            'tempat' => 'nullable|string|max:255',
            'pembimbing' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        JadwalSantri::create($validated);

        return redirect()->route('jadwal-santri.index')
            ->with('success', 'Jadwal santri berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, JadwalSantri $jadwalSantri)
    {
        // Load relasi absensi santri dengan santri
        $jadwalSantri->load(['absensiSantri.santri']);
        
        // Ambil filter bulan dan tahun dari request
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        
        // Filter absensi berdasarkan bulan dan tahun
        $absensiFiltered = $jadwalSantri->absensiSantri->filter(function($item) use ($bulan, $tahun) {
            return $item->tanggal_absensi->month == $bulan && 
                   $item->tanggal_absensi->year == $tahun;
        });
        
        // Group absensi berdasarkan tanggal
        $absensiPerTanggal = $absensiFiltered->groupBy(function($item) {
            return $item->tanggal_absensi->format('Y-m-d');
        })->sortKeysDesc(); // Urutkan tanggal terbaru di atas
        
        return view('jadwal-santri.show', compact('jadwalSantri', 'absensiPerTanggal', 'bulan', 'tahun'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalSantri $jadwalSantri)
    {
        return view('jadwal-santri.edit', compact('jadwalSantri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalSantri $jadwalSantri)
    {
        $validated = $request->validate([
            'nama_jadwal' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_jadwal' => 'required|in:harian,mingguan,bulanan',
            'hari' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'durasi_menit' => 'nullable|integer',
            'tempat' => 'nullable|string|max:255',
            'pembimbing' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $jadwalSantri->update($validated);

        return redirect()->route('jadwal-santri.index')
            ->with('success', 'Jadwal santri berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalSantri $jadwalSantri)
    {
        $jadwalSantri->delete();

        return redirect()->route('jadwal-santri.index')
            ->with('success', 'Jadwal santri berhasil dihapus!');
    }

    /**
     * Display jadwal list for karyawan (READ ONLY)
     */
    public function indexKaryawan()
    {
        $jadwalList = JadwalSantri::orderBy('created_at', 'desc')->get();
        
        // Tambahkan informasi apakah jadwal sedang berlangsung
        foreach ($jadwalList as $jadwal) {
            $jadwal->is_berlangsung = $jadwal->isJadwalBerlangsung();
        }
        
        return view('jadwal-santri.karyawan.index', compact('jadwalList'));
    }

    /**
     * Display jadwal detail for karyawan (READ ONLY)
     */
    public function showKaryawan(Request $request, JadwalSantri $jadwalSantri)
    {
        // Load relasi absensi santri dengan santri
        $jadwalSantri->load(['absensiSantri.santri']);
        
        // Ambil filter bulan dan tahun dari request
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        
        // Filter absensi berdasarkan bulan dan tahun
        $absensiFiltered = $jadwalSantri->absensiSantri->filter(function($item) use ($bulan, $tahun) {
            return $item->tanggal_absensi->month == $bulan && 
                   $item->tanggal_absensi->year == $tahun;
        });
        
        // Group absensi berdasarkan tanggal
        $absensiPerTanggal = $absensiFiltered->groupBy(function($item) {
            return $item->tanggal_absensi->format('Y-m-d');
        })->sortKeysDesc(); // Urutkan tanggal terbaru di atas
        
        return view('jadwal-santri.karyawan.show', compact('jadwalSantri', 'absensiPerTanggal', 'bulan', 'tahun'));
    }
}
