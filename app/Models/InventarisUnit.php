<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaris_id',
        'batch_code',
        'tanggal_perolehan',
        'supplier',
        'harga_perolehan_per_unit',
        'jumlah_unit_dalam_batch',
        'lokasi_penyimpanan',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'harga_perolehan_per_unit' => 'decimal:2',
    ];

    // Auto generate batch code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->batch_code)) {
                $model->batch_code = self::generateBatchCode($model->inventaris_id);
            }
        });
    }

    public static function generateBatchCode($inventarisId)
    {
        $lastBatch = self::where('inventaris_id', $inventarisId)
                        ->latest('id')
                        ->first();
        
        $number = $lastBatch ? intval(substr($lastBatch->batch_code, 6)) + 1 : 1;
        
        return 'BATCH-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function detailUnits()
    {
        return $this->hasMany(InventarisDetailUnit::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
