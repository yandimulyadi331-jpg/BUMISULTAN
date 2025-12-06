<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiRead extends Model
{
    use HasFactory;

    protected $table = 'informasi_reads';

    protected $fillable = [
        'informasi_id',
        'user_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function informasi()
    {
        return $this->belongsTo(Informasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
