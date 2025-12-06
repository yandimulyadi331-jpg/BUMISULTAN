@extends('layouts.app')
@section('titlepage', 'QR Code Check-In')

@section('content')
@section('navigasi')
    <span><a href="{{ route('pengunjung.index') }}">Manajemen Pengunjung</a> / QR Code</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">QR Code untuk Check-In Pengunjung</h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <h5>Scan QR Code untuk Check-In</h5>
                    <p class="text-muted">Pengunjung dapat scan QR Code ini untuk melakukan check-in mandiri</p>
                </div>

                <div class="qr-code-container mb-4" style="padding: 30px;">
                    {!! $qrCode !!}
                </div>

                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i>
                    <strong>URL Check-In:</strong><br>
                    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                </div>

                <div class="mt-4">
                    <a href="{{ route('pengunjung.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i> Kembali
                    </a>
                    <button class="btn btn-primary" onclick="printQR()">
                        <i class="fa fa-print me-2"></i> Print QR Code
                    </button>
                    <button class="btn btn-success" onclick="downloadQR()">
                        <i class="fa fa-download me-2"></i> Download QR Code
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function printQR() {
        window.print();
    }

    function downloadQR() {
        // Get SVG element
        var svg = document.querySelector('.qr-code-container svg');
        var svgData = new XMLSerializer().serializeToString(svg);
        
        // Create canvas
        var canvas = document.createElement("canvas");
        var ctx = canvas.getContext("2d");
        
        var img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            
            // Download
            var a = document.createElement("a");
            a.download = "qrcode-checkin-pengunjung.png";
            a.href = canvas.toDataURL("image/png");
            a.click();

            // Success toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: 'QR Code berhasil didownload!'
            });
        };
        
        img.src = "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(svgData)));
    }
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .qr-code-container, .qr-code-container * {
            visibility: visible;
        }
        .qr-code-container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    }
</style>
@endpush
