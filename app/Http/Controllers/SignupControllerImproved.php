<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\StatusKawin;
use App\Models\Facerecognition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SignupControllerImproved extends Controller
{
    public function index()
    {
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $departemen = Departemen::orderBy('nama_dept')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $status_kawin = StatusKawin::orderBy('status_kawin')->get();
        
        return view('auth.signup_wizard', compact('cabang', 'departemen', 'jabatan', 'status_kawin'));
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
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_cabang' => 'required|exists:cabang,kode_cabang',
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
            'tanggal_masuk' => 'required|date',
            'status_karyawan' => 'required|in:K,T',
            'password' => 'required|min:6|confirmed',
            'foto_profil' => 'required',
            'foto_wajah_multiple' => 'required',
        ], [
            'no_ktp.unique' => 'No. KTP sudah terdaftar',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'foto_profil.required' => 'Foto profil harus diambil',
            'foto_wajah_multiple.required' => 'Foto wajah untuk absensi harus diambil (5 gambar)',
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
            
            // ===== SIMPAN FOTO PROFIL =====
            $foto_profil_name = null;
            if ($request->has('foto_profil') && !empty($request->foto_profil)) {
                $foto_profil_base64 = $request->foto_profil;
                
                // Decode base64
                $image = str_replace('data:image/jpeg;base64,', '', $foto_profil_base64);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);
                
                // Nama file foto profil
                $foto_profil_name = $nikAuto . "_profil.jpg";
                $destination_foto_path = storage_path('app/public/karyawan');
                
                // Buat folder jika belum ada
                if (!file_exists($destination_foto_path)) {
                    mkdir($destination_foto_path, 0777, true);
                }
                
                // Simpan file
                file_put_contents($destination_foto_path . '/' . $foto_profil_name, $imageData);
            }

            // ===== SIMPAN DATA KARYAWAN =====
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
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_jabatan' => $request->kode_jabatan,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'lock_location' => 1,
                'status_aktif_karyawan' => 0, // Status pending, perlu approval admin
                'password' => Hash::make($request->password),
                'foto' => $foto_profil_name
            ];

            $simpan = Karyawan::create($data_karyawan);
            
            if ($simpan) {
                // ===== SIMPAN 5 FOTO WAJAH UNTUK ABSENSI =====
                if ($request->has('foto_wajah_multiple') && !empty($request->foto_wajah_multiple)) {
                    $fotoWajahData = json_decode($request->foto_wajah_multiple, true);
                    
                    if (is_array($fotoWajahData) && count($fotoWajahData) == 5) {
                        // PENTING: Gunakan struktur folder per-karyawan sesuai sistem yang sudah ada
                        // Format: uploads/facerecognition/{NIK}-{NAMADEPAN}/
                        $nama_depan = strtolower(explode(' ', trim($request->nama_karyawan))[0]);
                        $folder_name = $nikAuto . '-' . $nama_depan;
                        $destination_wajah_path = storage_path('app/public/uploads/facerecognition/' . $folder_name);
                        
                        // Buat folder jika belum ada
                        if (!file_exists($destination_wajah_path)) {
                            mkdir($destination_wajah_path, 0777, true);
                        }
                        
                        foreach ($fotoWajahData as $index => $fotoData) {
                            // Decode base64
                            $image = str_replace('data:image/jpeg;base64,', '', $fotoData['image']);
                            $image = str_replace(' ', '+', $image);
                            $imageData = base64_decode($image);
                            
                            // Nama file dengan NIK prefix untuk identifikasi
                            // Format: {NIK}_{direction}.jpg (misal: 251100001_1_front.jpg)
                            $foto_wajah_name = $nikAuto . '_' . $fotoData['direction'] . ".jpg";
                            
                            // Simpan file
                            file_put_contents($destination_wajah_path . '/' . $foto_wajah_name, $imageData);
                            
                            // Simpan ke database karyawan_wajah
                            Facerecognition::create([
                                'nik' => $nikAuto,
                                'wajah' => $foto_wajah_name,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    } else {
                        DB::rollBack();
                        return Redirect::back()->with('error', 'Data foto wajah tidak lengkap. Harus 5 gambar!')->withInput();
                    }
                }
                
                // ===== AUTO CREATE USER ACCOUNT =====
                // Otomatis membuat akun user agar karyawan bisa login setelah diapprove admin
                $password_hashed = $data_karyawan['password']; // Password sudah di-hash di atas
                
                User::create([
                    'name' => $request->nama_karyawan,
                    'username' => $nikAuto, // Username = NIK
                    'email' => $nikAuto . '@company.com', // Email dummy, bisa diganti sesuai kebutuhan
                    'password' => $password_hashed, // Gunakan password yang sama dengan tabel karyawan
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                DB::commit();
                
                return redirect()->route('loginuser')->with('success', 'Pendaftaran berhasil! Akun Anda sudah dibuat. Silakan tunggu persetujuan admin untuk dapat login dengan NIK: ' . $nikAuto . ' dan password yang Anda daftarkan.');
            }

            DB::rollBack();
            return Redirect::back()->with('error', 'Pendaftaran gagal, silakan coba lagi');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
}
