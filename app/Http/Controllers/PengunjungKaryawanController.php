<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengunjung;
use App\Models\Cabang;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PengunjungKaryawanController extends Controller
{
    // Index - Menampilkan daftar pengunjung (READ ONLY untuk karyawan)
    public function index()
    {
        $pengunjung = Pengunjung::with(['cabang'])
            ->orderBy('waktu_checkin', 'desc')
            ->get();
        
        $cabang = Cabang::all();
        
        return view('fasilitas.pengunjung.index-karyawan', compact('pengunjung', 'cabang'));
    }

    // Check-in Pengunjung
    public function checkin(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'no_identitas' => 'nullable|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'keperluan' => 'required|string|max:255',
            'bertemu_dengan' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['kode_pengunjung'] = Pengunjung::generateKodePengunjung();
        $data['waktu_checkin'] = now();
        $data['status'] = 'checkin';

        // Upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'pengunjung_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/pengunjung', $filename);
            $data['foto'] = 'pengunjung/' . $filename;
        }

        $pengunjung = Pengunjung::create($data);

        return redirect()->route('pengunjung.karyawan.index')
            ->with('success', 'Pengunjung berhasil check-in dengan kode: ' . $data['kode_pengunjung']);
    }

    // Check-out Pengunjung
    public function checkout($id)
    {
        $pengunjung = Pengunjung::findOrFail($id);
        
        if ($pengunjung->status === 'checkout') {
            return redirect()->route('pengunjung.karyawan.index')
                ->with('error', 'Pengunjung sudah checkout');
        }

        $pengunjung->update([
            'waktu_checkout' => now(),
            'status' => 'checkout'
        ]);

        return redirect()->route('pengunjung.karyawan.index')
            ->with('success', 'Pengunjung berhasil check-out');
    }

    // Detail Pengunjung (READ ONLY)
    public function show($id)
    {
        $pengunjung = Pengunjung::with(['cabang'])->findOrFail($id);
        
        return view('fasilitas.pengunjung.detail-karyawan', compact('pengunjung'));
    }
}
