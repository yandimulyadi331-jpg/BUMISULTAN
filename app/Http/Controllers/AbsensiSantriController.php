<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiSantri;
use App\Models\JadwalSantri;
use App\Models\Santri;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiSantriController extends Controller
{
    /**
     * Cek apakah tanggal sudah ada absensi
     */
    public function checkDate(Request $request, $jadwalId)
    {
        $tanggal = $request->get('tanggal');
        
        if (!$tanggal) {
            return response()->json([
                'exists' => false,
                'message' => 'Tanggal tidak valid'
            ]);
        }
        
        $absensi = AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggal)
            ->with('santri')
            ->get();
        
        if ($absensi->count() > 0) {
            return response()->json([
                'exists' => true,
                'tanggal' => $tanggal,
                'tanggal_format' => Carbon::parse($tanggal)->translatedFormat('d F Y'),
                'absensi' => $absensi->map(function($item) {
                    return [
                        'santri_id' => $item->santri_id,
                        'status_kehadiran' => $item->status_kehadiran,
                        'keterangan' => $item->keterangan,
                    ];
                })
            ]);
        }
        
        return response()->json([
            'exists' => false,
            'tanggal' => $tanggal,
            'tanggal_format' => Carbon::parse($tanggal)->translatedFormat('d F Y'),
            'message' => 'Belum ada data absensi'
        ]);
    }

    /**
     * Tampilkan form absensi untuk jadwal tertentu
     */
    public function create($jadwalId)
    {
        $jadwal = JadwalSantri::findOrFail($jadwalId);
        $santriList = Santri::where('status_aktif', true)->orderBy('nama_lengkap')->get();
        
        // Cek apakah sudah ada absensi hari ini untuk jadwal ini
        $tanggalHariIni = Carbon::today()->format('Y-m-d');
        $absensiHariIni = AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggalHariIni)
            ->pluck('santri_id')
            ->toArray();
        
        // Ambil data absensi yang sudah ada
        $absensiData = AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggalHariIni)
            ->get()
            ->keyBy('santri_id');
        
        return view('absensi-santri.create', compact('jadwal', 'santriList', 'absensiHariIni', 'absensiData'));
    }

    /**
     * Simpan absensi santri
     */
    public function store(Request $request, $jadwalId)
    {
        $validated = $request->validate([
            'tanggal_absensi' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,ijin,sakit,khidmat,absen',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        $jadwal = JadwalSantri::findOrFail($jadwalId);
        $tanggal = $validated['tanggal_absensi'];
        $waktu = Carbon::now()->format('H:i:s');

        // Hapus absensi yang sudah ada untuk tanggal ini (jika update)
        AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggal)
            ->delete();

        // Simpan absensi untuk setiap santri
        foreach ($validated['absensi'] as $santriId => $status) {
            AbsensiSantri::create([
                'jadwal_santri_id' => $jadwalId,
                'santri_id' => $santriId,
                'tanggal_absensi' => $tanggal,
                'waktu_absensi' => $waktu,
                'status_kehadiran' => $status,
                'keterangan' => $validated['keterangan'][$santriId] ?? null,
                'dibuat_oleh' => auth()->user()->name,
            ]);
        }

        return redirect()->route('jadwal-santri.show', $jadwalId)
            ->with('success', 'Absensi berhasil disimpan!');
    }

    /**
     * Edit absensi untuk tanggal tertentu
     */
    public function edit($jadwalId, $tanggal)
    {
        $jadwal = JadwalSantri::findOrFail($jadwalId);
        $santriList = Santri::where('status_aktif', true)->orderBy('nama_lengkap')->get();
        
        // Ambil data absensi untuk tanggal ini
        $absensiData = AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggal)
            ->get()
            ->keyBy('santri_id');
        
        $absensiHariIni = $absensiData->pluck('santri_id')->toArray();
        $tanggalAbsensi = $tanggal;
        
        return view('absensi-santri.edit', compact('jadwal', 'santriList', 'absensiData', 'absensiHariIni', 'tanggalAbsensi'));
    }

    /**
     * Update absensi
     */
    public function update(Request $request, $jadwalId, $tanggal)
    {
        $validated = $request->validate([
            'tanggal_absensi' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,ijin,sakit,khidmat,absen',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        $jadwal = JadwalSantri::findOrFail($jadwalId);
        $tanggalBaru = $validated['tanggal_absensi'];
        $waktu = Carbon::now()->format('H:i:s');

        // Hapus absensi yang lama
        AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggal)
            ->delete();

        // Simpan absensi yang baru
        foreach ($validated['absensi'] as $santriId => $status) {
            AbsensiSantri::create([
                'jadwal_santri_id' => $jadwalId,
                'santri_id' => $santriId,
                'tanggal_absensi' => $tanggalBaru,
                'waktu_absensi' => $waktu,
                'status_kehadiran' => $status,
                'keterangan' => $validated['keterangan'][$santriId] ?? null,
                'dibuat_oleh' => auth()->user()->name,
            ]);
        }

        return redirect()->route('jadwal-santri.show', $jadwalId)
            ->with('success', 'Absensi berhasil diperbarui!');
    }

    /**
     * Update absensi per santri (single record)
     */
    public function updateSingle(Request $request, $id)
    {
        $validated = $request->validate([
            'status_kehadiran' => 'required|in:hadir,ijin,sakit,khidmat,absen',
            'keterangan' => 'nullable|string',
        ]);

        $absensi = AbsensiSantri::findOrFail($id);
        $absensi->update([
            'status_kehadiran' => $validated['status_kehadiran'],
            'keterangan' => $validated['keterangan'],
            'waktu_absensi' => Carbon::now()->format('H:i:s'),
        ]);

        return redirect()->route('jadwal-santri.show', $absensi->jadwal_santri_id)
            ->with('success', 'Absensi santri ' . $absensi->santri->nama_lengkap . ' berhasil diperbarui!');
    }

    /**
     * Hapus absensi untuk tanggal tertentu
     */
    public function destroyByDate($jadwalId, $tanggal)
    {
        AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggal)
            ->delete();

        return redirect()->route('jadwal-santri.show', $jadwalId)
            ->with('success', 'Data absensi tanggal ' . Carbon::parse($tanggal)->format('d F Y') . ' berhasil dihapus!');
    }

    /**
     * Tampilkan laporan absensi untuk jadwal tertentu
     */
    public function show($jadwalId)
    {
        $jadwal = JadwalSantri::with(['absensiSantri.santri'])->findOrFail($jadwalId);
        
        // Group absensi berdasarkan tanggal
        $absensiPerTanggal = $jadwal->absensiSantri->groupBy(function($item) {
            return $item->tanggal_absensi->format('Y-m-d');
        });
        
        return view('absensi-santri.show', compact('jadwal', 'absensiPerTanggal'));
    }

    /**
     * Tampilkan laporan rekap absensi
     */
    public function laporan(Request $request)
    {
        $jadwalId = $request->get('jadwal_id');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        $query = AbsensiSantri::with(['santri', 'jadwalSantri'])
            ->whereMonth('tanggal_absensi', $bulan)
            ->whereYear('tanggal_absensi', $tahun);
        
        if ($jadwalId) {
            $query->where('jadwal_santri_id', $jadwalId);
        }
        
        $absensiList = $query->orderBy('tanggal_absensi', 'desc')->get();
        $jadwalList = JadwalSantri::aktif()->get();
        
        // Hitung statistik
        $statistik = [
            'hadir' => $absensiList->where('status_kehadiran', 'hadir')->count(),
            'ijin' => $absensiList->where('status_kehadiran', 'ijin')->count(),
            'sakit' => $absensiList->where('status_kehadiran', 'sakit')->count(),
            'khidmat' => $absensiList->where('status_kehadiran', 'khidmat')->count(),
            'absen' => $absensiList->where('status_kehadiran', 'absen')->count(),
            'total' => $absensiList->count(),
        ];
        
        return view('absensi-santri.laporan', compact('absensiList', 'jadwalList', 'statistik', 'bulan', 'tahun', 'jadwalId'));
    }

    /**
     * Export laporan absensi ke PDF
     */
    public function exportPdf(Request $request)
    {
        $jadwalId = $request->get('jadwal_id');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        $query = AbsensiSantri::with(['santri', 'jadwalSantri'])
            ->whereMonth('tanggal_absensi', $bulan)
            ->whereYear('tanggal_absensi', $tahun);
        
        if ($jadwalId) {
            $query->where('jadwal_santri_id', $jadwalId);
            $jadwal = JadwalSantri::find($jadwalId);
        } else {
            $jadwal = null;
        }
        
        $absensiList = $query->orderBy('tanggal_absensi', 'desc')->get();
        
        // Hitung statistik
        $statistik = [
            'hadir' => $absensiList->where('status_kehadiran', 'hadir')->count(),
            'ijin' => $absensiList->where('status_kehadiran', 'ijin')->count(),
            'sakit' => $absensiList->where('status_kehadiran', 'sakit')->count(),
            'khidmat' => $absensiList->where('status_kehadiran', 'khidmat')->count(),
            'absen' => $absensiList->where('status_kehadiran', 'absen')->count(),
            'total' => $absensiList->count(),
        ];
        
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F');
        
        $pdf = Pdf::loadView('absensi-santri.pdf', compact('absensiList', 'jadwal', 'statistik', 'bulan', 'tahun', 'namaBulan'));
        $pdf->setPaper('a4', 'landscape');
        
        $filename = 'Laporan_Absensi_Santri_' . $namaBulan . '_' . $tahun . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Hapus data absensi
     */
    public function destroy($id)
    {
        $absensi = AbsensiSantri::findOrFail($id);
        $jadwalId = $absensi->jadwal_santri_id;
        $santriNama = $absensi->santri->nama_lengkap;
        $absensi->delete();

        return redirect()->route('jadwal-santri.show', $jadwalId)
            ->with('success', 'Data absensi santri ' . $santriNama . ' berhasil dihapus!');
    }

    /**
     * Display absensi detail for karyawan (READ ONLY)
     */
    public function showKaryawan($jadwalId)
    {
        $jadwal = JadwalSantri::with(['absensiSantri.santri'])->findOrFail($jadwalId);
        
        // Group absensi berdasarkan tanggal
        $absensiPerTanggal = $jadwal->absensiSantri->groupBy(function($item) {
            return $item->tanggal_absensi->format('Y-m-d');
        });
        
        return view('absensi-santri.karyawan.show', compact('jadwal', 'absensiPerTanggal'));
    }

    /**
     * Display laporan absensi for karyawan (READ ONLY)
     */
    public function laporanKaryawan(Request $request)
    {
        $jadwalId = $request->get('jadwal_id');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        $query = AbsensiSantri::with(['santri', 'jadwalSantri'])
            ->whereMonth('tanggal_absensi', $bulan)
            ->whereYear('tanggal_absensi', $tahun);
        
        if ($jadwalId) {
            $query->where('jadwal_santri_id', $jadwalId);
        }
        
        $absensiList = $query->orderBy('tanggal_absensi', 'desc')->get();
        $jadwalList = JadwalSantri::aktif()->get();
        
        // Hitung statistik
        $statistik = [
            'hadir' => $absensiList->where('status_kehadiran', 'hadir')->count(),
            'ijin' => $absensiList->where('status_kehadiran', 'ijin')->count(),
            'sakit' => $absensiList->where('status_kehadiran', 'sakit')->count(),
            'khidmat' => $absensiList->where('status_kehadiran', 'khidmat')->count(),
            'absen' => $absensiList->where('status_kehadiran', 'absen')->count(),
            'total' => $absensiList->count(),
        ];
        
        return view('absensi-santri.karyawan.laporan', compact('absensiList', 'jadwalList', 'statistik', 'bulan', 'tahun', 'jadwalId'));
    }

    /**
     * Tampilkan form absensi untuk karyawan
     */
    public function createKaryawan($jadwalId)
    {
        $jadwal = JadwalSantri::findOrFail($jadwalId);
        $santriList = Santri::where('status_santri', 'aktif')->orderBy('nama_lengkap')->get();
        
        // Cek apakah sudah ada absensi hari ini untuk jadwal ini
        $tanggalHariIni = Carbon::today()->format('Y-m-d');
        $absensiHariIni = AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggalHariIni)
            ->pluck('santri_id')
            ->toArray();
        
        // Ambil data absensi yang sudah ada
        $absensiData = AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggalHariIni)
            ->get()
            ->keyBy('santri_id');
        
        return view('absensi-santri.karyawan.create', compact('jadwal', 'santriList', 'absensiHariIni', 'absensiData'));
    }

    /**
     * Simpan absensi santri dari karyawan
     */
    public function storeKaryawan(Request $request, $jadwalId)
    {
        $validated = $request->validate([
            'tanggal_absensi' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,ijin,sakit,khidmat,absen',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        $jadwal = JadwalSantri::findOrFail($jadwalId);
        $tanggal = $validated['tanggal_absensi'];
        $waktu = Carbon::now()->format('H:i:s');

        // Hapus absensi yang sudah ada untuk tanggal ini (jika update)
        AbsensiSantri::where('jadwal_santri_id', $jadwalId)
            ->whereDate('tanggal_absensi', $tanggal)
            ->delete();

        // Simpan absensi untuk setiap santri
        foreach ($validated['absensi'] as $santriId => $status) {
            AbsensiSantri::create([
                'jadwal_santri_id' => $jadwalId,
                'santri_id' => $santriId,
                'tanggal_absensi' => $tanggal,
                'waktu_absensi' => $waktu,
                'status_kehadiran' => $status,
                'keterangan' => $validated['keterangan'][$santriId] ?? null,
                'dibuat_oleh' => auth()->user()->name,
            ]);
        }

        return redirect()->route('jadwal-santri.karyawan.show', $jadwalId)
            ->with('success', 'Absensi berhasil disimpan!');
    }
}
