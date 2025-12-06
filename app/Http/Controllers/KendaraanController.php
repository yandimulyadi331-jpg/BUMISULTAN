<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kendaraan::with(['cabang', 'aktivitasAktif', 'peminjamanAktif', 'serviceAktif']);
        
        if (!empty($request->nama_kendaraan)) {
            $query->where('nama_kendaraan', 'like', '%' . $request->nama_kendaraan . '%');
        }
        
        if (!empty($request->jenis_kendaraan)) {
            $query->where('jenis_kendaraan', $request->jenis_kendaraan);
        }
        
        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        $query->orderBy('kode_kendaraan');
        $kendaraan = $query->paginate(15);
        $kendaraan->appends(request()->all());
        
        $cabang = Cabang::orderBy('nama_cabang')->get();
        
        return view('kendaraan.index', compact('kendaraan', 'cabang'));
    }

    public function create()
    {
        $cabang = Cabang::orderBy('nama_cabang')->get();
        return view('kendaraan.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kendaraan' => 'required|max:100',
            'jenis_kendaraan' => 'required|in:Mobil,Motor,Truk,Bus,Lainnya',
            'no_polisi' => 'required|max:20|unique:kendaraans,no_polisi',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            // Auto-generate kode kendaraan berdasarkan jenis
            $kodeKendaraan = Kendaraan::generateKodeKendaraan($request->jenis_kendaraan);
            
            $data = $request->all();
            $data['kode_kendaraan'] = $kodeKendaraan;
            
            // Handle foto upload
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = 'kendaraan_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/kendaraan'), $filename);
                $data['foto'] = $filename;
            }
            
            Kendaraan::create($data);
            return Redirect::back()->with(messageSuccess('Data Kendaraan Berhasil Disimpan dengan Kode: ' . $kodeKendaraan));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kendaraan = Kendaraan::findOrFail($id);
        $cabang = Cabang::orderBy('nama_cabang')->get();
        return view('kendaraan.edit', compact('kendaraan', 'cabang'));
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $kendaraan = Kendaraan::findOrFail($id);
        
        $request->validate([
            'kode_kendaraan' => 'required|max:20|unique:kendaraans,kode_kendaraan,' . $id,
            'nama_kendaraan' => 'required|max:100',
            'jenis_kendaraan' => 'required|in:Mobil,Motor,Truk,Bus,Lainnya',
            'no_polisi' => 'required|max:20|unique:kendaraans,no_polisi,' . $id,
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            $data = $request->all();
            
            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($kendaraan->foto && file_exists(public_path('storage/kendaraan/' . $kendaraan->foto))) {
                    unlink(public_path('storage/kendaraan/' . $kendaraan->foto));
                }
                
                $file = $request->file('foto');
                $filename = 'kendaraan_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/kendaraan'), $filename);
                $data['foto'] = $filename;
            }
            
            $kendaraan->update($data);
            return Redirect::back()->with(messageSuccess('Data Kendaraan Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        
        try {
            $kendaraan = Kendaraan::findOrFail($id);
            
            // Delete foto if exists
            if ($kendaraan->foto && file_exists(public_path('storage/kendaraan/' . $kendaraan->foto))) {
                unlink(public_path('storage/kendaraan/' . $kendaraan->foto));
            }
            
            $kendaraan->delete();
            return Redirect::back()->with(messageSuccess('Data Kendaraan Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $kendaraan = Kendaraan::with([
                'cabang', 
                'aktivitas' => function($q) { $q->latest()->limit(10); },
                'peminjaman' => function($q) { $q->latest()->limit(10); },
                'services' => function($q) { $q->latest()->limit(10); },
                'jadwalServices',
                'aktivitasAktif',
                'peminjamanAktif',
                'serviceAktif'
            ])->findOrFail($id);
            
            return view('kendaraan.detail', compact('kendaraan'));
        } catch (\Exception $e) {
            \Log::error('Error in KendaraanController@show: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return Redirect::back()->with(messageError('Terjadi kesalahan saat menampilkan data kendaraan'));
        }
    }
}
