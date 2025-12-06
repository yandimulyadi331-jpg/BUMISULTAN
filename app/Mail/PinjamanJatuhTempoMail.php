<?php

namespace App\Mail;

use App\Models\Pinjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PinjamanJatuhTempoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pinjaman;
    public $tipeNotifikasi;
    public $hariSebelumJatuhTempo;

    /**
     * Create a new message instance.
     */
    public function __construct(Pinjaman $pinjaman, string $tipeNotifikasi, ?int $hariSebelumJatuhTempo = null)
    {
        $this->pinjaman = $pinjaman;
        $this->tipeNotifikasi = $tipeNotifikasi;
        $this->hariSebelumJatuhTempo = $hariSebelumJatuhTempo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjectMap = [
            'jatuh_tempo_hari_ini' => '🔔 Pinjaman Jatuh Tempo HARI INI',
            'jatuh_tempo_besok' => '⏰ Pinjaman Jatuh Tempo BESOK',
            'jatuh_tempo_3_hari' => '📅 Pinjaman Jatuh Tempo 3 Hari Lagi',
            'jatuh_tempo_7_hari' => '📋 Pinjaman Jatuh Tempo 7 Hari Lagi',
            'sudah_lewat_jatuh_tempo' => '⚠️ Pinjaman Sudah Lewat Jatuh Tempo',
        ];

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'),
                'Manajemen Bumi Sultan'
            ),
            subject: $subjectMap[$this->tipeNotifikasi] ?? 'Notifikasi Pinjaman',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pinjaman.jatuh-tempo',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
