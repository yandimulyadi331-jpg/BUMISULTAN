<div class="mb-3">
    <button type="button" class="btn btn-primary btnTambahUnit">
        <i class="ti ti-plus me-1"></i> Tambah Unit Baru
    </button>
    <button type="button" class="btn btn-secondary" onclick="location.reload()">
        <i class="ti ti-refresh me-1"></i> Refresh
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th style="width: 50px;">No</th>
                <th>Kode Unit</th>
                <th>Nomor Seri</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Lokasi</th>
                <th>Info Peminjaman</th>
                <th style="width: 180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailUnits as $unit)
            <tr>
                <td>{{ $loop->iteration + ($detailUnits->currentPage() - 1) * $detailUnits->perPage() }}</td>
                <td>
                    <strong>{{ $unit->kode_unit }}</strong>
                    @if($unit->foto_unit)
                        <br><small class="text-muted"><i class="ti ti-photo"></i> Ada Foto</small>
                    @endif
                </td>
                <td>{{ $unit->nomor_seri_unit ?? '-' }}</td>
                <td>
                    <span class="badge {{ $unit->getKondisiBadgeClass() }}">
                        {{ $unit->getKondisiLabel() }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $unit->getStatusBadgeClass() }}">
                        {{ $unit->getStatusLabel() }}
                    </span>
                </td>
                <td>{{ $unit->lokasi_saat_ini ?? '-' }}</td>
                <td>
                    @if($unit->status === 'dipinjam')
                        <div class="text-warning">
                            <i class="ti ti-user"></i> {{ $unit->dipinjam_oleh }}<br>
                            <small><i class="ti ti-calendar"></i> {{ $unit->tanggal_pinjam ? $unit->tanggal_pinjam->format('d M Y') : '-' }}</small>
                        </div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group" role="group">
                        @if($unit->status === 'tersedia')
                        <button type="button" class="btn btn-sm btn-primary btnPinjamUnit" 
                            data-id="{{ $unit->id }}" 
                            data-kode="{{ $unit->kode_unit }}" 
                            title="Peminjaman">
                            <i class="ti ti-arrow-right"></i>
                        </button>
                        @endif
                        
                        @if($unit->status === 'dipinjam')
                        <button type="button" class="btn btn-sm btn-warning btnKembalikanUnit" 
                            data-id="{{ $unit->id }}" 
                            data-kode="{{ $unit->kode_unit }}" 
                            data-peminjam="{{ $unit->dipinjam_oleh ?? 'Unknown' }}"
                            data-tglpinjam="{{ $unit->tanggal_pinjam ? $unit->tanggal_pinjam->format('d M Y') : '-' }}"
                            title="Pengembalian">
                            <i class="ti ti-arrow-back-up"></i>
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-sm btn-info btnHistoryUnit" data-id="{{ $unit->id }}" title="History">
                            <i class="ti ti-history"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-success btnEditUnit" data-id="{{ $unit->id }}" title="Edit">
                            <i class="ti ti-edit"></i>
                        </button>
                        @if($unit->status !== 'dipinjam')
                        <button type="button" class="btn btn-sm btn-danger btnDeleteUnit" 
                            data-id="{{ $unit->id }}" 
                            data-kode="{{ $unit->kode_unit }}" 
                            title="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-sm btn-secondary" disabled title="Tidak bisa hapus saat dipinjam">
                            <i class="ti ti-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="mb-3">
                        <i class="ti ti-inbox" style="font-size: 48px; color: #ccc;"></i>
                    </div>
                    <p class="text-muted mb-3">Belum ada unit yang ditambahkan</p>
                    <button type="button" class="btn btn-primary btnTambahUnit">
                        <i class="ti ti-plus me-1"></i> Tambah Unit Pertama
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($detailUnits->hasPages())
<div class="d-flex justify-content-end mt-3">
    {{ $detailUnits->links() }}
</div>
@endif

@if($detailUnits->count() > 0)
<div class="alert alert-info mt-3">
    <i class="ti ti-info-circle me-1"></i> 
    <strong>Info:</strong> Setiap unit memiliki kode unik dan dapat di-track secara individual. 
    Unit dengan status "Dipinjam" tidak dapat dihapus atau diubah statusnya sampai dikembalikan.
</div>
@endif
