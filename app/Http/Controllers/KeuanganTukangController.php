<?php

namespace App\Http\Controllers;

use App\Models\Tukang;
use App\Models\KeuanganTukang;
use App\Models\PinjamanTukang;
use App\Models\PotonganTukang;
use App\Models\KehadiranTukang;
use App\Models\PembayaranGajiTukang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class KeuanganTukangController extends Controller
{
    /**
     * Dashboard Keuangan Tukang - Overview Per Minggu (Sabtu-Kamis)
     */
    public function index(Request $request)
    {
        // Hitung periode minggu ini (Sabtu minggu lalu s/d Kamis minggu ini)
        // SIMULASI: Gunakan tanggal 13 November 2025 (Kamis) untuk demo
        $today = Carbon::parse('2025-11-13'); // TODO: Ganti ke Carbon::now() untuk production
        
        // Cari Kamis terakhir atau hari ini jika Kamis
        $kamis = $today->copy();
        while ($kamis->dayOfWeek !== Carbon::THURSDAY) {
            if ($kamis->dayOfWeek < Carbon::THURSDAY) {
                $kamis->subWeek(); // Mundur ke minggu lalu
            }
            $kamis->subDay();
        }
        
        // Sabtu sebelum Kamis (6 hari ke belakang dari Kamis)
        $sabtu = $kamis->copy()->subDays(5);
        
        // Ambil semua tukang aktif
        $tukangs = Tukang::where('status', 'aktif')
                    ->orderBy('kode_tukang')
                    ->get();
        
        // Hitung keuangan untuk setiap tukang
        foreach ($tukangs as $tukang) {
            // Total upah harian minggu ini (Sabtu-Kamis)
            $tukang->total_upah_harian = KeuanganTukang::where('tukang_id', $tukang->id)
                                        ->whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
                                        ->where('jenis_transaksi', 'upah_harian')
                                        ->sum('jumlah');
            
            // Total lembur minggu ini (full + setengah + cash)
            $tukang->total_lembur = KeuanganTukang::where('tukang_id', $tukang->id)
                                        ->whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
                                        ->whereIn('jenis_transaksi', ['lembur_full', 'lembur_setengah', 'lembur_cash'])
                                        ->sum('jumlah');
            
            // Total potongan minggu ini
            $tukang->total_potongan = KeuanganTukang::where('tukang_id', $tukang->id)
                                        ->whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
                                        ->where('tipe', 'kredit')
                                        ->sum('jumlah');
            
            // Pinjaman aktif (total keseluruhan)
            $tukang->pinjaman_aktif = PinjamanTukang::where('tukang_id', $tukang->id)
                                        ->aktif()
                                        ->sum('sisa_pinjaman');
            
            // Hitung cicilan mingguan jika auto potong aktif
            $cicilanMingguan = 0;
            if ($tukang->auto_potong_pinjaman && $tukang->pinjaman_aktif > 0) {
                $cicilanMingguan = PinjamanTukang::where('tukang_id', $tukang->id)
                                        ->aktif()
                                        ->sum('cicilan_per_minggu');
            }
            
            // Total bersih (jika auto potong aktif, kurangi cicilan mingguan)
            $tukang->total_bersih = $tukang->total_upah_harian + $tukang->total_lembur - $tukang->total_potongan - $cicilanMingguan;
            
            // Simpan info cicilan untuk ditampilkan
            $tukang->cicilan_mingguan = $cicilanMingguan;
        }
        
        $data = [
            'tukangs' => $tukangs,
            'periode' => $sabtu->locale('id')->isoFormat('D MMMM') . ' - ' . $kamis->locale('id')->isoFormat('D MMMM YYYY'),
            'sabtu' => $sabtu,
            'kamis' => $kamis
        ];
        
        return view('keuangan-tukang.index', $data);
    }
    
    /**
     * Detail Keuangan Per Tukang
     */
    public function detail($tukang_id, Request $request)
    {
        $tukang = Tukang::findOrFail($tukang_id);
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        // Ambil semua transaksi keuangan
        $transaksi = KeuanganTukang::where('tukang_id', $tukang_id)
                                    ->bulan($tahun, $bulan)
                                    ->orderBy('tanggal')
                                    ->with(['kehadiranTukang', 'pinjamanTukang', 'potonganTukang'])
                                    ->get();
        
        // Hitung summary
        $summary = [
            'total_debit' => $transaksi->where('tipe', 'debit')->sum('jumlah'),
            'total_kredit' => $transaksi->where('tipe', 'kredit')->sum('jumlah'),
            'total_bersih' => $transaksi->where('tipe', 'debit')->sum('jumlah') - $transaksi->where('tipe', 'kredit')->sum('jumlah'),
        ];
        
        // Pinjaman aktif
        $pinjamanAktif = PinjamanTukang::where('tukang_id', $tukang_id)
                                        ->aktif()
                                        ->get();
        
        $data = [
            'tukang' => $tukang,
            'transaksi' => $transaksi,
            'summary' => $summary,
            'pinjamanAktif' => $pinjamanAktif,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanNama' => Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY')
        ];
        
        return view('keuangan-tukang.detail', $data);
    }
    
    /**
     * Toggle Auto Potong Pinjaman dari Gaji
     */
    public function togglePotonganPinjaman($tukang_id)
    {
        try {
            $tukang = Tukang::findOrFail($tukang_id);
            $tukang->auto_potong_pinjaman = !$tukang->auto_potong_pinjaman;
            $tukang->save();
            
            $status = $tukang->auto_potong_pinjaman ? 'AKTIF' : 'NONAKTIF';
            $message = "Potongan pinjaman otomatis untuk {$tukang->nama_tukang} sekarang {$status}";
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $tukang->auto_potong_pinjaman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Status Pembayaran Minggu Ini (untuk dashboard)
     */
    public function statusPembayaran($tukang_id, Request $request)
    {
        try {
            $tukang = Tukang::findOrFail($tukang_id);
            
            // Parse periode dari request
            if ($request->has('periode')) {
                $periode = explode('|', $request->periode);
                $sabtu = Carbon::parse($periode[0]);
                $kamis = Carbon::parse($periode[1]);
            } else {
                // Default: minggu ini
                $today = Carbon::now();
                $kamis = $today->copy();
                while ($kamis->dayOfWeek !== Carbon::THURSDAY) {
                    if ($kamis->dayOfWeek < Carbon::THURSDAY) {
                        $kamis->subWeek();
                    }
                    $kamis->subDay();
                }
                $sabtu = $kamis->copy()->subDays(5);
            }
            
            // Ambil data pembayaran minggu ini
            $pembayaran = PembayaranGajiTukang::where('tukang_id', $tukang_id)
                ->whereBetween('tanggal_bayar', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
                ->orderBy('tanggal_bayar', 'desc')
                ->get()
                ->map(function($p) {
                    return [
                        'tanggal' => Carbon::parse($p->tanggal_bayar)->locale('id')->isoFormat('dddd, D MMMM Y'),
                        'jumlah' => $p->jumlah_bayar,
                        'status' => $p->status
                    ];
                });
            
            return response()->json([
                'success' => true,
                'nama_tukang' => $tukang->nama_tukang,
                'pembayaran' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download Slip Gaji Minggu Ini
     */
    public function downloadSlipMingguIni($tukang_id)
    {
        $tukang = Tukang::findOrFail($tukang_id);
        
        // Hitung periode minggu ini
        $today = Carbon::now();
        $kamis = $today->copy();
        while ($kamis->dayOfWeek !== Carbon::THURSDAY) {
            if ($kamis->dayOfWeek < Carbon::THURSDAY) {
                $kamis->subWeek();
            }
            $kamis->subDay();
        }
        $sabtu = $kamis->copy()->subDays(5);
        
        // Hitung detail keuangan
        $upahHarian = KeuanganTukang::where('tukang_id', $tukang_id)
            ->whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
            ->where('jenis_transaksi', 'upah_harian')
            ->sum('jumlah');
        
        $lembur = KeuanganTukang::where('tukang_id', $tukang_id)
            ->whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
            ->whereIn('jenis_transaksi', ['lembur_full', 'lembur_setengah', 'lembur_cash'])
            ->sum('jumlah');
        
        $potongan = KeuanganTukang::where('tukang_id', $tukang_id)
            ->whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
            ->where('tipe', 'kredit')
            ->sum('jumlah');
        
        $cicilanMingguan = 0;
        if ($tukang->auto_potong_pinjaman) {
            $cicilanMingguan = PinjamanTukang::where('tukang_id', $tukang_id)
                ->aktif()
                ->sum('cicilan_per_minggu');
        }
        
        $totalBersih = $upahHarian + $lembur - $potongan - $cicilanMingguan;
        
        $data = [
            'tukang' => $tukang,
            'periode' => $sabtu->locale('id')->isoFormat('D MMMM') . ' - ' . $kamis->locale('id')->isoFormat('D MMMM Y'),
            'upah_harian' => $upahHarian,
            'lembur' => $lembur,
            'potongan' => $potongan,
            'cicilan' => $cicilanMingguan,
            'total_bersih' => $totalBersih
        ];
        
        $pdf = PDF::loadView('keuangan-tukang.slip-gaji-pdf', $data);
        return $pdf->download('Slip_Gaji_' . $tukang->kode_tukang . '_' . $sabtu->format('Ymd') . '.pdf');
    }
    
    /**
     * Halaman Lembur Cash - untuk bayar lembur hari ini
     */
    public function lemburCash(Request $request)
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
        
        return view('keuangan-tukang.lembur-cash', compact(
            'kehadirans',
            'tanggal',
            'hariNama',
            'isJumat'
        ));
    }
    
    /**
     * Toggle Lembur Cash (pindahan dari KehadiranTukangController)
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
            
            DB::beginTransaction();
            
            try {
                // Toggle cash on/off
                $kehadiran->lembur_dibayar_cash = !$kehadiran->lembur_dibayar_cash;
                
                // Jika ON, catat transaksi keuangan
                if ($kehadiran->lembur_dibayar_cash) {
                    // Hapus transaksi lembur lama jika ada
                    KeuanganTukang::where('kehadiran_tukang_id', $kehadiran->id)
                                    ->whereIn('jenis_transaksi', ['lembur_full', 'lembur_setengah'])
                                    ->delete();
                    
                    // Buat transaksi lembur cash
                    KeuanganTukang::create([
                        'tukang_id' => $kehadiran->tukang_id,
                        'tanggal' => $kehadiran->tanggal,
                        'jenis_transaksi' => 'lembur_cash',
                        'jumlah' => $kehadiran->upah_lembur,
                        'tipe' => 'debit',
                        'kehadiran_tukang_id' => $kehadiran->id,
                        'keterangan' => 'Lembur ' . ($kehadiran->lembur == 'full' ? 'Full Day' : 'Setengah Hari') . ' - Dibayar Cash',
                        'dicatat_oleh' => Auth::user()->name ?? 'Admin'
                    ]);
                } else {
                    // Jika OFF, hapus transaksi cash dan buat transaksi lembur normal
                    KeuanganTukang::where('kehadiran_tukang_id', $kehadiran->id)
                                    ->where('jenis_transaksi', 'lembur_cash')
                                    ->delete();
                    
                    // Buat transaksi lembur normal (dibayar hari Kamis)
                    $jenisLembur = $kehadiran->lembur == 'full' ? 'lembur_full' : 'lembur_setengah';
                    KeuanganTukang::create([
                        'tukang_id' => $kehadiran->tukang_id,
                        'tanggal' => $kehadiran->tanggal,
                        'jenis_transaksi' => $jenisLembur,
                        'jumlah' => $kehadiran->upah_lembur,
                        'tipe' => 'debit',
                        'kehadiran_tukang_id' => $kehadiran->id,
                        'keterangan' => 'Lembur ' . ($kehadiran->lembur == 'full' ? 'Full Day' : 'Setengah Hari'),
                        'dicatat_oleh' => Auth::user()->name ?? 'Admin'
                    ]);
                }
                
                $kehadiran->hitungUpah();
                $kehadiran->save();
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'lembur_dibayar_cash' => $kehadiran->lembur_dibayar_cash,
                    'upah' => number_format($kehadiran->total_upah, 0, ',', '.'),
                    'upah_lembur' => number_format($kehadiran->upah_lembur, 0, ',', '.'),
                    'tanggal_bayar' => $kehadiran->tanggal_bayar_lembur ? $kehadiran->tanggal_bayar_lembur->format('d/m/Y') : null
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Halaman Pinjaman Tukang
     */
    public function pinjaman(Request $request)
    {
        $status = $request->input('status', 'aktif');
        $tukang_id = $request->input('tukang_id');
        
        $query = PinjamanTukang::with('tukang');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($tukang_id) {
            $query->where('tukang_id', $tukang_id);
        }
        
        $pinjamans = $query->orderBy('tanggal_pinjaman', 'desc')->get();
        
        // Load tukangs dengan relasi pinjaman aktif
        $tukangs = Tukang::where('status', 'aktif')
                    ->with(['pinjamans' => function($q) {
                        $q->where('status', 'aktif');
                    }])
                    ->orderBy('nama_tukang')
                    ->get();
        
        return view('keuangan-tukang.pinjaman.index', compact('pinjamans', 'tukangs', 'status'));
    }
    
    /**
     * Simpan Pinjaman Baru (atau tambah pinjaman ke yang aktif)
     */
    public function storePinjaman(Request $request)
    {
        // Bersihkan input dari format ribuan (titik/koma)
        $jumlah_pinjaman = str_replace(['.', ','], '', $request->jumlah_pinjaman);
        $cicilan_per_minggu = $request->cicilan_per_minggu ? str_replace(['.', ','], '', $request->cicilan_per_minggu) : null;
        
        $request->merge([
            'jumlah_pinjaman' => $jumlah_pinjaman,
            'cicilan_per_minggu' => $cicilan_per_minggu
        ]);
        
        $request->validate([
            'tukang_id' => 'required|exists:tukangs,id',
            'tanggal_pinjaman' => 'required|date',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'cicilan_per_minggu' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Cek apakah tukang ini masih punya pinjaman aktif
            $pinjamanAktif = PinjamanTukang::where('tukang_id', $request->tukang_id)
                                          ->where('status', 'aktif')
                                          ->first();
            
            // Upload foto bukti jika ada
            $fotoBuktiPath = null;
            if ($request->hasFile('foto_bukti')) {
                $file = $request->file('foto_bukti');
                $filename = 'pinjaman_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $fotoBuktiPath = $file->storeAs('pinjaman_bukti', $filename, 'public');
            }
            
            if ($pinjamanAktif) {
                // JIKA MASIH ADA PINJAMAN AKTIF: Tambahkan ke pinjaman yang ada
                $pinjamanAktif->jumlah_pinjaman += $request->jumlah_pinjaman;
                $pinjamanAktif->sisa_pinjaman += $request->jumlah_pinjaman;
                
                // Update foto bukti jika ada yang baru
                if ($fotoBuktiPath) {
                    // Hapus foto lama jika ada
                    if ($pinjamanAktif->foto_bukti) {
                        Storage::disk('public')->delete($pinjamanAktif->foto_bukti);
                    }
                    $pinjamanAktif->foto_bukti = $fotoBuktiPath;
                }
                
                // Update keterangan (tambahkan ke yang lama)
                if ($request->keterangan) {
                    $pinjamanAktif->keterangan .= "\n[" . date('d/m/Y', strtotime($request->tanggal_pinjaman)) . "] Pinjaman tambahan: " . $request->keterangan;
                }
                
                // Update cicilan jika diisi
                if ($request->cicilan_per_minggu) {
                    $pinjamanAktif->cicilan_per_minggu = $request->cicilan_per_minggu;
                }
                
                $pinjamanAktif->save();
                $pinjaman = $pinjamanAktif;
                
                $pesanSukses = 'Pinjaman berhasil ditambahkan ke pinjaman aktif. Total pinjaman sekarang: Rp ' . number_format($pinjamanAktif->sisa_pinjaman, 0, ',', '.');
            } else {
                // JIKA BELUM ADA PINJAMAN AKTIF: Buat pinjaman baru
                $pinjaman = PinjamanTukang::create([
                    'tukang_id' => $request->tukang_id,
                    'tanggal_pinjaman' => $request->tanggal_pinjaman,
                    'jumlah_pinjaman' => $request->jumlah_pinjaman,
                    'jumlah_terbayar' => 0,
                    'sisa_pinjaman' => $request->jumlah_pinjaman,
                    'status' => 'aktif',
                    'cicilan_per_minggu' => $request->cicilan_per_minggu,
                    'keterangan' => $request->keterangan,
                    'foto_bukti' => $fotoBuktiPath,
                    'dicatat_oleh' => Auth::user()->name ?? 'Admin'
                ]);
                
                $pesanSukses = 'Pinjaman baru berhasil ditambahkan';
            }
            
            // Catat transaksi keuangan (kredit = uang keluar untuk tukang)
            KeuanganTukang::create([
                'tukang_id' => $request->tukang_id,
                'tanggal' => $request->tanggal_pinjaman,
                'jenis_transaksi' => 'pinjaman',
                'jumlah' => $request->jumlah_pinjaman,
                'tipe' => 'kredit', // Kredit karena mengurangi gaji
                'pinjaman_tukang_id' => $pinjaman->id,
                'keterangan' => 'Pinjaman: ' . ($request->keterangan ?? '-'),
                'dicatat_oleh' => Auth::user()->name ?? 'Admin'
            ]);
            
            DB::commit();
            
            return Redirect::back()->with(messageSuccess($pesanSukses));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    
    /**
     * Detail Pinjaman
     */
    public function detailPinjaman($id)
    {
        $pinjaman = PinjamanTukang::with('tukang')->findOrFail($id);
        
        // Ambil riwayat pembayaran cicilan dari transaksi keuangan
        $riwayatBayar = KeuanganTukang::where('pinjaman_tukang_id', $id)
                                     ->where('jenis_transaksi', 'bayar_pinjaman')
                                     ->orderBy('tanggal', 'desc')
                                     ->get();
        
        return view('keuangan-tukang.pinjaman.detail', compact('pinjaman', 'riwayatBayar'));
    }
    
    /**
     * Download Formulir Pinjaman
     */
    public function downloadFormulirPinjaman($id)
    {
        $pinjaman = PinjamanTukang::with('tukang')->findOrFail($id);
        
        $data = [
            'pinjaman' => $pinjaman,
            'tanggal_cetak' => now()->format('d M Y')
        ];
        
        $pdf = \PDF::loadView('keuangan-tukang.pinjaman.formulir-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Formulir-Pinjaman-' . $pinjaman->tukang->kode_tukang . '-' . date('dmY') . '.pdf';
        
        return $pdf->stream($filename);
    }
    
    /**
     * Download Formulir Pinjaman KOSONG (Template Blanko)
     */
    public function downloadFormulirKosong()
    {
        $data = [
            'tanggal_cetak' => now()->format('d M Y')
        ];
        
        $pdf = \PDF::loadView('keuangan-tukang.pinjaman.formulir-kosong-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Formulir-Permohonan-Pinjaman-Kosong.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Bayar Cicilan Pinjaman
     */
    public function bayarCicilan(Request $request, $id)
    {
        // Bersihkan input dari format ribuan
        $jumlah_bayar = str_replace(['.', ','], '', $request->jumlah_bayar);
        $request->merge(['jumlah_bayar' => $jumlah_bayar]);
        
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'nullable|date'
        ]);
        
        DB::beginTransaction();
        
        try {
            $pinjaman = PinjamanTukang::findOrFail($id);
            
            $jumlahBayar = $request->jumlah_bayar;
            
            // Validasi jumlah tidak melebihi sisa pinjaman
            if ($jumlahBayar > $pinjaman->sisa_pinjaman) {
                $jumlahBayar = $pinjaman->sisa_pinjaman;
            }
            
            // Update pinjaman
            $pinjaman->bayarCicilan($jumlahBayar);
            
            // Catat transaksi pembayaran
            KeuanganTukang::create([
                'tukang_id' => $pinjaman->tukang_id,
                'tanggal' => $request->tanggal_bayar ?? now(),
                'jenis_transaksi' => 'pembayaran_pinjaman',
                'jumlah' => $jumlahBayar,
                'tipe' => 'kredit', // Kredit karena dipotong dari gaji
                'pinjaman_tukang_id' => $pinjaman->id,
                'keterangan' => 'Bayar Cicilan Pinjaman #' . $pinjaman->id,
                'dicatat_oleh' => Auth::user()->name ?? 'Admin'
            ]);
            
            DB::commit();
            
            return Redirect::back()->with(messageSuccess('Cicilan berhasil dibayar'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    
    /**
     * Halaman Potongan Tukang
     */
    public function potongan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $tukang_id = $request->input('tukang_id');
        
        $query = PotonganTukang::with('tukang');
        
        if ($tukang_id) {
            $query->where('tukang_id', $tukang_id);
        }
        
        $potongans = $query->bulan($tahun, $bulan)
                            ->orderBy('tanggal', 'desc')
                            ->get();
        
        $tukangs = Tukang::where('status', 'aktif')->orderBy('nama_tukang')->get();
        
        return view('keuangan-tukang.potongan.index', compact('potongans', 'tukangs', 'bulan', 'tahun'));
    }
    
    /**
     * Simpan Potongan Baru
     */
    public function storePotongan(Request $request)
    {
        $request->validate([
            'tukang_id' => 'required|exists:tukangs,id',
            'tanggal' => 'required|date',
            'jenis_potongan' => 'required|in:keterlambatan,tidak_hadir,kerusakan_alat,pinjaman,denda,lain_lain',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Buat potongan
            $potongan = PotonganTukang::create([
                'tukang_id' => $request->tukang_id,
                'tanggal' => $request->tanggal,
                'jenis_potongan' => $request->jenis_potongan,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'dicatat_oleh' => Auth::user()->name ?? 'Admin'
            ]);
            
            // Catat transaksi keuangan
            KeuanganTukang::create([
                'tukang_id' => $request->tukang_id,
                'tanggal' => $request->tanggal,
                'jenis_transaksi' => 'potongan',
                'jumlah' => $request->jumlah,
                'tipe' => 'kredit', // Kredit karena mengurangi gaji
                'potongan_tukang_id' => $potongan->id,
                'keterangan' => 'Potongan ' . ucwords(str_replace('_', ' ', $request->jenis_potongan)) . ': ' . ($request->keterangan ?? '-'),
                'dicatat_oleh' => Auth::user()->name ?? 'Admin'
            ]);
            
            DB::commit();
            
            return Redirect::back()->with(messageSuccess('Potongan berhasil ditambahkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    
    /**
     * Hapus Potongan
     */
    public function destroyPotongan($id)
    {
        try {
            DB::beginTransaction();
            
            $potongan = PotonganTukang::findOrFail($id);
            
            // Hapus transaksi keuangan terkait
            KeuanganTukang::where('potongan_tukang_id', $id)->delete();
            
            // Hapus potongan
            $potongan->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Potongan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Laporan Keuangan (Rekap Per Tukang)
     */
    public function laporan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        // Ambil semua tukang aktif
        $tukangs = Tukang::where('status', 'aktif')
                    ->orderBy('kode_tukang')
                    ->get();
        
        // Hitung keuangan untuk setiap tukang
        foreach ($tukangs as $tukang) {
            $transaksi = KeuanganTukang::where('tukang_id', $tukang->id)
                                        ->bulan($tahun, $bulan)
                                        ->get();
            
            $tukang->total_debit = $transaksi->where('tipe', 'debit')->sum('jumlah');
            $tukang->total_kredit = $transaksi->where('tipe', 'kredit')->sum('jumlah');
            $tukang->total_bersih = $tukang->total_debit - $tukang->total_kredit;
            $tukang->pinjaman_aktif = PinjamanTukang::where('tukang_id', $tukang->id)
                                        ->aktif()
                                        ->sum('sisa_pinjaman');
        }
        
        $data = [
            'tukangs' => $tukangs,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanNama' => Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY')
        ];
        
        return view('keuangan-tukang.laporan', $data);
    }
    
    /**
     * Export PDF Laporan Keuangan
     */
    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        // Ambil semua tukang aktif
        $tukangs = Tukang::where('status', 'aktif')
                    ->orderBy('kode_tukang')
                    ->get();
        
        // Hitung keuangan untuk setiap tukang
        foreach ($tukangs as $tukang) {
            $transaksi = KeuanganTukang::where('tukang_id', $tukang->id)
                                        ->bulan($tahun, $bulan)
                                        ->get();
            
            $tukang->total_debit = $transaksi->where('tipe', 'debit')->sum('jumlah');
            $tukang->total_kredit = $transaksi->where('tipe', 'kredit')->sum('jumlah');
            $tukang->total_bersih = $tukang->total_debit - $tukang->total_kredit;
            
            // Hitung total pinjaman aktif
            $tukang->pinjaman_aktif = PinjamanTukang::where('tukang_id', $tukang->id)
                                                    ->where('status', 'aktif')
                                                    ->sum('sisa_pinjaman');
        }
        
        $data = [
            'tukangs' => $tukangs,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanNama' => Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY')
        ];
        
        $pdf = \PDF::loadView('keuangan-tukang.laporan-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('Laporan-Keuangan-Tukang-' . $data['bulanNama'] . '.pdf');
    }
    
    /**
     * ==========================================
     * PEMBAGIAN GAJI KAMIS DENGAN TTD DIGITAL
     * ==========================================
     */
    
    /**
     * Halaman Pembagian Gaji Kamis
     */
    public function pembagianGajiKamis(Request $request)
    {
        // Hitung periode minggu ini (Sabtu minggu lalu s/d Jumat minggu ini)
        $today = Carbon::now();
        
        // Cari Kamis terakhir atau hari ini jika Kamis
        $kamis = $today->copy();
        while ($kamis->dayOfWeek !== Carbon::THURSDAY) {
            $kamis->subDay();
        }
        
        // Periode: Sabtu minggu lalu s/d Jumat minggu ini
        $periodeAkhir = $kamis->copy()->subDay(); // Jumat
        $periodeMulai = $periodeAkhir->copy()->subDays(6); // Sabtu minggu lalu
        
        // Ambil semua tukang aktif
        $tukangs = Tukang::where('status', 'aktif')
                        ->orderBy('kode_tukang')
                        ->get();
        
        // Untuk setiap tukang, hitung total upah & potongan
        foreach ($tukangs as $tukang) {
            // Cek apakah sudah ada pembayaran untuk periode ini
            $pembayaran = PembayaranGajiTukang::where('tukang_id', $tukang->id)
                                            ->periode($periodeMulai->format('Y-m-d'), $periodeAkhir->format('Y-m-d'))
                                            ->first();
            
            if ($pembayaran) {
                $tukang->pembayaran = $pembayaran;
            } else {
                // Hitung upah untuk periode ini
                $kehadiran = KehadiranTukang::where('tukang_id', $tukang->id)
                                           ->whereBetween('tanggal', [$periodeMulai, $periodeAkhir])
                                           ->get();
                
                $totalUpahHarian = $kehadiran->sum('upah_harian');
                $totalUpahLembur = $kehadiran->where('lembur', '!=', 'tidak')->sum('upah_lembur');
                $lemburCashTerbayar = $kehadiran->where('lembur_dibayar_cash', true)->sum('upah_lembur');
                
                // Hitung potongan (pinjaman, denda, dll)
                $pinjaman = PinjamanTukang::where('tukang_id', $tukang->id)
                                         ->where('status', 'aktif')
                                         ->sum('cicilan_per_minggu');
                
                $potongan = PotonganTukang::where('tukang_id', $tukang->id)
                                         ->whereDate('tanggal', '>=', $periodeMulai)
                                         ->whereDate('tanggal', '<=', $periodeAkhir)
                                         ->sum('jumlah');
                
                $totalPotongan = $pinjaman + $potongan;
                $totalKotor = $totalUpahHarian + ($totalUpahLembur - $lemburCashTerbayar);
                $totalNett = $totalKotor - $totalPotongan;
                
                // Simpan ke temporary object
                $tukang->pembayaran = (object)[
                    'status' => 'pending',
                    'total_upah_harian' => $totalUpahHarian,
                    'total_upah_lembur' => $totalUpahLembur,
                    'lembur_cash_terbayar' => $lemburCashTerbayar,
                    'total_kotor' => $totalKotor,
                    'total_potongan' => $totalPotongan,
                    'total_nett' => $totalNett
                ];
            }
        }
        
        $data = [
            'tukangs' => $tukangs,
            'periodeMulai' => $periodeMulai,
            'periodeAkhir' => $periodeAkhir,
            'periode_mulai' => $periodeMulai->format('Y-m-d'),
            'periode_akhir' => $periodeAkhir->format('Y-m-d'),
            'periodeText' => $periodeMulai->format('d M') . ' - ' . $periodeAkhir->format('d M Y')
        ];
        
        return view('keuangan-tukang.pembagian-gaji-kamis', $data);
    }
    
    /**
     * Get detail gaji tukang untuk modal TTD
     */
    public function detailGajiTukang($tukang_id, Request $request)
    {
        $periodeMulai = Carbon::parse($request->periode_mulai);
        $periodeAkhir = Carbon::parse($request->periode_akhir);
        
        $tukang = Tukang::findOrFail($tukang_id);
        
        // Hitung upah
        $kehadiran = KehadiranTukang::where('tukang_id', $tukang_id)
                                   ->whereBetween('tanggal', [$periodeMulai, $periodeAkhir])
                                   ->get();
        
        $totalUpahHarian = $kehadiran->sum('upah_harian');
        $totalUpahLembur = $kehadiran->where('lembur', '!=', 'tidak')->sum('upah_lembur');
        $lemburCashTerbayar = $kehadiran->where('lembur_dibayar_cash', true)->sum('upah_lembur');
        
        // Hitung potongan
        $pinjamanAktif = PinjamanTukang::where('tukang_id', $tukang_id)
                                      ->where('status', 'aktif')
                                      ->get();
        
        $potonganLain = PotonganTukang::where('tukang_id', $tukang_id)
                                     ->whereDate('tanggal', '>=', $periodeMulai)
                                     ->whereDate('tanggal', '<=', $periodeAkhir)
                                     ->get();
        
        $rincianPotongan = [];
        $totalPotongan = 0;
        
        foreach ($pinjamanAktif as $p) {
            $rincianPotongan[] = [
                'jenis' => 'Cicilan Pinjaman',
                'keterangan' => 'Sisa: Rp ' . number_format($p->sisa_pinjaman, 0, ',', '.'),
                'jumlah' => $p->cicilan_per_minggu
            ];
            $totalPotongan += $p->cicilan_per_minggu;
        }
        
        foreach ($potonganLain as $p) {
            $rincianPotongan[] = [
                'jenis' => ucwords(str_replace('_', ' ', $p->jenis_potongan)),
                'keterangan' => $p->keterangan,
                'jumlah' => $p->jumlah
            ];
            $totalPotongan += $p->jumlah;
        }
        
        $totalKotor = $totalUpahHarian + ($totalUpahLembur - $lemburCashTerbayar);
        $totalNett = $totalKotor - $totalPotongan;
        
        return response()->json([
            'success' => true,
            'tukang' => $tukang,
            'periode_mulai' => $periodeMulai->format('d M Y'),
            'periode_akhir' => $periodeAkhir->format('d M Y'),
            'total_upah_harian' => $totalUpahHarian,
            'total_upah_lembur' => $totalUpahLembur,
            'lembur_cash_terbayar' => $lemburCashTerbayar,
            'total_kotor' => $totalKotor,
            'rincian_potongan' => $rincianPotongan,
            'total_potongan' => $totalPotongan,
            'total_nett' => $totalNett
        ]);
    }
    
    /**
     * Simpan pembayaran gaji + TTD digital
     */
    public function simpanPembayaranGaji(Request $request)
    {
        $request->validate([
            'tukang_id' => 'required|exists:tukangs,id',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date',
            'tanda_tangan' => 'required|string', // Base64 image
            'total_nett' => 'required|numeric'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Cek apakah sudah ada pembayaran untuk periode ini
            $existing = PembayaranGajiTukang::where('tukang_id', $request->tukang_id)
                                          ->periode($request->periode_mulai, $request->periode_akhir)
                                          ->first();
            
            if ($existing && $existing->status == 'lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gaji untuk periode ini sudah dibayar!'
                ], 400);
            }
            
            // Hitung ulang untuk validasi
            $periodeMulai = Carbon::parse($request->periode_mulai);
            $periodeAkhir = Carbon::parse($request->periode_akhir);
            
            $kehadiran = KehadiranTukang::where('tukang_id', $request->tukang_id)
                                       ->whereBetween('tanggal', [$periodeMulai, $periodeAkhir])
                                       ->get();
            
            $totalUpahHarian = $kehadiran->sum('upah_harian');
            $totalUpahLembur = $kehadiran->where('lembur', '!=', 'tidak')->sum('upah_lembur');
            $lemburCashTerbayar = $kehadiran->where('lembur_dibayar_cash', true)->sum('upah_lembur');
            
            // Potongan
            $pinjamanAktif = PinjamanTukang::where('tukang_id', $request->tukang_id)
                                          ->where('status', 'aktif')
                                          ->get();
            
            $potonganLain = PotonganTukang::where('tukang_id', $request->tukang_id)
                                         ->whereDate('tanggal', '>=', $periodeMulai)
                                         ->whereDate('tanggal', '<=', $periodeAkhir)
                                         ->get();
            
            $rincianPotongan = [];
            $totalPotongan = 0;
            
            foreach ($pinjamanAktif as $p) {
                $rincianPotongan[] = [
                    'id' => $p->id,
                    'jenis' => 'pinjaman',
                    'keterangan' => 'Cicilan Pinjaman - Sisa: Rp ' . number_format($p->sisa_pinjaman, 0, ',', '.'),
                    'jumlah' => $p->cicilan_per_minggu
                ];
                $totalPotongan += $p->cicilan_per_minggu;
                
                // Bayar cicilan
                $p->bayarCicilan($p->cicilan_per_minggu);
            }
            
            foreach ($potonganLain as $p) {
                $rincianPotongan[] = [
                    'id' => $p->id,
                    'jenis' => $p->jenis_potongan,
                    'keterangan' => $p->keterangan,
                    'jumlah' => $p->jumlah
                ];
                $totalPotongan += $p->jumlah;
            }
            
            $totalKotor = $totalUpahHarian + ($totalUpahLembur - $lemburCashTerbayar);
            $totalNett = $totalKotor - $totalPotongan;
            
            // Simpan pembayaran
            $pembayaran = PembayaranGajiTukang::create([
                'tukang_id' => $request->tukang_id,
                'periode_mulai' => $request->periode_mulai,
                'periode_akhir' => $request->periode_akhir,
                'tanggal_bayar' => now(),
                'total_upah_harian' => $totalUpahHarian,
                'total_upah_lembur' => $totalUpahLembur,
                'lembur_cash_terbayar' => $lemburCashTerbayar,
                'total_kotor' => $totalKotor,
                'total_potongan' => $totalPotongan,
                'rincian_potongan' => $rincianPotongan,
                'total_nett' => $totalNett,
                'tanda_tangan_base64' => $request->tanda_tangan,
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'status' => 'lunas',
                'dibayar_oleh' => Auth::user()->name ?? 'Admin',
                'catatan' => $request->catatan
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran gaji berhasil disimpan!',
                'pembayaran_id' => $pembayaran->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download Slip Gaji PDF dengan TTD Digital
     */
    public function downloadSlipGaji($id)
    {
        $pembayaran = PembayaranGajiTukang::with('tukang')->findOrFail($id);
        
        $data = [
            'pembayaran' => $pembayaran,
            'tukang' => $pembayaran->tukang,
            'periodeText' => $pembayaran->periode_mulai->format('d M Y') . ' - ' . $pembayaran->periode_akhir->format('d M Y'),
            'tanggalBayar' => $pembayaran->tanggal_bayar->format('d F Y, H:i')
        ];
        
        $pdf = \PDF::loadView('keuangan-tukang.slip-gaji-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Slip-Gaji-' . $pembayaran->tukang->kode_tukang . '-' . $pembayaran->periode_akhir->format('d-M-Y') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Download Laporan Pembayaran Gaji Periode (Semua Tukang)
     */
    public function downloadLaporanGajiKamis(Request $request)
    {
        $periodeMulai = Carbon::parse($request->periode_mulai);
        $periodeAkhir = Carbon::parse($request->periode_akhir);
        
        $pembayarans = PembayaranGajiTukang::with('tukang')
                                          ->periode($periodeMulai->format('Y-m-d'), $periodeAkhir->format('Y-m-d'))
                                          ->orderBy('tukang_id')
                                          ->get();
        
        $data = [
            'pembayaranGaji' => $pembayarans,
            'periode_mulai' => $periodeMulai->format('Y-m-d'),
            'periode_akhir' => $periodeAkhir->format('Y-m-d'),
            'periodeText' => $periodeMulai->format('d M Y') . ' - ' . $periodeAkhir->format('d M Y')
        ];
        
        $pdf = \PDF::loadView('keuangan-tukang.laporan-gaji-kamis-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'Laporan-Gaji-Kamis-' . $periodeAkhir->format('d-M-Y') . '.pdf';
        
        return $pdf->download($filename);
    }
}
