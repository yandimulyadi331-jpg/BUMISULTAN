<?php

namespace App\Services;

use App\Models\PotonganPinjamanDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PotonganPinjamanService
{
    /**
     * Auto-process potongan pinjaman yang sudah jatuh tempo
     * Dipanggil saat generate slip gaji atau laporan gaji
     */
    public static function processPendingPotongan($bulan, $tahun, $tanggalProses = null)
    {
        $tanggalProses = $tanggalProses ?? now();
        
        // Ambil semua detail yang:
        // 1. Status = 'pending'
        // 2. Bulan dan tahun sesuai
        // 3. Tanggal jatuh tempo <= tanggal proses
        $details = PotonganPinjamanDetail::with('master')
            ->where('status', 'pending')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('tanggal_jatuh_tempo', '<=', $tanggalProses)
            ->whereHas('master', function($q) {
                $q->where('status', 'aktif');
            })
            ->get();
        
        $processed = 0;
        
        DB::beginTransaction();
        try {
            foreach ($details as $detail) {
                // Mark sebagai dipotong
                $detail->status = 'dipotong';
                $detail->tanggal_dipotong = $tanggalProses;
                $detail->diproses_oleh = auth()->id();
                $detail->save();
                
                // Update master progress
                $detail->master->updateProgress();
                
                $processed++;
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'processed' => $processed,
                'message' => "Berhasil memproses {$processed} potongan pinjaman"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'processed' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get total potongan pinjaman untuk slip gaji per karyawan
     */
    public static function getTotalPotonganForSlip($nik, $bulan, $tahun)
    {
        return PotonganPinjamanDetail::where('nik', $nik)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 'dipotong')
            ->sum('jumlah_potongan');
    }
    
    /**
     * Get detail potongan pinjaman untuk slip gaji per karyawan
     */
    public static function getDetailPotonganForSlip($nik, $bulan, $tahun)
    {
        return PotonganPinjamanDetail::with('master')
            ->join('potongan_pinjaman_master', 'potongan_pinjaman_detail.master_id', '=', 'potongan_pinjaman_master.id')
            ->where('potongan_pinjaman_master.nik', $nik)
            ->where('potongan_pinjaman_detail.bulan', $bulan)
            ->where('potongan_pinjaman_detail.tahun', $tahun)
            ->where('potongan_pinjaman_detail.status', 'dipotong')
            ->select(
                'potongan_pinjaman_detail.*',
                'potongan_pinjaman_master.kode_potongan',
                'potongan_pinjaman_master.keterangan as keterangan_master'
            )
            ->get();
    }
}
