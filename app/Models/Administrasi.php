<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'administrasi';

    protected $fillable = [
        'kode_administrasi',
        'jenis_administrasi',
        'nomor_surat',
        'pengirim',
        'penerima',
        'perihal',
        'nama_acara',
        'tanggal_acara_mulai',
        'tanggal_acara_selesai',
        'waktu_acara_mulai',
        'waktu_acara_selesai',
        'lokasi_acara',
        'alamat_acara',
        'dress_code',
        'catatan_acara',
        'ringkasan',
        'tanggal_surat',
        'tanggal_terima',
        'tanggal_kirim',
        'prioritas',
        'status',
        'file_dokumen',
        'foto',
        'lampiran',
        'divisi_id',
        'cabang_id',
        'disposisi_ke',
        'catatan',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'datetime',
        'tanggal_kirim' => 'datetime',
        'tanggal_acara_mulai' => 'date',
        'tanggal_acara_selesai' => 'date',
        'lampiran' => 'array',
    ];

    // Relasi
    public function tindakLanjut()
    {
        return $this->hasMany(TindakLanjutAdministrasi::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    // Helper methods
    public function getJenisAdministrasiLabel()
    {
        $labels = [
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'undangan_masuk' => 'Undangan Masuk',
            'undangan_keluar' => 'Undangan Keluar',
            'proposal_masuk' => 'Proposal Masuk',
            'proposal_keluar' => 'Proposal Keluar',
            'paket_masuk' => 'Paket Masuk',
            'paket_keluar' => 'Paket Keluar',
            'memo_internal' => 'Memo Internal',
            'sk_internal' => 'SK Internal',
            'surat_tugas' => 'Surat Tugas',
            'surat_keputusan' => 'Surat Keputusan',
            'nota_dinas' => 'Nota Dinas',
            'berita_acara' => 'Berita Acara',
            'kontrak' => 'Kontrak',
            'mou' => 'MoU',
            'dokumen_lainnya' => 'Dokumen Lainnya',
        ];

        return $labels[$this->jenis_administrasi] ?? $this->jenis_administrasi;
    }

    public function getJenisAdministrasiIcon()
    {
        $icons = [
            'surat_masuk' => 'ti-mail-opened',
            'surat_keluar' => 'ti-send',
            'undangan_masuk' => 'ti-mail-heart',
            'undangan_keluar' => 'ti-mail-forward',
            'proposal_masuk' => 'ti-file-invoice',
            'proposal_keluar' => 'ti-file-export',
            'paket_masuk' => 'ti-package',
            'paket_keluar' => 'ti-package-export',
            'memo_internal' => 'ti-memo',
            'sk_internal' => 'ti-file-certificate',
            'surat_tugas' => 'ti-clipboard-text',
            'surat_keputusan' => 'ti-gavel',
            'nota_dinas' => 'ti-note',
            'berita_acara' => 'ti-file-text',
            'kontrak' => 'ti-file-contract',
            'mou' => 'ti-handshake',
            'dokumen_lainnya' => 'ti-files',
        ];

        return $icons[$this->jenis_administrasi] ?? 'ti-file';
    }

    public function getJenisAdministrasiColor()
    {
        $colors = [
            'surat_masuk' => 'primary',
            'surat_keluar' => 'info',
            'undangan_masuk' => 'warning',
            'undangan_keluar' => 'orange',
            'proposal_masuk' => 'success',
            'proposal_keluar' => 'teal',
            'paket_masuk' => 'purple',
            'paket_keluar' => 'pink',
            'memo_internal' => 'secondary',
            'sk_internal' => 'dark',
            'surat_tugas' => 'cyan',
            'surat_keputusan' => 'indigo',
            'nota_dinas' => 'lime',
            'berita_acara' => 'amber',
            'kontrak' => 'red',
            'mou' => 'green',
            'dokumen_lainnya' => 'gray',
        ];

        return $colors[$this->jenis_administrasi] ?? 'secondary';
    }

    public function getStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning"><i class="ti ti-clock me-1"></i>Pending</span>',
            'proses' => '<span class="badge bg-info"><i class="ti ti-progress me-1"></i>Proses</span>',
            'selesai' => '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Selesai</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Ditolak</span>',
            'expired' => '<span class="badge bg-dark"><i class="ti ti-clock-off me-1"></i>Expired</span>',
        ];

        return $badges[$this->status] ?? $this->status;
    }

    public function getPrioritasBadge()
    {
        $badges = [
            'rendah' => '<span class="badge bg-secondary">Rendah</span>',
            'normal' => '<span class="badge bg-primary">Normal</span>',
            'tinggi' => '<span class="badge bg-warning">Tinggi</span>',
            'urgent' => '<span class="badge bg-danger blink">URGENT</span>',
        ];

        return $badges[$this->prioritas] ?? $this->prioritas;
    }

    public static function generateKodeAdministrasi()
    {
        $lastRecord = self::withTrashed()->latest('id')->first();
        $lastNumber = $lastRecord ? intval(substr($lastRecord->kode_administrasi, 4)) : 0;
        $newNumber = $lastNumber + 1;
        
        return 'ADM-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function isMasuk()
    {
        return in_array($this->jenis_administrasi, [
            'surat_masuk',
            'undangan_masuk',
            'proposal_masuk',
            'paket_masuk'
        ]);
    }

    public function isKeluar()
    {
        return in_array($this->jenis_administrasi, [
            'surat_keluar',
            'undangan_keluar',
            'proposal_keluar',
            'paket_keluar'
        ]);
    }
}
