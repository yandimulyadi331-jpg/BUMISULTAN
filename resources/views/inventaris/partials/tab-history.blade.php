<div class="mb-3 d-flex justify-content-between align-items-center">
    <h5><i class="ti ti-clock me-1"></i> Timeline Aktivitas</h5>
    <button type="button" class="btn btn-sm btn-secondary" onclick="location.reload()">
        <i class="ti ti-refresh me-1"></i> Refresh
    </button>
</div>

@if($inventaris->tracking_per_unit)
    {{-- History untuk Multi-Unit (dari inventaris_unit_history) --}}
    @if($allHistory->count() > 0)
    <div class="timeline">
        @foreach($allHistory as $history)
        <div class="timeline-item mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge {{ $history->getJenisAktivitasBadgeClass() }} me-2">
                                    <i class="{{ $history->getJenisAktivitasIcon() }} me-1"></i>
                                    {{ $history->getJenisAktivitasLabel() }}
                                </span>
                                <span class="badge bg-dark">
                                    <i class="ti ti-qrcode me-1"></i>{{ $history->detailUnit->kode_unit }}
                                </span>
                            </div>
                            
                            <p class="mb-2"><strong>{{ $history->keterangan }}</strong></p>
                            
                            {{-- Detail Peminjaman --}}
                            @if($history->jenis_aktivitas === 'pinjam' && $history->peminjaman)
                            <div class="alert alert-warning mb-2 py-2">
                                <div class="row g-2">
                                    <div class="col-md-12 mb-1">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-package fs-3 me-2"></i>
                                            <div>
                                                <strong>Unit Dipinjam:</strong> {{ $history->detailUnit->kode_unit }}<br>
                                                <small class="text-muted">{{ $inventaris->nama_barang }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <small><i class="ti ti-user"></i> <strong>Peminjam:</strong> {{ $history->peminjaman->nama_peminjam ?? '-' }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><i class="ti ti-calendar"></i> <strong>Tgl Pinjam:</strong> {{ $history->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($history->peminjaman->tanggal_pinjam)->format('d M Y') : '-' }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><i class="ti ti-calendar-due"></i> <strong>Rencana Kembali:</strong> {{ $history->peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($history->peminjaman->tanggal_kembali_rencana)->format('d M Y') : '-' }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><i class="ti ti-info-circle"></i> <strong>Keperluan:</strong> {{ $history->peminjaman->keperluan ?? '-' }}</small>
                                    </div>
                                    @if(isset($history->peminjaman->lokasi_tujuan) && $history->peminjaman->lokasi_tujuan)
                                    <div class="col-md-12">
                                        <small><i class="ti ti-map-pin"></i> <strong>Lokasi Tujuan:</strong> {{ $history->peminjaman->lokasi_tujuan }}</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            {{-- Detail Pengembalian --}}
                            @if($history->jenis_aktivitas === 'kembali' && $history->pengembalian)
                            <div class="alert alert-success mb-2 py-2">
                                <div class="row g-2">
                                    <div class="col-md-12 mb-1">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-package-export fs-3 me-2"></i>
                                            <div>
                                                <strong>Unit Dikembalikan:</strong> {{ $history->detailUnit->kode_unit }}<br>
                                                <small class="text-muted">{{ $inventaris->nama_barang }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <small><i class="ti ti-calendar-check"></i> <strong>Tgl Kembali:</strong> {{ $history->pengembalian->tanggal_pengembalian ? \Carbon\Carbon::parse($history->pengembalian->tanggal_pengembalian)->format('d M Y') : '-' }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><i class="ti ti-check-circle"></i> <strong>Kondisi:</strong> 
                                            <span class="badge {{ isset($history->pengembalian->kondisi_barang) && $history->pengembalian->kondisi_barang === 'baik' ? 'bg-success' : 'bg-danger' }}">
                                                {{ isset($history->pengembalian->kondisi_barang) ? ucfirst($history->pengembalian->kondisi_barang) : '-' }}
                                            </span>
                                        </small>
                                    </div>
                                    @if(isset($history->pengembalian->catatan_pengembalian) && $history->pengembalian->catatan_pengembalian)
                                    <div class="col-md-12">
                                        <small><i class="ti ti-note"></i> <strong>Catatan:</strong> {{ $history->pengembalian->catatan_pengembalian }}</small>
                                    </div>
                                    @endif
                                    @if(isset($history->pengembalian->denda) && $history->pengembalian->denda > 0)
                                    <div class="col-md-12">
                                        <small><i class="ti ti-alert-circle"></i> <strong>Denda:</strong> <span class="text-danger">Rp {{ number_format($history->pengembalian->denda, 0, ',', '.') }}</span></small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="row g-2 mt-2">
                                @if($history->kondisi_sebelum || $history->kondisi_sesudah)
                                <div class="col-auto">
                                    <small class="text-muted">
                                        <strong>Kondisi:</strong> 
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->kondisi_sebelum ?? '-') }}</span>
                                        <i class="ti ti-arrow-right"></i>
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->kondisi_sesudah ?? '-') }}</span>
                                    </small>
                                </div>
                                @endif
                                
                                @if($history->status_sebelum || $history->status_sesudah)
                                <div class="col-auto">
                                    <small class="text-muted">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->status_sebelum ?? '-') }}</span>
                                        <i class="ti ti-arrow-right"></i>
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->status_sesudah ?? '-') }}</span>
                                    </small>
                                </div>
                                @endif
                                
                                @if($history->lokasi_sebelum || $history->lokasi_sesudah)
                                <div class="col-auto">
                                    <small class="text-muted">
                                        <strong>Lokasi:</strong> 
                                        {{ $history->lokasi_sebelum ?? '-' }} 
                                        <i class="ti ti-arrow-right"></i> 
                                        {{ $history->lokasi_sesudah ?? '-' }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-end" style="min-width: 150px;">
                            <small class="text-muted d-block">
                                <i class="ti ti-calendar"></i> {{ $history->created_at->format('d M Y H:i') }}
                            </small>
                            @if($history->user)
                            <small class="text-muted d-block">
                                <i class="ti ti-user"></i> {{ $history->user->name }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($allHistory->hasPages())
    <div class="d-flex justify-content-end mt-3">
        {{ $allHistory->links() }}
    </div>
    @endif
    @else
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-1"></i> Belum ada history aktivitas untuk unit-unit ini
    </div>
    @endif

@else
    {{-- History untuk Single Item (dari history_inventaris) --}}
    @if($allHistory->count() > 0)
    <div class="timeline">
        @foreach($allHistory as $history)
        <div class="timeline-item mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1">
                            <div class="mb-2">
                                <span class="badge bg-primary me-2">
                                    {{ ucfirst(str_replace('_', ' ', $history->jenis_aktivitas)) }}
                                </span>
                            </div>
                            <p class="mb-1"><strong>{{ $history->deskripsi }}</strong></p>
                            
                            @if($history->status_sebelum || $history->status_sesudah)
                            <small class="text-muted d-block">
                                Status: {{ $history->status_sebelum ?? '-' }} → {{ $history->status_sesudah ?? '-' }}
                            </small>
                            @endif
                            
                            @if($history->lokasi_sebelum || $history->lokasi_sesudah)
                            <small class="text-muted d-block">
                                Lokasi: {{ $history->lokasi_sebelum ?? '-' }} → {{ $history->lokasi_sesudah ?? '-' }}
                            </small>
                            @endif
                            
                            @if($history->jumlah)
                            <small class="text-muted d-block">
                                Jumlah: {{ $history->jumlah }}
                            </small>
                            @endif
                        </div>
                        
                        <div class="text-end" style="min-width: 150px;">
                            <small class="text-muted d-block">
                                {{ $history->created_at->format('d M Y H:i') }}
                            </small>
                            @if($history->user)
                            <small class="text-muted d-block">
                                {{ $history->user->name }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($allHistory->hasPages())
    <div class="d-flex justify-content-end mt-3">
        {{ $allHistory->links() }}
    </div>
    @endif
    @else
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-1"></i> Belum ada history aktivitas
    </div>
    @endif
@endif

<style>
.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 0;
    bottom: -20px;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item::after {
    content: '';
    position: absolute;
    left: -26px;
    top: 15px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #007bff;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e0e0e0;
}
</style>
