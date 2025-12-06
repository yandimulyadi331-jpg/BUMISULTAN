@extends('layouts.app')
@section('titlepage', 'Riwayat Transfer Barang')

@section('content')
@section('navigasi')
    <span>
        <a href="{{ route('gedung.index') }}">Manajemen Gedung</a> / 
        <a href="{{ route('ruangan.index', Crypt::encrypt($gedung->id)) }}">{{ $gedung->nama_gedung }}</a> / 
        <a href="{{ route('barang.index', [
            'gedung_id' => Crypt::encrypt($gedung->id),
            'ruangan_id' => Crypt::encrypt($ruangan->id)
        ]) }}">{{ $ruangan->nama_ruangan }}</a> / 
        Riwayat Transfer
    </span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Riwayat Transfer Barang</h5>
                        <small class="text-muted">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</small>
                    </div>
                    <a href="{{ route('barang.index', [
                        'gedung_id' => Crypt::encrypt($gedung->id),
                        'ruangan_id' => Crypt::encrypt($ruangan->id)
                    ]) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($riwayat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Transfer</th>
                                    <th>Tanggal</th>
                                    <th>Dari</th>
                                    <th>Ke</th>
                                    <th>Jumlah</th>
                                    <th>Petugas</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat as $d)
                                    <tr>
                                        <td>{{ $loop->iteration + $riwayat->firstItem() - 1 }}</td>
                                        <td><strong>{{ $d->kode_transfer }}</strong></td>
                                        <td>{{ $d->tanggal_transfer->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="small">
                                                <strong>{{ $d->ruanganAsal->gedung->nama_gedung }}</strong><br>
                                                {{ $d->ruanganAsal->nama_ruangan }}
                                                @if($d->ruanganAsal->lantai)
                                                    <span class="badge bg-secondary">Lt.{{ $d->ruanganAsal->lantai }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <strong>{{ $d->ruanganTujuan->gedung->nama_gedung }}</strong><br>
                                                {{ $d->ruanganTujuan->nama_ruangan }}
                                                @if($d->ruanganTujuan->lantai)
                                                    <span class="badge bg-secondary">Lt.{{ $d->ruanganTujuan->lantai }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $d->jumlah_transfer }} {{ $barang->satuan }}</span>
                                        </td>
                                        <td>{{ $d->petugas ?? '-' }}</td>
                                        <td>{{ $d->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $riwayat->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="ti ti-info-circle me-2"></i>
                        Belum ada riwayat transfer untuk barang ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
