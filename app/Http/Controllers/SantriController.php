<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class SantriController extends Controller
{
    /**
     * Generate NIS otomatis
     * Format: SS-YYYY-XXXX
     * SS = Saung Santri
     * YYYY = Tahun masuk
     * XXXX = Nomor urut 4 digit
     */
    private function generateNIS($tahunMasuk)
    {
        // Cari NIS terakhir untuk tahun tersebut
        $lastSantri = Santri::where('nis', 'like', "SS-{$tahunMasuk}-%")
            ->orderBy('nis', 'desc')
            ->first();

        if ($lastSantri) {
            // Ambil nomor urut dari NIS terakhir
            $lastNumber = (int) substr($lastSantri->nis, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada, mulai dari 1
            $newNumber = 1;
        }

        // Format dengan 4 digit
        $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return "SS-{$tahunMasuk}-{$formattedNumber}";
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Santri::query();

        // Filter berdasarkan status santri
        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter berdasarkan tahun masuk
        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Load relasi ijin santri hanya jika tabel ada
        if (\Schema::hasTable('ijin_santri')) {
            $santri = $query->with(['ijinSantri' => function($q) {
                $q->where('status', 'dipulangkan')
                  ->orderBy('tanggal_ijin', 'desc');
            }])->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $santri = $query->orderBy('created_at', 'desc')->paginate(10);
        }
        
        // Data untuk filter
        $tahunMasukList = Santri::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        return view('santri.index', compact('santri', 'tahunMasukList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate NIS untuk tahun sekarang sebagai preview
        $tahunSekarang = date('Y');
        $nisPreview = $this->generateNIS($tahunSekarang);
        
        return view('santri.create', compact('nisPreview'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data Pribadi - NIS dihapus dari validasi karena auto-generate
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'nik' => 'nullable|string|size:16|unique:santri,nik',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten_kota' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:5',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:santri,email',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Data Keluarga
            'nama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'no_hp_ayah' => 'nullable|string|max:15',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_hp_ibu' => 'nullable|string|max:15',
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali' => 'nullable|string|max:255',
            'no_hp_wali' => 'nullable|string|max:15',
            
            // Data Pendidikan
            'asal_sekolah' => 'nullable|string|max:255',
            'tingkat_pendidikan' => 'nullable|string|max:100',
            'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'tanggal_masuk' => 'required|date',
            'status_santri' => 'required|in:aktif,cuti,alumni,keluar',
            
            // Data Hafalan
            'jumlah_juz_hafalan' => 'nullable|integer|min:0|max:30',
            'jumlah_halaman_hafalan' => 'nullable|integer|min:0',
            'target_hafalan' => 'nullable|string|max:255',
            'tanggal_mulai_tahfidz' => 'nullable|date',
            'tanggal_khatam_terakhir' => 'nullable|date',
            'catatan_hafalan' => 'nullable|string',
            
            // Data Asrama
            'nama_asrama' => 'nullable|string|max:255',
            'nomor_kamar' => 'nullable|string|max:50',
            'nama_pembina' => 'nullable|string|max:255',
            
            // Status
            'status_aktif' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Generate NIS otomatis berdasarkan tahun masuk
            $validated['nis'] = $this->generateNIS($validated['tahun_masuk']);

            // Handle upload foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = 'santri_' . time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('public/santri', $filename);
                $validated['foto'] = $filename;
            }

            Santri::create($validated);

            DB::commit();

            return redirect()->route('santri.index')
                ->with('success', 'Data santri berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto jika ada error
            if (isset($validated['foto'])) {
                Storage::delete('public/santri/' . $validated['foto']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data santri: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $santri = Santri::findOrFail($id);
        return view('santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $santri = Santri::findOrFail($id);
        return view('santri.edit', compact('santri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $validated = $request->validate([
            // Data Pribadi - NIS tidak perlu validasi karena readonly
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'nik' => 'nullable|string|size:16|unique:santri,nik,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten_kota' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:5',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:santri,email,' . $id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Data Keluarga
            'nama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'no_hp_ayah' => 'nullable|string|max:15',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_hp_ibu' => 'nullable|string|max:15',
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali' => 'nullable|string|max:255',
            'no_hp_wali' => 'nullable|string|max:15',
            
            // Data Pendidikan
            'asal_sekolah' => 'nullable|string|max:255',
            'tingkat_pendidikan' => 'nullable|string|max:100',
            'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'tanggal_masuk' => 'required|date',
            'status_santri' => 'required|in:aktif,cuti,alumni,keluar',
            
            // Data Hafalan
            'jumlah_juz_hafalan' => 'nullable|integer|min:0|max:30',
            'jumlah_halaman_hafalan' => 'nullable|integer|min:0',
            'target_hafalan' => 'nullable|string|max:255',
            'tanggal_mulai_tahfidz' => 'nullable|date',
            'tanggal_khatam_terakhir' => 'nullable|date',
            'catatan_hafalan' => 'nullable|string',
            
            // Data Asrama
            'nama_asrama' => 'nullable|string|max:255',
            'nomor_kamar' => 'nullable|string|max:50',
            'nama_pembina' => 'nullable|string|max:255',
            
            // Status
            'status_aktif' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle upload foto baru
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($santri->foto) {
                    Storage::delete('public/santri/' . $santri->foto);
                }

                $foto = $request->file('foto');
                $filename = 'santri_' . time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('public/santri', $filename);
                $validated['foto'] = $filename;
            }

            $santri->update($validated);

            DB::commit();

            return redirect()->route('santri.index')
                ->with('success', 'Data santri berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto baru jika ada error
            if (isset($validated['foto']) && $validated['foto'] != $santri->foto) {
                Storage::delete('public/santri/' . $validated['foto']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data santri: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $santri = Santri::findOrFail($id);
            
            // Hapus foto jika ada
            if ($santri->foto) {
                Storage::delete('public/santri/' . $santri->foto);
            }

            $santri->delete();

            return redirect()->route('santri.index')
                ->with('success', 'Data santri berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data santri: ' . $e->getMessage());
        }
    }

    /**
     * Export data santri to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Santri::query();

        // Filter berdasarkan status santri
        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter berdasarkan tahun masuk
        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $santri = $query->orderBy('nama_lengkap', 'asc')->get();

        // Data untuk PDF
        $data = [
            'santri' => $santri,
            'tanggal' => now()->format('d F Y'),
            'total' => $santri->count(),
            'filter' => [
                'status_santri' => $request->status_santri,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tahun_masuk' => $request->tahun_masuk,
                'search' => $request->search,
            ]
        ];

        $pdf = Pdf::loadView('santri.pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->stream('Data-Santri-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Cetak QR Code Santri - Scan untuk lihat detail
     */
    public function cetakQr($id)
    {
        $santri = Santri::findOrFail($id);

        $data = [
            'santri' => $santri,
            'url' => route('santri.show', $santri->id) // URL yang akan di-scan
        ];

        $pdf = Pdf::loadView('santri.qrcode', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('QR-Code-Santri-' . $santri->nis . '.pdf');
    }

    /**
     * Download Formulir Pendaftaran Santri Baru (Template Kosong)
     */
    public function downloadFormulir()
    {
        $data = [
            'title' => 'FORMULIR PENDAFTARAN SANTRI BARU',
            'tahun' => date('Y'),
            'no_formulir' => 'FORM-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)
        ];

        $pdf = Pdf::loadView('santri.formulir-pendaftaran', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Formulir-Pendaftaran-Santri-Baru-' . date('Y') . '.pdf');
    }

    /**
     * Dashboard Saung Santri untuk Karyawan
     */
    public function dashboardKaryawan()
    {
        return view('saungsantri.dashboard-karyawan');
    }

    /**
     * Data Santri untuk Karyawan (READ ONLY)
     */
    public function indexKaryawan(Request $request)
    {
        $query = Santri::query();

        // Filter berdasarkan status santri
        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter berdasarkan tahun masuk
        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Load relasi ijin santri hanya jika tabel ada
        if (\Schema::hasTable('ijin_santri')) {
            $santri = $query->with(['ijinSantri' => function($q) {
                $q->where('status', 'dipulangkan')
                  ->orderBy('tanggal_ijin', 'desc');
            }])->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $santri = $query->orderBy('created_at', 'desc')->paginate(10);
        }
        
        // Data untuk filter
        $tahunMasukList = Santri::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        return view('santri.karyawan.index', compact('santri', 'tahunMasukList'));
    }

    /**
     * Detail Santri untuk Karyawan (READ ONLY)
     */
    public function showKaryawan($id)
    {
        $santri = Santri::findOrFail($id);
        return view('santri.karyawan.show', compact('santri'));
    }
}
