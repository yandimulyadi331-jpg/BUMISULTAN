<?php

namespace App\Events;

use App\Models\Pinjaman;
use App\Models\PinjamanCicilan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PinjamanPaymentUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pinjaman;
    public $cicilan;
    public $dataPerubahan;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(Pinjaman $pinjaman, PinjamanCicilan $cicilan, array $dataPerubahan = [])
    {
        $this->pinjaman = $pinjaman;
        $this->cicilan = $cicilan;
        $this->dataPerubahan = $dataPerubahan;
        $this->timestamp = now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('pinjaman.' . $this->pinjaman->id),
            new PrivateChannel('laporan.pinjaman'),
        ];
    }

    /**
     * Get the data that should be broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'pinjaman_id' => $this->pinjaman->id,
            'nomor_pinjaman' => $this->pinjaman->nomor_pinjaman,
            'cicilan_ke' => $this->cicilan->cicilan_ke,
            'total_terbayar' => (float) $this->pinjaman->total_terbayar,
            'sisa_pinjaman' => (float) $this->pinjaman->sisa_pinjaman,
            'persentase_pembayaran' => (float) $this->pinjaman->persentase_pembayaran,
            'status' => $this->pinjaman->status,
            'data_perubahan' => $this->dataPerubahan,
            'timestamp' => $this->timestamp,
        ];
    }
}
