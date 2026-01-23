@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                        <path d="M9 14l2 2l4 -4"></path>
                    </svg>
                    Manajemen Perawatan Gedung
                </h2>
                <div class="text-muted mt-1">Kelola template checklist perawatan gedung</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Navigation Tabs -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="btn-list">
                            <a href="{{ route('perawatan.index') }}" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('perawatan.master.index') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                                Master Checklist
                            </a>
                            <a href="{{ route('perawatan.laporan.index') }}" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                                Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Master Checklist</h3>
                        <div class="card-actions">
                            <a href="{{ route('perawatan.master.create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                Tambah Checklist
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tabs per Periode -->
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#harian" class="nav-link active" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /></svg>
                                    Harian <span class="badge bg-primary counter-harian">{{ $masters->where('tipe_periode', 'harian')->where('is_active', true)->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#mingguan" class="nav-link" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><path d="M7 14h.01" /><path d="M11 14h.01" /></svg>
                                    Mingguan <span class="badge bg-primary counter-mingguan">{{ $masters->where('tipe_periode', 'mingguan')->where('is_active', true)->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#bulanan" class="nav-link" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><rect x="8" y="15" width="2" height="2" /></svg>
                                    Bulanan <span class="badge bg-primary counter-bulanan">{{ $masters->where('tipe_periode', 'bulanan')->where('is_active', true)->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tahunan" class="nav-link" data-bs-toggle="tab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                    Tahunan <span class="badge bg-primary counter-tahunan">{{ $masters->where('tipe_periode', 'tahunan')->where('is_active', true)->count() }}</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            @foreach(['harian', 'mingguan', 'bulanan', 'tahunan'] as $tipe)
                            <div class="tab-pane {{ $loop->first ? 'active show' : '' }}" id="{{ $tipe }}" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">Urutan</th>
                                                <th width="20%">Nama Kegiatan</th>
                                                <th width="15%">Deskripsi</th>
                                                <th width="8%">Points</th>
                                                <th width="12%">Ruangan</th>
                                                <th width="12%">Kategori</th>
                                                <th width="10%" class="text-center">Status</th>
                                                <th width="10%" class="text-center">Eksekusi (30hr)</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($masters->where('tipe_periode', $tipe) as $master)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $master->urutan }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $master->nama_kegiatan }}</strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ Str::limit($master->deskripsi, 50) }}</small>
                                                </td>
                                                <td>
                                                    @if($master->points)
                                                        @php
                                                            $pointColor = $master->points <= 3 ? 'success' : ($master->points <= 7 ? 'warning' : 'danger');
                                                        @endphp
                                                        <span class="badge bg-{{ $pointColor }}">⭐ {{ $master->points }} pts</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($master->ruangan)
                                                        <span class="badge bg-cyan">{{ $master->ruangan->nama_ruangan }}</span>
                                                    @else
                                                        <small class="text-muted">Umum</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $badgeColors = [
                                                            'kebersihan' => 'bg-blue',
                                                            'perawatan_rutin' => 'bg-green',
                                                            'pengecekan' => 'bg-yellow',
                                                            'lainnya' => 'bg-gray'
                                                        ];
                                                    @endphp
                                                    <span class="badge {{ $badgeColors[$master->kategori] ?? 'bg-secondary' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $master->kategori)) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <div class="form-check form-switch mb-0">
                                                            <input class="form-check-input checklist-toggle" 
                                                                   type="checkbox" 
                                                                   id="toggle_checklist_{{ $master->id }}" 
                                                                   data-checklist-id="{{ $master->id }}"
                                                                   data-tipe-periode="{{ $master->tipe_periode }}"
                                                                   {{ $master->is_active ? 'checked' : '' }}>
                                                        </div>
                                                        <span class="badge toggle-status-{{ $master->id }}" id="status-{{ $master->id }}">
                                                            {{ $master->is_active ? '✅ Aktif' : '❌ Nonaktif' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">{{ $master->logs_count ?? 0 }}x</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('perawatan.master.edit', $master->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                        </a>
                                                        <form action="{{ route('perawatan.master.destroy', $master->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus checklist ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <div class="empty">
                                                        <div class="empty-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                                                        </div>
                                                        <p class="empty-title">Belum ada checklist {{ $tipe }}</p>
                                                        <p class="empty-subtitle text-muted">
                                                            Tambahkan checklist baru untuk periode {{ $tipe }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ⭐ Handle individual checklist toggle
    document.querySelectorAll('.checklist-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const checklistId = this.dataset.checklistId;
            const tipePeriode = this.dataset.tipePeriode;
            const isActive = this.checked;
            const statusBadge = document.querySelector(`#status-${checklistId}`);
            
            // Update badge instantly
            if (isActive) {
                statusBadge.textContent = '✅ Aktif';
                statusBadge.className = 'badge bg-success toggle-status-' + checklistId;
            } else {
                statusBadge.textContent = '❌ Nonaktif';
                statusBadge.className = 'badge bg-danger toggle-status-' + checklistId;
            }
            
            // Send AJAX request to backend
            fetch(`/perawatan/master/${checklistId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    is_active: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update counter badge
                    const counterElement = document.querySelector(`.counter-${tipePeriode}`);
                    if (counterElement) {
                        let currentCount = parseInt(counterElement.textContent);
                        if (isActive) {
                            currentCount++; // Toggle ON = +1
                        } else {
                            currentCount--; // Toggle OFF = -1
                        }
                        counterElement.textContent = currentCount;
                    }
                    
                    const message = isActive 
                        ? `✅ "${data.data.nama_kegiatan}" sekarang AKTIF` 
                        : `❌ "${data.data.nama_kegiatan}" sekarang NONAKTIF`;
                    
                    Swal.fire({
                        title: 'Berhasil!',
                        text: message,
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    
                    // Broadcast to other tabs via WebSocket
                    if (window.Echo !== undefined) {
                        window.Echo.channel('checklist-updates')
                            .whisper('ChecklistItemToggled', {
                                checklist_id: checklistId,
                                is_active: isActive,
                                nama_kegiatan: data.data.nama_kegiatan
                            });
                    }
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Gagal mengupdate status',
                        icon: 'error'
                    });
                    this.checked = !isActive; // Revert toggle
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mengupdate',
                    icon: 'error'
                });
                this.checked = !isActive; // Revert toggle
            });
        });
    });
    
    // Listen for real-time updates from other users
    if (window.Echo !== undefined) {
        window.Echo.channel('checklist-updates')
            .listen('ChecklistItemToggled', (data) => {
                const toggle = document.querySelector(`#toggle_checklist_${data.checklist_id}`);
                if (toggle && toggle.checked !== data.is_active) {
                    toggle.checked = data.is_active;
                    toggle.dispatchEvent(new Event('change'));
                }
            });
    }
});
</script>

<style>
.toggle-status {
    font-weight: 600;
    min-width: 100px;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-check-input:not(:checked) {
    background-color: #dc3545;
    border-color: #dc3545;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
}
</style>
@endsection
