<?php

namespace App\Http\Controllers;

use App\Models\Slipgaji;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Mail\SlipGajiMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SlipgajiController extends Controller
{
    public function index()
    {
        $user = User::where('id', auth()->user()->id)->first();

        $data['start_year'] = config('global.start_year');
        if ($user->hasRole('karyawan')) {
            $data['slipgaji'] = Slipgaji::orderBy('tahun')
                ->orderBy('bulan')
                ->where('status', '1')
                ->get();
            return view('payroll.slipgaji.index_mobile', $data);
        } else {
            $data['slipgaji'] = Slipgaji::orderBy('tahun')->orderBy('bulan')->get();
            return view('payroll.slipgaji.index', $data);
        }
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('payroll.slipgaji.create', $data);
    }

    public function store(Request $request)
    {

        try {
            Slipgaji::create([
                'kode_slip_gaji' => 'GJ' . $request->bulan . $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);
        $data['slipgaji'] = Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('payroll.slipgaji.edit', $data);
    }

    public function update(Request $request, $kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);
        try {
            Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->update([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);
        try {
            Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function sendSlipGajiEmail(Request $request)
    {
        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            
            // Validasi slip gaji exists
            $slipGaji = Slipgaji::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();
                
            if (!$slipGaji) {
                return Redirect::back()->with(messageError('Slip gaji tidak ditemukan'));
            }

            // Ambil karyawan yang punya email
            $karyawan = Karyawan::whereNotNull('email')
                ->where('email', '!=', '')
                ->where('status_aktif_karyawan', '1')
                ->get();

            if ($karyawan->count() == 0) {
                return Redirect::back()->with(messageError('Tidak ada karyawan dengan email yang terdaftar'));
            }

            $berhasil = 0;
            $gagal = 0;
            $errors = [];

            foreach ($karyawan as $k) {
                try {
                    // Generate PDF slip gaji untuk karyawan ini
                    $pdfPath = $this->generateSlipGajiPDF($k->nik, $bulan, $tahun);
                    
                    // Kirim email
                    Mail::to($k->email)->send(new SlipGajiMail($k, $bulan, $tahun, $pdfPath));
                    
                    // Hapus file PDF setelah dikirim
                    if ($pdfPath && file_exists($pdfPath)) {
                        unlink($pdfPath);
                    }
                    
                    $berhasil++;
                } catch (\Exception $e) {
                    $gagal++;
                    $errors[] = "Gagal kirim ke {$k->nama_karyawan}: " . $e->getMessage();
                }
            }

            $message = "Berhasil mengirim slip gaji ke {$berhasil} karyawan";
            if ($gagal > 0) {
                $message .= ", {$gagal} gagal";
            }

            return Redirect::back()->with(messageSuccess($message));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Error: ' . $e->getMessage()));
        }
    }

    public function sendSlipGajiEmailSingle(Request $request)
    {
        try {
            $nik = $request->nik;
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            
            // Ambil data karyawan
            $karyawan = Karyawan::where('nik', $nik)->first();
            
            if (!$karyawan) {
                return Redirect::back()->with(messageError('Karyawan tidak ditemukan'));
            }

            if (empty($karyawan->email)) {
                return Redirect::back()->with(messageError('Email karyawan tidak terdaftar'));
            }

            // Generate PDF slip gaji
            $pdfPath = null;
            
            // Kirim email
            Mail::to($karyawan->email)->send(new SlipGajiMail($karyawan, $bulan, $tahun, $pdfPath));

            return Redirect::back()->with(messageSuccess("Slip gaji berhasil dikirim ke email {$karyawan->email}"));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Error: ' . $e->getMessage()));
        }
    }

    public function selectKaryawan()
    {
        $data['start_year'] = config('global.start_year');
        $data['karyawan'] = Karyawan::with(['departemen', 'jabatan'])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('status_aktif_karyawan', '1')
            ->orderBy('nama_karyawan', 'asc')
            ->get();
        
        return view('payroll.slipgaji.select_karyawan', $data);
    }

    public function sendSlipGajiEmailSelected(Request $request)
    {
        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $nikArray = $request->nik; // Array NIK yang dipilih
            
            // Validasi
            if (!$bulan || !$tahun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bulan dan tahun harus dipilih'
                ], 400);
            }

            if (!$nikArray || count($nikArray) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan pilih minimal 1 karyawan'
                ], 400);
            }
            
            // Validasi slip gaji exists
            $slipGaji = Slipgaji::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();
                
            if (!$slipGaji) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slip gaji untuk periode yang dipilih tidak ditemukan'
                ], 404);
            }

            // Ambil karyawan yang dipilih
            $karyawan = Karyawan::whereIn('nik', $nikArray)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->where('status_aktif_karyawan', '1')
                ->get();

            if ($karyawan->count() == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada karyawan valid yang dipilih'
                ], 400);
            }

            $berhasil = 0;
            $gagal = 0;
            $errors = [];

            foreach ($karyawan as $k) {
                try {
                    // Generate PDF slip gaji untuk karyawan ini
                    $pdfPath = $this->generateSlipGajiPDF($k->nik, $bulan, $tahun);
                    
                    // Kirim email
                    Mail::to($k->email)->send(new SlipGajiMail($k, $bulan, $tahun, $pdfPath));
                    
                    // Hapus file PDF setelah dikirim
                    if ($pdfPath && file_exists($pdfPath)) {
                        unlink($pdfPath);
                    }
                    
                    $berhasil++;
                } catch (\Exception $e) {
                    $gagal++;
                    $errors[] = "Gagal kirim ke {$k->nama_karyawan}: " . $e->getMessage();
                    \Log::error("Error sending email to {$k->nama_karyawan}: " . $e->getMessage());
                }
            }

            $message = "Berhasil mengirim slip gaji ke {$berhasil} dari " . count($nikArray) . " karyawan";
            if ($gagal > 0) {
                $message .= ", {$gagal} gagal dikirim";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'berhasil' => $berhasil,
                'gagal' => $gagal,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending slip gaji emails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cetakslipgajiPublic($nik, $bulan, $tahun)
    {
        // Method ini bisa diakses tanpa login untuk view slip gaji dari email
        // Panggil LaporanController untuk generate slip gaji
        $laporanController = new \App\Http\Controllers\LaporanController();
        
        $request = new Request([
            'nik' => $nik,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'format_laporan' => 3, // Format slip gaji
            'periode_laporan' => 1
        ]);
        
        return $laporanController->cetakpresensi($request);
    }

    private function generateSlipGajiPDF($nik, $bulan, $tahun)
    {
        try {
            // Ambil data karyawan
            $karyawan = Karyawan::with(['jabatan', 'departemen'])->where('nik', $nik)->first();
            
            if (!$karyawan) {
                return null;
            }
            
            // Data simple untuk PDF
            $data = [
                'karyawan' => $karyawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama_bulan' => getNamabulan($bulan)
            ];
            
            // Generate PDF sederhana
            $html = view('emails.slipgaji.pdf-slip', $data)->render();
            
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            
            // Simpan PDF temporary
            $fileName = 'slip_gaji_' . $karyawan->nama_karyawan . '_' . getNamabulan($bulan) . '_' . $tahun . '.pdf';
            $filePath = storage_path('app/temp/' . $fileName);
            
            // Buat folder temp jika belum ada
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            $pdf->save($filePath);
            
            return $filePath;
        } catch (\Exception $e) {
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return null;
        }
    }
}

