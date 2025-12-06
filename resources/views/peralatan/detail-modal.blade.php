<div class="row">
    <!-- Foto Peralatan -->
    @if($peralatan->foto)
    <div class="col-md-3 text-center mb-3">
        <img src="{{ Storage::url('peralatan/' . $peralatan->foto) }}" 
             alt="{{ $peralatan->nama_peralatan }}" 
             class="img-fluid rounded shadow-sm foto-peralatan-detail"
             style="max-height: 300px; cursor: pointer;"
             data-foto="{{ Storage::url('peralatan/' . $peralatan->foto) }}"
             data-nama="{{ $peralatan->nama_peralatan }}"
             title="Klik untuk memperbesar">
    </div>
    @endif
    
    <!-- Informasi Peralatan -->
    <div class="col-md-{{ $peralatan->foto ? '9' : '12' }}">
        <table class="table table-borderless">
            <tr>
                <td width="200" class="fw-bold">Kode Peralatan</td>
                <td>: <span class="badge bg-primary">{{ $peralatan->kode_peralatan }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold">Nama Peralatan</td>
                <td>: <strong>{{ $peralatan->nama_peralatan }}</strong></td>
            </tr>
            <tr>
                <td class="fw-bold">Kategori</td>
                <td>: <span class="badge bg-info">{{ $peralatan->kategori }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold">Deskripsi</td>
                <td>: {{ $peralatan->deskripsi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Stok Awal</td>
                <td>: {{ $peralatan->stok_awal }} {{ $peralatan->satuan }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Stok Tersedia</td>
                <td>: <span class="badge {{ $peralatan->isStokMenipis() ? 'bg-danger' : 'bg-success' }} fs-6">
                    {{ $peralatan->stok_tersedia }} {{ $peralatan->satuan }}
                </span></td>
            </tr>
            <tr>
                <td class="fw-bold">Stok Dipinjam</td>
                <td>: <span class="badge bg-warning fs-6">{{ $peralatan->stok_dipinjam }} {{ $peralatan->satuan }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold">Stok Rusak</td>
                <td>: <span class="badge bg-danger fs-6">{{ $peralatan->stok_rusak }} {{ $peralatan->satuan }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold">Stok Minimum</td>
                <td>: {{ $peralatan->stok_minimum }} {{ $peralatan->satuan }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Lokasi Penyimpanan</td>
                <td>: {{ $peralatan->lokasi_penyimpanan }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Kondisi</td>
                <td>: 
                    @if($peralatan->kondisi == 'baik')
                        <span class="badge bg-success">Baik</span>
                    @elseif($peralatan->kondisi == 'rusak ringan')
                        <span class="badge bg-warning">Rusak Ringan</span>
                    @else
                        <span class="badge bg-danger">Rusak Berat</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold">Harga Satuan</td>
                <td>: Rp {{ number_format($peralatan->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Tanggal Pembelian</td>
                <td>: {{ $peralatan->tanggal_pembelian ? $peralatan->tanggal_pembelian->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Supplier</td>
                <td>: {{ $peralatan->supplier ?? '-' }}</td>
            </tr>
            @if($peralatan->catatan)
            <tr>
                <td class="fw-bold">Catatan</td>
                <td>: {{ $peralatan->catatan }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>
