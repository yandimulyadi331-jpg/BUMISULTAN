@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-white mb-1">
                                <i class="ti ti-clipboard-check me-2"></i>Checklist Perawatan {{ ucfirst($tipe) }}
                            </h4>
                            <p class="mb-0 small">
                                Periode: <strong>{{ $periodeKey }}</strong> | 
                                Progress: <strong>{{ $statusPeriode->total_completed }}/{{ $statusPeriode->total_checklist }}</strong>
                            </p>
                        </div>
                        <a href="{{ route('perawatan.index') }}" class="btn btn-white btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Config Status Banner --}}
    @if($config)
    <div class="row mb-3">
        <div class="col-12">
            @if(!$config->is_enabled)
                {{-- Checklist Nonaktif --}}
                <div class="alert alert-secondary d-flex align-items-start" role="alert">
                    <i class="ti ti-power fs-3 me-3 mt-1"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2">
                            <i class="ti ti-power me-1"></i>Checklist {{ ucfirst($tipe) }} Sedang Nonaktif
                        </h5>
                        <p class="mb-2">
                            Checklist ini telah dinonaktifkan oleh admin. Anda tidak perlu menyelesaikan checklist dan dapat melakukan checkout langsung.
                        </p>
                        @if($config->keterangan)
                        <div class="mt-2 p-2 bg-white rounded border border-secondary">
                            <strong>Keterangan Admin:</strong>
                            <p class="mb-0 mt-1">{{ $config->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @elseif($config->is_mandatory)
                {{-- Checklist Aktif & Wajib --}}
                <div class="alert alert-danger d-flex align-items-start" role="alert">
                    <i class="ti ti-alert-triangle fs-3 me-3 mt-1"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2">
                            <i class="ti ti-circle-check me-1"></i>Checklist {{ ucfirst($tipe) }} WAJIB Diselesaikan
                        </h5>
                        <p class="mb-2">
                            <strong>⚠️ PENTING:</strong> Anda harus menyelesaikan <strong>SEMUA</strong> item checklist di bawah ini sebelum dapat melakukan absen pulang (checkout).
                        </p>
                        @if($config->keterangan)
                        <div class="mt-2 p-2 bg-white rounded border border-danger">
                            <strong>Instruksi Admin:</strong>
                            <p class="mb-0 mt-1">{{ $config->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- Checklist Aktif & Opsional --}}
                <div class="alert alert-success d-flex align-items-start" role="alert">
                    <i class="ti ti-info-circle fs-3 me-3 mt-1"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2">
                            <i class="ti ti-list-check me-1"></i>Checklist {{ ucfirst($tipe) }} Opsional
                        </h5>
                        <p class="mb-2">
                            Checklist ini bersifat opsional. Anda tetap dapat melakukan checkout meskipun belum menyelesaikan semua item.
                        </p>
                        @if($config->keterangan)
                        <div class="mt-2 p-2 bg-white rounded border border-success">
                            <strong>Catatan Admin:</strong>
                            <p class="mb-0 mt-1">{{ $config->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Progress Card --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card {{ $statusPeriode->is_completed ? 'border-success' : 'border-warning' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-1">
                                        @if($statusPeriode->is_completed)
                                            <i class="ti ti-circle-check text-success me-2"></i>Semua Checklist Selesai!
                                        @else
                                            <i class="ti ti-hourglass-empty text-warning me-2"></i>Checklist Belum Selesai
                                        @endif
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    @php
                                        $completedItems = $logs->count();
                                        $totalItems = $masters->count();
                                        $totalPoints = $masters->sum('points') ?? 0;
                                        $earnedPoints = $logs->sum(function($log) { 
                                            return optional($log->master)->points ?? 0; 
                                        });
                                    @endphp
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="h5 mb-1">{{ $completedItems }}/{{ $totalItems }}</div>
                                                <div class="text-muted small">Checklist Selesai</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="h5 mb-1">⭐ {{ $earnedPoints }}/{{ $totalPoints }}</div>
                                                <div class="text-muted small">Points Terkumpul</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ms-3">
                            @if($statusPeriode->is_completed)
                                <button type="button" class="btn btn-success" id="btnGenerateLaporan">
                                    <i class="ti ti-file-download me-1"></i> Generate Laporan
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="ti ti-lock me-1"></i> Belum Bisa
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Checklist Content --}}
    @if($masters->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-clipboard-off" style="font-size: 4rem; color: #ccc;"></i>
                    </div>
                    <h5 class="text-muted">Belum ada checklist {{ $tipe }}</h5>
                    <p class="text-muted small">
                        Admin belum membuat template checklist untuk periode ini.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Checklist by Ruangan --}}
    @php
        $kategoriIcons = [
            'kebersihan' => '🧹',
            'perawatan_rutin' => '🔧',
            'pengecekan' => '✅',
            'lainnya' => '📋'
        ];
        $kategoriColors = [
            'kebersihan' => 'primary',
            'perawatan_rutin' => 'success',
            'pengecekan' => 'warning',
            'lainnya' => 'secondary'
        ];
    @endphp

    @if(isset($mastersByRuangan) && !empty($mastersByRuangan))
        {{-- Display with Ruangan Classification --}}
        @foreach($mastersByRuangan as $ruanganGroup)
        <div class="card mb-4 border-2" style="border-color: #e7e7ff;">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="ti ti-building me-2"></i>
                    <strong>{{ $ruanganGroup['ruangan_nama'] }}</strong>
                    <span class="badge bg-info ms-2">{{ $ruanganGroup['items']->count() }} items</span>
                </h5>
            </div>
            <div class="card-body">
                @php
                    $kategorisByRuangan = $ruanganGroup['items']->groupBy('kategori');
                @endphp
                
                @foreach($kategorisByRuangan as $kategori => $items)
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <span style="font-size: 1.3rem; min-width: 30px;">{{ $kategoriIcons[$kategori] ?? '📋' }}</span>
                        <h6 class="mb-0 ms-2">{{ ucfirst(str_replace('_', ' ', $kategori)) }}</h6>
                        <span class="badge bg-{{ $kategoriColors[$kategori] ?? 'secondary' }} ms-auto">
                            {{ $items->filter(fn($m) => isset($logs[$m->id]))->count() }}/{{ $items->count() }}
                        </span>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($items as $master)
                        @php
                            $logData = isset($logs[$master->id]) ? $logs[$master->id] : null;
                            $isChecked = $logData !== null;
                        @endphp
                        <div class="list-group-item checklist-item {{ $isChecked ? 'bg-label-success' : '' }}" data-master-id="{{ $master->id }}">
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input checklist-checkbox" 
                                           type="checkbox" 
                                           data-master-id="{{ $master->id }}" 
                                           {{ $isChecked ? 'checked' : '' }}
                                           id="checklist_{{ $master->id }}">
                                </div>
                                <label class="form-check-label flex-grow-1 cursor-pointer" for="checklist_{{ $master->id }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <strong>{{ $master->nama_kegiatan }}</strong>
                                        @if($master->points)
                                            @php
                                                $pointColor = $master->points <= 3 ? 'success' : ($master->points <= 7 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $pointColor }}">⭐ {{ $master->points }} pts</span>
                                        @endif
                                    </div>
                                    @if($master->deskripsi)
                                        <div class="small text-muted">{{ $master->deskripsi }}</div>
                                    @endif
                                    @if($master->point_description)
                                        <div class="small text-muted" style="font-style: italic;">
                                            <i class="ti ti-info-circle"></i> {{ $master->point_description }}
                                        </div>
                                    @endif
                                </label>
                                @if($isChecked)
                                <div class="ms-3">
                                    <span class="badge bg-success">
                                        <i class="ti ti-check me-1"></i>{{ $logData->tanggal_perawatan ? $logData->tanggal_perawatan->format('d/m/Y') : 'Hari ini' }}
                                    </span>
                                    @if($logData->user)
                                    <div class="small text-muted mt-1">oleh {{ $logData->user->name }}</div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @else
        {{-- Fallback: Display by Kategori only (for backward compatibility) --}}
        @php
            $kategoris = $masters->groupBy('kategori');
        @endphp

        @foreach($kategoris as $kategori => $items)
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-{{ $kategoriColors[$kategori] ?? 'secondary' }}">
                        <h5 class="card-title text-white mb-0">
                            <span class="me-2">{{ $kategoriIcons[$kategori] ?? '📋' }}</span>
                            {{ ucfirst(str_replace('_', ' ', $kategori)) }}
                            <span class="badge bg-white text-{{ $kategoriColors[$kategori] ?? 'secondary' }} ms-2">
                                {{ $items->filter(fn($m) => isset($logs[$m->id]))->count() }}/{{ $items->count() }}
                            </span>
                        </h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($items as $master)
                        @php
                            $logData = isset($logs[$master->id]) ? $logs[$master->id] : null;
                            $isChecked = $logData !== null;
                        @endphp
                        <div class="list-group-item checklist-item {{ $isChecked ? 'bg-label-success' : '' }}" data-master-id="{{ $master->id }}">
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input checklist-checkbox" 
                                           type="checkbox" 
                                           data-master-id="{{ $master->id }}" 
                                           {{ $isChecked ? 'checked' : '' }}
                                           style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0 {{ $isChecked ? 'text-success' : '' }}">{{ $master->nama_kegiatan }}</h6>
                                        @if($master->points)
                                            @php
                                                $pointColor = $master->points <= 3 ? 'success' : ($master->points <= 7 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $pointColor }}">⭐ {{ $master->points }} pts</span>
                                        @endif
                                    </div>
                                    @if($master->deskripsi)
                                        <p class="text-muted small mb-1">{{ $master->deskripsi }}</p>
                                    @endif
                                    @if($master->point_description)
                                        <p class="text-muted small mb-1" style="font-style: italic;">
                                            <i class="ti ti-info-circle"></i> {{ $master->point_description }}
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    @if($isChecked)
                                        <span class="badge bg-success">
                                            <i class="ti ti-check me-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
    @endif
</div>

@endsection

@push('styles')
<style>
    .checklist-item {
        transition: all 0.3s ease;
    }
    
    .checklist-item:hover {
        background-color: #f8f9fa;
    }
    
    .bg-label-success {
        background-color: #d4edda !important;
        border-left: 4px solid #28a745;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    const tipe = '{{ $tipe }}';
    
    $('.checklist-checkbox').on('change', function() {
        const checkbox = $(this);
        const masterId = checkbox.data('master-id');
        
        if (checkbox.is(':checked')) {
            $.ajax({
                url: '{{ route("perawatan.checklist.execute") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    master_perawatan_id: masterId,
                    tipe_periode: tipe
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function() {
                    checkbox.prop('checked', false);
                }
            });
        }
    });

    $('#btnGenerateLaporan').on('click', function() {
        if (confirm('Generate laporan untuk periode ini?')) {
            $.ajax({
                url: '{{ route("perawatan.laporan.generate") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', tipe_periode: tipe },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("perawatan.laporan.index") }}';
                    }
                }
            });
        }
    });
});
</script>
@endpush
