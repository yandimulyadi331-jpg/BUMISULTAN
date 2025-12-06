<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Cabang;
use App\Models\Ruangan;
use App\Models\Barang;
use App\Models\TransferBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class GedungController extends Controller
{
    public function index(Request $request)
    {
        $query = Gedung::with(['cabang', 'ruangans']);
        
        if (!empty($request->nama_gedung)) {
            $query->where('nama_gedung', 'like', '%' . $request->nama_gedung . '%');
        }
        
        if (!empty($request->kode_cabang)) {
            $query->where('kode_cabang', $request->kode_cabang);
        }
        
        $query->orderBy('kode_gedung');
        $gedung = $query->paginate(10);
        $gedung->appends(request()->all());
        
        $cabang = Cabang::orderBy('nama_cabang')->get();
        
        return view('fasilitas.gedung.index', compact('gedung', 'cabang'));
    }

    public function create()
    {
        $cabang = Cabang::orderBy('nama_cabang')->get();
        return view('fasilitas.gedung.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gedung' => 'required|max:100',
            'alamat' => 'nullable',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'jumlah_lantai' => 'required|integer|min:1',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            // Auto-generate kode gedung
            $kodeGedung = Gedung::generateKodeGedung();
            
            $data = [
                'kode_gedung' => $kodeGedung,
                'nama_gedung' => $request->nama_gedung,
                'alamat' => $request->alamat,
                'kode_cabang' => $request->kode_cabang,
                'jumlah_lantai' => $request->jumlah_lantai,
                'keterangan' => $request->keterangan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = 'gedung_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('storage/gedung'), $filename);
                $data['foto'] = $filename;
            }

            Gedung::create($data);
            return Redirect::back()->with(messageSuccess('Data Gedung Berhasil Disimpan dengan Kode: ' . $kodeGedung));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $gedung = Gedung::findOrFail($id);
        $cabang = Cabang::orderBy('nama_cabang')->get();
        return view('fasilitas.gedung.edit', compact('gedung', 'cabang'));
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'nama_gedung' => 'required|max:100',
            'alamat' => 'nullable',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'jumlah_lantai' => 'required|integer|min:1',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            $gedung = Gedung::findOrFail($id);
            
            $data = [
                'nama_gedung' => $request->nama_gedung,
                'alamat' => $request->alamat,
                'kode_cabang' => $request->kode_cabang,
                'jumlah_lantai' => $request->jumlah_lantai,
                'keterangan' => $request->keterangan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($gedung->foto && file_exists(public_path('storage/gedung/' . $gedung->foto))) {
                    unlink(public_path('storage/gedung/' . $gedung->foto));
                }
                
                $foto = $request->file('foto');
                $filename = 'gedung_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('storage/gedung'), $filename);
                $data['foto'] = $filename;
            }

            $gedung->update($data);
            return Redirect::back()->with(messageSuccess('Data Gedung Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            $gedung = Gedung::findOrFail($id);
            
            // Check jika masih ada ruangan
            if ($gedung->ruangans()->count() > 0) {
                return Redirect::back()->with(messageError('Tidak dapat menghapus gedung yang masih memiliki ruangan'));
            }
            
            // Delete foto if exists
            if ($gedung->foto && file_exists(public_path('storage/gedung/' . $gedung->foto))) {
                unlink(public_path('storage/gedung/' . $gedung->foto));
            }
            
            $gedung->delete();
            return Redirect::back()->with(messageSuccess('Data Gedung Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $gedung = Gedung::with(['cabang', 'ruangans'])->findOrFail($id);
        return view('fasilitas.gedung.show', compact('gedung'));
    }

    public function exportPDF()
    {
        // Get all data
        $gedung = Gedung::with(['cabang', 'ruangans.barangs'])->orderBy('kode_gedung')->get();
        
        // Get all transfer history
        $transfers = TransferBarang::with(['barang', 'ruanganAsal.gedung', 'ruanganTujuan.gedung'])
            ->orderBy('tanggal_transfer', 'desc')
            ->get();
        
        // Count totals
        $totalGedung = $gedung->count();
        $totalRuangan = Ruangan::count();
        $totalBarang = Barang::count();
        $totalTransfer = $transfers->count();
        
        $data = [
            'gedung' => $gedung,
            'transfers' => $transfers,
            'totalGedung' => $totalGedung,
            'totalRuangan' => $totalRuangan,
            'totalBarang' => $totalBarang,
            'totalTransfer' => $totalTransfer,
            'tanggal' => date('d/m/Y'),
        ];
        
        $pdf = Pdf::loadView('fasilitas.gedung.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan_Fasilitas_Asset_' . date('YmdHis') . '.pdf');
    }

    // Method untuk Karyawan (READ ONLY)
    public function dashboardKaryawan()
    {
        return view('fasilitas.dashboard-karyawan');
    }

    public function indexKaryawan(Request $request)
    {
        $query = Gedung::with([
            'cabang',
            'ruangans' => function($q) {
                $q->select('id', 'gedung_id', 'nama_ruangan', 'kode_ruangan', 'foto', 'kapasitas', 'lantai')
                  ->withCount('barangs as total_barang')
                  ->orderBy('kode_ruangan');
            }
        ])
        ->withCount('ruangans as total_ruangan');
        
        if (!empty($request->nama_gedung)) {
            $query->where('nama_gedung', 'like', '%' . $request->nama_gedung . '%');
        }
        
        if (!empty($request->kode_cabang)) {
            $query->where('kode_cabang', $request->kode_cabang);
        }
        
        $query->orderBy('kode_gedung');
        $gedung = $query->paginate(12); // Increased for grid layout
        
        // Calculate total barang per gedung after loading
        foreach($gedung as $g) {
            $g->total_barang = $g->ruangans->sum('total_barang');
        }
        
        $gedung->appends(request()->all());
        
        $cabang = Cabang::orderBy('nama_cabang')->get();
        
        return view('fasilitas.gedung.index-karyawan', compact('gedung', 'cabang'));
    }
}
