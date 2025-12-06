<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\PeminjamanKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class PeminjamanKendaraanController extends Controller
{
    public function index($kendaraan_id)
    {
        $kendaraan_id = Crypt::decrypt($kendaraan_id);
        $kendaraan = Kendaraan::findOrFail($kendaraan_id);
        
        $peminjaman = PeminjamanKendaraan::where('kendaraan_id', $kendaraan_id)
            ->orderBy('waktu_pinjam', 'desc')
            ->paginate(15);
            
        return view('kendaraan.peminjaman.index', compact('kendaraan', 'peminjaman'));
    }

    public function formPinjam($kendaraan_id)
    {
        $kendaraan_id_decrypt = Crypt::decrypt($kendaraan_id);
        $kendaraan = Kendaraan::findOrFail($kendaraan_id_decrypt);
        
        if (!$kendaraan->isTersedia()) {
            return Redirect::back()->with(messageError('Kendaraan sedang tidak tersedia'));
        }
        
        return view('kendaraan.peminjaman.pinjam', compact('kendaraan', 'kendaraan_id'));
    }

    public function prosesPinjam(Request $request, $kendaraan_id)
    {
        $kendaraan_id = Crypt::decrypt($kendaraan_id);
        
        $request->validate([
            'nama_peminjam' => 'required|max:100',
            'no_hp_peminjam' => 'nullable|max:20',
            'email_peminjam' => 'nullable|email',
            'foto_identitas' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'keperluan' => 'required',
            'waktu_pinjam' => 'required|date',
            'estimasi_kembali' => 'required|date|after:waktu_pinjam',
            'km_awal' => 'nullable|numeric',
            'status_bbm_pinjam' => 'nullable|in:Penuh,3/4,1/2,1/4,Kosong',
            'latitude_pinjam' => 'nullable|numeric',
            'longitude_pinjam' => 'nullable|numeric',
            'ttd_peminjam' => 'required',
        ]);

        try {
            DB::beginTransaction();
            
            $kendaraan = Kendaraan::findOrFail($kendaraan_id);
            
            if (!$kendaraan->isTersedia()) {
                return Redirect::back()->with(messageError('Kendaraan sedang tidak tersedia'));
            }
            
            $tanggal = date('Ymd');
            $lastPeminjaman = PeminjamanKendaraan::whereDate('created_at', date('Y-m-d'))->count();
            $kode_peminjaman = 'PJM-' . $tanggal . '-' . str_pad($lastPeminjaman + 1, 4, '0', STR_PAD_LEFT);
            
            // Waktu pinjam dan estimasi kembali langsung dari datetime-local input
            $waktu_pinjam = $request->waktu_pinjam;
            $estimasi_kembali = $request->estimasi_kembali;
            
            // Upload foto identitas
            $foto_identitas_filename = null;
            if ($request->hasFile('foto_identitas')) {
                $file = $request->file('foto_identitas');
                $foto_identitas_filename = 'identitas_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/peminjaman/identitas'), $foto_identitas_filename);
            }

            // Save signature to file
            $ttd_filename = null;
            if ($request->ttd_peminjam) {
                $ttd_data = $request->ttd_peminjam;
                $ttd_data = str_replace('data:image/png;base64,', '', $ttd_data);
                $ttd_data = str_replace(' ', '+', $ttd_data);
                $ttd_decoded = base64_decode($ttd_data);
                $ttd_filename = 'ttd_pinjam_' . time() . '.png';
                file_put_contents(public_path('storage/peminjaman/' . $ttd_filename), $ttd_decoded);
            }
            
            PeminjamanKendaraan::create([
                'kode_peminjaman' => $kode_peminjaman,
                'kendaraan_id' => $kendaraan_id,
                'nama_peminjam' => $request->nama_peminjam,
                'no_hp_peminjam' => $request->no_hp_peminjam,
                'email_peminjam' => $request->email_peminjam,
                'foto_identitas' => $foto_identitas_filename,
                'keperluan' => $request->keperluan,
                'waktu_pinjam' => $waktu_pinjam,
                'estimasi_kembali' => $estimasi_kembali,
                'km_awal' => $request->km_awal,
                'status_bbm_pinjam' => $request->status_bbm_pinjam,
                'latitude_pinjam' => $request->latitude_pinjam,
                'longitude_pinjam' => $request->longitude_pinjam,
                'ttd_pinjam' => $ttd_filename,
                'keterangan_pinjam' => $request->keterangan,
                'status' => 'dipinjam',
            ]);
            
            $kendaraan->update(['status' => 'dipinjam']);
            
            DB::commit();
            
            // Redirect ke halaman download surat jalan
            $peminjaman_id = PeminjamanKendaraan::where('kode_peminjaman', $kode_peminjaman)->first()->id;
            return Redirect::route('kendaraan.peminjaman.surat', Crypt::encrypt($peminjaman_id))
                ->with(messageSuccess('Peminjaman berhasil dicatat. Silakan download surat jalan.'));
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function formKembali($id)
    {
        $id = Crypt::decrypt($id);
        $peminjaman = PeminjamanKendaraan::with('kendaraan')->findOrFail($id);
        
        if ($peminjaman->status != 'dipinjam') {
            return Redirect::back()->with(messageError('Peminjaman sudah selesai'));
        }
        
        return view('kendaraan.peminjaman.kembali', compact('peminjaman'));
    }

    public function prosesKembali(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        
        $request->validate([
            'waktu_kembali' => 'required|date',
            'km_akhir' => 'nullable|numeric',
            'status_bbm_kembali' => 'nullable|in:Penuh,3/4,1/2,1/4,Kosong',
            'kondisi_kendaraan' => 'nullable|in:Baik,Cukup,Perlu Perbaikan',
            'foto_kembali' => 'nullable|image|max:2048',
            'latitude_kembali' => 'nullable|numeric',
            'longitude_kembali' => 'nullable|numeric',
            'ttd_kembali' => 'required',
        ]);

        try {
            DB::beginTransaction();
            
            $peminjaman = PeminjamanKendaraan::with('kendaraan')->findOrFail($id);
            
            if ($peminjaman->status != 'dipinjam') {
                return Redirect::back()->with(messageError('Peminjaman sudah selesai'));
            }
            
            // Waktu kembali langsung dari datetime-local input
            $waktu_kembali = $request->waktu_kembali;
            
            // Check keterlambatan
            $status = 'kembali';
            if (strtotime($waktu_kembali) > strtotime($peminjaman->estimasi_kembali)) {
                $status = 'terlambat';
            }
            
            // Handle foto upload
            $foto_kembali = null;
            if ($request->hasFile('foto_kembali')) {
                $file = $request->file('foto_kembali');
                $foto_kembali = 'kembali_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/peminjaman'), $foto_kembali);
            }
            
            // Save signature to file
            $ttd_filename = null;
            if ($request->ttd_kembali) {
                $ttd_data = $request->ttd_kembali;
                $ttd_data = str_replace('data:image/png;base64,', '', $ttd_data);
                $ttd_data = str_replace(' ', '+', $ttd_data);
                $ttd_decoded = base64_decode($ttd_data);
                $ttd_filename = 'ttd_kembali_' . time() . '.png';
                file_put_contents(public_path('storage/peminjaman/' . $ttd_filename), $ttd_decoded);
            }
            
            $peminjaman->update([
                'waktu_kembali' => $waktu_kembali,
                'km_akhir' => $request->km_akhir,
                'status_bbm_kembali' => $request->status_bbm_kembali,
                'kondisi_kendaraan' => $request->kondisi_kendaraan,
                'foto_kembali' => $foto_kembali,
                'latitude_kembali' => $request->latitude_kembali,
                'longitude_kembali' => $request->longitude_kembali,
                'ttd_kembali' => $ttd_filename,
                'keterangan_kembali' => $request->keterangan,
                'status' => $status,
            ]);
            
            $peminjaman->kendaraan->update(['status' => 'tersedia']);
            $peminjaman->kendaraan->update(['status' => 'tersedia']);
            
            DB::commit();
            return Redirect::route('kendaraan.index')->with(messageSuccess('Pengembalian berhasil dicatat'));
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function tracking($id)
    {
        $id = Crypt::decrypt($id);
        $peminjaman = PeminjamanKendaraan::with('kendaraan')->findOrFail($id);
        
        // Parse koordinat untuk Leaflet map
        $markers = [];
        
        // Marker lokasi pinjam
        if ($peminjaman->latitude_pinjam && $peminjaman->longitude_pinjam) {
            $markers[] = [
                'type' => 'pinjam',
                'latitude' => floatval($peminjaman->latitude_pinjam),
                'longitude' => floatval($peminjaman->longitude_pinjam),
                'waktu' => $peminjaman->waktu_pinjam,
                'km' => $peminjaman->km_awal,
                'bbm' => $peminjaman->status_bbm_pinjam,
                'peminjam' => $peminjaman->nama_peminjam,
                'keperluan' => $peminjaman->keperluan,
            ];
        }
        
        // Marker lokasi kembali
        if ($peminjaman->latitude_kembali && $peminjaman->longitude_kembali) {
            // Safe access waktu_kembali
            $waktuKembaliSafe = null;
            try {
                $wk = $peminjaman->waktu_kembali;
                if (!is_null($wk) && !is_array($wk)) {
                    $waktuKembaliSafe = $wk;
                }
            } catch (\Exception $e) {
                \Log::warning('Error accessing waktu_kembali in tracking: ' . $e->getMessage());
            }
            
            if ($waktuKembaliSafe) {
                $markers[] = [
                    'type' => 'kembali',
                    'latitude' => floatval($peminjaman->latitude_kembali),
                    'longitude' => floatval($peminjaman->longitude_kembali),
                    'waktu' => $waktuKembaliSafe,
                    'km' => $peminjaman->km_akhir,
                    'bbm' => $peminjaman->status_bbm_kembali,
                    'kondisi' => $peminjaman->kondisi_kendaraan,
                ];
            }
        }
        
        return view('kendaraan.peminjaman.tracking', compact('peminjaman', 'markers'));
    }
    
    /**
     * Get tracking data for AJAX request
     */
    public function getTrackingData(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $peminjaman = PeminjamanKendaraan::with('kendaraan')->findOrFail($id);
        
        // Parse koordinat untuk Leaflet map
        $markers = [];
        
        // Marker lokasi pinjam
        if ($peminjaman->latitude_pinjam && $peminjaman->longitude_pinjam) {
            $markers[] = [
                'type' => 'pinjam',
                'latitude' => floatval($peminjaman->latitude_pinjam),
                'longitude' => floatval($peminjaman->longitude_pinjam),
                'waktu' => $peminjaman->waktu_pinjam,
                'km' => $peminjaman->km_awal,
                'bbm' => $peminjaman->status_bbm_pinjam,
                'peminjam' => $peminjaman->nama_peminjam,
                'keperluan' => $peminjaman->keperluan,
            ];
        }
        
        // Marker lokasi kembali
        if ($peminjaman->latitude_kembali && $peminjaman->longitude_kembali) {
            // Safe access waktu_kembali
            $waktuKembaliSafe = null;
            try {
                $wk = $peminjaman->waktu_kembali;
                if (!is_null($wk) && !is_array($wk)) {
                    $waktuKembaliSafe = $wk;
                }
            } catch (\Exception $e) {
                \Log::warning('Error accessing waktu_kembali in getTrackingData: ' . $e->getMessage());
            }
            
            if ($waktuKembaliSafe) {
                $markers[] = [
                    'type' => 'kembali',
                    'latitude' => floatval($peminjaman->latitude_kembali),
                    'longitude' => floatval($peminjaman->longitude_kembali),
                    'waktu' => $waktuKembaliSafe,
                    'km' => $peminjaman->km_akhir,
                    'bbm' => $peminjaman->status_bbm_kembali,
                    'kondisi' => $peminjaman->kondisi_kendaraan,
                ];
            }
        }
        
        return response()->json([
            'peminjaman' => $peminjaman,
            'markers' => $markers
        ]);
    }

    /**
     * Get peminjaman data for edit (AJAX)
     */
    public function edit($id)
    {
        try {
            // Check if ID is encrypted or not
            // If the ID is already numeric, don't decrypt
            if (!is_numeric($id)) {
                $id = Crypt::decrypt($id);
            }
            
            $peminjaman = PeminjamanKendaraan::findOrFail($id);
            
            // Format datetime for datetime-local input
            $waktu_pinjam = $peminjaman->waktu_pinjam ? date('Y-m-d\TH:i', strtotime($peminjaman->waktu_pinjam)) : '';
            $estimasi_kembali = $peminjaman->estimasi_kembali ? date('Y-m-d\TH:i', strtotime($peminjaman->estimasi_kembali)) : '';
            $waktu_kembali = (isset($peminjaman->waktu_kembali) && $peminjaman->waktu_kembali) ? date('Y-m-d\TH:i', strtotime($peminjaman->waktu_kembali)) : '';
            
            return response()->json([
                'nama_peminjam' => $peminjaman->nama_peminjam,
                'no_hp_peminjam' => $peminjaman->no_hp_peminjam,
                'email_peminjam' => $peminjaman->email_peminjam,
                'keperluan' => $peminjaman->keperluan,
                'waktu_pinjam' => $waktu_pinjam,
                'estimasi_kembali' => $estimasi_kembali,
                'waktu_kembali' => $waktu_kembali,
                'km_awal' => $peminjaman->km_awal,
                'km_akhir' => $peminjaman->km_akhir,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update peminjaman
     */
    public function update(Request $request, $id)
    {
        try {
            // Check if ID is encrypted or not
            if (!is_numeric($id)) {
                $id = Crypt::decrypt($id);
            }
            
            $peminjaman = PeminjamanKendaraan::findOrFail($id);
            
            $request->validate([
                'nama_peminjam' => 'required|max:100',
                'keperluan' => 'required',
                'waktu_pinjam' => 'required',
                'estimasi_kembali' => 'required',
            ]);
            
            $peminjaman->nama_peminjam = $request->nama_peminjam;
            $peminjaman->no_hp_peminjam = $request->no_hp_peminjam;
            $peminjaman->email_peminjam = $request->email_peminjam;
            $peminjaman->keperluan = $request->keperluan;
            $peminjaman->waktu_pinjam = $request->waktu_pinjam;
            $peminjaman->estimasi_kembali = $request->estimasi_kembali;
            $peminjaman->waktu_kembali = $request->waktu_kembali;
            $peminjaman->km_awal = $request->km_awal;
            $peminjaman->km_akhir = $request->km_akhir;
            
            // Update status based on waktu_kembali
            if ($request->waktu_kembali) {
                $peminjaman->status = 'dikembalikan';
            } else {
                $peminjaman->status = 'dipinjam';
            }
            
            $peminjaman->save();
            
            return redirect()->back()->with(['success' => 'Peminjaman berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['warning' => 'Gagal mengupdate peminjaman: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete peminjaman
     */
    public function delete($id)
    {
        try {
            // Check if ID is encrypted or not
            if (!is_numeric($id)) {
                $id = Crypt::decrypt($id);
            }
            
            $peminjaman = PeminjamanKendaraan::findOrFail($id);
            $kendaraan_id = $peminjaman->kendaraan_id;
            
            // Jika peminjaman masih aktif, ubah status kendaraan kembali ke tersedia
            if ($peminjaman->status == 'dipinjam' && !$peminjaman->waktu_kembali) {
                $kendaraan = Kendaraan::find($kendaraan_id);
                if ($kendaraan) {
                    $kendaraan->status = 'tersedia';
                    $kendaraan->save();
                }
            }
            
            // Hapus foto identitas jika ada
            if ($peminjaman->foto_identitas && file_exists(public_path('storage/peminjaman/identitas/' . $peminjaman->foto_identitas))) {
                unlink(public_path('storage/peminjaman/identitas/' . $peminjaman->foto_identitas));
            }
            
            // Hapus foto kembali jika ada
            if ($peminjaman->foto_kembali && file_exists(public_path('storage/peminjaman/' . $peminjaman->foto_kembali))) {
                unlink(public_path('storage/peminjaman/' . $peminjaman->foto_kembali));
            }
            
            // Hapus tanda tangan
            if ($peminjaman->ttd_pinjam && file_exists(public_path('storage/peminjaman/' . $peminjaman->ttd_pinjam))) {
                unlink(public_path('storage/peminjaman/' . $peminjaman->ttd_pinjam));
            }
            if ($peminjaman->ttd_kembali && file_exists(public_path('storage/peminjaman/' . $peminjaman->ttd_kembali))) {
                unlink(public_path('storage/peminjaman/' . $peminjaman->ttd_kembali));
            }
            
            $peminjaman->delete();
            
            return redirect()->back()->with(['success' => 'Peminjaman berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['warning' => 'Gagal menghapus peminjaman: ' . $e->getMessage()]);
        }
    }

    /**
     * Halaman download surat jalan
     */
    public function suratJalan($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $peminjaman = PeminjamanKendaraan::with('kendaraan')->findOrFail($id);
            
            return view('kendaraan.peminjaman.surat-jalan', compact('peminjaman'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data peminjaman tidak ditemukan'));
        }
    }

    /**
     * Download PDF Surat Jalan (untuk Transportasi)
     */
    public function downloadSuratTransportasi($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $peminjaman = PeminjamanKendaraan::with('kendaraan')->findOrFail($id);
            
            // Gunakan PDF rangkap dengan garis sobek
            $pdf = \PDF::loadView('kendaraan.peminjaman.pdf-rangkap-sobek', compact('peminjaman'));
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'Surat_Peminjaman_Kendaraan_' . $peminjaman->kode_peminjaman . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal generate PDF: ' . $e->getMessage()));
        }
    }

    /**
     * Download PDF Surat Jalan (untuk Peminjam) - sama dengan transportasi karena rangkap
     */
    public function downloadSuratPeminjam($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $peminjaman = PeminjamanKendaraan::with('kendaraan')->findOrFail($id);
            
            // Gunakan PDF rangkap dengan garis sobek yang sama
            $pdf = \PDF::loadView('kendaraan.peminjaman.pdf-rangkap-sobek', compact('peminjaman'));
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'Surat_Peminjaman_Kendaraan_' . $peminjaman->kode_peminjaman . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal generate PDF: ' . $e->getMessage()));
        }
    }
}
