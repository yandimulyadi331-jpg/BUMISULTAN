<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tukang extends Model
{
    use HasFactory;
    
    protected $table = "tukangs";
    protected $primaryKey = "id";
    
    protected $fillable = [
        'kode_tukang',
        'nama_tukang',
        'nik',
        'alamat',
        'no_hp',
        'email',
        'keahlian',
        'status',
        'auto_potong_pinjaman',
        'tarif_harian',
        'keterangan',
        'foto'
    ];
    
    protected $casts = [
        'tarif_harian' => 'decimal:2',
        'auto_potong_pinjaman' => 'boolean',
    ];
    
    /**
     * Relasi ke kehadiran tukang
     */
    public function kehadiran()
    {
        return $this->hasMany(KehadiranTukang::class, 'tukang_id');
    }
    
    /**
     * Kehadiran bulan ini
     */
    public function kehadiranBulanIni()
    {
        return $this->hasMany(KehadiranTukang::class, 'tukang_id')
                    ->whereYear('tanggal', date('Y'))
                    ->whereMonth('tanggal', date('m'));
    }
    
    /**
     * Relasi ke keuangan tukang
     */
    public function keuangan()
    {
        return $this->hasMany(KeuanganTukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke pinjaman tukang
     */
    public function pinjaman()
    {
        return $this->hasMany(PinjamanTukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke pinjaman tukang (alias plural)
     */
    public function pinjamans()
    {
        return $this->hasMany(PinjamanTukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke pinjaman aktif
     */
    public function pinjamanAktif()
    {
        return $this->hasMany(PinjamanTukang::class, 'tukang_id')->aktif();
    }
    
    /**
     * Relasi ke potongan tukang
     */
    public function potongan()
    {
        return $this->hasMany(PotonganTukang::class, 'tukang_id');
    }

    /**
     * Relasi ke riwayat potongan pinjaman per-minggu
     * (NEW: 29 Januari 2026)
     */
    public function riwayatPotonganPinjaman()
    {
        return $this->hasMany(PotonganPinjamanPayrollDetail::class, 'tukang_id');
    }

    /**
     * Method: Dapatkan status potongan untuk minggu tertentu (ISO 8601)
     * 
     * Contoh: $tukang->getStatusPotonganMinggu(2026, 5)
     * Return: 'DIPOTONG', 'TIDAK_DIPOTONG', atau 'TIDAK_TERCATAT'
     */
    public function getStatusPotonganMinggu($tahun, $minggu)
    {
        $record = $this->riwayatPotonganPinjaman()
                       ->where('tahun', $tahun)
                       ->where('minggu', $minggu)
                       ->first();

        return $record ? $record->status_potong : 'TIDAK_TERCATAT';
    }

    /**
     * Method: Dapatkan nominal cicilan untuk minggu tertentu
     * 
     * Return: 
     * - Jika status DIPOTONG: return nominal_cicilan
     * - Jika status TIDAK_DIPOTONG: return 0
     * - Jika tidak tercatat: return 0
     */
    public function getNominalCicilanMinggu($tahun, $minggu)
    {
        $record = $this->riwayatPotonganPinjaman()
                       ->where('tahun', $tahun)
                       ->where('minggu', $minggu)
                       ->first();

        if (!$record) {
            return 0;
        }

        if ($record->status_potong === 'TIDAK_DIPOTONG') {
            return 0;
        }

        return $record->nominal_cicilan ?? 0;
    }

    /**
     * Method: Dapatkan detail potongan minggu tertentu
     */
    public function getDetailPotonganMinggu($tahun, $minggu)
    {
        return $this->riwayatPotonganPinjaman()
                    ->where('tahun', $tahun)
                    ->where('minggu', $minggu)
                    ->first();
    }

    /**
     * Method: Dapatkan riwayat potongan dalam range bulan tertentu
     */
    public function getRiwayatPotonganBulan($tahun, $bulan)
    {
        // Dapatkan semua minggu dalam bulan tertentu
        $startDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
        $endDate = $startDate->copy()->endOfMonth();

        return $this->riwayatPotonganPinjaman()
                    ->where('tahun', $tahun)
                    ->whereBetween('tanggal_mulai', [$startDate, $endDate])
                    ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                    ->orderBy('minggu')
                    ->get();
    }

    /**
     * Method: Hitung total nominal cicilan yang dipotong dalam range bulan
     */
    public function getTotalCicilanDipotongBulan($tahun, $bulan)
    {
        $riwayat = $this->getRiwayatPotonganBulan($tahun, $bulan);
        return $riwayat->where('status_potong', 'DIPOTONG')->sum('nominal_cicilan');
    }

    /**
     * Method: Hitung total minggu yang tidak dipotong dalam range bulan
     */
    public function getJumlahMingguTidakDipotongBulan($tahun, $bulan)
    {
        $riwayat = $this->getRiwayatPotonganBulan($tahun, $bulan);
        return $riwayat->where('status_potong', 'TIDAK_DIPOTONG')->count();
    }

    /**
     * Method: Record riwayat potongan pinjaman ketika toggle diubah
     * 
     * Digunakan saat user mengklik toggle di halaman detail pinjaman
     */
    public function recordRiwayatPotonganPinjaman($tahun, $minggu, $status, $toggleBy, $alasan = null, $catatan = null)
    {
        // Hitung range tanggal untuk minggu tersebut (ISO 8601)
        $dateTime = new \DateTime();
        $dateTime->setISODate($tahun, $minggu, 1); // 1 = Senin
        $tanggal_mulai = $dateTime->format('Y-m-d');

        $dateTime->modify('+6 days'); // +6 hari = Minggu
        $tanggal_selesai = $dateTime->format('Y-m-d');

        // Ambil pinjaman aktif untuk tukang ini
        $pinjamanAktif = $this->pinjamanAktif()->first();
        $nominalCicilan = $pinjamanAktif ? $pinjamanAktif->cicilan_per_minggu : 0;

        // Update atau create record
        return PotonganPinjamanPayrollDetail::updateOrCreate(
            [
                'tukang_id' => $this->id,
                'tahun' => $tahun,
                'minggu' => $minggu,
            ],
            [
                'pinjaman_tukang_id' => $pinjamanAktif ? $pinjamanAktif->id : null,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'status_potong' => $status,
                'nominal_cicilan' => $nominalCicilan,
                'alasan_tidak_potong' => $alasan,
                'toggle_by' => $toggleBy,
                'toggle_at' => now(),
                'catatan' => $catatan,
            ]
        );
    }

    /**
     * Method: Auto-record semua minggu pinjaman aktif untuk bulan tertentu
     * 
     * Digunakan saat pinjaman baru dibuat atau untuk backfill data historis
     */
    public function autoRecordPotonganBulan($tahun, $bulan)
    {
        $pinjamanAktif = $this->pinjamanAktif()->get();

        if ($pinjamanAktif->isEmpty()) {
            return null;
        }

        // Dapatkan semua minggu dalam bulan
        $startDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $mingguList = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $isoWeek = $current->isoFormat('W');
            $mingguList[$isoWeek] = [
                'tahun' => $current->isoFormat('Y'),
                'minggu' => (int) $isoWeek,
                'tanggal_mulai' => $current->startOfWeek()->format('Y-m-d'),
                'tanggal_selesai' => $current->endOfWeek()->format('Y-m-d'),
            ];
            $current->addWeek();
        }

        // Record untuk setiap pinjaman dan setiap minggu
        foreach ($pinjamanAktif as $pinjaman) {
            foreach ($mingguList as $minggu => $info) {
                // Cek apakah sudah ada record
                $exists = PotonganPinjamanPayrollDetail::where('tukang_id', $this->id)
                                                       ->where('tahun', $info['tahun'])
                                                       ->where('minggu', $info['minggu'])
                                                       ->exists();

                if (!$exists) {
                    PotonganPinjamanPayrollDetail::create([
                        'tukang_id' => $this->id,
                        'pinjaman_tukang_id' => $pinjaman->id,
                        'tahun' => $info['tahun'],
                        'minggu' => $info['minggu'],
                        'tanggal_mulai' => $info['tanggal_mulai'],
                        'tanggal_selesai' => $info['tanggal_selesai'],
                        'status_potong' => 'DIPOTONG',
                        'nominal_cicilan' => $pinjaman->cicilan_per_minggu,
                        'toggle_by' => 'System',
                        'toggle_at' => now(),
                    ]);
                }
            }
        }

        return true;
    }
}
