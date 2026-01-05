<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AgendaPerusahaan;
use App\Models\AgendaReminderLog;
use App\Models\User;
use Carbon\Carbon;

class AgendaReminderCommand extends Command
{
    protected $signature = 'agenda:reminder {--type=all}';
    protected $description = 'Kirim reminder untuk agenda yang akan datang';

    public function handle()
    {
        $type = $this->option('type');
        $now = Carbon::now();
        
        $agendas = AgendaPerusahaan::where('status', 'terjadwal')
            ->where('reminder_aktif', true)
            ->where('tanggal_mulai', '>=', $now->toDateString())
            ->get();

        foreach ($agendas as $agenda) {
            $waktuAgenda = Carbon::parse($agenda->tanggal_mulai->format('Y-m-d') . ' ' . $agenda->waktu_mulai);
            $menitSebelum = $now->diffInMinutes($waktuAgenda, false);

            // Skip jika sudah lewat
            if ($menitSebelum < 0) {
                continue;
            }

            // Reminder 1 hari (1440 menit)
            if ($type == 'all' || $type == '1_hari') {
                if ($agenda->reminder_1_hari && $menitSebelum <= 1440 && $menitSebelum > 1380) {
                    $this->kirimReminder($agenda, '1_hari', 1440);
                }
            }

            // Reminder 3 jam (180 menit)
            if ($type == 'all' || $type == '3_jam') {
                if ($agenda->reminder_3_jam && $menitSebelum <= 180 && $menitSebelum > 150) {
                    $this->kirimReminder($agenda, '3_jam', 180);
                }
            }

            // Reminder 30 menit
            if ($type == 'all' || $type == '30_menit') {
                if ($agenda->reminder_30_menit && $menitSebelum <= 30 && $menitSebelum > 25) {
                    $this->kirimReminder($agenda, '30_menit', 30);
                }
            }
        }

        $this->info('Reminder check completed!');
        return 0;
    }

    private function kirimReminder($agenda, $tipe, $menitSebelum)
    {
        // Cek apakah sudah pernah dikirim
        $sudahKirim = AgendaReminderLog::where('agenda_id', $agenda->id)
            ->where('tipe_reminder', $tipe)
            ->where('status', 'terkirim')
            ->exists();

        if ($sudahKirim) {
            return;
        }

        // Siapkan pesan
        $pesan = $this->buatPesan($agenda, $tipe);

        // Kirim ke pembuat agenda
        if ($agenda->pembuat && $agenda->pembuat->no_wa) {
            $this->kirimWA($agenda->pembuat->no_wa, $pesan);
            
            AgendaReminderLog::create([
                'agenda_id' => $agenda->id,
                'tipe_reminder' => $tipe,
                'menit_sebelum' => $menitSebelum,
                'metode_reminder' => 'whatsapp',
                'tujuan' => $agenda->pembuat->no_wa,
                'status' => 'terkirim',
                'tanggal_kirim' => now(),
            ]);
        }

        // Kirim ke peserta internal
        if (!empty($agenda->peserta_internal)) {
            $users = User::whereIn('id', $agenda->peserta_internal)->get();
            foreach ($users as $user) {
                if ($user->no_wa) {
                    $this->kirimWA($user->no_wa, $pesan);
                    
                    AgendaReminderLog::create([
                        'agenda_id' => $agenda->id,
                        'tipe_reminder' => $tipe,
                        'menit_sebelum' => $menitSebelum,
                        'metode_reminder' => 'whatsapp',
                        'tujuan' => $user->no_wa,
                        'status' => 'terkirim',
                        'tanggal_kirim' => now(),
                    ]);
                }
            }
        }

        $this->info("Reminder dikirim untuk: {$agenda->judul} ({$tipe})");
    }

    private function buatPesan($agenda, $tipe)
    {
        $waktu = $tipe == '1_hari' ? '1 HARI LAGI' : 
                ($tipe == '3_jam' ? '3 JAM LAGI' : '30 MENIT LAGI');
        
        $pesan = "🔔 *REMINDER AGENDA - {$waktu}*\n\n";
        $pesan .= "*{$agenda->judul}*\n\n";
        $pesan .= "📅 {$agenda->tanggal_mulai->format('d F Y')}\n";
        $pesan .= "🕐 " . substr($agenda->waktu_mulai, 0, 5) . " WIB\n";
        
        if ($agenda->is_online) {
            $pesan .= "📍 Online Meeting\n";
            if ($agenda->link_meeting) {
                $pesan .= "🔗 {$agenda->link_meeting}\n";
            }
        } else {
            $pesan .= "📍 {$agenda->lokasi}\n";
        }
        
        $pesan .= "\n👔 *Dress Code:* " . ucfirst(str_replace('_', ' ', $agenda->dress_code)) . "\n";
        
        if ($agenda->perlengkapan_dibawa) {
            $pesan .= "\n📋 *Perlengkapan:*\n{$agenda->perlengkapan_dibawa}\n";
        }
        
        if ($agenda->is_wajib_hadir) {
            $pesan .= "\n⚠️ *WAJIB HADIR*\n";
        }
        
        $pesan .= "\n_Sistem Agenda Perusahaan_";
        
        return $pesan;
    }

    private function kirimWA($nomor, $pesan)
    {
        // Gunakan WA Gateway existing
        try {
            $url = env('WAGATEWAY_URL') . '/send-message';
            $data = [
                'number' => $nomor,
                'message' => $pesan
            ];

            // Kirim via cURL atau HTTP client
            // Implementasi sesuai dengan WA Gateway yang ada
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Gagal kirim WA reminder: ' . $e->getMessage());
            return false;
        }
    }
}
