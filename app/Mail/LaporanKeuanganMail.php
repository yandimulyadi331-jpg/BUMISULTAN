<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class LaporanKeuanganMail extends Mailable
{
    use Queueable, SerializesModels;

    public $periodeLabel;
    public $tanggalAwal;
    public $tanggalAkhir;
    public $pdfPath;
    public $totalMasuk;
    public $totalKeluar;
    public $saldoAkhir;

    /**
     * Create a new message instance.
     */
    public function __construct($periodeLabel, $tanggalAwal, $tanggalAkhir, $pdfPath = null, $totalMasuk = 0, $totalKeluar = 0, $saldoAkhir = 0)
    {
        $this->periodeLabel = $periodeLabel;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->pdfPath = $pdfPath;
        $this->totalMasuk = $totalMasuk;
        $this->totalKeluar = $totalKeluar;
        $this->saldoAkhir = $saldoAkhir;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'),
                'Bumi Sultan - Laporan Keuangan'
            ),
            subject: "Laporan Keuangan {$this->periodeLabel} - Bumi Sultan",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.laporan-keuangan.kirim-laporan',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $attachments[] = Attachment::fromPath($this->pdfPath)
                ->as("Laporan_Keuangan_{$this->periodeLabel}.pdf")
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}
