<?php

namespace App\Services;

use App\Models\JadwalPiket;
use App\Models\JadwalPiketKaryawan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class JadwalPiketService
{
    /**
     * Check if current time is within a jadwal piket
     * @param JadwalPiket $jadwalPiket
     * @param Carbon|null $time
     * @return bool
     */
    public function isInSchedule(JadwalPiket $jadwalPiket, $time = null)
    {
        $time = $time ?: now();
        $jamMulai = $jadwalPiket->jam_mulai->format('H:i:s');
        $jamSelesai = $jadwalPiket->jam_selesai->format('H:i:s');
        $currentTime = $time->format('H:i:s');

        // Handle overnight shifts (e.g., 20:00 - 06:00)
        if ($jamMulai > $jamSelesai) {
            return $currentTime >= $jamMulai || $currentTime < $jamSelesai;
        }

        return $currentTime >= $jamMulai && $currentTime < $jamSelesai;
    }

    /**
     * Get active jadwal piket for a karyawan (based on nik/user)
     * @param string $nik
     * @param Carbon|null $date
     * @return JadwalPiket|null
     */
    public function getActiveScheduleForKaryawan($nik, $date = null)
    {
        $date = $date ?: now();

        $jadwalPiket = JadwalPiketKaryawan::activeOnDate($nik, $date)
            ->with('jadwalPiket')
            ->first();

        if ($jadwalPiket) {
            return $jadwalPiket->jadwalPiket;
        }

        return null;
    }

    /**
     * Get all active jadwal piket for a karyawan
     * @param string $nik
     * @param Carbon|null $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllActiveSchedulesForKaryawan($nik, $date = null)
    {
        $date = $date ?: now();

        $jadwalPikets = JadwalPiketKaryawan::activeOnDate($nik, $date)
            ->with('jadwalPiket')
            ->get()
            ->pluck('jadwalPiket');

        return $jadwalPikets;
    }

    /**
     * Calculate minutes remaining until shift ends
     * @param JadwalPiket $jadwalPiket
     * @param Carbon|null $time
     * @return int|null - null if shift has ended
     */
    public function getMinutesUntilShiftEnd(JadwalPiket $jadwalPiket, $time = null)
    {
        $time = $time ?: now();
        $jamSelesai = $jadwalPiket->jam_selesai->format('H:i:s');
        list($hour, $minute, $second) = explode(':', $jamSelesai);

        $shiftEnd = $time->copy()->setHours($hour)->setMinutes($minute)->setSeconds($second);

        // Jika sudah lewat jam selesai hari ini
        if ($time >= $shiftEnd) {
            return null; // shift sudah selesai
        }

        return $shiftEnd->diffInMinutes($time);
    }

    /**
     * Calculate minutes until shift starts
     * @param JadwalPiket $jadwalPiket
     * @param Carbon|null $time
     * @return int|null - null if shift is currently active
     */
    public function getMinutesUntilShiftStart(JadwalPiket $jadwalPiket, $time = null)
    {
        $time = $time ?: now();

        // Jika sedang dalam shift, return null
        if ($this->isInSchedule($jadwalPiket, $time)) {
            return null;
        }

        $jamMulai = $jadwalPiket->jam_mulai->format('H:i:s');
        list($hour, $minute, $second) = explode(':', $jamMulai);

        $shiftStart = $time->copy()->setHours($hour)->setMinutes($minute)->setSeconds($second);

        // Jika sudah lewat jam mulai hari ini, shift mulai besok
        if ($time >= $shiftStart) {
            $shiftStart->addDay();
        }

        return $shiftStart->diffInMinutes($time);
    }

    /**
     * Check if checklist should be reset for a given schedule
     * @param JadwalPiket $jadwalPiket
     * @param Carbon|null $lastReset
     * @return bool
     */
    public function shouldResetSchedule(JadwalPiket $jadwalPiket, $lastReset = null)
    {
        $now = now();
        $jamSelesai = $jadwalPiket->jam_selesai->format('H:i:s');
        list($hour, $minute, $second) = explode(':', $jamSelesai);

        $shiftEnd = $now->copy()->setHours($hour)->setMinutes($minute)->setSeconds($second);

        // Jika shift belum berakhir hari ini
        if ($now < $shiftEnd) {
            return false;
        }

        // Jika tidak ada lastReset, berarti perlu reset
        if (!$lastReset) {
            return true;
        }

        // Parse lastReset untuk compare
        $lastResetTime = Carbon::parse($lastReset);

        // Reset jika:
        // 1. lastReset before jam_selesai today AND now after jam_selesai today
        // 2. OR lastReset is from yesterday
        $todayShiftEnd = now()->copy()->setHours($hour)->setMinutes($minute)->setSeconds($second);
        $yesterdayShiftEnd = $todayShiftEnd->copy()->subDay();

        return $lastResetTime <= $yesterdayShiftEnd || ($lastResetTime < $todayShiftEnd && $now >= $todayShiftEnd);
    }

    /**
     * Determine validity status of a checklist
     * @param JadwalPiket $jadwalPiket
     * @param Carbon|null $time
     * @return string - 'valid', 'expired', or 'outside_shift'
     */
    public function getValidityStatus(JadwalPiket $jadwalPiket, $time = null)
    {
        $time = $time ?: now();

        if ($this->isInSchedule($jadwalPiket, $time)) {
            return 'valid';
        }

        // Jika shift selesai (expired)
        $jamSelesai = $jadwalPiket->jam_selesai->format('H:i:s');
        list($hour, $minute, $second) = explode(':', $jamSelesai);
        $shiftEnd = $time->copy()->setHours($hour)->setMinutes($minute)->setSeconds($second);

        if ($time >= $shiftEnd) {
            return 'expired';
        }

        // Jika belum mulai
        return 'outside_shift';
    }

    /**
     * Format jadwal piket info for response
     * @param JadwalPiket $jadwalPiket
     * @return array
     */
    public function formatJadwalPiketInfo(JadwalPiket $jadwalPiket)
    {
        $isActive = $this->isInSchedule($jadwalPiket);
        $now = now();

        return [
            'id' => $jadwalPiket->id,
            'nama' => $jadwalPiket->nama_piket,
            'jam_mulai' => $jadwalPiket->jam_mulai->format('H:i'),
            'jam_selesai' => $jadwalPiket->jam_selesai->format('H:i'),
            'hari' => $jadwalPiket->hari,
            'deskripsi' => $jadwalPiket->deskripsi,
            'is_active' => $isActive,
            'status' => $isActive ? 'AKTIF' : ($this->getMinutesUntilShiftStart($jadwalPiket) === null ? 'TERTUTUP (SELESAI)' : 'TERTUTUP (BELUM MULAI)'),
            'waktu_tersisa_menit' => $this->getMinutesUntilShiftEnd($jadwalPiket),
            'waktu_dimulai_menit' => $this->getMinutesUntilShiftStart($jadwalPiket)
        ];
    }

    /**
     * Log jadwal piket activity
     */
    public function logActivity($message, $data = [])
    {
        Log::info('JadwalPiketService: ' . $message, $data);
    }
}
