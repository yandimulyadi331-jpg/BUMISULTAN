<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengunjung;
use App\Models\JadwalPengunjung;
use App\Models\Cabang;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class PengunjungController extends Controller
{
    // ============ MANAJEMEN PENGUNJUNG ============
    
    public function index()
    {
        $pengunjung = Pengunjung::with(['cabang', 'jadwalPengunjung'])
            ->orderBy('waktu_checkin', 'desc')
            ->get();
        
        $cabang = Cabang::all();
        
        return view('fasilitas.pengunjung.index', compact('pengunjung', 'cabang'));
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'no_identitas' => 'nullable|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'keperluan' => 'required|string|max:255',
            'bertemu_dengan' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'jadwal_pengunjung_id' => 'nullable|exists:jadwal_pengunjung,id',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['kode_pengunjung'] = Pengunjung::generateKodePengunjung();
        $data['waktu_checkin'] = now();
        $data['status'] = 'checkin';

        // Upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'pengunjung_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/pengunjung', $filename);
            $data['foto'] = 'pengunjung/' . $filename;
        }

        $pengunjung = Pengunjung::create($data);

        // Update status jadwal jika dari jadwal
        if ($request->jadwal_pengunjung_id) {
            JadwalPengunjung::find($request->jadwal_pengunjung_id)->update(['status' => 'selesai']);
        }

        return redirect()->route('pengunjung.index')
            ->with('success', 'Pengunjung berhasil check-in dengan kode: ' . $data['kode_pengunjung']);
    }

    public function checkout($id)
    {
        $pengunjung = Pengunjung::findOrFail($id);
        
        $pengunjung->update([
            'waktu_checkout' => now(),
            'status' => 'checkout'
        ]);

        return redirect()->route('pengunjung.index')
            ->with('success', 'Pengunjung berhasil check-out');
    }

    public function show($id)
    {
        $pengunjung = Pengunjung::with(['cabang', 'jadwalPengunjung'])->findOrFail($id);
        return view('fasilitas.pengunjung.detail', compact('pengunjung'));
    }

    public function edit($id)
    {
        $pengunjung = Pengunjung::findOrFail($id);
        $cabang = Cabang::all();
        return view('fasilitas.pengunjung.edit', compact('pengunjung', 'cabang'));
    }

    public function update(Request $request, $id)
    {
        $pengunjung = Pengunjung::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'no_identitas' => 'nullable|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'keperluan' => 'required|string|max:255',
            'bertemu_dengan' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->all();

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($pengunjung->foto) {
                Storage::delete('public/' . $pengunjung->foto);
            }
            
            $file = $request->file('foto');
            $filename = 'pengunjung_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/pengunjung', $filename);
            $data['foto'] = 'pengunjung/' . $filename;
        }

        $pengunjung->update($data);

        return redirect()->route('pengunjung.index')
            ->with('success', 'Data pengunjung berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pengunjung = Pengunjung::findOrFail($id);
        
        // Hapus foto
        if ($pengunjung->foto) {
            Storage::delete('public/' . $pengunjung->foto);
        }
        
        $pengunjung->delete();

        return redirect()->route('pengunjung.index')
            ->with('success', 'Data pengunjung berhasil dihapus');
    }

    // ============ QR CODE ============
    
    public function showQrCode()
    {
        $url = route('pengunjung.scan');
        $qrCode = QrCode::size(300)->generate($url);
        
        return view('fasilitas.pengunjung.qrcode', compact('qrCode', 'url'));
    }

    public function scanQrCode()
    {
        $cabang = Cabang::all();
        return view('fasilitas.pengunjung.scan-checkin', compact('cabang'));
    }

    // ============ JADWAL PENGUNJUNG ============
    
    public function jadwalIndex()
    {
        $jadwal = JadwalPengunjung::with('cabang')
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get();
        
        $cabang = Cabang::all();
        
        return view('fasilitas.pengunjung.jadwal.index', compact('jadwal', 'cabang'));
    }

    public function jadwalStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'keperluan' => 'required|string|max:255',
            'bertemu_dengan' => 'nullable|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['kode_jadwal'] = JadwalPengunjung::generateKodeJadwal();
        $data['status'] = 'terjadwal';

        JadwalPengunjung::create($data);

        return redirect()->route('pengunjung.jadwal.index')
            ->with('success', 'Jadwal pengunjung berhasil ditambahkan');
    }

    public function jadwalUpdate(Request $request, $id)
    {
        $jadwal = JadwalPengunjung::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'keperluan' => 'required|string|max:255',
            'bertemu_dengan' => 'nullable|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'catatan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('pengunjung.jadwal.index')
            ->with('success', 'Jadwal pengunjung berhasil diperbarui');
    }

    public function jadwalDestroy($id)
    {
        $jadwal = JadwalPengunjung::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('pengunjung.jadwal.index')
            ->with('success', 'Jadwal pengunjung berhasil dihapus');
    }

    public function jadwalCheckin($id)
    {
        $jadwal = JadwalPengunjung::findOrFail($id);
        $cabang = Cabang::all();
        
        return view('fasilitas.pengunjung.jadwal.checkin', compact('jadwal', 'cabang'));
    }

    // ============ EXPORT PDF ============
    
    public function exportPDF()
    {
        $pengunjung = Pengunjung::with(['cabang', 'jadwalPengunjung'])
            ->orderBy('waktu_checkin', 'desc')
            ->get();
        
        $jadwal = JadwalPengunjung::with('cabang')
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get();
        
        $totalPengunjung = $pengunjung->count();
        $totalCheckin = $pengunjung->where('status', 'checkin')->count();
        $totalCheckout = $pengunjung->where('status', 'checkout')->count();
        $totalJadwal = $jadwal->count();
        
        $data = [
            'pengunjung' => $pengunjung,
            'jadwal' => $jadwal,
            'totalPengunjung' => $totalPengunjung,
            'totalCheckin' => $totalCheckin,
            'totalCheckout' => $totalCheckout,
            'totalJadwal' => $totalJadwal,
            'tanggal' => date('d/m/Y'),
        ];
        
        $pdf = Pdf::loadView('fasilitas.pengunjung.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan_Manajemen_Pengunjung_' . date('YmdHis') . '.pdf');
    }
}
