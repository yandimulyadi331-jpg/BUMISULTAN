<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Nomor Distribusi</label>
        <p class="form-control-plaintext">{{ $distribusi->nomor_distribusi }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Tanggal Distribusi</label>
        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->format('d/m/Y') }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Jamaah</label>
        <p class="form-control-plaintext">
            {{ $distribusi->jamaah->nama_jamaah }}<br>
            <small class="text-muted">{{ $distribusi->jamaah->nomor_jamaah }}</small>
        </p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Hadiah</label>
        <p class="form-control-plaintext">
            {{ $distribusi->hadiah->nama_hadiah }}<br>
            <small class="text-muted">{{ $distribusi->hadiah->kode_hadiah }}</small>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold">Ukuran</label>
        <p class="form-control-plaintext">
            @if($distribusi->ukuran)
                <span class="badge bg-primary">{{ $distribusi->ukuran }}</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </p>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold">Jumlah</label>
        <p class="form-control-plaintext">
            <span class="badge bg-success">{{ $distribusi->jumlah }} pcs</span>
        </p>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold">Metode Distribusi</label>
        <p class="form-control-plaintext">{{ ucfirst($distribusi->metode_distribusi ?? 'Langsung') }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Penerima</label>
        <p class="form-control-plaintext">{{ $distribusi->penerima }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Petugas Distribusi</label>
        <p class="form-control-plaintext">{{ $distribusi->petugas_distribusi ?? '-' }}</p>
    </div>
</div>

@if($distribusi->keterangan)
<div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">Keterangan</label>
        <p class="form-control-plaintext">{{ $distribusi->keterangan }}</p>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Status</label>
        <p class="form-control-plaintext">
            @php
                $badge = $distribusi->status_distribusi == 'diterima' ? 'success' : ($distribusi->status_distribusi == 'pending' ? 'warning' : 'danger');
            @endphp
            <span class="badge bg-{{ $badge }}">{{ ucfirst($distribusi->status_distribusi) }}</span>
        </p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Tanggal Dicatat</label>
        <p class="form-control-plaintext">
            <small class="text-muted">{{ \Carbon\Carbon::parse($distribusi->created_at)->format('d/m/Y H:i') }}</small>
        </p>
    </div>
</div>
