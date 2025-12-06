<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\AktivitasKendaraan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AktivitasKendaraanController extends Controller
{
    public function index($kendaraan_id)
    {
        $kendaraan_id = Crypt::decrypt($kendaraan_id);
        $kendaraan = Kendaraan::findOrFail($kendaraan_id);
        
        $aktivitas = AktivitasKendaraan::where('kendaraan_id', $kendaraan_id)
            ->orderBy('waktu_keluar', 'desc')
            ->paginate(15);
            
        return view('kendaraan.aktivitas.index', compact('kendaraan', 'aktivitas'));
    }

    public function formKeluar($kendaraan_id)
    {
        $kendaraan_id_decrypt = Crypt::decrypt($kendaraan_id);
        $kendaraan = Kendaraan::findOrFail($kendaraan_id_decrypt);
        
        // Check apakah kendaraan tersedia
        if (!$kendaraan->isTersedia()) {
            return Redirect::back()->with(messageError('Kendaraan sedang tidak tersedia'));
        }
        
        return view('kendaraan.aktivitas.keluar', compact('kendaraan', 'kendaraan_id'));
    }

    public function prosesKeluar(Request $request, $kendaraan_id)
    {
        $kendaraan_id = Crypt::decrypt($kendaraan_id);
        
        $request->validate([
            'nama_pengemudi' => 'required|max:100',
            'no_hp_pengemudi' => 'nullable|max:20',
            'tujuan' => 'required',
            'tanggal_keluar' => 'required|date',
            'jam_keluar' => 'required',
            'km_awal' => 'nullable|numeric',
            'status_bbm_keluar' => 'nullable|in:Penuh,3/4,1/2,1/4,Kosong',
            'latitude_keluar' => 'nullable|numeric',
            'longitude_keluar' => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();
            
            $kendaraan = Kendaraan::findOrFail($kendaraan_id);
            
            // Check availability
            if (!$kendaraan->isTersedia()) {
                return Redirect::back()->with(messageError('Kendaraan sedang tidak tersedia'));
            }
            
            // Generate kode aktivitas
            $tanggal = date('Ymd');
            $lastAktivitas = AktivitasKendaraan::whereDate('created_at', date('Y-m-d'))->count();
            $kode_aktivitas = 'AKT-' . $tanggal . '-' . str_pad($lastAktivitas + 1, 4, '0', STR_PAD_LEFT);
            
            // Combine date and time for waktu_keluar
            $waktu_keluar = $request->tanggal_keluar . ' ' . $request->jam_keluar;
            
            // Create aktivitas
            $aktivitas_baru = AktivitasKendaraan::create([
                'kode_aktivitas' => $kode_aktivitas,
                'kendaraan_id' => $kendaraan_id,
                'driver' => $request->nama_pengemudi,
                'email_driver' => $request->no_hp_pengemudi,
                'penumpang' => $request->penumpang,
                'tujuan' => $request->tujuan,
                'waktu_keluar' => $waktu_keluar,
                'km_awal' => $request->km_awal,
                'status_bbm_keluar' => $request->status_bbm_keluar,
                'latitude_keluar' => $request->latitude_keluar,
                'longitude_keluar' => $request->longitude_keluar,
                'keterangan_keluar' => $request->keterangan,
                'status' => 'keluar',
            ]);
            
            // Tambahkan notifikasi real-time untuk kendaraan keluar
            $aktivitas_dengan_kendaraan = AktivitasKendaraan::with('kendaraan')->find($aktivitas_baru->id);
            NotificationService::kendaraanNotification($aktivitas_dengan_kendaraan, 'keluar');
            
            // Update status kendaraan
            $kendaraan->update(['status' => 'keluar']);
            
            DB::commit();
            
            // Check if AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kendaraan berhasil keluar'
                ]);
            }
            
            return Redirect::route('kendaraan.index')->with(messageSuccess('Kendaraan berhasil keluar'));
        } catch (\Exception $e) {
            DB::rollback();
            
            // Check if AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function formKembali($id)
    {
        $id = Crypt::decrypt($id);
        $aktivitas = AktivitasKendaraan::with('kendaraan')->findOrFail($id);
        
        if ($aktivitas->status == 'kembali') {
            return Redirect::back()->with(messageError('Aktivitas sudah selesai'));
        }
        
        return view('kendaraan.aktivitas.kembali', compact('aktivitas'));
    }

    public function prosesKembali(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'jam_kembali' => 'required',
            'km_akhir' => 'nullable|numeric',
            'status_bbm_kembali' => 'nullable|in:Penuh,3/4,1/2,1/4,Kosong',
            'kondisi_kendaraan' => 'nullable|in:Baik,Cukup,Perlu Perbaikan',
            'latitude_kembali' => 'nullable|numeric',
            'longitude_kembali' => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();
            
            $aktivitas = AktivitasKendaraan::with('kendaraan')->findOrFail($id);
            
            if ($aktivitas->status == 'kembali') {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aktivitas sudah selesai'
                    ]);
                }
                return Redirect::back()->with(messageError('Aktivitas sudah selesai'));
            }
            
            // Combine date and time for waktu_kembali
            $waktu_kembali = $request->tanggal_kembali . ' ' . $request->jam_kembali;
            
            // Update aktivitas
            $aktivitas->update([
                'waktu_kembali' => $waktu_kembali,
                'km_akhir' => $request->km_akhir,
                'status_bbm_kembali' => $request->status_bbm_kembali,
                'kondisi_kendaraan' => $request->kondisi_kendaraan,
                'latitude_kembali' => $request->latitude_kembali,
                'longitude_kembali' => $request->longitude_kembali,
                'keterangan_kembali' => $request->keterangan,
                'status' => 'kembali',
            ]);
            
            // Tambahkan notifikasi real-time untuk kendaraan masuk/kembali
            $aktivitas_updated = AktivitasKendaraan::with('kendaraan')->find($aktivitas->id);
            NotificationService::kendaraanNotification($aktivitas_updated, 'masuk');
            
            // Update status kendaraan kembali ke tersedia
            $aktivitas->kendaraan->update(['status' => 'tersedia']);
            
            DB::commit();
            
            // Handle AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kendaraan berhasil ditandai kembali!'
                ]);
            }
            
            return Redirect::route('kendaraan.index')->with(messageSuccess('Kendaraan berhasil kembali'));
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function tracking($id)
    {
        $id = Crypt::decrypt($id);
        $aktivitas = AktivitasKendaraan::with('kendaraan')->findOrFail($id);
        
        // Parse koordinat untuk Leaflet map
        $markers = [];
        
        // Marker lokasi keluar
        if ($aktivitas->latitude_keluar && $aktivitas->longitude_keluar) {
            $markers[] = [
                'type' => 'keluar',
                'latitude' => floatval($aktivitas->latitude_keluar),
                'longitude' => floatval($aktivitas->longitude_keluar),
                'waktu' => $aktivitas->waktu_keluar,
                'km' => $aktivitas->km_awal,
                'bbm' => $aktivitas->status_bbm_keluar,
                'keterangan' => $aktivitas->keterangan_keluar,
            ];
        }
        
        // Marker lokasi kembali
        if ($aktivitas->latitude_kembali && $aktivitas->longitude_kembali) {
            // Safe access waktu_kembali
            $waktuKembaliSafe = null;
            try {
                $wk = $aktivitas->waktu_kembali;
                if (!is_null($wk) && !is_array($wk)) {
                    $waktuKembaliSafe = $wk;
                }
            } catch (\Exception $e) {
                \Log::warning('Error accessing waktu_kembali in aktivitas tracking: ' . $e->getMessage());
            }
            
            if ($waktuKembaliSafe) {
                $markers[] = [
                    'type' => 'kembali',
                    'latitude' => floatval($aktivitas->latitude_kembali),
                    'longitude' => floatval($aktivitas->longitude_kembali),
                    'waktu' => $waktuKembaliSafe,
                    'km' => $aktivitas->km_akhir,
                    'bbm' => $aktivitas->status_bbm_kembali,
                    'kondisi' => $aktivitas->kondisi_kendaraan,
                    'keterangan' => $aktivitas->keterangan_kembali,
                ];
            }
        }
        
        return view('kendaraan.aktivitas.tracking', compact('aktivitas', 'markers'));
    }
    
    /**
     * Get tracking data for AJAX request
     */
    public function getTrackingData(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $aktivitas = AktivitasKendaraan::with('kendaraan')->findOrFail($id);
        
        // Parse koordinat untuk Leaflet map
        $markers = [];
        
        // Marker lokasi keluar
        if ($aktivitas->latitude_keluar && $aktivitas->longitude_keluar) {
            $markers[] = [
                'type' => 'keluar',
                'latitude' => floatval($aktivitas->latitude_keluar),
                'longitude' => floatval($aktivitas->longitude_keluar),
                'waktu' => $aktivitas->waktu_keluar,
                'km' => $aktivitas->km_awal,
                'bbm' => $aktivitas->status_bbm_keluar,
                'keterangan' => $aktivitas->keterangan_keluar,
            ];
        }
        
        // Marker lokasi kembali
        if ($aktivitas->latitude_kembali && $aktivitas->longitude_kembali) {
            // Safe access waktu_kembali
            $waktuKembaliSafe = null;
            try {
                $wk = $aktivitas->waktu_kembali;
                if (!is_null($wk) && !is_array($wk)) {
                    $waktuKembaliSafe = $wk;
                }
            } catch (\Exception $e) {
                \Log::warning('Error accessing waktu_kembali in aktivitas getTrackingData: ' . $e->getMessage());
            }
            
            if ($waktuKembaliSafe) {
                $markers[] = [
                    'type' => 'kembali',
                    'latitude' => floatval($aktivitas->latitude_kembali),
                    'longitude' => floatval($aktivitas->longitude_kembali),
                    'waktu' => $waktuKembaliSafe,
                    'km' => $aktivitas->km_akhir,
                    'bbm' => $aktivitas->status_bbm_kembali,
                    'kondisi' => $aktivitas->kondisi_kendaraan,
                    'keterangan' => $aktivitas->keterangan_kembali,
                ];
            }
        }
        
        return response()->json([
            'aktivitas' => $aktivitas,
            'markers' => $markers
        ]);
    }

    /**
     * Delete aktivitas kendaraan
     */
    public function delete($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $aktivitas = AktivitasKendaraan::findOrFail($id);
            $kendaraan_id = $aktivitas->kendaraan_id;
            
            // Jika aktivitas masih aktif (belum kembali), ubah status kendaraan kembali ke tersedia
            if ($aktivitas->status == 'keluar' && !$aktivitas->waktu_kembali) {
                $kendaraan = Kendaraan::find($kendaraan_id);
                if ($kendaraan) {
                    $kendaraan->status = 'tersedia';
                    $kendaraan->save();
                }
            }
            
            // Hapus foto jika ada
            if ($aktivitas->foto_keluar && Storage::exists('public/uploads/aktivitas/' . $aktivitas->foto_keluar)) {
                Storage::delete('public/uploads/aktivitas/' . $aktivitas->foto_keluar);
            }
            if ($aktivitas->foto_kembali && Storage::exists('public/uploads/aktivitas/' . $aktivitas->foto_kembali)) {
                Storage::delete('public/uploads/aktivitas/' . $aktivitas->foto_kembali);
            }
            
            $aktivitas->delete();
            
            return redirect()->back()->with(['success' => 'Aktivitas berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['warning' => 'Gagal menghapus aktivitas: ' . $e->getMessage()]);
        }
    }
    
    // Alias untuk method delete
    public function destroy($id)
    {
        return $this->delete($id);
    }

    /**
     * Get aktivitas data for edit (AJAX)
     */
    public function edit($id)
    {
        try {
            // Check if ID is encrypted or not
            if (!is_numeric($id)) {
                $id = Crypt::decrypt($id);
            }
            
            $aktivitas = AktivitasKendaraan::findOrFail($id);
            
            // Format datetime for datetime-local input with safe access
            $waktu_keluar = '';
            try {
                if ($aktivitas->waktu_keluar && !is_array($aktivitas->waktu_keluar)) {
                    $waktu_keluar = date('Y-m-d\TH:i', strtotime($aktivitas->waktu_keluar));
                }
            } catch (\Exception $e) {
                \Log::warning('Error formatting waktu_keluar: ' . $e->getMessage());
            }
            
            $waktu_kembali = '';
            try {
                if ($aktivitas->waktu_kembali && !is_array($aktivitas->waktu_kembali)) {
                    $waktu_kembali = date('Y-m-d\TH:i', strtotime($aktivitas->waktu_kembali));
                }
            } catch (\Exception $e) {
                \Log::warning('Error formatting waktu_kembali: ' . $e->getMessage());
            }
            
            return response()->json([
                'driver' => $aktivitas->driver,
                'penumpang' => $aktivitas->penumpang,
                'tujuan' => $aktivitas->tujuan,
                'waktu_keluar' => $waktu_keluar,
                'waktu_kembali' => $waktu_kembali,
                'km_awal' => $aktivitas->km_awal,
                'km_akhir' => $aktivitas->km_akhir,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update aktivitas kendaraan
     */
    public function update(Request $request, $id)
    {
        try {
            // Check if ID is encrypted or not
            if (!is_numeric($id)) {
                $id = Crypt::decrypt($id);
            }
            
            $aktivitas = AktivitasKendaraan::findOrFail($id);
            
            $request->validate([
                'driver' => 'required',
                'tujuan' => 'required',
                'waktu_keluar' => 'required',
                'km_awal' => 'required|numeric',
            ]);
            
            $aktivitas->driver = $request->driver;
            $aktivitas->penumpang = $request->penumpang;
            $aktivitas->tujuan = $request->tujuan;
            $aktivitas->waktu_keluar = $request->waktu_keluar;
            $aktivitas->waktu_kembali = $request->waktu_kembali;
            $aktivitas->km_awal = $request->km_awal;
            $aktivitas->km_akhir = $request->km_akhir;
            
            // Update status based on waktu_kembali
            if ($request->waktu_kembali) {
                $aktivitas->status = 'kembali';
            } else {
                $aktivitas->status = 'keluar';
            }
            
            $aktivitas->save();
            
            return redirect()->back()->with(['success' => 'Aktivitas berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['warning' => 'Gagal mengupdate aktivitas: ' . $e->getMessage()]);
        }
    }
}
