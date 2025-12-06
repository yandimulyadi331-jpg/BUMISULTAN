@extends('layouts.mobile.app')
@section('content')
<style>
    body {
        background: linear-gradient(180deg, #e3f4f9 0%, #f8fbfc 100%);
    }

    #header-section {
        padding: 25px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0 0 35px 35px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        margin-bottom: 20px;
    }

    .back-btn {
        color: #ffffff;
        font-size: 28px;
        text-decoration: none;
    }

    #header-title h3 {
        color: #ffffff;
        font-weight: 900;
        margin: 15px 0 5px 0;
        font-size: 1.8rem;
        text-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }

    #header-title p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.95rem;
        margin: 0;
    }
</style>

<!-- Header Section -->
<div id="header-section">
    <div id="section-back" style="position: absolute; left: 15px; top: 15px;">
        <a href="/dashboard" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div id="header-title" style="text-align: center;">
        <h3>Kendaraan</h3>
        <p>Daftar Kendaraan Tersedia</p>
    </div>
</div>

<!-- Content Section -->
<div style="padding: 0 15px 100px 15px;">
    <div class="card" style="border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="card-body" style="padding: 20px;">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('kendaraan.karyawan.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                    <x-input-with-icon label="Cari Nama Kendaraan" value="{{ Request('nama_kendaraan') }}" 
                                        name="nama_kendaraan" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="jenis_kendaraan" class="form-select">
                                            <option value="">Semua Jenis</option>
                                            <option value="Mobil" {{ Request('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                            <option value="Motor" {{ Request('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor</option>
                                            <option value="Truk" {{ Request('jenis_kendaraan') == 'Truk' ? 'selected' : '' }}>Truk</option>
                                            <option value="Bus" {{ Request('jenis_kendaraan') == 'Bus' ? 'selected' : '' }}>Bus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="tersedia" {{ Request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="keluar" {{ Request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                            <option value="dipinjam" {{ Request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                            <option value="service" {{ Request('status') == 'service' ? 'selected' : '' }}>Service</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Foto</th>
                                        <th>Kode</th>
                                        <th>Nama/No.Polisi</th>
                                        <th>Jenis</th>
                                        <th>Merk/Model</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kendaraan as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $kendaraan->firstItem() - 1 }}</td>
                                            <td>
                                                @if($d->foto)
                                                    <img src="{{ asset('storage/kendaraan/' . $d->foto) }}" 
                                                        alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="badge bg-secondary">No Photo</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $d->kode_kendaraan }}</strong></td>
                                            <td>
                                                <strong>{{ $d->nama_kendaraan }}</strong><br>
                                                <small class="text-muted">{{ $d->no_polisi }}</small>
                                            </td>
                                            <td>{{ $d->jenis_kendaraan }}</td>
                                            <td>
                                                {{ $d->merk ?? '-' }}<br>
                                                <small class="text-muted">{{ $d->model ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @if($d->status == 'tersedia')
                                                    <span class="badge bg-success">Tersedia</span>
                                                    @if(method_exists($d, 'perluService') && $d->perluService())
                                                        <br><span class="badge bg-warning mt-1">Perlu Service</span>
                                                    @endif
                                                @elseif($d->status == 'keluar')
                                                    <span class="badge bg-info">Sedang Keluar</span>
                                                @elseif($d->status == 'dipinjam')
                                                    <span class="badge bg-primary">Dipinjam</span>
                                                @else
                                                    <span class="badge bg-danger">Service</span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Detail Kendaraan -->
                                                <a href="{{ route('kendaraan.karyawan.detail', Crypt::encrypt($d->id)) }}?tab=aktivitas" class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $kendaraan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
    <style>
        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }
        
        #header-title {
            text-align: center;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .card {
            border: none;
        }
    </style>
@endpush
