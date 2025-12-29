<?php
/**
 * CEK ERROR LOG EMAIL
 * Upload file ini ke hosting untuk cek error detail
 */

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Models\Karyawan;
use App\Mail\SlipGajiMail;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cek Error Log Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #f5f5f5; }
        .card { margin-bottom: 20px; }
        pre { background: #000; color: #0f0; padding: 15px; border-radius: 5px; max-height: 500px; overflow: auto; }
        .error-line { color: #ff6b6b; }
        .success-line { color: #51cf66; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">🔍 Diagnosa Error Email Slip Gaji</h2>

        <!-- Config Email -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">⚙️ Konfigurasi Email</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><strong>MAIL_MAILER</strong></td>
                        <td><?= env('MAIL_MAILER') ?: '<span class="text-danger">NOT SET</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>MAIL_HOST</strong></td>
                        <td><?= env('MAIL_HOST') ?: '<span class="text-danger">NOT SET</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>MAIL_PORT</strong></td>
                        <td><?= env('MAIL_PORT') ?: '<span class="text-danger">NOT SET</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>MAIL_USERNAME</strong></td>
                        <td><?= env('MAIL_USERNAME') ?: '<span class="text-danger">NOT SET</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>MAIL_PASSWORD</strong></td>
                        <td><?= env('MAIL_PASSWORD') ? 'SET (length: ' . strlen(env('MAIL_PASSWORD')) . ')' : '<span class="text-danger">NOT SET</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>MAIL_ENCRYPTION</strong></td>
                        <td><?= env('MAIL_ENCRYPTION') ?: '<span class="text-danger">NOT SET</span>' ?></td>
                    </tr>
                    <tr>
                        <td><strong>MAIL_FROM_ADDRESS</strong></td>
                        <td><?= env('MAIL_FROM_ADDRESS') ?: '<span class="text-danger">NOT SET</span>' ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Test Koneksi SMTP -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">🔌 Test Koneksi SMTP</h5>
            </div>
            <div class="card-body">
                <?php
                $host = env('MAIL_HOST');
                $port = env('MAIL_PORT');
                $timeout = 10;
                
                echo "<p><strong>Testing connection to {$host}:{$port}...</strong></p>";
                
                $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
                
                if ($connection) {
                    echo '<div class="alert alert-success">✅ Koneksi SMTP <strong>BERHASIL</strong>!</div>';
                    fclose($connection);
                } else {
                    echo '<div class="alert alert-danger">❌ Koneksi SMTP <strong>GAGAL</strong>!<br>';
                    echo "Error: {$errstr} ({$errno})<br>";
                    echo "<strong>Solusi:</strong> Port {$port} kemungkinan diblok oleh firewall hosting. Hubungi provider hosting atau coba port alternatif (587 atau 465).</div>";
                }
                ?>
            </div>
        </div>

        <!-- Karyawan dengan Email -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">👥 Karyawan dengan Email</h5>
            </div>
            <div class="card-body">
                <?php
                $karyawan = Karyawan::whereNotNull('email')
                    ->where('email', '!=', '')
                    ->where('status_aktif_karyawan', '1')
                    ->get();
                
                echo "<p>Total: <strong>" . $karyawan->count() . " karyawan</strong></p>";
                
                if ($karyawan->count() > 0) {
                    echo '<table class="table table-sm table-bordered">';
                    echo '<thead><tr><th>NIK</th><th>Nama</th><th>Email</th><th>Status</th></tr></thead><tbody>';
                    foreach ($karyawan as $k) {
                        $emailValid = filter_var($k->email, FILTER_VALIDATE_EMAIL);
                        $statusBadge = $emailValid 
                            ? '<span class="badge bg-success">Valid</span>' 
                            : '<span class="badge bg-danger">Invalid</span>';
                        echo "<tr>
                            <td>{$k->nik}</td>
                            <td>{$k->nama_karyawan}</td>
                            <td>{$k->email}</td>
                            <td>{$statusBadge}</td>
                        </tr>";
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-warning">Tidak ada karyawan dengan email terdaftar</div>';
                }
                ?>
            </div>
        </div>

        <!-- Log Error Terbaru -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">📄 Log Error Terbaru (50 baris terakhir)</h5>
            </div>
            <div class="card-body">
                <?php
                $logFile = storage_path('logs/laravel.log');
                
                if (file_exists($logFile)) {
                    // Ambil 100 baris terakhir
                    $lines = file($logFile);
                    $lastLines = array_slice($lines, -100);
                    
                    // Filter baris yang ada kata "email" atau "mail" atau "ERROR"
                    $filteredLines = array_filter($lastLines, function($line) {
                        return stripos($line, 'email') !== false 
                            || stripos($line, 'mail') !== false
                            || stripos($line, 'ERROR') !== false
                            || stripos($line, 'slip gaji') !== false;
                    });
                    
                    if (count($filteredLines) > 0) {
                        echo '<pre>';
                        foreach ($filteredLines as $line) {
                            if (stripos($line, 'ERROR') !== false || stripos($line, 'gagal') !== false) {
                                echo '<span class="error-line">' . htmlspecialchars($line) . '</span>';
                            } elseif (stripos($line, 'berhasil') !== false) {
                                echo '<span class="success-line">' . htmlspecialchars($line) . '</span>';
                            } else {
                                echo htmlspecialchars($line);
                            }
                        }
                        echo '</pre>';
                    } else {
                        echo '<div class="alert alert-info">Tidak ada log error terkait email dalam 100 baris terakhir</div>';
                    }
                    
                    echo '<hr>';
                    echo '<p><strong>Path log file:</strong> <code>' . $logFile . '</code></p>';
                    echo '<p><strong>Size:</strong> ' . formatBytes(filesize($logFile)) . '</p>';
                    echo '<p><strong>Last modified:</strong> ' . date('Y-m-d H:i:s', filemtime($logFile)) . '</p>';
                } else {
                    echo '<div class="alert alert-warning">Log file tidak ditemukan: ' . $logFile . '</div>';
                }
                
                function formatBytes($bytes) {
                    if ($bytes >= 1073741824) {
                        return number_format($bytes / 1073741824, 2) . ' GB';
                    } elseif ($bytes >= 1048576) {
                        return number_format($bytes / 1048576, 2) . ' MB';
                    } elseif ($bytes >= 1024) {
                        return number_format($bytes / 1024, 2) . ' KB';
                    } else {
                        return $bytes . ' bytes';
                    }
                }
                ?>
            </div>
        </div>

        <!-- Test Kirim Email Sederhana -->
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">✉️ Test Kirim Email Sederhana</h5>
            </div>
            <div class="card-body">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_send'])) {
                    $testEmail = $_POST['test_email'];
                    
                    echo '<div class="alert alert-info">Mengirim test email ke: <strong>' . htmlspecialchars($testEmail) . '</strong>...</div>';
                    
                    try {
                        Mail::raw('Test email dari Bumi Sultan. Jika Anda menerima email ini, berarti konfigurasi email sudah benar!', function($message) use ($testEmail) {
                            $message->to($testEmail)
                                    ->subject('Test Email - Bumi Sultan');
                        });
                        
                        echo '<div class="alert alert-success">✅ <strong>BERHASIL!</strong> Email test berhasil dikirim. Silakan cek inbox/spam Anda.</div>';
                    } catch (\Exception $e) {
                        echo '<div class="alert alert-danger">❌ <strong>GAGAL!</strong><br>';
                        echo '<strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '<br><br>';
                        
                        // Analisa error
                        if (stripos($e->getMessage(), 'Connection timed out') !== false) {
                            echo '<strong>Kemungkinan penyebab:</strong> Port SMTP diblok oleh firewall hosting<br>';
                            echo '<strong>Solusi:</strong><br>';
                            echo '1. Hubungi provider hosting untuk membuka port ' . env('MAIL_PORT') . '<br>';
                            echo '2. Atau coba ubah MAIL_PORT ke 465 dan MAIL_ENCRYPTION ke ssl di .env<br>';
                            echo '3. Atau gunakan service email alternatif (Mailgun, SendGrid, SES)';
                        } elseif (stripos($e->getMessage(), 'Username and Password not accepted') !== false) {
                            echo '<strong>Kemungkinan penyebab:</strong> Password Gmail salah atau belum menggunakan App Password<br>';
                            echo '<strong>Solusi:</strong><br>';
                            echo '1. Generate App Password di: <a href="https://myaccount.google.com/apppasswords" target="_blank">https://myaccount.google.com/apppasswords</a><br>';
                            echo '2. Aktifkan 2FA terlebih dahulu<br>';
                            echo '3. Update MAIL_PASSWORD di .env dengan App Password (16 digit)<br>';
                            echo '4. Jalankan: php artisan config:clear';
                        } elseif (stripos($e->getMessage(), 'SSL') !== false || stripos($e->getMessage(), 'certificate') !== false) {
                            echo '<strong>Kemungkinan penyebab:</strong> Masalah SSL certificate<br>';
                            echo '<strong>Solusi:</strong><br>';
                            echo '1. Coba ubah MAIL_ENCRYPTION dari tls ke ssl (atau sebaliknya)<br>';
                            echo '2. Atau ubah MAIL_PORT: 587 (TLS) atau 465 (SSL)';
                        }
                        
                        echo '</div>';
                    }
                }
                ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Tujuan Test:</label>
                        <input type="email" name="test_email" class="form-control" 
                               placeholder="your@email.com" required
                               value="<?= isset($_POST['test_email']) ? htmlspecialchars($_POST['test_email']) : '' ?>">
                    </div>
                    <button type="submit" name="test_send" class="btn btn-warning">
                        <i class="bi bi-send"></i> Test Kirim Email
                    </button>
                </form>
            </div>
        </div>

        <!-- Command Helper -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">💻 Command Helper (SSH)</h5>
            </div>
            <div class="card-body">
                <h6>Clear Cache:</h6>
                <pre>php artisan config:clear
php artisan cache:clear
php artisan view:clear</pre>

                <h6>Cek Log Realtime:</h6>
                <pre>tail -f storage/logs/laravel.log</pre>

                <h6>Cek Error Email Spesifik:</h6>
                <pre>grep -i "email\|mail\|ERROR" storage/logs/laravel.log | tail -50</pre>

                <h6>Test Port SMTP:</h6>
                <pre>telnet <?= env('MAIL_HOST') ?> <?= env('MAIL_PORT') ?></pre>
            </div>
        </div>
    </div>
</body>
</html>
