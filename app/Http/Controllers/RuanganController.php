<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class RuanganController extends Controller
{
    public function index($gedung_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('gedung_id', $gedung_id)
            ->with('barangs')
            ->orderBy('kode_ruangan')
            ->paginate(15);
            
        return view('fasilitas.ruangan.index', compact('gedung', 'ruangan'));
    }

    public function create($gedung_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $gedung = Gedung::findOrFail($gedung_id);
        return view('fasilitas.ruangan.create', compact('gedung'));
    }

    public function store(Request $request, $gedung_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        
        $request->validate([
            'nama_ruangan' => 'required|max:100',
            'lantai' => 'nullable|max:10',
            'luas' => 'nullable|numeric|min:0',
            'kapasitas' => 'nullable|integer|min:0',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            // Auto-generate kode ruangan berdasarkan gedung
            $kodeRuangan = Ruangan::generateKodeRuangan($gedung_id);
            
            $data = [
                'kode_ruangan' => $kodeRuangan,
                'gedung_id' => $gedung_id,
                'nama_ruangan' => $request->nama_ruangan,
                'lantai' => $request->lantai,
                'luas' => $request->luas,
                'kapasitas' => $request->kapasitas,
                'keterangan' => $request->keterangan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = 'ruangan_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('storage/ruangan'), $filename);
                $data['foto'] = $filename;
            }

            Ruangan::create($data);
            return Redirect::back()->with(messageSuccess('Data Ruangan Berhasil Disimpan dengan Kode: ' . $kodeRuangan));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($gedung_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $id = Crypt::decrypt($id);
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $id)->where('gedung_id', $gedung_id)->firstOrFail();
        return view('fasilitas.ruangan.edit', compact('gedung', 'ruangan'));
    }

    public function update(Request $request, $gedung_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $id = Crypt::decrypt($id);
        
        $request->validate([
            'nama_ruangan' => 'required|max:100',
            'lantai' => 'nullable|max:10',
            'luas' => 'nullable|numeric|min:0',
            'kapasitas' => 'nullable|integer|min:0',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            $ruangan = Ruangan::where('id', $id)->where('gedung_id', $gedung_id)->firstOrFail();
            
            $data = [
                'nama_ruangan' => $request->nama_ruangan,
                'lantai' => $request->lantai,
                'luas' => $request->luas,
                'kapasitas' => $request->kapasitas,
                'keterangan' => $request->keterangan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($ruangan->foto && file_exists(public_path('storage/ruangan/' . $ruangan->foto))) {
                    unlink(public_path('storage/ruangan/' . $ruangan->foto));
                }
                
                $foto = $request->file('foto');
                $filename = 'ruangan_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('storage/ruangan'), $filename);
                $data['foto'] = $filename;
            }

            $ruangan->update($data);
            return Redirect::back()->with(messageSuccess('Data Ruangan Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($gedung_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $id = Crypt::decrypt($id);
        
        try {
            $ruangan = Ruangan::where('id', $id)->where('gedung_id', $gedung_id)->firstOrFail();
            
            // Check jika masih ada barang
            if ($ruangan->barangs()->count() > 0) {
                return Redirect::back()->with(messageError('Tidak dapat menghapus ruangan yang masih memiliki barang'));
            }
            
            // Delete foto if exists
            if ($ruangan->foto && file_exists(public_path('storage/ruangan/' . $ruangan->foto))) {
                unlink(public_path('storage/ruangan/' . $ruangan->foto));
            }
            
            $ruangan->delete();
            return Redirect::back()->with(messageSuccess('Data Ruangan Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    // Method untuk Karyawan (READ ONLY)
    public function indexKaryawan($gedung_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('gedung_id', $gedung_id)
            ->withCount('barangs as total_barang')
            ->orderBy('kode_ruangan')
            ->get();
            
        return view('fasilitas.ruangan.index-karyawan', compact('gedung', 'ruangan'));
    }
}
