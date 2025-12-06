@extends('layouts.mobile.app')
@section('content')

<style>
    body {
        background: #f5f7fb;
    }
    
    .header-section {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        padding: 20px;
        color: white;
        margin: -16px -16px 20px -16px;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: white;
        text-decoration: none;
        margin-bottom: 15px;
    }
    
    .vehicle-info-card {
        background: white;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .form-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #6b7280;
        box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.1);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    .btn-submit {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 900;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
    }
    
    .required {
        color: #ef4444;
    }
</style>

<div class="header-section">
    <a href="{{ route('kendaraan.karyawan.index') }}" class="back-btn">
        <ion-icon name="arrow-back-outline" style="font-size: 1.3rem;"></ion-icon>
    </a>
    <h2 style="font-weight: 900; margin: 0;">Laporan Service</h2>
    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Input Service Kendaraan</p>
</div>

<div class="vehicle-info-card">
    <div style="display: flex; align-items: center; gap: 15px;">
        @if($kendaraan->foto && Storage::disk('public')->exists('kendaraan/' . $kendaraan->foto))
            <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">
        @else
            <div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                <ion-icon name="car-sport" style="font-size: 2.5rem; color: white; opacity: 0.5;"></ion-icon>
            </div>
        @endif
        
        <div style="flex: 1;">
            <h3 style="margin: 0; font-weight: 900; font-size: 1.1rem;">{{ $kendaraan->nama_kendaraan }}</h3>
            <div style="color: #718096; margin-top: 5px;">
                <strong>No. Polisi:</strong> {{ $kendaraan->no_polisi }}<br>
                <strong>KM Terakhir:</strong> {{ number_format($kendaraan->km_terakhir ?? 0) }} km
            </div>
        </div>
    </div>
</div>

<form action="{{ route('kendaraan.karyawan.storeService', Crypt::encrypt($kendaraan->id)) }}" method="POST" enctype="multipart/form-data" id="formService">
    @csrf
    
    <div class="form-card">
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="calendar-outline"></ion-icon>
                Tanggal Service <span class="required">*</span>
            </label>
            <input type="date" name="tanggal_service" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="construct-outline"></ion-icon>
                Jenis Service <span class="required">*</span>
            </label>
            <select name="jenis_service" class="form-select" required>
                <option value="">-- Pilih Jenis Service --</option>
                <option value="Service Rutin">Service Rutin</option>
                <option value="Perbaikan">Perbaikan</option>
                <option value="Ganti Oli">Ganti Oli</option>
                <option value="Ganti Ban">Ganti Ban</option>
                <option value="Body Repair">Body Repair</option>
                <option value="Cuci">Cuci</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="business-outline"></ion-icon>
                Bengkel
            </label>
            <input type="text" name="bengkel" class="form-control" placeholder="Nama bengkel">
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="document-text-outline"></ion-icon>
                Deskripsi Pekerjaan <span class="required">*</span>
            </label>
            <textarea name="deskripsi_pekerjaan" class="form-control" placeholder="Jelaskan pekerjaan service yang dilakukan" required></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="speedometer-outline"></ion-icon>
                KM Service
            </label>
            <input type="number" name="km_service" class="form-control" min="0" placeholder="KM saat service">
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="cash-outline"></ion-icon>
                Biaya (Rp)
            </label>
            <input type="number" name="biaya" class="form-control" min="0" step="1000" placeholder="0">
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="person-outline"></ion-icon>
                Mekanik
            </label>
            <input type="text" name="mekanik" class="form-control" placeholder="Nama mekanik">
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="build-outline"></ion-icon>
                Sparepart yang Diganti
            </label>
            <textarea name="sparepart_diganti" class="form-control" rows="3" placeholder="Contoh: Oli, Filter udara, dll"></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="image-outline"></ion-icon>
                Foto Bukti
            </label>
            <input type="file" name="foto_bukti" class="form-control" accept="image/*">
            <small style="color: #718096; font-size: 0.8rem;">Format: JPG, PNG. Maksimal 2MB</small>
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <ion-icon name="chatbox-outline"></ion-icon>
                Keterangan
            </label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
        </div>
        
        <button type="submit" class="btn-submit">
            <ion-icon name="save-outline" style="font-size: 1.3rem;"></ion-icon>
            Simpan Laporan Service
        </button>
    </div>
</form>

<div style="height: 80px;"></div>

@push('myscript')
<script>
    document.getElementById('formService').addEventListener('submit', function(e) {
        const jenisService = document.querySelector('select[name="jenis_service"]').value;
        const deskripsi = document.querySelector('textarea[name="deskripsi_pekerjaan"]').value;
        
        if (!jenisService) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Jenis service harus dipilih!',
                confirmButtonColor: '#6b7280'
            });
            return false;
        }
        
        if (!deskripsi.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Deskripsi pekerjaan harus diisi!',
                confirmButtonColor: '#6b7280'
            });
            return false;
        }
    });
</script>
@endpush
@endsection
