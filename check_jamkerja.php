<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Check presensi_jamkerja ===\n\n";

$count = DB::table('presensi_jamkerja')->count();
echo "Total records: $count\n\n";

if ($count > 0) {
    $data = DB::select('SELECT * FROM presensi_jamkerja LIMIT 5');
    foreach ($data as $row) {
        echo "kode_jam_kerja: {$row->kode_jam_kerja}\n";
    }
}
?>
