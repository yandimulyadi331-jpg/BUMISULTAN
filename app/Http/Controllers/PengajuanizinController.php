<?php

namespace App\Http\Controllers;

use App\Models\Izinabsen;
use App\Models\Izincuti;
use App\Models\Izindinas;
use App\Models\Izinsakit;
use App\Models\TugasLuar;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanizinController extends Controller
{
    public function index()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();

        $izinabsen = Izinabsen::where('nik', $userkaryawan->nik)
            ->select('kode_izin as kode', 'tanggal', 'keterangan', 'dari', 'sampai', DB::raw('\'i\' as ket'), 'status as status_izin');

        $izinsakit = Izinsakit::where('nik', $userkaryawan->nik)
            ->select('kode_izin_sakit as kode', 'tanggal', 'keterangan', 'dari', 'sampai', DB::raw('\'s\' as ket'), 'status as status_izin');

        $izincuti = Izincuti::where('nik', $userkaryawan->nik)
            ->select('kode_izin_cuti as kode', 'tanggal', 'keterangan', 'dari', 'sampai', DB::raw('\'c\' as ket'), 'status as status_izin');

        $izin_dinas = Izindinas::where('nik', $userkaryawan->nik)
            ->select('kode_izin_dinas as kode', 'tanggal', 'keterangan', 'dari', 'sampai', DB::raw('\'d\' as ket'), 'status as status_izin');

        // Ambil data tugas luar untuk karyawan ini
        $tugas_luar = TugasLuar::whereRaw('JSON_CONTAINS(karyawan_list, ?)', ['"' . $userkaryawan->nik . '"'])
            ->select(
                'kode_tugas as kode', 
                'tanggal', 
                'tujuan as keterangan', 
                'tanggal as dari', 
                'tanggal as sampai', 
                DB::raw('\'tl\' as ket'),
                DB::raw('CASE 
                    WHEN status = "keluar" THEN "0" 
                    WHEN status = "kembali" THEN "1" 
                END as status_izin')
            );

        $pengajuan_izin = $izinabsen->union($izinsakit)->union($izincuti)->union($izin_dinas)->union($tugas_luar)->orderBy('tanggal', 'desc')->get();
        $data['pengajuan_izin'] = $pengajuan_izin;
        return view('pengajuanizin.index', $data);
    }
}
