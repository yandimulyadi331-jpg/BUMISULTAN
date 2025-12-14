<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Facerecognition;
use App\Models\Jabatan;
use App\Models\Jamkerja;
use App\Models\YayasanMasar;
use App\Models\Pengaturanumum;
use App\Models\Setjamkerjabydate;
use App\Models\Setjamkerjabyday;
use App\Models\Statuskawin;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class YayasanMasarController extends Controller
{
    public function index(Request $request)
    {
        $query = YayasanMasar::query();
        $query->select('yayasan_masar.*', 'departemen.nama_dept', 'jabatan.nama_jabatan', 'cabang.nama_cabang', 'id_user');
        $query->leftJoin('departemen', 'yayasan_masar.kode_dept', '=', 'departemen.kode_dept');
        $query->leftJoin('jabatan', 'yayasan_masar.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $query->leftJoin('cabang', 'yayasan_masar.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('users_yayasan_masar', 'yayasan_masar.kode_yayasan', '=', 'users_yayasan_masar.kode_yayasan');
        if (!empty($request->kode_cabang)) {
            $query->where('yayasan_masar.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('yayasan_masar.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_group)) {
            $query->where('yayasan_masar.kode_group', $request->kode_group);
        }

        if (!empty($request->nama)) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if (!empty($request->pin)) {
            $query->where('pin', 'like', '%' . $request->pin . '%');
        }

        // Tambahkan filter jenis kelamin
        if (!empty($request->jenis_kelamin)) {
            $query->where('yayasan_masar.jenis_kelamin', $request->jenis_kelamin);
        }

        // Tambahkan filter status umroh
        if (!empty($request->status_umroh)) {
            $query->where('yayasan_masar.status_umroh', $request->status_umroh);
        }

        $query->orderBy('nama', 'asc');
        $yayasan_masar = $query->paginate(15);

        // Get count for badges
        $total_count = YayasanMasar::count();
        $laki_count = YayasanMasar::where('jenis_kelamin', 'L')->count();
        $perempuan_count = YayasanMasar::where('jenis_kelamin', 'P')->count();
        $umroh_count = YayasanMasar::where('status_umroh', '1')->count();
        $tidak_umroh_count = YayasanMasar::where('status_umroh', '0')->count();

        $data['yayasan_masar'] = $yayasan_masar;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['total_count'] = $total_count;
        $data['laki_count'] = $laki_count;
        $data['perempuan_count'] = $perempuan_count;
        $data['umroh_count'] = $umroh_count;
        $data['tidak_umroh_count'] = $tidak_umroh_count;
        return view('datamaster.yayasan_masar.index', $data);
    }
    public function create()
    {
        $data['status_kawin'] = Statuskawin::orderBy('kode_status_kawin')->get();
        return view('datamaster.yayasan_masar.create', $data);
    }


    public function store(Request $request)
    {
        // Generate no_identitas otomatis: YYMMDD + 4 digit urut
        $no_identitas = $this->generateNoIdentitas();

        $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string|max:500',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'kode_status_kawin' => 'required|exists:status_kawin,kode_status_kawin',
            'pendidikan_terakhir' => 'required|in:SD,SMP,SMA,SMK,D1,D2,D3,D4,S1,S2,S3',
            'tanggal_masuk' => 'required|date|before_or_equal:today',
            'status_umroh' => 'required|in:0,1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ], [
            'nama.required' => 'Nama Yayasan Masar harus diisi',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'no_hp.required' => 'No. HP harus diisi',
            'kode_status_kawin.required' => 'Status perkawinan harus dipilih',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir harus dipilih',
            'status_umroh.required' => 'Status Umroh harus dipilih'
        ]);

        try {
            // Generate kode_yayasan otomatis: YYMM + 5 digit urut per bulan
            $tahun = date('y');
            $bulan = date('m');
            $prefix = $tahun . $bulan; // e.g., 2512 (Dec 2025)

            $last = YayasanMasar::where('kode_yayasan', 'like', $prefix . '%')
                ->orderBy('kode_yayasan', 'desc')
                ->first();

            $lastNumber = 0;
            if ($last) {
                $lastNumber = (int)substr($last->kode_yayasan, 4, 5);
            }
            $nextNumber = $lastNumber + 1;
            $kode_yayasan = $prefix . str_pad((string)$nextNumber, 5, '0', STR_PAD_LEFT);

            $data_foto = [];
            if ($request->hasfile('foto')) {
                $foto_name = $no_identitas . "." . $request->file('foto')->getClientOriginalExtension();
                $destination_foto_path = "yayasan_masar";
                $foto = $foto_name;
                $data_foto = [
                    'foto' => $foto
                ];
            }
            $data_yayasan_masar = [
                'kode_yayasan' => $kode_yayasan,
                'no_identitas' => $no_identitas,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_umroh' => $request->status_umroh,
                'lock_location' => 1,
                'status_aktif' => 1,
                'password' => Hash::make('12345'),
                'kode_cabang' => null,
                'kode_dept' => null,
                'kode_jabatan' => null,
                'status' => 'T'
            ];
            $data = array_merge($data_yayasan_masar, $data_foto);
            $simpan = YayasanMasar::create($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name, 'public');
                    // Sync to public/storage for Windows compatibility
                    $this->syncStorageFile($destination_foto_path, $foto_name);
                }
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $data['yayasan_masar'] = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
        $data['status_kawin'] = Statuskawin::orderBy('kode_status_kawin')->get();
        return view('datamaster.yayasan_masar.edit', $data);
    }


    public function update($kode_yayasan, Request $request)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)->firstOrFail();
        
        $request->validate([
            'no_identitas' => 'required|string|max:50|unique:yayasan_masar,no_identitas,' . $yayasan_masar->kode_yayasan . ',kode_yayasan',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string|max:500',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'kode_status_kawin' => 'required|exists:status_kawin,kode_status_kawin',
            'pendidikan_terakhir' => 'required|in:SD,SMP,SMA,SMK,D1,D2,D3,D4,S1,S2,S3',
            'tanggal_masuk' => 'required|date|before_or_equal:today',
            'status_umroh' => 'required|in:0,1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ], [
            'no_identitas.required' => 'No. Identitas harus diisi',
            'no_identitas.unique' => 'No. Identitas sudah terdaftar',
            'nama.required' => 'Nama Yayasan Masar harus diisi',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'no_hp.required' => 'No. HP harus diisi',
            'kode_status_kawin.required' => 'Status perkawinan harus dipilih',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir harus dipilih',
            'status_umroh.required' => 'Status Umroh harus dipilih'
        ]);

        try {
            $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
            $data_foto = [];
            if ($request->hasfile('foto')) {
                $foto_name = $yayasan_masar->no_identitas . "." . $request->file('foto')->getClientOriginalExtension();
                $destination_foto_path = "yayasan_masar";
                $foto = $foto_name;
                $data_foto = [
                    'foto' => $foto
                ];
            }

            $data_yayasan_masar = [
                'no_identitas' => $request->no_identitas,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_umroh' => $request->status_umroh,
                'status_aktif' => $request->status_aktif,
                'pin' => $request->pin
            ];

            $data = array_merge($data_yayasan_masar, $data_foto);
            $simpan = YayasanMasar::where('kode_yayasan', $kode_yayasan)->update($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    Storage::disk('public')->delete($destination_foto_path . "/" . $yayasan_masar->foto);
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name, 'public');
                    // Sync to public/storage for Windows compatibility
                    $this->syncStorageFile($destination_foto_path, $foto_name);
                }
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function lockunlocklocation($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        try {
            $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
            if ($yayasan_masar->lock_location == '1') {
                $lock_location = 0;
            } else {
                $lock_location = 1;
            }

            YayasanMasar::where('kode_yayasan', $kode_yayasan)->update([
                'lock_location' => $lock_location
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function lockunlockjamkerja($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        try {
            $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
            if ($yayasan_masar->lock_jam_kerja == '1') {
                $lock_jam_kerja = 0;
            } else {
                $lock_jam_kerja = 1;
            }

            YayasanMasar::where('kode_yayasan', $kode_yayasan)->update([
                'lock_jam_kerja' => $lock_jam_kerja
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)
            ->leftJoin('cabang', 'yayasan_masar.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('departemen', 'yayasan_masar.kode_dept', '=', 'departemen.kode_dept')
            ->leftJoin('jabatan', 'yayasan_masar.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->leftJoin('status_kawin', 'yayasan_masar.kode_status_kawin', '=', 'status_kawin.kode_status_kawin')
            ->first();
        $user_yayasan_masar = DB::table('users_yayasan_masar')->where('kode_yayasan', $kode_yayasan)->first();
        $user = $user_yayasan_masar ? User::where('id', $user_yayasan_masar->id_user)->first() : null;
        $wajah = DB::table('yayasan_masar_wajah')->where('kode_yayasan', $kode_yayasan)->get();
        $data['yayasan_masar'] = $yayasan_masar;
        $data['user'] = $user;
        $data['wajah'] = $wajah;
        return view('datamaster.yayasan_masar.show', $data);
    }


    public function destroy($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        try {
            $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
            $user_yayasan_masar = DB::table('users_yayasan_masar')->where('kode_yayasan', $kode_yayasan)->first();
            if (!empty($user_yayasan_masar)) {
                User::where('id', $user_yayasan_masar->id_user)->delete();
                DB::table('users_yayasan_masar')->where('kode_yayasan', $kode_yayasan)->delete();
            }

            $nama_folder = $kode_yayasan . "-" . getNamaDepan(strtolower($yayasan_masar->nama));
            $path_folder = 'public/uploads/yayasan_masar_wajah/' . $nama_folder;
            Storage::deleteDirectory($path_folder);

            $nama_file_foto = $yayasan_masar->foto;
            $path_foto = 'yayasan_masar/' . $nama_file_foto;
            Storage::disk('public')->delete($path_foto);
            YayasanMasar::where('kode_yayasan', $kode_yayasan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function setjamkerja($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $data['yayasan_masar'] = YayasanMasar::where('kode_yayasan', $kode_yayasan)
            ->join('departemen', 'yayasan_masar.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'yayasan_masar.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['jamkerja'] = Jamkerja::orderBy('kode_jam_kerja')->get();
        $data['jamkerjabyday'] = Setjamkerjabyday::where('kode_yayasan_masar', $kode_yayasan)->pluck('kode_jam_kerja', 'hari')->toArray();
        return view('datamaster.yayasan_masar.setjamkerja', $data);
    }

    public function setcabang($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $data['yayasan_masar'] = YayasanMasar::where('kode_yayasan', $kode_yayasan)
            ->join('departemen', 'yayasan_masar.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'yayasan_masar.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        // Exclude cabang utama dari pilihan
        $data['cabang'] = Cabang::where('kode_cabang', '!=', $data['yayasan_masar']->kode_cabang)->orderBy('kode_cabang')->get();
        $data['kode_cabang_array'] = $data['yayasan_masar']->kode_cabang_array ?? [];
        return view('datamaster.yayasan_masar.setcabang', $data);
    }

    public function storecabang(Request $request, $kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        try {
            $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
            $kode_cabang_utama = $yayasan_masar->kode_cabang;

            $kode_cabang_array = $request->kode_cabang_array ?? [];
            $kode_cabang_array[] = $kode_cabang_utama;
            $kode_cabang_array = array_unique($kode_cabang_array);

            YayasanMasar::where('kode_yayasan', $kode_yayasan)->update([
                'kode_cabang_array' => $kode_cabang_array
            ]);
            return Redirect::back()->with(messageSuccess('Data Cabang Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function storejamkerjabyday(Request $request, $kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        DB::beginTransaction();
        try {
            Setjamkerjabyday::where('kode_yayasan_masar', $kode_yayasan)->delete();
            for ($i = 0; $i < count($hari); $i++) {
                if (!empty($kode_jam_kerja[$i])) {
                    Setjamkerjabyday::create([
                        'kode_yayasan_masar' => $kode_yayasan,
                        'hari' => $hari[$i],
                        'kode_jam_kerja' => $kode_jam_kerja[$i]
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function storejamkerjabydate(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');

        $cek = Setjamkerjabydate::where('kode_yayasan_masar', $request->kode_yayasan_masar)->where('tanggal', $tanggal)->first();
        if (!empty($cek)) {
            return response()->json(['success' => false, 'message' => 'Sudah Memiliki Jadwal pada Tanggal Ini']);
        }
        try {
            Setjamkerjabydate::create([
                'kode_yayasan_masar' => $request->kode_yayasan_masar,
                'tanggal' => $tanggal,
                'kode_jam_kerja' => $request->kode_jam_kerja
            ]);

            return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getjamkerjabydate(Request $request)
    {
        $kode_yayasan_masar = $request->kode_yayasan_masar;
        $tanggal = $request->tanggal;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $jamkerjabydate = Setjamkerjabydate::where('kode_yayasan_masar', $kode_yayasan_masar)
            ->join('presensi_jamkerja', 'presensi_jamkerja.kode_jam_kerja', '=', 'presensi_jamkerja_bydate.kode_jam_kerja')
            ->whereRaw('MONTH(tanggal) = ' . $bulan . ' AND YEAR(tanggal) = ' . $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json($jamkerjabydate);
    }

    public function deletejamkerjabydate(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');

        try {
            Setjamkerjabydate::where('kode_yayasan_masar', $request->kode_yayasan_masar)->where('tanggal', $tanggal)->delete();
            return response()->json(['success' => true, 'message' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function createuser($kode_yayasan)
    {
        $generalsetting = Pengaturanumum::first();
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)->first();
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $yayasan_masar->nama,
                'username' => $yayasan_masar->kode_yayasan,
                'password' => Hash::make($yayasan_masar->kode_yayasan),
                'email' => strtolower(removeTitik($yayasan_masar->kode_yayasan)) . '@' . $generalsetting->domain_email,
            ]);

            DB::table('users_yayasan_masar')->insert([
                'kode_yayasan' => $kode_yayasan,
                'id_user' => $user->id
            ]);

            $user->assignRole('karyawan');
            DB::commit();
            return Redirect::route('yayasan_masar.index')->with(messageSuccess('User Berhasil Dibuat'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deleteuser($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        try {
            $user_yayasan_masar = DB::table('users_yayasan_masar')->where('kode_yayasan', $kode_yayasan)->first();
            User::where('id', $user_yayasan_masar->id_user)->delete();
            DB::table('users_yayasan_masar')->where('kode_yayasan', $kode_yayasan)->delete();
            return Redirect::back()->with(messageSuccess('User Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data User gagal dihapus ' . $e->getMessage()));
        }
    }

    public function show_detail($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)
            ->join('cabang', 'yayasan_masar.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('departemen', 'yayasan_masar.kode_dept', '=', 'departemen.kode_dept')
            ->join('jabatan', 'yayasan_masar.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('status_kawin', 'yayasan_masar.kode_status_kawin', '=', 'status_kawin.kode_status_kawin')
            ->first();
        $user_yayasan_masar = DB::table('users_yayasan_masar')->where('kode_yayasan', $kode_yayasan)->first();
        $user = $user_yayasan_masar ? User::where('id', $user_yayasan_masar->id_user)->first() : null;
        $wajah = DB::table('yayasan_masar_wajah')->where('kode_yayasan', $kode_yayasan)->get();
        $data['yayasan_masar'] = $yayasan_masar;
        $data['user'] = $user;
        $data['wajah'] = $wajah;
        return view('datamaster.yayasan_masar.show_detail', $data);
    }

    public function idcard($kode_yayasan)
    {
        $kode_yayasan = Crypt::decrypt($kode_yayasan);
        $yayasan_masar = YayasanMasar::where('kode_yayasan', $kode_yayasan)
            ->join('departemen', 'yayasan_masar.kode_dept', '=', 'departemen.kode_dept')
            ->join('jabatan', 'yayasan_masar.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->first();
        $data['yayasan_masar'] = $yayasan_masar;
        $generalsetting = Pengaturanumum::where('id', 1)->first();
        $data['generalsetting'] = $generalsetting;
        return view('datamaster.yayasan_masar.idcard', $data);
    }

    // Form Import
    public function importForm()
    {
        return view('datamaster.yayasan_masar.import');
    }

    // Download Template Excel
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header columns
        $headers = ['No. Identitas', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Jenis Kelamin', 'No. HP', 'Email', 'Status Kawin', 'Pendidikan Terakhir', 'Tanggal Masuk', 'Status Umroh'];
        $sheet->fromArray([$headers], NULL, 'A1');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        
        // Make header bold and center
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1:L1')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        // Add sample/instruction row
        $sheet->setCellValue('A2', '(Contoh)');
        $sheet->setCellValue('B2', 'Nama Jamaah');
        $sheet->setCellValue('C2', 'Kota');
        $sheet->setCellValue('D2', '2000-01-01');
        $sheet->setCellValue('E2', 'Jl. Contoh No. 1');
        $sheet->setCellValue('F2', 'L');
        $sheet->setCellValue('G2', '081234567890');
        $sheet->setCellValue('H2', 'email@example.com');
        $sheet->setCellValue('I2', '-');
        $sheet->setCellValue('J2', 'SMA');
        $sheet->setCellValue('K2', '2024-01-01');
        $sheet->setCellValue('L2', '0');
        
        // Add notes
        $sheet->setCellValue('A4', 'CATATAN:');
        $sheet->setCellValue('A5', '• Jenis Kelamin: L (Laki-laki) atau P (Perempuan)');
        $sheet->setCellValue('A6', '• Tanggal format: YYYY-MM-DD (misal: 2024-01-15)');
        $sheet->setCellValue('A7', '• Status Umroh: 0 (Tidak) atau 1 (Umroh)');
        $sheet->setCellValue('A8', '• Kolom yang tidak wajib boleh dikosongkan');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_yayasan_masar_' . date('Y-m-d_His') . '.xlsx';
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // Export Excel - semua data
    public function exportExcel()
    {
        $data = YayasanMasar::select('no_identitas', 'nama', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'jenis_kelamin', 'no_hp', 'email', 'kode_status_kawin', 'pendidikan_terakhir', 'tanggal_masuk', 'status_umroh', 'pin')->get();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Jamaah');
        
        // Set header
        $headers = ['No. Identitas', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Jenis Kelamin', 'No. HP', 'Email', 'Status Kawin', 'Pendidikan Terakhir', 'Tanggal Masuk', 'PIN', 'Status Umroh'];
        $sheet->fromArray([$headers], NULL, 'A1');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        
        // Make header bold and colored
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1:M1')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        // Add data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->no_identitas);
            $sheet->setCellValue('B' . $row, $item->nama);
            $sheet->setCellValue('C' . $row, $item->tempat_lahir);
            $sheet->setCellValue('D' . $row, $item->tanggal_lahir);
            $sheet->setCellValue('E' . $row, $item->alamat);
            $sheet->setCellValue('F' . $row, $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('G' . $row, $item->no_hp);
            $sheet->setCellValue('H' . $row, $item->email);
            $sheet->setCellValue('I' . $row, $item->kode_status_kawin);
            $sheet->setCellValue('J' . $row, $item->pendidikan_terakhir);
            $sheet->setCellValue('K' . $row, $item->tanggal_masuk);
            $sheet->setCellValue('L' . $row, $item->pin);
            $sheet->setCellValue('M' . $row, $item->status_umroh == '1' ? 'Umroh' : 'Tidak');
            
            // Center alignment for certain columns
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'export_yayasan_masar_' . date('Y-m-d_His') . '.xlsx';
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // Import Excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            $imported = 0;
            $failed = 0;
            
            // Skip header row
            for ($i = 2; $i <= count($rows); $i++) {
                $row = $rows[$i - 1];
                
                if (empty($row[0]) || empty($row[1])) {
                    $failed++;
                    continue;
                }
                
                try {
                    $tahun = date('y');
                    $bulan = date('m');
                    $prefix = $tahun . $bulan;

                    $last = YayasanMasar::where('kode_yayasan', 'like', $prefix . '%')
                        ->orderBy('kode_yayasan', 'desc')
                        ->first();

                    $lastNumber = 0;
                    if ($last) {
                        $lastNumber = (int)substr($last->kode_yayasan, 4, 5);
                    }
                    $nextNumber = $lastNumber + 1;
                    $kode_yayasan = $prefix . str_pad((string)$nextNumber, 5, '0', STR_PAD_LEFT);
                    
                    YayasanMasar::create([
                        'kode_yayasan' => $kode_yayasan,
                        'no_identitas' => $row[0],
                        'nama' => $row[1],
                        'tempat_lahir' => $row[2] ?? null,
                        'tanggal_lahir' => $row[3] ?? null,
                        'alamat' => $row[4] ?? null,
                        'jenis_kelamin' => $row[5] ?? 'L',
                        'no_hp' => $row[6] ?? null,
                        'email' => $row[7] ?? null,
                        'kode_status_kawin' => $row[8] ?? null,
                        'pendidikan_terakhir' => $row[9] ?? null,
                        'tanggal_masuk' => $row[10] ?? date('Y-m-d'),
                        'status_umroh' => $row[11] ?? '0',
                        'lock_location' => 1,
                        'status_aktif' => 1,
                        'password' => Hash::make('12345'),
                        'kode_cabang' => null,
                        'kode_dept' => null,
                        'kode_jabatan' => null,
                        'status' => 'T'
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
            
            return redirect()->back()->with('success', "Import berhasil! Data diimpor: $imported, Gagal: $failed");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import file: ' . $e->getMessage());
        }
    }
    public function generateIdCard($kode_yayasan)
    {
        $jamaah = YayasanMasar::find($kode_yayasan);
        if (!$jamaah) {
            abort(404, 'Data jamaah tidak ditemukan');
        }

        // Generate ID Card dengan background
        return $this->createPremiumIdCard($jamaah);
    }

    private function createPremiumIdCard($jamaah)
    {
        // === PREMIUM ELEGANT DESIGN ID CARD - MATCHED TO REFERENCE ===
        $width = 638;
        $height = 1012;

        // Load background image
        $bgPath = public_path('assets/template/img/logo/idcardback.png');
        if (file_exists($bgPath)) {
            $img = imagecreatefrompng($bgPath);
            $resized = imagecreatetruecolor($width, $height);
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
            $img = $resized;
            imagedestroy($img);
        } else {
            $img = imagecreatetruecolor($width, $height);
            $creameBg = imagecolorallocate($img, 250, 250, 245);
            imagefilledrectangle($img, 0, 0, $width, $height, $creameBg);
        }

        // Define warna palette
        $elegantGreen = imagecolorallocate($img, 30, 127, 61);    // Hijau elegan
        $premiumBlue = imagecolorallocate($img, 30, 92, 207);     // Biru premium
        $darkGreen = imagecolorallocate($img, 15, 63, 30);        // Hijau tua
        $softGold = imagecolorallocate($img, 218, 165, 32);       // Soft gold
        $black = imagecolorallocate($img, 0, 0, 0);               // Black
        $white = imagecolorallocate($img, 255, 255, 255);         // Pure white

        // ============== HEADER SECTION - LOGO LEBIH KECIL ==============
        
        // LOGO 75x75px (lebih kecil dari sebelumnya)
        $logoPath = public_path('assets/template/img/logo/logoyayasan.png');
        $logoSize = 75;
        $logoX = ($width - $logoSize) / 2;
        $logoY = 20;
        
        if (file_exists($logoPath)) {
            $logo = imagecreatefrompng($logoPath);
            imagealphablending($img, true);
            imagesavealpha($logo, true);
            imagecopyresampled($img, $logo, $logoX, $logoY, 0, 0, $logoSize, $logoSize, imagesx($logo), imagesy($logo));
            imagedestroy($logo);
        }
        
        // Nama Yayasan - LEBIH KECIL DAN RAPI (2 baris)
        $yayasanName1 = 'YAYASAN MUHAMMAD';
        $yayasanName2 = 'SULTAN RAMADHAN';
        $yayasanY1 = 105;
        $yayasanY2 = 125;
        
        // Line 1
        $width1 = strlen($yayasanName1) * imagefontwidth(3);
        $x1 = ($width - $width1) / 2;
        imagestring($img, 3, $x1, $yayasanY1, $yayasanName1, $elegantGreen);
        
        // Line 2
        $width2 = strlen($yayasanName2) * imagefontwidth(3);
        $x2 = ($width - $width2) / 2;
        imagestring($img, 3, $x2, $yayasanY2, $yayasanName2, $elegantGreen);

        // ============== JUDUL KARTU - BESAR PROMINENT ==============
        
        $titleY = 165;
        $titleText = 'MAJLIS TA\'LIM AL-IKHLAS';
        // Gunakan font size lebih besar dengan custom approach
        for ($size = 5; $size > 0; $size--) {
            $titleWidth = strlen($titleText) * imagefontwidth($size);
            if ($titleWidth < $width - 40) {
                break;
            }
        }
        $titleWidth = strlen($titleText) * imagefontwidth($size);
        $titleX = ($width - $titleWidth) / 2;
        imagestring($img, $size, $titleX, $titleY, $titleText, $darkGreen);

        // ============== FOTO LINGKARAN - LEBIH BESAR ==============
        
        $photoRadius = 130;
        $photoCenterX = $width / 2;
        $photoCenterY = 390;
        
        // Draw border lingkaran biru (lebih tebal)
        for ($i = 0; $i < 14; $i++) {
            imagearc($img, $photoCenterX, $photoCenterY, 
                    (2 * $photoRadius) + (14 - $i), 
                    (2 * $photoRadius) + (14 - $i), 
                    0, 360, $premiumBlue);
        }
        
        // Fill interior dengan white
        imagefilledarc($img, $photoCenterX, $photoCenterY, 
                      (2 * $photoRadius), (2 * $photoRadius), 
                      0, 360, $white, IMG_ARC_PIE);
        
        // Load foto
        $photoPath = public_path('yayasan_masar/' . $jamaah->foto);
        if (file_exists($photoPath) && !empty($jamaah->foto)) {
            try {
                $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
                if ($ext == 'jpg' || $ext == 'jpeg') {
                    $photo = imagecreatefromjpeg($photoPath);
                } elseif ($ext == 'png') {
                    $photo = imagecreatefrompng($photoPath);
                } else {
                    $photo = null;
                }

                if ($photo) {
                    $photoWidth = imagesx($photo);
                    $photoHeight = imagesy($photo);
                    $size = min($photoWidth, $photoHeight);
                    $srcX = ($photoWidth - $size) / 2;
                    $srcY = ($photoHeight - $size) / 2;
                    
                    imagecopyresampled($img, $photo, 
                                     $photoCenterX - $photoRadius + 10, 
                                     $photoCenterY - $photoRadius + 10,
                                     $srcX, $srcY, 
                                     (2 * $photoRadius) - 20, (2 * $photoRadius) - 20, 
                                     $size, $size);
                    
                    imagedestroy($photo);
                }
            } catch (\Exception $e) {
                // Silent
            }
        }

        // ============== DATA SECTION - LAYOUT BARU ==============
        
        $dataStartY = $photoCenterY + $photoRadius + 90;
        
        // NAMA JAMAAH - BESAR BOLD HITAM
        $namaText = strtoupper(substr($jamaah->nama, 0, 28));
        $nameY = $dataStartY;
        $nameWidth = strlen($namaText) * imagefontwidth(5);
        $nameX = ($width - $nameWidth) / 2;
        imagestring($img, 5, $nameX, $nameY, $namaText, $black);
        
        // UNDERLINE BIRU DI BAWAH NAMA - TEBAL
        $underlineY = $nameY + 22;
        imagesetthickness($img, 6);
        imageline($img, 100, $underlineY, $width - 100, $underlineY, $premiumBlue);
        imagesetthickness($img, 1);

        // LABEL "NO. IDENTITAS" - MEDIUM
        $idLabelY = $nameY + 50;
        $idLabel = 'NO. IDENTITAS';
        $idWidth = strlen($idLabel) * imagefontwidth(4);
        $idX = ($width - $idWidth) / 2;
        imagestring($img, 4, $idX, $idLabelY, $idLabel, $black);
        
        // UNDERLINE BIRU LABEL
        $idUnderlineY = $idLabelY + 16;
        imagesetthickness($img, 4);
        imageline($img, 120, $idUnderlineY, $width - 120, $idUnderlineY, $premiumBlue);
        imagesetthickness($img, 1);

        // NOMOR IDENTITAS - BESAR BOLD
        $nikText = $jamaah->no_identitas;
        $nikY = $idLabelY + 40;
        $nikWidth = strlen($nikText) * imagefontwidth(5);
        $nikX = ($width - $nikWidth) / 2;
        imagestring($img, 5, $nikX, $nikY, $nikText, $black);

        // TANGGAL TERBIT - MEDIUM
        $dateIssuedY = $nikY + 45;
        $dateIssuedText = date('d F Y');
        $dateWidth = strlen($dateIssuedText) * imagefontwidth(4);
        $dateX = ($width - $dateWidth) / 2;
        imagestring($img, 4, $dateX, $dateIssuedY, $dateIssuedText, $black);

        // PIN KARTU - MEDIUM (bisa random atau dari data)
        $pinY = $dateIssuedY + 38;
        // Generate PIN lebih rapi
        $pinGenerated = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 40);
        $pinText = $pinGenerated;
        $pinWidth = strlen($pinText) * imagefontwidth(3);
        $pinX = ($width - $pinWidth) / 2;
        imagestring($img, 3, $pinX, $pinY, $pinText, $black);

        // ============== YEAR BADGE ==============
        
        $tahunMasuk = date('Y', strtotime($jamaah->tanggal_masuk));
        $badgeY = $pinY + 50;
        
        $badgeX = ($width - 140) / 2;
        $badgeWidth = 140;
        $badgeHeight = 70;
        $cornerRadius = 12;
        
        // Draw badge background biru
        $badgeTop = $badgeY;
        $badgeBottom = $badgeY + $badgeHeight;
        $badgeLeft = $badgeX;
        $badgeRight = $badgeX + $badgeWidth;
        
        // Rounded rectangle
        imagefilledrectangle($img, $badgeLeft + $cornerRadius, $badgeTop, 
                           $badgeRight - $cornerRadius, $badgeBottom, $premiumBlue);
        imagefilledrectangle($img, $badgeLeft, $badgeTop + $cornerRadius, 
                           $badgeRight, $badgeBottom - $cornerRadius, $premiumBlue);
        
        // Corners
        imagefilledarc($img, $badgeLeft + $cornerRadius, $badgeTop + $cornerRadius, 
                      2 * $cornerRadius, 2 * $cornerRadius, 180, 270, $premiumBlue, IMG_ARC_PIE);
        imagefilledarc($img, $badgeRight - $cornerRadius, $badgeTop + $cornerRadius, 
                      2 * $cornerRadius, 2 * $cornerRadius, 270, 360, $premiumBlue, IMG_ARC_PIE);
        imagefilledarc($img, $badgeLeft + $cornerRadius, $badgeBottom - $cornerRadius, 
                      2 * $cornerRadius, 2 * $cornerRadius, 90, 180, $premiumBlue, IMG_ARC_PIE);
        imagefilledarc($img, $badgeRight - $cornerRadius, $badgeBottom - $cornerRadius, 
                      2 * $cornerRadius, 2 * $cornerRadius, 0, 90, $premiumBlue, IMG_ARC_PIE);
        
        // Year text - PUTIH BESAR
        $yearWidth = strlen($tahunMasuk) * imagefontwidth(5);
        $yearX = $badgeX + ($badgeWidth - $yearWidth) / 2;
        $yearY = $badgeY + 50;
        imagestring($img, 5, $yearX, $yearY, $tahunMasuk, $white);

        // ============== BOTTOM BAR ==============
        
        $bottomBarHeight = 22;
        imagefilledrectangle($img, 0, $height - $bottomBarHeight, $width, $height, $premiumBlue);

        // ============== OUTPUT ==============
        
        header('Content-Type: image/jpeg');
        $filename = 'ID_CARD_' . str_replace(' ', '_', $jamaah->nama) . '_' . $jamaah->no_identitas . '.jpg';
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        imagejpeg($img, null, 95);
        imagedestroy($img);
        exit;
    }

    /**
     * Download ID Card dalam format JPG
     */
    public function downloadIdCard($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $yayasan_masar = YayasanMasar::with(['departemen', 'jabatan'])->findOrFail($id);
            $data['yayasan_masar'] = $yayasan_masar;
            return view('datamaster.yayasan_masar.id_card_download', $data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getyayasan_masar(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $yayasan_masar = YayasanMasar::where('kode_cabang', $kode_cabang)->get();
        return response()->json($yayasan_masar);
    }

    /**
     * Generate No. Identitas otomatis dengan format: YYMMDD + 4 digit urut per hari
     * Contoh: 2512061001, 2512061002, dst
     */
    private function generateNoIdentitas()
    {
        $today = date('ymd'); // YYMMDD
        $prefix = $today; // e.g., 251206 (6 December 2025)

        // Cari nomor urut terakhir untuk hari ini
        $last = YayasanMasar::where('no_identitas', 'like', $prefix . '%')
            ->orderBy('no_identitas', 'desc')
            ->first();

        $lastNumber = 0;
        if ($last) {
            $lastNumber = (int)substr($last->no_identitas, 6, 4); // Ambil 4 digit terakhir
        }

        $nextNumber = $lastNumber + 1;
        $noIdentitas = $prefix . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);

        return $noIdentitas;
    }

    /**
     * Sync file from storage/app/public to public/storage (Windows fix)
     */
    private function syncStorageFile($folder, $filename)
    {
        try {
            $source = storage_path('app/public/' . $folder . '/' . $filename);
            $destination = public_path('storage/' . $folder . '/' . $filename);

            // Create destination folder if not exists
            if (!is_dir(dirname($destination))) {
                mkdir(dirname($destination), 0755, true);
            }

            // Copy file
            if (file_exists($source)) {
                copy($source, $destination);
            }
        } catch (\Exception $e) {
            // Silent fail - file is still in storage
            \Log::warning('Storage sync failed: ' . $e->getMessage());
        }
    }
}
