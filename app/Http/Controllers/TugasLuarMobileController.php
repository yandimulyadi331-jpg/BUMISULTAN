<?php

namespace App\Http\Controllers;

use App\Models\TugasLuar;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TugasLuarMobileController extends Controller
{
    /**
     * Show the form for creating a new resource from mobile.
     */
    public function create()
    {
        return view('tugasluar.create');
    }

    /**
     * Store a newly created resource in storage from mobile.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'waktu_keluar' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Ambil data user dan karyawan
            $user = User::where('id', auth()->user()->id)->first();
            $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();

            // Cek apakah karyawan sudah presensi
            $presensi = \App\Models\Presensi::where('nik', $userkaryawan->nik)
                ->where('tanggal', $request->tanggal)
                ->where('status', 'h')
                ->first();

            if (!$presensi) {
                return redirect()->back()->with('error', 'Anda belum melakukan presensi hadir untuk tanggal tersebut!')->withInput();
            }

            // Buat tugas luar langsung approved
            $tugasLuar = TugasLuar::create([
                'kode_tugas' => TugasLuar::generateKodeTugas(),
                'karyawan_list' => json_encode([$userkaryawan->nik]),
                'tanggal' => $request->tanggal,
                'tujuan' => $request->tujuan,
                'keterangan' => $request->keterangan,
                'waktu_keluar' => $request->waktu_keluar,
                'status' => 'keluar', // Langsung approved
                'dibuat_oleh' => $user->name,
            ]);

            DB::commit();

            return redirect()->route('pengajuanizin.index')->with('success', 'Tugas luar berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update tugas luar status menjadi kembali
     */
    public function kembali($kode)
    {
        try {
            // Decrypt kode
            $kode_decrypt = \Crypt::decrypt($kode);
            
            // Cari tugas luar berdasarkan kode
            $tugasLuar = TugasLuar::where('kode_tugas', $kode_decrypt)->first();
            
            if (!$tugasLuar) {
                return redirect()->back()->with('error', 'Data tugas luar tidak ditemukan!');
            }

            // Update status menjadi kembali dan set waktu kembali
            $tugasLuar->update([
                'status' => 'kembali',
                'waktu_kembali' => now()->format('H:i:s')
            ]);

            return redirect()->route('pengajuanizin.index')->with('success', 'Status tugas luar berhasil diperbarui menjadi kembali!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
