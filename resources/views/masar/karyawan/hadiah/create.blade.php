@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --primary-gradient-start: #8e44ad;
            --primary-gradient-end: #3498db;
        }

        body {
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            min-height: 100vh;
        }

        #header-section {
            height: auto;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            border-radius: 0 0 30px 30px;
            position: relative;
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

        #content-section {
            padding: 20px;
            margin-top: -20px;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-gradient-start);
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 20px;
        }

        .ukuran-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .btn-add-ukuran {
            padding: 8px 15px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .btn-remove-ukuran {
            padding: 8px 12px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('masar.karyawan.hadiah.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Tambah Hadiah Baru</h3>
            <p>MASAR</p>
        </div>
    </div>

    <div id="content-section">
        <div class="form-card">
            <form id="formHadiah" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Nama Hadiah *</label>
                    <input type="text" name="nama_hadiah" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Hadiah *</label>
                    <select name="jenis_hadiah" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        <option value="pakaian">Pakaian</option>
                        <option value="aksesoris">Aksesoris</option>
                        <option value="elektronik">Elektronik</option>
                        <option value="voucher">Voucher</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Stok Awal *</label>
                    <input type="number" name="stok_awal" class="form-control" min="1" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Detail Ukuran (Opsional)</label>
                    <button type="button" class="btn-add-ukuran" onclick="addUkuranRow()">
                        <ion-icon name="add-outline"></ion-icon> Tambah Ukuran
                    </button>
                    <div id="ukuranContainer"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Hadiah</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn-submit">
                    <ion-icon name="save-outline"></ion-icon> Simpan Hadiah
                </button>
            </form>
        </div>

        <div style="height: 80px;"></div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let ukuranIndex = 0;

function addUkuranRow() {
    let html = `
        <div class="ukuran-row" id="ukuran-${ukuranIndex}">
            <input type="text" name="ukuran[]" class="form-control" placeholder="Ukuran (S, M, L, XL)" style="flex: 1;">
            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="0" style="flex: 1;">
            <button type="button" class="btn-remove-ukuran" onclick="removeUkuranRow(${ukuranIndex})">
                <ion-icon name="trash-outline"></ion-icon>
            </button>
        </div>
    `;
    $('#ukuranContainer').append(html);
    ukuranIndex++;
}

function removeUkuranRow(index) {
    $(`#ukuran-${index}`).remove();
}

$(document).ready(function() {
    $('#formHadiah').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("masar.karyawan.hadiah.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Hadiah berhasil ditambahkan!');
                window.location.href = '{{ route("masar.karyawan.hadiah.index") }}';
            },
            error: function(xhr) {
                alert('Gagal menyimpan hadiah: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'));
            }
        });
    });
});
</script>
@endsection

