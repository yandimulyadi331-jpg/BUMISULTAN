<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalKhidmat;
use App\Models\PetugasKhidmat;
use App\Models\BelanjaKhidmat;
use App\Models\FotoBelanjaKhidmat;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhidmatTemplateExport;
use App\Imports\KhidmatBelanjaImport;

class KhidmatController extends Controller
{
    /**
     * Display a listing of jadwal khidmat
     */
    public function index()
    {
        // Auto-generate jadwal seminggu jika belum ada atau semua sudah selesai
        $this->autoGenerateJadwal();

        // Hanya tampilkan 7 jadwal terbaru (jadwal aktif saat ini)
        $jadwal = JadwalKhidmat::with(['petugas.santri', 'belanja', 'foto'])
            ->orderBy('tanggal_jadwal', 'desc')
            ->limit(7)
            ->get();

        return view('khidmat.index', compact('jadwal'));
    }

    /**
     * Auto-generate jadwal seminggu penuh
     */
    private function autoGenerateJadwal()
    {
        // Cek apakah ada jadwal yang belum selesai
        $jadwalBelumSelesai = JadwalKhidmat::where('status_selesai', false)->count();
        
        if ($jadwalBelumSelesai > 0) {
            return; // Masih ada jadwal yang belum selesai, tidak perlu generate
        }

        // Cari tanggal terakhir
        $jadwalTerakhir = JadwalKhidmat::orderBy('tanggal_jadwal', 'desc')->first();
        $tanggalMulai = $jadwalTerakhir 
            ? $jadwalTerakhir->tanggal_jadwal->addDay() 
            : now();

        // Ambil saldo akhir dari jadwal terakhir
        $saldoAwal = $jadwalTerakhir ? $jadwalTerakhir->saldo_akhir : 0;

        // Generate 7 jadwal (Senin - Minggu)
        $namaHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        DB::beginTransaction();
        try {
            for ($i = 0; $i < 7; $i++) {
                $tanggal = $tanggalMulai->copy()->addDays($i);
                $hariIndex = ($tanggal->dayOfWeek + 6) % 7; // Convert to 0=Senin
                
                JadwalKhidmat::create([
                    'nama_kelompok' => 'Kelompok ' . $namaHari[$hariIndex],
                    'tanggal_jadwal' => $tanggal,
                    'status_kebersihan' => 'bersih',
                    'status_selesai' => false,
                    'saldo_awal' => $saldoAwal,
                    'saldo_masuk' => 0,
                    'total_belanja' => 0,
                    'saldo_akhir' => $saldoAwal,
                    'keterangan' => 'Auto-generated'
                ]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Show the form for creating a new jadwal
     */
    public function create()
    {
        $santri = Santri::orderBy('nama_lengkap', 'asc')->get();

        // Get saldo akhir dari jadwal terakhir untuk saldo awal
        $jadwalTerakhir = JadwalKhidmat::orderBy('tanggal_jadwal', 'desc')->first();
        $saldoAwal = $jadwalTerakhir ? $jadwalTerakhir->saldo_akhir : 0;

        return view('khidmat.create', compact('santri', 'saldoAwal'));
    }

    /**
     * Store a newly created jadwal in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'tanggal_jadwal' => 'required|date',
            'saldo_masuk' => 'nullable|numeric|min:0',
            'petugas' => 'required|array|min:3',
            'petugas.*' => 'required|exists:santri,id',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Get saldo awal dari jadwal terakhir
            $jadwalTerakhir = JadwalKhidmat::orderBy('tanggal_jadwal', 'desc')->first();
            $saldoAwal = $jadwalTerakhir ? $jadwalTerakhir->saldo_akhir : 0;

            $saldoMasuk = $request->saldo_masuk ?? 0;
            $saldoAkhir = $saldoAwal + $saldoMasuk;

            // Create jadwal
            $jadwal = JadwalKhidmat::create([
                'nama_kelompok' => $request->nama_kelompok,
                'tanggal_jadwal' => $request->tanggal_jadwal,
                'status_kebersihan' => 'bersih',
                'saldo_awal' => $saldoAwal,
                'saldo_masuk' => $saldoMasuk,
                'total_belanja' => 0,
                'saldo_akhir' => $saldoAkhir,
                'keterangan' => $request->keterangan
            ]);

            // Add petugas
            foreach ($request->petugas as $petugasId) {
                PetugasKhidmat::create([
                    'jadwal_khidmat_id' => $jadwal->id,
                    'santri_id' => $petugasId
                ]);
            }

            DB::commit();
            return redirect()->route('khidmat.index')
                ->with('success', 'Jadwal khidmat berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified jadwal (Laporan Lengkap)
     */
    public function show($id)
    {
        $jadwal = JadwalKhidmat::with(['petugas.santri', 'belanja', 'foto'])
            ->findOrFail($id);

        // Gunakan view laporan-view untuk tampilan lengkap
        return view('khidmat.laporan-view', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified jadwal
     */
    public function edit($id)
    {
        $jadwal = JadwalKhidmat::with(['petugas'])->findOrFail($id);
        $santri = Santri::orderBy('nama_lengkap', 'asc')->get();

        return view('khidmat.edit', compact('jadwal', 'santri'));
    }

    /**
     * Update the specified jadwal in storage
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'tanggal_jadwal' => 'required|date',
            'saldo_masuk' => 'nullable|numeric|min:0',
            'petugas' => 'required|array|min:3',
            'petugas.*' => 'required|exists:santri,id',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $jadwal = JadwalKhidmat::findOrFail($id);

            // Update saldo_masuk dan recalculate saldo_akhir
            $saldoMasuk = $request->saldo_masuk ?? $jadwal->saldo_masuk;
            $saldoAkhir = ($jadwal->saldo_awal + $saldoMasuk) - $jadwal->total_belanja;

            // Update jadwal
            $jadwal->update([
                'nama_kelompok' => $request->nama_kelompok,
                'tanggal_jadwal' => $request->tanggal_jadwal,
                'saldo_masuk' => $saldoMasuk,
                'saldo_akhir' => $saldoAkhir,
                'keterangan' => $request->keterangan
            ]);

            // Update petugas - delete old and add new
            PetugasKhidmat::where('jadwal_khidmat_id', $jadwal->id)->delete();
            foreach ($request->petugas as $petugasId) {
                PetugasKhidmat::create([
                    'jadwal_khidmat_id' => $jadwal->id,
                    'santri_id' => $petugasId
                ]);
            }

            // Update next jadwal saldo awal
            $this->updateNextJadwalSaldo($jadwal);

            DB::commit();
            return redirect()->route('khidmat.index')
                ->with('success', 'Jadwal khidmat berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified jadwal from storage
     */
    public function destroy($id)
    {
        try {
            $jadwal = JadwalKhidmat::findOrFail($id);

            // Delete related photos from storage
            foreach ($jadwal->foto as $foto) {
                if (Storage::exists($foto->path_file)) {
                    Storage::delete($foto->path_file);
                }
            }

            $jadwal->delete();

            return redirect()->route('khidmat.index')
                ->with('success', 'Jadwal khidmat berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Search jadwal lama (AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = JadwalKhidmat::with(['petugas.santri', 'belanja', 'foto']);

        // Filter pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_kelompok', 'like', "%{$search}%")
                  ->orWhere('tanggal_jadwal', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($status === 'selesai') {
            $query->where('status_selesai', true);
        } elseif ($status === 'belum') {
            $query->where('status_selesai', false);
        }

        $jadwal = $query->orderBy('tanggal_jadwal', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    /**
     * Update status kebersihan
     */
    public function updateKebersihan(Request $request, $id)
    {
        try {
            $jadwal = JadwalKhidmat::findOrFail($id);
            $jadwal->update([
                'status_kebersihan' => $request->status_kebersihan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status kebersihan berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status kebersihan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status selesai jadwal
     */
    public function toggleSelesai(Request $request, $id)
    {
        try {
            $jadwal = JadwalKhidmat::findOrFail($id);
            $statusBaru = !$jadwal->status_selesai;
            
            $jadwal->update([
                'status_selesai' => $statusBaru
            ]);

            // Cek apakah semua jadwal sudah selesai
            $jadwalBelumSelesai = JadwalKhidmat::where('status_selesai', false)->count();
            
            $message = $statusBaru 
                ? 'Jadwal ditandai selesai' 
                : 'Jadwal ditandai belum selesai';

            return response()->json([
                'success' => true,
                'message' => $message,
                'status_selesai' => $statusBaru,
                'all_completed' => $jadwalBelumSelesai === 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show laporan keuangan form (untuk input/edit belanja)
     */
    public function laporan($id)
    {
        $jadwal = JadwalKhidmat::with(['petugas.santri', 'belanja', 'foto'])
            ->findOrFail($id);

        return view('khidmat.laporan', compact('jadwal'));
    }

    /**
     * Store belanja items
     */
    public function storeBelanja(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $jadwal = JadwalKhidmat::findOrFail($id);
            $totalBelanja = 0;

            // Delete existing belanja items
            BelanjaKhidmat::where('jadwal_khidmat_id', $jadwal->id)->delete();

            // Add new belanja items
            foreach ($request->items as $item) {
                $totalHarga = $item['jumlah'] * $item['harga_satuan'];
                $totalBelanja += $totalHarga;

                BelanjaKhidmat::create([
                    'jadwal_khidmat_id' => $jadwal->id,
                    'nama_barang' => $item['nama_barang'],
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_harga' => $totalHarga,
                    'keterangan' => $item['keterangan'] ?? null
                ]);
            }

            // Update jadwal with new totals
            $saldoAkhir = ($jadwal->saldo_awal + $jadwal->saldo_masuk) - $totalBelanja;
            $jadwal->update([
                'total_belanja' => $totalBelanja,
                'saldo_akhir' => $saldoAkhir
            ]);

            // Update saldo awal of next jadwal if exists
            $this->updateNextJadwalSaldo($jadwal);

            DB::commit();
            return redirect()->route('khidmat.laporan', $jadwal->id)
                ->with('success', 'Laporan belanja berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan laporan belanja: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload foto belanja
     */
    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'keterangan' => 'nullable|string'
        ]);

        try {
            $jadwal = JadwalKhidmat::findOrFail($id);

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('khidmat/belanja', $fileName, 'public');

                FotoBelanjaKhidmat::create([
                    'jadwal_khidmat_id' => $jadwal->id,
                    'nama_file' => $fileName,
                    'path_file' => $path,
                    'keterangan' => $request->keterangan
                ]);

                return redirect()->back()
                    ->with('success', 'Foto berhasil diupload');
            }

            return redirect()->back()
                ->with('error', 'Tidak ada file yang diupload');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }

    /**
     * Delete foto belanja
     */
    public function deleteFoto($id)
    {
        try {
            $foto = FotoBelanjaKhidmat::findOrFail($id);

            // Delete file from storage
            if (Storage::exists($foto->path_file)) {
                Storage::delete($foto->path_file);
            }

            $foto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download PDF laporan keseluruhan
     */
    public function downloadPDF()
    {
        $jadwal = JadwalKhidmat::with(['petugas.santri', 'belanja'])
            ->orderBy('tanggal_jadwal', 'asc')
            ->get();

        $totalSemuaBelanja = $jadwal->sum('total_belanja');
        $saldoAwalKeseluruhan = $jadwal->first() ? $jadwal->first()->saldo_awal : 0;
        $saldoAkhirKeseluruhan = $jadwal->last() ? $jadwal->last()->saldo_akhir : 0;

        $pdf = PDF::loadView('khidmat.pdf', compact(
            'jadwal',
            'totalSemuaBelanja',
            'saldoAwalKeseluruhan',
            'saldoAkhirKeseluruhan'
        ));

        return $pdf->download('Laporan_Keuangan_Khidmat_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Download PDF for single jadwal
     */
    public function downloadSinglePDF($id)
    {
        $jadwal = JadwalKhidmat::with(['petugas.santri', 'belanja'])
            ->findOrFail($id);

        $pdf = PDF::loadView('khidmat.pdf-single', compact('jadwal'));

        return $pdf->download('Laporan_Khidmat_' . $jadwal->nama_kelompok . '_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Helper: Update saldo awal of next jadwal
     */
    private function updateNextJadwalSaldo($jadwal)
    {
        $nextJadwal = JadwalKhidmat::where('tanggal_jadwal', '>', $jadwal->tanggal_jadwal)
            ->orderBy('tanggal_jadwal', 'asc')
            ->first();

        if ($nextJadwal) {
            $nextJadwal->update([
                'saldo_awal' => $jadwal->saldo_akhir
            ]);

            // Recalculate saldo akhir of next jadwal
            $nextJadwal->update([
                'saldo_akhir' => $nextJadwal->saldo_awal - $nextJadwal->total_belanja
            ]);

            // Recursively update all subsequent jadwals
            $this->updateNextJadwalSaldo($nextJadwal);
        }
    }

    /**
     * Download template Excel untuk input belanja
     */
    public function downloadTemplate($id)
    {
        $jadwal = JadwalKhidmat::findOrFail($id);
        
        return Excel::download(
            new KhidmatTemplateExport(), 
            'Template_Belanja_Khidmat_' . $jadwal->nama_kelompok . '_' . $jadwal->tanggal_jadwal->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Import data belanja dari Excel
     */
    public function importBelanja(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $jadwal = JadwalKhidmat::findOrFail($id);
            
            // Delete existing belanja items
            BelanjaKhidmat::where('jadwal_khidmat_id', $jadwal->id)->delete();
            
            // Import new data
            Excel::import(new KhidmatBelanjaImport($jadwal->id), $request->file('file'));

            // Recalculate totals
            $totalBelanja = BelanjaKhidmat::where('jadwal_khidmat_id', $jadwal->id)->sum('total_harga');
            $saldoAkhir = ($jadwal->saldo_awal + $jadwal->saldo_masuk) - $totalBelanja;
            
            $jadwal->update([
                'total_belanja' => $totalBelanja,
                'saldo_akhir' => $saldoAkhir
            ]);

            // Update next jadwal saldo
            $this->updateNextJadwalSaldo($jadwal);

            DB::commit();
            return redirect()->route('khidmat.laporan', $id)
                ->with('success', 'Data belanja berhasil diimport dari Excel');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Index untuk Karyawan (Read Only)
     */
    public function indexKaryawan()
    {
        // Tampilkan 7 jadwal terbaru
        $jadwal = JadwalKhidmat::with(['petugas.santri', 'belanja', 'foto'])
            ->orderBy('tanggal_jadwal', 'desc')
            ->limit(7)
            ->get();

        return view('khidmat.karyawan.index', compact('jadwal'));
    }

    /**
     * Show detail untuk Karyawan (Read Only)
     */
    public function showKaryawan($id)
    {
        $jadwal = JadwalKhidmat::with(['petugas.santri', 'belanja', 'foto'])
            ->findOrFail($id);

        return view('khidmat.karyawan.show', compact('jadwal'));
    }
}
