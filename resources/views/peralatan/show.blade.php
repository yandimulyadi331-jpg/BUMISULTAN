@extends('layouts.app')
@section('titlepage', 'Detail Peralatan BS')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peralatan.index') }}">PERALATAN BS</a> / Detail</span>
@endsection
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-info-circle me-2"></i> Detail Peralatan</h4>
                    <a href="{{ route('peralatan.index') }}" class="btn btn-light btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Foto Peralatan -->
                    @if($peralatan->foto)
                    <div class="col-md-4 text-center mb-3">
                        <img src="{{ Storage::url('peralatan/' . $peralatan->foto) }}" 
                             alt="{{ $peralatan->nama_peralatan }}" 
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 300px;">
                    </div>
                    @endif
                    
                    <!-- Informasi Peralatan -->
                    <div class="col-md-{{ $peralatan->foto ? '8' : '12' }}">
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
                                <td>: {{ $peralatan->kategori }}</td>
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
                                <td>: <span class="badge {{ $peralatan->isStokMenipis() ? 'bg-danger' : 'bg-success' }}">
                                    {{ $peralatan->stok_tersedia }} {{ $peralatan->satuan }}
                                </span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Stok Dipinjam</td>
                                <td>: {{ $peralatan->stok_dipinjam }} {{ $peralatan->satuan }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Stok Rusak</td>
                                <td>: {{ $peralatan->stok_rusak }} {{ $peralatan->satuan }}</td>
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
                                    @if($peralatan->kondisi == 'Baik')
                                        <span class="badge bg-success">{{ $peralatan->kondisi }}</span>
                                    @elseif($peralatan->kondisi == 'Rusak Ringan')
                                        <span class="badge bg-warning">{{ $peralatan->kondisi }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $peralatan->kondisi }}</span>
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
                        
                        <div class="mt-3">
                            <a href="{{ route('peralatan.edit', $peralatan->id) }}" class="btn btn-warning btn-sm">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdateStok">
                                <i class="ti ti-refresh me-1"></i> Update Stok
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Riwayat Peminjaman -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="ti ti-history me-2"></i> Riwayat Peminjaman</h5>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                @forelse($riwayatPeminjaman as $item)
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">{{ $item->nomor_peminjaman }}</small>
                        @if($item->status == 'dipinjam')
                            <span class="badge bg-warning">Dipinjam</span>
                        @else
                            <span class="badge bg-success">Dikembalikan</span>
                        @endif
                    </div>
                    <div><strong>{{ $item->nama_peminjam }}</strong></div>
                    <div class="small">
                        <i class="ti ti-calendar"></i> {{ $item->tanggal_pinjam->format('d/m/Y') }}
                    </div>
                    <div class="small">
                        <i class="ti ti-box"></i> {{ $item->jumlah_dipinjam }} {{ $peralatan->satuan }}
                    </div>
                </div>
                @empty
                <p class="text-center text-muted">Belum ada riwayat peminjaman</p>
                @endforelse
                
                <div class="mt-3">
                    {{ $riwayatPeminjaman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Stok -->
<div class="modal fade" id="modalUpdateStok" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-refresh me-2"></i> Update Stok Manual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('peralatan.update-stok', $peralatan->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        Stok saat ini: <strong>{{ $peralatan->stok_tersedia }} {{ $peralatan->satuan }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah Penambahan/Pengurangan *</label>
                        <input type="number" name="jumlah" class="form-control" required>
                        <small class="text-muted">Gunakan angka negatif untuk mengurangi stok</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan *</label>
                        <textarea name="keterangan" class="form-control" rows="3" required 
                            placeholder="Contoh: Pembelian baru, Rusak, Hilang, dll"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
