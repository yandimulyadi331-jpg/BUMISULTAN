<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakLanjutAdministrasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tindak_lanjut_administrasi';

    protected $fillable = [
        'administrasi_id',
        'kode_tindak_lanjut',
        'jenis_tindak_lanjut',
        'judul_tindak_lanjut',
        'deskripsi_tindak_lanjut',
        'status_tindak_lanjut',
        'nominal_pencairan',
        'metode_pencairan',
        'nomor_rekening',
        'nama_penerima_dana',
        'tanggal_pencairan',
        'bukti_pencairan',
        'tandatangan_pencairan',
        'disposisi_dari',
        'disposisi_kepada',
        'instruksi_disposisi',
        'deadline_disposisi',
        'nama_penerima_paket',
        'foto_paket',
        'waktu_terima_paket',
        'kondisi_paket',
        'resi_pengiriman',
        'waktu_rapat',
        'tempat_rapat',
        'peserta_rapat',
        'hasil_rapat',
        'notulen_rapat',
        'nama_penandatangan',
        'jabatan_penandatangan',
        'tanggal_tandatangan',
        'file_dokumen_ttd',
        'verifikator',
        'tanggal_verifikasi',
        'hasil_verifikasi',
        'catatan_verifikasi',
        'catatan',
        'lampiran',
        'pic_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_pencairan' => 'date',
        'deadline_disposisi' => 'date',
        'waktu_terima_paket' => 'datetime',
        'waktu_rapat' => 'datetime',
        'tanggal_tandatangan' => 'date',
        'tanggal_verifikasi' => 'date',
        'peserta_rapat' => 'array',
        'lampiran' => 'array',
        'nominal_pencairan' => 'decimal:2',
    ];

    // Relasi
    public function administrasi()
    {
        return $this->belongsTo(Administrasi::class);
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Alias untuk creator (backward compatibility)
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper methods
    public function getJenisTindakLanjutLabel()
    {
        $labels = [
            'pencairan_dana' => 'Pencairan Dana',
            'disposisi' => 'Disposisi',
            'konfirmasi_terima' => 'Konfirmasi Terima',
            'konfirmasi_kirim' => 'Konfirmasi Kirim',
            'rapat_pembahasan' => 'Rapat Pembahasan',
            'penerbitan_sk' => 'Penerbitan SK',
            'tandatangan' => 'Penandatanganan',
            'verifikasi' => 'Verifikasi',
            'approval' => 'Approval',
            'revisi' => 'Revisi',
            'arsip' => 'Pengarsipan',
            'lainnya' => 'Lainnya',
        ];

        return $labels[$this->jenis_tindak_lanjut] ?? $this->jenis_tindak_lanjut;
    }

    public function getJenisTindakLanjutIcon()
    {
        $icons = [
            'pencairan_dana' => 'ti-cash',
            'disposisi' => 'ti-share',
            'konfirmasi_terima' => 'ti-checkbox',
            'konfirmasi_kirim' => 'ti-send-check',
            'rapat_pembahasan' => 'ti-users',
            'penerbitan_sk' => 'ti-file-certificate',
            'tandatangan' => 'ti-signature',
            'verifikasi' => 'ti-shield-check',
            'approval' => 'ti-circle-check',
            'revisi' => 'ti-edit',
            'arsip' => 'ti-archive',
            'lainnya' => 'ti-dots',
        ];

        return $icons[$this->jenis_tindak_lanjut] ?? 'ti-file';
    }

    public function getStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning"><i class="ti ti-clock me-1"></i>Pending</span>',
            'proses' => '<span class="badge bg-info"><i class="ti ti-progress me-1"></i>Proses</span>',
            'selesai' => '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Selesai</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Ditolak</span>',
        ];

        return $badges[$this->status_tindak_lanjut] ?? $this->status_tindak_lanjut;
    }

    public static function generateKodeTindakLanjut()
    {
        $lastRecord = self::withTrashed()->latest('id')->first();
        $lastNumber = $lastRecord ? intval(substr($lastRecord->kode_tindak_lanjut, 4)) : 0;
        $newNumber = $lastNumber + 1;
        
        return 'TLJ-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function formatNominalPencairan()
    {
        if ($this->nominal_pencairan) {
            return 'Rp ' . number_format($this->nominal_pencairan, 0, ',', '.');
        }
        return '-';
    }
}
