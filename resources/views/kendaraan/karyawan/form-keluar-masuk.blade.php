@extends('layouts.mobile.app')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <a href="{{ route('kendaraan.karyawan.index') }}" class="btn btn-ghost-dark btn-sm mb-2">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali
                </a>
                <div class="page-pretitle">Kendaraan</div>
                <h2 class="page-title">
                    <ion-icon name="swap-horizontal" style="font-size: 1.8rem; vertical-align: middle;"></ion-icon>
                    Input Keluar/Masuk Kendaraan
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    @if($kendaraan->foto && Storage::disk('public')->exists('kendaraan/' . $kendaraan->foto))
                        <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="card-img-top" alt="{{ $kendaraan->nama_kendaraan }}">
                    @else
                        <div class="ratio ratio-1x1" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                            <ion-icon name="car-sport" style="font-size: 5rem; color: white; opacity: 0.3;"></ion-icon>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h3 class="card-title">{{ $kendaraan->nama_kendaraan }}</h3>
                        <div class="mb-2"><strong>No. Polisi:</strong> {{ $kendaraan->no_polisi }}</div>
                        <div class="mb-2"><strong>Merk:</strong> {{ $kendaraan->merk }}</div>
                        <div class="mb-2"><strong>KM Terakhir:</strong> {{ number_format($kendaraan->km_terakhir ?? 0) }} km</div>
                        <div>
                            <span class="badge bg-{{ $kendaraan->status_kendaraan == 'Tersedia' ? 'success' : ($kendaraan->status_kendaraan == 'Digunakan' ? 'warning' : 'danger') }}">
                                {{ $kendaraan->status_kendaraan }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <form action="{{ route('kendaraan.karyawan.storeKeluarMasuk', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formKeluarMasuk">
                    @csrf
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Input Keluar/Masuk</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Tipe</label>
                                    <select name="tipe" id="tipe" class="form-select" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        @if($kendaraan->status_kendaraan == 'Tersedia')
                                            <option value="Keluar">Keluar</option>
                                        @endif
                                        @if($kendaraan->status_kendaraan == 'Digunakan')
                                            <option value="Masuk">Masuk</option>
                                        @endif
                                        @if($kendaraan->status_kendaraan != 'Tersedia' && $kendaraan->status_kendaraan != 'Digunakan')
                                            <option value="Keluar">Keluar</option>
                                            <option value="Masuk">Masuk</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Pengemudi</label>
                                    <input type="text" name="pengemudi" class="form-control" value="{{ auth()->user()->name }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Waktu</label>
                                    <input type="datetime-local" name="waktu" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">KM</label>
                                    <input type="number" name="km" class="form-control" min="0" required>
                                    <small class="form-hint">KM terakhir: {{ number_format($kendaraan->km_terakhir ?? 0) }} km</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Kondisi Kendaraan</label>
                                    <select name="kondisi" class="form-select" required>
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Cukup">Cukup</option>
                                        <option value="Perlu Service">Perlu Service</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="fieldTujuan" style="display:none;">
                                    <label class="form-label">Tujuan</label>
                                    <input type="text" name="tujuan" class="form-control" placeholder="Contoh: Jakarta">
                                </div>

                                <div class="col-12 mb-3" id="fieldKeperluan" style="display:none;">
                                    <label class="form-label">Keperluan</label>
                                    <textarea name="keperluan" class="form-control" rows="3" placeholder="Jelaskan keperluan penggunaan kendaraan"></textarea>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="reset" class="btn btn-link">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <ion-icon name="save-outline"></ion-icon> Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    document.getElementById('tipe').addEventListener('change', function() {
        const tipe = this.value;
        const fieldTujuan = document.getElementById('fieldTujuan');
        const fieldKeperluan = document.getElementById('fieldKeperluan');
        
        if (tipe === 'Keluar') {
            fieldTujuan.style.display = 'block';
            fieldKeperluan.style.display = 'block';
        } else {
            fieldTujuan.style.display = 'none';
            fieldKeperluan.style.display = 'none';
        }
    });
    
    document.getElementById('formKeluarMasuk').addEventListener('submit', function(e) {
        const km = parseInt(document.querySelector('input[name="km"]').value);
        const kmTerakhir = {{ $kendaraan->km_terakhir ?? 0 }};
        const tipe = document.getElementById('tipe').value;
        
        if (tipe === 'Masuk' && km < kmTerakhir) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'KM masuk tidak boleh kurang dari KM terakhir (' + kmTerakhir.toLocaleString() + ' km)',
                confirmButtonColor: '#206bc4'
            });
            return false;
        }
    });
</script>
@endpush
@endsection
