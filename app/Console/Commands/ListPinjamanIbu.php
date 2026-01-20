<?php

namespace App\Console\Commands;

use App\Models\PinjamanIbu;
use Illuminate\Console\Command;

class ListPinjamanIbu extends Command
{
    protected $signature = 'pinjaman:list-ibu';

    public function handle()
    {
        $list = PinjamanIbu::pluck('nomor_pinjaman')->toArray();
        $this->info("PinjamanIbu total: " . count($list));
        $this->info("Nomor: " . implode(', ', $list));
        return 0;
    }
}
