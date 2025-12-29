<?php

namespace App\Http\Controllers;

use App\Models\PotonganPinjamanMaster;
use App\Models\PotonganPinjamanDetail;
use App\Models\Karyawan;
use App\Models\Pinjaman;
use App\Models\Cabang;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class PotonganPinjamanMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PotonganPinjamanMaster::with(['karyawan', 'pinjaman']);

        // Filter by search (nama karyawan atau NIK)
        if (!empty($request->nama_karyawan)) {
            $query->search($request->nama_karyawan);
        }

        // Filter by cabang
        if (!empty($request->kode_cabang)) {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('kode_cabang', $request->kode_cabang);
            });
        }

        // Filter by departemen
        if (!empty($request->kode_dept)) {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('kode_dept', $request->kode_dept);
            });
        }

        // Filter by status
        if (!empty($request->status)) {
            $query->byStatus($request->status);
        }

        $potongan = $query->orderBy('created_at', 'desc')->paginate(20);
        $potongan->appends($request->all());

        $data['potongan'] = $potongan;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();

        return view('payroll.potongan_pinjaman_master.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')->get();
        $data['pinjaman'] = Pinjaman::where('status', 'berjalan')
                                    ->where('kategori_peminjam', 'crew')
                                    ->whereColumn('sisa_pinjaman', '>', DB::raw('0'))
                                    ->with('karyawan')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        $data['start_year'] = config('global.start_year', 2020);
        $data['nama_bulan'] = config('global.nama_bulan');

        return view('payroll.potongan_pinjaman_master.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'cicilan_per_bulan' => 'required|numeric|min:1',
            'bulan_mulai' => 'required|integer|min:1|max:12',
            'tahun_mulai' => 'required|integer|min:2020',
            'tanggal_potongan' => 'required|integer|min:1|max:31',
        ]);

        try {
            DB::beginTransaction();

            $jumlahPinjaman = toNumber($request->jumlah_pinjaman);
            $cicilanPerBulan = toNumber($request->cicilan_per_bulan);

            // Validate cicilan tidak lebih besar dari total
            if ($cicilanPerBulan > $jumlahPinjaman) {
                return Redirect::back()->with(messageError('Cicilan per bulan tidak boleh lebih besar dari total pinjaman'));
            }

            // Calculate jumlah bulan
            $jumlahBulan = ceil($jumlahPinjaman / $cicilanPerBulan);

            // Calculate periode selesai
            $periodeSelesai = PotonganPinjamanMaster::calculatePeriodeSelesai(
                $request->bulan_mulai,
                $request->tahun_mulai,
                $jumlahBulan
            );

            // Check apakah karyawan sudah punya potongan aktif di periode yang overlap
            $existingActive = PotonganPinjamanMaster::where('nik', $request->nik)
                ->where('status', 'aktif')
                ->where(function($q) use ($request, $periodeSelesai) {
                    // Check overlap periode
                    $q->where(function($q2) use ($request) {
                        $q2->whereRaw("
                            (tahun_mulai < ? OR (tahun_mulai = ? AND bulan_mulai <= ?))
                        ", [$request->tahun_mulai, $request->tahun_mulai, $request->bulan_mulai]);
                    })->where(function($q3) use ($periodeSelesai) {
                        $q3->whereRaw("
                            (tahun_selesai > ? OR (tahun_selesai = ? AND bulan_selesai >= ?))
                        ", [$periodeSelesai['tahun_selesai'], $periodeSelesai['tahun_selesai'], $periodeSelesai['bulan_selesai']]);
                    });
                })
                ->exists();

            if ($existingActive) {
                return Redirect::back()->with(messageError('Karyawan sudah memiliki potongan pinjaman aktif di periode yang sama'));
            }

            // Generate kode
            $kodePotongan = PotonganPinjamanMaster::generateKode();

            // Create master
            $master = PotonganPinjamanMaster::create([
                'kode_potongan' => $kodePotongan,
                'nik' => $request->nik,
                'pinjaman_id' => $request->pinjaman_id,
                'jumlah_pinjaman' => $jumlahPinjaman,
                'cicilan_per_bulan' => $cicilanPerBulan,
                'jumlah_bulan' => $jumlahBulan,
                'bulan_mulai' => $request->bulan_mulai,
                'tahun_mulai' => $request->tahun_mulai,
                'bulan_selesai' => $periodeSelesai['bulan_selesai'],
                'tahun_selesai' => $periodeSelesai['tahun_selesai'],
                'tanggal_potongan' => $request->tanggal_potongan,
                'sisa_pinjaman' => $jumlahPinjaman,
                'status' => 'aktif',
                'keterangan' => $request->keterangan,
                'dibuat_oleh' => auth()->id(),
            ]);

            // AUTO-GENERATE semua detail cicilan untuk semua periode
            $startDate = Carbon::create($request->tahun_mulai, $request->bulan_mulai, 1);
            $sisaPinjaman = $jumlahPinjaman;
            
            for ($i = 1; $i <= $jumlahBulan; $i++) {
                $currentDate = $startDate->copy()->addMonths($i - 1);
                
                // Hitung tanggal jatuh tempo (misal: 2025-12-25, 2026-01-25, dst)
                $tanggalJatuhTempo = Carbon::create(
                    $currentDate->year,
                    $currentDate->month,
                    min($request->tanggal_potongan, $currentDate->daysInMonth) // Adjust untuk bulan Feb
                );
                
                // Hitung jumlah cicilan (cicilan terakhir mungkin berbeda)
                $jumlahCicilan = $cicilanPerBulan;
                if ($i == $jumlahBulan) {
                    // Cicilan terakhir, gunakan sisa
                    $jumlahCicilan = $sisaPinjaman;
                } elseif ($jumlahCicilan > $sisaPinjaman) {
                    $jumlahCicilan = $sisaPinjaman;
                }
                
                // Create detail dengan status PENDING (akan dipotong saat proses slip gaji)
                PotonganPinjamanDetail::create([
                    'master_id' => $master->id,
                    'bulan' => $currentDate->month,
                    'tahun' => $currentDate->year,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'jumlah_potongan' => $jumlahCicilan,
                    'cicilan_ke' => $i,
                    'status' => 'pending', // Status pending, nanti dipotong otomatis saat generate slip gaji
                    'keterangan' => "Auto-generated: Cicilan ke-{$i} dari {$jumlahBulan} (Jatuh tempo: {$tanggalJatuhTempo->format('d M Y')})",
                ]);
                
                $sisaPinjaman -= $jumlahCicilan;
            }
            
            // Update master progress (akan tetap 0% karena semua detail masih pending)
            $master->updateProgress();

            DB::commit();

            return Redirect::route('potongan_pinjaman_master.index')
                ->with(messageSuccess('Data potongan pinjaman berhasil ditambahkan. Kode: ' . $kodePotongan));

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Gagal menyimpan data: ' . $e->getMessage()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['potongan'] = PotonganPinjamanMaster::with(['karyawan', 'pinjaman'])->findOrFail($id);
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')->get();
        $data['pinjaman'] = Pinjaman::where('status', 'berjalan')
                                    ->where('kategori_peminjam', 'crew')
                                    ->with('karyawan')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        $data['start_year'] = config('global.start_year', 2020);
        $data['nama_bulan'] = config('global.nama_bulan');

        return view('payroll.potongan_pinjaman_master.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'cicilan_per_bulan' => 'required|numeric|min:1',
            'status' => 'required|in:aktif,selesai,ditunda,dibatalkan',
        ]);

        try {
            DB::beginTransaction();

            $potongan = PotonganPinjamanMaster::findOrFail($id);

            $cicilanPerBulan = toNumber($request->cicilan_per_bulan);

            // Validate cicilan tidak lebih besar dari sisa
            if ($cicilanPerBulan > $potongan->sisa_pinjaman && $potongan->sisa_pinjaman > 0) {
                $cicilanPerBulan = $potongan->sisa_pinjaman;
            }

            // Recalculate jumlah bulan
            $sisaCicilan = $potongan->jumlah_bulan - $potongan->cicilan_terbayar;
            $jumlahBulanBaru = ceil($potongan->sisa_pinjaman / $cicilanPerBulan);

            // Recalculate periode selesai dari periode terakhir
            if ($potongan->bulan_terakhir_dipotong) {
                $periodeSelesai = PotonganPinjamanMaster::calculatePeriodeSelesai(
                    $potongan->bulan_terakhir_dipotong,
                    $potongan->tahun_terakhir_dipotong,
                    $jumlahBulanBaru
                );
            } else {
                $periodeSelesai = PotonganPinjamanMaster::calculatePeriodeSelesai(
                    $potongan->bulan_mulai,
                    $potongan->tahun_mulai,
                    $jumlahBulanBaru + $potongan->cicilan_terbayar
                );
            }

            $potongan->update([
                'cicilan_per_bulan' => $cicilanPerBulan,
                'jumlah_bulan' => $potongan->cicilan_terbayar + $jumlahBulanBaru,
                'bulan_selesai' => $periodeSelesai['bulan_selesai'],
                'tahun_selesai' => $periodeSelesai['tahun_selesai'],
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'diupdate_oleh' => auth()->id(),
            ]);

            DB::commit();

            return Redirect::back()->with(messageSuccess('Data berhasil diupdate'));

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Gagal update data: ' . $e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        try {
            $potongan = PotonganPinjamanMaster::findOrFail($id);

            // Check apakah ada detail yang sudah dipotong
            $sudahDipotong = $potongan->details()->where('status', 'dipotong')->exists();

            if ($sudahDipotong) {
                return Redirect::back()->with(messageError('Tidak dapat menghapus. Sudah ada cicilan yang dipotong. Gunakan status "Dibatalkan" sebagai gantinya.'));
            }

            $potongan->delete();

            return Redirect::back()->with(messageSuccess('Data berhasil dihapus'));

        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal menghapus data: ' . $e->getMessage()));
        }
    }

    /**
     * Halaman proses potongan bulanan
     */
    public function proses(Request $request)
    {
        $tahun = !empty($request->tahun) ? $request->tahun : date('Y');
        $bulan = !empty($request->bulan) ? $request->bulan : date('n');

        $data['start_year'] = config('global.start_year');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        // Get summary by status
        $data['summary'] = PotonganPinjamanDetail::with('master.karyawan')
            ->periode($bulan, $tahun)
            ->selectRaw('
                status,
                COUNT(*) as total_cicilan,
                SUM(jumlah_potongan) as total_potongan,
                COUNT(DISTINCT master_id) as total_karyawan
            ')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Get detail potongan
        $data['details'] = PotonganPinjamanDetail::with(['master.karyawan', 'master.pinjaman'])
            ->periode($bulan, $tahun)
            ->orderBy('status')
            ->orderByHas('master', 'nik')
            ->get();

        return view('payroll.potongan_pinjaman_master.proses', $data);
    }

    /**
     * Generate detail untuk periode tertentu
     */
    public function generateDetail(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
        ]);

        try {
            DB::beginTransaction();

            $bulan = $request->bulan;
            $tahun = $request->tahun;

            // Ambil master yang aktif di periode ini
            $masters = PotonganPinjamanMaster::with('karyawan')
                ->byStatus('aktif')
                ->activePeriode($bulan, $tahun)
                ->get();

            if ($masters->count() == 0) {
                return Redirect::back()->with('warning', 'Tidak ada potongan pinjaman aktif untuk periode ini.');
            }

            $generated = 0;
            $skipped = 0;

            foreach ($masters as $master) {
                // Check apakah detail untuk periode ini sudah ada
                $exists = PotonganPinjamanDetail::where('master_id', $master->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Hitung cicilan ke berapa
                $cicilanKe = $master->details()->where('bulan', '<', $bulan)
                    ->orWhere(function($q) use ($bulan, $tahun) {
                        $q->where('bulan', '=', $bulan)->where('tahun', '<', $tahun);
                    })
                    ->count() + 1;

                // Tentukan jumlah potongan (bisa beda di cicilan terakhir)
                $jumlahPotongan = $master->cicilan_per_bulan;
                if ($cicilanKe == $master->jumlah_bulan) {
                    // Cicilan terakhir, gunakan sisa
                    $jumlahPotongan = $master->sisa_pinjaman;
                } elseif ($jumlahPotongan > $master->sisa_pinjaman) {
                    $jumlahPotongan = $master->sisa_pinjaman;
                }

                // Create detail
                PotonganPinjamanDetail::create([
                    'master_id' => $master->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jumlah_potongan' => $jumlahPotongan,
                    'cicilan_ke' => $cicilanKe,
                    'status' => 'pending',
                    'keterangan' => "Cicilan ke-{$cicilanKe} dari {$master->jumlah_bulan}",
                ]);

                $generated++;
            }

            DB::commit();

            $message = "Berhasil generate {$generated} detail potongan.";
            if ($skipped > 0) {
                $message .= " {$skipped} detail di-skip karena sudah ada.";
            }

            return Redirect::back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Gagal generate detail: ' . $e->getMessage());
        }
    }

    /**
     * Proses potongan (mark as dipotong)
     */
    public function prosesPotongan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $details = PotonganPinjamanDetail::with('master')
                ->periode($request->bulan, $request->tahun)
                ->pending()
                ->get();

            if ($details->count() == 0) {
                return Redirect::back()->with('warning', 'Tidak ada potongan dengan status pending.');
            }

            foreach ($details as $detail) {
                $detail->markAsDipotong();

                // Update tracking di master
                $master = $detail->master;
                $master->bulan_terakhir_dipotong = $request->bulan;
                $master->tahun_terakhir_dipotong = $request->tahun;
                $master->save();
            }

            DB::commit();

            return Redirect::back()->with('success', "Berhasil memproses {$details->count()} potongan pinjaman.");

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    /**
     * Hapus detail untuk periode tertentu
     */
    public function deletePeriode(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            // Get details
            $details = PotonganPinjamanDetail::with('master')
                ->periode($request->bulan, $request->tahun)
                ->get();

            // Update master progress untuk setiap master yang terdampak
            $masterIds = $details->pluck('master_id')->unique();

            // Delete details
            $deleted = PotonganPinjamanDetail::periode($request->bulan, $request->tahun)->delete();

            // Update progress untuk setiap master
            foreach ($masterIds as $masterId) {
                $master = PotonganPinjamanMaster::find($masterId);
                if ($master) {
                    $master->updateProgress();
                }
            }

            DB::commit();

            return Redirect::back()->with('success', "Berhasil menghapus {$deleted} detail potongan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Get potongan by NIK untuk slip gaji
     */
    public function getPotonganByNik($nik, $bulan, $tahun)
    {
        return PotonganPinjamanDetail::with(['master'])
            ->whereHas('master', function($q) use ($nik) {
                $q->where('nik', $nik);
            })
            ->periode($bulan, $tahun)
            ->dipotong()
            ->get();
    }
}
