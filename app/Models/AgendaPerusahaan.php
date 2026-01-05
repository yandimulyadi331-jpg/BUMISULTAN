<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaPerusahaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agenda_perusahaan';

    protected $fillable = [
        'nomor_agenda',
        'judul',
        'deskripsi',
        'tipe_agenda',
        'kategori_agenda',
        'tanggal_mulai',
        'waktu_mulai',
        'tanggal_selesai',
        'waktu_selesai',
        'durasi_menit',
        'lokasi',
        'lokasi_detail',
        'is_online',
        'link_meeting',
        'penyelenggara',
        'contact_person',
        'no_telp_cp',
        'email_cp',
        'dress_code',
        'dress_code_keterangan',
        'perlengkapan_dibawa',
        'peserta_internal',
        'peserta_eksternal',
        'jumlah_peserta_estimasi',
        'ada_anggaran',
        'nominal_anggaran',
        'sumber_anggaran',
        'dokumen_undangan',
        'dokumen_rundown',
        'dokumen_materi',
        'dokumen_lainnya',
        'status',
        'prioritas',
        'is_wajib_hadir',
        'reminder_aktif',
        'reminder_1_hari',
        'reminder_3_jam',
        'reminder_30_menit',
        'reminder_custom_menit',
        'kehadiran_konfirmasi',
        'nama_perwakilan',
        'catatan_kehadiran',
        'hasil_agenda',
        'tindak_lanjut',
        'foto_dokumentasi',
        'dibuat_oleh',
        'diupdate_oleh',
        'dibatalkan_oleh',
        'tanggal_dibatalkan',
        'alasan_dibatalkan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_dibatalkan' => 'datetime',
        'is_online' => 'boolean',
        'ada_anggaran' => 'boolean',
        'is_wajib_hadir' => 'boolean',
        'reminder_aktif' => 'boolean',
        'reminder_1_hari' => 'boolean',
        'reminder_3_jam' => 'boolean',
        'reminder_30_menit' => 'boolean',
        'nominal_anggaran' => 'decimal:2',
        'peserta_internal' => 'array',
        'dokumen_lainnya' => 'array',
        'foto_dokumentasi' => 'array',
    ];

    /**
     * Relasi ke User pembuat
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke User yang update
     */
    public function pengupdate()
    {
        return $this->belongsTo(User::class, 'diupdate_oleh');
    }

    /**
     * Relasi ke User yang membatalkan
     */
    public function pembatal()
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }

    /**
     * Relasi ke reminder log
     */
    public function reminderLogs()
    {
        return $this->hasMany(AgendaReminderLog::class, 'agenda_id');
    }

    /**
     * Relasi ke history
     */
    public function history()
    {
        return $this->hasMany(AgendaHistory::class, 'agenda_id');
    }

    /**
     * Scope untuk filter berdasarkan tipe
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe_agenda', $tipe);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk agenda mendatang
     */
    public function scopeMendatang($query)
    {
        return $query->where('tanggal_mulai', '>=', now()->toDateString())
                     ->whereNotIn('status', ['selesai', 'dibatalkan'])
                     ->orderBy('tanggal_mulai')
                     ->orderBy('waktu_mulai');
    }

    /**
     * Scope untuk agenda hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_mulai', now()->toDateString())
                     ->whereNotIn('status', ['dibatalkan']);
    }

    /**
     * Scope untuk agenda minggu ini
     */
    public function scopeMingguIni($query)
    {
        return $query->whereBetween('tanggal_mulai', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->whereNotIn('status', ['dibatalkan']);
    }

    /**
     * Generate nomor agenda otomatis
     */
    public static function generateNomorAgenda()
    {
        $prefix = 'AGD-' . date('Ym') . '-';
        
        $lastAgenda = self::withTrashed()
            ->where('nomor_agenda', 'like', $prefix . '%')
            ->orderBy('nomor_agenda', 'desc')
            ->first();

        if ($lastAgenda) {
            $lastNumber = (int) substr($lastAgenda->nomor_agenda, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Log history perubahan
     */
    public function logHistory($aksi, $perubahan = null, $catatan = null)
    {
        AgendaHistory::create([
            'agenda_id' => $this->id,
            'aksi' => $aksi,
            'perubahan' => $perubahan ? json_encode($perubahan) : null,
            'catatan' => $catatan,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'System',
        ]);
    }

    /**
     * Get waktu agenda lengkap (tanggal + jam)
     */
    public function getWaktuLengkapAttribute()
    {
        $start = $this->tanggal_mulai->format('d M Y') . ' ' . substr($this->waktu_mulai, 0, 5);
        
        if ($this->tanggal_selesai) {
            $end = $this->tanggal_selesai->format('d M Y') . ' ' . substr($this->waktu_selesai, 0, 5);
            return $start . ' - ' . $end;
        }
        
        if ($this->waktu_selesai) {
            return $start . ' - ' . substr($this->waktu_selesai, 0, 5) . ' WIB';
        }
        
        return $start . ' WIB';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'terjadwal' => 'primary',
            'berlangsung' => 'success',
            'selesai' => 'info',
            'dibatalkan' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get prioritas badge color
     */
    public function getPrioritasBadgeAttribute()
    {
        return match($this->prioritas) {
            'rendah' => 'secondary',
            'sedang' => 'primary',
            'tinggi' => 'warning',
            'urgent' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get icon dress code
     */
    public function getDressCodeIconAttribute()
    {
        return match($this->dress_code) {
            'formal' => '👔',
            'semi_formal' => '👕',
            'batik' => '👘',
            'casual' => '👕',
            'bebas_rapi' => '👔',
            'khusus' => '🎭',
            default => '👔'
        };
    }

    /**
     * Cek apakah agenda sudah lewat
     */
    public function isSudahLewat()
    {
        return $this->tanggal_mulai < now()->toDateString();
    }

    /**
     * Hitung countdown (dalam menit)
     */
    public function getCountdownMenitAttribute()
    {
        $waktuAgenda = \Carbon\Carbon::parse($this->tanggal_mulai->format('Y-m-d') . ' ' . $this->waktu_mulai);
        $sekarang = now();
        
        if ($waktuAgenda < $sekarang) {
            return 0;
        }
        
        return $sekarang->diffInMinutes($waktuAgenda);
    }
}
