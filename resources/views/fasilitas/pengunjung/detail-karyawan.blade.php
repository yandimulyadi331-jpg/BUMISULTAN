@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
        }

        #header-section {
            height: auto;
            padding: 20px;
            position: relative;
            background: linear-gradient(135deg, #32745e 0%, #58907D 100%);
            border-radius: 0 0 30px 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        #section-back {
            position: absolute;
            left: 15px;
            top: 15px;
        }

        .back-btn {
            color: #ffffff;
            font-size: 30px;
            text-decoration: none;
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            margin-top: 10px;
        }

        #header-title h3 {
            font-weight: bold;
            margin: 0;
        }

        #content-section {
            padding: 20px;
            margin-bottom: 80px;
        }

        .detail-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
        }

        .foto-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .foto-pengunjung {
            width: 200px;
            height: 200px;
            border-radius: 15px;
            object-fit: cover;
            border: 3px solid var(--bg-indicator);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .no-foto {
            width: 200px;
            height: 200px;
            border-radius: 15px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .nama-pengunjung {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin: 15px 0 10px 0;
        }

        .badge-status-detail {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-checkin-detail {
            background: #d4edda;
            color: #155724;
        }

        .badge-checkout-detail {
            background: #d1ecf1;
            color: #0c5460;
        }

        .info-section {
            margin-top: 20px;
        }

        .info-group {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-group:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .info-value {
            color: #333;
            font-size: 0.95rem;
            padding-left: 24px;
        }

        .section-title {
            font-weight: bold;
            color: var(--bg-indicator);
            margin: 20px 0 15px 0;
            padding-left: 10px;
            border-left: 4px solid var(--bg-indicator);
        }

        .btn-action-detail {
            padding: 12px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            width: 100%;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-checkout-detail {
            background: #ffc107;
            color: #000;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('pengunjung.karyawan.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Detail Pengunjung</h3>
        </div>
    </div>

    <div id="content-section">
        <div class="detail-card">
            <!-- Foto Section -->
            <div class="foto-section">
                @if($pengunjung->foto)
                    <img src="{{ Storage::url($pengunjung->foto) }}" alt="Foto" class="foto-pengunjung">
                @else
                    <div class="no-foto">
                        <ion-icon name="person" style="font-size: 80px; color: #999;"></ion-icon>
                    </div>
                @endif
                <h4 class="nama-pengunjung">{{ $pengunjung->nama_lengkap }}</h4>
                <span class="badge-status-detail {{ $pengunjung->status == 'checkin' ? 'badge-checkin-detail' : 'badge-checkout-detail' }}">
                    {{ $pengunjung->status == 'checkin' ? 'Check-In' : 'Check-Out' }}
                </span>
            </div>

            <!-- Data Diri -->
            <h6 class="section-title">Data Diri</h6>
            <div class="info-section">
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="key-outline"></ion-icon>
                        <span>Kode Pengunjung</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->kode_pengunjung }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="briefcase-outline"></ion-icon>
                        <span>Instansi/Perusahaan</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->instansi ?? '-' }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="card-outline"></ion-icon>
                        <span>No. Identitas</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->no_identitas ?? '-' }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="call-outline"></ion-icon>
                        <span>No. Telepon</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->no_telepon }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="mail-outline"></ion-icon>
                        <span>Email</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->email ?? '-' }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="location-outline"></ion-icon>
                        <span>Alamat</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->alamat ?? '-' }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="business-outline"></ion-icon>
                        <span>Cabang</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->cabang->nama_cabang ?? '-' }}</div>
                </div>
            </div>

            <!-- Informasi Kunjungan -->
            <h6 class="section-title">Informasi Kunjungan</h6>
            <div class="info-section">
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Keperluan</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->keperluan }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="people-outline"></ion-icon>
                        <span>Bertemu Dengan</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->bertemu_dengan ?? '-' }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="enter-outline"></ion-icon>
                        <span>Waktu Check-In</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->waktu_checkin->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="exit-outline"></ion-icon>
                        <span>Waktu Check-Out</span>
                    </div>
                    <div class="info-value">
                        @if($pengunjung->waktu_checkout)
                            {{ $pengunjung->waktu_checkout->format('d/m/Y H:i:s') }}
                        @else
                            <span style="color: #ffc107;">Belum Check-Out</span>
                        @endif
                    </div>
                </div>
                @if($pengunjung->waktu_checkout)
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>Durasi Kunjungan</span>
                    </div>
                    <div class="info-value">
                        @php
                            $durasi = $pengunjung->waktu_checkin->diff($pengunjung->waktu_checkout);
                            $jam = $durasi->h;
                            $menit = $durasi->i;
                        @endphp
                        {{ $jam }} jam {{ $menit }} menit
                    </div>
                </div>
                @endif
                @if($pengunjung->catatan)
                <div class="info-group">
                    <div class="info-label">
                        <ion-icon name="chatbox-outline"></ion-icon>
                        <span>Catatan</span>
                    </div>
                    <div class="info-value">{{ $pengunjung->catatan }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        @if($pengunjung->status == 'checkin')
            <button type="button" class="btn-action-detail btn-checkout-detail btn-checkout" 
                data-id="{{ $pengunjung->id }}" 
                data-nama="{{ $pengunjung->nama_lengkap }}">
                <ion-icon name="log-out-outline" style="font-size: 20px;"></ion-icon>
                <span>Check-Out Pengunjung</span>
            </button>
        @endif
        <a href="{{ route('pengunjung.karyawan.index') }}" class="btn-action-detail btn-back">
            <ion-icon name="arrow-back-outline" style="font-size: 20px;"></ion-icon>
            <span>Kembali ke Daftar</span>
        </a>
    </div>
@endsection

@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Checkout confirmation
        var btnCheckout = document.querySelectorAll('.btn-checkout');
        btnCheckout.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var nama = this.getAttribute('data-nama');
                
                Swal.fire({
                    title: 'Konfirmasi Check-Out',
                    html: 'Apakah Anda yakin ingin check-out pengunjung:<br><strong>' + nama + '</strong>?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Check-Out',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form and submit
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/fasilitas/pengunjung-karyawan/' + id + '/checkout';
                        
                        var token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = '{{ csrf_token() }}';
                        form.appendChild(token);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Success notification
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Error notification
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}'
            });
        @endif
    });
</script>
@endpush
