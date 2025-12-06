<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $karyawan;
    public $bulan;
    public $tahun;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct($karyawan, $bulan, $tahun, $pdfPath = null)
    {
        $this->karyawan = $karyawan;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $namaBulan = getNamabulan($this->bulan);
        
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'),
                'Bumi Sultan - HRD'
            ),
            subject: "Slip Gaji {$namaBulan} {$this->tahun} - Bumi Sultan",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.slipgaji.kirim-slip',
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
            $namaBulan = getNamabulan($this->bulan);
            $attachments[] = Attachment::fromPath($this->pdfPath)
                ->as("Slip_Gaji_{$this->karyawan->nama_karyawan}_{$namaBulan}_{$this->tahun}.pdf")
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}
