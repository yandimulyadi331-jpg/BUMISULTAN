<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_dokumen',
        'nama_dokumen',
        'document_category_id',
        'deskripsi',
        'nomor_loker',
        'lokasi_loker',
        'rak',
        'baris',
        'jenis_dokumen',
        'jenis_file',
        'file_path',
        'file_size',
        'file_extension',
        'access_level',
        'tanggal_dokumen',
        'tanggal_berlaku',
        'tanggal_berakhir',
        'nomor_referensi',
        'penerbit',
        'tags',
        'status',
        'jumlah_view',
        'jumlah_download',
        'uploaded_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'tanggal_berlaku' => 'date',
        'tanggal_berakhir' => 'date',
        'jumlah_view' => 'integer',
        'jumlah_download' => 'integer',
    ];

    /**
     * Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    /**
     * Relasi ke access logs
     */
    public function accessLogs()
    {
        return $this->hasMany(DocumentAccessLog::class);
    }

    /**
     * Relasi ke user yang upload
     */
    public function uploader()
    {
        return $this->belongsTo(Karyawan::class, 'uploaded_by', 'nik');
    }

    /**
     * Relasi ke user yang update
     */
    public function updater()
    {
        return $this->belongsTo(Karyawan::class, 'updated_by', 'nik');
    }

    /**
     * Generate kode dokumen otomatis
     * Format: [KATEGORI]-[NOMOR]-[LOKER]
     * Contoh: SK-001-L001
     */
    public static function generateKodeDokumen($categoryId, $nomorLoker = null)
    {
        $category = DocumentCategory::findOrFail($categoryId);
        $nextNumber = $category->getNextNumber();
        
        // Format nomor loker
        $lokerCode = $nomorLoker ? $nomorLoker : 'L000';
        
        return $category->kode_kategori . '-' . $nextNumber . '-' . $lokerCode;
    }

    /**
     * Check apakah user bisa download dokumen
     */
    public function canDownload($user = null)
    {
        $user = $user ?? Auth::user();
        
        // Admin bisa download semua
        if ($user->hasRole('super admin')) {
            return true;
        }

        // Cek access level
        if ($this->access_level === 'public') {
            return true;
        }

        if ($this->access_level === 'restricted') {
            return false;
        }

        // view_only tidak bisa download
        return false;
    }

    /**
     * Check apakah user bisa view dokumen
     */
    public function canView($user = null)
    {
        $user = $user ?? Auth::user();
        
        // Admin bisa view semua
        if ($user->hasRole('super admin')) {
            return true;
        }

        // Restricted hanya admin
        if ($this->access_level === 'restricted') {
            return false;
        }

        // Public dan view_only bisa dilihat
        return true;
    }

    /**
     * Increment view count
     */
    public function incrementView()
    {
        $this->increment('jumlah_view');
    }

    /**
     * Increment download count
     */
    public function incrementDownload()
    {
        $this->increment('jumlah_download');
    }

    /**
     * Get full file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->jenis_dokumen === 'link') {
            return $this->file_path;
        }

        if ($this->file_path) {
            return Storage::url($this->file_path);
        }

        return null;
    }

    /**
     * Get badge class based on access level
     */
    public function getAccessBadgeAttribute()
    {
        $badges = [
            'public' => 'badge bg-success',
            'view_only' => 'badge bg-warning',
            'restricted' => 'badge bg-danger',
        ];

        return $badges[$this->access_level] ?? 'badge bg-secondary';
    }

    /**
     * Get access level text
     */
    public function getAccessLevelTextAttribute()
    {
        $texts = [
            'public' => 'Publik (View & Download)',
            'view_only' => 'Hanya Lihat',
            'restricted' => 'Terbatas (Admin)',
        ];

        return $texts[$this->access_level] ?? $this->access_level;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'aktif' => 'badge bg-success',
            'arsip' => 'badge bg-secondary',
            'kadaluarsa' => 'badge bg-danger',
        ];

        return $badges[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('kode_dokumen', 'like', "%{$search}%")
              ->orWhere('nama_dokumen', 'like', "%{$search}%")
              ->orWhere('nomor_loker', 'like', "%{$search}%")
              ->orWhere('nomor_referensi', 'like', "%{$search}%")
              ->orWhere('tags', 'like', "%{$search}%");
        });
    }

    /**
     * Scope untuk filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('document_category_id', $categoryId);
        }
        return $query;
    }

    /**
     * Scope untuk filter by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope untuk filter by access level
     */
    public function scopeByAccessLevel($query, $accessLevel)
    {
        if ($accessLevel) {
            return $query->where('access_level', $accessLevel);
        }
        return $query;
    }

    /**
     * Check if document is expired
     */
    public function isExpired()
    {
        if ($this->tanggal_berakhir) {
            return $this->tanggal_berakhir->isPast();
        }
        return false;
    }

    /**
     * Get file icon based on extension
     */
    public function getFileIconAttribute()
    {
        if ($this->jenis_dokumen === 'link') {
            return 'ti ti-link';
        }

        $icons = [
            'pdf' => 'ti ti-file-type-pdf text-danger',
            'doc' => 'ti ti-file-type-doc text-primary',
            'docx' => 'ti ti-file-type-docx text-primary',
            'xls' => 'ti ti-file-type-xls text-success',
            'xlsx' => 'ti ti-file-type-xlsx text-success',
            'jpg' => 'ti ti-photo text-info',
            'jpeg' => 'ti ti-photo text-info',
            'png' => 'ti ti-photo text-info',
            'gif' => 'ti ti-photo text-info',
            'zip' => 'ti ti-file-zip text-warning',
            'rar' => 'ti ti-file-zip text-warning',
        ];

        return $icons[$this->file_extension] ?? 'ti ti-file text-secondary';
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->uploaded_by = Auth::user()->nik;
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::user()->nik;
            }
        });

        // Auto update status jika kadaluarsa
        static::saving(function ($model) {
            if ($model->isExpired() && $model->status === 'aktif') {
                $model->status = 'kadaluarsa';
            }
        });

        // Hapus file saat delete
        static::deleting(function ($model) {
            if ($model->jenis_dokumen === 'file' && $model->file_path) {
                Storage::delete($model->file_path);
            }
        });
    }
}
