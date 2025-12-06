<div class="row">
    <div class="col-md-6 mb-3">
        <strong>Nomor Peminjaman:</strong><br>
        <span class="badge bg-primary fs-6">{{ $peminjamanPeralatan->nomor_peminjaman }}</span>
    </div>
    <div class="col-md-6 mb-3">
        <strong>Status:</strong><br>
        @if($peminjamanPeralatan->status == 'dipinjam')
            <span class="badge bg-warning fs-6">Dipinjam</span>
        @else
            <span class="badge bg-success fs-6">Dikembalikan</span>
        @endif
    </div>
</div>

<table class="table table-borderless">
    <tr>
        <td width="220"><strong>Peralatan</strong></td>
        <td>: {{ $peminjamanPeralatan->peralatan->nama_peralatan }}</td>
    </tr>
    <tr>
        <td><strong>Kode Peralatan</strong></td>
        <td>: {{ $peminjamanPeralatan->peralatan->kode_peralatan }}</td>
    </tr>
    <tr>
        <td><strong>Peminjam</strong></td>
        <td>: {{ $peminjamanPeralatan->nama_peminjam }}</td>
    </tr>
    <tr>
        <td><strong>Jumlah Dipinjam</strong></td>
        <td>: <strong>{{ $peminjamanPeralatan->jumlah_dipinjam }} {{ $peminjamanPeralatan->peralatan->satuan }}</strong></td>
    </tr>
    <tr>
        <td><strong>Keperluan</strong></td>
        <td>: {{ $peminjamanPeralatan->keperluan }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal Pinjam</strong></td>
        <td>: {{ $peminjamanPeralatan->tanggal_pinjam->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal Rencana Kembali</strong></td>
        <td>: {{ $peminjamanPeralatan->tanggal_kembali_rencana->format('d/m/Y') }}</td>
    </tr>
    @if($peminjamanPeralatan->tanggal_kembali_aktual)
    <tr>
        <td><strong>Tanggal Kembali Aktual</strong></td>
        <td>: {{ $peminjamanPeralatan->tanggal_kembali_aktual->format('d/m/Y') }}</td>
    </tr>
    @endif
    @if($peminjamanPeralatan->kondisi_saat_dipinjam)
    <tr>
        <td><strong>Kondisi Saat Dipinjam</strong></td>
        <td>: {{ $peminjamanPeralatan->kondisi_saat_dipinjam }}</td>
    </tr>
    @endif
    @if($peminjamanPeralatan->kondisi_saat_dikembalikan)
    <tr>
        <td><strong>Kondisi Saat Dikembalikan</strong></td>
        <td>: {{ $peminjamanPeralatan->kondisi_saat_dikembalikan }}</td>
    </tr>
    @endif
    @if($peminjamanPeralatan->catatan)
    <tr>
        <td><strong>Catatan</strong></td>
        <td>: {{ $peminjamanPeralatan->catatan }}</td>
    </tr>
    @endif
</table>
