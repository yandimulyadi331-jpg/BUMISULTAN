<?php

namespace App\Observers;

use App\Models\PresensiYayasan;

class PresensiYayasanObserver
{
    /**
     * Handle the PresensiYayasan "created" event.
     * 
     * Ketika ada record presensi baru dibuat, increment jumlah_kehadiran
     * di tabel yayasan_masar (hanya 1x per hari per karyawan)
     */
    public function created(PresensiYayasan $presensi)
    {
        // Cari Yayasan terkait
        $yayasan = $presensi->yayasan;
        
        if ($yayasan && $presensi->jam_in) {
            // Cek apakah sudah ada record untuk tanggal ini
            $hasRecord = PresensiYayasan::where('kode_yayasan', $presensi->kode_yayasan)
                ->whereDate('tanggal', $presensi->tanggal)
                ->count();
            
            // Jika ini adalah record pertama untuk hari ini, increment
            if ($hasRecord == 1) {
                $yayasan->increment('jumlah_kehadiran');
            }
        }
    }

    /**
     * Handle the PresensiYayasan "updated" event.
     * 
     * Jika jam_in diupdate dari null ke ada nilai, increment
     */
    public function updated(PresensiYayasan $presensi)
    {
        // Jika jam_in baru saja di-set
        if ($presensi->wasChanged('jam_in') && $presensi->jam_in && !$presensi->getOriginal('jam_in')) {
            // Cek apakah sudah ada record untuk tanggal ini dengan jam_in
            $hasRecordWithJamIn = PresensiYayasan::where('kode_yayasan', $presensi->kode_yayasan)
                ->whereDate('tanggal', $presensi->tanggal)
                ->whereNotNull('jam_in')
                ->count();
            
            // Jika ini adalah record pertama dengan jam_in untuk hari ini, increment
            if ($hasRecordWithJamIn == 1) {
                $yayasan = $presensi->yayasan;
                if ($yayasan) {
                    $yayasan->increment('jumlah_kehadiran');
                }
            }
        }
    }

    /**
     * Handle the PresensiYayasan "restored" event.
     */
    public function restored(PresensiYayasan $presensi)
    {
        //
    }

    /**
     * Handle the PresensiYayasan "force deleted" event.
     */
    public function forceDeleted(PresensiYayasan $presensi)
    {
        //
    }
}

