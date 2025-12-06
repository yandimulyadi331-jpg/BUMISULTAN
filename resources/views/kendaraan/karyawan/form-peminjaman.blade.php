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
                    <ion-icon name="calendar" style="font-size: 1.8rem; vertical-align: middle;"></ion-icon>
                    Pengajuan Peminjaman Kendaraan
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
                        <div class="mb-2"><strong>Kapasitas:</strong> {{ $kendaraan->kapasitas_penumpang }} orang</div>
                        <div class="mb-2"><strong>BBM:</strong> {{ $kendaraan->jenis_bbm }}</div>
                        <div>
                            <span class="badge bg-success">
                                {{ $kendaraan->status_kendaraan }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <form action="{{ route('kendaraan.karyawan.storePeminjaman', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formPeminjaman">
                    @csrf
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Pengajuan Peminjaman</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <ion-icon name="information-circle" class="me-2"></ion-icon>
                                Pengajuan akan diproses oleh admin. Status persetujuan dapat dilihat di menu <strong>Riwayat Peminjaman Saya</strong>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Peminjam</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">No. HP</label>
                                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Tanggal Pinjam</label>
                                    <input type="datetime-local" name="tanggal_pinjam" id="tanggalPinjam" class="form-control" min="{{ date('Y-m-d\TH:i') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Tanggal Kembali</label>
                                    <input type="datetime-local" name="tanggal_kembali" id="tanggalKembali" class="form-control" min="{{ date('Y-m-d\TH:i') }}" required>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="form-label required">Tujuan Penggunaan</label>
                                    <input type="text" name="tujuan_penggunaan" class="form-control" placeholder="Contoh: Perjalanan dinas ke Jakarta" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Jumlah Penumpang</label>
                                    <input type="number" name="jumlah_penumpang" class="form-control" min="1" max="{{ $kendaraan->kapasitas_penumpang }}">
                                    <small class="form-hint">Maks: {{ $kendaraan->kapasitas_penumpang }} orang</small>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label required">Keperluan Detail</label>
                                    <textarea name="keperluan" class="form-control" rows="4" placeholder="Jelaskan secara detail keperluan peminjaman kendaraan" required></textarea>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Keterangan Tambahan</label>
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="reset" class="btn btn-link">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <ion-icon name="paper-plane-outline"></ion-icon> Kirim Pengajuan
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
    document.getElementById('tanggalPinjam').addEventListener('change', function() {
        const tanggalPinjam = new Date(this.value);
        const tanggalKembali = document.getElementById('tanggalKembali');
        tanggalKembali.min = this.value;
        
        if (tanggalKembali.value && new Date(tanggalKembali.value) < tanggalPinjam) {
            tanggalKembali.value = '';
        }
    });
    
    document.getElementById('formPeminjaman').addEventListener('submit', function(e) {
        const tanggalPinjam = new Date(document.getElementById('tanggalPinjam').value);
        const tanggalKembali = new Date(document.getElementById('tanggalKembali').value);
        
        if (tanggalKembali < tanggalPinjam) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam',
                confirmButtonColor: '#206bc4'
            });
            return false;
        }
        
        const diffTime = Math.abs(tanggalKembali - tanggalPinjam);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays > 7) {
            e.preventDefault();
            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi',
                text: 'Durasi peminjaman lebih dari 7 hari (' + diffDays + ' hari). Apakah Anda yakin?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#206bc4'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
            return false;
        }
    });
</script>
@endpush
@endsection
