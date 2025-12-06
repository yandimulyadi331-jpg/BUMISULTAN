<?php

namespace App\Http\Controllers;

use App\Models\Tukang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class TukangController extends Controller
{
    public function index(Request $request)
    {
        $query = Tukang::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tukang', 'like', '%' . $search . '%')
                  ->orWhere('kode_tukang', 'like', '%' . $search . '%')
                  ->orWhere('keahlian', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        $data['tukangs'] = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('manajemen-tukang.data-tukang.index', $data);
    }

    public function create()
    {
        // Generate kode tukang otomatis
        $data['kode_tukang'] = $this->generateKodeTukang();
        return view('manajemen-tukang.data-tukang.create', $data);
    }
    
    /**
     * Generate kode tukang otomatis dengan format TK001, TK002, dst
     */
    private function generateKodeTukang()
    {
        // Ambil kode terakhir
        $lastTukang = Tukang::orderBy('id', 'desc')->first();
        
        if (!$lastTukang) {
            // Jika belum ada data, mulai dari TK001
            return 'TK001';
        }
        
        // Ambil nomor dari kode terakhir
        $lastKode = $lastTukang->kode_tukang;
        
        // Ekstrak angka dari kode (misal: TK001 -> 001)
        preg_match('/\d+/', $lastKode, $matches);
        
        if (isset($matches[0])) {
            $lastNumber = (int)$matches[0];
            $newNumber = $lastNumber + 1;
            
            // Format dengan 3 digit (001, 002, dst)
            return 'TK' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }
        
        // Fallback jika format tidak sesuai
        return 'TK001';
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tukang' => 'required|max:100',
            'nik' => 'nullable|max:20',
            'alamat' => 'nullable',
            'no_hp' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'keahlian' => 'nullable|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'tarif_harian' => 'nullable|numeric',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        try {
            // Generate kode tukang otomatis jika kosong
            $kodeTukang = $request->kode_tukang ?: $this->generateKodeTukang();
            
            $data = [
                'kode_tukang' => $kodeTukang,
                'nama_tukang' => $request->nama_tukang,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'keahlian' => $request->keahlian,
                'status' => $request->status,
                'tarif_harian' => $request->tarif_harian,
                'keterangan' => $request->keterangan
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = 'tukang_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/tukang', $filename);
                $data['foto'] = $filename;
            }

            Tukang::create($data);

            return redirect()->route('tukang.index')->with(messageSuccess('Data Tukang Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()))->withInput();
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $data['tukang'] = Tukang::findOrFail($id);
        return view('manajemen-tukang.data-tukang.show', $data);
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['tukang'] = Tukang::findOrFail($id);
        return view('manajemen-tukang.data-tukang.edit', $data);
    }

    public function update($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        $tukang = Tukang::findOrFail($id);

        $request->validate([
            'nama_tukang' => 'required|max:100',
            'nik' => 'nullable|max:20',
            'alamat' => 'nullable',
            'no_hp' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'keahlian' => 'nullable|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'tarif_harian' => 'nullable|numeric',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        try {
            $data = [
                'nama_tukang' => $request->nama_tukang,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'keahlian' => $request->keahlian,
                'status' => $request->status,
                'tarif_harian' => $request->tarif_harian,
                'keterangan' => $request->keterangan
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($tukang->foto && Storage::exists('public/tukang/' . $tukang->foto)) {
                    Storage::delete('public/tukang/' . $tukang->foto);
                }
                
                $file = $request->file('foto');
                $filename = 'tukang_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/tukang', $filename);
                $data['foto'] = $filename;
            }

            $tukang->update($data);

            return redirect()->route('tukang.index')->with(messageSuccess('Data Tukang Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()))->withInput();
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            $tukang = Tukang::findOrFail($id);
            
            // Delete foto if exists
            if ($tukang->foto && Storage::exists('public/tukang/' . $tukang->foto)) {
                Storage::delete('public/tukang/' . $tukang->foto);
            }
            
            $tukang->delete();
            
            return Redirect::back()->with(messageSuccess('Data Tukang Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    // ========================================
    // METHODS FOR KARYAWAN (READ-ONLY)
    // ========================================
    
    /**
     * Tampilkan daftar tukang untuk karyawan (Read-Only)
     */
    public function indexKaryawan(Request $request)
    {
        $query = Tukang::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tukang', 'like', '%' . $search . '%')
                  ->orWhere('kode_tukang', 'like', '%' . $search . '%')
                  ->orWhere('keahlian', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        $data['tukangs'] = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('manajemen-tukang.karyawan.index', $data);
    }

    /**
     * Tampilkan detail tukang untuk karyawan (Read-Only)
     */
    public function showKaryawan($id)
    {
        $id = Crypt::decrypt($id);
        $data['tukang'] = Tukang::findOrFail($id);
        return view('manajemen-tukang.karyawan.show', $data);
    }
}
