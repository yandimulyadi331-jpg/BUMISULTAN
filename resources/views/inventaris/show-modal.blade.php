<div class="row">
    <div class="col-md-4 text-center mb-3">
        @if($inventaris->foto)
            <img src="{{ Storage::url($inventaris->foto) }}" alt="{{ $inventaris->nama_barang }}" class="img-fluid rounded" style="max-height: 250px;">
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                <i class="ti ti-photo-off" style="font-size: 48px; color: #ccc;"></i>
            </div>
        @endif
    </div>
    
    <div class="col-md-8">
        <table class="table table-borderless">
            <tr>
                <td width="30%" class="fw-bold">Kode Inventaris</td>
                <td>: {{ $inventaris->kode_inventaris }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Nama Barang</td>
                <td>: {{ $inventaris->nama_barang }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Kategori</td>
                <td>: <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $inventaris->kategori)) }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold">Cabang</td>
                <td>: {{ $inventaris->cabang ? $inventaris->cabang->nama_cabang : '-' }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Jumlah</td>
                <td>: {{ $inventaris->jumlah }} {{ $inventaris->satuan }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Lokasi</td>
                <td>: {{ $inventaris->lokasi_penyimpanan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Status</td>
                <td>: 
                    @if($inventaris->status == 'tersedia')
                        <span class="badge bg-success">Tersedia</span>
                    @elseif($inventaris->status == 'dipinjam')
                        <span class="badge bg-warning">Dipinjam</span>
                    @elseif($inventaris->status == 'rusak')
                        <span class="badge bg-danger">Rusak</span>
                    @elseif($inventaris->status == 'maintenance')
                        <span class="badge bg-info">Maintenance</span>
                    @else
                        <span class="badge bg-dark">{{ ucfirst($inventaris->status) }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold">Kondisi</td>
                <td>: 
                    @if($inventaris->kondisi == 'baik')
                        <span class="badge bg-success">Baik</span>
                    @elseif($inventaris->kondisi == 'rusak_ringan')
                        <span class="badge bg-warning">Rusak Ringan</span>
                    @else
                        <span class="badge bg-danger">Rusak Berat</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold">Deskripsi</td>
                <td>: {{ $inventaris->deskripsi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Keterangan</td>
                <td>: {{ $inventaris->keterangan ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>

<hr class="my-3">

<h5 class="mb-3">Peminjaman Aktif</h5>
@if($peminjamanAktif->count() > 0)
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamanAktif as $p)
                <tr>
                    <td>{{ $p->kode_peminjaman }}</td>
                    <td>{{ $p->karyawan->nama ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_peminjaman)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pengembalian_rencana)->format('d/m/Y') }}</td>
                    <td>{{ $p->jumlah_dipinjam }}</td>
                    <td>
                        @if($p->status_peminjaman == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($p->status_peminjaman == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($p->status_peminjaman) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-2"></i>Tidak ada peminjaman aktif saat ini.
    </div>
@endif

<div class="d-flex justify-content-end mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
