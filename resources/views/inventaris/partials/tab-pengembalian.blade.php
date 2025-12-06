<div class="row">
    <div class="col-12">
        <h5 class="mb-3"><i class="ti ti-arrow-back-up me-1"></i> Riwayat Pengembalian Terakhir</h5>
        
        @if($recentPengembalian->count() > 0)
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
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Kondisi Saat Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPengembalian as $kembali)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $kembali->peminjaman->kode_peminjaman ?? '-' }}</strong></td>
                        @if($inventaris->tracking_per_unit)
                        <td>
                            @if($kembali->peminjaman && $kembali->peminjaman->detailUnit)
                                <span class="badge bg-primary">{{ $kembali->peminjaman->detailUnit->kode_unit }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        @endif
                        <td>{{ $kembali->peminjaman->nama_peminjam ?? '-' }}</td>
                        <td>{{ $kembali->peminjaman && $kembali->peminjaman->tanggal_pinjam ? $kembali->peminjaman->tanggal_pinjam->format('d M Y') : '-' }}</td>
                        <td>{{ $kembali->tanggal_pengembalian ? $kembali->tanggal_pengembalian->format('d M Y') : '-' }}</td>
                        <td>
                            @php
                                $kondisi = $kembali->kondisi_saat_kembali ?? $kembali->kondisi_barang ?? 'baik';
                            @endphp
                            <span class="badge {{ $kondisi == 'baik' ? 'bg-success' : ($kondisi == 'rusak_ringan' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $kondisi)) }}
                            </span>
                            @if(isset($kembali->ada_kerusakan) && $kembali->ada_kerusakan)
                                <br><small class="text-danger"><i class="ti ti-alert-triangle"></i> Ada kerusakan</small>
                            @endif
                        </td>
                        <td>
                            @if($kembali->denda > 0)
                                <span class="badge bg-danger">Denda: Rp {{ number_format($kembali->denda, 0, ',', '.') }}</span>
                            @else
                                <span class="badge bg-success">Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-1"></i> Belum ada riwayat pengembalian
        </div>
        @endif
        
        <div class="mt-4 text-center">
            <a href="{{ route('pengembalian-inventaris.index') }}" class="btn btn-secondary">
                <i class="ti ti-list me-1"></i> Lihat Semua Pengembalian
            </a>
        </div>
    </div>
</div>

@if($peminjamanAktif->count() > 0)
<div class="alert alert-warning mt-4">
    <i class="ti ti-alert-circle me-1"></i> 
    <strong>Catatan:</strong> Ada {{ $peminjamanAktif->count() }} peminjaman aktif yang belum dikembalikan. 
    Silakan proses pengembalian melalui menu <a href="{{ route('pengembalian-inventaris.index') }}">Pengembalian Inventaris</a>.
</div>
@endif
