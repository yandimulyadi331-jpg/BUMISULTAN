<?php

namespace App\Http\Controllers;

use App\Models\Tukang;
use App\Models\KehadiranTukang;
use App\Models\KeuanganTukang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class KehadiranTukangController extends Controller
{
    /**
     * Halaman absensi harian - List semua tukang aktif hari ini
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $carbonDate = Carbon::parse($tanggal);
        
        // Cek apakah hari Jumat (libur)
        $isJumat = $carbonDate->isFriday();
        
        // Ambil semua tukang aktif
        $tukangs = Tukang::where('status', 'aktif')
                    ->orderBy('kode_tukang')
                    ->get();
        
        // Ambil data kehadiran hari ini untuk setiap tukang
        foreach ($tukangs as $tukang) {
            $tukang->kehadiran_hari_ini = KehadiranTukang::where('tukang_id', $tukang->id)
                                                ->where('tanggal', $tanggal)
                                                ->first();
        }
        
        $data = [
            'tukangs' => $tukangs,
            'tanggal' => $tanggal,
            'isJumat' => $isJumat,
            'hariNama' => $carbonDate->locale('id')->isoFormat('dddd, D MMMM YYYY')
        ];
        
        return view('manajemen-tukang.kehadiran.index', $data);
    }
    
    /**
     * Simpan/Update absensi tukang
     */
    public function store(Request $request)
    {
        $request->validate([
            'tukang_id' => 'required|exists:tukangs,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,tidak_hadir,setengah_hari',
            'lembur' => 'nullable|boolean',
            'keterangan' => 'nullable|string'
        ]);
        
        try {
            $tukang = Tukang::findOrFail($request->tukang_id);
            
            // Cek apakah sudah ada absensi hari ini
            $kehadiran = KehadiranTukang::where('tukang_id', $request->tukang_id)
                                        ->where('tanggal', $request->tanggal)
                                        ->first();
            
            if ($kehadiran) {
                // Update existing
                $kehadiran->status = $request->status;
                $kehadiran->lembur = $request->lembur ?? false;
                $kehadiran->keterangan = $request->keterangan;
                $kehadiran->dicatat_oleh = Auth::user()->name ?? 'Admin';
            } else {
                // Create new
                $kehadiran = new KehadiranTukang();
                $kehadiran->tukang_id = $request->tukang_id;
                $kehadiran->tanggal = $request->tanggal;
                $kehadiran->status = $request->status;
                $kehadiran->lembur = $request->lembur ?? false;
                $kehadiran->keterangan = $request->keterangan;
                $kehadiran->dicatat_oleh = Auth::user()->name ?? 'Admin';
            }
            
            // Hitung upah otomatis
            $kehadiran->hitungUpah();
            $kehadiran->save();
            
            // Auto-sync ke keuangan
            $this->syncKeuangan($kehadiran);
            
            return Redirect::back()->with(messageSuccess('Absensi berhasil disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    
    /**
     * Toggle status kehadiran (untuk quick update)
     */
    public function toggleStatus(Request $request)
    {
        try {
            $kehadiran = KehadiranTukang::where('tukang_id', $request->tukang_id)
                                        ->where('tanggal', $request->tanggal)
                                        ->first();
            
            if ($kehadiran) {
                // Cycle: tidak_hadir -> hadir -> setengah_hari -> tidak_hadir
                switch ($kehadiran->status) {
                    case 'tidak_hadir':
                        $kehadiran->status = 'hadir';
                        break;
                    case 'hadir':
                        $kehadiran->status = 'setengah_hari';
                        break;
                    case 'setengah_hari':
                        $kehadiran->status = 'tidak_hadir';
                        break;
                }
            } else {
                // Buat baru dengan status hadir
                $kehadiran = new KehadiranTukang();
                $kehadiran->tukang_id = $request->tukang_id;
                $kehadiran->tanggal = $request->tanggal;
                $kehadiran->status = 'hadir';
                $kehadiran->dicatat_oleh = Auth::user()->name ?? 'Admin';
            }
            
            $kehadiran->hitungUpah();
            $kehadiran->save();
            
            // Auto-create/update transaksi keuangan
            $this->syncKeuangan($kehadiran);
            
            return response()->json([
                'success' => true,
                'status' => $kehadiran->status,
                'upah' => number_format($kehadiran->total_upah, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Toggle jenis lembur (cycle: tidak -> full -> setengah_hari -> tidak)
     * FITUR BARU: Bisa lembur meskipun tidak hadir (untuk kasus khusus)
     */
    public function toggleLembur(Request $request)
    {
        try {
            $kehadiran = KehadiranTukang::where('tukang_id', $request->tukang_id)
                                        ->where('tanggal', $request->tanggal)
                                        ->first();
            
            if (!$kehadiran) {
                // Auto-create record kehadiran dengan status tidak_hadir
                // untuk case: tukang lembur di hari libur/tidak masuk reguler
                $kehadiran = new KehadiranTukang();
                $kehadiran->tukang_id = $request->tukang_id;
                $kehadiran->tanggal = $request->tanggal;
                $kehadiran->status = 'tidak_hadir';
                $kehadiran->lembur = 'tidak';
                $kehadiran->dicatat_oleh = Auth::user()->name ?? 'Admin';
            }
            
            // Cycle: tidak -> full -> setengah_hari -> tidak
            switch ($kehadiran->lembur) {
                case 'tidak':
                    $kehadiran->lembur = 'full';
                    break;
                case 'full':
                    $kehadiran->lembur = 'setengah_hari';
                    break;
                case 'setengah_hari':
                    $kehadiran->lembur = 'tidak';
                    break;
                default:
                    $kehadiran->lembur = 'tidak';
                    break;
            }
            
            // Reset cash jika lembur dimatikan
            if ($kehadiran->lembur == 'tidak') {
                $kehadiran->lembur_dibayar_cash = false;
            }
            
            $kehadiran->hitungUpah();
            $kehadiran->save();
            
            // Auto-create/update transaksi keuangan
            $this->syncKeuangan($kehadiran);
            
            return response()->json([
                'success' => true,
                'lembur' => $kehadiran->lembur,
                'lembur_dibayar_cash' => $kehadiran->lembur_dibayar_cash,
                'upah' => number_format($kehadiran->total_upah, 0, ',', '.'),
                'upah_lembur' => number_format($kehadiran->upah_lembur, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Toggle lembur cash (on/off untuk bayar hari ini)
     */
    public function toggleLemburCash(Request $request)
    {
        try {
            // Support 2 cara: dari halaman absensi (tukang_id + tanggal) atau halaman cash lembur (kehadiran_id)
            if ($request->has('kehadiran_id')) {
                $kehadiran = KehadiranTukang::find($request->kehadiran_id);
            } else {
                $kehadiran = KehadiranTukang::where('tukang_id', $request->tukang_id)
                                            ->where('tanggal', $request->tanggal)
                                            ->first();
            }
            
            if (!$kehadiran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kehadiran tidak ditemukan'
                ], 400);
            }
            
            // Cek apakah ada lembur aktif
            if ($kehadiran->lembur == 'tidak') {
                return response()->json([
                    'success' => false,
                    'message' => 'Aktifkan lembur terlebih dahulu'
                ], 400);
            }
            
            // Toggle cash on/off
            $kehadiran->lembur_dibayar_cash = !$kehadiran->lembur_dibayar_cash;
            
            $kehadiran->hitungUpah();
            $kehadiran->save();
            
            return response()->json([
                'success' => true,
                'lembur_dibayar_cash' => $kehadiran->lembur_dibayar_cash,
                'upah' => number_format($kehadiran->total_upah, 0, ',', '.'),
                'upah_lembur' => number_format($kehadiran->upah_lembur, 0, ',', '.'),
                'tanggal_bayar' => $kehadiran->tanggal_bayar_lembur ? $kehadiran->tanggal_bayar_lembur->format('d/m/Y') : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Halaman pembayaran cash lembur
     */
    public function cashLembur(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $tanggalCarbon = Carbon::parse($tanggal);
        
        // Format hari
        $hariNama = $tanggalCarbon->locale('id')->isoFormat('dddd, D MMMM YYYY');
        
        // Cek apakah hari Jumat
        $isJumat = $tanggalCarbon->dayOfWeek == 5;
        
        // Ambil data kehadiran yang ada lembur hari ini
        $kehadirans = KehadiranTukang::with('tukang')
            ->where('tanggal', $tanggal)
            ->whereIn('lembur', ['full', 'setengah_hari'])
            ->orderBy('created_at')
            ->get();
        
        return view('manajemen-tukang.cash-lembur.index', compact(
            'kehadirans',
            'tanggal',
            'hariNama',
            'isJumat'
        ));
    }
    
    /**
     * Halaman rekap kehadiran
     */
    public function rekap(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        // Ambil semua tukang aktif
        $tukangs = Tukang::where('status', 'aktif')
                    ->orderBy('kode_tukang')
                    ->get();
        
        // Hitung rekap untuk setiap tukang
        foreach ($tukangs as $tukang) {
            $kehadiran = KehadiranTukang::where('tukang_id', $tukang->id)
                                        ->bulan($tahun, $bulan)
                                        ->get();
            
            $tukang->total_hadir = $kehadiran->where('status', 'hadir')->count();
            $tukang->total_setengah_hari = $kehadiran->where('status', 'setengah_hari')->count();
            $tukang->total_tidak_hadir = $kehadiran->where('status', 'tidak_hadir')->count();
            
            // Pisahkan lembur normal dan lembur cash
            $tukang->total_lembur_full = $kehadiran->where('lembur', 'full')->where('lembur_dibayar_cash', false)->count();
            $tukang->total_lembur_setengah = $kehadiran->where('lembur', 'setengah_hari')->where('lembur_dibayar_cash', false)->count();
            $tukang->total_lembur_full_cash = $kehadiran->where('lembur', 'full')->where('lembur_dibayar_cash', true)->count();
            $tukang->total_lembur_setengah_cash = $kehadiran->where('lembur', 'setengah_hari')->where('lembur_dibayar_cash', true)->count();
            
            $tukang->total_upah = $kehadiran->sum('total_upah');
            $tukang->detail_kehadiran = $kehadiran;
        }
        
        $data = [
            'tukangs' => $tukangs,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanNama' => Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY')
        ];
        
        return view('manajemen-tukang.kehadiran.rekap', $data);
    }
    
    /**
     * Export PDF Rekap Kehadiran
     */
    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        // Ambil semua tukang aktif
        $tukangs = Tukang::where('status', 'aktif')
                    ->orderBy('kode_tukang')
                    ->get();
        
        // Hitung rekap untuk setiap tukang
        foreach ($tukangs as $tukang) {
            $kehadiran = KehadiranTukang::where('tukang_id', $tukang->id)
                                        ->bulan($tahun, $bulan)
                                        ->get();
            
            $tukang->total_hadir = $kehadiran->where('status', 'hadir')->count();
            $tukang->total_setengah_hari = $kehadiran->where('status', 'setengah_hari')->count();
            $tukang->total_tidak_hadir = $kehadiran->where('status', 'tidak_hadir')->count();
            
            // Pisahkan lembur normal dan lembur cash
            $tukang->total_lembur_full = $kehadiran->where('lembur', 'full')->where('lembur_dibayar_cash', false)->count();
            $tukang->total_lembur_setengah = $kehadiran->where('lembur', 'setengah_hari')->where('lembur_dibayar_cash', false)->count();
            $tukang->total_lembur_full_cash = $kehadiran->where('lembur', 'full')->where('lembur_dibayar_cash', true)->count();
            $tukang->total_lembur_setengah_cash = $kehadiran->where('lembur', 'setengah_hari')->where('lembur_dibayar_cash', true)->count();
            
            $tukang->total_upah = $kehadiran->sum('total_upah');
        }
        
        $data = [
            'tukangs' => $tukangs,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanNama' => Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY')
        ];
        
        $pdf = \PDF::loadView('manajemen-tukang.kehadiran.rekap-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('Rekap-Kehadiran-Tukang-' . $data['bulanNama'] . '.pdf');
    }
    
    /**
     * Detail kehadiran per tukang
     */
    public function detail($tukang_id, Request $request)
    {
        $tukang = Tukang::findOrFail($tukang_id);
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        $kehadiran = KehadiranTukang::where('tukang_id', $tukang_id)
                                    ->bulan($tahun, $bulan)
                                    ->orderBy('tanggal')
                                    ->get();
        
        $data = [
            'tukang' => $tukang,
            'kehadiran' => $kehadiran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanNama' => Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY'),
            'total_upah' => $kehadiran->sum('total_upah')
        ];
        
        return view('manajemen-tukang.kehadiran.detail', $data);
    }
    
    /**
     * Hapus data kehadiran
     */
    public function destroy($id)
    {
        try {
            $kehadiran = KehadiranTukang::findOrFail($id);
            $kehadiran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data kehadiran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Sync data kehadiran ke tabel keuangan_tukangs
     * Auto-create/update transaksi upah harian dan lembur
     */
    private function syncKeuangan(KehadiranTukang $kehadiran)
    {
        try {
            DB::beginTransaction();
            
            // 1. Sync Upah Harian (jika hadir atau setengah hari)
            if (in_array($kehadiran->status, ['hadir', 'setengah_hari']) && $kehadiran->upah_harian > 0) {
                KeuanganTukang::updateOrCreate(
                    [
                        'tukang_id' => $kehadiran->tukang_id,
                        'tanggal' => $kehadiran->tanggal,
                        'jenis_transaksi' => 'upah_harian',
                        'kehadiran_tukang_id' => $kehadiran->id
                    ],
                    [
                        'jumlah' => $kehadiran->upah_harian,
                        'tipe' => 'debit',
                        'keterangan' => 'Upah harian - ' . ucwords(str_replace('_', ' ', $kehadiran->status)),
                        'dicatat_oleh' => Auth::user()->name ?? 'System'
                    ]
                );
            } else {
                // Hapus transaksi upah harian jika tidak hadir
                KeuanganTukang::where('kehadiran_tukang_id', $kehadiran->id)
                    ->where('jenis_transaksi', 'upah_harian')
                    ->delete();
            }
            
            // 2. Sync Upah Lembur
            if ($kehadiran->lembur != 'tidak' && $kehadiran->upah_lembur > 0) {
                $jenisLembur = $kehadiran->lembur == 'full' ? 'lembur_full' : 'lembur_setengah';
                
                KeuanganTukang::updateOrCreate(
                    [
                        'tukang_id' => $kehadiran->tukang_id,
                        'tanggal' => $kehadiran->tanggal,
                        'jenis_transaksi' => $jenisLembur,
                        'kehadiran_tukang_id' => $kehadiran->id
                    ],
                    [
                        'jumlah' => $kehadiran->upah_lembur,
                        'tipe' => 'debit',
                        'keterangan' => 'Upah lembur - ' . ($kehadiran->lembur == 'full' ? 'Full' : 'Setengah Hari') . 
                                        ($kehadiran->lembur_dibayar_cash ? ' (Cash)' : ' (Kamis)'),
                        'dicatat_oleh' => Auth::user()->name ?? 'System'
                    ]
                );
            } else {
                // Hapus transaksi lembur jika tidak lembur
                KeuanganTukang::where('kehadiran_tukang_id', $kehadiran->id)
                    ->whereIn('jenis_transaksi', ['lembur_full', 'lembur_setengah'])
                    ->delete();
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sync Keuangan Error: ' . $e->getMessage());
            return false;
        }
    }
}
