<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\PinjamanCicilan;
use App\Models\PinjamanHistory;
use App\Models\PinjamanEmailNotification;
use App\Models\Karyawan;
use App\Models\TransaksiKeuangan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PinjamanJatuhTempoMail;
use Carbon\Carbon;
use PDF;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pinjaman::with(['karyawan', 'pengaju', 'penyetuju', 'emailNotifications'])->orderBy('created_at', 'desc');

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_peminjam', $request->kategori);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('tanggal_pengajuan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereYear('tanggal_pengajuan', $request->tahun);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pinjaman', 'like', "%$search%")
                  ->orWhere('nama_peminjam', 'like', "%$search%")
                  ->orWhere('tujuan_pinjaman', 'like', "%$search%")
                  ->orWhereHas('karyawan', function($q) use ($search) {
                      $q->where('nama_karyawan', 'like', "%$search%");
                  });
            });
        }

        $pinjaman = $query->paginate(20);

        // Statistik
        $stats = [
            'total_pengajuan' => Pinjaman::where('status', 'pengajuan')->count(),
            'total_review' => Pinjaman::where('status', 'review')->count(),
            'total_disetujui' => Pinjaman::where('status', 'disetujui')->count(),
            'total_berjalan' => Pinjaman::whereIn('status', ['dicairkan', 'berjalan'])->count(),
            'total_lunas' => Pinjaman::where('status', 'lunas')->count(),
            'total_nominal_berjalan' => Pinjaman::whereIn('status', ['dicairkan', 'berjalan'])->sum('sisa_pinjaman'),
            'total_nominal_dicairkan' => Pinjaman::whereIn('status', ['dicairkan', 'berjalan', 'lunas'])->sum('jumlah_disetujui'),
        ];

        return view('pinjaman.index', compact('pinjaman', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $karyawans = Karyawan::where('status_aktif_karyawan', '1')->orderBy('nama_karyawan')->get();
        
        // Jika ada parameter duplicate_from, ambil data pinjaman sebelumnya
        $duplicateData = null;
        $pinjamanAktif = [];
        
        if ($request->has('duplicate_from')) {
            $pinjamanLama = Pinjaman::find($request->duplicate_from);
            
            if ($pinjamanLama) {
                // Ambil data peminjam saja, TIDAK termasuk dokumen
                $duplicateData = [
                    'kategori_peminjam' => $pinjamanLama->kategori_peminjam,
                    'karyawan_id' => $pinjamanLama->karyawan_id,
                    'nama_peminjam' => $pinjamanLama->nama_peminjam,
                    'nama_peminjam_lengkap' => $pinjamanLama->nama_peminjam_lengkap,
                    'nik_peminjam' => $pinjamanLama->nik_peminjam,
                    'alamat_peminjam' => $pinjamanLama->alamat_peminjam,
                    'no_telp_peminjam' => $pinjamanLama->no_telp_peminjam,
                    'pekerjaan_peminjam' => $pinjamanLama->pekerjaan_peminjam,
                ];
                
                // Cek pinjaman aktif peminjam ini
                $pinjamanAktif = Pinjaman::where(function($q) use ($pinjamanLama) {
                    if ($pinjamanLama->kategori_peminjam == 'crew') {
                        $q->where('karyawan_id', $pinjamanLama->karyawan_id);
                    } else {
                        $q->where('nik_peminjam', $pinjamanLama->nik_peminjam);
                    }
                })
                ->whereIn('status', ['pengajuan', 'review', 'disetujui', 'dicairkan', 'berjalan'])
                ->with('cicilan')
                ->get();
            }
        }
        
        return view('pinjaman.create', compact('karyawans', 'duplicateData', 'pinjamanAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_peminjam' => 'required|in:crew,non_crew',
            'nama_peminjam_crew' => 'required_if:kategori_peminjam,crew',
            'nik_crew' => 'required_if:kategori_peminjam,crew',
            'nama_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'nik_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'alamat_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'no_telp_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'email_peminjam' => 'required_if:kategori_peminjam,non_crew|nullable|email',
            'pekerjaan_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'tanggal_pengajuan' => 'required|date',
            'jumlah_pengajuan' => 'required|numeric|min:100000',
            'tujuan_pinjaman' => 'required|string',
            'tenor_bulan' => 'required|integer|min:1|max:60',
            'cicilan_per_bulan' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo_setiap_bulan' => 'required|integer|min:1|max:31', // NEW
            // Jaminan (opsional)
            'jenis_jaminan' => 'nullable|string',
            'nomor_jaminan' => 'nullable|string',
            'deskripsi_jaminan' => 'nullable|string',
            'nilai_jaminan' => 'nullable|numeric',
            'atas_nama_jaminan' => 'nullable|string',
            'kondisi_jaminan' => 'nullable|string',
            'keterangan_jaminan' => 'nullable|string',
            // Dokumen
            'dokumen_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_slip_gaji' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_pendukung_lain' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nama_penjamin' => 'nullable|string',
            'hubungan_penjamin' => 'nullable|string',
            'no_telp_penjamin' => 'nullable|string',
            'alamat_penjamin' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle data crew vs non-crew
            if ($request->kategori_peminjam == 'crew') {
                $validated['nama_peminjam_lengkap'] = $request->nama_peminjam_crew;
                $validated['karyawan_id'] = $request->nik_crew; // Gunakan NIK yang diinput manual
            } else {
                $validated['nama_peminjam_lengkap'] = $request->nama_peminjam;
            }

            // Upload dokumen
            if ($request->hasFile('dokumen_ktp')) {
                $validated['dokumen_ktp'] = $request->file('dokumen_ktp')->store('pinjaman/ktp', 'public');
            }
            if ($request->hasFile('dokumen_slip_gaji')) {
                $validated['dokumen_slip_gaji'] = $request->file('dokumen_slip_gaji')->store('pinjaman/slip_gaji', 'public');
            }
            if ($request->hasFile('dokumen_pendukung_lain')) {
                $validated['dokumen_pendukung_lain'] = $request->file('dokumen_pendukung_lain')->store('pinjaman/pendukung', 'public');
            }

            // Generate nomor pinjaman
            $validated['nomor_pinjaman'] = Pinjaman::generateNomorPinjaman();
            $validated['diajukan_oleh'] = auth()->id();
            
            // Set bunga dan tipe bunga ke 0 dan flat (default, tidak digunakan)
            $validated['bunga_persen'] = 0;
            $validated['tipe_bunga'] = 'flat';
            
            // Hitung total pinjaman dari cicilan x tenor
            $validated['total_pinjaman'] = $validated['cicilan_per_bulan'] * $validated['tenor_bulan'];
            $validated['total_pokok'] = $validated['jumlah_pengajuan'];
            $validated['total_bunga'] = 0;

            // Create pinjaman
            $pinjaman = Pinjaman::create($validated);

            // Log history
            $pinjaman->logHistory('pengajuan', null, 'pengajuan', 'Pengajuan pinjaman dibuat');
            
            // Tambahkan notifikasi real-time untuk pengajuan pinjaman
            NotificationService::pinjamanNotification($pinjaman, 'pengajuan');

            DB::commit();

            return redirect()->route('pinjaman.show', $pinjaman->id)
                ->with('success', 'Pengajuan pinjaman berhasil dibuat dengan nomor: ' . $pinjaman->nomor_pinjaman);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengajuan pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load([
            'karyawan', 
            'pengaju', 
            'reviewer', 
            'penyetuju', 
            'pencair',
            'cicilan' => function($query) {
                $query->orderBy('cicilan_ke');
            },
            'history' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Update status cicilan yang terlambat
        foreach ($pinjaman->cicilan as $cicilan) {
            if ($cicilan->status != 'lunas' && !$cicilan->is_ditunda) {
                $cicilan->hitungDenda();
            }
        }

        return view('pinjaman.show', compact('pinjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pinjaman $pinjaman)
    {
        // Hanya bisa edit jika status masih pengajuan
        if (!in_array($pinjaman->status, ['pengajuan', 'review'])) {
            return redirect()->back()->with('error', 'Pinjaman tidak dapat diubah karena sudah diproses');
        }

        $karyawans = Karyawan::where('status_aktif_karyawan', '1')->orderBy('nama_karyawan')->get();
        return view('pinjaman.edit', compact('pinjaman', 'karyawans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pinjaman $pinjaman)
    {
        // Hanya bisa update jika status masih pengajuan
        if (!in_array($pinjaman->status, ['pengajuan', 'review'])) {
            return redirect()->back()->with('error', 'Pinjaman tidak dapat diubah karena sudah diproses');
        }

        $validated = $request->validate([
            'kategori_peminjam' => 'required|in:crew,non_crew',
            'karyawan_id' => 'required_if:kategori_peminjam,crew',
            'nama_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'nik_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'alamat_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'no_telp_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'email_peminjam' => 'required_if:kategori_peminjam,non_crew|nullable|email',
            'pekerjaan_peminjam' => 'required_if:kategori_peminjam,non_crew',
            'tanggal_pengajuan' => 'required|date',
            'jumlah_pengajuan' => 'required|numeric|min:100000',
            'tujuan_pinjaman' => 'required|string',
            'tenor_bulan' => 'required|integer|min:1|max:60',
            'tanggal_jatuh_tempo_setiap_bulan' => 'required|integer|min:1|max:31', // NEW
            'dokumen_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_slip_gaji' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_pendukung_lain' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nama_penjamin' => 'nullable|string',
            'hubungan_penjamin' => 'nullable|string',
            'no_telp_penjamin' => 'nullable|string',
            'alamat_penjamin' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Upload dokumen baru jika ada
            if ($request->hasFile('dokumen_ktp')) {
                if ($pinjaman->dokumen_ktp) {
                    Storage::disk('public')->delete($pinjaman->dokumen_ktp);
                }
                $validated['dokumen_ktp'] = $request->file('dokumen_ktp')->store('pinjaman/ktp', 'public');
            }
            if ($request->hasFile('dokumen_slip_gaji')) {
                if ($pinjaman->dokumen_slip_gaji) {
                    Storage::disk('public')->delete($pinjaman->dokumen_slip_gaji);
                }
                $validated['dokumen_slip_gaji'] = $request->file('dokumen_slip_gaji')->store('pinjaman/slip_gaji', 'public');
            }
            if ($request->hasFile('dokumen_pendukung_lain')) {
                if ($pinjaman->dokumen_pendukung_lain) {
                    Storage::disk('public')->delete($pinjaman->dokumen_pendukung_lain);
                }
                $validated['dokumen_pendukung_lain'] = $request->file('dokumen_pendukung_lain')->store('pinjaman/pendukung', 'public');
            }

            // Update pinjaman
            $pinjaman->update($validated);

            // Log history
            $pinjaman->logHistory('update', null, null, 'Data pinjaman diperbarui');

            DB::commit();

            return redirect()->route('pinjaman.show', $pinjaman->id)
                ->with('success', 'Data pinjaman berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Review pinjaman
     */
    public function review(Request $request, Pinjaman $pinjaman)
    {
        if ($pinjaman->status != 'pengajuan') {
            return redirect()->back()->with('error', 'Pinjaman tidak dapat direview');
        }

        $validated = $request->validate([
            'catatan_review' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $statusLama = $pinjaman->status;
            $pinjaman->update([
                'status' => 'review',
                'direview_oleh' => auth()->id(),
                'tanggal_review' => now(),
                'catatan_review' => $validated['catatan_review'] ?? null,
            ]);

            $pinjaman->logHistory('review', $statusLama, 'review', 'Pinjaman sedang direview');

            DB::commit();

            return redirect()->back()->with('success', 'Pinjaman berhasil direview');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mereview pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Approve atau reject pinjaman
     */
    public function approve(Request $request, Pinjaman $pinjaman)
    {
        if (!in_array($pinjaman->status, ['pengajuan', 'review'])) {
            return redirect()->back()->with('error', 'Pinjaman tidak dapat diproses');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'jumlah_disetujui' => 'required_if:action,approve|numeric|min:0',
            'catatan_persetujuan' => 'nullable|string',
            'alasan_penolakan' => 'required_if:action,reject|string',
        ]);

        try {
            DB::beginTransaction();

            $statusLama = $pinjaman->status;

            if ($validated['action'] == 'approve') {
                $pinjaman->update([
                    'status' => 'disetujui',
                    'disetujui_oleh' => auth()->id(),
                    'tanggal_persetujuan' => now(),
                    'jumlah_disetujui' => $validated['jumlah_disetujui'],
                    'catatan_persetujuan' => $validated['catatan_persetujuan'] ?? null,
                ]);

                $pinjaman->logHistory('approve', $statusLama, 'disetujui', 'Pinjaman disetujui');
                
                // Tambahkan notifikasi real-time untuk persetujuan pinjaman
                NotificationService::pinjamanNotification($pinjaman, 'approve');

                $message = 'Pinjaman berhasil disetujui';
            } else {
                $pinjaman->update([
                    'status' => 'ditolak',
                    'alasan_penolakan' => $validated['alasan_penolakan'],
                ]);

                $pinjaman->logHistory('reject', $statusLama, 'ditolak', 'Pinjaman ditolak: ' . $validated['alasan_penolakan']);
                
                // Tambahkan notifikasi real-time untuk penolakan pinjaman
                NotificationService::pinjamanNotification($pinjaman, 'reject');

                $message = 'Pinjaman berhasil ditolak';
            }

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Proses pencairan pinjaman
     */
    public function cairkan(Request $request, Pinjaman $pinjaman)
    {
        if ($pinjaman->status != 'disetujui') {
            return redirect()->back()->with('error', 'Pinjaman belum disetujui');
        }

        $validated = $request->validate([
            'tanggal_pencairan' => 'required|date',
            'metode_pencairan' => 'required|in:transfer,tunai',
            'no_rekening_tujuan' => 'required_if:metode_pencairan,transfer',
            'nama_bank' => 'required_if:metode_pencairan,transfer',
            'bukti_pencairan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Upload bukti pencairan
            if ($request->hasFile('bukti_pencairan')) {
                $validated['bukti_pencairan'] = $request->file('bukti_pencairan')->store('pinjaman/bukti_cair', 'public');
            }

            $statusLama = $pinjaman->status;
            $validated['status'] = 'dicairkan';
            $validated['dicairkan_oleh'] = auth()->id();

            $pinjaman->update($validated);

            // Generate jadwal cicilan
            $pinjaman->generateJadwalCicilan();

            // Catat transaksi keuangan (dana keluar)
            TransaksiKeuangan::create([
                'nomor_transaksi' => TransaksiKeuangan::generateNomorTransaksi('keluar'),
                'tanggal_transaksi' => $validated['tanggal_pencairan'],
                'kategori' => 'pengeluaran',
                'keterangan' => 'Pencairan Pinjaman ' . $pinjaman->nomor_pinjaman . ' - ' . $pinjaman->nama_peminjam_lengkap,
                'jumlah' => $pinjaman->jumlah_disetujui,
                'referensi' => $pinjaman->nomor_pinjaman,
                'status' => 'approved',
                'created_by' => auth()->id(),
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'akun_debit_id' => null,
                'akun_kredit_id' => null,
            ]);

            $pinjaman->logHistory('cairkan', $statusLama, 'dicairkan', 'Pinjaman berhasil dicairkan');

            DB::commit();

            return redirect()->route('pinjaman.show', $pinjaman->id)
                ->with('success', 'Pinjaman berhasil dicairkan dan jadwal cicilan telah dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencairkan pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Tunda cicilan
     */
    public function tundaCicilan(Request $request, PinjamanCicilan $cicilan)
    {
        // Validasi: Cek apakah cicilan sudah lunas
        if ($cicilan->status == 'lunas') {
            return redirect()->back()->with('error', '⚠️ Cicilan ke-' . $cicilan->cicilan_ke . ' sudah LUNAS. Tidak dapat ditunda.');
        }

        // Validasi: Cek apakah cicilan sudah pernah ditunda
        if ($cicilan->is_ditunda) {
            return redirect()->back()->with('error', '⚠️ Cicilan ke-' . $cicilan->cicilan_ke . ' sudah pernah ditunda pada ' . $cicilan->tanggal_ditunda->format('d M Y'));
        }

        // Validasi: Cek apakah pinjaman sudah lunas
        if ($cicilan->pinjaman->status == 'lunas') {
            return redirect()->back()->with('error', '✓ Pinjaman sudah LUNAS. Tidak dapat menunda cicilan.');
        }

        $validated = $request->validate([
            'alasan_ditunda' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pinjaman = $cicilan->pinjaman;

            // 1. Tandai cicilan ini sebagai ditunda
            $cicilan->update([
                'is_ditunda' => true,
                'tanggal_ditunda' => now(),
                'ditunda_oleh' => auth()->id(),
                'alasan_ditunda' => $validated['alasan_ditunda'],
                'status' => 'belum_bayar', // Reset status
            ]);

            // 2. Cari cicilan terakhir untuk menentukan tanggal jatuh tempo baru
            $cicilanTerakhir = $pinjaman->cicilan()->orderBy('cicilan_ke', 'desc')->first();
            $nomorCicilanBaru = $cicilanTerakhir->cicilan_ke + 1;

            // 3. Hitung tanggal jatuh tempo baru (1 bulan setelah cicilan terakhir)
            $tanggalJatuhTempoBaru = $cicilanTerakhir->tanggal_jatuh_tempo->copy()->addMonth();
            
            // Gunakan tanggal jatuh tempo yang di-set di pinjaman
            $tanggalJatuhTempoSetiapBulan = $pinjaman->tanggal_jatuh_tempo_setiap_bulan ?? 1;
            $hariTerakhirBulan = $tanggalJatuhTempoBaru->daysInMonth;
            $tanggalJatuhTempoBaru->day(min($tanggalJatuhTempoSetiapBulan, $hariTerakhirBulan));

            // 4. Buat cicilan baru di akhir tenor (hasil dari penundaan)
            $cicilanBaru = PinjamanCicilan::create([
                'pinjaman_id' => $pinjaman->id,
                'cicilan_ke' => $nomorCicilanBaru,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempoBaru,
                'jumlah_pokok' => $cicilan->jumlah_pokok,
                'jumlah_bunga' => 0,
                'jumlah_cicilan' => $cicilan->jumlah_cicilan,
                'sisa_cicilan' => $cicilan->jumlah_cicilan,
                'status' => 'belum_bayar',
                'is_hasil_tunda' => true,
                'cicilan_ditunda_id' => $cicilan->id,
            ]);

            // 5. Update tenor pinjaman (+1 bulan)
            $pinjaman->tenor_bulan = $pinjaman->tenor_bulan + 1;
            $pinjaman->tanggal_jatuh_tempo_terakhir = $tanggalJatuhTempoBaru;
            $pinjaman->save();

            // 6. Log history
            $pinjaman->logHistory(
                'tunda_cicilan',
                null,
                null,
                "Cicilan ke-{$cicilan->cicilan_ke} ditunda. Cicilan baru ke-{$nomorCicilanBaru} ditambahkan pada {$tanggalJatuhTempoBaru->format('d M Y')}",
                [
                    'cicilan_ditunda_ke' => $cicilan->cicilan_ke,
                    'cicilan_baru_ke' => $nomorCicilanBaru,
                    'tanggal_jatuh_tempo_baru' => $tanggalJatuhTempoBaru->format('Y-m-d'),
                    'alasan' => $validated['alasan_ditunda'],
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', "✓ Cicilan ke-{$cicilan->cicilan_ke} berhasil ditunda. Cicilan baru ke-{$nomorCicilanBaru} telah ditambahkan pada tanggal {$tanggalJatuhTempoBaru->format('d M Y')}. Tenor bertambah menjadi {$pinjaman->tenor_bulan} bulan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menunda cicilan: ' . $e->getMessage());
        }
    }

    /**
     * Bayar cicilan
     */
    public function bayarCicilan(Request $request, PinjamanCicilan $cicilan)
    {
        // Validasi: Cek apakah cicilan sudah lunas
        if ($cicilan->status == 'lunas') {
            return redirect()->back()->with('error', '⚠️ Cicilan ke-' . $cicilan->cicilan_ke . ' sudah LUNAS pada tanggal ' . $cicilan->tanggal_bayar->format('d M Y') . '. Tidak dapat melakukan pembayaran lagi.');
        }

        // Validasi: Cek apakah pinjaman sudah lunas
        if ($cicilan->pinjaman->status == 'lunas') {
            return redirect()->back()->with('error', '✓ Pinjaman sudah LUNAS. Semua cicilan sudah terbayar penuh.');
        }

        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:transfer,tunai,potong_gaji',
            'no_referensi' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Upload bukti pembayaran
            $buktiBayar = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiBayar = $request->file('bukti_pembayaran')->store('pinjaman/bukti_bayar', 'public');
            }

            // Proses pembayaran
            $result = $cicilan->prosesPembayaran(
                $validated['jumlah_bayar'],
                $validated['metode_pembayaran'],
                $validated['no_referensi'] ?? null,
                $buktiBayar,
                $validated['keterangan'] ?? null
            );

            // Catat transaksi keuangan (dana masuk)
            TransaksiKeuangan::create([
                'nomor_transaksi' => TransaksiKeuangan::generateNomorTransaksi('masuk'),
                'tanggal_transaksi' => now(),
                'kategori' => 'pemasukan',
                'keterangan' => 'Pembayaran Cicilan ' . $cicilan->pinjaman->nomor_pinjaman . ' - Cicilan ke-' . $cicilan->cicilan_ke,
                'jumlah' => $validated['jumlah_bayar'],
                'referensi' => $cicilan->pinjaman->nomor_pinjaman . '-' . $cicilan->cicilan_ke,
                'status' => 'approved',
                'created_by' => auth()->id(),
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'akun_debit_id' => null,
                'akun_kredit_id' => null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pembayaran cicilan berhasil diproses');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Laporan pinjaman
     */
    public function laporan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $kategori = $request->get('kategori', 'all');

        $query = Pinjaman::with(['karyawan', 'cicilan']);

        if ($bulan != 'all') {
            $query->whereMonth('tanggal_pengajuan', $bulan);
        }

        if ($tahun != 'all') {
            $query->whereYear('tanggal_pengajuan', $tahun);
        }

        if ($kategori != 'all') {
            $query->where('kategori_peminjam', $kategori);
        }

        $pinjaman = $query->get();

        // Statistik laporan
        $stats = [
            'total_pinjaman' => $pinjaman->count(),
            'total_dicairkan' => $pinjaman->whereIn('status', ['dicairkan', 'berjalan', 'lunas'])->sum('jumlah_disetujui'),
            'total_terbayar' => $pinjaman->sum('total_terbayar'),
            'total_sisa' => $pinjaman->whereIn('status', ['dicairkan', 'berjalan'])->sum('sisa_pinjaman'),
            'total_denda' => $pinjaman->sum('denda_keterlambatan'),
        ];

        if ($request->has('download_pdf')) {
            $pdf = PDF::loadView('pinjaman.laporan-pdf', compact('pinjaman', 'stats', 'bulan', 'tahun', 'kategori'));
            return $pdf->download('Laporan_Pinjaman_' . $bulan . '_' . $tahun . '.pdf');
        }

        return view('pinjaman.laporan', compact('pinjaman', 'stats', 'bulan', 'tahun', 'kategori'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pinjaman $pinjaman)
    {
        // Hanya bisa hapus jika status sudah selesai (lunas, ditolak, atau dibatalkan)
        if (!in_array($pinjaman->status, ['lunas', 'ditolak', 'dibatalkan'])) {
            return redirect()->back()->with('error', 'Pinjaman tidak dapat dihapus! Hanya pinjaman dengan status LUNAS, DITOLAK, atau DIBATALKAN yang dapat dihapus untuk menjaga integritas data keuangan.');
        }

        try {
            DB::beginTransaction();

            // Hapus dokumen terkait
            if ($pinjaman->dokumen_ktp) {
                Storage::disk('public')->delete($pinjaman->dokumen_ktp);
            }
            if ($pinjaman->dokumen_slip_gaji) {
                Storage::disk('public')->delete($pinjaman->dokumen_slip_gaji);
            }
            if ($pinjaman->dokumen_pendukung_lain) {
                Storage::disk('public')->delete($pinjaman->dokumen_pendukung_lain);
            }

            $pinjaman->delete();

            DB::commit();

            return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Tambah jumlah pinjaman ke pinjaman yang sudah ada
     * dan merge dengan angsuran sebelumnya
     */
    public function tambahPinjaman(Request $request, Pinjaman $pinjaman)
    {
        // Validasi status pinjaman
        if (!in_array($pinjaman->status, ['dicairkan', 'berjalan'])) {
            return redirect()->back()->with('error', 'Pinjaman dengan status ' . $pinjaman->status . ' tidak dapat ditambah.');
        }

        // Validasi input
        $validated = $request->validate([
            'jumlah_tambahan' => 'required|numeric|min:100000',
            'cicilan_baru' => 'required|numeric|min:10000',
            'tujuan_tambahan' => 'nullable|string|max:500',
        ], [
            'jumlah_tambahan.required' => 'Jumlah tambahan harus diisi',
            'jumlah_tambahan.numeric' => 'Jumlah tambahan harus berupa angka',
            'jumlah_tambahan.min' => 'Jumlah tambahan minimal Rp 100.000',
            'cicilan_baru.required' => 'Cicilan per bulan baru harus diisi',
            'cicilan_baru.numeric' => 'Cicilan per bulan harus berupa angka',
            'cicilan_baru.min' => 'Cicilan per bulan minimal Rp 10.000',
        ]);

        DB::beginTransaction();
        try {
            $jumlahTambahan = $validated['jumlah_tambahan'];
            $cicilanBaru = $validated['cicilan_baru'];
            $tujuanTambahan = $validated['tujuan_tambahan'] ?? null;

            // Simpan nilai lama untuk history
            $totalLama = $pinjaman->total_pinjaman;
            $sisaLama = $pinjaman->sisa_pinjaman;
            $cicilanLama = $pinjaman->cicilan_per_bulan;

            // Update pinjaman dengan nilai baru
            $pinjaman->total_pinjaman = $totalLama + $jumlahTambahan;
            $pinjaman->sisa_pinjaman = $sisaLama + $jumlahTambahan;
            $pinjaman->cicilan_per_bulan = $cicilanBaru;
            
            // Hitung tenor baru
            $tenorBaru = ceil($pinjaman->sisa_pinjaman / $cicilanBaru);
            $pinjaman->tenor = $tenorBaru;

            // Update status jika sebelumnya 'dicairkan', ubah ke 'berjalan'
            if ($pinjaman->status === 'dicairkan') {
                $pinjaman->status = 'berjalan';
            }

            $pinjaman->save();

            // Hapus semua cicilan yang belum dibayar
            PinjamanCicilan::where('pinjaman_id', $pinjaman->id)
                ->where('status', 'belum_bayar')
                ->delete();

            // Hitung ulang dari cicilan yang sudah dibayar
            $cicilanTerbayar = PinjamanCicilan::where('pinjaman_id', $pinjaman->id)
                ->whereIn('status', ['lunas', 'sebagian'])
                ->orderBy('cicilan_ke', 'asc')
                ->get();

            // Hitung total yang sudah dibayar
            $totalTerbayar = $cicilanTerbayar->sum('jumlah_bayar');
            $pinjaman->total_terbayar = $totalTerbayar;
            
            // Hitung sisa yang harus dibayar
            $sisaPinjaman = $pinjaman->total_pinjaman - $totalTerbayar;
            $pinjaman->sisa_pinjaman = $sisaPinjaman;

            // Tentukan cicilan_ke terakhir yang sudah dibayar
            $cicilanKeTerakhir = $cicilanTerbayar->max('cicilan_ke') ?? 0;

            // Generate cicilan baru mulai dari cicilan_ke berikutnya
            $cicilanKeBaru = $cicilanKeTerakhir + 1;
            $sisaBayar = $sisaPinjaman;
            $tanggalJatuhTempo = Carbon::now()->addMonth();

            while ($sisaBayar > 0) {
                $jumlahCicilan = min($cicilanBaru, $sisaBayar);
                
                PinjamanCicilan::create([
                    'pinjaman_id' => $pinjaman->id,
                    'cicilan_ke' => $cicilanKeBaru,
                    'jumlah_pokok' => $jumlahCicilan, // Karena tidak ada bunga, jumlah pokok = jumlah cicilan
                    'jumlah_bunga' => 0, // Tidak ada bunga
                    'jumlah_cicilan' => $jumlahCicilan,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                    'status' => 'belum_bayar',
                    'jumlah_bayar' => 0,
                    'jumlah_dibayar' => 0,
                    'sisa_cicilan' => $jumlahCicilan,
                ]);

                $sisaBayar -= $jumlahCicilan;
                $cicilanKeBaru++;
                $tanggalJatuhTempo = $tanggalJatuhTempo->addMonth();
            }

            // Update tenor sesuai dengan jumlah cicilan aktual
            $totalCicilan = PinjamanCicilan::where('pinjaman_id', $pinjaman->id)->count();
            $pinjaman->tenor = $totalCicilan;
            
            // Hitung persentase pembayaran
            $pinjaman->persentase_pembayaran = ($totalTerbayar / $pinjaman->total_pinjaman) * 100;
            $pinjaman->save();

            // Catat history
            PinjamanHistory::create([
                'pinjaman_id' => $pinjaman->id,
                'user_id' => auth()->id(),
                'aksi' => 'tambah_pinjaman',
                'status_lama' => $pinjaman->status === 'berjalan' ? 'dicairkan' : $pinjaman->status,
                'status_baru' => 'berjalan',
                'keterangan' => sprintf(
                    'Tambah pinjaman sebesar Rp %s. Total pinjaman dari Rp %s menjadi Rp %s. Cicilan dari Rp %s menjadi Rp %s. Tenor baru: %d bulan. Tujuan: %s',
                    number_format($jumlahTambahan, 0, ',', '.'),
                    number_format($totalLama, 0, ',', '.'),
                    number_format($pinjaman->total_pinjaman, 0, ',', '.'),
                    number_format($cicilanLama, 0, ',', '.'),
                    number_format($cicilanBaru, 0, ',', '.'),
                    $tenorBaru,
                    $tujuanTambahan ?? '-'
                ),
            ]);

            DB::commit();

            return redirect()->route('pinjaman.show', $pinjaman->id)
                ->with('success', 'Pinjaman berhasil ditambah sebesar Rp ' . number_format($jumlahTambahan, 0, ',', '.') . '. Angsuran telah digabung dengan angsuran sebelumnya.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambah pinjaman: ' . $e->getMessage());
        }
    }

    /**
     * Download formulir pinjaman resmi dengan kop surat
     */
    public function downloadFormulir(Pinjaman $pinjaman)
    {
        $pinjaman->load(['karyawan', 'pengaju', 'penyetuju', 'cicilan']);

        $pdf = PDF::loadView('pinjaman.formulir-pdf', compact('pinjaman'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Formulir_Pinjaman_' . $pinjaman->nomor_pinjaman . '.pdf');
    }

    /**
     * Download formulir pinjaman kosong untuk diisi manual
     */
    public function downloadFormulirBlank()
    {
        $pdf = PDF::loadView('pinjaman.formulir-blank-pdf')
            ->setPaper('a4', 'portrait');

        return $pdf->download('Formulir_Pengajuan_Pinjaman_Bumi_Sultan.pdf');
    }

    /**
     * Kirim email notifikasi pinjaman jatuh tempo secara manual
     */
    public function kirimEmailManual(Request $request, $id)
    {
        try {
            $pinjaman = Pinjaman::with(['karyawan'])->findOrFail($id);
            
            // Validasi: pastikan ada email tujuan
            $emailTujuan = null;
            if ($pinjaman->kategori_peminjam === 'crew' && $pinjaman->karyawan) {
                $emailTujuan = $pinjaman->karyawan->email;
            } elseif ($pinjaman->kategori_peminjam === 'non_crew') {
                $emailTujuan = $pinjaman->email_peminjam;
            }

            if (!$emailTujuan) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Email tidak tersedia untuk peminjam ini'
                ], 400);
            }

            // Validasi email format
            if (!filter_var($emailTujuan, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Format email tidak valid: ' . $emailTujuan
                ], 400);
            }

            // Tentukan tipe notifikasi berdasarkan request atau default
            $tipeNotifikasi = $request->input('tipe', 'manual');
            $hariSebelum = 0;

            // Jika ada tanggal jatuh tempo, hitung selisih hari
            if ($pinjaman->tanggal_jatuh_tempo_setiap_bulan) {
                $today = Carbon::now();
                $tanggalJT = Carbon::create($today->year, $today->month, $pinjaman->tanggal_jatuh_tempo_setiap_bulan);
                
                if ($tanggalJT < $today) {
                    $tanggalJT->addMonth();
                }
                
                $hariSebelum = $today->diffInDays($tanggalJT, false);
                
                // Tentukan tipe berdasarkan hari
                if ($hariSebelum < 0) {
                    $tipeNotifikasi = 'lewat_jatuh_tempo';
                } elseif ($hariSebelum == 0) {
                    $tipeNotifikasi = 'jatuh_tempo_hari_ini';
                } elseif ($hariSebelum == 1) {
                    $tipeNotifikasi = 'jatuh_tempo_besok';
                } elseif ($hariSebelum <= 3) {
                    $tipeNotifikasi = 'jatuh_tempo_3_hari';
                } elseif ($hariSebelum <= 7) {
                    $tipeNotifikasi = 'jatuh_tempo_7_hari';
                }
            }

            // Kirim email
            Mail::to($emailTujuan)->send(
                new PinjamanJatuhTempoMail($pinjaman, $tipeNotifikasi, $hariSebelum)
            );

            // Simpan log notifikasi
            PinjamanEmailNotification::create([
                'pinjaman_id' => $pinjaman->id,
                'email_tujuan' => $emailTujuan,
                'tipe_notifikasi' => $tipeNotifikasi,
                'tanggal_jatuh_tempo' => Carbon::create(
                    Carbon::now()->year, 
                    Carbon::now()->month, 
                    $pinjaman->tanggal_jatuh_tempo_setiap_bulan ?? 1
                ),
                'status' => 'sent',
                'sent_at' => now(),
                'keterangan' => 'Dikirim manual oleh admin'
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Email berhasil dikirim ke ' . $emailTujuan,
                'email' => $emailTujuan,
                'tipe' => $tipeNotifikasi
            ]);

        } catch (\Exception $e) {
            // Log error
            PinjamanEmailNotification::create([
                'pinjaman_id' => $pinjaman->id ?? $id,
                'email_tujuan' => $emailTujuan ?? 'unknown',
                'tipe_notifikasi' => 'manual',
                'tanggal_jatuh_tempo' => Carbon::now(),
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'keterangan' => 'Gagal kirim manual'
            ]);

            return response()->json([
                'success' => false,
                'message' => '❌ Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }
}
