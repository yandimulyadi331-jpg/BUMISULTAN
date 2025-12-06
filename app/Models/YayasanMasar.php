<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class YayasanMasar extends Model
{
    use HasFactory;
    protected $table = "yayasan_masar";
    protected $primaryKey = "kode_yayasan";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'kode_cabang_array' => 'array',
    ];

    function getRekapstatusYayasanMasar($request = null)
    {
        $query = YayasanMasar::query();
        $query->select(
            DB::raw("SUM(IF(status = 'K', 1, 0)) as jml_kontrak"),
            DB::raw("SUM(IF(status = 'T', 1, 0)) as jml_tetap"),
            DB::raw("SUM(IF(status = 'O', 1, 0)) as jml_outsourcing"),
            DB::raw("SUM(IF(status_aktif = '1', 1, 0)) as jml_aktif"),
        );
        if (!empty($request->kode_cabang)) {
            $query->where('yayasan_masar.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('yayasan_masar.kode_dept', $request->kode_dept);
        }
        return $query->first();
    }

    // Relasi dengan Facerecognition (jika diperlukan)
    public function facerecognition()
    {
        return $this->hasMany(Facerecognition::class, 'kode_yayasan', 'kode_yayasan');
    }

    // Relasi dengan Departemen
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'kode_dept', 'kode_dept');
    }

    // Relasi dengan Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kode_jabatan', 'kode_jabatan');
    }

    // Relasi dengan Cabang
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    // Relasi dengan PresensiYayasan
    public function presensi()
    {
        return $this->hasMany(PresensiYayasan::class, 'kode_yayasan', 'kode_yayasan');
    }
}
