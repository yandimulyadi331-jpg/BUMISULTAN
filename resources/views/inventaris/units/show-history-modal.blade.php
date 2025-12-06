<div class="row">
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="mb-3">Info Unit</h5>
                
                @if($unit->foto_unit)
                <img src="{{ Storage::url($unit->foto_unit) }}" class="img-fluid rounded mb-3" alt="Foto Unit">
                @endif
                
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Kode Unit</td>
                        <td><strong>{{ $unit->kode_unit }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Inventaris</td>
                        <td>{{ $unit->inventaris->nama_barang }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kondisi</td>
                        <td><span class="badge {{ $unit->getKondisiBadgeClass() }}">{{ $unit->getKondisiLabel() }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge {{ $unit->getStatusBadgeClass() }}">{{ $unit->getStatusLabel() }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lokasi</td>
                        <td>{{ $unit->lokasi_saat_ini ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nomor Seri</td>
                        <td>{{ $unit->nomor_seri_unit ?? '-' }}</td>
                    </tr>
                </table>
                
                @if($unit->catatan_kondisi)
                <div class="alert alert-info">
                    <strong>Catatan:</strong><br>
                    {{ $unit->catatan_kondisi }}
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <h5 class="mb-3">Timeline History</h5>
        
        @if($histories->count() > 0)
        <div class="timeline-history" style="max-height: 500px; overflow-y: auto;">
            @foreach($histories as $history)
            <div class="card mb-2">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-2">
                                <span class="badge {{ $history->getJenisAktivitasBadgeClass() }} me-2">
                                    <i class="{{ $history->getJenisAktivitasIcon() }} me-1"></i>
                                    {{ $history->getJenisAktivitasLabel() }}
                                </span>
                                <small class="text-muted">
                                    <i class="ti ti-calendar"></i> {{ $history->created_at->format('d M Y H:i') }}
                                </small>
                            </div>
                            
                            <p class="mb-2"><strong>{{ $history->keterangan }}</strong></p>
                            
                            <div class="row g-2">
                                @if($history->kondisi_sebelum || $history->kondisi_sesudah)
                                <div class="col-12">
                                    <small class="text-muted">
                                        <strong>Kondisi:</strong> 
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->kondisi_sebelum ?? '-') }}</span>
                                        <i class="ti ti-arrow-right"></i>
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->kondisi_sesudah ?? '-') }}</span>
                                    </small>
                                </div>
                                @endif
                                
                                @if($history->status_sebelum || $history->status_sesudah)
                                <div class="col-12">
                                    <small class="text-muted">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->status_sebelum ?? '-') }}</span>
                                        <i class="ti ti-arrow-right"></i>
                                        <span class="badge bg-light text-dark">{{ ucfirst($history->status_sesudah ?? '-') }}</span>
                                    </small>
                                </div>
                                @endif
                                
                                @if($history->lokasi_sebelum || $history->lokasi_sesudah)
                                <div class="col-12">
                                    <small class="text-muted">
                                        <strong>Lokasi:</strong> 
                                        {{ $history->lokasi_sebelum ?? '-' }} 
                                        <i class="ti ti-arrow-right"></i> 
                                        {{ $history->lokasi_sesudah ?? '-' }}
                                    </small>
                                </div>
                                @endif
                            </div>
                            
                            @if($history->user)
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="ti ti-user"></i> Oleh: {{ $history->user->name }}
                                </small>
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-end" style="min-width: 80px;">
                            <small class="text-muted">{{ $history->formatTimeAgo() }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-1"></i> Belum ada history untuk unit ini
        </div>
        @endif
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
