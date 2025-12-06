<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Denda;
use App\Models\Detailharilibur;
use App\Models\Detailsetjamkerjabydept;
use App\Models\Device;
use App\Models\Facerecognition;
use App\Models\GrupDetail;
use App\Models\GrupJamkerjaBydate;
use App\Models\Harilibur;
use App\Models\Izindinas;
use App\Models\Jamkerja;
use App\Models\YayasanMasar;
use App\Models\Pengaturanumum;
use App\Models\PresensiYayasan;
use App\Models\Setjamkerjabydate;
use App\Models\Setjamkerjabyday;
use App\Models\Setjamkerjabydept;
use App\Models\User;
use App\Models\Userkaryawan;
use App\Jobs\SendWaMessage;
use App\Services\NotificationService;
use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class YayasanPresensiController extends Controller
{

    public function index(Request $request)
    {

        $tanggal = !empty($request->tanggal) ? $request->tanggal : date('Y-m-d');
        $presensi = PresensiYayasan::join('presensi_jamkerja', 'presensi_yayasan.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->select(
                'presensi_yayasan.id',
                'presensi_yayasan.kode_yayasan',
                'presensi_yayasan.tanggal',
                'presensi_yayasan.kode_jam_kerja',
                'nama_jam_kerja',
                'jam_masuk',
                'jam_pulang',
                'istirahat',
                'jam_awal_istirahat',
                'jam_akhir_istirahat',
                'jam_in',
                'foto_in',
                'jam_out',
                'foto_out',
                'presensi_yayasan.status',
                'lintashari',
                'total_jam'
            )
            ->where('presensi_yayasan.tanggal', $tanggal);

        $query = YayasanMasar::query();
        $query->select(
            'presensi_yayasan.id',
            'yayasan_masar.kode_yayasan',
            'yayasan_masar.nama',
            'yayasan_masar.kode_dept',
            'yayasan_masar.kode_cabang',
            'presensi_yayasan.tanggal as tanggal_presensi',
            'presensi_yayasan.jam_in',
            'presensi_yayasan.kode_jam_kerja',
            'nama_jam_kerja',
            'jam_masuk',
            'jam_pulang',
            'istirahat',
            'jam_awal_istirahat',
            'jam_akhir_istirahat',
            'jam_in',
            'jam_out',
            'presensi_yayasan.status',
            'foto_in',
            'foto_out',
            'lintashari',
            'yayasan_masar.pin',
            'total_jam'
        );
        $query->leftjoinSub($presensi, 'presensi_yayasan', function ($join) {
            $join->on('yayasan_masar.kode_yayasan', '=', 'presensi_yayasan.kode_yayasan');
        });
        $query->orderBy('yayasan_masar.nama');
        if (!empty($request->kode_cabang)) {
            $query->where('yayasan_masar.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->nama)) {
            $query->where('yayasan_masar.nama', 'like', '%' . $request->nama . '%');
        }

        $yayasan = $query->paginate(10);
        $yayasan->appends(request()->all());
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $data['yayasan'] = $yayasan;
        $data['cabang'] = $cabang;
        $data['denda_list'] = Denda::all()->toArray();
        return view('yayasan-presensi.index', $data);
    }

    // DUPLIKASI DARI PRESENSI CONTROLLER - GETDATAMESIN
    // Menggunakan Cloud ID dan API Key yang SAMA dari Pengaturanumum
    public function getdatamesin(Request $request)
    {
        $tanggal = $request->tanggal;
        $pin = $request->pin;
        $general_setting = Pengaturanumum::where('id', 1)->first();

        $specific_value = $pin;

        //Mesin 1 - menggunakan konfigurasi yang SAMA seperti modul Karyawan
        $url = 'https://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"1", "cloud_id":"' . $general_setting->cloud_id . '", "start_date":"' . $tanggal . '", "end_date":"' . $tanggal . '"}';
        $authorization = "Authorization: Bearer " . $general_setting->api_key;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result);
        $datamesin1 = $res->data;

        $filtered_array = array_filter($datamesin1, function ($obj) use ($specific_value) {
            return $obj->pin == $specific_value;
        });

        return view('yayasan-presensi.getdatamesin', compact('filtered_array'));
    }

    // DUPLIKASI DARI PRESENSI CONTROLLER - UPDATEFROMMACHINE
    // Menggunakan logika yang SAMA tetapi dengan model PresensiYayasan dan YayasanMasar
    public function updatefrommachine(Request $request, $pin, $status_scan)
    {
        $pin = Crypt::decrypt($pin);
        $scan = $request->scan_date;

        $yayasan = YayasanMasar::where('pin', $pin)->first();

        if ($yayasan == null) {
            return Redirect::back()->with(messageError('Data Yayasan Tidak Ditemukan'));
        }

        $kode_yayasan = $yayasan->kode_yayasan;

        $tanggal_sekarang   = date("Y-m-d", strtotime($scan));
        $jam_sekarang = date("H:i", strtotime($scan));
        $tanggal_kemarin = date("Y-m-d", strtotime("-1 days"));
        $tanggal_besok = date("Y-m-d", strtotime("+1 days"));

        //Cek Presensi Kemarin
        $presensi_kemarin = PresensiYayasan::join('presensi_jamkerja', 'presensi_yayasan.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('presensi_yayasan.kode_yayasan', $yayasan->kode_yayasan)
            ->where('presensi_yayasan.tanggal', $tanggal_kemarin)->first();

        $lintas_hari = $presensi_kemarin ? $presensi_kemarin->lintashari : 0;

        $tanggal_presensi = $lintas_hari == 1 ? $tanggal_kemarin : $tanggal_sekarang;
        $tanggal_pulang = $lintas_hari == 1 ? $tanggal_besok : $tanggal_sekarang;

        $namahari = getnamaHari(date('D', strtotime($tanggal_presensi)));
        
        //Cek Jam Kerja By Date
        $jamkerja = Setjamkerjabydate::join('presensi_jamkerja', 'presensi_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('presensi_jamkerja_bydate.kode_yayasan', $yayasan->kode_yayasan)
            ->where('tanggal', $tanggal_presensi)
            ->first();

        //Jika Tidak Memiliki Jam Kerja By Date
        if ($jamkerja == null) {
            //Cek Jam Kerja harian / Jam Kerja Khusus / Jam Kerja Per Orangannya
            $jamkerja = Setjamkerjabyday::join('presensi_jamkerja', 'presensi_jamkerja_byday.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('presensi_jamkerja_byday.kode_yayasan', $yayasan->kode_yayasan)->where('hari', $namahari)->first();

            // Jika Jam Kerja Harian Kosong
            if ($jamkerja == null) {
                $jamkerja = Jamkerja::where('kode_jam_kerja', 'JK01')->first();
            }
        }

        //Cek Presensi
        $presensi = PresensiYayasan::where('presensi_yayasan.kode_yayasan', $yayasan->kode_yayasan)->where('presensi_yayasan.tanggal', $tanggal_presensi)->first();

        if ($presensi != null && $presensi->status != 'h') {
            return Redirect::back()->with(messageError('Sudah Melakukan Presensi'));
        } else if ($jamkerja == null) {
            return Redirect::back()->with(messageError('Tidak Memiliki Jadwal'));
        }

        $kode_jam_kerja = $jamkerja->kode_jam_kerja;
        $jam_kerja = Jamkerja::where('kode_jam_kerja', $kode_jam_kerja)->first();

        $jam_presensi = $tanggal_sekarang . " " . $jam_sekarang;

        $jam_masuk = $tanggal_presensi . " " . date('H:i', strtotime($jam_kerja->jam_masuk));

        $presensi_hariini = PresensiYayasan::where('presensi_yayasan.kode_yayasan', $yayasan->kode_yayasan)
            ->where('presensi_yayasan.tanggal', $tanggal_presensi)
            ->first();

        if (in_array($status_scan, [0, 2, 4, 6, 8])) {
            if ($presensi_hariini && $presensi_hariini->jam_in != null) {
                return Redirect::back()->with(messageError('Sudah Melakukan Presensi Masuk'));
            } else {
                try {
                    if ($presensi_hariini != null) {
                        PresensiYayasan::where('id', $presensi_hariini->id)->update([
                            'jam_in' => $jam_presensi,
                        ]);
                    } else {
                        PresensiYayasan::create([
                            'kode_yayasan' => $yayasan->kode_yayasan,
                            'tanggal' => $tanggal_presensi,
                            'jam_in' => $jam_presensi,
                            'jam_out' => null,
                            'lokasi_out' => null,
                            'foto_out' => null,
                            'kode_jam_kerja' => $kode_jam_kerja,
                            'status' => 'h'
                        ]);
                    }

                    return Redirect::back()->with(messageSuccess('Berhasil Melakukan Presensi Masuk'));
                } catch (\Exception $e) {
                    return Redirect::back()->with(messageError($e->getMessage()));
                }
            }
        } else {
            try {
                if ($presensi_hariini != null) {
                    PresensiYayasan::where('id', $presensi_hariini->id)->update([
                        'jam_out' => $jam_presensi,
                    ]);
                } else {
                    PresensiYayasan::create([
                        'kode_yayasan' => $yayasan->kode_yayasan,
                        'tanggal' => $tanggal_presensi,
                        'jam_in' => null,
                        'jam_out' => $jam_presensi,
                        'lokasi_in' => null,
                        'foto_in' => null,
                        'kode_jam_kerja' => $kode_jam_kerja,
                        'status' => 'h'
                    ]);
                }
                return Redirect::back()->with(messageSuccess('Berhasil Melakukan Presensi Pulang'));
            } catch (\Exception $e) {
                return Redirect::back()->with(messageError($e->getMessage()));
            }
        }
    }

    public function create(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja ?? null;

        //Get Data Yayasan By User
        $user = User::where('id', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $yayasan = YayasanMasar::where('kode_yayasan', $userkaryawan->kode_yayasan ?? null)->first();

        if (!$yayasan) {
            return redirect('/yayasan-presensi')->with(messageError('Data Yayasan Tidak Ditemukan'));
        }

        if ($yayasan->lock_jam_kerja == 0 && $kode_jam_kerja == null) {
            $presensi = PresensiYayasan::where('kode_yayasan', $yayasan->kode_yayasan)->where('tanggal', date('Y-m-d'))->first();
            if ($presensi != null) {
                return redirect('/yayasan-presensi/create?kode_jam_kerja=' . $presensi->kode_jam_kerja);
            }
            $data['jamkerja'] = Jamkerja::orderBy('jam_masuk')->get();
            return view('yayasan-presensi.pilih_jam_kerja', $data);
        }

        $general_setting = Pengaturanumum::where('id', 1)->first();
        //Cek Lokasi Kantor
        $lokasi_kantor = Cabang::where('kode_cabang', $yayasan->kode_cabang)->first();

        //Cek Lintas Hari
        $hariini = date("Y-m-d");
        $jamsekarang = date("H:i");
        $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hariini)));
        $cekpresensi_sebelumnya = PresensiYayasan::join('presensi_jamkerja', 'presensi_yayasan.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('tanggal', $tgl_sebelumnya)
            ->where('kode_yayasan', $yayasan->kode_yayasan)
            ->first();

        $ceklintashari_presensi = $cekpresensi_sebelumnya != null  ? $cekpresensi_sebelumnya->lintashari : 0;

        if ($ceklintashari_presensi == 1) {
            if ($jamsekarang < $general_setting->batas_presensi_lintashari) {
                $hariini = $tgl_sebelumnya;
            }
        }

        $namahari = getnamaHari(date('D', strtotime($hariini)));

        $kode_dept = $yayasan->kode_dept;

        //Cek Presensi
        $presensi = PresensiYayasan::where('kode_yayasan', $yayasan->kode_yayasan)->where('tanggal', $hariini)->first();


        if ($kode_jam_kerja == null) {
            //Cek Jam Kerja By Date
            $jamkerja = Setjamkerjabydate::join('presensi_jamkerja', 'presensi_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('kode_yayasan', $yayasan->kode_yayasan)
                ->where('tanggal', $hariini)
                ->first();

            //Jika Tidak Memiliki Jam Kerja By Date
            if ($jamkerja == null) {
                //Cek Jam Kerja harian / Jam Kerja Khusus / Jam Kerja Per Orangannya
                $jamkerja = Setjamkerjabyday::join('presensi_jamkerja', 'presensi_jamkerja_byday.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                    ->where('kode_yayasan', $yayasan->kode_yayasan)->where('hari', $namahari)->first();
            }

            // Jika Jam Kerja Harian Kosong
            if ($jamkerja == null) {
                $jamkerja = Detailsetjamkerjabydept::join('presensi_jamkerja_bydept', 'presensi_jamkerja_bydept_detail.kode_jk_dept', '=', 'presensi_jamkerja_bydept.kode_jk_dept')
                    ->join('presensi_jamkerja', 'presensi_jamkerja_bydept_detail.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                    ->where('kode_dept', $kode_dept)
                    ->where('kode_cabang', $yayasan->kode_cabang)
                    ->where('hari', $namahari)->first();
            }
        } else {
            $jamkerja = Jamkerja::where('kode_jam_kerja', $kode_jam_kerja)->first();
        }

        $ceklibur = Detailharilibur::join('hari_libur', 'hari_libur_detail.kode_libur', '=', 'hari_libur.kode_libur')
            ->where('kode_yayasan', $yayasan->kode_yayasan)
            ->where('tanggal', $hariini)
            ->first();
        $data['harilibur'] = $ceklibur;

        if ($presensi != null && $presensi->status != 'h') {
            return view('yayasan-presensi.notif_izin');
        } else if ($ceklibur != null) {
            return view('yayasan-presensi.notif_libur', $data);
        } else if ($jamkerja == null) {
            return view('yayasan-presensi.notif_jamkerja');
        }

        $kode_cabang_array = $yayasan->kode_cabang_array ?? [];
        $data['cabang'] = Cabang::WhereIn('kode_cabang', $kode_cabang_array)
            ->orWhere('kode_cabang', $yayasan->kode_cabang)
            ->get();

        $data['hariini'] = $hariini;
        $data['jam_kerja'] = $jamkerja;
        $data['lokasi_kantor'] = $lokasi_kantor;
        $data['presensi'] = $presensi;
        $data['yayasan'] = $yayasan;
        $data['wajah'] = Facerecognition::where('kode_yayasan', $yayasan->kode_yayasan)->count();

        return view('yayasan-presensi.create', $data);
    }

    public function store(Request $request)
    {
        $generalsetting = Pengaturanumum::where('id', 1)->first();
        $user = User::where('id', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $yayasan = YayasanMasar::where('kode_yayasan', $userkaryawan->kode_yayasan ?? null)->first();

        if (!$yayasan) {
            return response()->json(['status' => false, 'message' => 'Data Yayasan Tidak Ditemukan'], 400);
        }

        $status_lock_location = $yayasan->lock_location;

        $status = $request->status;
        $lokasi = $request->lokasi;
        $kode_jam_kerja = $request->kode_jam_kerja;

        $tanggal_sekarang = date("Y-m-d");
        $jam_sekarang = date("H:i");

        $tanggal_kemarin = date("Y-m-d", strtotime("-1 days"));

        $tanggal_besok = date("Y-m-d", strtotime("+1 days"));

        //Cek Presensi Kemarin
        $presensi_kemarin = PresensiYayasan::join('presensi_jamkerja', 'presensi_yayasan.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('presensi_yayasan.kode_yayasan', $yayasan->kode_yayasan)
            ->where('presensi_yayasan.tanggal', $tanggal_kemarin)->first();

        $lintas_hari = $presensi_kemarin ? $presensi_kemarin->lintashari : 0;

        //Get Lokasi User
        $koordinat_user = explode(",", $lokasi);
        $latitude_user = $koordinat_user[0];
        $longitude_user = $koordinat_user[1];

        //Get Lokasi Kantor
        $cabang = Cabang::where('kode_cabang', $yayasan->kode_cabang)->first();
        $lokasi_kantor = $request->lokasi_cabang;

        $koordinat_kantor = explode(",", $lokasi_kantor);
        $latitude_kantor = $koordinat_kantor[0];
        $longitude_kantor = $koordinat_kantor[1];

        $jarak = hitungjarak($latitude_kantor, $longitude_kantor, $latitude_user, $longitude_user);

        $radius = round($jarak["meters"]);

        $in_out = $status == 1 ? "in" : "out";
        $image = $request->image;
        $folderPath = "public/uploads/absensi/yayasan/";

        $jam_kerja = Jamkerja::where('kode_jam_kerja', $kode_jam_kerja)->first();

        $jam_presensi = $tanggal_sekarang . " " . $jam_sekarang;

        $batas_jam_absen = $generalsetting->batas_jam_absen * 60;
        $batas_jam_absen_pulang = $generalsetting->batas_jam_absen_pulang * 60;

        //Jiak Kemarin Melakukan Presensi
        if ($presensi_kemarin != null) {
            //Jika Presensi Kemarin Lintas Hari
            if ($presensi_kemarin->lintashari == 1) {
                //Jika Jam Sekarang Lebih Besar dari batas_presensi_lintashari
                if ($jam_sekarang > $generalsetting->batas_presensi_lintashari) {
                    $tanggal_pulang = $tanggal_besok;
                    $jam_kerja_pulang = $jam_kerja->jam_pulang;
                    $tanggal_presensi = $tanggal_sekarang;
                } else {
                    $tanggal_pulang = $tanggal_sekarang;
                    $jam_kerja_pulang = $presensi_kemarin->jam_pulang;
                    $tanggal_presensi = $tanggal_kemarin;
                }
            } else {
                if ($jam_kerja->lintashari == 1) {
                    $tanggal_pulang = $tanggal_besok;
                    $jam_kerja_pulang = $jam_kerja->jam_pulang;
                    $tanggal_presensi = $tanggal_sekarang;
                } else {
                    $tanggal_pulang = $tanggal_sekarang;
                    $jam_kerja_pulang = $jam_kerja->jam_pulang;
                    $tanggal_presensi = $tanggal_sekarang;
                }
            }
        } else {
            if ($jam_kerja->lintashari == 1) {
                $tanggal_pulang = $tanggal_besok;
                $jam_kerja_pulang = $jam_kerja->jam_pulang;
                $tanggal_presensi = $tanggal_sekarang;
            } else {
                $tanggal_pulang = $tanggal_sekarang;
                $jam_kerja_pulang = $jam_kerja->jam_pulang;
                $tanggal_presensi = $tanggal_sekarang;
            }
        }

        $formatName = $yayasan->kode_yayasan . "-" . $tanggal_presensi . "-" . $in_out;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $jam_masuk = $tanggal_presensi . " " . date('H:i', strtotime($jam_kerja->jam_masuk));
        $jam_mulai_masuk = $tanggal_presensi . " " . date('H:i', strtotime('-' . $batas_jam_absen . ' minutes', strtotime($jam_masuk)));
        $jam_akhir_masuk = $tanggal_presensi . " " . date('H:i', strtotime('+' . $batas_jam_absen . ' minutes', strtotime($jam_masuk)));
        $jam_pulang = $tanggal_pulang . " " . $jam_kerja_pulang;

        $jam_mulai_pulang =  date('Y-m-d H:i', strtotime('-' . $batas_jam_absen_pulang . ' minutes', strtotime($jam_pulang)));

        // Cek Izin Dinas
        $izin_dinas = Izindinas::where('kode_yayasan', $yayasan->kode_yayasan)
            ->where('status', 1)
            ->where('dari', '<=', $tanggal_presensi)
            ->where('sampai', '>=', $tanggal_presensi)
            ->first();

        if ($izin_dinas) {
            $status_lock_location = 0;
        }

        $presensi_hariini = PresensiYayasan::where('presensi_yayasan.kode_yayasan', $yayasan->kode_yayasan)
            ->where('presensi_yayasan.tanggal', $tanggal_presensi)
            ->first();

        if ($status_lock_location == 1 && $radius > $cabang->radius_cabang) {
            return response()->json(['status' => false, 'message' => 'Anda Berada Di Luar Radius Kantor, Jarak Anda ' . formatAngka($radius) . ' Meters Dari Kantor', 'notifikasi' => 'notifikasi_radius'], 400);
        } else {
            if ($status == 1) {
                if ($presensi_hariini && $presensi_hariini->jam_in != null) {
                    return response()->json(['status' => false, 'message' => 'Anda Sudah Absen Masuk Hari Ini', 'notifikasi' => 'notifikasi_sudahabsen'], 400);
                } else if ($jam_presensi < $jam_mulai_masuk && $generalsetting->batasi_absen == 1) {
                    return response()->json(['status' => false, 'message' => 'Maaf Belum Waktunya Absen Masuk, Waktu Absen Dimulai Pukul ' . formatIndo3($jam_mulai_masuk), 'notifikasi' => 'notifikasi_mulaiabsen'], 400);
                } else if ($jam_presensi > $jam_akhir_masuk && $generalsetting->batasi_absen == 1) {
                    return response()->json(['status' => false, 'message' => 'Maaf Waktu Absen Masuk Sudah Habis ', 'notifikasi' => 'notifikasi_akhirabsen'], 400);
                } else {
                    try {
                        if ($presensi_hariini != null) {
                            PresensiYayasan::where('id', $presensi_hariini->id)->update([
                                'jam_in' => $jam_presensi,
                                'lokasi_in' => $lokasi,
                                'foto_in' => $fileName
                            ]);
                        } else {
                            $presensi_baru = PresensiYayasan::create([
                                'kode_yayasan' => $yayasan->kode_yayasan,
                                'tanggal' => $tanggal_presensi,
                                'jam_in' => $jam_presensi,
                                'jam_out' => null,
                                'lokasi_in' => $lokasi,
                                'lokasi_out' => null,
                                'foto_in' => $fileName,
                                'foto_out' => null,
                                'kode_jam_kerja' => $kode_jam_kerja,
                                'status' => 'h'
                            ]);
                        }
                        Storage::put($file, $image_base64);

                        //Kirim Notifikasi Ke WA
                        if ($generalsetting->notifikasi_wa == 1) {
                            if ($generalsetting->tujuan_notifikasi_wa == 1) {
                                if ($yayasan->no_hp != "") {
                                    $message = "Terimakasih, Hari ini " . $yayasan->nama . " absen Masuk pada " . $jam_presensi;
                                    $this->sendwa($yayasan->no_hp, $message);
                                }
                            } else {
                                if (!empty($generalsetting->id_group_wa)) {
                                    $message = "Terimakasih, Hari ini " . $yayasan->nama . " absen Masuk pada " . $jam_presensi;
                                    $this->sendwa($generalsetting->id_group_wa, $message);
                                }
                            }
                        }
                        return response()->json(['status' => true, 'message' => 'Berhasil Absen Masuk', 'notifikasi' => 'notifikasi_absenmasuk'], 200);
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                    }
                }
            } else {
                if ($presensi_hariini && $presensi_hariini->jam_out != null) {
                    return response()->json(['status' => false, 'message' => 'Anda Sudah Absen Pulang Hari Ini', 'notifikasi' => 'notifikasi_sudahabsen'], 400);
                } else if ($jam_presensi < $jam_mulai_pulang && $generalsetting->batasi_absen == 1) {
                    return response()->json(['status' => false, 'message' => 'Maaf Belum Waktunya Absen Pulang, Waktu Absen Dimulai Pukul ' . formatIndo3($jam_mulai_pulang), 'notifikasi' => 'notifikasi_mulaiabsen'], 400);
                } else {
                    try {
                        if ($presensi_hariini != null) {
                            PresensiYayasan::where('id', $presensi_hariini->id)->update([
                                'jam_out' => $jam_presensi,
                                'lokasi_out' => $lokasi,
                                'foto_out' => $fileName
                            ]);
                        } else {
                            $presensi_baru = PresensiYayasan::create([
                                'kode_yayasan' => $yayasan->kode_yayasan,
                                'tanggal' => $tanggal_presensi,
                                'jam_in' => null,
                                'jam_out' => $jam_presensi,
                                'lokasi_in' => null,
                                'lokasi_out' => $lokasi,
                                'foto_in' => null,
                                'foto_out' => $fileName,
                                'kode_jam_kerja' => $kode_jam_kerja,
                                'status' => 'h'
                            ]);
                        }
                        Storage::put($file, $image_base64);
                        //Kirim Notifikasi Ke WA
                        if ($generalsetting->notifikasi_wa == 1) {
                            if ($generalsetting->tujuan_notifikasi_wa == 1) {
                                if ($yayasan->no_hp != "") {
                                    $message = "Terimakasih, Hari ini " . $yayasan->nama . " absen Pulang pada " . $jam_presensi;
                                    $this->sendwa($yayasan->no_hp, $message);
                                }
                            } else {
                                if (!empty($generalsetting->id_group_wa)) {
                                    $message = "Terimakasih, Hari ini " . $yayasan->nama . " absen Pulang pada " . $jam_presensi;
                                    $this->sendwa($generalsetting->id_group_wa, $message);
                                }
                            }
                        }
                        return response()->json(['status' => true, 'message' => 'Berhasil Absen Pulang', 'notifikasi' => 'notifikasi_absenpulang'], 200);
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                    }
                }
            }
        }
    }

    function sendwa($no_hp, $message)
    {
        if (!empty($no_hp) && !empty($message)) {
            dispatch(new SendWaMessage($no_hp, $message));
        }
    }

    public function edit(Request $request)
    {
        $kode_yayasan = Crypt::decrypt($request->kode_yayasan);
        $tanggal = $request->tanggal;

        $yayasan = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
        $jam_kerja = Jamkerja::all();
        $presensi = PresensiYayasan::where('kode_yayasan', $kode_yayasan)->where('tanggal', $tanggal)->first();
        $data['presensi'] = $presensi;
        $data['yayasan'] = $yayasan;
        $data['jam_kerja'] = $jam_kerja;
        $data['tanggal'] = $tanggal;

        return view('yayasan-presensi.edit', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'kode_yayasan' => 'required',
            'tanggal' => 'required',
            'kode_jam_kerja' => 'required',
            'status' => 'required',
        ]);

        $kode_yayasan = Crypt::decrypt($request->kode_yayasan);
        $tanggal = $request->tanggal;
        $kode_jam_kerja = $request->kode_jam_kerja;
        
        // Format jam_in dan jam_out ke DATETIME (YYYY-MM-DD HH:MM:SS)
        $jam_in = null;
        $jam_out = null;
        
        if ($request->jam_in) {
            // Cek apakah sudah format DATETIME atau hanya TIME
            if (strpos($request->jam_in, ' ') !== false) {
                // Sudah format DATETIME
                $jam_in = $request->jam_in;
            } else {
                // Format TIME, gabung dengan tanggal
                $jam_in = $tanggal . ' ' . $request->jam_in;
            }
        }
        
        if ($request->jam_out) {
            // Cek apakah sudah format DATETIME atau hanya TIME
            if (strpos($request->jam_out, ' ') !== false) {
                // Sudah format DATETIME
                $jam_out = $request->jam_out;
            } else {
                // Format TIME, gabung dengan tanggal
                $jam_out = $tanggal . ' ' . $request->jam_out;
            }
        }
        
        $status = $request->status;

        try {
            $cekpresensi = PresensiYayasan::where('kode_yayasan', $kode_yayasan)->where('tanggal', $tanggal)->first();
            if (!empty($cekpresensi)) {
                PresensiYayasan::where('kode_yayasan', $kode_yayasan)->where('tanggal', $tanggal)->update([
                    'jam_in' => $jam_in,
                    'jam_out' => $jam_out,
                    'status' => $status,
                    'kode_jam_kerja' => $kode_jam_kerja,
                ]);
            } else {
                PresensiYayasan::create([
                    'kode_yayasan' => $kode_yayasan,
                    'tanggal' => $tanggal,
                    'jam_in' => $jam_in,
                    'jam_out' => $jam_out,
                    'kode_jam_kerja' => $kode_jam_kerja,
                    'status' => $status
                ]);
            }

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($id, $status)
    {
        $presensi = PresensiYayasan::where('id', $id)
            ->join('yayasan_masar', 'presensi_yayasan.kode_yayasan', '=', 'yayasan_masar.kode_yayasan')
            ->join('departemen', 'yayasan_masar.kode_dept', '=', 'departemen.kode_dept')
            ->join('jabatan', 'yayasan_masar.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('cabang', 'yayasan_masar.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $cabang = Cabang::where('kode_cabang', $presensi->kode_cabang)->first();
        $lokasi = explode(',', $cabang->lokasi_cabang);
        $data['latitude'] = $lokasi[0];
        $data['longitude'] = $lokasi[1];
        $data['presensi'] = $presensi;
        $data['status'] = $status;
        $data['cabang'] = $cabang;

        return view('yayasan-presensi.show', $data);
    }

    public function histori(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', auth()->user()->id)->first();
        $data['datapresensi'] = PresensiYayasan::join('presensi_jamkerja', 'presensi_yayasan.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('presensi_yayasan.kode_yayasan', $userkaryawan->kode_yayasan ?? null)
            ->select(
                'presensi_yayasan.*',
                'presensi_jamkerja.nama_jam_kerja',
                'presensi_jamkerja.jam_masuk',
                'presensi_jamkerja.jam_pulang',
                'presensi_jamkerja.total_jam',
                'presensi_jamkerja.lintashari'
            )
            ->when(!empty($request->dari) && !empty($request->sampai), function ($q) use ($request) {
                $q->whereBetween('presensi_yayasan.tanggal', [$request->dari, $request->sampai]);
            })
            ->orderBy('presensi_yayasan.tanggal', 'desc')
            ->limit(30)
            ->get();
        return view('yayasan-presensi.histori', $data);
    }

    /**
     * Ambil data presensi dari mesin untuk SEMUA yayasan sekaligus
     */
    public function getdatamesinall(Request $request)
    {
        $tanggal = $request->tanggal;
        $general_setting = Pengaturanumum::where('id', 1)->first();

        // Ambil semua data dari mesin
        $url = 'https://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"1", "cloud_id":"' . $general_setting->cloud_id . '", "start_date":"' . $tanggal . '", "end_date":"' . $tanggal . '"}';
        $authorization = "Authorization: Bearer " . $general_setting->api_key;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result);
        $datamesin = $res->data ?? [];

        // Ambil semua PIN yayasan yang aktif
        $yayasanList = YayasanMasar::where('status_aktif', 1)->get(['pin', 'kode_yayasan', 'nama']);

        // Group data mesin berdasarkan PIN
        $filteredDataByPIN = [];
        foreach ($yayasanList as $yayasan) {
            $pin = $yayasan->pin;
            $filtered = array_filter($datamesin, function ($obj) use ($pin) {
                return $obj->pin == $pin;
            });
            
            if (!empty($filtered)) {
                $filteredDataByPIN[$yayasan->kode_yayasan] = [
                    'nama_yayasan' => $yayasan->nama,
                    'pin' => $pin,
                    'data' => array_values($filtered) // Reset array keys
                ];
            }
        }

        return view('yayasan-presensi.getdatamesinall', compact('filteredDataByPIN', 'tanggal'));
    }

    /**
     * Submit MASUK untuk SEMUA jamaah sekaligus
     */
    public function submitallmasuk(Request $request)
    {
        $tanggal = $request->tanggal;
        $general_setting = Pengaturanumum::where('id', 1)->first();
        $count_success = 0;
        $count_failed = 0;

        // Tentukan kode_jam_kerja default - ambil yang pertama
        $default_jam_kerja = Jamkerja::first();
        if (!$default_jam_kerja) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jam kerja di sistem'
            ], 400);
        }
        $kode_jam_kerja_default = $default_jam_kerja->kode_jam_kerja;

        try {
            // Ambil semua data dari mesin untuk tanggal ini
            $url = 'https://developer.fingerspot.io/api/get_attlog';
            $data = '{"trans_id":"1", "cloud_id":"' . $general_setting->cloud_id . '", "start_date":"' . $tanggal . '", "end_date":"' . $tanggal . '"}';
            $authorization = "Authorization: Bearer " . $general_setting->api_key;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($result);
            $datamesin = $res->data ?? [];

            // Ambil semua PIN yayasan yang aktif
            $yayasanList = YayasanMasar::where('status_aktif', 1)->get(['pin', 'kode_yayasan']);

            // Process setiap jamaah
            foreach ($yayasanList as $yayasan) {
                $pin = $yayasan->pin;
                
                // Cari data masuk untuk jamaah ini
                $masukData = array_filter($datamesin, function ($obj) use ($pin) {
                    return $obj->pin == $pin && $obj->status_scan % 2 == 0;
                });

                if (!empty($masukData)) {
                    // Ambil data masuk yang pertama
                    $masuk = reset($masukData);
                    
                    // Cek apakah sudah ada presensi
                    $existingPresensi = PresensiYayasan::where('kode_yayasan', $yayasan->kode_yayasan)
                        ->where('tanggal', $tanggal)
                        ->first();

                    if (!$existingPresensi) {
                        // Buat presensi baru
                        $jam_in = $tanggal . ' ' . date('H:i:s', strtotime($masuk->scan_date));
                        
                        PresensiYayasan::create([
                            'kode_yayasan' => $yayasan->kode_yayasan,
                            'tanggal' => $tanggal,
                            'jam_in' => $jam_in,
                            'kode_jam_kerja' => $kode_jam_kerja_default,
                            'status' => 'h', // hadir
                            'foto_in' => null
                        ]);
                        $count_success++;
                    } else {
                        // Update jam masuk jika belum ada
                        if (empty($existingPresensi->jam_in)) {
                            $jam_in = $tanggal . ' ' . date('H:i:s', strtotime($masuk->scan_date));
                            $existingPresensi->update([
                                'jam_in' => $jam_in,
                                'status' => 'h'
                            ]);
                            $count_success++;
                        }
                    }
                } else {
                    $count_failed++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil: $count_success jamaah, Gagal: $count_failed jamaah",
                'count_success' => $count_success,
                'count_failed' => $count_failed
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit PULANG untuk SEMUA jamaah sekaligus
     */
    public function submitallpulang(Request $request)
    {
        $tanggal = $request->tanggal;
        $general_setting = Pengaturanumum::where('id', 1)->first();
        $count_success = 0;
        $count_failed = 0;

        // Tentukan kode_jam_kerja default - ambil yang pertama
        $default_jam_kerja = Jamkerja::first();
        if (!$default_jam_kerja) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jam kerja di sistem'
            ], 400);
        }
        $kode_jam_kerja_default = $default_jam_kerja->kode_jam_kerja;

        try {
            // Ambil semua data dari mesin untuk tanggal ini
            $url = 'https://developer.fingerspot.io/api/get_attlog';
            $data = '{"trans_id":"1", "cloud_id":"' . $general_setting->cloud_id . '", "start_date":"' . $tanggal . '", "end_date":"' . $tanggal . '"}';
            $authorization = "Authorization: Bearer " . $general_setting->api_key;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($result);
            $datamesin = $res->data ?? [];

            // Ambil semua PIN yayasan yang aktif
            $yayasanList = YayasanMasar::where('status_aktif', 1)->get(['pin', 'kode_yayasan']);

            // Process setiap jamaah
            foreach ($yayasanList as $yayasan) {
                $pin = $yayasan->pin;
                
                // Cari data pulang untuk jamaah ini
                $pulangData = array_filter($datamesin, function ($obj) use ($pin) {
                    return $obj->pin == $pin && $obj->status_scan % 2 != 0;
                });

                if (!empty($pulangData)) {
                    // Ambil data pulang yang pertama
                    $pulang = reset($pulangData);
                    
                    // Cek apakah sudah ada presensi
                    $existingPresensi = PresensiYayasan::where('kode_yayasan', $yayasan->kode_yayasan)
                        ->where('tanggal', $tanggal)
                        ->first();

                    if ($existingPresensi) {
                        // Update jam pulang
                        if (empty($existingPresensi->jam_out)) {
                            $jam_out = $tanggal . ' ' . date('H:i:s', strtotime($pulang->scan_date));
                            $existingPresensi->update([
                                'jam_out' => $jam_out
                            ]);
                            $count_success++;
                        }
                    } else {
                        // Jika belum ada presensi, buat baru dengan jam pulang
                        $jam_out = $tanggal . ' ' . date('H:i:s', strtotime($pulang->scan_date));
                        
                        PresensiYayasan::create([
                            'kode_yayasan' => $yayasan->kode_yayasan,
                            'tanggal' => $tanggal,
                            'jam_out' => $jam_out,
                            'kode_jam_kerja' => $kode_jam_kerja_default,
                            'status' => 'h',
                            'foto_out' => null
                        ]);
                        $count_success++;
                    }
                } else {
                    $count_failed++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil: $count_success jamaah, Gagal: $count_failed jamaah",
                'count_success' => $count_success,
                'count_failed' => $count_failed
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
