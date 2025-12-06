<?php

namespace App\Http\Controllers;

use App\Models\TugasLuar;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TugasLuarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TugasLuar::query();

        // Filter berdasarkan tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', date('Y-m-d'));
        }

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tugasLuar = $query->orderBy('waktu_keluar', 'desc')->get();

        return view('tugas-luar.index', compact('tugasLuar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil karyawan yang hadir hari ini
        $karyawanHadir = Karyawan::whereIn('nik', function($query) {
            $query->select('nik')
                  ->from('presensi')
                  ->where('tanggal', date('Y-m-d'))
                  ->where('status', 'h');
        })
        ->with(['departemen', 'jabatan'])
        ->orderBy('nama_karyawan')
        ->get();

        return view('tugas-luar.create', compact('karyawanHadir'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_list' => 'required|array|min:1',
            'karyawan_list.*' => 'exists:karyawan,nik',
            'tanggal' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'waktu_keluar' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah semua karyawan sudah hadir
            foreach ($request->karyawan_list as $nik) {
                $presensi = Presensi::where('nik', $nik)
                    ->where('tanggal', $request->tanggal)
                    ->where('status', 'h')
                    ->first();

                if (!$presensi) {
                    $karyawan = Karyawan::where('nik', $nik)->first();
                    return redirect()->back()->with('error', 'Karyawan ' . $karyawan->nama_karyawan . ' belum melakukan presensi hadir!')->withInput();
                }
            }

            $tugasLuar = TugasLuar::create([
                'kode_tugas' => TugasLuar::generateKodeTugas(),
                'karyawan_list' => json_encode($request->karyawan_list),
                'tanggal' => $request->tanggal,
                'tujuan' => $request->tujuan,
                'keterangan' => $request->keterangan,
                'waktu_keluar' => $request->waktu_keluar,
                'status' => 'keluar',
                'dibuat_oleh' => Auth::user()->name,
            ]);

            DB::commit();

            return redirect()->route('tugas-luar.index')->with('success', 'Tugas luar berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status tugas luar menjadi kembali
     */
    public function kembali($id)
    {
        try {
            $tugasLuar = TugasLuar::findOrFail($id);
            $tugasLuar->update([
                'status' => 'kembali',
                'waktu_kembali' => date('H:i:s'),
            ]);

            return redirect()->back()->with('success', 'Karyawan telah kembali dari tugas luar!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $tugasLuar = TugasLuar::findOrFail($id);
            $tugasLuar->delete();

            return redirect()->back()->with('success', 'Data tugas luar berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Approve tugas luar (untuk pengajuan dari mobile)
     */
    public function approve($id)
    {
        try {
            $tugasLuar = TugasLuar::findOrFail($id);
            $tugasLuar->update([
                'status' => 'keluar',
            ]);

            return redirect()->back()->with('success', 'Pengajuan tugas luar telah disetujui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject tugas luar (untuk pengajuan dari mobile)
     */
    public function reject($id)
    {
        try {
            $tugasLuar = TugasLuar::findOrFail($id);
            $tugasLuar->delete();

            return redirect()->back()->with('success', 'Pengajuan tugas luar telah ditolak dan dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
