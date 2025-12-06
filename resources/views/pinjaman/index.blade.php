@extends('layouts.app')

@section('content')
<style>
    .card-stats {
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }
    .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .badge-status {
        font-size: 12px;
        padding: 5px 10px;
        font-weight: 600;
    }
    .btn-action {
        padding: 4px 8px;
        font-size: 12px;
    }
</style>

<!-- Header -->
<div class="bg-primary" style="padding: 2.5rem 0; margin: -1.5rem -1.5rem 1.5rem -1.5rem;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-1">
                    <i class="bi bi-cash-coin"></i> MANAJEMEN PINJAMAN
                </h3>
                <p class="text-white-50 mb-0">Sistem Pinjaman Crew & Non-Crew dengan Approval Workflow</p>
            </div>
            <div>
                <a href="{{ route('pinjaman.download-formulir-blank') }}" class="btn btn-danger btn-lg me-2" target="_blank">
                    <i class="bi bi-file-pdf"></i> Download Formulir Kosong
                </a>
                <a href="{{ route('pinjaman.create') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-plus-circle"></i> Pengajuan Pinjaman Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Dashboard -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card card-stats bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white mb-2">Pengajuan Baru</h6>
                        <h2 class="mb-0">{{ $stats['total_pengajuan'] }}</h2>
                    </div>
                    <div><i class="bi bi-file-earmark-text" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white mb-2">Dalam Review</h6>
                        <h2 class="mb-0">{{ $stats['total_review'] + $stats['total_disetujui'] }}</h2>
                    </div>
                    <div><i class="bi bi-hourglass-split" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white mb-2">Pinjaman Berjalan</h6>
                        <h2 class="mb-0">{{ $stats['total_berjalan'] }}</h2>
                        <small>Rp {{ number_format($stats['total_nominal_berjalan'], 0, ',', '.') }}</small>
                    </div>
                    <div><i class="bi bi-arrow-repeat" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white mb-2">Lunas</h6>
                        <h2 class="mb-0">{{ $stats['total_lunas'] }}</h2>
                        <small>Rp {{ number_format($stats['total_nominal_dicairkan'], 0, ',', '.') }}</small>
                    </div>
                    <div><i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter & Pencarian</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('pinjaman.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua</option>
                        <option value="crew" {{ request('kategori') == 'crew' ? 'selected' : '' }}>Crew</option>
                        <option value="non_crew" {{ request('kategori') == 'non_crew' ? 'selected' : '' }}>Non-Crew</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua</option>
                        <option value="pengajuan" {{ request('status') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                        <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dicairkan" {{ request('status') == 'dicairkan' ? 'selected' : '' }}>Dicairkan</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua</option>
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua</option>
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="No. Pinjaman, Nama..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Tabel Pinjaman -->
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Pinjaman</h5>
        <div>
            <a href="{{ route('pinjaman.laporan') }}" class="btn btn-light btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Laporan
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No. Pinjaman</th>
                        <th>Kategori</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Jumlah Pinjaman</th>
                        <th>Tenor</th>
                        <th>Cicilan/Bulan</th>
                        <th>Jatuh Tempo</th>
                        <th>Sisa Pinjaman</th>
                        <th>Status</th>
                        <th>📧 Email</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjaman as $item)
                    <tr>
                        <td><strong>{{ $item->nomor_pinjaman }}</strong></td>
                        <td>
                            <span class="badge {{ $item->kategori_peminjam == 'crew' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ strtoupper($item->kategori_peminjam) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $item->nama_peminjam_lengkap }}</strong>
                            @if($item->kategori_peminjam == 'crew' && $item->karyawan)
                                <br><small class="text-muted">NIK: {{ $item->karyawan->nik }}</small>
                            @elseif($item->nik_peminjam)
                                <br><small class="text-muted">NIK: {{ $item->nik_peminjam }}</small>
                            @endif
                        </td>
                        <td>{{ $item->tanggal_pengajuan->format('d M Y') }}</td>
                        <td><strong>Rp {{ number_format($item->total_pinjaman, 0, ',', '.') }}</strong></td>
                        <td>{{ $item->tenor ?? $item->tenor_bulan }} bulan</td>
                        <td>Rp {{ number_format($item->cicilan_per_bulan, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-info">
                                <i class="bi bi-calendar-event"></i> 
                                Tgl {{ $item->tanggal_jatuh_tempo_setiap_bulan ?? 1 }}
                            </span>
                            <br><small class="text-muted">setiap bulan</small>
                        </td>
                        <td>
                            <strong class="{{ $item->sisa_pinjaman > 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($item->sisa_pinjaman, 0, ',', '.') }}
                            </strong>
                            @if($item->total_pinjaman > 0)
                            <br><small class="text-muted">{{ number_format($item->persentase_pembayaran, 1) }}%</small>
                            @endif
                        </td>
                        <td>
                            @php
                            $statusClass = [
                                'pengajuan' => 'warning',
                                'review' => 'info',
                                'disetujui' => 'primary',
                                'ditolak' => 'danger',
                                'dicairkan' => 'success',
                                'berjalan' => 'primary',
                                'lunas' => 'success',
                                'dibatalkan' => 'secondary'
                            ];
                            @endphp
                            <span class="badge badge-status bg-{{ $statusClass[$item->status] ?? 'secondary' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td>
                            @php
                                // Cek email tersedia
                                $emailTersedia = false;
                                $emailTujuan = null;
                                if ($item->kategori_peminjam === 'crew' && $item->karyawan && $item->karyawan->email) {
                                    $emailTersedia = true;
                                    $emailTujuan = $item->karyawan->email;
                                } elseif ($item->kategori_peminjam === 'non_crew' && $item->email_peminjam) {
                                    $emailTersedia = true;
                                    $emailTujuan = $item->email_peminjam;
                                }
                                
                                // Cek email terakhir dikirim
                                $lastEmail = $item->emailNotifications()->where('status', 'sent')->latest('sent_at')->first();
                            @endphp
                            
                            @if($emailTersedia)
                                <div class="text-center">
                                    @if($lastEmail)
                                        <span class="badge bg-success" title="Email terakhir dikirim: {{ $lastEmail->sent_at->format('d M Y H:i') }}">
                                            <i class="bi bi-check-circle"></i> Terkirim
                                        </span>
                                        <br>
                                        <small class="text-muted" style="font-size: 10px;">
                                            {{ $lastEmail->sent_at->diffForHumans() }}
                                        </small>
                                    @else
                                        <span class="badge bg-info text-white">
                                            <i class="bi bi-robot"></i> Otomatis
                                        </span>
                                        <br>
                                        <small class="text-muted" style="font-size: 10px;">
                                            Kirim otomatis setiap hari jam 08:00
                                        </small>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle"></i> Tidak ada email
                                </span>
                            @endif
                        </td>
                        <td>
                            @php
                                // Cek email tersedia untuk tombol di aksi
                                $emailTersediaAksi = false;
                                $emailTujuanAksi = null;
                                if ($item->kategori_peminjam === 'non_crew' && $item->email_peminjam) {
                                    $emailTersediaAksi = true;
                                    $emailTujuanAksi = $item->email_peminjam;
                                }
                            @endphp
                            
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Tombol Detail (selalu aktif) -->
                                <a href="{{ route('pinjaman.show', $item->id) }}" class="btn btn-info btn-action" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <!-- Tombol Edit -->
                                <a href="{{ route('pinjaman.edit', $item->id) }}" 
                                   class="btn btn-warning btn-action" 
                                   title="Edit Pinjaman"
                                   @if(!in_array($item->status, ['pengajuan', 'review', 'disetujui']))
                                       onclick="alert('Pinjaman dengan status {{ strtoupper($item->status) }} tidak dapat diedit!'); return false;"
                                       style="opacity: 0.5; cursor: not-allowed;"
                                   @endif>
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <!-- Tombol Hapus -->
                                <button type="button" 
                                        class="btn btn-danger btn-action" 
                                        title="Hapus Pinjaman"
                                        @if(in_array($item->status, ['lunas', 'ditolak', 'dibatalkan']))
                                            onclick="confirmDelete(
                                                {{ $item->id }}, 
                                                '{{ $item->nomor_pinjaman }}', 
                                                '{{ addslashes($item->nama_peminjam_lengkap) }}', 
                                                'Rp {{ number_format($item->total_pinjaman, 0, ',', '.') }}',
                                                '{{ $item->status }}'
                                            )"
                                        @else
                                            onclick="alertCannotDelete('{{ $item->status }}')"
                                            style="opacity: 0.5; cursor: not-allowed;"
                                        @endif>
                                    <i class="bi bi-trash"></i>
                                </button>
                                
                                <!-- Hidden Form untuk Delete -->
                                <form id="delete-form-{{ $item->id }}" 
                                      action="{{ route('pinjaman.destroy', $item->id) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2">Tidak ada data pinjaman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $pinjaman->links() }}
        </div>
    </div>
</div>

<!-- SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ===== GLOBAL FUNCTIONS =====

// Function untuk konfirmasi hapus dengan SweetAlert2
function confirmDelete(pinjamanId, nomor, nama, jumlah, status) {
    Swal.fire({
        title: '⚠️ PERINGATAN!',
        html: `
            <div style="text-align: left; padding: 20px;">
                <p style="font-size: 16px; margin-bottom: 15px;">
                    <strong>Yakin ingin menghapus pinjaman ini?</strong>
                </p>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;">
                    <p style="margin: 5px 0;"><strong>No. Pinjaman:</strong> ${nomor}</p>
                    <p style="margin: 5px 0;"><strong>Nama:</strong> ${nama}</p>
                    <p style="margin: 5px 0;"><strong>Jumlah:</strong> ${jumlah}</p>
                    <p style="margin: 5px 0;"><strong>Status:</strong> <span class="badge bg-success">${status}</span></p>
                </div>
                <p style="color: #dc3545; margin-top: 15px; font-weight: 600;">
                    <i class="bi bi-exclamation-triangle"></i> Data yang dihapus tidak dapat dikembalikan!
                </p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus!',
        cancelButtonText: '<i class="bi bi-x-circle"></i> Batal',
        width: '600px',
        customClass: {
            popup: 'swal-modern',
            confirmButton: 'btn btn-danger btn-lg px-4',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            document.getElementById('delete-form-' + pinjamanId).submit();
        }
    });
}

// Function untuk alert tidak bisa hapus
function alertCannotDelete(status) {
    Swal.fire({
        title: '🚫 Tidak Dapat Dihapus',
        html: `
            <div style="text-align: left; padding: 20px;">
                <p style="font-size: 16px; margin-bottom: 15px;">
                    Pinjaman dengan status <strong class="text-primary">${status.toUpperCase()}</strong> tidak dapat dihapus!
                </p>
                <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <p style="margin: 5px 0; font-weight: 600; color: #856404;">
                        <i class="bi bi-info-circle"></i> Syarat Hapus Pinjaman:
                    </p>
                    <ul style="margin: 10px 0; padding-left: 20px; color: #856404;">
                        <li>Status: <strong>LUNAS</strong></li>
                        <li>Status: <strong>DITOLAK</strong></li>
                        <li>Status: <strong>DIBATALKAN</strong></li>
                    </ul>
                </div>
            </div>
        `,
        icon: 'warning',
        confirmButtonColor: '#0d6efd',
        confirmButtonText: '<i class="bi bi-check-circle"></i> OK, Mengerti',
        width: '600px',
        customClass: {
            confirmButton: 'btn btn-primary btn-lg px-5'
        },
        buttonsStyling: false
    });
}

// Function kirim email via AJAX
function kirimEmail(pinjamanId, email) {
    console.log('kirimEmail function called:', pinjamanId, email);
    
    // Show loading
    Swal.fire({
        title: '📤 Mengirim Email...',
        html: `Mohon tunggu, sedang mengirim ke <strong>${email}</strong>`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const url = `/pinjaman/${pinjamanId}/kirim-email`;
    console.log('Sending request to:', url);
    
    // AJAX request
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            Swal.fire({
                title: '✅ EMAIL TERKIRIM!',
                html: `
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 72px; color: #198754; margin-bottom: 20px;">
                            ✅
                        </div>
                        <p style="font-size: 20px; font-weight: 600; margin-bottom: 15px; color: #198754;">
                            Email Berhasil Dikirim!
                        </p>
                        <div style="background: #d1e7dd; padding: 15px; border-radius: 8px; border-left: 4px solid #198754; text-align: left;">
                            <p style="margin: 5px 0;"><strong>📧 Email Tujuan:</strong> ${data.email}</p>
                            <p style="margin: 5px 0;"><strong>📋 Tipe Notifikasi:</strong> ${data.tipe}</p>
                            <p style="margin: 5px 0;"><strong>🕐 Waktu:</strong> Baru saja</p>
                        </div>
                        <p style="color: #6c757d; margin-top: 15px; font-size: 14px;">
                            <i class="bi bi-info-circle"></i> Penerima akan menerima email dalam beberapa menit
                        </p>
                    </div>
                `,
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: '<i class="bi bi-check-circle"></i> OK, Tutup',
                customClass: {
                    confirmButton: 'btn btn-success btn-lg px-5'
                },
                buttonsStyling: false,
                allowOutsideClick: false
            }).then(() => {
                // Reload halaman untuk update status
                location.reload();
            });
        } else {
            Swal.fire({
                title: '❌ Gagal Kirim Email',
                html: `
                    <div style="text-align: left; padding: 20px;">
                        <p style="font-size: 16px; margin-bottom: 15px; color: #dc3545;">
                            ${data.message}
                        </p>
                    </div>
                `,
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: '<i class="bi bi-x-circle"></i> Tutup',
                customClass: {
                    confirmButton: 'btn btn-danger btn-lg px-5'
                },
                buttonsStyling: false
            });
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        Swal.fire({
            title: '❌ Error',
            html: `
                <div style="text-align: left; padding: 20px;">
                    <p style="font-size: 16px; margin-bottom: 15px; color: #dc3545;">
                        Terjadi kesalahan saat mengirim email
                    </p>
                    <div style="background: #f8d7da; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;">
                        <p style="margin: 5px 0; color: #842029;">${error.message}</p>
                    </div>
                </div>
            `,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: '<i class="bi bi-x-circle"></i> Tutup',
            customClass: {
                confirmButton: 'btn btn-danger btn-lg px-5'
            },
            buttonsStyling: false
        });
    });
}
                        <li>✅ Status: <strong>LUNAS</strong> (sudah dibayar penuh)</li>
                        <li>✅ Status: <strong>DITOLAK</strong> (pengajuan ditolak)</li>
                        <li>✅ Status: <strong>DIBATALKAN</strong> (dibatalkan)</li>
                    </ul>
                </div>
                <p style="color: #6c757d; margin-top: 15px; font-size: 14px;">
                    <i class="bi bi-shield-check"></i> Kebijakan ini untuk menjaga integritas data keuangan yang masih berjalan.
                </p>
            </div>
        `,
        icon: 'info',
        confirmButtonColor: '#0d6efd',
        confirmButtonText: '<i class="bi bi-check-circle"></i> Mengerti',
        width: '600px',
        customClass: {
            popup: 'swal-modern',
            confirmButton: 'btn btn-primary btn-lg px-4'
        },
        buttonsStyling: false
    });
}
</script>

<style>
/* Custom SweetAlert2 Styling */
.swal-modern {
    border-radius: 12px !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.swal2-html-container {
    margin: 0 !important;
    padding: 0 !important;
}

.swal2-title {
    font-size: 24px !important;
    font-weight: 700 !important;
    padding: 20px 20px 10px 20px !important;
}

.swal2-actions {
    gap: 10px !important;
    padding: 20px !important;
}
</style>

@endsection
