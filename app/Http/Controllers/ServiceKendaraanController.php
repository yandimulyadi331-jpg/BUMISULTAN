<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\ServiceKendaraan;
use App\Models\JadwalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class ServiceKendaraanController extends Controller
{
    public function index($kendaraan_id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($kendaraan_id)) {
            try {
                $kendaraan_id = Crypt::decrypt($kendaraan_id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Kendaraan tidak valid'));
            }
        }
        
        $kendaraan = Kendaraan::with('jadwalServices')->findOrFail($kendaraan_id);
        
        $services = ServiceKendaraan::where('kendaraan_id', $kendaraan_id)
            ->orderBy('waktu_service', 'desc')
            ->paginate(15);
            
        return view('kendaraan.service.index', compact('kendaraan', 'services'));
    }

    public function formService($kendaraan_id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($kendaraan_id)) {
            try {
                $kendaraan_id = Crypt::decrypt($kendaraan_id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Kendaraan tidak valid'));
            }
        }
        
        $kendaraan = Kendaraan::findOrFail($kendaraan_id);
        
        if (!$kendaraan->isTersedia()) {
            return Redirect::back()->with(messageError('Kendaraan sedang tidak tersedia'));
        }
        
        return view('kendaraan.service.form', compact('kendaraan', 'kendaraan_id'));
    }

    public function prosesService(Request $request, $kendaraan_id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($kendaraan_id)) {
            try {
                $kendaraan_id = Crypt::decrypt($kendaraan_id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Kendaraan tidak valid'));
            }
        }
        
        $request->validate([
            'tanggal_service' => 'required|date',
            'jam_service' => 'required',
            'jenis_service' => 'required|max:100',
            'bengkel' => 'nullable|max:200',
            'km_service' => 'nullable|numeric',
            'estimasi_biaya' => 'nullable|numeric',
            'deskripsi_kerusakan' => 'required',
            'pekerjaan' => 'nullable',
            'estimasi_selesai' => 'nullable|date',
            'pic' => 'nullable|max:100',
            'foto_before' => 'nullable|image|max:2048',
            'latitude_service' => 'nullable',
            'longitude_service' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            
            $kendaraan = Kendaraan::findOrFail($kendaraan_id);
            
            if (!$kendaraan->isTersedia()) {
                return Redirect::back()->with(messageError('Kendaraan sedang tidak tersedia'));
            }
            
            $tanggal = date('Ymd');
            $lastService = ServiceKendaraan::whereDate('created_at', date('Y-m-d'))->count();
            $kode_service = 'SRV-' . $tanggal . '-' . str_pad($lastService + 1, 4, '0', STR_PAD_LEFT);
            
            // Combine tanggal and jam
            $waktu_service = $request->tanggal_service . ' ' . $request->jam_service;
            
            $data = [
                'kode_service' => $kode_service,
                'kendaraan_id' => $kendaraan_id,
                'waktu_service' => $waktu_service,
                'jenis_service' => $request->jenis_service,
                'bengkel' => $request->bengkel,
                'km_service' => $request->km_service,
                'estimasi_biaya' => $request->estimasi_biaya,
                'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
                'pekerjaan' => $request->pekerjaan,
                'estimasi_selesai' => $request->estimasi_selesai,
                'pic' => $request->pic,
                'latitude_service' => $request->latitude_service,
                'longitude_service' => $request->longitude_service,
                'status' => 'proses',
            ];
            
            if ($request->hasFile('foto_before')) {
                $file = $request->file('foto_before');
                $filename = 'before_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/service'), $filename);
                $data['foto_before'] = $filename;
            }
            
            ServiceKendaraan::create($data);
            $kendaraan->update(['status' => 'service']);
            
            DB::commit();
            
            // Check if AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kendaraan berhasil diproses untuk service'
                ]);
            }
            
            return Redirect::route('kendaraan.index')->with(messageSuccess('Service berhasil dicatat'));
        } catch (\Exception $e) {
            DB::rollback();
            
            // Check if AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function editService($id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($id)) {
            try {
                $id = Crypt::decrypt($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Service tidak valid'));
            }
        }
        
        $service = ServiceKendaraan::with('kendaraan')->findOrFail($id);
        
        return view('kendaraan.service.edit', compact('service'));
    }

    public function updateService(Request $request, $id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($id)) {
            try {
                $id = Crypt::decrypt($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Service tidak valid'));
            }
        }
        
        $request->validate([
            'tanggal_service' => 'required|date',
            'jam_service' => 'required',
            'jenis_service' => 'required|max:100',
            'bengkel' => 'required|max:200',
            'km_service' => 'required|numeric',
            'estimasi_biaya' => 'nullable|numeric',
            'deskripsi_kerusakan' => 'required',
            'pekerjaan' => 'nullable',
            'estimasi_selesai' => 'nullable|date',
            'pic' => 'nullable|max:100',
            'foto_before' => 'nullable|image|max:2048',
        ]);

        try {
            DB::beginTransaction();
            
            $service = ServiceKendaraan::findOrFail($id);
            
            // Combine tanggal and jam
            $waktu_service = $request->tanggal_service . ' ' . $request->jam_service;
            
            $data = [
                'waktu_service' => $waktu_service,
                'jenis_service' => $request->jenis_service,
                'bengkel' => $request->bengkel,
                'km_service' => $request->km_service,
                'estimasi_biaya' => $request->estimasi_biaya,
                'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
                'pekerjaan' => $request->pekerjaan,
                'estimasi_selesai' => $request->estimasi_selesai,
                'pic' => $request->pic,
            ];
            
            if ($request->hasFile('foto_before')) {
                // Delete old photo if exists
                if ($service->foto_before && file_exists(public_path('storage/service/' . $service->foto_before))) {
                    unlink(public_path('storage/service/' . $service->foto_before));
                }
                
                $file = $request->file('foto_before');
                $filename = 'before_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/service'), $filename);
                $data['foto_before'] = $filename;
            }
            
            $service->update($data);
            
            DB::commit();
            
            // Always redirect back to previous page (either detail or service index)
            return Redirect::back()->with(messageSuccess('Data service berhasil diupdate'));
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deleteService($id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($id)) {
            try {
                $id = Crypt::decrypt($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Service tidak valid'));
            }
        }
        
        try {
            DB::beginTransaction();
            
            $service = ServiceKendaraan::with('kendaraan')->findOrFail($id);
            
            // Delete photos if exist
            if ($service->foto_before && file_exists(public_path('storage/service/' . $service->foto_before))) {
                unlink(public_path('storage/service/' . $service->foto_before));
            }
            
            if ($service->foto_after && file_exists(public_path('storage/service/' . $service->foto_after))) {
                unlink(public_path('storage/service/' . $service->foto_after));
            }
            
            // Update kendaraan status back to tersedia
            $service->kendaraan->update(['status' => 'tersedia']);
            
            $kendaraan_id = $service->kendaraan_id;
            $service->delete();
            
            DB::commit();
            
            // Always redirect back to previous page (either detail or service index)
            return Redirect::back()->with(messageSuccess('Data service berhasil dihapus'));
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function formSelesai($id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($id)) {
            try {
                $id = Crypt::decrypt($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Service tidak valid'));
            }
        }
        
        $service = ServiceKendaraan::with('kendaraan')->findOrFail($id);
        
        if ($service->status == 'selesai') {
            return Redirect::back()->with(messageError('Service sudah selesai'));
        }
        
        return view('kendaraan.service.selesai', compact('service'));
    }

    public function prosesSelesai(Request $request, $id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($id)) {
            try {
                $id = Crypt::decrypt($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Service tidak valid'));
            }
        }
        
        $request->validate([
            'tanggal_selesai' => 'required|date',
            'jam_selesai' => 'required',
            'km_selesai' => 'nullable|numeric',
            'biaya_akhir' => 'nullable|numeric',
            'pekerjaan_selesai' => 'required',
            'catatan_mekanik' => 'nullable',
            'kondisi_kendaraan' => 'required',
            'pic_selesai' => 'nullable|max:100',
            'foto_after' => 'required|image|max:2048',
            'latitude_selesai' => 'nullable',
            'longitude_selesai' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            
            $service = ServiceKendaraan::with('kendaraan')->findOrFail($id);
            
            if ($service->status == 'selesai') {
                return Redirect::back()->with(messageError('Service sudah selesai'));
            }
            
            // Combine tanggal and jam
            $waktu_selesai = $request->tanggal_selesai . ' ' . $request->jam_selesai;
            
            $data = [
                'waktu_selesai' => $waktu_selesai,
                'km_selesai' => $request->km_selesai,
                'biaya_akhir' => $request->biaya_akhir,
                'pekerjaan_selesai' => $request->pekerjaan_selesai,
                'catatan_mekanik' => $request->catatan_mekanik,
                'kondisi_kendaraan' => $request->kondisi_kendaraan,
                'pic_selesai' => $request->pic_selesai,
                'latitude_selesai' => $request->latitude_selesai,
                'longitude_selesai' => $request->longitude_selesai,
                'status' => 'selesai',
            ];
            
            if ($request->hasFile('foto_after')) {
                $file = $request->file('foto_after');
                $filename = 'after_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/service'), $filename);
                $data['foto_after'] = $filename;
            }
            
            $service->update($data);
            $service->kendaraan->update(['status' => 'tersedia']);
            
            DB::commit();
            
            // Check if AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service berhasil diselesaikan'
                ]);
            }
            
            return Redirect::route('kendaraan.index')->with(messageSuccess('Service selesai dicatat'));
        } catch (\Exception $e) {
            DB::rollback();
            
            // Check if AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    // Jadwal Service
    public function jadwalService($kendaraan_id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($kendaraan_id)) {
            try {
                $kendaraan_id = Crypt::decrypt($kendaraan_id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Kendaraan tidak valid'));
            }
        }
        
        $kendaraan = Kendaraan::findOrFail($kendaraan_id);
        
        $jadwals = JadwalService::where('kendaraan_id', $kendaraan_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('kendaraan.service.jadwal', compact('kendaraan', 'jadwals'));
    }

    public function storeJadwal(Request $request, $kendaraan_id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($kendaraan_id)) {
            try {
                $kendaraan_id = Crypt::decrypt($kendaraan_id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Kendaraan tidak valid'));
            }
        }
        
        $request->validate([
            'jenis_service' => 'required|max:100',
            'tipe_interval' => 'required|in:kilometer,waktu',
        ]);

        try {
            $data = [
                'kendaraan_id' => $kendaraan_id,
                'jenis_service' => $request->jenis_service,
                'tipe_interval' => $request->tipe_interval,
                'keterangan' => $request->keterangan,
            ];
            
            if ($request->tipe_interval == 'kilometer') {
                $data['interval_km'] = $request->interval_km;
                $data['km_terakhir'] = $request->km_terakhir;
            } else {
                $data['interval_hari'] = $request->interval_hari;
                $data['tanggal_terakhir'] = $request->tanggal_terakhir;
                
                // Calculate jadwal_berikutnya
                if ($request->tanggal_terakhir && $request->interval_hari) {
                    $data['jadwal_berikutnya'] = date('Y-m-d', strtotime($request->tanggal_terakhir . ' + ' . $request->interval_hari . ' days'));
                }
            }
            
            JadwalService::create($data);
            
            return Redirect::back()->with(messageSuccess('Jadwal service berhasil ditambahkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function updateJadwal(Request $request, $id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($id)) {
            try {
                $id = Crypt::decrypt($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Jadwal tidak valid'));
            }
        }
        
        $request->validate([
            'jenis_service' => 'required|max:100',
            'tipe_interval' => 'required|in:kilometer,waktu',
        ]);
        
        try {
            $jadwal = JadwalService::findOrFail($id);
            
            $data = [
                'jenis_service' => $request->jenis_service,
                'tipe_interval' => $request->tipe_interval,
                'keterangan' => $request->keterangan,
            ];
            
            if ($request->tipe_interval == 'kilometer') {
                $data['interval_km'] = $request->interval_km;
                $data['km_terakhir'] = $request->km_terakhir;
                $data['interval_hari'] = null;
                $data['tanggal_terakhir'] = null;
                $data['jadwal_berikutnya'] = null;
            } else {
                $data['interval_hari'] = $request->interval_hari;
                $data['tanggal_terakhir'] = $request->tanggal_terakhir;
                $data['interval_km'] = null;
                $data['km_terakhir'] = null;
                
                // Calculate jadwal_berikutnya
                if ($request->tanggal_terakhir && $request->interval_hari) {
                    $data['jadwal_berikutnya'] = date('Y-m-d', strtotime($request->tanggal_terakhir . ' + ' . $request->interval_hari . ' days'));
                }
            }
            
            $jadwal->update($data);
            
            return Redirect::back()->with(messageSuccess('Jadwal service berhasil diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deleteJadwal($id)
    {
        // Accept both encrypted and plain IDs
        if (!is_numeric($id)) {
            try {
                $id = Crypt::decrypt($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return Redirect::back()->with(messageError('ID Jadwal tidak valid'));
            }
        }
        
        try {
            $jadwal = JadwalService::findOrFail($id);
            $jadwal->delete();
            
            return Redirect::back()->with(messageSuccess('Jadwal service berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
