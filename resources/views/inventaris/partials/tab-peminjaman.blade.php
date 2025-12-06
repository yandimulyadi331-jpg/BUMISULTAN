<div class="row">
    <div class="col-md-12">
        <h5 class="mb-3"><i class="ti ti-list me-1"></i> Daftar Peminjaman Aktif</h5>
        
        @if($inventaris->tracking_per_unit)
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-1"></i>
            <strong>Info:</strong> Untuk melakukan peminjaman, klik tombol <strong>Peminjaman</strong> (biru) pada unit yang tersedia di tab <strong>Detail Units</strong>.
        </div>
        @endif
        
        @if($peminjamanAktif->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Peminjaman</th>
                        @if($inventaris->tracking_per_unit)
                        <th>Unit</th>
                        @endif
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamanAktif as $pinjam)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $pinjam->kode_peminjaman }}</strong></td>
                        @if($inventaris->tracking_per_unit)
                        <td>
                            @if($pinjam->detailUnit)
                                <span class="badge bg-primary">{{ $pinjam->detailUnit->kode_unit }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        @endif
                        <td>{{ $pinjam->nama_peminjam }}</td>
                        <td>{{ $pinjam->tanggal_pinjam->format('d M Y') }}</td>
                        <td>{{ $pinjam->tanggal_kembali_rencana->format('d M Y') }}</td>
                        <td>
                            @if($pinjam->tanggal_kembali_rencana < now())
                                <span class="badge bg-danger">Terlambat</span>
                            @else
                                <span class="badge bg-success">Aktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('peminjaman-inventaris.show', $pinjam->id) }}" class="btn btn-sm btn-info" title="Detail">
                                <i class="ti ti-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-1"></i> Tidak ada peminjaman aktif saat ini
        </div>
        @endif
    </div>
</div>
