<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$kodes = DB::select('SELECT kode_jam_kerja FROM presensi_jamkerja LIMIT 1');
echo $kodes[0]->kode_jam_kerja ?? 'NONE';
?>
