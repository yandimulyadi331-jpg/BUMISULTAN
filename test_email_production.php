<?php
/**
 * TEST EMAIL PRODUCTION
 * Untuk mengecek dan memperbaiki masalah email di hosting
 * 
 * Cara pakai:
 * 1. Upload file ini ke folder root hosting
 * 2. Akses via browser: https://your-domain.com/test_email_production.php
 * 3. Isi form dan klik "Test Kirim Email"
 */

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

// Cek apakah form sudah disubmit
$emailSent = false;
$error = null;
$configInfo = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
    $testEmail = $_POST['email'];
    
    try {
        // Test kirim email sederhana
        Mail::raw('Test email dari sistem Bumi Sultan. Jika Anda menerima email ini, berarti konfigurasi email sudah benar!', function($message) use ($testEmail) {
            $message->to($testEmail)
                    ->subject('Test Email - Sistem Bumi Sultan');
        });
        
        $emailSent = true;
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }
}

// Ambil konfigurasi email (hide password)
$configInfo = [
    'MAIL_MAILER' => env('MAIL_MAILER'),
    'MAIL_HOST' => env('MAIL_HOST'),
    'MAIL_PORT' => env('MAIL_PORT'),
    'MAIL_USERNAME' => env('MAIL_USERNAME'),
    'MAIL_PASSWORD' => env('MAIL_PASSWORD') ? '***hidden*** (length: ' . strlen(env('MAIL_PASSWORD')) . ')' : 'NOT SET',
    'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
    'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
    'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email Production - Bumi Sultan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: none;
            border-radius: 15px;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        .config-table {
            font-size: 13px;
        }
        .config-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .config-table tr:last-child td {
            border-bottom: none;
        }
        .btn-test {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: bold;
        }
        .btn-test:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h2 class="mb-0">
                            <i class="bi bi-envelope-check"></i> Test Email Production
                        </h2>
                        <p class="mb-0 mt-2">Sistem Bumi Sultan - Diagnostic Tool</p>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if ($emailSent): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill"></i> 
                                <strong>Email Berhasil Dikirim!</strong>
                                <p class="mb-0 mt-2">Email test telah dikirim ke <strong><?= htmlspecialchars($testEmail) ?></strong>. Silakan cek inbox/spam folder Anda.</p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i> 
                                <strong>Error!</strong>
                                <p class="mb-0 mt-2"><?= htmlspecialchars($error) ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Konfigurasi Email -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="bi bi-gear"></i> Konfigurasi Email Saat Ini:</h5>
                            <table class="table table-bordered config-table">
                                <?php foreach($configInfo as $key => $value): ?>
                                <tr>
                                    <td style="width: 40%"><strong><?= $key ?></strong></td>
                                    <td>
                                        <?php if ($value === null || $value === ''): ?>
                                            <span class="badge bg-danger">NOT SET</span>
                                        <?php else: ?>
                                            <code><?= htmlspecialchars($value) ?></code>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>

                        <!-- Form Test Email -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-3"><i class="bi bi-send"></i> Test Kirim Email:</h5>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Email Tujuan:</label>
                                        <input type="email" name="email" class="form-control" 
                                               placeholder="contoh@email.com" required 
                                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                        <small class="text-muted">Masukkan email Anda untuk menerima test email</small>
                                    </div>
                                    <button type="submit" name="test_email" class="btn btn-primary btn-test w-100">
                                        <i class="bi bi-send-fill"></i> Test Kirim Email
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Checklist Troubleshooting -->
                        <div class="mt-4">
                            <h5 class="mb-3"><i class="bi bi-list-check"></i> Checklist Troubleshooting:</h5>
                            <div class="alert alert-info">
                                <p><strong>Jika email tidak terkirim, cek hal berikut:</strong></p>
                                <ol class="mb-0">
                                    <li><strong>File .env</strong> - Pastikan semua variable MAIL_* sudah diset dengan benar</li>
                                    <li><strong>Gmail App Password</strong> - Jika pakai Gmail, gunakan App Password (bukan password biasa)</li>
                                    <li><strong>Port & Encryption</strong> - Gmail: port 587 + TLS atau port 465 + SSL</li>
                                    <li><strong>Firewall Hosting</strong> - Pastikan port SMTP tidak diblok oleh hosting</li>
                                    <li><strong>PHP Extensions</strong> - Cek apakah openssl extension aktif</li>
                                    <li><strong>Laravel Cache</strong> - Jalankan: <code>php artisan config:clear</code></li>
                                    <li><strong>Log Error</strong> - Cek file <code>storage/logs/laravel.log</code></li>
                                </ol>
                            </div>
                        </div>

                        <!-- Command untuk Clear Cache -->
                        <div class="mt-4">
                            <h5 class="mb-3"><i class="bi bi-terminal"></i> Command Penting (SSH):</h5>
                            <div class="card">
                                <div class="card-body bg-dark text-light">
                                    <code>
                                        # Clear semua cache<br>
                                        php artisan config:clear<br>
                                        php artisan cache:clear<br>
                                        php artisan view:clear<br>
                                        php artisan route:clear<br>
                                        <br>
                                        # Test PHP mail (basic)<br>
                                        php -r "mail('test@example.com', 'Test', 'Test message');"<br>
                                        <br>
                                        # Cek log error<br>
                                        tail -f storage/logs/laravel.log
                                    </code>
                                </div>
                            </div>
                        </div>

                        <!-- Solusi Umum -->
                        <div class="mt-4">
                            <h5 class="mb-3"><i class="bi bi-lightbulb"></i> Solusi Umum:</h5>
                            <div class="accordion" id="solutionAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#solution1">
                                            ❌ Error: Connection timed out
                                        </button>
                                    </h2>
                                    <div id="solution1" class="accordion-collapse collapse show" data-bs-parent="#solutionAccordion">
                                        <div class="accordion-body">
                                            <strong>Penyebab:</strong> Port SMTP diblok oleh firewall hosting<br>
                                            <strong>Solusi:</strong>
                                            <ul>
                                                <li>Hubungi penyedia hosting untuk membuka port 587/465</li>
                                                <li>Alternatif: Gunakan Mailgun, SendGrid, atau Amazon SES</li>
                                                <li>Coba port alternatif (587 atau 465)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#solution2">
                                            ❌ Error: Username and Password not accepted
                                        </button>
                                    </h2>
                                    <div id="solution2" class="accordion-collapse collapse" data-bs-parent="#solutionAccordion">
                                        <div class="accordion-body">
                                            <strong>Penyebab:</strong> Kredensial Gmail salah atau "Less secure app access" dinonaktifkan<br>
                                            <strong>Solusi:</strong>
                                            <ul>
                                                <li>Aktifkan 2FA di Google Account</li>
                                                <li>Generate App Password di Google: <a href="https://myaccount.google.com/apppasswords" target="_blank">myaccount.google.com/apppasswords</a></li>
                                                <li>Gunakan App Password (16 digit) di .env, bukan password asli</li>
                                                <li>Format di .env: <code>MAIL_PASSWORD="xxxx xxxx xxxx xxxx"</code></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#solution3">
                                            ❌ Error: SSL/TLS certificate problem
                                        </button>
                                    </h2>
                                    <div id="solution3" class="accordion-collapse collapse" data-bs-parent="#solutionAccordion">
                                        <div class="accordion-body">
                                            <strong>Penyebab:</strong> Sertifikat SSL tidak valid atau expired<br>
                                            <strong>Solusi:</strong>
                                            <ul>
                                                <li>Update PHP CA certificates</li>
                                                <li>Ubah MAIL_ENCRYPTION dari tls ke ssl (atau sebaliknya)</li>
                                                <li>Ubah MAIL_PORT: 587 (untuk TLS) atau 465 (untuk SSL)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-center text-muted">
                        <small>© <?= date('Y') ?> Bumi Sultan - Sistem Manajemen Karyawan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
