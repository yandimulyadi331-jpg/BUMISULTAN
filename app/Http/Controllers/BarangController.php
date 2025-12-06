<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Gedung;
use App\Models\TransferBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index($gedung_id, $ruangan_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
            
        $barang = Barang::where('ruangan_id', $ruangan_id)
            ->orderBy('kode_barang')
            ->paginate(15);
            
        return view('fasilitas.barang.index', compact('gedung', 'ruangan', 'barang'));
    }

    public function create($gedung_id, $ruangan_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
            
        return view('fasilitas.barang.create', compact('gedung', 'ruangan'));
    }

    public function store(Request $request, $gedung_id, $ruangan_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        
        $request->validate([
            'nama_barang' => 'required|max:100',
            'kategori' => 'nullable|max:50',
            'merk' => 'nullable|max:50',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|max:20',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_perolehan' => 'nullable|date',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            // Auto-generate kode barang berdasarkan ruangan
            $kodeBarang = Barang::generateKodeBarang($ruangan_id);
            
            $data = [
                'kode_barang' => $kodeBarang,
                'ruangan_id' => $ruangan_id,
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'merk' => $request->merk,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
                'kondisi' => $request->kondisi,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'harga_perolehan' => $request->harga_perolehan,
                'keterangan' => $request->keterangan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = 'barang_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('storage/barang'), $filename);
                $data['foto'] = $filename;
            }

            Barang::create($data);
            return Redirect::back()->with(messageSuccess('Data Barang Berhasil Disimpan dengan Kode: ' . $kodeBarang));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
        $barang = Barang::where('id', $id)
            ->where('ruangan_id', $ruangan_id)
            ->firstOrFail();
            
        return view('fasilitas.barang.edit', compact('gedung', 'ruangan', 'barang'));
    }

    public function update(Request $request, $gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $request->validate([
            'nama_barang' => 'required|max:100',
            'kategori' => 'nullable|max:50',
            'merk' => 'nullable|max:50',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|max:20',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_perolehan' => 'nullable|date',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            $barang = Barang::where('id', $id)->where('ruangan_id', $ruangan_id)->firstOrFail();
            
            $data = [
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'merk' => $request->merk,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
                'kondisi' => $request->kondisi,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'harga_perolehan' => $request->harga_perolehan,
                'keterangan' => $request->keterangan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($barang->foto && file_exists(public_path('storage/barang/' . $barang->foto))) {
                    unlink(public_path('storage/barang/' . $barang->foto));
                }
                
                $foto = $request->file('foto');
                $filename = 'barang_' . time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('storage/barang'), $filename);
                $data['foto'] = $filename;
            }

            $barang->update($data);
            return Redirect::back()->with(messageSuccess('Data Barang Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        try {
            $barang = Barang::where('id', $id)
                ->where('ruangan_id', $ruangan_id)
                ->firstOrFail();
            
            // Delete foto if exists
            if ($barang->foto && file_exists(public_path('storage/barang/' . $barang->foto))) {
                unlink(public_path('storage/barang/' . $barang->foto));
            }
            
            $barang->delete();
            return Redirect::back()->with(messageSuccess('Data Barang Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function transfer($gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
        $barang = Barang::where('id', $id)
            ->where('ruangan_id', $ruangan_id)
            ->with('ruangan.gedung')
            ->firstOrFail();
            
        // Get all ruangan except current ruangan
        $all_ruangan = Ruangan::with('gedung')
            ->where('id', '!=', $ruangan_id)
            ->orderBy('kode_ruangan')
            ->get();
            
        return view('fasilitas.barang.transfer', compact('gedung', 'ruangan', 'barang', 'all_ruangan'));
    }

    public function prosesTransfer(Request $request, $gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $request->validate([
            'ruangan_tujuan_id' => 'required|exists:ruangans,id',
            'jumlah_transfer' => 'required|integer|min:1',
            'tanggal_transfer' => 'required|date',
            'petugas' => 'nullable|max:100',
            'keterangan' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            
            $barang = Barang::where('id', $id)
                ->where('ruangan_id', $ruangan_id)
                ->firstOrFail();
                
            // Validasi jumlah
            if ($request->jumlah_transfer > $barang->jumlah) {
                return Redirect::back()->with(messageError('Jumlah transfer melebihi stok barang yang tersedia'));
            }
            
            // Generate kode transfer
            $tanggal = date('Ymd');
            $lastTransfer = TransferBarang::whereDate('created_at', date('Y-m-d'))->count();
            $kode_transfer = 'TRF-' . $tanggal . '-' . str_pad($lastTransfer + 1, 4, '0', STR_PAD_LEFT);
            
            // Create transfer record
            TransferBarang::create([
                'kode_transfer' => $kode_transfer,
                'barang_id' => $id,
                'ruangan_asal_id' => $ruangan_id,
                'ruangan_tujuan_id' => $request->ruangan_tujuan_id,
                'jumlah_transfer' => $request->jumlah_transfer,
                'tanggal_transfer' => $request->tanggal_transfer,
                'petugas' => $request->petugas,
                'keterangan' => $request->keterangan,
            ]);
            
            // Update jumlah barang di ruangan asal
            $barang->decrement('jumlah', $request->jumlah_transfer);
            
            // Check apakah barang sudah ada di ruangan tujuan
            $barangTujuan = Barang::where('kode_barang', $barang->kode_barang)
                ->where('ruangan_id', $request->ruangan_tujuan_id)
                ->first();
                
            if ($barangTujuan) {
                // Jika sudah ada, tambahkan jumlahnya
                $barangTujuan->increment('jumlah', $request->jumlah_transfer);
            } else {
                // Jika belum ada, buat barang baru di ruangan tujuan
                Barang::create([
                    'kode_barang' => $barang->kode_barang . '-' . time(), // Tambah timestamp untuk unique
                    'ruangan_id' => $request->ruangan_tujuan_id,
                    'nama_barang' => $barang->nama_barang,
                    'kategori' => $barang->kategori,
                    'merk' => $barang->merk,
                    'jumlah' => $request->jumlah_transfer,
                    'satuan' => $barang->satuan,
                    'kondisi' => $barang->kondisi,
                    'tanggal_perolehan' => $barang->tanggal_perolehan,
                    'harga_perolehan' => $barang->harga_perolehan,
                    'keterangan' => 'Transfer dari ' . $barang->ruangan->nama_ruangan,
                ]);
            }
            
            // Jika jumlah di ruangan asal menjadi 0, hapus barang
            if ($barang->jumlah == 0) {
                $barang->delete();
            }
            
            DB::commit();
            return Redirect::route('barang.index', [
                'gedung_id' => Crypt::encrypt($gedung_id),
                'ruangan_id' => Crypt::encrypt($ruangan_id)
            ])->with(messageSuccess('Transfer Barang Berhasil'));
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function riwayatTransfer($gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
        $barang = Barang::where('id', $id)
            ->where('ruangan_id', $ruangan_id)
            ->firstOrFail();
            
        $riwayat = TransferBarang::where('barang_id', $id)
            ->with(['ruanganAsal.gedung', 'ruanganTujuan.gedung'])
            ->orderBy('tanggal_transfer', 'desc')
            ->paginate(15);
            
        return view('fasilitas.barang.riwayat', compact('gedung', 'ruangan', 'barang', 'riwayat'));
    }

    // Method untuk Karyawan (READ ONLY)
    public function indexKaryawan($gedung_id, $ruangan_id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
            
        $barang = Barang::where('ruangan_id', $ruangan_id)
            ->orderBy('kode_barang')
            ->paginate(15);
            
        // Get all ruangan for transfer dropdown
        $all_ruangan = Ruangan::with('gedung')
            ->where('id', '!=', $ruangan_id)
            ->orderBy('nama_ruangan')
            ->get();
            
        return view('fasilitas.barang.index-karyawan', compact('gedung', 'ruangan', 'barang', 'all_ruangan'));
    }

    public function transferKaryawan($gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
        $barang = Barang::where('id', $id)
            ->where('ruangan_id', $ruangan_id)
            ->with('ruangan.gedung')
            ->firstOrFail();
            
        // Get all ruangan except current ruangan
        $all_ruangan = Ruangan::with('gedung')
            ->where('id', '!=', $ruangan_id)
            ->orderBy('kode_ruangan')
            ->get();
            
        return view('fasilitas.barang.transfer-karyawan', compact('gedung', 'ruangan', 'barang', 'all_ruangan'));
    }

    public function prosesTransferKaryawan(Request $request, $gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $request->validate([
            'ruangan_tujuan_id' => 'required|exists:ruangans,id',
            'jumlah_transfer' => 'required|integer|min:1',
            'tanggal_transfer' => 'required|date',
            'petugas' => 'nullable|max:100',
            'keterangan' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            
            $barang = Barang::where('id', $id)
                ->where('ruangan_id', $ruangan_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Generate kode transfer
            $tanggal = date('Ymd');
            $lastTransfer = TransferBarang::whereDate('created_at', date('Y-m-d'))->count();
            $kode_transfer = 'TRF-' . $tanggal . '-' . str_pad($lastTransfer + 1, 4, '0', STR_PAD_LEFT);

            // Create transfer record
            TransferBarang::create([
                'kode_transfer' => $kode_transfer,
                'barang_id' => $barang->id,
                'ruangan_asal_id' => $ruangan_id,
                'ruangan_tujuan_id' => $request->ruangan_tujuan_id,
                'jumlah_transfer' => $request->jumlah_transfer,
                'tanggal_transfer' => $request->tanggal_transfer,
                'petugas' => $request->petugas ?? auth()->user()->name,
                'keterangan' => $request->keterangan,
            ]);

            // Update barang ruangan
            $barang->update([
                'ruangan_id' => $request->ruangan_tujuan_id,
            ]);

            DB::commit();

            return redirect()
                ->route('barang.karyawan', [
                    'gedung_id' => Crypt::encrypt($gedung_id),
                    'ruangan_id' => Crypt::encrypt($ruangan_id)
                ])
                ->with(['success' => 'Barang berhasil ditransfer']);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(['error' => 'Gagal transfer barang: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function riwayatTransferKaryawan($gedung_id, $ruangan_id, $id)
    {
        $gedung_id = Crypt::decrypt($gedung_id);
        $ruangan_id = Crypt::decrypt($ruangan_id);
        $id = Crypt::decrypt($id);
        
        $gedung = Gedung::findOrFail($gedung_id);
        $ruangan = Ruangan::where('id', $ruangan_id)
            ->where('gedung_id', $gedung_id)
            ->firstOrFail();
        $barang = Barang::where('id', $id)
            ->where('ruangan_id', $ruangan_id)
            ->firstOrFail();
            
        $riwayat = TransferBarang::where('barang_id', $id)
            ->with(['ruanganAsal.gedung', 'ruanganTujuan.gedung'])
            ->orderBy('tanggal_transfer', 'desc')
            ->paginate(15);
            
        return view('fasilitas.barang.riwayat-karyawan', compact('gedung', 'ruangan', 'barang', 'riwayat'));
    }
}
