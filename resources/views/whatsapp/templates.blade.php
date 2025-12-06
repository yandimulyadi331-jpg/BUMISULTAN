@extends('layouts.app')
@section('titlepage', 'Templates - WhatsApp')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('whatsapp.index') }}">WhatsApp</a> /</span> Templates
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Message Templates</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>
                Fitur Template Library sedang dalam pengembangan. Akan segera hadir!
            </div>
            <p>Fitur yang akan tersedia:</p>
            <ul>
                <li>Template pesan dengan variable dinamis</li>
                <li>Kategori: Slip Gaji, KPI, Presensi, Pinjaman, dll</li>
                <li>Quick insert ke broadcast</li>
                <li>Media attachment support</li>
            </ul>
        </div>
    </div>
</div>
</div>
@endsection
