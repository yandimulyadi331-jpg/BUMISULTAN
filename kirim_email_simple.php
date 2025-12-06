<?php
/**
 * Script sederhana untuk kirim email manual
 * Buka di browser: http://localhost:8000/kirim_email_simple.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pinjaman;
use App\Mail\PinjamanJatuhTempoMail;
use Illuminate\Support\Facades\Mail;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_email'])) {
    $pinjamanId = $_POST['pinjaman_id'];
    
    try {
        $pinjaman = Pinjaman::with(['karyawan', 'approvedBy', 'angsuran'])->findOrFail($pinjamanId);
        
        // Cek email
        $email = null;
        if ($pinjaman->kategori === 'CREW') {
            $email = $pinjaman->karyawan->email ?? null;
        } else {
            $email = $pinjaman->email_peminjam;
        }
        
        if (!$email) {
            $error = "⚠️ Email tidak ditemukan untuk pinjaman ini!";
        } else {
            // Tentukan tipe notifikasi
            $today = now();
            $tanggalJatuhTempo = \Carbon\Carbon::parse($pinjaman->tanggal_jatuh_tempo);
            $selisihHari = $today->diffInDays($tanggalJatuhTempo, false);
            
            if ($selisihHari < 0) {
                $tipeNotifikasi = 'overdue';
            } elseif ($selisihHari == 0) {
                $tipeNotifikasi = 'h-0';
            } elseif ($selisihHari == 1) {
                $tipeNotifikasi = 'h-1';
            } elseif ($selisihHari == 3) {
                $tipeNotifikasi = 'h-3';
            } elseif ($selisihHari == 7) {
                $tipeNotifikasi = 'h-7';
            } else {
                $tipeNotifikasi = 'reminder';
            }
            
            // Kirim email
            Mail::to($email)->send(new PinjamanJatuhTempoMail($pinjaman, $tipeNotifikasi));
            
            // Log ke database
            \DB::table('pinjaman_email_notifications')->insert([
                'pinjaman_id' => $pinjaman->id,
                'tipe_notifikasi' => $tipeNotifikasi,
                'email' => $email,
                'status' => 'sent',
                'sent_at' => now(),
                'keterangan' => 'Manual send via web form',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $success = "✅ BERHASIL! Email terkirim ke: $email (Tipe: $tipeNotifikasi)";
        }
    } catch (Exception $e) {
        $error = "❌ ERROR: " . $e->getMessage();
    }
}

// Ambil data pinjaman yang punya email
$pinjamanList = Pinjaman::with('karyawan')
    ->where(function($q) {
        $q->whereNotNull('email_peminjam')
          ->orWhereHas('karyawan', function($q2) {
              $q2->whereNotNull('email');
          });
    })
    ->where('status_lunas', 0)
    ->orderBy('tanggal_jatuh_tempo', 'asc')
    ->get();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Email Pinjaman - Simple Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .main-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .header-section {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
        }
        .pinjaman-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }
        .pinjaman-card:hover {
            border-color: #0d6efd;
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .pinjaman-card.selected {
            border-color: #0d6efd;
            background: #e7f3ff;
            border-width: 3px;
        }
        .btn-kirim {
            padding: 12px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="header-section text-center">
                <h1><i class="bi bi-envelope-at"></i> KIRIM EMAIL PINJAMAN</h1>
                <p class="mb-0">Form Sederhana untuk Mengirim Email Notifikasi</p>
            </div>
            
            <!-- Alert -->
            <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                <h5><i class="bi bi-check-circle-fill"></i> Sukses!</h5>
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                <h5><i class="bi bi-exclamation-triangle-fill"></i> Error!</h5>
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <!-- Content -->
            <div class="p-4">
                <h5 class="mb-3"><i class="bi bi-list-check"></i> Pilih Pinjaman yang Akan Dikirim Email:</h5>
                
                <form method="POST" action="" id="emailForm">
                    <input type="hidden" name="pinjaman_id" id="pinjamanIdInput" required>
                    
                    <div class="row">
                        <?php if ($pinjamanList->isEmpty()): ?>
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle"></i> Tidak ada pinjaman dengan email yang tersedia
                            </div>
                        </div>
                        <?php else: ?>
                            <?php foreach ($pinjamanList as $pinjaman): ?>
                            <?php
                                $email = $pinjaman->kategori === 'CREW' 
                                    ? ($pinjaman->karyawan->email ?? null)
                                    : $pinjaman->email_peminjam;
                                    
                                if (!$email) continue;
                                
                                $today = now();
                                $tanggalJatuhTempo = \Carbon\Carbon::parse($pinjaman->tanggal_jatuh_tempo);
                                $selisihHari = $today->diffInDays($tanggalJatuhTempo, false);
                                
                                if ($selisihHari < 0) {
                                    $statusBadge = '<span class="badge bg-danger">OVERDUE</span>';
                                } elseif ($selisihHari <= 1) {
                                    $statusBadge = '<span class="badge bg-warning">URGENT</span>';
                                } elseif ($selisihHari <= 3) {
                                    $statusBadge = '<span class="badge bg-info">MENDESAK</span>';
                                } else {
                                    $statusBadge = '<span class="badge bg-secondary">AKTIF</span>';
                                }
                            ?>
                            <div class="col-md-6">
                                <div class="pinjaman-card" onclick="selectPinjaman(<?= $pinjaman->id ?>, this)">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1"><strong><?= $pinjaman->nomor_pinjaman ?></strong></h6>
                                            <p class="mb-1 text-muted"><?= $pinjaman->nama_peminjam ?></p>
                                        </div>
                                        <?= $statusBadge ?>
                                    </div>
                                    <hr class="my-2">
                                    <div class="small">
                                        <div class="mb-1">
                                            <i class="bi bi-calendar-event text-primary"></i>
                                            <strong>Jatuh Tempo:</strong> 
                                            <?= \Carbon\Carbon::parse($pinjaman->tanggal_jatuh_tempo)->format('d M Y') ?>
                                            (<?= abs($selisihHari) ?> hari <?= $selisihHari >= 0 ? 'lagi' : 'lewat' ?>)
                                        </div>
                                        <div class="mb-1">
                                            <i class="bi bi-cash-stack text-success"></i>
                                            <strong>Jumlah:</strong> Rp <?= number_format($pinjaman->jumlah, 0, ',', '.') ?>
                                        </div>
                                        <div>
                                            <i class="bi bi-envelope text-info"></i>
                                            <strong>Email:</strong> <?= $email ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!$pinjamanList->isEmpty()): ?>
                    <div class="text-center mt-4">
                        <button type="submit" name="kirim_email" class="btn btn-primary btn-kirim" disabled id="btnKirim">
                            <i class="bi bi-send-fill"></i> KIRIM EMAIL SEKARANG
                        </button>
                        <p class="text-muted mt-2 small">
                            <i class="bi bi-info-circle"></i> Pilih salah satu pinjaman di atas terlebih dahulu
                        </p>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="/pinjaman" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Pinjaman
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedCard = null;
        
        function selectPinjaman(id, card) {
            // Remove previous selection
            if (selectedCard) {
                selectedCard.classList.remove('selected');
            }
            
            // Add new selection
            card.classList.add('selected');
            selectedCard = card;
            
            // Set hidden input
            document.getElementById('pinjamanIdInput').value = id;
            
            // Enable button
            document.getElementById('btnKirim').disabled = false;
            document.getElementById('btnKirim').classList.remove('btn-secondary');
            document.getElementById('btnKirim').classList.add('btn-primary');
        }
        
        // Confirm before submit
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin mengirim email ke pinjaman yang dipilih?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
