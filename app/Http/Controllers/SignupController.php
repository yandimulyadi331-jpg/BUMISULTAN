<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\StatusKawin;
use App\Models\Facerecognition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SignupController extends Controller
{
    public function index()
    {
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $departemen = Departemen::orderBy('nama_dept')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $status_kawin = StatusKawin::orderBy('status_kawin')->get();
        
        return view('auth.signup', compact('cabang', 'departemen', 'jabatan', 'status_kawin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik_show' => 'required',
            'no_ktp' => 'required|unique:karyawan,no_ktp',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required',
            'email' => 'required|email|unique:karyawan,email',
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_cabang' => 'required|exists:cabang,kode_cabang',
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
            'tanggal_masuk' => 'required|date',
            'status_karyawan' => 'required|in:K,T',
            'password' => 'required|min:6|confirmed',
        ], [
            'no_ktp.unique' => 'No. KTP sudah terdaftar',
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();
            
            // Generate NIK format YYMM + 5 digit urut per bulan
            $tahun = date('y');
            $bulan = date('m');
            $prefix = $tahun . $bulan;

            $last = Karyawan::where('nik', 'like', $prefix . '%')
                ->orderBy('nik', 'desc')
                ->first();

            $lastNumber = 0;
            if ($last) {
                $lastNumber = (int)substr($last->nik, 4, 5);
            }
            $nextNumber = $lastNumber + 1;
            $nikAuto = $prefix . str_pad((string)$nextNumber, 5, '0', STR_PAD_LEFT);
            
            $data_foto = [];
            
            // Handle foto dari input file (hasil capture camera)
            if ($request->hasfile('foto')) {
                $foto_name = $nikAuto . "." . $request->file('foto')->getClientOriginalExtension();
                $destination_foto_path = "/public/karyawan";
                $foto = $foto_name;
                $data_foto = [
                    'foto' => $foto
                ];
            }

            $data_karyawan = [
                'nik' => $nikAuto,
                'nik_show' => $request->nik_show,
                'no_ktp' => $request->no_ktp,
                'nama_karyawan' => $request->nama_karyawan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_jabatan' => $request->kode_jabatan,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'lock_location' => 1,
                'status_aktif_karyawan' => 0, // Status pending, perlu approval admin
                'password' => Hash::make($request->password)
            ];

            $data = array_merge($data_karyawan, $data_foto);
            $simpan = Karyawan::create($data);
            
            if ($simpan) {
                // Simpan foto ke storage
                if ($request->hasfile('foto')) {
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
                
                // OTOMATIS SIMPAN DATA WAJAH UNTUK FACE RECOGNITION ABSENSI
                if ($request->hasfile('foto')) {
                    // Generate nama file wajah dengan timestamp
                    $timestamp = date('YmdHis');
                    $foto_wajah_name = $nikAuto . "_" . $timestamp . "." . $request->file('foto')->getClientOriginalExtension();
                    $destination_wajah_path = "/public/karyawan/wajah";
                    
                    // Simpan foto wajah ke folder khusus face recognition
                    $request->file('foto')->storeAs($destination_wajah_path, $foto_wajah_name);
                    
                    // Simpan data wajah ke tabel karyawan_wajah (kolom: wajah, bukan foto_wajah)
                    Facerecognition::create([
                        'nik' => $nikAuto,
                        'wajah' => $foto_wajah_name,  // Sesuaikan dengan nama kolom di database
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                DB::commit();
                
                return redirect()->route('loginuser')->with('success', 'Pendaftaran berhasil! Data wajah Anda sudah terdaftar untuk absensi. Silakan tunggu persetujuan admin untuk dapat login.');
            }

            DB::rollBack();
            return Redirect::back()->with('error', 'Pendaftaran gagal, silakan coba lagi');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
}
